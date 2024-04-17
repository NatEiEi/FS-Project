<?php
    require '../db.php'; 
    $OrderID = $_GET["OrderID"];

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

        // ลดจำนวนสินค้าใน stock
        $newStock = $prod[0]['QtyStock'] + $list['Qty'];
        $query = "UPDATE product SET QtyStock = '$newStock' WHERE ProductID = '{$list['ProductID']}'";
        $statement = $pdo->prepare($query);
        $statement->execute();
    }

        
    
    $query =    "UPDATE `Order` SET Status = '60'
                WHERE OrderID = '$OrderID'";
    $statement = $pdo->prepare($query);
    $statement->execute();
    
    header('Location: order_detail.php?OrderID=' . $OrderID);
    echo "<script>alert('ยกเลิกรายการสั่งซื้อสำเร็จ'); window.location='order_detail.php?OrderID=$OrderID';</script>";
 
?>

