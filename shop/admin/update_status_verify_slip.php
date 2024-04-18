<?ob_start();?>
<?php
    require '../db.php'; 

    $OrderID = $_GET['OrderID'];
    $Verify = $_GET['Verify'];

    // ถ้าตรวจสอบแล้วผ่านให้เปลี่ยนสถานะเป็น เตรียมจัดส่ง
    if ($Verify === "True") {
        $query =    "UPDATE `order` SET Status = '30'
                    WHERE OrderID = '$OrderID'";

        $statement = $pdo->prepare($query);
        $statement->execute();
        echo "<script>alert('เข้าสู่กระบวนการเตรียมจัดส่ง'); window.location='order_management.php?OrderID=$OrderID';</script>";
    } else {
    // ถ้าตรวจสอบแล้ว "ไม่ผ่าน" ให้เปลี่ยนสถานะเป็น รอการชำระเงิน
        $query =    "UPDATE `order` SET Status = '10'
        WHERE OrderID = '$OrderID'";

        $statement = $pdo->prepare($query);
        $statement->execute();

        $slipPath = "../images/slip/" . $OrderID . ".jpg" ;

        // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
        if (file_exists($slipPath)) {
            // ลบไฟล์
            if (unlink($slipPath)) {
                echo 'ไฟล์ถูกลบเรียบร้อยแล้ว';
            } else {
                echo 'ไม่สามารถลบไฟล์ได้';
            }
        } else {
            echo 'ไฟล์ไม่มีอยู่จริง';
        }
        echo "<script>alert('หลักฐานการโอนเงินไม่ถูกต้อง'); window.location='order_management.php?OrderID=$OrderID';</script>";
    }
?>

