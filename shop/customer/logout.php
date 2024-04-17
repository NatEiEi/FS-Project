<?php
    require_once __DIR__ . '/db.php'; 
    require_once 'JWT_Handler.php'; 
    session_start();


    if (isset($_COOKIE['JWT'])) {
        $JWT = new JWT_Handler();
        $Username = $JWT->decode()->Username;

        date_default_timezone_set('Asia/Bangkok');
        $query = "INSERT INTO log (Date, Username, Action) VALUES (NOW(), '$Username', 'Logout')";
        $statement = $pdo->prepare($query);
        $statement->execute();
    }
    
    setcookie("JWT", null, -1, "/");
    unset($_SESSION['CartArray']);
    unset($_SESSION['CartCount']);
    session_destroy();
    // header('Location: home.php');
    echo "<script>alert('ออกจากระบบเรียบร้อย'); window.location='Home.php';</script>";
?>