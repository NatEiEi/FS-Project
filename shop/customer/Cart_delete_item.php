<?php
    require_once __DIR__ . '/db.php'; 
    require_once 'JWT_Handler.php';
    session_start();
    
    $ProductID = $_GET["ProductID"];
    if (isset($_COOKIE['JWT'])){
        $JWT = new JWT_Handler();
        $Username = $JWT->decode()->Username;  
        $query = "DELETE FROM Cart WHERE ProductID = '$ProductID' AND Username = '$Username'";
        $statement = $pdo->prepare($query);
        $statement->execute();
        $_SESSION['CartCount'] -= 1;
    } else {
        $CartArray = $_SESSION['CartArray'];
        foreach ($CartArray as $key => &$item) {
            if ($item["ProductID"] == $ProductID) {
                unset($CartArray[$key]); // ลบรายการที่ตรงเงื่อนไขออกจากตะกร้า
                break;
            }
        }
        $_SESSION['CartArray'] = $CartArray; // อัปเดตตะกร้าใน session
        $_SESSION['CartCount'] -= 1;
    }
    
  

    echo "ลบสำเร็จเรียบร้อยแล้ว...";
    header("Location: Cart.php");
    exit;

?>
