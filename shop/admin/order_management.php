<?ob_start();?>
<?php 
    require '../db.php'; 
    include __DIR__ . '/adminLogin.php';
  
    $OrderID = $_GET['OrderID'];
    $OrderQuery = "SELECT * FROM `order` o, order_status os 
                    WHERE OrderID = '$OrderID' AND o.Status = os.StatusID";
    $statement = $pdo->prepare($OrderQuery);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $orders = $statement->fetchAll(PDO::FETCH_ASSOC);
        $orderInfo = $orders[0];
    }
    
    $AddressQuery = "SELECT Username , Name , Tel , Detail , Province ,Amphure , District , Zip , Type
                    FROM address a, addresslist al
                    WHERE a.AddressID = al.AddressID AND al.OrderID = '$OrderID'";
    $statement = $pdo->prepare($AddressQuery);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $addressLists = $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    $ProductQuery = "SELECT p.ProductID , ProductName , pl.Qty , PricePerUnit 
                    FROM product p, productlist pl
                    WHERE p.ProductID = pl.ProductID AND pl.OrderID = '$OrderID'";
    $statement = $pdo->prepare($ProductQuery);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    $OrderQuery = "SELECT * FROM order_shipping_detail WHERE OrderID = '$OrderID'";
    $statement = $pdo->prepare($OrderQuery);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $orders = $statement->fetchAll(PDO::FETCH_ASSOC);
        $orderInfo_ship = $orders[0];
    }

    //customer and invoice details
    $infoBuy = [];
    $infoBill = [];
    $infoShip = [];
    foreach ($addressLists as $row) {
        if($row['Type'] == "Buy") {
            $infoBuy =[
                "Name" => $row['Name'],
                "Tel" => $row['Tel'],
                "Detail" => $row['Detail'],
                "Province" =>  $row['Province'], 
                "Amphure" => $row['Amphure'], 
                "District" => $row['District'], 
                "Zip" => $row['Zip']
            ];
        }
        
        if($row['Type'] == "Bill"){
            $infoBill = [
                "Name" => $row['Name'],
                "Tel" => $row['Tel'],
                "Detail" => $row['Detail'],
                "Province" =>  $row['Province'], 
                "Amphure" => $row['Amphure'], 
                "District" => $row['District'], 
                "Zip" => $row['Zip']
            ];
        }
        if($row['Type'] == "Ship"){
            $infoShip = [
                "Name" => $row['Name'],
                "Tel" => $row['Tel'],
                "Detail" => $row['Detail'],
                "Province" =>  $row['Province'], 
                "Amphure" => $row['Amphure'], 
                "District" => $row['District'], 
                "Zip" => $row['Zip']
            ];
        }
    }
    $totalPrice = 0;
    // ถ้าสถานะเป็นขอคืนเงิน หรือคืนเงินแล้ว
    $query = "SELECT * FROM order_refund_detail WHERE OrderID = '$OrderID'";
    $statement = $pdo->prepare($query);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $orders = $statement->fetchAll(PDO::FETCH_ASSOC);
        $orderInfo_refund = $orders[0];
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/cart.css">
</head>
<body>
<div class="container">
    <h1>รายการสั่งซื้อ</h1>
    <ul>
        <p>หมายเลขรายการสั่งซื้อ : <?= $OrderID ?></p>
        <p>สถานะรายการสั่งซื้อ : <?= $orderInfo['Status_TH'] ?></p>
        <p>สั่งซื้อวันที่ : <?= date('d F Y H:i:s', strtotime($orderInfo['Date'])) ?></p>

        <?php 
            if($orderInfo['Payment'] == "QRCode"){
                $PaymentMethod = "ชำระผ่าน QR Code PromtPay";
            } else if($orderInfo['Payment'] == "CashOnDelivery"){
                $PaymentMethod = "ชำระเงินปลายทาง";
            }
        ?>
        <p>วิธีชำระเงิน : <?= $PaymentMethod ?></p>
    </ul>
    <ul>
        
        <?php if(isset($products)): ?>
            <p>รายการสินค้า : <?= count($products) ?> รายการ</p>
            <?php foreach($products as $product): 
                $fileName = $product['ProductID'];
                $filePath = "../images/product/" . $fileName . "-1" . ".jpg"; 


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


    <!-- <p class="price">Total Price :  <?= $totalPrice ?> THB</p> -->
    <p class="price">ราคารวม : <?= $orderInfo['SubTotalPrice']?> บาท</p>
    <p class="price">ภาษีมูลค่าเพิ่ม (7%) : <?= $orderInfo['Vat']?> บาท</p>
    <p class="price">ค่าจัดส่ง : <?= $orderInfo['ShippingCost']?> บาท</p>
    <hr style="width: 25%; float: right; clear: both;"><br>
    <p class="price">ราคารวมสุทธิ : <?= $orderInfo['TotalPrice']?> บาท</p>
    
    <!-- <p>ประเภทการชำระเงิน : <?= $Payment ?></p> -->
    <hr>
    <p>ที่อยู่</p>
    <p>ที่อยู่ผู้ซื้อ</p>
    <p><?= $infoBuy["Name"] ?> <?= $infoBuy["Tel"] ?> , <?= $infoBuy["Detail"] ?> <?= $infoBuy["District"] ?> <?= $infoBuy["Amphure"] ?> <?= $infoBuy["Province"] ?>   <?= $infoBuy["Zip"] ?></p>
    
    <p>ที่อยู่ออกบิล</p>
    <p><?= $infoBill["Name"] ?> <?= $infoBill["Tel"] ?> , <?= $infoBill["Detail"] ?> <?= $infoBill["District"] ?> <?= $infoBill["Amphure"] ?> <?= $infoBill["Province"] ?>   <?= $infoBill["Zip"] ?></p>

    <p>ที่อยู่จัดส่ง</p>
    <p><?= $infoShip["Name"] ?> <?= $infoShip["Tel"] ?> , <?= $infoShip["Detail"] ?> <?= $infoShip["District"] ?> <?= $infoShip["Amphure"] ?> <?= $infoShip["Province"] ?>   <?= $infoShip["Zip"] ?></p>
    
    <p>บริษัทจัดส่ง : <?= $orderInfo_ship['Shipper']?></p>
    <?php if($orderInfo_ship['TrackingNumber'] != NULL): ?>
        <p>หมายเลขติดตามพัสดุ : <?= $orderInfo_ship['TrackingNumber']?></p>
    <?php endif ?>
    
    <?php 
        $slipPath = "../images/slip/" . $OrderID . ".jpg" ;
    ?>
    <?php if(file_exists($slipPath)): ?>
        <p>หลักฐานการชำระเงิน : </p>
        <img src="<?= $slipPath ?>" class="slip_image" alt="slip">
        <?php if($orderInfo['Status'] == "20"): ?>
            <a href="update_status_verify_slip.php?OrderID=<?= $OrderID ?>&Verify=True"><input type="button" class="btn_Purchase" value="Slip is Correct."></a>
            <a href="update_status_verify_slip.php?OrderID=<?= $OrderID ?>&Verify=False"><input type="button" class="btn_Purchase" value="Slip is Not Correct."></a>
        <?php endif ?>
        <hr>
    <?php endif ?>
    <?php if(isset($orderInfo_refund)): ?>
        <?php if(count($orderInfo_refund) > 0): ?>
            <p>การร้องขอคืนเงิน</p>
            <?php if($orderInfo_refund['refund_status'] != ""): ?>
                <p>สถานะการร้องขอคืนเงิน : <?= $orderInfo_refund['refund_status']?></p>
                <?php else: ?>
                <p>สถานะการร้องขอคืนเงิน : รอดำเนินการ</p>
            <?php endif ?>
                
            
            <p>เหตุผลในการขอคืนเงิน : <?= $orderInfo_refund['reason']?></p>
            <p>ร้องขอคืนเงินเมื่อ : <?= date('d F Y H:i:s', strtotime($orderInfo_refund['submitted_at'])) ?> </p>
            <?php if($orderInfo_refund['received_at'] != "0000-00-00 00:00:00"): ?>
                <p>ดำเนินการเมื่อ : <?= date('d F Y H:i:s', strtotime($orderInfo_refund['received_at'])) ?></p>
            <?php endif ?>
            <p>ช่องทางรับเงินคืน :</p>
            <p>ชื่อธนาคาร : <?= $orderInfo_refund['bank']?></p>
            <p>เลขบัญชี : <?= $orderInfo_refund['account_number']?></p>
            <p>ชื่อบัญชี : <?= $orderInfo_refund['account_name']?></p>
            <?php 
                $slip_refundPath = "../images/refund_slip/refund_slip_" . $OrderID . ".jpg" ;
            ?>
            <?php if(file_exists($slip_refundPath)): ?>
                <p>หลักฐานการคืนเงินให้ลูกค้า : </p>
                <img src="<?= $slip_refundPath ?>" class="slip_image" alt="slip" style="max-width:50%;max-height:50%;">
                
            <?php endif ?>
            <hr>
        <?php endif ?>
    <?php endif ?>

   
    
    <?php if($orderInfo['Status'] == "30"): ?>
        
        <form method="GET" action="update_status_to_ship.php">
            <center><p>ดำเนินการขนส่ง</p>
                <label for="TrackingNumber"><b>ใส่ Tracking Number</b></label>
                <input type="text" placeholder="Enter Tracking Number" name="TrackingNumber" required style="height:30px;" >
            </center>
            <br><br>
            <input type="hidden" name="OrderID" value="<?= $OrderID ?>">
            <input type="submit" class="btn_Purchase" value="ยืนยัน" onclick="return confirmTrackingNumber()">
        </form>
        <hr>
    <?php endif ?>

    <?php if($orderInfo['Status'] == "70"): ?>
        <?php include('check_refund.php');?>
    <?php endif ?>
    
    <?php if($orderInfo['Status'] != "60" && $orderInfo['Status'] != "40" && $orderInfo['Status'] != "50"): ?>
        <a href="order_cancel.php?OrderID=<?= $OrderID ?>"><input type="button" class="cancel" value="Cancel Order" onclick="return confirmCalcel()"></a>
        <hr>
    <?php endif ?>
    <hr>
    <center><p>พิมพ์เอกสาร</p></center>
    <a href="../report/Invoice.php?OrderID=<?= $OrderID ?>"><input type="button" class="btn_Purchase" value="พิมพ์ใบแจ้งหนี้"></a>
    <hr>
    <a href="../report/E-receipt.php?OrderID=<?= $OrderID ?>"><input type="button" class="btn_Purchase" value="พิมพ์ใบเสร็จ"></a>
    <hr>
    <a href="../report/Delivery_Note.php?OrderID=<?= $OrderID ?>"><input type="button" class="btn_Purchase" value="พิมพ์ใบจัดส่งสินค้า"></a>
    


    
    
</body>

<script>
    function confirmCalcel() {
        // แสดง popup confirm และรับค่าที่ผู้ใช้ตอบ
        var confirmation = confirm("คุณต้องการยกเลิกรายการสั่งซื้อใช่หรือไม่?");
        // หากผู้ใช้กด OK ใน popup confirm จะเปิดลิงก์
        return confirmation;
    }
    function confirmTrackingNumber() {
        // แสดง popup confirm และรับค่าที่ผู้ใช้ตอบ
        var confirmation = confirm("คุณต้องการเพิ่ม Tracking number ใช่หรือไม่?");
        // หากผู้ใช้กด OK ใน popup confirm จะเปิดลิงก์
        return confirmation;
    }
</script>