<?php 
    require_once __DIR__ . '/db.php'; 
    include_once __DIR__ . '/navbar.php';
  
    $OrderID = $_GET['OrderID'];
    $OrderQuery = "SELECT * FROM `order` o, order_status os WHERE OrderID = '$OrderID' AND o.Status = os.StatusID";
    $statement = $pdo->prepare($OrderQuery);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $orders = $statement->fetchAll(PDO::FETCH_ASSOC);
        $orderInfo = $orders[0];
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แบบฟอร์มขอคืนเงิน</title>
    <style>

form {
    width: 400px;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: 0 auto; /* ทำให้ฟอร์มอยู่ตรงกลาง */
}

h2 {
    text-align: center; /* จัดข้อความให้อยู่ตรงกลาง */
    margin-bottom: 20px; /* ระยะห่างด้านล่างของหัวข้อ */
}

input[type="text"],
textarea,
select {
    width: 100%; /* ขยายขนาดให้เต็มช่อง */
    padding: 8px; /* ขนาดของช่อง */
    margin-bottom: 15px; /* ระยะห่างด้านล่างของช่อง */
    box-sizing: border-box; /* ไม่ให้ขนาดของช่องขยายเพิ่มเติม */
    border: 1px solid #ccc; /* เส้นขอบของช่อง */
    border-radius: 4px; /* ทำให้มีเส้นมนเรียบขึ้น */
}

input[type="submit"] {
    width: 100%; /* ขยายขนาดให้เต็มช่อง */
    padding: 10px; /* ขนาดของปุ่ม */
    background-color: #007bff; /* สีพื้นหลังของปุ่ม */
    color: #fff; /* สีข้อความ */
    border: none; /* ไม่มีเส้นขอบ */
    border-radius: 4px; /* ทำให้มีเส้นมนเรียบขึ้น */
    cursor: pointer; /* เปลี่ยนรูปแบบเมาส์เป็นไอคอนเมาส์ */
}

input[type="submit"]:hover {
    background-color: #0056b3; /* เปลี่ยนสีพื้นหลังของปุ่มเมื่อเมาส์ไปที่ปุ่ม */
}

#error_message {
    color: red; /* สีข้อความเป็นสีแดง */
}

/* Responsive Styles (optional) */
@media screen and (max-width: 600px) {
    form {
        width: 90%; /* ปรับขนาดฟอร์มเมื่อหน้าจอเล็ก */
    }
}


input[type="radio"],
textarea {
    margin-left: 0;
}


    </style>
</head>
<body>
  
    <h2>แบบฟอร์มขอคืนเงิน</h2>
    <form action="submit_refund_request.php" method="POST">
        <label>รหัสรายการสั่งซื้อ : <?= $OrderID ?></label><br>
        
        <label>เหตุผลในการขอคืนเงิน:</label><br>
        <input type="radio" id="reason_cancel" name="reason" value="ไม่พอใจกับสินค้า" onclick="toggleOtherReason(false)">
        <label for="reason_cancel">ไม่พอใจกับสินค้า</label><br>
        <input type="radio" id="reason_wrong_item" name="reason" value="สั่งสินค้าผิด" onclick="toggleOtherReason(false)">
        <label for="reason_wrong_item">สั่งสินค้าผิด</label><br>
        <input type="radio" id="reason_delivery_issue" name="reason" value="ข้อมูลจัดส่งไม่ถูกต้อง" onclick="toggleOtherReason(false)">
        <label for="reason_delivery_issue">ข้อมูลจัดส่งไม่ถูกต้อง</label><br>
        <input type="radio" id="reason_other" name="reason" value="อื่นๆ" onclick="toggleOtherReason(true)">
        <label for="reason_other">อื่นๆ</label><br>
        <textarea id="other_reason" name="other_reason" rows="4" cols="50" style="display: none;"></textarea><br><br>


        <!-- เพิ่มช่องทางรับเงิน -->
        <label for="bank">ช่องทางรับเงิน:</label><br>
        <select id="bank" name="bank">
            <option value="กสิกร">กสิกร</option>
            <option value="กรุงศรี">กรุงศรี</option>
            <option value="กรุงไทย">กรุงไทย</option>
            <option value="อื่นๆ">อื่นๆ</option>
        </select><br><br>

        <!-- เพิ่มช่องสำหรับอื่นๆ ในกรณีที่ผู้ใช้เลือก "อื่นๆ" -->
        <div id="other_bank_container" style="display: none;">
            <label for="other_bank">ระบุชื่อธนาคาร:</label><br>
            <input type="text" id="other_bank" name="other_bank"><br><br>
        </div>

        <!-- เพิ่มช่องสำหรับใส่เลขบัญชี -->
        <label for="account_number">เลขบัญชี:</label><br>
        <input type="text" id="account_number" name="account_number"><br><br>

        <!-- เพิ่มช่องสำหรับใส่ชื่อบัญชี -->
        <label for="account_name">ชื่อบัญชี:</label><br>
        <input type="text" id="account_name" name="account_name"><br><br>

        <input type="hidden" id="OrderID" name="OrderID" value="<?= $OrderID ?>">
        <input type="submit" value="ส่งคำขอขอคืนเงิน">
    </form>

    <script>
        function toggleOtherReason(isChecked) {
            var otherReasonField = document.getElementById("other_reason");
            otherReasonField.style.display = isChecked ? "block" : "none";
            if (!isChecked) {
                otherReasonField.value = ""; // เคลียร์ค่าเมื่อไม่มีการเลือก "อื่นๆ"
            }
        }

        // เมื่อเลือก "อื่นๆ" ในช่องทางรับเงิน
        document.getElementById("bank").addEventListener("change", function() {
            var otherBankContainer = document.getElementById("other_bank_container");
            if (this.value === "อื่นๆ") {
                otherBankContainer.style.display = "block";
            } else {
                otherBankContainer.style.display = "none";
                document.getElementById("other_bank").value = ""; // เคลียร์ค่า
            }
        });
    </script>
</body>
</html>
