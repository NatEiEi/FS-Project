<?php
    require '../db.php'; 
    include __DIR__ . '/adminLogin.php';

    date_default_timezone_set('Asia/Bangkok');
    $currentYearBE = date("Y");
    echo "<p style='text-align: center;'>วันที่ : " .date("d-m") ."-$currentYearBE " . " เวลา : " . date("H:i:s") ."</p>";


    echo 
        '<center>
        Filter by date :
        <form action="allOrder.php" method="GET">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date">
        
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date">
            <label for="Status">Status :</label>
                <select name="Status" id="Status">
                    <option value="All">ทั้งหมด</option>
                    <option value="10">รอชำระเงิน</option>
                    <option value="20">รอตรวจสอบการชำระ</option>
                    <option value="30">เตรียมจัดส่ง</option>
                    <option value="40">กำลังขนส่ง</option>
                    <option value="50">เสร็จสิ้น</option>
                    <option value="60">ยกเลิก</option>
                    <option value="70">ขอคืนเงิน</option>
                    <option value="80">คืนเงินแล้ว</option>
                </select>
            <button type="submit">Submit</button>
        </form>
        </center>';

    if (empty($_GET['start_date'])) {
        $start_date = '2023-01-01 00:00:00';
    } else {
        $start_date = date('Y-m-d 00:00:00', strtotime($_GET['start_date']));
    }
    if (empty($_GET['end_date'])) {
        $end_date = date('Y-m-d 23:59:59');
    } else {
        $end_date = date('Y-m-d 23:59:59', strtotime($_GET['end_date']));
    }

    echo "<center>Filter Start Date : $start_date - End Date : $end_date</center>";
    
    $status_condition = "";
    if(!empty($_GET['Status'])) {
        $Status = $_GET['Status'];
        if ($Status == "All") {

        } else {
            $status_condition = "AND Status = '$Status'";
        }
    }

    $query = "SELECT * FROM `order` o, order_status os
                WHERE Date BETWEEN '$start_date' 
                        AND '$end_date' $status_condition 
                        AND o.Status = os.StatusID
                ORDER BY Date";

    // $query = "SELECT * FROM `order` WHERE Date BETWEEN '$start_date' AND '$end_date' ORDER BY Date";
    $statement = $pdo->prepare($query);
    $statement->execute();
    if($statement->rowCount() > 0) {
        $orders = $statement->fetchAll(PDO::FETCH_ASSOC);

        
        echo "
        <br><br>
        <table border='1' style='width: 60%; margin: 0 auto;'>
        <tr>
            <th style='width: 15%;'>Date</th>
            <th style='width: 15%;'>Username</th>
            <th style='width: 15%;'>OrderID</th>
            <th style='width: 20%;'>Status</th>
            <th style='width: 15%;'>Management</th>
        </tr>";

        foreach ($orders as $row) {
            $pathReceipt = "../customer/E-receipt.php?OrderID=" . $row['OrderID'];
            $pathManagement = "order_management.php?OrderID=" . $row['OrderID'];
            echo "<tr>
                <td style='text-align: center;'>{$row['Date']}</td>
                <td style='text-align: center;'>{$row['Username']}</td>
                <td style='text-align: center;'>{$row['OrderID']}</td>
                <td style='text-align: center;'>{$row['Status_TH']}</td>
                <td style='text-align: center;'><a href='$pathManagement'>Manage</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<br><center>No records found...</center>";
    }
?>