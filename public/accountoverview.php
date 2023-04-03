<?php  
session_start();
if (!isset($_SESSION['login'])) {
  header('location:/login');
}
else {
  echo $_SESSION['fullName'];
}
?>
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
    <h1> <?php echo $_SESSION['fullName'];?></h1>
    <a href="/home">Home</a>
    <section>

    </section>
  </body>
</html>  