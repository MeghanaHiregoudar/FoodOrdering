<?php
session_start();
include('../db_con.php');
include('../function.inc.php');

unset($_SESSION['IS_DELIVERY_BOY']);
unset($_SESSION['DELIVERY_BOY_NAME']);
unset($_SESSION['DELIVERY_BOY_ID']);
redirectPage('login.php');
?>