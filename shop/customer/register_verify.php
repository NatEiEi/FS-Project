<?ob_start();?>
<?php
require_once __DIR__ . '/db.php'; 
include_once __DIR__ . '/navbar.php';

// อ่านไฟล์รหัสผ่านที่ไม่ปลอดภัยจากไฟล์ text
$unsafePasswords = file(__DIR__ . '/worst_passwords.txt', FILE_IGNORE_NEW_LINES);

// รับค่าจากฟอร์ม
$Username = $_POST['Username'];
$FName = $_POST['FName'];
$LName = $_POST['LName'];
$Email = $_POST['Email'];
$Password = $_POST['Password'];
$Tel = $_POST['Tel'];

// ตรวจสอบว่ารหัสผ่านที่ผู้ใช้ป้อนเข้ามาเป็นรหัสผ่านที่ไม่ปลอดภัยหรือไม่
if (in_array($Password, $unsafePasswords)) {
    echo "รหัสผ่านที่คุณใช้ไม่ปลอดภัย โปรดเลือกรหัสผ่านอื่น";
} else {
    // ตรวจสอบว่ามีชื่อผู้ใช้นี้อยู่ในฐานข้อมูลหรือไม่
    $query = "SELECT * FROM customer WHERE Username = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$Username]);
    $escaped_Username = htmlspecialchars($Username, ENT_QUOTES, 'UTF-8');
    $escaped_FName = htmlspecialchars($FName, ENT_QUOTES, 'UTF-8');
    $escaped_LName = htmlspecialchars($LName, ENT_QUOTES, 'UTF-8');
    $escaped_Email = htmlspecialchars($Email, ENT_QUOTES, 'UTF-8');
    $escaped_Tel = htmlspecialchars($Tel, ENT_QUOTES, 'UTF-8');




    if($statement->rowCount() == 0) {
        // ถ้าไม่มีชื่อผู้ใช้นี้อยู่ในระบบ ให้เพิ่มผู้ใช้ใหม่
        $hashed_password = password_hash($Password, PASSWORD_ARGON2I);
    
        $query = "INSERT INTO customer (Username ,Password, FName , LName  , Tel , Email) 
                  VALUES ( ? , ? , ? , ?  , ? , ?  );";
        $statement = $pdo->prepare($query);
        $statement->execute([$escaped_Username,$hashed_password, $escaped_FName, $escaped_LName ,  $escaped_Tel , $escaped_Email]);
        //$statement->execute([$Username,$hashed_password, $FName, $LName , $Tel , $Email]);
        echo "$hashed_password";
        echo "บันทึกข้อมูลผู้ใช้เรียบร้อยแล้ว";
    } else {
        // ถ้ามีชื่อผู้ใช้นี้อยู่ในระบบแล้ว ให้แสดงข้อความแจ้งเตือน
        echo "Username is already used!";
    }
}
?>