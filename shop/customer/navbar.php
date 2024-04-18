<?ob_start();?>
<?php
    require_once 'JWT_Handler.php'; 
    if(isset($_COOKIE['JWT'])) {
        include __DIR__ . '/navbar_login.php';
    } else {
        include __DIR__ . '/navbar_guest.php';
    }
?>