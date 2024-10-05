<?php 
header('Content-Type: application/json');
require '../vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

    class Crypto {
        public static function Encrypt($data) {
            $key = $_ENV['ENCRYPTION_KEY'];
            $iv = $_ENV['ENCRYPTION_IV'];
            if (empty($key) || empty($iv)) {
                // throw new Exception('Encryption key or IV is missing.');
                $response = ['success' => false, 'message' => 'Encryption key or IV is missing.'];
            }
            $encrypted = openssl_encrypt($data, 'AES-128-CBC', $key, 0, $iv);
            return $encrypted;
        }
        public static function Decrypt($encrypted) {
            $key = $_ENV['ENCRYPTION_KEY'];
            $iv = $_ENV['ENCRYPTION_IV'];
            if (empty($key) || empty($iv)) {
                // throw new Exception('Encryption key or IV is missing.');
                $response = ['success' => false, 'message' => 'Encryption key or IV is missing.'];
            }
            $decrypted = openssl_decrypt($encrypted, 'AES-128-CBC', $key, 0, $iv);
            return $decrypted;
        }
    }

    $response = ['success' => false, 'message' => 'Invalid request'];
    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST['data'] ?? null;
            $type = $_POST['type'] ?? null;
    
            if ($data === null || $type === null) {
                $response = ['success' => false, 'message' => 'Missing required parameters.'];
            }
    
            if (strtoupper($type) === 'ENCRYPT') {
                $encrypted = Crypto::Encrypt($data);
                $response = ['success' => true, 'encrypted' => $encrypted];
            } elseif (strtoupper($type) === 'DECRYPT') {
                $decrypted = Crypto::Decrypt($data);
                $response = ['success' => true, 'decrypted' => $decrypted];
            } else {
                // throw new Exception('Invalid operation type.');
                $response = ['success' => false, 'message' => 'Invalid operation type.'];
            }
        } else {
            // throw new Exception('Invalid request method.');
            $response = ['success' => false, 'message' => 'Invalid request method.'];
        }
    } catch (Exception $e) {
        $response = ['success' => false, 'message' => $e->getMessage()];
    }
    echo json_encode($response);
?>