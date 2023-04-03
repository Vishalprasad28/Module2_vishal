<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style/stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@400;700;800&family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
		<title>Document</title>
	</head>
	<body>
    <div class="container flexing">
      <div class="container-extended flexing flex-justify-between">
        <div class="left">
          <form action="/loginValidation" id="login-form" method="post">
            <img src="public/assets/lightbluelogo.png" class="form-inline-logo" alt="">
            <h1>Login Page</h1>
            <label for="uName">User name</label>
            <input type="text" name="uName"id="uName" required>
            <p id="uName-error">&nbsp;</p>
            <div id="pwd-container">
            <i class="fa-regular fa-eye eye-open-icon hide-icon"></i>  
            <i class="fa-sharp fa-regular fa-eye-slash eye-close-icon"></i>
              <label for="pwd">Password</label>
              <input type="password" name="pwd" id="pwd" required>
            </div>
            <p id="pwd-error">&nbsp;</p>
            <button name="login" type="submit" id="submit">Log in</button>
            <br>
            <div class="error-box">
            <p>&nbsp;</p>
            </div>
          </form>
        </div>
     </div>
    </div>
  </body>
</html>