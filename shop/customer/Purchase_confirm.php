<?php
    require_once __DIR__ . '/db.php'; 
    include_once __DIR__ . '/navbar.php';
    require_once 'JWT_Handler.php'; 
    include('db_thailand.php');

    $products = $_SESSION['ProductBuyList'];
    $SubTotalPrice = 0;
    $Vat = 0;
    $ShippingCost = 0;
    $TotalPrice = 0;
    
    


    if (isset($_GET['AddressBilling'] , $_GET['AddressShipping'] , $_GET['AddressBuy'])) {

        $AddressBuy = $_GET['AddressBuy'];
        $AddressBilling = $_GET['AddressBilling'];
        $AddressShipping = $_GET['AddressShipping'];

        $infoBuy = [];
        $infoBill = [];
        $infoShip = [];

        // AddressList Buying Address
        if ($AddressBuy == 'addAddressBuy') {
            $infoBuy =[
                "Name" => $_GET['buy_name'],
                "Tel" => $_GET['buy_tel'],
                "Detail" => $_GET['buy_detail'],
                "Province" =>  $_GET['buy_province'], 
                "Amphure" => $_GET['buy_amphure'], 
                "District" => $_GET['buy_district'], 
                "Zip" => $_GET['buy_zip']
            ];
            //แก้จากโค๊ดเป็นคำ
            $sql = "SELECT * FROM provinces WHERE id='{$infoBuy['Province']}'";
            $query = mysqli_query($conn, $sql);
            $infoBuy['Province'] = mysqli_fetch_assoc($query)['name_th'];

            $sql = "SELECT * FROM amphures WHERE id='{$infoBuy['Amphure']}'";
            $query = mysqli_query($conn, $sql);
            $infoBuy['Amphure'] = mysqli_fetch_assoc($query)['name_th'];

            $sql = "SELECT * FROM districts WHERE id='{$infoBuy['District']}'";
            $query = mysqli_query($conn, $sql);
            $infoBuy['District'] = mysqli_fetch_assoc($query)['name_th'];
            
            $query = mysqli_query($conn, $sql);
            $infoBuy['Zip'] = mysqli_fetch_assoc($query)['zip_code'];

        } else {
            $query = "SELECT * FROM address WHERE AddressID='$AddressBuy'";
            $statement = $pdo->prepare($query);
            $statement->execute();
            if($statement->rowCount() > 0) {
                $addresslist = $statement->fetchAll(PDO::FETCH_ASSOC);
                $infoBuy = $addresslist[0];
            }
        }

        // AddressList Billing Address
        if ($AddressBilling == 'addAddressBill') {
            $infoBill =[
                "Name" => $_GET['bill_name'],
                "Tel" => $_GET['bill_tel'],
                "Detail" => $_GET['bill_detail'],
                "Province" =>  $_GET['bill_province'], 
                "Amphure" => $_GET['bill_amphure'], 
                "District" => $_GET['bill_district'], 
                "Zip" => $_GET['bill_zip']
            ];
             //แก้จากโค๊ดเป็นคำ
             $sql = "SELECT * FROM provinces WHERE id='{$infoBill['Province']}'";
             $query = mysqli_query($conn, $sql);
             $infoBill['Province'] = mysqli_fetch_assoc($query)['name_th'];

 
 
             $sql = "SELECT * FROM amphures WHERE id='{$infoBill['Amphure']}'";
             $query = mysqli_query($conn, $sql);
             $infoBill['Amphure'] = mysqli_fetch_assoc($query)['name_th'];
 
             $sql = "SELECT * FROM districts WHERE id='{$infoBill['District']}'";
             $query = mysqli_query($conn, $sql);
             $infoBill['District'] = mysqli_fetch_assoc($query)['name_th'];
             
             $query = mysqli_query($conn, $sql);
             $infoBill['Zip'] = mysqli_fetch_assoc($query)['zip_code'];
        }
        
        // AddressList Shipping Address
        if ($AddressShipping == 'addAddressShip') {
            $infoShip =[
                "Name" => $_GET['ship_name'],
                "Tel" => $_GET['ship_tel'],
                "Detail" => $_GET['ship_detail'],
                "Province" =>  $_GET['ship_province'], 
                "Amphure" => $_GET['ship_amphure'], 
                "District" => $_GET['ship_district'], 
                "Zip" => $_GET['ship_zip']
            ];
            //แก้จากโค๊ดเป็นคำ
            $sql = "SELECT * FROM provinces WHERE id='{$infoShip['Province']}'";
            $query = mysqli_query($conn, $sql);
            $infoShip['Province'] = mysqli_fetch_assoc($query)['name_th'];



            $sql = "SELECT * FROM amphures WHERE id='{$infoShip['Amphure']}'";
            $query = mysqli_query($conn, $sql);
            $infoShip['Amphure'] = mysqli_fetch_assoc($query)['name_th'];

            $sql = "SELECT * FROM districts WHERE id='{$infoShip['District']}'";
            $query = mysqli_query($conn, $sql);
            $infoShip['District'] = mysqli_fetch_assoc($query)['name_th'];
            
            $query = mysqli_query($conn, $sql);
            $infoShip['Zip'] = mysqli_fetch_assoc($query)['zip_code'];
        }



        // ในกรณีที่อยู่ Bill ที่เลือกเหมือนซื้อหรือที่อยู่ส่ง
        if ($AddressBilling == 'sameBill_Buy') {
            $infoBill = $infoBuy;
        } else if ($AddressBilling == 'sameBill_Ship') {
            $infoBill = $infoShip;
        } else {
            $query = "SELECT * FROM address WHERE AddressID='$AddressBilling'";
            $statement = $pdo->prepare($query);
            $statement->execute();
            if($statement->rowCount() > 0) {
                $addresslist = $statement->fetchAll(PDO::FETCH_ASSOC);
                $infoBill = $addresslist[0];
            }
        }


        // ในกรณีที่อยู่ส่ง ที่เลือกเหมือนซื้อหรือที่บิล
        if ($AddressShipping == 'sameShip_Buy') {
            $infoShip = $infoBuy;
        } else if ($AddressShipping == 'sameShip_Bill') {
            $infoShip = $infoBill;
        } else {
            $query = "SELECT * FROM address WHERE AddressID='$AddressShipping'";
            $statement = $pdo->prepare($query);
            $statement->execute();
            if($statement->rowCount() > 0) {
                $addresslist = $statement->fetchAll(PDO::FETCH_ASSOC);
                $infoShip = $addresslist[0];
            }
        }
    }
