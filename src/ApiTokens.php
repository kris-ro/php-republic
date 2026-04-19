<?php

namespace KrisRo\PhpRepublic;

class ApiTokens {

  private $key;
  private $cipher = 'AES-256-GCM';
  private $nonce;

  public function __construct() {}

  /**
   * Set the encryption key
   *
   * @param string $encryptionKey - 32 bytes long
   * @return self
   */
  public function setEncryptionKey(string $encryptionKey): self {
    // Key should be 32 bytes for AES-256
    $this->key = $encryptionKey;

    return $this;
  }

  public function getNonce() {
    return $this->nonce;
  }

  /**
   * Generate an encrypted token string
   * Packaged as: IV (12 bytes) + Tag (16 bytes) + CipherText
   *
   * @param string|int $userId
   * @param int $expiryMinutes
   *
   * @return string
   */
  public function createToken(string|int $userId, int $expiry): string {
    // 32 chars in hex
    $this->nonce = Crypto::generateToken(16);

    $payload = json_encode([
      'uid' => $userId,
      'expire' => $expiry,
      'nonce' => $this->nonce
    ]);

    $ivLenght = openssl_cipher_iv_length($this->cipher);
    $iv = openssl_random_pseudo_bytes($ivLenght);

    // Encrypt and get the authentication tag
    $cipherText = openssl_encrypt($payload, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv, $tag);

    // Package as: IV (12 bytes) + Tag (16 bytes) + Ciphertext
    // This makes the string "self-contained" for decryption
    return base64_encode($iv . $tag . $cipherText);
  }

  /**
   * Decrypt and validate the token structure
   *
   * @param string $token
   * @return array|false
   */
  public function decryptToken(string $token): array|false {
    $data = base64_decode($token);
    $ivLength = openssl_cipher_iv_length($this->cipher);

    $iv = substr($data, 0, $ivLength);

    // GCM tag is 16 bytes
    $tag = substr($data, $ivLength, 16);

    $cipherText = substr($data, $ivLength + 16);

    $payload = openssl_decrypt($cipherText, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv, $tag);

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

    return $arrayPayload;
  }

  /**
   * List expiration interval options
   * or generate expiration date/time for a specified interval
   *
   * @param string|null $type
   * @param string|null $code
   * @return array
   */
  public static function expirationIntervals(string|null $type = 'code', ?string $code = null) {
    if ($type == 'code') {
      return [
        '1H' => 'One hour',
        '1D' => 'One day',
        '1M' => 'One month',
        '3M' => 'Three months',
        '6M' => 'Six months',
        '1Y' => 'One year',
        '10Y' => 'Ten years',
      ];
    }

    $date = new \DateTimeImmutable();

    switch ($code) {
      case '1H':
        return ($date->modify('+60 minute'));
      case '1D':
        return ($date->modify('+24 hour'));
      case '1M':
        return ($date->modify('+1 month'));
      case '3M':
        return ($date->modify('+3 month'));
      case '6M':
        return ($date->modify('+6 month'));
      case '1Y':
        return ($date->modify('+1 year'));
      case '10Y':
        return ($date->modify('+10 year'));
    }
  }
}
