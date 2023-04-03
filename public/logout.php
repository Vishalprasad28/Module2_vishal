<?php
session_start();
unset($_SESSION);
session_destroy();
header("Refresh:0,url=login.php")
?>