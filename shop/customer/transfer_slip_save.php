<?ob_start();?>
<?php
    require_once __DIR__ . '/db.php'; 

    if(isset($_FILES['imageUpload'])) {
        $file_name = $_FILES['imageUpload']['name'];
        $file_tmp = $_FILES['imageUpload']['tmp_name'];
        $OrderID = $_POST['OrderID'];

        $newName = $OrderID . "." . "jpg";

        move_uploaded_file($file_tmp,"../images/slip/".$newName);
        $query =    "UPDATE `order` SET Status = '20'
                    WHERE OrderID = '$OrderID'";

        $statement = $pdo->prepare($query);
        $statement->execute();

        echo "<script>alert('ส่งหลักฐานการชำระเงินสำเร็จ'); window.location='order_detail.php?OrderID=$OrderID';</script>";
    }
        
    
?>