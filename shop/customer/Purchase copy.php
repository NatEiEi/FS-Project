<?php
    require_once __DIR__ . '/db.php'; 
    include_once __DIR__ . '/navbar.php';
    require_once 'JWT_Handler.php'; 
    
    session_start();

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

        $query = "SELECT * FROM payment WHERE Username='$Username'";
        $statement = $pdo->prepare($query);
        $statement->execute();
        if($statement->rowCount() > 0) {
            $payments = $statement->fetchAll(PDO::FETCH_ASSOC);
        }

    } else {
        $Username = "GUEST";
        $payments = array();
        $addresses = array();
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

?>


<head>
    <link rel="stylesheet" href="css/cart.css"><link rel="stylesheet" href="css/address_form.css">
</head>
<body>
<div class="container">
    <h1>Payment</h1>
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
                <div class="qty">Quantity : <?= $product['Qty'] ?></div>
                <div class="price">Price : <?= $price ?> THB<br>delete</div>
            </li>
            <?php endforeach; ?>
        <?php endif ?>
    </ul>


    <p class="price">Total Price :  <?= $totalPrice ?> THB</p>


    <form method="GET" action="Purchase_confirm.php">


    <p>Select Payment</p>


    <!-- <?php if(isset($payments)): ?>
        <?php foreach($payments as $payment): ?>
            <input type="radio" id="<?= $payment['PaymentID'] ?>" name="Payment" value="<?= $payment['PaymentID'] ?>">
            <label for="<?= $payment['PaymentID'] ?>">Card Number : <?= $payment['CardNumber'] ?>   <?= $payment['PaymentType'] ?> </label><br>
        <?php endforeach; ?>
    <?php endif ?>
    <input type="radio" id="CashOnDelivery" name="Payment" value="CashOnDelivery">
    <label for="CashOnDelivery">Cash On Delivery</label><br>
    <input type="radio" id="addPayment" name="Payment" value="addPayment">
    <label for="addPayment">เพิ่มเติม</label><br>
    <div id="addPaymentDiv" style="display:none;">
        PaymentType : 
        <input type="text"  name="addPay_Type"><br>
        Card Number : 
        <input type="text" name="addPay_CardNum" max="16"><br>
    </div> -->


    <input type="radio" id="CashOnDelivery" name="Payment" value="CashOnDelivery">
    <label for="CashOnDelivery">Cash On Delivery</label><br>
    <input type="radio" id="QRCode" name="Payment" value="QRCode">
    <label for="QRCode">QR Code</label><br>

    
    <p>Address Buyer</p>
    <?php if(isset($addresses)): ?>
        <?php foreach($addresses as $address): ?>
            <input type="radio" id="<?= $address['AddressID'] ?>" name="AddressBuy" value="<?= $address['AddressID'] ?>">
            <label for="<?= $address['AddressID'] ?>">Address : <?= $address['Province'] ?>   <?= $address['Country'] ?>   <?= $address['Postal'] ?></label><br>
        <?php endforeach; ?>
    <?php endif ?>
    <input type="radio" id="addAddressBuy" name="AddressBuy" value="addAddressBuy">
    <label for="addAddressBuy">เพิ่มที่อยู่ผู้ซื้อ</label><br>
    <div id="addAddressBuyDiv" style="display:none; text-align: start; padding:0px 5%;">
        Name : 
        <input type="text"  name="addBuy_Name"><br>
        Country : 
        <input type="text" name="addBuy_Country"><br>
        Province : 
        <input type="text" name="addBuy_Province"><br>
        Postol : 
        <input type="text" name="addBuy_Postol"><br>
    </div>


    <p>Address Billing</p>
    <?php if(isset($addresses)): ?>
        <?php foreach($addresses as $address): ?>
            <input type="radio" id="<?= $address['AddressID'] ?>" name="AddressBilling" value="<?= $address['AddressID'] ?>">
            <label for="<?= $address['AddressID'] ?>">Address : <?= $address['Province'] ?>   <?= $address['Country'] ?>   <?= $address['Postal'] ?></label><br>
        <?php endforeach; ?>
    <?php endif ?>
    <input type="radio" id="addAddressBill" name="AddressBilling" value="addAddressBill">
    <label for="addAddressBill">เพิ่มที่อยู่ออก Bill</label><br>
    <div id="addAddressBillDiv" style="display:none; text-align: start; padding:0px 5%;">
        Name : 
        <input type="text"  name="addBill_Name"><br>
        Country : 
        <input type="text" name="addBill_Country"><br>
        Province : 
        <input type="text" name="addBill_Province"><br>
        Postol : 
        <input type="text" name="addBill_Postol"><br>
    </div>
    <div id="sameBill_BuyDiv" style="display:none;">
    <input type="radio" id="sameBill_Buy" name="AddressBilling" value="sameBill_Buy">
    <label for="sameBill_Buy">ใช้ที่อยู่เดียวกันกับ ที่อยู่ผู้ซื้อ</label><br>
    </div>
    <div id="sameBill_ShipDiv" style="display:none;">
    <input type="radio" id="sameBill_Ship" name="AddressBilling" value="sameBill_Ship">
    <label for="sameBill_Ship">ใช้ที่อยู่เดียวกันกับ ที่อยู่จัดส่ง</label><br>
    </div>

    <p>Address Shipping</p>
    <?php if(isset($addresses)): ?>
        <?php foreach($addresses as $address): ?>
            <input type="radio" id="<?= $address['AddressID'] ?>" name="AddressShipping" value="<?= $address['AddressID'] ?>">
            <label for="<?= $address['AddressID'] ?>">Address : <?= $address['Province'] ?>   <?= $address['Country'] ?>   <?= $address['Postal'] ?></label><br>
        <?php endforeach; ?>
    <?php endif ?>
    <input type="radio" id="addAddressShip" name="AddressShipping" value="addAddressShip">
    <label for="addAddressShip">เพิ่มที่อยู่จัดส่ง</label><br>

    <div id="addAddressShipDiv" style="display:none; text-align: start; padding:0px 5%;">
        Name : 
        <input type="text"  name="addShip_Name"><br>
        Country : 
        <input type="text" name="addShip_Country"><br>
        Province : 
        <input type="text" name="addShip_Province"><br>
        Postol : 
        <input type="text" name="addShip_Postol"><br>
    </div>
    <div id="sameShip_BuyDiv" style="display:none;">
      <input type="radio" id="sameShip_Buy" name="AddressShipping" value="sameShip_Buy">
      <label for="sameShip_Buy">ใช้ที่อยู่เดียวกันกับ ที่อยู่ผู้ซื้อ</label><br>
    </div>

    <div id="sameShip_BillDiv" style="display:none;">
      <input type="radio" id="sameShip_Bill" name="AddressShipping" value="sameShip_Bill">
      <label for="sameShip_Bill">ใช้ที่อยู่เดียวกันกับ ที่อยู่ออกบิล</label><br>
    </div>

    
    <br><hr><br>
  
        <input type="hidden" name="BuyMethod" value="<?= $_GET['BuyMethod'] ?>">
        <input type="submit" class="btn_Purchase" value="Continue">
    </form>
    
</div>
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
