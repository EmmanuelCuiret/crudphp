<?php 
session_start();
var_dump($_SESSION);


unset($_SESSION['id']);
header("Location: index.php");

?>