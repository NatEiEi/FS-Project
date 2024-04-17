<?php

    require_once '../libraries/jwt/vendor/autoload.php'; 
    use \Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    $secret_key = "your_secret_key";
    
    class JWT_Handler {

        public function get_key() {
            global $secret_key;
            return $secret_key;
        }

        public function encode($Username , $FName , $LName) {
            global $secret_key;
            $payload = array(
                "Username" => $Username,
                "FName" => $FName,
                "LName" => $LName,
            );
            $jwt = JWT::encode($payload, $secret_key, 'HS256');
            setcookie("JWT", $jwt, time() + 6000 , "/", "", false, true);
        }

        public function decode() {
            global $secret_key;
            $jwt = $_COOKIE['JWT'];
            $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
            return $decoded;
        }
    }

?>