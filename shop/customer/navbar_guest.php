<?ob_start();?>
<?php
    session_start();
    if(isset($_SESSION['CartCount'])) {
        $count = $_SESSION['CartCount'];
    } else {
        $count = 0;
    }
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <style>
        /* CSS เพื่อปรับแต่งรูปแบบ */
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center; /* จัดตำแหน่งตรงกลางในแนวสูง */
            padding: 10px 20px;
            background-color: white;
            color: white;
            z-index: 9999; /* ค่า z-index สูงที่สุด */
            box-shadow: 0 2px 8px 0 rgba(0,0,0,0.2);
        }
        .menu {
            font-size: 20px;
            float: left;
            display: block;
            color: black;
            text-align: center;
            padding: 5px 20px;
            text-decoration: none;
        }
        .menu:hover {
            border-bottom: 2px solid rgb(44, 92, 155);
        }
        .divider {
            border: none;
            border-left: 1px solid black; /* เส้นขอบซ้ายของเส้นกั้น */
            height: 20px; /* สูงขนาดตามต้องการ */
            width: 1px; /* ความกว้างของเส้นกั้น */
            margin-right: 20px; /* ระยะห่างของเส้น */
            margin-left: 5px; /* ระยะห่างของเส้น */
        }
        .cart-icon-container {
            display: flex;
            align-items: center;
            margin-right: 150px;
        }
        .cart-icon {
            cursor: pointer;
            width: 30px;
            height: 30px;
            
        }
        .cart-icon img {
            width: 100%;
            height: auto;
        }
        
        .cart-icon::after {
            margin-right: 150px;
            content: attr(data-count);
            position: absolute;
            top: 5px;
            right: 10px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 14px;
        }
        .cart-icon:hover {
            border-bottom: 2px solid rgb(44, 92, 155);
        }
        .logo {
            margin-left: 140px;
            width: 130px;
            height: 38px;
        }
    
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <!-- <a href="#" class="menu" style="margin-left: 120px;">Home</a> -->
            <a href="Home.php"">
                <img class="logo" src="../images/icon/logo_techshop.png" alt="logo">
            </a>


        </div>



        <div class="cart-icon-container">
            
            <a href="login.php" class="menu">Login</a>
            <hr class="divider">
            <div class="cart-icon" data-count="<?= $count ?>">
                <a href="Cart.php"">
                    <img src="../images/icon/cart_icon.png" alt="cart_icon">
                </a>
            </div>
        </div>
    </div>
</body>
</html>



