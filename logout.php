<?php
//logout script 
session_start(); 
session_destroy();
//redirect to login page 
header('Location: login.php'); 
?>