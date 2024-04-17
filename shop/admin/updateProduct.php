<?php
    require '../db.php'; 

    $ProductID = $_GET["ID"];
    $query = "SELECT * FROM Product WHERE ProductID = '$ProductID';";

    $statement = $pdo->prepare($query);
    $statement->execute();

    if($statement->rowCount() > 0) {
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    

     

    $row = $products[0];
    echo '<form method="POST" action="saveUpdate.php">';
    echo "<h1>รหัสสินค้า คือ $ProductID <br></h1>";
    echo '<h4><input type="Hidden" name="ProductID" value="' . $ProductID . '" >';
    echo "กรอกข้อมูลใหม่ที่ต้องการแก้ <br>";
    echo '  ProductName
            <input type="text" name="ProductName" size="20" maxlength="20" value="' . $row['ProductName'] . '"><br>
            
            PricePerUnit
            <input type="text" name="PricePerUnit" size="10" maxlength="10" value="' . $row['PricePerUnit'] . '"><br>
            
            Detail
            <input type="text" name="Detail" size="40" maxlength="40" value="' . $row['Detail'] . '"><br>
            
            <input type="submit" value="ยืนยัน">
            <input type="reset" value="รีเซ็ต"> ';
    echo "</h4>";
    echo '</form>';
?>

