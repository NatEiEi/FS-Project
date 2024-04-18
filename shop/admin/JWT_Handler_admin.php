<?ob_start();?>
<?php

    require_once '../libraries/jwt/vendor/autoload.php'; 
    use \Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    $secret_key = "your_secret_key";
    
    class JWT_Handler_admin {

        public function get_key() {
            global $secret_key;
            return $secret_key;
        }

        public function encode($EmployeeID , $Role ) {
            global $secret_key;
            $payload = array(
                "EmployeeID" => $EmployeeID,
                "Role" => $Role
            );
            $jwt = JWT::encode($payload, $secret_key, 'HS256');
            setcookie("admin", $jwt, time() + 6000 , "/", "", false, true);
        }

        public function decode() {
            global $secret_key;
            $jwt = $_COOKIE['admin'];
            $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
            return $decoded;
        }
    }

?>