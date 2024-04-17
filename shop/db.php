<?php
    $host="host.docker.internal"; 
    $user="root"; 
    $password=""; 
    $db="shopdb"; 

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    } catch (PDOException $e) {
        die("DB ERROR: ". $e->getMessage());
    }
?>