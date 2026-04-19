<?php

namespace KrisRo\PhpRepublic;

use KrisRo\PhpConfig\Config;

class OpenSSL {

  public static function encrypt(string $data): string {
    return openssl_encrypt(
			$data,
			Config::get('openssl/cipher'),
			Config::get('openssl/key'),
			0, // options parameter
			hex2bin(Config::get('openssl/iv'))
		);
  }

  public static function decrypt( string $data): string {
    return openssl_decrypt(
			$data,
			Config::get('openssl/cipher'),
			Config::get('openssl/key'),
			0, // options parameter
			hex2bin(Config::get('openssl/iv'))
		);
  }
}
