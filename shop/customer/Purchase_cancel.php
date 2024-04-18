<?ob_start();?>
<?php
    unset($_SESSION['ProductBuyList']);
    header('Location: Home.php');
    exit;
?>