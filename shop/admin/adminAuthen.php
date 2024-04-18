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

        // echo $EmployeeID;
        // echo $user['Password'];
        echo "<br>";
        // echo password_verify($password, $user['Password']);
        
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
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<style>
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f2f2f2;
  }

  .form-popup {
    display: block; /* Change to display: none; initially to hide the form */
    background-color: #fff;
    border: 3px solid #f1f1f1;
    border-radius: 5px;
    padding: 16px;
    width: 300px; /* Adjust width as needed */
    text-align: center; /* Center text within the form */
  }

  h1 {
    margin-top: 0;
    margin-bottom: 20px;
  }

  label {
    display: block;
    margin-bottom: 5px;
  }

  input[type="text"],
  input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-sizing: border-box;
    margin-bottom: 15px;
  }

  .btn {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 5px;
  }
</style>
</head>
<body>

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
</body>
</html>
