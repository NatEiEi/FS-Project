<?ob_start();?>
<?php
    require '../db.php'; 

    $OrderID = $_GET['OrderID'];

    $query =    "UPDATE `order` SET Status = '60'
                    WHERE OrderID = '$OrderID'";

    $statement = $pdo->prepare($query);
    $statement->execute();
 
?>

