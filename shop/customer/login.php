<?php
    require_once __DIR__ . '/db.php'; 
    include_once __DIR__ . '/navbar.php';
    require_once('../libraries/vendor/autoload.php'); 
    require_once 'JWT_Handler.php'; 

    if (isset($_POST['Username']) && isset($_POST['password'])){
        $Username = $_POST['Username'];
        $password = $_POST['password'];
        $query = "SELECT * FROM customer WHERE Username='$Username'";
        $statement = $pdo->prepare($query);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['Password'])) {

            $Username = $user["Username"];
            $FName = $user['FName'];
            $LName = $user['LName'];
            
            $jwt = new JWT_Handler();
            $jwt->encode($Username, $FName, $LName); 
            $escaped_Username = htmlspecialchars($Username, ENT_QUOTES, 'UTF-8');

            // if (isset($_COOKIE['JWT'])){
            //     echo $jwt->decode();
            // }
        
                
            date_default_timezone_set('Asia/Bangkok');
            $query = "INSERT INTO log (Date, Username, Action) VALUES (NOW(), ?, 'Login')";
            $statement = $pdo->prepare($query);
            $statement->execute([$escaped_Username]);
            
            $query = "SELECT * FROM cart WHERE Username = ?";
            $statement = $pdo->prepare($query);
            $statement->execute([$Username]);
            if($statement->rowCount() > 0) {
                $products = $statement->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION['CartCount'] = count($products);
            } else {
                $_SESSION['CartCount'] = 0;
            }

            
            // echo "Login Success.";
            header('Location: Home.php');
            exit();
        }
        else {
            // echo "Login Failed...";
            echo "Username or Password Is not correct.";
        }
    }
?>

<?php

$ClientId = "430873183011-8k7gi5hrjd73ns8odtfmra3bps9i0khf.apps.googleusercontent.com";
$ClientSecret = "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";
$RedirectUri = "http://www.techshops.space:8000/customer/login.php";


$google_client = new Google_Client();

$google_client->setClientId($ClientId); //Define your ClientID

$google_client->setClientSecret($ClientSecret); //Define your Client Secret Key

$google_client->setRedirectUri($RedirectUri ); 

$google_client->addScope('email');

$google_client->addScope('profile');

if(isset($_GET["code"]))
{

    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

    if(!isset($token["error"]))
    {
        // ตั้งค่า Access Token ใน Google Client
        $google_client->setAccessToken($token['access_token']);

        // เรียกใช้งาน Google Service Oauth2 เพื่อรับข้อมูลผู้ใช้
        $google_service = new Google_Service_Oauth2($google_client);
        $data = $google_service->userinfo->get();
        $email = $data['email'];
        $query = "SELECT * FROM customer WHERE Email = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$email]);

        if($statement->rowCount() == 0) {
            $escaped_Email = htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8');
            $escaped_FName = htmlspecialchars($data['given_name'], ENT_QUOTES, 'UTF-8');
            $escaped_LName = htmlspecialchars($data['family_name'], ENT_QUOTES, 'UTF-8');
            $escaped_Username = htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8');
        
            $query =    "INSERT INTO customer (Email , FName , LName ,Username  ) 
                        VALUES ( ? , ? , ? ,?);";
            $statement = $pdo->prepare($query);
            // $statement->execute([$data['email'],$data['given_name'], $data['family_name'],$data['email']]);
            $statement->execute([$escaped_Email,$escaped_FName, $escaped_LName, $escaped_Username]);

        } 
        $jwt = new JWT_Handler();
        $jwt->encode($data['email'], $data['given_name'], $data['family_name']); 
        $secret_key = "your_secret_key"; 
        header('Location: Home.php');
    }
}
?>

<head>
    <link rel="stylesheet" href="css/login.css">
</head>

<!-- <header>
One account. All of Google.
</header> -->

<body>
<div class="login">
<i ripple>
</i>
<div class="photo">
</div>
<!-- <span>Sign in</span> -->
<form action="login.php" method="POST" id="login-form" class="form-login">
<div id="u" class="form-group">
  <input id="Username" spellcheck=false class="form-control" name="Username" type="text" size="18" alt="login" required="">
  <span class="form-highlight"></span>
  <span class="form-bar"></span>
  <label for="Username" class="float-label">Email or Username</label>
  <erroru>
  	Username is required
  	<i>		
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
		    <path d="M0 0h24v24h-24z" fill="none"/>
		    <path d="M1 21h22l-11-19-11 19zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
		</svg>
  	</i>
  </erroru>
</div>
<div id="p" class="form-group">
  <input id="password" class="form-control" spellcheck=false name="password" type="password" size="18" alt="login" required="">
  
  <span class="form-highlight"></span>
  <span class="form-bar"></span>
  <label for="password" class="float-label">Password</label>
  <errorp>
  	Password is required
  	<i>		
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
		    <path d="M0 0h24v24h-24z" fill="none"/>
		    <path d="M1 21h22l-11-19-11 19zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
		</svg>
  	</i>
  </errorp>
</div>
<br>
<button class="btn_submit" type="submit" id="submit" ripple>Sign in</button>

</form>


<form class="form-login" id="google-login-form">
    <hr><br>
    <button class="btn_submit" type="button" id="google-login-btn" ripple>Sign in with Google</button>
</form>

<footer><a href="register.php">Register</a></footer>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/login.js"></script>


</body>

<script>
    document.getElementById('google-login-btn').addEventListener('click', function() {
        window.location.href = '<?php echo $google_client->createAuthUrl(); ?>';
    });
</script>