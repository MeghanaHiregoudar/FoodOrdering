<?php
session_start();
include('db_con.php');
include('function.inc.php');
include('constant_inc.php');

$rating = get_safe_value($_POST['rating']);
$Dish_details_id = get_safe_value($_POST['did']);
$order_id = get_safe_value($_POST['oid']);
$uid = $_SESSION['FOOD_USER_ID'];

mysqli_query($conn,"INSERT INTO `rating` ( `user_id`,`order_id`, `dish_details_id`, `rating`) VALUES ('$uid','$order_id','$Dish_details_id','$rating')");

?>