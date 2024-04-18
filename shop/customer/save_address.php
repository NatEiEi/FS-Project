<?ob_start();?>
<?php
    require_once __DIR__ . '/db.php'; 
    require_once 'JWT_Handler.php'; 
    include('db_thailand.php');
    include __DIR__ . '/counter.php';
    $counter = new counter();

    if (isset($_GET['Name'])) {

        if (isset($_COOKIE['JWT'])) {
            $JWT = new JWT_Handler();
            $Username = $JWT->decode()->Username;
        }
        
        $Name = $_GET['Name'];
        $Tel = $_GET['Tel'];
        $Detail = $_GET['Detail'];
        $Province = $_GET['Province'];
        $Amphure = $_GET['Amphure'];
        $District = $_GET['District'];
        $Zip = $_GET['Zip'];

        //แก้จากโค๊ดเป็นคำ
        $sql = "SELECT * FROM provinces WHERE id='$Province'";
        $query = mysqli_query($conn, $sql);
        $Province = mysqli_fetch_assoc($query)['name_th'];

        $sql = "SELECT * FROM amphures WHERE id='$Amphure'";
        $query = mysqli_query($conn, $sql);
        $Amphure = mysqli_fetch_assoc($query)['name_th'];

        $sql = "SELECT * FROM districts WHERE id='$District'";
        $query = mysqli_query($conn, $sql);
        $District = mysqli_fetch_assoc($query)['name_th'];
            
        $query = mysqli_query($conn, $sql);
        $Zip = mysqli_fetch_assoc($query)['zip_code'];

        $AddressCnt = $counter->getAddressCnt();
        $query =    "INSERT INTO Address (AddressID , Username , Name , Tel , Detail , Province , Amphure , District , Zip) 
                        VALUES ('$AddressCnt', '$Username', '$Name' , '$Tel' , '$Detail' , '$Province' , '$Amphure' , '$District' , '$Zip');";
        $statement = $pdo->prepare($query);
        $statement->execute();

        echo "<script>alert('เพิ่มที่อยู่ใหม่สำเร็จ'); window.location='profile.php?Page=Address';</script>";
    }
?>
