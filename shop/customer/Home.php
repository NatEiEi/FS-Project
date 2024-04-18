<?ob_start();?>
<?php
    require_once __DIR__ . '/db.php'; 
    include_once __DIR__ . '/navbar.php';
    
    if (isset($_GET["Search"])) {
        $Search = $_GET["Search"];
        $query = "SELECT * FROM product WHERE ProductName Like '%$Search%';";
    } else {
        $query = "SELECT * FROM product";
        
    }
    $statement = $pdo->prepare($query);
    $statement->execute();
    if($statement->rowCount() > 0) {
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
?>


<head>
    <link rel="stylesheet" href="css/itemCard.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<br>
<div class="topnav">
    <div class="search-container">
      <form action="Home.php" method="GET">
        <input type="text" placeholder="Search.." name="Search" id="Search" class="search_input">
        <button type="submit" class="search_btn"><i class="fa fa-search"></i></button>
      </form>
    </div>
</div>

<center><h1>รายการสินค้า</h1></center>

<?php if(isset($_GET["Search"])): ?>
    <center><p>ค้นหาสินค้า >> <?= $Search ?></p></center> 
<?php endif ?>
<div class="container">
   
    <?php if(isset($products)): ?>
        <?php foreach($products as $product): 
            $fileName = $product['ProductID'];
            $filePath = "../images/product/" . $fileName . "-1" . ".jpg"; 


            if (file_exists($filePath)) {
            } else {
                $filePath = "../images/boss_Dog.jpg";
            }
        ?>  
            <a href="product_detail.php?ProductID=<?= $product['ProductID'] ?>" style="text-decoration: none; color: black;">
                <div class="card">
                    <img class="product_img" src="<?= $filePath ?>">
                    <p class="PName"><?= $product['ProductName'] ?></p>
                    <p class="detail"><?= $product['Detail'] ?></p>
                    <p class="price"><?= $product['PricePerUnit'] ?> บาท</p>
                </div>
            </a>
        <?php endforeach; ?>
        <?php else: ?>
            <p>ไม่มีสินค้าที่ตรงกับการค้นหา.</p>
    <?php endif ?>
</div>

</body>
</html>
