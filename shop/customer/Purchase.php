<?ob_start();?>
<?php
    require_once __DIR__ . '/db.php'; 
    include_once __DIR__ . '/navbar.php';
    require_once 'JWT_Handler.php'; 
    

    if (isset($_COOKIE['JWT'])) {
        $JWT = new JWT_Handler();
        $Username = $JWT->decode()->Username;

        if (isset($_GET['ProductID']) && isset($_GET['Qty']) ){
            $ProductID = $_GET['ProductID'];
            $Qty = $_GET['Qty'];
            $products = array();

            $query = "SELECT * FROM product WHERE ProductID='$ProductID'";
            $statement = $pdo->prepare($query);
            $statement->execute();
            if($statement->rowCount() > 0) {
                $prod = $statement->fetchAll(PDO::FETCH_ASSOC);
                $prod = $prod[0];
                array_push($products, ["ProductID" => $ProductID ,"ProductName" => $prod['ProductName'] 
                ,"PricePerUnit" => $prod['PricePerUnit'],"Qty" => $Qty]);
            }
        } else {
            $query = "SELECT p.ProductID, p.ProductName, c.Username, c.Qty , PricePerUnit
                    FROM product p 
                    INNER JOIN cart c ON p.ProductID = c.ProductID 
                    WHERE c.Username='$Username'";
            $statement = $pdo->prepare($query);
            $statement->execute();
            if($statement->rowCount() > 0) {
                $products = $statement->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        

        $query = "SELECT * FROM address WHERE Username='$Username'";
        $statement = $pdo->prepare($query);
        $statement->execute();
        if($statement->rowCount() > 0) {
            $addresses = $statement->fetchAll(PDO::FETCH_ASSOC);
        }



    } else {
        $Username = "GUEST";
        $payments = array();
        $products = array();
        if (isset($_GET['ProductID']) && $_GET['Qty']){
            $ProductID = $_GET['ProductID'];
            $Qty = $_GET['Qty'];
            $query = "SELECT * FROM product WHERE ProductID='$ProductID'";
            $statement = $pdo->prepare($query);
            $statement->execute();
            if($statement->rowCount() > 0) {
                $prod = $statement->fetchAll(PDO::FETCH_ASSOC);
                $prod = $prod[0];
                array_push($products, ["ProductID" => $ProductID ,"ProductName" => $prod['ProductName'] 
                ,"PricePerUnit" => $prod['PricePerUnit'],"Qty" => $Qty]);
            }
        }else {
            $array = $_SESSION['CartArray'];
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
        }
       
 
    }

    $_SESSION['ProductBuyList'] = $products;
    $totalPrice = 0;
    include('db_thailand.php');
    $sql = "SELECT * FROM provinces";
    $query = mysqli_query($conn, $sql);
    $query1 = mysqli_query($conn, $sql);
    $query2 = mysqli_query($conn, $sql);

?>


<head>
    <link rel="stylesheet" href="css/address_form.css">
    <link rel="stylesheet" href="css/cart.css">
    
</head>
<body>
<div class="container">
    <h1>รายการสั่งซื้อ</h1>
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
                <img src="<?= $filePath ?>" alt="Product Image">
                <div class="name"><?= $product['ProductName'] ?></div>
                <div class="qty">จำนวน : <?= $product['Qty'] ?></div>
                <div class="price">ราคา : <?= $price ?> บาท</div>
            </li>
            <?php endforeach; ?>
        <?php endif ?>
    </ul>


    <p class="price">ราคารวม :  <?= $totalPrice ?> บาท</p>





<form method="GET" action="Purchase_confirm.php">


    
    
    <p>เลือกที่อยู่ผู้ซื้อ</p>
    <div class="radio-buttons">
        <input type="radio" id="addAddressBuy" name="AddressBuy" value="addAddressBuy" required>
        <label for="addAddressBuy">เพิ่มที่อยู่ผู้ซื้อใหม่</label><br>
    </div>
    
    <?php if(isset($addresses)): ?>
        <p>เลือกที่อยู่ที่มี</p>
        <?php foreach($addresses as $address): ?>
          <input type="radio" id="1<?= $address['AddressID'] ?>" name="AddressBuy" value="<?= $address['AddressID'] ?>" > 
          <label for="1<?= $address['AddressID'] ?>" style="display: block;" ><?= $address['Name'] ?> <?= $address['Tel'] ?> <?= $address['Detail'] ?> <?= $address['Province'] ?>  <?= $address['Amphure'] ?>  <?= $address['District'] ?> <?= $address['Zip'] ?></label>
        <?php endforeach; ?>
    <?php endif ?>


    <div id="addAddressBuyDiv" class="card-body" style="display:none; text-align: start; padding:0px 5%;">
        <p>เพิ่มที่อยู่ผู้ซื้อ</p>
            <label>ข้อมูลติดต่อ</label>
            <input class="form-group" type="text" placeholder="ชื่อ นามสกุล" name="buy_name" ><br>
            <input class="form-group" type="text" placeholder="หมายเลขโทรศัพท์" name="buy_tel" maxlength="10"><br>
            <input class="form-group" type="text" placeholder="บ้านเลขที่ , ซอย , หมู่ , ถนน , แขวง/ตำบล" name="buy_detail" size="40" >
            
            <label for="province">จังหวัด</label>
            <select name="buy_province" id="province" class="form-control" >
                <option value="">เลือกจังหวัด</option>
                  <?php while($result = mysqli_fetch_assoc($query)): ?>
                    <option value="<?=$result['id']?>"><?=$result['name_th']?></option>
                 <?php endwhile; ?>
            </select>

            <label for="amphure">อำเภอ</label>
            <select name="buy_amphure" id="amphure" class="form-control" >
                <option value="">เลือกอำเภอ</option>
            </select>
              
            <label for="district">ตำบล</label>
            <select name="buy_district" id="district" class="form-control" >
                <option value="">เลือกตำบล</option>
            </select>

            <label for="zip">รหัสไปรษณีย์</label>
            <select name="buy_zip" id="zip" class="form-control" >
                <option value="">เลือกรหัสไปรษณีย์</option>
            </select>
    </div>
    


    <hr>

    <p>เลือกที่อยู่ผู้ออกบิล</p>
    <div class="radio-buttons">
        <input type="radio" id="addAddressBill" name="AddressBilling" value="addAddressBill" required>
        <label for="addAddressBill">เพิ่มที่อยู่ออกบิลใหม่</label><br>
    
        <div id="sameBill_BuyDiv" style="display:none;">
          <input type="radio" id="sameBill_Buy" name="AddressBilling" value="sameBill_Buy">
          <label for="sameBill_Buy">เหมือนที่อยู่ผู้ซื้อ</label><br>
        </div>

        <div id="sameBill_ShipDiv" style="display:none;">
          <input type="radio" id="sameBill_Ship" name="AddressBilling" value="sameBill_Ship">
          <label for="sameBill_Ship">เหมือนที่อยู่จัดส่ง</label><br>
        </div>
    </div>

    
    <?php if(isset($addresses)): ?>
      <p>เลือกที่อยู่ที่มี</p>
        <?php foreach($addresses as $address): ?>
          <input type="radio" id="2<?= $address['AddressID'] ?>" name="AddressBilling" value="<?= $address['AddressID'] ?>" > 
          <label for="2<?= $address['AddressID'] ?>" style="display: block;" ><?= $address['Name'] ?> <?= $address['Tel'] ?> <?= $address['Detail'] ?> <?= $address['Province'] ?>  <?= $address['Amphure'] ?>  <?= $address['District'] ?> <?= $address['Zip'] ?></label>
        <?php endforeach; ?>
    <?php endif ?>

    <div id="addAddressBillDiv" style="display:none; text-align: start; padding:0px 5%;">
    <p>เพิ่มที่อยู่ผู้ออกบิลใหม่</p>

            <label>ข้อมูลติดต่อ</label>
            <input class="form-group" type="text" placeholder="ชื่อ นามสกุล" name="bill_name" ><br>
            <input class="form-group" type="text" placeholder="หมายเลขโทรศัพท์" name="bill_tel" maxlength="10"><br>
            <input class="form-group" type="text" placeholder="บ้านเลขที่ , ซอย , หมู่ , ถนน , แขวง/ตำบล" name="bill_detail" size="40" >
            
            <label for="province1">จังหวัด</label>
            <select name="bill_province" id="province1" class="form-control" >
                <option value="">เลือกจังหวัด</option>
                  <?php while($result1 = mysqli_fetch_assoc($query1)): ?>
                    <option value="<?=$result1['id']?>"><?=$result1['name_th']?></option>
                 <?php endwhile; ?>
            </select>

            <label for="amphure1">อำเภอ</label>
            <select name="bill_amphure" id="amphure1" class="form-control" >
                <option value="">เลือกอำเภอ</option>
            </select>
              
            <label for="district1">ตำบล</label>
            <select name="bill_district" id="district1" class="form-control" >
                <option value="">เลือกตำบล</option>
            </select>

            <label for="zip1">รหัสไปรษณีย์</label>
            <select name="bill_zip" id="zip1" class="form-control" >
                <option value="">เลือกรหัสไปรษณีย์</option>
            </select>
  
    </div>
    

    <hr>
    <p>เลือกที่อยู่จัดส่ง</p>
    <div class="radio-buttons">
    <input type="radio" id="addAddressShip" name="AddressShipping" value="addAddressShip" required>
    <label for="addAddressShip">เพิ่มที่อยู่จัดส่ง</label><br>
    
      <div id="sameShip_BuyDiv" style="display:none;">
          <input type="radio" id="sameShip_Buy" name="AddressShipping" value="sameShip_Buy">
          <label for="sameShip_Buy">เหมือนที่อยู่ผู้ซื้อ</label><br>
      </div>
      <div id="sameShip_BillDiv" style="display:none;">
          <input type="radio" id="sameShip_Bill" name="AddressShipping" value="sameShip_Bill">
          <label for="sameShip_Bill">เหมือนที่อยู่ออกบิล</label><br>
      </div>
    </div>
    
    <?php if(isset($addresses)): ?>
      <p>เลือกที่อยู่ที่มี</p>
        <?php foreach($addresses as $address): ?>
          <input type="radio" id="3<?= $address['AddressID'] ?>" name="AddressShipping" value="<?= $address['AddressID'] ?>" > 
          <label for="3<?= $address['AddressID'] ?>"  style="display: block;" ><?= $address['Name'] ?> <?= $address['Tel'] ?> <?= $address['Detail'] ?> <?= $address['Province'] ?>  <?= $address['Amphure'] ?>  <?= $address['District'] ?> <?= $address['Zip'] ?></label>
        <?php endforeach; ?>
    <?php endif ?>


    <div id="addAddressShipDiv" style="display:none; text-align: start; padding:0px 5%;">
    <p>เพิ่มที่อยู่ผู้รับ</p>

            <label>ข้อมูลติดต่อ</label>
            <input class="form-group" type="text" placeholder="ชื่อ นามสกุล" name="ship_name"><br>
            <input class="form-group" type="text" placeholder="หมายเลขโทรศัพท์" name="ship_tel" maxlength="10"><br>
            <input class="form-group" type="text" placeholder="บ้านเลขที่ , ซอย , หมู่ , ถนน , แขวง/ตำบล" name="ship_detail" size="40">
            
            <label for="province2">จังหวัด</label>
            <select name="ship_province" id="province2" class="form-control">
                <option value="">เลือกจังหวัด</option>
                  <?php while($result2 = mysqli_fetch_assoc($query2)): ?>
                    <option value="<?=$result2['id']?>"><?=$result2['name_th']?></option>
                 <?php endwhile; ?>
            </select>

            <label for="amphure2">อำเภอ</label>
            <select name="ship_amphure" id="amphure2" class="form-control">
                <option value="">เลือกอำเภอ</option>
            </select>
              
            <label for="district2">ตำบล</label>
            <select name="ship_district" id="district2" class="form-control">
                <option value="">เลือกตำบล</option>
            </select>

            <label for="zip2">รหัสไปรษณีย์</label>
            <select name="ship_zip" id="zip2" class="form-control">
                <option value="">เลือกรหัสไปรษณีย์</option>
            </select>

    </div>
    

    
    <br><hr><br>
  
        <input type="hidden" name="BuyMethod" value="<?= $_GET['BuyMethod'] ?>">
        <input type="submit" value="ดำเนินต่อ">
    </form>
    
</div>
<script src="js/jquery.min.js"></script>
<script src="js/script.js"></script>
<script>

  document.querySelectorAll('input[name="Payment"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
      var additionalInput = document.getElementById('addPaymentDiv');
      if (this.id === 'addPayment' && this.checked) {
        additionalInput.style.display = 'block';
      } else {
        additionalInput.style.display = 'none';
      }
    });
  });

  document.querySelectorAll('input[name="AddressBuy"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
      var additionalInput = document.getElementById('addAddressBuyDiv');
      var sameShip_Buy = document.getElementById('sameShip_BuyDiv');
      var sameBill_Buy = document.getElementById('sameBill_BuyDiv');
      if (this.id === 'addAddressBuy' && this.checked) {
        additionalInput.style.display = 'block';
        sameShip_Buy.style.display = 'block';
        sameBill_Buy.style.display = 'block';
      } else {
        additionalInput.style.display = 'none';
        sameShip_Buy.style.display = 'none';
        sameBill_Buy.style.display = 'none';
      }
    });
  });

  document.querySelectorAll('input[name="AddressBilling"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
      var additionalInput = document.getElementById('addAddressBillDiv');
      var sameShip_Bill = document.getElementById('sameShip_BillDiv');
      if (this.id === 'addAddressBill' && this.checked) {
        additionalInput.style.display = 'block';
        sameShip_Bill.style.display = 'block';
      } else {
        additionalInput.style.display = 'none';
        sameShip_Bill.style.display = 'none';
      }
    });
  });

  document.querySelectorAll('input[name="AddressShipping"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
      var additionalInput = document.getElementById('addAddressShipDiv');
      var sameBill_Ship = document.getElementById('sameBill_ShipDiv');
      if (this.id === 'addAddressShip' && this.checked) {
        additionalInput.style.display = 'block';
        sameBill_Ship.style.display = 'block';
      } else {
        additionalInput.style.display = 'none';
        sameBill_Ship.style.display = 'none';
      }
    });
  });
</script>

</body>
</html>
