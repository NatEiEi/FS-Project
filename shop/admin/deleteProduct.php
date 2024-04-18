<?ob_start();?>
<?php
    require '../db.php'; 
    include __DIR__ . '/adminLogin.php';


    $ProductID = $_GET["ProductID"];
    $query = "DELETE FROM Product WHERE ProductID = '$ProductID'";
    $statement = $pdo->prepare($query);
    $statement->execute();

    echo "ลบสำเร็จเรียบร้อยแล้ว...";
    echo "<script>alert('ลบสำเร็จเรียบร้อยแล้ว'); window.location='selectProduct.php';</script>";
?>
