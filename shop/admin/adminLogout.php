<?php
    require '../db.php'; 
    require_once 'JWT_Handler_admin.php'; 

    session_start();

    
    // if (isset($_SESSION['CustID'])) {
    //     date_default_timezone_set('Asia/Bangkok');
    //     $CustID = $_SESSION['CustID'];
    //     $query = "INSERT INTO log (Date, CustID, Action) VALUES (NOW(), '$CustID', 'Logout')";
    //     $statement = $pdo->prepare($query);
    //     $statement->execute();
    // }
    
    if (isset($_COOKIE['admin'])) {
        $JWT = new JWT_Handler_admin();
        $EmployeeID = $JWT->decode()->EmployeeID;

     
    }
    setcookie("admin", null, -1, "/");

    unset($_SESSION['EmployeeID']);
    unset($_SESSION['FName']);
    unset($_SESSION['LName']);
    unset($_SESSION['Role']);
    session_destroy();
    header('Location: selectProduct.php');
?>