?>


<head>
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/address_form.css">
</head>
<body>
<div class="container">
    <h1>รายการสั่งซื้อ</h1>

    <p>ที่อยู่ผู้ซื้อ</p>
    <p>ชื่อผู้ซื้อ : <?= $infoBuy["Name"] ?> จังหวัด : <?= $infoBuy["Province"] ?> อำเภอ : <?= $infoBuy["Amphure"] ?> อำเภอ : <?= $infoBuy["District"] ?> รหัสไปรณีษ์ : <?= $infoBuy["Zip"] ?></p>
    
    <p>ที่อยู่ออกบิล</p>
    <p>ชื่อ : <?= $infoBill["Name"] ?> จังหวัด : <?= $infoBill["Province"] ?> อำเภอ : <?= $infoBill["Amphure"] ?> อำเภอ : <?= $infoBill["District"] ?> รหัสไปรณีษ์ : <?= $infoBill["Zip"] ?></p>
    
    <p>ที่อยู่จัดส่ง</p>
    <p>ชื่อ : <?= $infoShip["Name"] ?> จังหวัด : <?= $infoShip["Province"] ?> อำเภอ : <?= $infoShip["Amphure"] ?> อำเภอ : <?= $infoShip["District"] ?> รหัสไปรณีษ์ : <?= $infoShip["Zip"] ?></p>
    
    
    <br><hr><br>
    <form method="GET" action="SaveOrder.php">
        <p>เลือกวิธีการชำระเงิน</p>
        <div class="radio-buttons">
            <input type="radio" id="CashOnDelivery" name="Payment" value="CashOnDelivery">
            <label for="CashOnDelivery">ชำระเงินปลายทาง</label><br>
            <input type="radio" id="QRCode" name="Payment" value="QRCode">
            <label for="QRCode">ชำระผ่าน QR Code PromtPay</label><br>
        </div>

        <p>เลือกบริษัทขนส่ง</p>
        <div class="radio-buttons">
            <input type="radio" id="ThailandPost" name="Shipper" value="ThailandPost">
            <label for="ThailandPost">ไปรษณีย์ไทย</label><br>
            <input type="radio" id="KerryExpress" name="Shipper" value="KerryExpress">
            <label for="KerryExpress">Kerry Express</label><br>
        </div>

        <hr>
        <ul>
        <p>รายการสินค้า</p>
        <?php if(isset($products)): ?>
            <?php foreach($products as $product): 
                $fileName = $product['ProductID'];
                $filePath = "../images/product/" . $fileName . "-1".".jpg"; 


                if (file_exists($filePath)) {
                } else {
                    $filePath = "../images/boss_Dog.jpg";
                }
                $price = $product['PricePerUnit'] * $product['Qty'];
                $SubTotalPrice = $SubTotalPrice + $price;
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
    
    <?php
        //คำนวนราคา
        $Vat = $SubTotalPrice * 0.07;
        $ShippingCost = 40;
        $TotalPrice = $SubTotalPrice + $Vat + $ShippingCost;
    ?>
    <p class="price">ราคารวม :  <?= $SubTotalPrice ?> บาท</p>
    <p class="price">ภาษีมูลค่าเพิ่ม :  <?= $Vat ?> บาท</p>
    <p class="price">ค่าขนส่ง :  <?= $ShippingCost ?> บาท</p>
    <p class="price">ราคารวมทั้งหมด :  <?= $TotalPrice ?> บาท</p>
        
        <input type="hidden" name="SubTotalPrice" value="<?= $SubTotalPrice ?>">
        <input type="hidden" name="Vat" value="<?= $Vat ?>">
        <input type="hidden" name="ShippingCost" value="<?= $ShippingCost ?>">
        <input type="hidden" name="TotalPrice" value="<?= $TotalPrice ?>">



        <input type="hidden" name="AddressBuy" value="<?= $_GET['AddressBuy'] ?>">
        <input type="hidden" name="buy_name" value="<?= $infoBuy["Name"] ?>">
        <input type="hidden" name="buy_tel" value="<?= $infoBuy["Tel"] ?>">
        <input type="hidden" name="buy_detail" value="<?= $infoBuy["Detail"] ?>">
        <input type="hidden" name="buy_province" value="<?= $infoBuy["Province"] ?>">
        <input type="hidden" name="buy_amphure" value="<?= $infoBuy["Amphure"] ?>">
        <input type="hidden" name="buy_district" value="<?= $infoBuy["District"] ?>">
        <input type="hidden" name="buy_zip" value="<?= $infoBuy["Zip"] ?>">

        <input type="hidden" name="AddressBilling" value="<?= $_GET['AddressBilling'] ?>">
        <input type="hidden" name="bill_name" value="<?= $infoBill["Name"] ?>">
        <input type="hidden" name="bill_tel" value="<?= $infoBill["Tel"] ?>">
        <input type="hidden" name="bill_detail" value="<?= $infoBill["Detail"] ?>">
        <input type="hidden" name="bill_province" value="<?= $infoBill["Province"] ?>">
        <input type="hidden" name="bill_amphure" value="<?= $infoBill["Amphure"] ?>">
        <input type="hidden" name="bill_district" value="<?= $infoBill["District"] ?>">
        <input type="hidden" name="bill_zip" value="<?= $infoBill["Zip"] ?>">

        <input type="hidden" name="AddressShipping" value="<?= $_GET['AddressShipping'] ?>">
        <input type="hidden" name="ship_name" value="<?= $infoShip["Name"] ?>">
        <input type="hidden" name="ship_tel" value="<?= $infoShip["Tel"] ?>">
        <input type="hidden" name="ship_detail" value="<?= $infoShip["Detail"] ?>">
        <input type="hidden" name="ship_province" value="<?= $infoShip["Province"] ?>">
        <input type="hidden" name="ship_amphure" value="<?= $infoShip["Amphure"] ?>">
        <input type="hidden" name="ship_district" value="<?= $infoShip["District"] ?>">
        <input type="hidden" name="ship_zip" value="<?= $infoShip["Zip"] ?>">


        <!-- <input type="hidden" name="AddressBilling" value="<?= $_GET['AddressBilling'] ?>">
        <input type="hidden" name="addBill_Name" value="<?= $infoBill["Name"] ?>">
        <input type="hidden" name="addBill_Country" value="<?= $infoBill["Province"] ?>">
        <input type="hidden" name="addBill_Province" value="<?= $infoBill["Country"] ?>">
        <input type="hidden" name="addBill_Postol" value="<?= $infoBill["Postal"] ?>">

        <input type="hidden" name="AddressShipping" value="<?= $_GET['AddressShipping'] ?>">
        <input type="hidden" name="addShip_Name" value="<?= $infoShip["Name"] ?>">
        <input type="hidden" name="addShip_Country" value="<?= $infoShip["Province"] ?>">
        <input type="hidden" name="addShip_Province" value="<?= $infoShip["Country"] ?>">
        <input type="hidden" name="addShip_Postol" value="<?= $infoShip["Postal"] ?>"> -->

        <input type="hidden" name="BuyMethod" value="<?= $_GET['BuyMethod'] ?>">
        <input type="submit" class="btn_Purchase" value="ยืนยันรายการสั่งซื้อ" onclick="return confirmOrder()">
        <a href="Purchase_cancel.php" onclick="return confirmCancel()"><input type="button" class="btn_Purchase" value="ยกเลิก" style="background-color: red;"></a>
    </form>
    
</div>

</body>
<script>
    function confirmCancel() {
        // แสดง popup confirm และรับค่าที่ผู้ใช้ตอบ
        var confirmation = confirm("คุณต้องการที่จะยกเลิกรายการสั่งซื้อใช่หรือไม่?");
        // หากผู้ใช้กด OK ใน popup confirm จะเปิดลิงก์
        return confirmation;
    }
</script>
<script>
    function confirmOrder() {
        // แสดง popup confirm และรับค่าที่ผู้ใช้ตอบ
        var confirmation = confirm("ยืนยันรายการสั่งซื้อใช่หรือไม่?");
        // หากผู้ใช้กด OK ใน popup confirm จะเปิดลิงก์
        return confirmation;
    }
</script>
</html>
