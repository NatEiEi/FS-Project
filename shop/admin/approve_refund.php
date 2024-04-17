<?php
    // Include database connection file
    require '../db.php'; 
    date_default_timezone_set('Asia/Bangkok');

    if (isset($_POST['approve']) && isset($_FILES['imageUpload'])) {
        $OrderID = $_POST['OrderID'];
        
        $query = "UPDATE order_refund_detail SET received_at = NOW() , refund_status = 'approve' WHERE OrderID = '$OrderID'";
        $statement = $pdo->prepare($query);
        $statement->execute();

        $query = "UPDATE `order` SET Status = '80' WHERE OrderID = '$OrderID'";
        $statement = $pdo->prepare($query);
        $statement->execute();


        // Save image
        $file_name = $_FILES['imageUpload']['name'];
        $file_tmp = $_FILES['imageUpload']['tmp_name'];
        $OrderID = $_POST['OrderID'];
        $newName = "refund_slip_" . $OrderID . "." . "jpg";
    
        move_uploaded_file($file_tmp,"../images/refund_slip/".$newName);

        // เพิ่มจำนวนสินค้าแทนใน stock
        $query = "SELECT * FROM `productlist` WHERE OrderID = '$OrderID'";
        $statement = $pdo->prepare($query);
        $statement->execute();
        if($statement->rowCount() > 0) {
            $productlist = $statement->fetchAll(PDO::FETCH_ASSOC);
        }
                
        foreach ($productlist as $list) {
            $query = "SELECT * FROM product WHERE ProductID='{$list['ProductID']}'";
            $statement = $pdo->prepare($query);
            $statement->execute();
            if($statement->rowCount() > 0) {
                $prod = $statement->fetchAll(PDO::FETCH_ASSOC);
            }

            // เพิ่มจำนวนสินค้าใน stock
            $newStock = $prod[0]['QtyStock'] + $list['Qty'];
            $query = "UPDATE product SET QtyStock = '$newStock' WHERE ProductID = '{$list['ProductID']}'";
            $statement = $pdo->prepare($query);
            $statement->execute();
        }



        // Redirect back to the page
        // header("Location: order_management.php?OrderID=" . $OrderID);
        // exit();

        echo "<script>alert('ยกเลิกรายการสั่งซื้อสำเร็จ'); window.location='order_management.php?OrderID=$OrderID';</script>";
        
    } elseif (isset($_POST['reject'])) {
        $OrderID = $_POST['OrderID'];
        $query = "UPDATE order_refund_detail SET received_at = NOW() , refund_status = 'reject' WHERE OrderID = '$OrderID'";
        $statement = $pdo->prepare($query);
        $statement->execute();

        $query = "SELECT previous_status FROM `order_refund_detail` WHERE OrderID = '$OrderID'";
        $statement = $pdo->prepare($query);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            $orders = $statement->fetchAll(PDO::FETCH_ASSOC);
            $previous_status = $orders[0]['previous_status'];
        }  

        $query = "UPDATE `order` SET Status = '$previous_status' WHERE OrderID = '$OrderID'";
        $statement = $pdo->prepare($query);
        $statement->execute();
        // Redirect back to the page
        echo "<script>alert('ยกเลิกรายการสั่งซื้อสำเร็จ'); window.location='order_management.php?OrderID=$OrderID';</script>";
    }
?>
