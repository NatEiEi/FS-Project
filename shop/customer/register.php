<?php

    include_once __DIR__ . '/navbar.php';
?>


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

<h2>Password Policy Check</h2>

<form id="passwordForm">
    <label for="username">username:</label>
    <input type="text" id="username" name="username" minlength="5" maxlength="100"><br>
    <div id="usernameError" style="color: red;"></div><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" minlength="8" maxlength="100"><br>
    <input type="checkbox" onclick="myFunction()">Show Password
    <p>Password does not meet the following criteria: </p>
    <ul>
        <li id="char8" style="color: red;">At least 8 characters</li>
        <li id="uppercase" style="color: red;">At least one uppercase letter</li>
        <li id="lowercase" style="color: red;">At least one lowercase letter</li>
        <li id="number" style="color: red;">At least one number</li>
        <li id="special" style="color: red;">At least one special character</li>
    </ul>
    <label for="FName">First Name:</label>
    <input type="text" id="FName" name="FName" size="20" minlength="1" maxlength="20"><br>
    <label for="LName">Last Name:</label>
    <input type="text" id="LName" name="LName" size="20" minlength="1" maxlength="20"><br>
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
    var errorDiv = document.getElementById("error");
    if (validationResult === true) {
        errorDiv.textContent = "pass ";
    } else {
        errorDiv.textContent = "failed";
    }

    
});

function validatePassword(password) {
    var char8 = document.getElementById("char8");
    var uppercase = document.getElementById("uppercase");
    var lowercase = document.getElementById("lowercase");
    var number = document.getElementById("number");
    var special = document.getElementById("special");

    const minLength = 8;
    const uppercaseRegex = /[A-Z]/;
    const lowercaseRegex = /[a-z]/;
    const numberRegex = /[0-9]/;
    const specialCharRegex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;

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

    if (
        password.length > minLength &&
        uppercaseRegex.test(password) &&
        lowercaseRegex.test(password) &&
        numberRegex.test(password) &&
        specialCharRegex.test(password)
    ) {
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
    // var Username = document.getElementById("username").value;
    // var FName = document.getElementById("FName").value;
    // var LName = document.getElementById("LName").value;
    // var Tel = document.getElementById("Tel").value;
    // var Sex = document.getElementById("Sex").value;
    

    // window.location.href = "register.php?Password=" + encodeURIComponent(password) 
    //                         + "&Username=" + encodeURIComponent(Username)
    //                         + "&FName=" + encodeURIComponent(FName)
    //                         + "&LName=" + encodeURIComponent(LName)
    //                         + "&Tel=" + encodeURIComponent(Tel)
    //                         + "&Sex=" + encodeURIComponent(Sex);

    var user = {
        Username: document.getElementById("username").value,
        Password: document.getElementById("password").value,
        FName: document.getElementById("FName").value,
        LName: document.getElementById("LName").value,
        Tel: document.getElementById("Tel").value
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
