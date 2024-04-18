<?ob_start();?>
<?php
    require_once __DIR__ . '/db.php'; 
    include __DIR__ . '/counter.php';
    require_once 'JWT_Handler.php'; 

    session_start();
    $counter = new counter();
    
    date_default_timezone_set('Asia/Bangkok');
    
    if (isset($_COOKIE['JWT'])) {
        $JWT = new JWT_Handler();
        $Username = $JWT->decode()->Username;
    } else {
        $Username = 'GUEST';
    }
        
    if (isset($_GET['Payment'], $_GET['AddressBilling'], $_GET['AddressShipping'] , $_GET['AddressBuy'])) {
        $Payment = $_GET['Payment'];
        $AddressBuy = $_GET['AddressBuy'];
        $AddressBilling = $_GET['AddressBilling'];
        $AddressShipping = $_GET['AddressShipping'];
        $BuyMethod = $_GET['BuyMethod'];

        $SubTotalPrice = $_GET['SubTotalPrice'];
        $Vat = $_GET['Vat'];
        $ShippingCost = $_GET['ShippingCost'];
        $TotalPrice = $_GET['TotalPrice'];

        $Shipper = $_GET['Shipper'];

        if ($Payment == "CashOnDelivery") {
            // เตรียมจัดส่ง
            $Status = "30";
        } else {
            // รอชำระเงิน
            $Status = "10";
        }

        // insert to order table
        $OrderID = $counter->getOrderCnt();
        $insertOrderQuery = "INSERT INTO `order` (Username, OrderID, Date, Status, Payment, SubTotalPrice, Vat, ShippingCost, TotalPrice) 
                            VALUES ('$Username', '$OrderID', NOW() , '$Status', '$Payment' , '$SubTotalPrice', '$Vat', '$ShippingCost', '$TotalPrice');";
        $Statement = $pdo->prepare($insertOrderQuery);
        $Statement->execute();

        $insertShipingDetailQuery = "INSERT INTO `order_shipping_detail` (OrderID, Shipper) 
                                    VALUES ('$OrderID', '$Shipper');";
        $Statement = $pdo->prepare($insertShipingDetailQuery);
        $Statement->execute();


        //add list product to database
        $products = $_SESSION['ProductBuyList'];
        foreach ($products as $list) {
            $insertListQuery = "INSERT INTO `productlist` (ProductID, OrderID, Qty) VALUES ('{$list['ProductID']}', '$OrderID', '{$list['Qty']}');";
            $statement = $pdo->prepare($insertListQuery);
            $statement->execute();
            
            $query = "SELECT * FROM product WHERE ProductID='{$list['ProductID']}'";
            $statement = $pdo->prepare($query);
            $statement->execute();
            if($statement->rowCount() > 0) {
                $prod = $statement->fetchAll(PDO::FETCH_ASSOC);
            }

            // ลดจำนวนสินค้าใน stock
            $newStock = $prod[0]['QtyStock'] - $list['Qty'];
            $query = "UPDATE product SET QtyStock = '$newStock' WHERE ProductID = '{$list['ProductID']}'";
            $statement = $pdo->prepare($query);
            $statement->execute();
        }
        

        if ($Username == 'GUEST'){
           // delete from cart
            if ($BuyMethod == "BuyFromCart") {
                unset($_SESSION['CartArray']);
                $_SESSION['CartCount'] = 0;
            }
            unset($_SESSION['ProductBuyList']); 
        } else {
            // delete from cart
            if ($BuyMethod == "BuyFromCart") {
                foreach ($products as $list) {
                    $deleteListQuery = "DELETE FROM `cart` WHERE ProductID = '{$list['ProductID']}' AND Username = '$Username';";
                    $statement = $pdo->prepare($deleteListQuery);
                    $statement->execute();
                }
                $_SESSION['CartCount'] = 0;
            }
            unset($_SESSION['ProductBuyList']);
        }

        

        // insert to AddressList Buying Address
        if ($AddressBuy == 'addAddressBuy') {
            $AddressBuy = $counter->getAddressCnt();

            $Name = $_GET['buy_name'];
            $Tel = $_GET['buy_tel'];
            $Detail = $_GET['buy_detail'];
            $Province = $_GET['buy_province'];
            $Amphure = $_GET['buy_amphure'];
            $District = $_GET['buy_district'];
            $Zip = $_GET['buy_zip'];

            $query =    "INSERT INTO Address (AddressID , Username , Name , Tel , Detail , Province , Amphure , District , Zip) 
                        VALUES ('$AddressBuy', '$Username', '$Name' , '$Tel' , '$Detail' , '$Province' , '$Amphure' , '$District' , '$Zip');";
            $statement = $pdo->prepare($query);
            $statement->execute();
        }
        $insertBuyQuery = "INSERT INTO `addresslist` (AddressID, OrderID, Type) VALUES ('$AddressBuy', '$OrderID', 'Buy');";
        $statement = $pdo->prepare($insertBuyQuery);
        $statement->execute();
        

        // insert to AddressList Billing Address
        if ($AddressBilling == 'addAddressBill') {
            $AddressBilling = $counter->getAddressCnt();
            $Name = $_GET['bill_name'];
            $Tel = $_GET['bill_tel'];
            $Detail = $_GET['bill_detail'];
            $Province = $_GET['bill_province'];
            $Amphure = $_GET['bill_amphure'];
            $District = $_GET['bill_district'];
            $Zip = $_GET['bill_zip'];

            $query =    "INSERT INTO Address (AddressID , Username , Name , Tel , Detail , Province , Amphure , District , Zip) 
                        VALUES ('$AddressBilling', '$Username', '$Name' , '$Tel' , '$Detail' , '$Province' , '$Amphure' , '$District' , '$Zip');";
            $statement = $pdo->prepare($query);
            $statement->execute();
        }
        



        // insert to AddressList Shipping Address
        if ($AddressShipping == 'addAddressShip') {
            $AddressShipping = $counter->getAddressCnt();
            $Name = $_GET['ship_name'];
            $Tel = $_GET['ship_tel'];
            $Detail = $_GET['ship_detail'];
            $Province = $_GET['ship_province'];
            $Amphure = $_GET['ship_amphure'];
            $District = $_GET['ship_district'];
            $Zip = $_GET['ship_zip'];

            $query =    "INSERT INTO Address (AddressID , Username , Name , Tel , Detail , Province , Amphure , District , Zip) 
                        VALUES ('$AddressShipping', '$Username', '$Name' , '$Tel' , '$Detail' , '$Province' , '$Amphure' , '$District' , '$Zip');";
            $statement = $pdo->prepare($query);
            $statement->execute();
        }

        // ในกรณีที่อยู่Bill ที่เลือกเหมือนซื้อหรือที่อยู่ส่ง
        if ($AddressBilling == 'sameBill_Buy') {
            $insertBuyQuery = "INSERT INTO `addresslist` (AddressID, OrderID, Type) VALUES ('$AddressBuy', '$OrderID', 'Bill');";
            $statement = $pdo->prepare($insertBuyQuery);
            $statement->execute();
        } else if ($AddressBilling == 'sameBill_Ship') {
            $insertShippingQuery = "INSERT INTO `addresslist` (AddressID, OrderID, Type) VALUES ('$AddressShipping', '$OrderID', 'Bill');";
            $statement = $pdo->prepare($insertShippingQuery);
            $statement->execute();
        } else {
            $insertBillingQuery = "INSERT INTO `addresslist` (AddressID, OrderID, Type) VALUES ('$AddressBilling', '$OrderID', 'Bill');";
            $statement = $pdo->prepare($insertBillingQuery);
            $statement->execute();
        }


        // ในกรณีที่อยู่ส่ง ที่เลือกเหมือนซื้อหรือที่บิล
        if ($AddressShipping == 'sameShip_Buy') {
            $insertBuyQuery = "INSERT INTO `addresslist` (AddressID, OrderID, Type) VALUES ('$AddressBuy', '$OrderID', 'Ship');";
            $statement = $pdo->prepare($insertBuyQuery);
            $statement->execute();
        } else if ($AddressShipping == 'sameShip_Bill') {
            $insertShippingQuery = "INSERT INTO `addresslist` (AddressID, OrderID, Type) VALUES ('$AddressBilling', '$OrderID', 'Ship');";
            $statement = $pdo->prepare($insertShippingQuery);
            $statement->execute();
        } else {
            $insertBillingQuery = "INSERT INTO `addresslist` (AddressID, OrderID, Type) VALUES ('$AddressShipping', '$OrderID', 'Ship');";
            $statement = $pdo->prepare($insertBillingQuery);
            $statement->execute();
        }
        
        

        echo "Successfully";

        // Redirect the page
        if ($Payment == 'CashOnDelivery') {
            // $newURL = 'Home.php';
            echo "<script>alert('สร้างรายการสั่งซื้อสำเร็จ'); window.location='Home.php';</script>";
        } else {
            // $newURL = 'transfer_slip.php?OrderID=' . $OrderID . '&Price=' . $TotalPrice;
            echo "<script>alert('สร้างรายการสั่งซื้อสำเร็จ ดำเนินการชำระเงินต่อ'); window.location='transfer_slip.php?OrderID=$OrderID';</script>";
        }
        // header('Location: ' . $newURL);
        // exit;
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด');</script>";
    }
?>