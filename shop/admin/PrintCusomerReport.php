<?ob_start();?>
<?php 
    require '../db.php';
    require ("../libraries/fpdf/fpdf.php");
  
    date_default_timezone_set('Asia/Bangkok');
    // $start_date = '2023-01-01 00:00:00';
    // $end_date = date('Y-m-d 23:59:59');
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $print_date = date('Y-m-d H:i:s');

    $query = "SELECT o.Username, COUNT(DISTINCT o.OrderID) AS TotalOrders, SUM(p.PricePerUnit * pl.Qty) AS TotalAmount
    FROM `order` o
    JOIN productlist pl ON o.OrderID = pl.OrderID
    JOIN product p ON pl.ProductID = p.ProductID
    GROUP BY o.Username";
    
  
  
  
    $result = $pdo->query($query);

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($result->rowCount() > 0) {
    // วนลูปเพื่อดึงข้อมูลและแสดงผล
    echo "<table border='1'>
            <tr>
                <th>ชื่อลูกค้า</th>
                <th>จำนวนออเดอร์</th>
                <th>ยอดเงินรวม (บาท)</th>
            </tr>";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>".$row['Username']."</td>";
        echo "<td>".$row['TotalOrders']."</td>";
        echo "<td>".$row['TotalAmount']."</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "ไม่พบข้อมูล";
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$pdo = null;
?>

