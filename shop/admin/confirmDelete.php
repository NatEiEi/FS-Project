<?ob_start();?>
<?php
    require '../db.php'; 
    include __DIR__ . '/adminLogin.php';


    $ProductID = $_GET["ID"];
    $query = "SELECT * FROM Product WHERE ProductID = '$ProductID';";

    $statement = $pdo->prepare($query);
    $statement->execute();

    if($statement->rowCount() > 0) {
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    echo "
        <br><br>
        <table border='1' style='width: 60%; margin: 0 auto;'>
        <tr>
            <th style='width: 15%;'>Product ID</th>
            <th style='width: 15%;'>Product Name</th>
            <th style='width: 15%;'>Price / Unit</th>
            <th style='width: 15%;'>StockQty</th>
            <th style='width: 10%;'>Detail</th>
        </tr>";

    foreach ($products as $row) {
        $a1 = $row['ProductID'];
        echo "<tr>
            <td style='text-align: center;'>{$row['ProductID']}</td>
            <td style='text-align: center;'>{$row['ProductName']}</td>
            <td style='text-align: center;'>{$row['PricePerUnit']}</td>
            <td style='text-align: center;'>{$row['QtyStock']}</td>
            <td style='text-align: center;'>{$row['QtyStock']}</td>
          </tr>";
    }
    echo "</table>";
    echo 
            '<center>
            <form method="POST" action="deleteProduct.php">
                <p>ต้องการลบรหัสลูกค้าหมายเลข ' . $ProductID . ' หรือไม่???</p><br>
                <input type="Hidden" name="ProductID" value="' . $ProductID . '" >
                <input type="submit" value="ยืนยันการลบ">
            </form>
            </center>';

?>
