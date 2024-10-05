<?php
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Dotenv\Dotenv;

class JwtHandler {
    private static $secretKey;
    private static $issuer;
    private static $audience;
    private $issuedAt;
    private $expiration;

    public function __construct() {
        self::initialize();
        $this->issuedAt = time();
        $this->expiration = $this->issuedAt + (int)$_ENV['EXPIRATION'];
    }
    private static function initialize() {
        if (self::$secretKey === null) {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
            self::$secretKey = $_ENV['SECRET_KEY'];
            self::$issuer = $_ENV['ISSUER'];
            self::$audience = $_ENV['AUDIENCE'];
        }
    }

    public function generateToken($payload) {
        $tokenPayload = array_merge($payload, [
            'iss' => self::$issuer,
            'aud' => self::$audience,
            'iat' => $this->issuedAt,
            'exp' => $this->expiration,
        ]);
        return JWT::encode($tokenPayload, self::$secretKey, 'HS256');
    }

    public static function decodeToken($jwt) {
        self::initialize();
        try {
            $decoded = JWT::decode($jwt, new Key(self::$secretKey, 'HS256'));
            return (array) $decoded;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function isTokenExpired($jwt) {
        $decodedToken = self::decodeToken($jwt);
        if ($decodedToken && isset($decodedToken['exp'])) {
            return $decodedToken['exp'] < time();
        }
        return true;
    }

    public static function gatewayInit($token) {
        $decodedToken = self::decodeToken($token);
        if (!$decodedToken || self::isTokenExpired($token)) {
            return false;
        } else { return true;}
    }
}
