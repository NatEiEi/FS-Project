<?php
    require '../db.php'; 
    include __DIR__ . '/adminLogin.php';

    $ProductID = $_POST["ProductID"];
    // $QtyStock = $_POST["QtyStock"];

    $query = "SELECT * FROM Product WHERE ProductID = '$ProductID'";
    $statement = $pdo->prepare($query);
    $statement->execute();
    if($statement->rowCount() > 0) {
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);
        $QtyStock = $products[0]['QtyStock'];
    }

    if (isset($_POST['addButton'])) {
        $qty = $_POST["qty"];
        $NewQtyStock = $qty + $QtyStock;
    } elseif (isset($_POST['deleteButton'])) {
        $qty = $_POST["qty"];
        if (($QtyStock - $qty) < 0) {
            echo "<script>alert('ไม่สามารถลบจำนวนให้น้อยกว่า 0 ได้'); window.location='stock_manage.php';</script>";
        } else {
            $NewQtyStock = $QtyStock - $qty;
        }
    }

    $query =    "UPDATE Product SET QtyStock = '$NewQtyStock'
                WHERE ProductID = '$ProductID'";
    $statement = $pdo->prepare($query);
    $statement->execute();
    echo "Updated Successfully...";
    echo "<script>alert('อัพเดทจำนวนสินค้าสำเร็จ'); window.location='stock_manage.php';</script>";
    
?>
