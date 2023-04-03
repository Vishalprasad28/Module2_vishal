<?php
use App\User;

$uName = $_REQUEST['uName'];
$password = $_REQUEST['pwd'];
$obj = new User($uName, $password);

$message = $obj->checkUser();
if ($message == 'success') {
  header('refresh:0,Url=../public/home.php');
}
else {
  header('refresh:0,Url=../public/home.php');
}


?>