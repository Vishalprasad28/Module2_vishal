<?php 
session_start();
require_once('../vendor/autoload.php');

use App\Books;
$id = $_REQUEST['id'];
$title = $_REQUEST['title'];
$cover = $_FILES['cover'];
$genere= $_REQUEST['genere'];
$author = $_REQUEST['author'];
$date= (int)$_REQUEST['date'];
$rating = (int)$_REQUEST['rating'];
$category = $_REQUEST['category'];
$obj = new Books($id, $title, $cover, $genere, $author, $rating, $category, $date);
$message = $obj->validate();
echo $message;