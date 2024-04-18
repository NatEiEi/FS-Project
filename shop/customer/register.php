<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Password Policy Check</title>
<style>
    .error {
        color: red;
    }
</style>
</head>
<body>

<h2>Register</h2>

<form id="passwordForm">
    <label for="username">username:</label>
    <input type="text" id="username" name="username" minlength="5" maxlength="100"><br>
    <div id="usernameError" style="color: red;"></div><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" minlength="0" maxlength="100"><br>
    <input type="checkbox" onclick="myFunction()">Show Password
    
    <p>Password should meet the following criteria: </p>
    <ul>
        <li id="char8" style="color: red;">At least 8 characters</li>
        <li id="uppercase" style="color: red;">At least one uppercase letter</li>
        <li id="lowercase" style="color: red;">At least one lowercase letter</li>
        <li id="number" style="color: red;">At least one number</li>
        <li id="special" style="color: red;">At least one special character</li>
    </ul>

    <!-- เพิ่ม progress element -->
    <label for="securityLevel" id="securityLevel1">Security Level:</label>
    <progress id="securityLevel" value="0" max="30"></progress><br>
    
    <label for="FName">First Name:</label>
    <input type="text" id="FName" name="FName" size="20" minlength="1" maxlength="50"><br>
    <label for="LName">Last Name:</label>
    <input type="text" id="LName" name="LName" size="20" minlength="1" maxlength="50"><br>
    <label for="Email">Email:</label>
    <input type="Email" id="Email" name="Email" size="20" minlength="1" maxlength="50"><br>
    <label for="Tel">Tel Number:</label>
    <input type="tel" id="Tel" name="Tel" size="20" minlength="10" maxlength="10"><br>
    <button type="submit">Register</button>
</form>
    

<div id="error" class="error"></div>

<script>

function myFunction() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}

document.getElementById("password").addEventListener("input", function(event) {
   
    var password = document.getElementById("password").value;

    var validationResult = validatePassword(password);
    var securityProgressLabel = document.getElementById("securityLevel1").textContent; 
    var errorDiv = document.getElementById("error");
    if (securityProgressLabel === "Good" || securityProgressLabel === "Strong" || securityProgressLabel === "Very strong") {
        errorDiv.textContent = "pass ";
    } else {
        errorDiv.textContent = "Password should be Good or higher.";
    }

    
});

