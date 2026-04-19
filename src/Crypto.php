<?php

/**
 * Handle encryption functionalities using openssl encrypt
 */

namespace KrisRo\PhpRepublic;

use KrisRo\PhpConfig\Config;

class Crypto {

  /**
   * Encrypt data using oenssl_encrypt
   *
   * @param string $data
   * @return string
   */
  public static function encrypt(string $data): string {
    return openssl_encrypt(
			$data,
			Config::get('crypto/cipher'),
			Config::get('crypto/key'),
			0, // options parameter
			hex2bin(Config::get('crypto/iv'))
		);
  }

  /**
   * Decrypt data using oenssl_decrypt
   *
   * @param string $data
   * @return string
   */
  public static function decrypt( string $data): string {
    return openssl_decrypt(
			$data,
			Config::get('crypto/cipher'),
			Config::get('crypto/key'),
			0, // options parameter
			hex2bin(Config::get('crypto/iv'))
		);
  }

  /**
   * Generate cryptographically secure pseudo-random bytes
   * and converts it to hex
   *
   * @param int $length
   *  This is binary length, not hex
   *
   * @return string
   */
  public static function generateToken(int $length = 64): string {
    return bin2hex(random_bytes($length));
  }

  public static function generateOpenSslIv() {
    $ivLength = openssl_cipher_iv_length(Config::get('crypto/cipher'));
    $iv = bin2hex(openssl_random_pseudo_bytes($ivLength));

    return $iv;
  }

  public static function generateEncryptionKey() {
    $privateKey = openssl_pkey_new();
    $publicKeyPem = openssl_pkey_get_details($privateKey)['key'];

    return base64_encode($publicKeyPem);
  }
}
