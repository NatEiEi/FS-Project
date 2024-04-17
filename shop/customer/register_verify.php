<?php
    require_once __DIR__ . '/db.php'; 
    include_once __DIR__ . '/navbar.php';
?>

<?php
    $Username = $_POST['Username'];
    $FName = $_POST['FName'];
    $LName = $_POST['LName'];
    $Password = $_POST['Password'];
    $Tel = $_POST['Tel'];

    $query = "SELECT * FROM customer WHERE Username = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$Username]);

    if($statement->rowCount() == 0) {
        $hashed_password = password_hash($Password, PASSWORD_ARGON2I);
    
        $query =    "INSERT INTO customer (Username ,Password, FName , LName  , Tel) 
                    VALUES ( ? , ? , ? , ? , ? );";
        $statement = $pdo->prepare($query);
        $statement->execute([$Username,$hashed_password, $FName, $LName , $Tel]);
        echo "$hashed_password";
        echo "<script>alert('สมัครสมาชิกสำเร็จ'); window.location='login.php';</script>";
    } else {
        echo "Username is already used!";
        echo "<script>alert('Username is already used!'); window.location='register.php';</script>";
    }
?>