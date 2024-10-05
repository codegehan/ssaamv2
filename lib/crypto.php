<?php
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
class Crypto {
    public static function Encrypt($data) {
        $key = $_ENV['ENCRYPTION_KEY'];
        $iv = $_ENV['ENCRYPTION_IV'];
        // Encrypt data
        $encrypted = openssl_encrypt($data, 'AES-128-CBC', $key, 0, $iv);
        // URL-encode the encrypted data
        $encrypted = urlencode($encrypted);
        return $encrypted;
    }

    public static function Decrypt($encrypted) {
        $key = $_ENV['ENCRYPTION_KEY'];
        $iv = $_ENV['ENCRYPTION_IV'];
        // URL-decode the encrypted data
        $encrypted = urldecode($encrypted);
        // Decrypt data
        $decrypted = openssl_decrypt($encrypted, 'AES-128-CBC', $key, 0, $iv);
        return $decrypted;
    }
}
?>
