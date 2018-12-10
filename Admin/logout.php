<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/business/config/init.php';
unset($_SESSION['SBUser']);
header('Location: login.php');
?>
