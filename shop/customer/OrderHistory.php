<?ob_start();?>
<?php
    require_once __DIR__ . '/db.php'; 
    // include_once __DIR__ . '/navbar.php';
    require_once 'JWT_Handler.php'; 

    if (isset($_COOKIE['JWT'])) {
        $JWT = new JWT_Handler();
        $Username = $JWT->decode()->Username;
    }
    
    $query = "SELECT * FROM `order` o, order_status os WHERE Username='$Username' AND o.Status = os.StatusID ORDER BY o.Date DESC";


    $statement = $pdo->prepare($query);
    $statement->execute();
    if($statement->rowCount() > 0) {
        $orders = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
?>


<head>
    <link rel="stylesheet" href="./css/history.css">
</head>
<body>
<div class="container-history">
    <br>
    <h1>ประวัติรายการสั่งซื้อ</h1>
    <div class="filter">
        <a href="#" style="width: 10%; font-size:13px;" id="All_btn"onclick="filterItems('All')">ทั้งหมด</a>
        <a href="#" style="width: 15%; font-size:13px;" onclick="filterItems('รอชำระเงิน')">รอชำระเงิน</a>
        <a href="#" style="width: 20%; font-size:13px;" onclick="filterItems('รอตรวจสอบการชำระ')">รอตรวจสอบการชำระ</a>
        <a href="#" style="width: 15%; font-size:13px;" onclick="filterItems('เตรียมจัดส่ง')">เตรียมจัดส่ง</a>
        <a href="#" style="width: 15%; font-size:13px;" onclick="filterItems('กำลังขนส่ง')">กำลังขนส่ง</a>
        <a href="#" style="width: 10%; font-size:13px;" onclick="filterItems('เสร็จสิ้น')">เสร็จสิ้น</a>
        <a href="#" style="width: 15%; font-size:13px;" onclick="filterItems('ยกเลิก')">ยกเลิก/คืนเงิน</a>
    </div>
    <ul>
        <?php if(isset($orders)): ?>
            <?php foreach($orders as $order): ?>  
            <a href="order_detail.php?OrderID=<?= $order['OrderID'] ?>">
            <div class="<?= $order['Status'] ?>">
                <div class="item">
                    <p class="name" style="color: black;">หมายเลขรายการ : <?= $order['OrderID'] ?></p>
                    <p class="date" style="color: black;">วันที่ : <?= $order['Date'] ?> <br> รวม : <?= $order['TotalPrice'] ?> บาท </p>
                    <p class="status" style="color: black; padding: 0px 20px;">สถานะ : <?= $order['Status_TH'] ?></p>
                </div>
            </div>
            </a>
            
            <?php endforeach; ?>
        <?php else: ?>
            <div><br><center><p>ไม่มีรายการสั่งซื้อ</p></center><br></div>
        <?php endif ?>
        
    </ul>


</div>
<script>

function filterItems(status) {
    var items = document.querySelectorAll('.item');
    items.forEach(function(item) {
        var itemStatus = item.querySelector('.status').innerText.split(' : ')[1]; // ดึงข้อมูล Status จาก element
        if (itemStatus === 'ขอคืนเงิน' || itemStatus === 'คืนเงินแล้ว') {
            itemStatus = 'ยกเลิก';
        }

        if (status === 'All' || itemStatus === status) {
            item.parentElement.style.display = 'block';
        } else {
            item.parentElement.style.display = 'none';
        }
    });
}

</script>

</body>
</html>