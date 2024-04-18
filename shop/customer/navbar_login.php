<?ob_start();?>
<?php
    session_start();
    if(isset($_SESSION['CartCount'])) {
        $count = $_SESSION['CartCount'];
    } else {
        $count = 0;
    }

    require_once 'JWT_Handler.php';

    if (isset($_COOKIE['JWT'])){
        $JWT = new JWT_Handler();
    }

?>

<!DOCTYPE html>
<html lang="en">
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
        .cart-icon:hover {
            border-bottom: 2px solid rgb(44, 92, 155);
        }
        .cart-icon img {
            width: 100%;
            height: auto;
        }
        .profile-icon {
            margin-right: 20px;
            cursor: pointer;
            width: 30px;
            height: 30px;

        display: inline-block;
        cursor: pointer;
            
        }
        .profile-icon img {
            width: 100%;
            height: auto;
        }

        .cart-icon::after {

            content: attr(data-count);
            position: relative;
            top: -40px;
            right: -20px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 14px;
        }
        .logo {
            margin-left: 140px;
            width: 130px;
            height: 38px;
        }

        
        .profile-icon .popup {
            display: none;
            position: relative;
            top: 0px;
            right: 0;
            background-color: #f9f9f9;
            min-width: 200px;
            box-shadow: 0 2px 8px 0 rgba(0,0,0,0.2);
            z-index: 1;
            padding: 10px;
        }
        
        .profile-icon.active .popup {
            display: block;
        }
        
        
        .popup a {
            display: block;
            color: black;
            padding: 5px;
            text-decoration: none;
        }
        .popup p {
            display: block;
            color: black;
            padding: 0px 5px;
        }
        
        .popup a:hover {
            background-color: #ddd;
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
            <div class="profile-icon" onclick="toggleProfilePopup()">
                <img src="../images/icon/profile_icon.png" alt="cart_icon">
                <div class="popup">
                    <p>ชื่อ <?= $JWT->decode()->FName ?>  <?=  $JWT->decode()->LName ?></p>
                    <a href="profile.php">หน้าโปรไฟล์</a>
                    <a href="profile.php?Page=Address">ที่อยู่</a>
                    <a href="profile.php?Page=OrderHistory">ดูประวัติการสั่งซื้อ</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
            <hr class="divider">
            <div class="cart-icon" data-count="<?= $count ?>">
                <a href="Cart.php">
                    <img src="../images/icon/cart_icon.png" alt="cart_icon">
                </a>
            </div>
        </div>
    </div>

<script>
    // เพิ่ม Event Listener เพื่อตรวจจับการคลิกที่ส่วนอื่นของหน้าจอเพื่อปิด popup
    document.addEventListener('click', function(event) {
        var profileIcon = document.querySelector('.profile-icon');
        var popup = profileIcon.querySelector('.popup');
        
        // ตรวจสอบว่าคลิกอยู่ภายใน popup หรือไม่
        var isClickInsidePopup = profileIcon.contains(event.target);

        // ถ้าไม่ใช่คลิกอยู่ภายใน popup ให้ปิด popup
        if (!isClickInsidePopup) {
            profileIcon.classList.remove('active');
        }
    });
    function toggleProfilePopup() {
        var profileIcon = document.querySelector('.profile-icon');
        profileIcon.classList.toggle('active');
    }
</script>
</body>
</html>
