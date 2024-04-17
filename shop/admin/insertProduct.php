<?php
    include __DIR__ . '/adminLogin.php';
?>

<center>
    <h1>ใส่ข้อมูลของลูกค้าที่ต้องการ</h1><br>
    <form method="post" action="saveInsert.php" enctype="multipart/form-data" style='text-align: start; padding:0px 30%;'>
        ProductID &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="text" name="ProductID" size="4" maxlength="4"><br>
        ProductName &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="text" name="ProductName" size="20" maxlength="20"><br>
        PricePerUnit &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="text" name="PricePerUnit" size="10" maxlength="10"><br>
        Cost &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="text" name="Cost" size="10" maxlength="10"><br>
        QtyStock &nbsp;&nbsp;&nbsp;
        <input type="text" name="QtyStock" size="10" maxlength="10"><br>
        Detail &nbsp;&nbsp;&nbsp;
        <input type="text" name="Detail" size="40" maxlength="10"><br>
        Upload Product Image
        <input type="file" id="imageUpload" name="imageUpload""><br>

        <input type="submit" value="ยืนยัน">
        <input type="reset" value="ยกเลิก">
    </form>
</center>
