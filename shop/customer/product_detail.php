<?php
    require_once __DIR__ . '/db.php'; 
    include_once __DIR__ . '/navbar.php';
    require_once 'JWT_Handler.php'; 
    
    $ProductID = $_GET["ProductID"];

    $query = "SELECT * FROM product WHERE ProductID='$ProductID'";
    $statement = $pdo->prepare($query);
    $statement->execute();
    if($statement->rowCount() > 0) {
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    $product = $products[0];
    if ($product['QtyStock'] > 0){
      $qty = $product['QtyStock'];
      $activeForm = 1;
    } else {
        $qty = 'สินค้าหมด';
        $activeForm = 0;
    }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Product Card/Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/product_detail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
  </head>
  <body>
    
    <div class = "card-wrapper">
      <div class = "card">
        <!-- card left -->
        <div class = "product-imgs">
          <div class = "img-display">
            <div class = "img-showcase">
            <?php 
                for ($i = 1; $i < 5; $i++) {
                    $fileName = $product['ProductID'];
                    $filePath = "../images/product/" . $fileName . "-" . $i . ".jpg"; 
                    $filePath = "../images/product/" . $fileName . "-1"  . ".jpg";
                    echo '<img src="' . $filePath . '" alt="shoe image">';


                    // if (file_exists($filePath)) {
                    //     echo '<img src="' . $filePath . '" alt="shoe image">';
                    // } else {
                    //     break;
                    // }
                }
            ?>
            </div>
          </div>
          
          <div class = "img-select">
          <?php 
                for ($i = 1; $i < 5; $i++) {
                    $fileName = $product['ProductID'];
                    $filePath = "../images/product/" . $fileName . "-" . $i . ".jpg"; 

                    $filePath = "../images/product/" . $fileName . "-1"  . ".jpg";
                    echo '<div class = "img-item">';
                        echo '<a href = "#" data-id = "' . $i . '">';
                        echo '<img src = "' . $filePath . '" alt = "shoe image">';
                        echo '</a>';
                        echo '</div>';

                    // if (file_exists($filePath)) {
                    //     echo '<div class = "img-item">';
                    //     echo '<a href = "#" data-id = "' . $i . '">';
                    //     echo '<img src = "' . $filePath . '" alt = "shoe image">';
                    //     echo '</a>';
                    //     echo '</div>';
                    // } else {
                    //     break;
                    // }
                }
            ?>
           
          </div>
        </div>
        <!-- card right -->
        <div class = "product-content">
          <h2 class = "product-title"><?= $product['ProductName'] ?></h2>

          <div class = "product-price">
            <p class = "new-price">ราคา: <span><?= $product['PricePerUnit'] ?> บาท</span></p>
          </div>

          <div class = "product-detail">
            <h2>รายละเอียดสินค้า : </h2>
            <p><?= $product['Detail'] ?></p>
            <p>สินค้าคงเหลือ :  <?= $qty ?> </p>
          </div>
          
          <div class = "purchase-info">
            <form method="POST" id="BuyForm" action="add_to_cart.php">
                <input type="hidden" name="ProductID" value="<?= $product['ProductID'] ?>">
                <input type="number" id="Qty" name="Qty" min=1 max=<?= $product['QtyStock'] ?> value="1" >
                <button type="submit" value="AddToCart" class="btn" style="background: #256eff;">เพิ่มเข้าตระกร้า <i class = "fas fa-shopping-cart"></i></button>
                <a href="Purchase.php?ProductID=<?= $product['ProductID'] ?>&BuyMethod=BuyNow" id="purchaseLink">
                    <button type="button" class="btn" value="Buy Now">ซื้อตอนนี้</button>
                </a>
            </form>
            
          </div>
        </div>
      </div>
    </div>

    
    <script src="js/product_detail.js"></script>  
    <script>
        function disableForm() {
            var form = document.getElementById("BuyForm");
            var elements = form.elements;
            for (var i = 0; i < elements.length; i++) {
                elements[i].disabled = true;
        }
        }
        if (<?= $activeForm ?> == 0){
            disableForm();
        }
        
        document.getElementById('purchaseLink').addEventListener('click', function(event) {
            // ดึงค่าจำนวนที่ผู้ใช้เลือก
            var qtyValue = document.getElementById('Qty').value;
            // เพิ่มค่าที่เลือกไว้ใน URL ของลิงก์
            this.href += '&Qty=' + encodeURIComponent(qtyValue);
        });

    </script>
  </body>
</html>