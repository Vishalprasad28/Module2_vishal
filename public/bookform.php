<?php 
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'author') {
  header('location:index.php');
}
else {
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style/bookform.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@400;700;800&family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
		<title>Document</title>
	</head>
	<body>
    <div class="container flexing flex-column flex-justify">
      <h1>Form</h1>
    <form action="../Controller/BookValidation.php" method="post" id= "book-form" enctype="multipart/form-data">
      <input type="text" name="id" placeholder="Book_Id"><br>
      <input type="file" name="cover"><br>
      <input type="text" name="title" placeholder="Title"><br>
      <input type="text" name="genere" placeholder="Genere"><br>
      <input type="number" name="date" placeholder="Year"><br>
      <input type="text" name="author" placeholder="Author"><br>
      <input type="number" name="rating" placeholder="Rating" maxlength="1"><br>
      <input type="text" name="category" name="category" placeholder="Category"><br>
      <button type='submit' name="submit" id="submit">Upload</button>
    </form>
    <div class="error-box">
      &nbsp;
    </div>
  </div>
  </body>
  <script src="js/bookValidation.js"></script>
 </html> 
<?php 
}
?>