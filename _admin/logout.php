<?php 
session_start();
session_unset($_SESSION['onUserSession']); 
session_destroy();
sleep(1);
header('Location: login.php');
?> 