<?php
session_start();
use App\User;
require_once('../vendor/autoload.php');
$uName = $_REQUEST['uName'];
$password = $_REQUEST['pwd'];
$obj = new User($uName, $password);
$message = $obj->checkUser();
$array = array();
$array['message'] = $message;
if ($message == 'success') {
  $role = $obj->roleGetter();
  $_SESSION['login'] = TRUE;
  $_SESSION['uName'] = $obj->getUserName();
  $_SESSION['fullName'] = $obj->getFullName();
  $_SESSION['role'] = $role;
  $array['role'] = $role;
  $jsonArray = json_encode($array);
  print_r($jsonArray);
}
else {
  $jsonArray = json_encode($array);
  print_r($jsonArray);
}
