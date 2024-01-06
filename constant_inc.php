<?php 

define("SITE_NAME","Food Ordering Admin");
define("FRONT_SITE_NAME","Food Ordering");

define("FRONT_SITE_PATH","http://localhost/food_ordering/");
define("SERVER_IMAGE",$_SERVER['DOCUMENT_ROOT']."/food_ordering/");

//to fetch admin dish uploaded img to front end
define("SERVER_DISH_IMAGE",SERVER_IMAGE."media/dish/");
//to cal dish img to display
define("SITE_DISH_IMAGE",FRONT_SITE_PATH."media/dish/");

//to fetch admin dish uploaded img to front end
define("SERVER_BANNER_IMAGE",SERVER_IMAGE."media/banner/");
//to cal dish img to display
define("SITE_BANNER_IMAGE",FRONT_SITE_PATH."media/banner/");



?>