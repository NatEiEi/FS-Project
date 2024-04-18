<?ob_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backend Management</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .navbar {
            overflow: hidden;
            background-color: #333;
        }


        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }


        .navbar a.right {
            float: right;
        }


        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        .profile {
            color: white;
            padding: 15px 100px;
            text-align: right;

        }
    </style>
</head>
<body>
<?php
require_once 'JWT_Handler_admin.php'; 

if(isset($_COOKIE['admin'])) {
    $JWT = new JWT_Handler_admin();
    $EmployeeID = $JWT->decode()->EmployeeID;
    $Role = $JWT->decode()->Role;
}
?>
<div class="navbar">
    <a href="selectProduct.php">Product List</a>
    <a href="insertProduct.php">Insert Product</a>
    <a href="allOrder.php">See Order</a>
    <!-- <a href="report.php">Report</a> -->
    <a href="http://localhost:3000">Report</a>
    <a href="stock_manage.php">Stock</a>
    <a href="adminLogout.php">Logout</a>

    <div class="profile">ID : <?= $EmployeeID ?></div>
</div>

</body>
</html>
