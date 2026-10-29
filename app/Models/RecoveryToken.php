<?php

namespace App\Models;

use KrisRo\PhpRepublic\Authenticate;
use KrisRo\PhpRepublic\Messages;
use KrisRo\PhpRepublic\Translate;
use KrisRo\PhpRepublic\Crypto;
use KrisRo\PhpConfig\Config;

class RecoveryToken extends \KrisRo\PhpRepublic\Model {

  public function setRecoveryToken(array $token): int {
    return $this->db->setRecovery_tokenAndGetId([
      'fingerprint' => $token['fingerprint'],
      'random_nonce' => $token['random_nonce'],
      'expires' => $token['expires'],
    ]);
  }

  public function getRecoveryTokenByFingerprint(string $fingerprint): array|null {
    return $this->db->getAssocRecovery_tokenByFingerprint($fingerprint)->next();
  }

  public function getRecoveryPasswordCode(array $user) {
    $cipher = Config::get('crypto/cipher');
    $nonce = Crypto::generateToken(16);

    $date = new \DateTimeImmutable();
    $expiry = $date->modify('+10 minute');

    $payload = json_encode([
      'uid' => $user['id'],
      'expire' => $expiry->getTimestamp(),
      'nonce' => $nonce
    ]);

    $ivLenght = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivLenght);

    $cipherText = openssl_encrypt($payload, $cipher, Config::get('crypto/key'), OPENSSL_RAW_DATA, $iv, $tag);
    $token = rtrim(strtr(base64_encode($iv . $tag . $cipherText), '+/', '-_'), '=');

    $this->setRecoveryToken([
      'fingerprint' => hash('sha256', $token),
      'random_nonce' => $nonce,
      'expires' => $expiry->format('Y-m-d H:i:s'),
    ]);

    return $token;
  }

  public function decodeToken(string $token): array|bool {
    $fingerprint = hash('sha256', $token);

    // add padding if necesary
    $remainder = strlen($token) % 4;

    if ($remainder) {
        $padLength = 4 - $remainder;
        $token .= str_repeat('=', $padLength);
    }

    $decodedToken = base64_decode(strtr($token, '-_', '+/'));

    $cipher = Config::get('crypto/cipher');

    $ivLength = openssl_cipher_iv_length($cipher);
    $iv = substr($decodedToken, 0, $ivLength);

    $tag = substr($decodedToken, $ivLength, 16);

    $cipherText = substr($decodedToken, $ivLength + 16);

    $payload = openssl_decrypt($cipherText, $cipher, Config::get('crypto/key'), OPENSSL_RAW_DATA, $iv, $tag);

    if ($payload === false) {
      // Decryption failed or data was tampered with
      return false;
    }

    $arrayPayload = json_decode($payload, true);

    // Check internal expiry
    if ($arrayPayload['expire'] < time()) {
      // Expired
      return false;
    }

    if (!($this->getRecoveryTokenByFingerprint($fingerprint))) {
      return false;
    }

    return $arrayPayload;
  }
}