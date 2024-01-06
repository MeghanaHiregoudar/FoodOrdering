<?php
session_start();
include('function.inc.php');
unset($_SESSION['FOOD_USER_ID']);
unset($_SESSION['FOOD_USER_NAME']);
unset($_SESSION['FOOD_USER_EMAIL']);

//dish added to cart without login
unset($_SESSION['cart']);

redirectPage("shop");



?>