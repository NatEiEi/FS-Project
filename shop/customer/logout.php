<?php
    require_once __DIR__ . '/db.php'; 
    require_once 'JWT_Handler.php'; 
    session_start();


    if (isset($_COOKIE['JWT'])) {
        $JWT = new JWT_Handler();
        $Username = $JWT->decode()->Username;
        $escaped_Username = htmlspecialchars($Tel, ENT_QUOTES, 'UTF-8');

        date_default_timezone_set('Asia/Bangkok');
        $query = "INSERT INTO log (Date, Username, Action) VALUES (NOW(), '$escaped_Username', 'Logout')";
        $statement = $pdo->prepare($query);
        $statement->execute();
    }
    
    setcookie("JWT", null, -1, "/");
    unset($_SESSION['CartArray']);
    unset($_SESSION['CartCount']);
    session_destroy();
    header('Location: home.php');
?>