<?php 
session_start();
session_unset($_SESSION['logged']); 
session_destroy();
sleep(1);
header('Location: login.php');
?> 