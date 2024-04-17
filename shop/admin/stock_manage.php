<?php
    require '../db.php'; 
    include __DIR__ . '/adminLogin.php';

    if (isset($_GET["begin"])){
        $begin = $_GET["begin"];
        $end = $_GET["end"];
    } else {
        $begin = "";
        $end = "";
    }

    if (isset($_GET["FilterName"])) {
        $FilterName = $_GET["FilterName"];
        if ($end != "") {
            $query = "SELECT * FROM Product WHERE ProductName Like '%$FilterName%' AND ProductID between '$begin' and '$end' order by ProductID;";
        } else {
            $query = "SELECT * FROM Product WHERE ProductName Like '%$FilterName%';";
        }
    } else {
        $FilterName = "";
        if ($end != "") {
            $query = "SELECT * FROM Product WHERE ProductID between '$begin' and '$end' order by ProductID;";
        } else {
            $query = "SELECT * FROM Product";
        }
    }
    $statement = $pdo->prepare($query);
    $statement->execute();
    if($statement->rowCount() > 0) {
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);
    }

?>

<center><h2>เพิ่ม - ลบ สินค้าใน stock</h2></center>
<center>
    <FORM METHOD = "GET" ACTION = "selectProduct.php">
        รหัสตั้งแต่
        <Input Type="text" Name="begin" Size=4 MaxLength=4 value="">&nbsp&nbsp&nbsp&nbsp-
        &nbsp&nbsp&nbsp&nbsp&nbsp ถึง
        <Input Type="text" Name="end" Size=4 MaxLength=4 value="">
        <br>
        กรองชื่อ
        <Input Type="text" Name="FilterName" Size=4 MaxLength=4 value="">
        <Input Type="submit" Value="ยืนยัน">
        <Input Type="Reset" Value="reset"> 
    </FORM>

Filter By ProductID : <?= $begin ?> - <?= $end ?> and By Name : <?= $FilterName ?>
</center>

<style>
    .table-container {
        max-height: 350px; /* ปรับความสูงตามที่คุณต้องการ */
        overflow: auto;
    }
    button[name="addButton"] {
    /* กำหนดสไตล์ของปุ่มที่มี attribute name เป็น "addButton" */
    padding: 10px 20px; 
    background-color: #4CAF50; /* กำหนดสีพื้นหลังของปุ่มสำหรับ "เพิ่ม" */
    color: white; 
    border: none; 
    border-radius: 5px; 
    cursor: pointer; 
    margin-right: 10px; 
}

button[name="addButton"]:hover {
    background-color: #45a049; /* กำหนดสีพื้นหลังของปุ่มเมื่อนำเมาส์ไปชี้ที่ปุ่มสำหรับ "เพิ่ม" */
}

button[name="addButton"]:active {
    background-color: #4CAF50; 
}

button[name="deleteButton"] {
    /* กำหนดสไตล์ของปุ่มที่มี attribute name เป็น "deleteButton" */
    padding: 10px 20px; 
    background-color: #f44336; /* กำหนดสีพื้นหลังของปุ่มสำหรับ "ลบ" */
    color: white; 
    border: none; 
    border-radius: 5px; 
    cursor: pointer; 
}

button[name="deleteButton"]:hover {
    background-color: #d32f2f; /* กำหนดสีพื้นหลังของปุ่มเมื่อนำเมาส์ไปชี้ที่ปุ่มสำหรับ "ลบ" */
}

button[name="deleteButton"]:active {
    background-color: #f44336; 
}

</style>
<div class="table-container">
        <table border='1' style='width: 60%; margin: 0 auto;'>
        <tr>
            <th style='width: 15%;'>Image</th>
            <th style='width: 15%;'>Product ID</th>
            <th style='width: 15%;'>Product Name</th>
            <th style='width: 10%;'>StockQty</th>
        </tr>

        <?php foreach ($products as $row): ?>
            <?php  $fileName = $row['ProductID'];
                $filePath = "../images/product/" . $fileName . "-1".".jpg"; 
                if (file_exists($filePath)) {
                } else {
                    $filePath = "../images/boss_Dog.jpg";
                }
            ?>
        <tr>
            <td style='text-align: center;'><img src="<?= $filePath ?>" style="width: 100px;height:60px;"></td>
            <td style='text-align: center;'><?= $row['ProductID'] ?></td>
            <td style='text-align: center;'><?= $row['ProductName'] ?></td>
            <td style='text-align: center;'><?= $row['QtyStock'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>





<hr>
<center>
<div class="container">
<form action="add_stock.php" method="post">

    <label for="ProductID">เลือกรหัสสินค้า:</label>
    <select id="ProductID" name="ProductID" onchange="updateStock(this.selectedIndex)">
        <?php foreach ($products as $row): ?>
            <option value="<?= $row['ProductID'] ?>"><?= $row['ProductID'] ?></option>
        <?php endforeach; ?>
    </select>
    <br><br>
    <label for="qty">จำนวน:</label>
    <input type="number" name="qty" id="qty" value="0" min="0">
    <br><br>
    <button type="submit" name="addButton"  onclick="return confirmAdd()">เพิ่ม</button>
    <button type="submit" name="deleteButton"  onclick="return confirmDelete()">ลบ</button>
</form>
</div>


<script>
    function confirmAdd() {
        // แสดง popup confirm และรับค่าที่ผู้ใช้ตอบ
        var confirmation = confirm("คุณต้อง 'เพิ่มจำนวน' สินค้าใช่หรือไม่?");
        // หากผู้ใช้กด OK ใน popup confirm จะเปิดลิงก์
        return confirmation;
    }
    function confirmDelete() {
        // แสดง popup confirm และรับค่าที่ผู้ใช้ตอบ
        var confirmation = confirm("คุณต้องการ 'ลบจำนวน' สินค้าใช่หรือไม่?");
        // หากผู้ใช้กด OK ใน popup confirm จะเปิดลิงก์
        return confirmation;
    }
</script>