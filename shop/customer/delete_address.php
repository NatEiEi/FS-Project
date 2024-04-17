<?php
    require_once __DIR__ . '/db.php'; 
    require_once 'JWT_Handler.php'; 
    include('db_thailand.php');

    if (isset($_GET['AddressID'])) {
        $AddressID = $_GET['AddressID'];
        if (isset($_COOKIE['JWT'])) {
            $JWT = new JWT_Handler();
            $Username = $JWT->decode()->Username;


            $query =    "UPDATE address SET Username = 'GUEST'
                        WHERE AddressID = '$AddressID' AND Username = '$Username'";
            $statement = $pdo->prepare($query);
            $statement->execute();
        }

        header('Location: profile.php?Page=Address');
        exit();
    }
?>
