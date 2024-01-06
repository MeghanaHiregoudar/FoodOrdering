<?php
session_start();
include('../db_con.php');
include('../function.inc.php');

unset($_SESSION['IS_LOGIN']);
unset($_SESSION['ADMIN_NAME']);
redirectPage('login.php');
?>