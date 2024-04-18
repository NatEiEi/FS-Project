<?ob_start();?>
<?php
    require_once __DIR__ . '/db.php'; 
    require_once 'JWT_Handler.php'; 
    session_start();

    if(isset($_POST["ProductID"])) {
        $ProductID = $_POST["ProductID"];
        $Qty = $_POST["Qty"];

        
        if (isset($_COOKIE['JWT'])) {
            $JWT = new JWT_Handler();
            $Username = $JWT->decode()->Username;

            
  



            $existingQuery = "SELECT * FROM cart WHERE Username = '$Username' AND ProductID = '$ProductID'";
            $existingStatement = $pdo->prepare($existingQuery);
            $existingStatement->execute();
            $existingProduct = $existingStatement->fetch(PDO::FETCH_ASSOC);
            if ($existingProduct) {
                $newQuantity = $existingProduct['Qty'] + $Qty;
                $updateQuery = "UPDATE cart SET Qty = '$newQuantity' WHERE Username = '$Username' AND ProductID = '$ProductID'";
                $updateStatement = $pdo->prepare($updateQuery);
                $updateStatement->execute();
            } else {
                $insertQuery = "INSERT INTO cart(Username, ProductID, Qty) VALUES ('$Username', '$ProductID', '$Qty')";
                $insertStatement = $pdo->prepare($insertQuery);
                $insertStatement->execute();
            }

            $query = "SELECT * FROM cart WHERE Username = '$Username'";
            $statement = $pdo->prepare($query);
            $statement->execute();
            if($statement->rowCount() > 0) {
                $products = $statement->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION['CartCount'] = count($products);
            }
            
        } else {
            //ตระกร้าของ guest จะเก็บเป็น session
            if (isset($_SESSION['CartArray'])) {
                
                $CartArray = $_SESSION['CartArray'];
                // array_push($CartArray, ["ProductID" => $ProductID,"Qty" => $qty]);
                $found = false;
                foreach ($CartArray as &$item) {
                    if ($item["ProductID"] == $ProductID) {
                        $item["Qty"] += $Qty;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    array_push($CartArray, ["ProductID" => $ProductID,"Qty" => $Qty]);
                }
                $_SESSION['CartArray'] = $CartArray;
                $_SESSION['CartCount'] = count($CartArray);
            } else {
                //กรณีที่ยังไม่มี session ตระกร้า ก็จะสร้างตระกร้าขึ้นมา พร้อม add product เข้าไป
                $CartArray = array(["ProductID" => $ProductID,"Qty" => $Qty]);
                $_SESSION['CartArray']=$CartArray;
                $_SESSION['CartCount'] = count($CartArray);
            }
            // echo "<script>alert('เพิ่มสินค้าลงในตะกร้าสำเร็จแล้ว');</script>";
        }
        echo "<script>alert('เพิ่มสินค้าใหม่ลงตระกร้าเรียบร้อย'); window.location='product_detail.php?ProductID=$ProductID';</script>";
    }
?>