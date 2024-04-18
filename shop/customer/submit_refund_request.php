<?ob_start();?>
<?php
    require_once __DIR__ . '/db.php'; 
    
    date_default_timezone_set('Asia/Bangkok');
    // รับค่าจากแบบฟอร์ม
    $OrderID = $_POST['OrderID'];
    $reason = $_POST['reason'];

    // หากมีการเลือก "อื่นๆ"
    if ($reason === "อื่นๆ") {
        $reason = $_POST['other_reason'];
    }
    $bank = $_POST['bank'];

    if ($bank === "อื่นๆ") {
        $bank = $_POST['other_bank'];
    }

    $account_number = $_POST['account_number'];
    $account_name = $_POST['account_name'];

    $query = "SELECT Status FROM `order` WHERE OrderID = '$OrderID'";
    $statement = $pdo->prepare($query);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $orders = $statement->fetchAll(PDO::FETCH_ASSOC);
        $order = $orders[0];
        $previous_status = $order['Status'];
    }  
    echo $previous_status;
    $query = "INSERT INTO order_refund_detail (OrderID, reason, bank, account_number, account_name, submitted_at, previous_status) 
                VALUES ('$OrderID', '$reason', '$bank', '$account_number', '$account_name', NOW() , '$previous_status')";
    $statement = $pdo->prepare($query);
    $statement->execute();

    $query = "UPDATE `order` SET status = '70' WHERE OrderID = '$OrderID'";
    $statement = $pdo->prepare($query);
    $statement->execute();

    header("Location: order_detail.php?OrderID=" . $OrderID);
    exit();
?>
