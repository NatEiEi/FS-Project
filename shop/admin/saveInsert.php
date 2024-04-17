<?php
    require '../db.php'; 
    include __DIR__ . '/adminLogin.php';


    if (isset($_POST["ProductID"])) {
        $ProductID = $_POST["ProductID"];
        $ProductName = $_POST["ProductName"];
        $PricePerUnit = $_POST["PricePerUnit"];
        $Cost = $_POST["Cost"];
        $QtyStock = $_POST["QtyStock"];
        $Detail = $_POST["Detail"];

        $query = "INSERT INTO Product(ProductID, ProductName, PricePerUnit, Cost, QtyStock , Detail) VALUES('$ProductID','$ProductName', '$Cost', '$PricePerUnit', '$QtyStock' , '$Detail')";
        $statement = $pdo->prepare($query);
        $statement->execute();

        if(isset($_FILES['imageUpload'])) {
            $file_name = $_FILES['imageUpload']['name'];
            $file_size = $_FILES['imageUpload']['size'];
            $file_tmp = $_FILES['imageUpload']['tmp_name'];
            $file_type = $_FILES['imageUpload']['type'];
            $file_ext = strtolower(end(explode('.',$_FILES['imageUpload']['name'])));

            $newName = $ProductID . "." . $file_ext;

            move_uploaded_file($file_tmp,"../images/product/".$newName);
        }
        echo "Insert Product Successfully...";
    }

?>
