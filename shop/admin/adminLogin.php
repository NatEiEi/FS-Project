<?ob_start();?>
<?php
    require '../db.php'; 

    session_start();

    if(isset($_COOKIE['admin'])) {
        include __DIR__ . '/adminNavbar.php';
    }else {
        header('Location: adminAuthen.php');
    }

?>

