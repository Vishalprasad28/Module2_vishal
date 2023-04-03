<?php  
use Controller\Router;
require_once('../vendor/autoload.php');
$path = $_SERVER['REQUEST_URI'];
$obj = new Router();
$obj->route($path);

?>