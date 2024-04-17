<?php
    require '../db.php'; 

    $OrderID = $_GET['OrderID'];
    $TrackingNumber = $_GET["TrackingNumber"];

    date_default_timezone_set('Asia/Bangkok');

    $query =    "UPDATE `order` SET Status = '40'
            WHERE OrderID = '$OrderID'";
    $statement = $pdo->prepare($query);
    $statement->execute();

    $query =    "UPDATE order_shipping_detail SET TrackingNumber = '$TrackingNumber'
            WHERE OrderID = '$OrderID'";
    $statement = $pdo->prepare($query);
    $statement->execute();
    
    echo "เริ่มขนส่ง";
    echo "<script>alert('รายการสั่งซื้อนี้เข้าสู่กระบวนการขนส่ง'); window.location='order_management.php?OrderID=$OrderID';</script>";
?>
