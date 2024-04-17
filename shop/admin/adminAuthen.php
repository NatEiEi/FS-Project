<?php
    require '../db.php'; 
    require_once 'JWT_Handler_admin.php'; 

    session_start();
    if (isset($_POST['EmployeeID']) && isset($_POST['password'])){
        $EmployeeID = $_POST['EmployeeID'];
        $password = $_POST['password'];
        $query = "SELECT * FROM employee WHERE EmployeeID='$EmployeeID'";
        $statement = $pdo->prepare($query);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        echo $EmployeeID;
        echo $user['Password'];
        echo "<br>";
        echo password_verify($password, $user['Password']);
        
        if ($user && password_verify($password, $user['Password'])) {

            $EmployeeID = $user["EmployeeID"];
            $Role = $user['Role'];

            $jwt = new JWT_Handler_admin();
            $jwt->encode($EmployeeID,$Role); 
            
          

            echo "Login Successfully...";
            header('Location: selectProduct.php');
            exit;
        }
        else {
            echo "Login Failed...";
            echo "Username or Password Is not correct.";
        }
    }
?>

<div class="form-popup" id="myForm">
        <form action="adminAuthen.php" method="POST" class="form-container">
        <h1>Login TO Back-end Management</h1>
            
        <label for="EmployeeID"><b>EmployeeID</b></label>
        <input type="text" placeholder="Enter EmployeeID" name="EmployeeID" required>
            
        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" required>
            
        <button type="submit" class="btn">Login</button>
        
    </form>
</div>