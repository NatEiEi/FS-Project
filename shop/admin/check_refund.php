<?php
    $query = "SELECT *
            FROM order_refund_detail
            WHERE OrderID = '$OrderID'";
    $statement = $pdo->prepare($query);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $orders = $statement->fetchAll(PDO::FETCH_ASSOC);
        $order_refund = $orders[0];
    } else {
        echo "No order";
    }
?>


<style>

    button[name="approve"] {
    /* กำหนดสไตล์ของปุ่มที่มี attribute name เป็น "addButton" */
    padding: 10px 20px; 
    background-color: #4CAF50; /* กำหนดสีพื้นหลังของปุ่มสำหรับ "เพิ่ม" */
    color: white; 
    border: none; 
    border-radius: 5px; 
    cursor: pointer; 
    margin-right: 10px; 
}

button[name="approve"]:hover {
    background-color: #45a049; /* กำหนดสีพื้นหลังของปุ่มเมื่อนำเมาส์ไปชี้ที่ปุ่มสำหรับ "เพิ่ม" */
}

button[name="approve"]:active {
    background-color: #4CAF50; 
}

button[name="reject"] {
    /* กำหนดสไตล์ของปุ่มที่มี attribute name เป็น "deleteButton" */
    padding: 10px 20px; 
    background-color: #f44336; /* กำหนดสีพื้นหลังของปุ่มสำหรับ "ลบ" */
    color: white; 
    border: none; 
    border-radius: 5px; 
    cursor: pointer; 
}

button[name="reject"]:hover {
    background-color: #d32f2f; /* กำหนดสีพื้นหลังของปุ่มเมื่อนำเมาส์ไปชี้ที่ปุ่มสำหรับ "ลบ" */
}

button[name="reject"]:active {
    background-color: #f44336; 
}

</style>

<body>
    <h2>ขอคืนเงิน</h2>
    <p>เหตุผลในการขอคืนเงิน : <?= $order_refund['reason'] ?></p>
    ช่องทางรับเงินคืน
    <p>ชื่อธนาคาร : <?= $order_refund['bank'] ?></p>
    <p>เลขบัญชี : <?= $order_refund['account_number'] ?></p>
    <p>ชื่อบัญชี : <?= $order_refund['account_name'] ?></p>
    <p>ร้องขอเมื่อวันที่ : <?= $order_refund['submitted_at'] ?></p>
    <form method='post' action='approve_refund.php' enctype="multipart/form-data" >
        <input type='hidden' name='OrderID' value="<?= $OrderID ?>">
        หลักฐานการคืนเงินให้ลูกค้า (กรณีที่อนุมัติ)
        <input type="file" id="imageUpload" name="imageUpload" onchange="previewImage(event)" required>
        <div id="imagePreview"></div>
        <button type='submit' name='approve' onclick="return confirmApprove()">อนุมัติ</button>
    </form>
    <form method='post' action='approve_refund.php'>
        <input type='hidden' name='OrderID' value="<?= $OrderID ?>">
        <button type='submit' name='reject' onclick="return confirmReject()">ไม่อนุมัติ</button>
    </form>

    <hr>
</body>
<script>
function previewImage(event) {
  var reader = new FileReader();
  reader.onload = function(){
    var output = document.getElementById('imagePreview');
    output.innerHTML = '<img src="' + reader.result + '" style="max-width:50%;max-height:50%;">';
  }
  reader.readAsDataURL(event.target.files[0]);
}
</script>

<script>
    function confirmApprove() {
        // แสดง popup confirm และรับค่าที่ผู้ใช้ตอบ
        var confirmation = confirm("อนุมัติการคืนเงินใช่หรือไม่?");
        // หากผู้ใช้กด OK ใน popup confirm จะเปิดลิงก์
        return confirmation;
    }
    function confirmReject() {
        // แสดง popup confirm และรับค่าที่ผู้ใช้ตอบ
        var confirmation = confirm("ไม่อนุมัติการคืนเงินใช่หรือไม่?");
        // หากผู้ใช้กด OK ใน popup confirm จะเปิดลิงก์
        return confirmation;
    }
</script>