function validatePassword(password) {
    var char8 = document.getElementById("char8");
    var uppercase = document.getElementById("uppercase");
    var lowercase = document.getElementById("lowercase");
    var number = document.getElementById("number");
    var special = document.getElementById("special");
    var securityProgressLabel = document.getElementById("securityLevel1"); // เพิ่มบรรทัดนี้


    const minLength = 8;
    const uppercaseRegex = /[A-Z]/;
    const lowercaseRegex = /[a-z]/;
    const numberRegex = /[0-9]/;
    const specialCharRegex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;

    var floatEntropy;		// Maximum possible combinations of password characters
    var intBase = 0;		// Total number of characters in the character sets used in the password
    var intPwdLen;		// length of the password
    var strUnique="";		// Sort of unique characters used, I count no more than 2 of each
    var intUniqueLen=0;		// Length of "unique" character string, allowing 2 of each character max
    var intStrength = 0;	// Calculated password strangth
    var x;			// General counter

    var barWidth;		// Current width and color of the bar
    var barColor;

    var DEBUG=0;    		// Set to 1 for debug output


    intPwdLen = password.length;	// Length of typed password

    // Entropy space determined by number of possible combinations
    // of character sets, 26 each for upper an lower case letters,
    // 10 for digits, 33 for special characters.

    // Lowercase letters - there are 26 possibilities

    if (password.match(/[a-z]/))
    {
        intBase = intBase + 26;
    }

    // Uppercase = 26

    if (password.match(/[A-Z]/))
    {
        intBase = intBase + 26;
    }

    // Digits = 10

    if (password.match(/[0-9]/))
    {
        intBase = intBase + 10;
    }

    // Special characters = 33

    if (password.match(/[\W_]/))
    {
        intBase = intBase + 33;
    }

    

    for (x = 0; x < intPwdLen; x++)
    {
        var intMatches = 0;
        for (var i = x+1; i < intPwdLen; i++)
        {
            if (password.charAt(x) == password.charAt(i))
                intMatches = intMatches + 1;
        }
        if (intMatches < 2)
            strUnique = strUnique + password.charAt(x);
    }
    strUniqueLen = strUnique.length;

    // Entropy for only unique bytes in password

    floatEntropy = Math.pow(intBase, strUniqueLen);

    // Calculate pwd strength as the exponent of entropy

    x = floatEntropy;
    while (x >= 10) {
        intStrength = intStrength + 1;
        x = x / 10;
    }

    // Scale from 0 - 50, max strength is 50

    if (intStrength > 50) intStrength = 50;


    if (intStrength == 0) strDesc = "";
    else if (intStrength <= 7) 
    {
        securityProgressLabel.textContent = "Weak";
        char8.style.color = "Red";

    }
    else if (intStrength <= 14) 
    {   
        securityProgressLabel.textContent = "Fair";
        char8.style.color = "Orange";
    }
    else if (intStrength <= 20) 
    {   
        securityProgressLabel.textContent = "Good";
        char8.style.color = "yellow";

    }
    else if (intStrength <= 30) 
    {   
        securityProgressLabel.textContent = "Strong";
        char8.style.color = "GreenYellow";
   
    }
    else if (intStrength > 30) 
    {
        securityProgressLabel.textContent = "Very strong";
        char8.style.color = "Green";
     
    }

    if (password.length < minLength) {
        char8.style.color = "red";
    } else {
        char8.style.color = "green";
    }
    if (!uppercaseRegex.test(password)) {
        uppercase.style.color = "red";
    } else {
        uppercase.style.color = "green";
    }
    if (!lowercaseRegex.test(password)) {
        lowercase.style.color = "red";
    } else {
        lowercase.style.color = "green";
    }
    if (!numberRegex.test(password)) {
        number.style.color = "red";
    } else {
        number.style.color = "green";
    }
    if (!specialCharRegex.test(password)) {
        special.style.color = "red";
    } else {
        special.style.color = "green";
    }

    // // คำนวณระดับความปลอดภัยของรหัสผ่าน
    var securityLevel = 0;
    if (password.length >= minLength) securityLevel++;
    if (uppercaseRegex.test(password)) securityLevel++;
    if (lowercaseRegex.test(password)) securityLevel++;
    if (numberRegex.test(password)) securityLevel++;
    if (specialCharRegex.test(password)) securityLevel++;

    // // อัพเดทค่าของ progress element
    var securityProgress = document.getElementById("securityLevel");
    securityProgress.value = intStrength;
    // if (securityLevel <= 2) {
    //     securityProgressLabel.textContent = "Security Level: Weak";
    // } else if (securityLevel <= 3) {
    //     securityProgressLabel.textContent = "Security Level: Moderate";
    // } else {
    //     securityProgressLabel.textContent = "Security Level: Strong";
    // }

  
    if ( intStrength >= 15 ) {
        return true;
    }
    return false;
}

function post(path, params, method='post') {
  const form = document.createElement('form');
  form.method = method;
  form.action = path;

  for (const key in params) {
    if (params.hasOwnProperty(key)) {
      const hiddenField = document.createElement('input');
      hiddenField.type = 'hidden';
      hiddenField.name = key;
      hiddenField.value = params[key];

      form.appendChild(hiddenField);
    }
  }

  document.body.appendChild(form);
  form.submit();
}



document.getElementById("passwordForm").addEventListener("submit", function(event) {
    event.preventDefault(); 
    var password = document.getElementById("password").value;
    var isValid = validatePassword(password);
    var errorDiv = document.getElementById("error");
    if (!isValid) {
        errorDiv.textContent = "Password does not meet the criteria.";
        return;
    }

   

    var user = {
        Username: document.getElementById("username").value,
        Password: document.getElementById("password").value,
        FName: document.getElementById("FName").value,
        LName: document.getElementById("LName").value,
        Tel: document.getElementById("Tel").value,
        Email: document.getElementById("Email").value
    };
    post('register_verify.php', user);
});


document.getElementById("username").addEventListener("input", function(event) {
    var usernameInput = event.target.value;
    var usernameError = document.getElementById("usernameError");
    var isValid = /^[a-zA-Z0-9]+$/.test(usernameInput);

    if (usernameInput.includes(" ") || !isValid) {
        usernameError.textContent = "Username cannot contain spaces or special characters.";
    } else {
        usernameError.textContent = "";
    }
});
</script>

</body>
</html>
