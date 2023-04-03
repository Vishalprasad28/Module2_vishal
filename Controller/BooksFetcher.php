<?php 
use App\DbHandler;
session_start();
require_once('../vendor/autoload.php');

$obj = new DbHandler();
$count = (int)$_REQUEST['count'];

$array = $obj->fetchByName($count);

foreach ($array as $row) {
  $id = $row['book_id'];
  $path = $row['path'];
  $title = $row['title'];
  $author = $row['author'];
  include('../public/templates/book.php');
}