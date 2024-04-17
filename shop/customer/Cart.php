<?php
    require_once __DIR__ . '/db.php'; 
    include_once __DIR__ . '/navbar.php';
    require_once 'JWT_Handler.php'; 
    
    $canPurchase = true;
    if (isset($_COOKIE['JWT'])) {
        $JWT = new JWT_Handler();
        $Username = $JWT->decode()->Username;

        $query = "SELECT p.ProductID, p.ProductName, c.Username, c.Qty , PricePerUnit
                FROM product p 
                INNER JOIN cart c ON p.ProductID = c.ProductID 
                WHERE c.Username='$Username'";
        $statement = $pdo->prepare($query);
        $statement->execute();
        if($statement->rowCount() > 0) {
            $products = $statement->fetchAll(PDO::FETCH_ASSOC);
        }else {
            $canPurchase = false;
        }
    } else {
        if (isset($_SESSION['CartArray']) && !empty($_SESSION['CartArray'])) {
            $array = $_SESSION['CartArray'];
            $products = array();
            foreach($array as $arr) {
                $query = "SELECT * FROM product WHERE ProductID='{$arr['ProductID']}'";
                $statement = $pdo->prepare($query);
                $statement->execute();
                if($statement->rowCount() > 0) {
                    $prod = $statement->fetchAll(PDO::FETCH_ASSOC);
                    $prod = $prod[0];
                    array_push($products, ["ProductID" => $arr['ProductID'] ,"ProductName" => $prod['ProductName'] 
                    ,"PricePerUnit" => $prod['PricePerUnit'],"Qty" => $arr['Qty']]);
                }
            }
        } else {
            $canPurchase = false;
        }
    }


    $totalPrice = 0;

?>


<head>
    <link rel="stylesheet" href="css/cart.css">
</head>
<body>
<div class="container">
    <h1>ตระกร้าสินค้า</h1>
    <ul>
        <?php if(isset($products)): ?>
            <?php foreach($products as $product): 
                $fileName = $product['ProductID'];
                $filePath = "../images/product/" . $fileName . "-1".".jpg"; 


                if (file_exists($filePath)) {
                } else {
                    $filePath = "../images/boss_Dog.jpg";
                }
                $price = $product['PricePerUnit'] * $product['Qty'];
                $totalPrice = $totalPrice + $price;
        ?>  
            
            <li class="item">
                <a href="product_detail.php?ProductID=<?= $product['ProductID'] ?>">
                    <img src="<?= $filePath ?>" alt="Product Image">
                </a>
                <div class="name">
                    <a href="product_detail.php?ProductID=<?= $product['ProductID'] ?>">
                        <?= $product['ProductName'] ?>
                    </a>
                </div>
                <div class="qty">จำนวน : <?= $product['Qty'] ?></div>
                <div class="price">ราคา : <?= $price ?> บาท</div>
                
                <br><a href='Cart_delete_item.php?ProductID=<?= $product['ProductID'] ?>' onclick="return confirmDelete()">
                        <img src="../images/icon/delete_icon.png" style="width: 30px; height:30px">
                    </a>

                
            </li>
            
            <?php endforeach; ?>
            <p class="price">ราคารวม :  <?= $totalPrice ?> บาท</p>
        <?php endif ?>
    </ul>


    
    
    <?php if($canPurchase): ?>
    <form method="GET" action="Purchase.php">
        <input type="hidden" name="BuyMethod" value="BuyFromCart">
        <input type="submit" class="btn_Purchase" value="ดำเนินการสั่งซื้อ">
    </form>
    <?php else: ?>
        <input type="submit" class="btn_Purchase btn_disabled" value="ดำเนินการสั่งซื้อ" disabled>
    <?php endif ?>

    
</div>
</body>
<script>
    function confirmDelete() {
        // แสดง popup confirm และรับค่าที่ผู้ใช้ตอบ
        var confirmation = confirm("คุณต้องการลบสินค้าจากตระกร้าใช่หรือไม่?");
        // หากผู้ใช้กด OK ใน popup confirm จะเปิดลิงก์
        return confirmation;
    }
</script>
</html>
