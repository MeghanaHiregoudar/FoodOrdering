<?php

/******************************************COMMON FUNCTIONS**********************************************/
function pr($arr){
	echo '<pre>';
	print_r($arr);
}

function prx($arr){
	echo '<pre>';
	print_r($arr);
	die();
}

function redirectPage($link)
{?>
<script>
	window.location.href="<?php echo $link; ?>";
</script>
<?php
die();
}


function get_safe_value($str){
	global $conn;
	$str=mysqli_real_escape_string($conn,$str);
	return $str;
}
/******************************************COMMON FUNCTIONS**********************************************/

/******************************************EMAIL FUNCTIONS**********************************************/
function send_email($email,$body,$subject)
{
	$mail = new PHPMailer(true);
	$mail->isSMTP();
	$mail->Host="smtp.gmail.com";
	$mail->Port=587;
	$mail->SMTPSecure="tls";
	$mail->SMTPAuth=true;
	$mail->Username="meghana62558@gmail.com";
	$mail->Password="Meghana@123";
	$mail->SetFrom("meghana62558@gmail.com","Foor Ordering");
	$mail->addAddress($email);
	$mail->isHTML(true);
	$mail->Subject=$subject;
	$mail->Body=$body;
	$mail->SMTPOptions=array('ssl'=>array(
		'verify_peer'=>false,
		'verify_peer_name'=>false,
		'allow_selft_signed'=>false
	));
	$mail->send();
// 	if(!$mail->send()){ 
//     return 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo; 
//     }else{ 
//         return 'Message has been sent.'; 
//     }
}
/******************************************EMAIL FUNCTIONS**********************************************/


/************************************ADMIN PANEL FUNCTIONS*******************************************/
//get order details based on order id (USED IN ADMIN PANEL ORDER PAGE AND FRONT PANEL ORDER_HISTORY PAGE)
function getOrderDetailById($oid)
{
	global $conn;
	$data = array();
	$query = mysqli_query($conn,"SELECT od.dish_details_id,od.price,od.qty,dd.attribute,d.dish FROM `order_detail` as od
	INNER JOIN dish_details as dd on od.dish_details_id = dd.id
	INNER JOIN dish d ON dd.dish_id = d.id
	where od.order_id = '$oid' ");
	while($row = mysqli_fetch_assoc($query))
	{
		$data[] = $row;
	}
	return $data;
}

//To get Delivery boy details by id
function getDeliveryBoyById($did)
{
	global $conn;
	$data = "";
	$res = mysqli_query($conn,"SELECT `name`,`mobile` FROM `delivery_boy` where `id` = '$did' ");
	if(mysqli_num_rows($res) > 0)
	{
		$row = mysqli_fetch_assoc($res);
		$data = $row;
		return $data;
	}
	else
	{
		return $data;
	}
}

//Dahsboard report analysis
function getDashboardSale($start,$end)
{
	global $conn;
	$res = mysqli_query($conn,"SELECT SUM(`final_price`) as total_price FROM `order_master` WHERE `order_status` = '4' AND `added_on` BETWEEN '$start' and '$end' ");
	while($row = mysqli_fetch_assoc($res))
	{
		return "₹".$row['total_price'];
	}
}
/************************************ADMIN PANEL FUNCTIONS*******************************************/




/************************************WEBSITE FRONT VIEW FUNCTIONS*******************************************/
//This function is to create random value based on string defined in function
function random_str()
{
	$str = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789");
	return $str = substr($str,0,12);
}

//To get value of settings
function getSetting()
{
	global $conn;
	$res = mysqli_query($conn,"select * from setting where id = '1'");
	$row = mysqli_fetch_assoc($res);
	return $row;
}

//for rating array
function getRatingList($did,$oid)
{
	$arr = array('Bad','Below Average','Average','Good', 'Very Good');
	$html = '<select class="form-control" onchange=updaterating("'.$did.'","'.$oid.'") id="rate_select_'.$did.'" >';
	$html .= "<option value=''>Select Rating</option>";
		foreach($arr as $key => $val)
		{
			$rate_id = $key+1;
			$html .= "<option value='".$rate_id."'>$val</option>";
		}
	$html .= "</select>";
	return $html;
}

//this function is to set the rating deatils of dish when user rated if not rated then above function will be called
function getorsetRating($did,$oid)
{
	global $conn;
	$res = mysqli_query($conn,"SELECT * FROM `rating` WHERE `order_id` = '$oid' AND `dish_details_id`='$did' ");
	if(mysqli_num_rows($res) > 0)
	{
		$row = mysqli_fetch_assoc($res);
		$rating = $row['rating'];
		//If rating data of particular dish found then fetch rating from db and disaply through below array
		$arr = array('','Bad','Below Average','Average','Good', 'Very Good');
		echo "<div class='set_rating'>".$arr[$rating]."</div>";		
	}
	else
	{
		echo getRatingList($did,$oid);
	}

}

//To show rating in front end on shop page
function getRatingByDishId($dish_id)
{
	global $conn;
	//Fetch dish_detail_id based on dish_id
	$query = mysqli_query($conn,"SELECT `id` FROM `dish_details` WHERE `dish_id` = '$dish_id' ");
	$data = array();
	while($row = mysqli_fetch_assoc($query))
	{
		$data[] = $row['id'];
	}
	//convert array of id in to string
	$dis_ids = implode(',',$data);
	//fetch total num of rating and sum of rating
	$rate_query = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(`rating`) as rating , count(*) as total_rating FROM `rating` WHERE `dish_details_id` IN ('$dis_ids')"));
	$arr = array('','Bad','Below Average','Average','Good', 'Very Good');
	//if total num od rating is greater then 0
	if($rate_query['total_rating'] > 0)
	{
		//Then take an average of rating
		$rating_avg = $rate_query['rating']/$rate_query['total_rating'];
		echo "<div class='rating_show'>( Rating :<span>  ".$arr[round($rating_avg)]." </span> By ".$rate_query['total_rating']." Users )</div>";
	}
	
}

//get order master based on user id ( FRONT PANEL ORDER_HISTORY PAGE)
function getOrderById($oid)
{
	global $conn;
	$data =array();
	$query = mysqli_query($conn,"SELECT * FROM `order_master` WHERE `id` = '$oid' ");
	while($row = mysqli_fetch_assoc($query))
	{
		$data[] = $row;
	}
	return $data;
}


//function to get records based on session id
//used in (header_inc file)
function getUserCart()
{
	global $conn;
	$data = array();
	$user_id = $_SESSION['FOOD_USER_ID'];
	$query = mysqli_query($conn,"select * from `dish_cart` where `user_id` = '$user_id'");
	while($cart_row = mysqli_fetch_assoc($query))
	{
		$data[] = $cart_row;
	}
	return $data;
}

//function to insert dish to cart by checking if present update else add
//(used in manage_cart and login file)
function manageUserCart($user_id,$dish_attr,$qty)
{
	global $conn;
	$added_on = date('Y-m-d h:i:s');
	$check_data = mysqli_query($conn,"SELECT * FROM `dish_cart` WHERE `user_id` = '$user_id' and `dish_details_id` = '$dish_attr'") ;
    if(mysqli_num_rows($check_data)> 0)
    {
        $row = mysqli_fetch_assoc($check_data);
        $cart_id = $row['id'];
        mysqli_query($conn, "update dish_cart set `qty` = '$qty' where `id` = '$cart_id'");
    }
    else
    {
        mysqli_query($conn,"INSERT INTO `dish_cart`( `user_id`, `dish_details_id`, `qty`, `added_on`) VALUES ('$user_id','$dish_attr','$qty','$added_on')");    
    }   
}

//This function is to get status of dish from dish and dish_detail table which are added to cart
//(to display dishes based on status only 1)
function getDishStatusInCart()
{
	global $conn;
	$cartArr = array();
	//Store dish attribute id in this array and run thin in loop
	$dishDetailId = array();
	if(isset($_SESSION['FOOD_USER_ID']))
	{
		$getUserCart = getUserCart();
		foreach($getUserCart as $list)
		{
			$dishDetailId[] = $list['dish_details_id'];
		}
	}
	else
	{
		if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0)
        {
			foreach( $_SESSION['cart'] as $key=>$val)
			{
				$dishDetailId[] = $key;
			}
		}

	}

	//dish attribute id which are added to cart are stored in this array
	foreach($dishDetailId as $id)	
	{
		$res = mysqli_query($conn,"select d.id as dish_id,d.status as dish_status,dd.status as dish_detail_status  from dish_details as dd INNER JOIN dish as d on dd.dish_id = d.id WHERE dd.id = '$id'");
		$row = mysqli_fetch_assoc($res);
		if($row['dish_status'] == 0)
		{
			$dish_id = $row['dish_id'];
			$query = mysqli_query($conn,"select id from dish_details where dish_id = '$dish_id'");
			while($rows = mysqli_fetch_assoc($query))
			{
				removedishfromcartbyid($rows['id']);
			}
		}
		if($row['dish_detail_status'] == 0)
		{
			removedishfromcartbyid($id);
		}
	}
	
}

  //TO CHECK WEATHER DISH IS IN SESION OR IN DB
  //parameter to get qty of particular dish_attr id (used in header and shop file for displaying dish added to cart msg)
function getUserDetailCart($attr_id = '')
{
	$cartArr = array();
    //TO CHECK WEATHER DISH IS IN SESION OR IN DB
    if(isset($_SESSION['FOOD_USER_ID']))
    {      
        //Fetch all record based on user id  
        $getUserCart = getUserCart();
		$cartArr = array();
        //Make make same fomat of data fetched by db and present in session and store in $cartArr array
        foreach ($getUserCart as $list)
        {
            $cartArr[$list['dish_details_id']]['qty'] = $list['qty'];
			$getdishDetailById = getdishDetailById($list['dish_details_id']);
			$cartArr[$list['dish_details_id']]['price'] = $getdishDetailById['price'];
			$cartArr[$list['dish_details_id']]['dish'] = $getdishDetailById['dish'];
			$cartArr[$list['dish_details_id']]['image'] = $getdishDetailById['image'];
			$cartArr[$list['dish_details_id']]['attribute'] = $getdishDetailById['attribute'];
        }  
    }
    else
    {
        if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0)
        {
			foreach( $_SESSION['cart'] as $key=>$val)
			{
				$cartArr[$key]['qty'] = $val['qty'];
				$getdishDetailById = getdishDetailById($key);
				$cartArr[$key]['price'] = $getdishDetailById['price'];
				$cartArr[$key]['dish'] = $getdishDetailById['dish'];
				$cartArr[$key]['image'] = $getdishDetailById['image'];
				$cartArr[$key]['attribute'] = $getdishDetailById['attribute'];
			}
            
        }
    }
	if($attr_id!= '')
	{
		return $cartArr[$attr_id]['qty'];
	}
	else
	{
		return $cartArr;
	}
}


//to get total amount in cart of particular user
function getcartTotalPrice(){
	$cartArr=getUserDetailCart();
	$totalPrice=0;
	foreach($cartArr as $list){
		$totalPrice=$totalPrice+($list['qty']*$list['price']);
	}
	return $totalPrice;
}


//Fetching data of dish with all attributes and storing in $cartArr for further use
function getdishDetailById($dish_attr_id)
{
	global $conn;
	// $dish_data = array();
	$res = mysqli_query($conn,"select dish.dish, dish.image, dish_details.attribute, dish_details.price from dish INNER JOIN dish_details on dish_details.dish_id = dish.id WHERE dish_details.id = '$dish_attr_id'") ;
	$row = mysqli_fetch_assoc($res);
	return $row;
}

//Delete of dish from cart when user delete dish from his cart
function removedishfromcartbyid($dish_attr_id)
{
	if(isset($_SESSION['FOOD_USER_ID']))
    {      
		global $conn;
		mysqli_query($conn, "delete from dish_cart  where `dish_details_id` = '$dish_attr_id' and `user_id` ='".$_SESSION['FOOD_USER_ID']."' ");
    }
    else
    {
        unset($_SESSION['cart'][$dish_attr_id]);
    }
}

//To fetch user details from user table
function getUserDetailsById($uid='')
{
	global $conn;
	$data['name'] = '';
	$data['email'] = '';
	$data['mobile'] = '';
	$data['referral_code'] = '';
	if(isset($_SESSION['FOOD_USER_ID']))
	{
		$uid = $_SESSION['FOOD_USER_ID'];
	}
	
	$user_row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT  `name`, `email`, `mobile`,`referral_code` FROM `user` WHERE `id` = '$uid'"));
	$data['name'] = $user_row['name'];
	$data['email'] = $user_row['email'];
	$data['mobile'] = $user_row['mobile'];	
	$data['referral_code'] = $user_row['referral_code'];	
	return $data;
}

//To empty cart based on user id
function emptyCart()
{
	if(isset($_SESSION['FOOD_USER_ID']))
    {      
		global $conn;
		mysqli_query($conn, "delete from dish_cart  where `user_id` ='".$_SESSION['FOOD_USER_ID']."' ");
    }
    else
    {
        unset($_SESSION['cart']);
    }
}



function Order_emailInvoice($oid,$uid='')
{
	global $conn;
	$getUserDetailsById = getUserDetailsById($uid);
	$name = $getUserDetailsById['name'];

	$getOrderById = getOrderById($oid);
	
	$order_id = $getOrderById[0]['id'];
	$total_amount = $getOrderById[0]['total_price'];
	$final_price = $getOrderById[0]['final_price'];

	//to fetch coupon amount to deduct
	$coupon = mysqli_fetch_assoc(mysqli_query($conn,"select * from coupon_code where `coupon_code` = '".$getOrderById[0]['coupon_code']."'"));
	$coupon_amount = 0;
	if($coupon['coupon_type'] == 'F')
	{
	  $coupon_amount = $coupon['coupon_value'];
	} else {
	  $coupon_amount = ($coupon['coupon_value'] * $getOrderById[0]['total_price'] / 100 );
	}

	$getOrderDetailById = getOrderDetailById($oid);

	$html  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	  <head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="x-apple-disable-message-reformatting" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title></title>
		<style type="text/css" rel="stylesheet" media="all">
		/* Base ------------------------------ */
		
		@import url("https://fonts.googleapis.com/css?family=Nunito+Sans:400,700&display=swap");
		body {
		  width: 100% !important;
		  height: 100%;
		  margin: 0;
		  -webkit-text-size-adjust: none;
		}
		
		a {
		  color: #3869D4;
		}
		
		a img {
		  border: none;
		}
		
		td {
		  word-break: break-word;
		}
		
		.preheader {
		  display: none !important;
		  visibility: hidden;
		  mso-hide: all;
		  font-size: 1px;
		  line-height: 1px;
		  max-height: 0;
		  max-width: 0;
		  opacity: 0;
		  overflow: hidden;
		}
		/* Type ------------------------------ */
		
		body,
		td,
		th {
		  font-family: "Nunito Sans", Helvetica, Arial, sans-serif;
		}
		
		h1 {
		  margin-top: 0;
		  color: #333333;
		  font-size: 22px;
		  font-weight: bold;
		  text-align: left;
		}
		
		h2 {
		  margin-top: 0;
		  color: #333333;
		  font-size: 16px;
		  font-weight: bold;
		  text-align: left;
		}
		
		h3 {
		  margin-top: 0;
		  color: #333333;
		  font-size: 14px;
		  font-weight: bold;
		  text-align: left;
		}
		
		td,
		th {
		  font-size: 16px;
		}
		
		p,
		ul,
		ol,
		blockquote {
		  margin: .4em 0 1.1875em;
		  font-size: 16px;
		  line-height: 1.625;
		}
		
		p.sub {
		  font-size: 13px;
		}
		/* Utilities ------------------------------ */
		
		.align-right {
		  text-align: right;
		}
		
		.align-left {
		  text-align: left;
		}
		
		.align-center {
		  text-align: center;
		}
		/* Buttons ------------------------------ */
		
		.button {
		  background-color: #3869D4;
		  border-top: 10px solid #3869D4;
		  border-right: 18px solid #3869D4;
		  border-bottom: 10px solid #3869D4;
		  border-left: 18px solid #3869D4;
		  display: inline-block;
		  color: #FFF;
		  text-decoration: none;
		  border-radius: 3px;
		  box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
		  -webkit-text-size-adjust: none;
		  box-sizing: border-box;
		}
		
		.button--green {
		  background-color: #22BC66;
		  border-top: 10px solid #22BC66;
		  border-right: 18px solid #22BC66;
		  border-bottom: 10px solid #22BC66;
		  border-left: 18px solid #22BC66;
		}
		
		.button--red {
		  background-color: #FF6136;
		  border-top: 10px solid #FF6136;
		  border-right: 18px solid #FF6136;
		  border-bottom: 10px solid #FF6136;
		  border-left: 18px solid #FF6136;
		}
		
		@media only screen and (max-width: 500px) {
		  .button {
			width: 100% !important;
			text-align: center !important;
		  }
		}
		/* Attribute list ------------------------------ */
		
		.attributes {
		  margin: 0 0 21px;
		}
		
		.attributes_content {
		  background-color: #F4F4F7;
		  padding: 16px;
		}
		
		.attributes_item {
		  padding: 0;
		}
		/* Related Items ------------------------------ */
		
		.related {
		  width: 100%;
		  margin: 0;
		  padding: 25px 0 0 0;
		  -premailer-width: 100%;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		}
		
		.related_item {
		  padding: 10px 0;
		  color: #CBCCCF;
		  font-size: 15px;
		  line-height: 18px;
		}
		
		.related_item-title {
		  display: block;
		  margin: .5em 0 0;
		}
		
		.related_item-thumb {
		  display: block;
		  padding-bottom: 10px;
		}
		
		.related_heading {
		  border-top: 1px solid #CBCCCF;
		  text-align: center;
		  padding: 25px 0 10px;
		}
		/* Discount Code ------------------------------ */
		
		.discount {
		  width: 100%;
		  margin: 0;
		  padding: 24px;
		  -premailer-width: 100%;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		  background-color: #F4F4F7;
		  border: 2px dashed #CBCCCF;
		}
		
		.discount_heading {
		  text-align: center;
		}
		
		.discount_body {
		  text-align: center;
		  font-size: 15px;
		}
		/* Social Icons ------------------------------ */
		
		.social {
		  width: auto;
		}
		
		.social td {
		  padding: 0;
		  width: auto;
		}
		
		.social_icon {
		  height: 20px;
		  margin: 0 8px 10px 8px;
		  padding: 0;
		}
		/* Data table ------------------------------ */
		
		.purchase {
		  width: 100%;
		  margin: 0;
		  padding: 35px 0;
		  -premailer-width: 100%;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		}
		
		.purchase_content {
		  width: 100%;
		  margin: 0;
		  padding: 25px 0 0 0;
		  -premailer-width: 100%;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		}
		
		.purchase_item {
		  padding: 10px 0;
		  color: #51545E;
		  font-size: 15px;
		  line-height: 18px;
		}
		
		.purchase_heading {
		  padding-bottom: 8px;
		  border-bottom: 1px solid #EAEAEC;
		}
		
		.purchase_heading p {
		  margin: 0;
		  color: #85878E;
		  font-size: 12px;
		}
		
		.purchase_footer {
		  padding-top: 15px;
		  border-top: 1px solid #EAEAEC;
		}
		
		.purchase_total {
		  margin: 0;
		  text-align: right;
		  font-weight: bold;
		  color: #333333;
		}
		
		.purchase_total--label {
		  padding: 0 15px 0 0;
		}
		
		body {
		  background-color: #F4F4F7;
		  color: #51545E;
		}
		
		p {
		  color: #51545E;
		}
		
		p.sub {
		  color: #6B6E76;
		}
		
		.email-wrapper {
		  width: 100%;
		  margin: 0;
		  padding: 0;
		  -premailer-width: 100%;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		  background-color: #F4F4F7;
		}
		
		.email-content {
		  width: 100%;
		  margin: 0;
		  padding: 0;
		  -premailer-width: 100%;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		}
		/* Masthead ----------------------- */
		
		.email-masthead {
		  padding: 25px 0;
		  text-align: center;
		}
		
		.email-masthead_logo {
		  width: 94px;
		}
		
		.email-masthead_name {
		  font-size: 16px;
		  font-weight: bold;
		  color: #A8AAAF;
		  text-decoration: none;
		  text-shadow: 0 1px 0 white;
		}
		/* Body ------------------------------ */
		
		.email-body {
		  width: 100%;
		  margin: 0;
		  padding: 0;
		  -premailer-width: 100%;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		  background-color: #FFFFFF;
		}
		
		.email-body_inner {
		  width: 570px;
		  margin: 0 auto;
		  padding: 0;
		  -premailer-width: 570px;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		  background-color: #FFFFFF;
		}
		
		.email-footer {
		  width: 570px;
		  margin: 0 auto;
		  padding: 0;
		  -premailer-width: 570px;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		  text-align: center;
		}
		
		.email-footer p {
		  color: #6B6E76;
		}
		
		.body-action {
		  width: 100%;
		  margin: 30px auto;
		  padding: 0;
		  -premailer-width: 100%;
		  -premailer-cellpadding: 0;
		  -premailer-cellspacing: 0;
		  text-align: center;
		}
		
		.body-sub {
		  margin-top: 25px;
		  padding-top: 25px;
		  border-top: 1px solid #EAEAEC;
		}
		
		.content-cell {
		  padding: 35px;
		}
		/*Media Queries ------------------------------ */
		
		@media only screen and (max-width: 600px) {
		  .email-body_inner,
		  .email-footer {
			width: 100% !important;
		  }
		}
		
		@media (prefers-color-scheme: dark) {
		  body,
		  .email-body,
		  .email-body_inner,
		  .email-content,
		  .email-wrapper,
		  .email-masthead,
		  .email-footer {
			background-color: #333333 !important;
			color: #FFF !important;
		  }
		  p,
		  ul,
		  ol,
		  blockquote,
		  h1,
		  h2,
		  h3 {
			color: #FFF !important;
		  }
		  .attributes_content,
		  .discount {
			background-color: #222 !important;
		  }
		  .email-masthead_name {
			text-shadow: none !important;
		  }
		}
		</style>
		<!--[if mso]>
		<style type="text/css">
		  .f-fallback  {
			font-family: Arial, sans-serif;
		  }
		</style>
	  <![endif]-->
	  </head>
	  <body>
		<span class="preheader">This is an invoice for your recent purchase on '.FRONT_SITE_NAME.' </span>
		<table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
		  <tr>
			<td align="center">
			  <table class="email-content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
				<tr>
				  <td class="email-masthead">
					<a href="" class="f-fallback email-masthead_name">
					<img src="https://i.ibb.co/6myys4W/logo-1.png"/>
				  </a>
				  </td>
				</tr>
				<!-- Email Body -->
				<tr>
				  <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
					<table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
					  <!-- Body content -->
					  <tr>
						<td class="content-cell">
						  <div class="f-fallback">
							<h1>Hi , '.$name.'</h1>
							<p>This is an invoice for your recent purchase.</p>
							<table class="attributes" width="100%" cellpadding="0" cellspacing="0" role="presentation">
							  <tr>
								<td class="attributes_content">
								  <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
									<tr>
									  <td class="attributes_item">
										<span class="f-fallback">
				  <strong>Amount Due:</strong> ₹ '.$final_price.'
				</span>
									  </td>
									</tr>
									<tr>
									  <td class="attributes_item">
										<span class="f-fallback">
				  <strong>Order ID:</strong> '.$order_id.'
				</span>
									  </td>
									</tr>
								  </table>
								</td>
							  </tr>
							</table>
							<!-- Action -->
							
							<table class="purchase" width="100%" cellpadding="0" cellspacing="0">
							 
							  <tr>
								<td colspan="2">
								  <table class="purchase_content" width="100%" cellpadding="0" cellspacing="0">
									<tr>
									  <th class="purchase_heading" align="left">
										<p class="f-fallback">Description</p>
									  </th>
									   <th class="purchase_heading" align="left">
										<p class="f-fallback">Qty</p>
									  </th>
									  <th class="purchase_heading" align="right">
										<p class="f-fallback">Amount</p>
									  </th>
									</tr>';
									$total_price = 0;
									foreach($getOrderDetailById as $val) {
										$total_price = $total_price+($val['qty']*$val['price']);
									$html.= '<tr>
										  <td width="40%" class="purchase_item"><span class="f-fallback">'.ucfirst($val['dish']).' ('. $val['attribute'].')</span></td>
										  <td width="40%" class="purchase_item"><span class="f-fallback">'.$val['qty'].'</span></td>
										  <td  width="20%" class="align-right purchase_item"><span class="f-fallback"> ₹ '.$val['qty']*$val['price'].'</span></td>
									</tr>';
									}
									$html.= '<tr>
									  <td width="80%" class="purchase_footer" valign="middle" colspan="2">
										<p class="f-fallback purchase_total purchase_total--label">Sub Total</p>
									  </td>
									  <td width="20%" class="purchase_footer" valign="middle">
										<p class="f-fallback purchase_total"> ₹  '.$total_price.'</p>
									  </td>
									</tr>
									<tr>
									  <td width="80%" class="purchase_footer" valign="middle" colspan="2">
										<p class="f-fallback purchase_total purchase_total--label">Coupon Applied</p>
									  </td>
									  <td width="20%" class="purchase_footer" valign="middle">
										<p class="f-fallback purchase_total"> - ₹  '.$coupon_amount.'</p>
									  </td>
									</tr>
									<tr>
									  <td width="80%" class="purchase_footer" valign="middle" colspan="2">
										<p class="f-fallback purchase_total purchase_total--label">Total</p>
									  </td>
									  <td width="20%" class="purchase_footer" valign="middle">
										<p class="f-fallback purchase_total"> ₹  '.$final_price.'</p>
									  </td>
									</tr>
									
								  </table>
								</td>
							  </tr>
							</table>
							<p>If you have any questions about this invoice, simply reply to this email or reach out to our <a href="'.FRONT_SITE_PATH.'">support team</a> for help.</p>
							<p>Cheers,
							  <br>'.FRONT_SITE_NAME.'</p>
							<!-- Sub copy -->
							
						  </div>
						</td>
					  </tr>
					</table>
				  </td>
				</tr>
				
			  </table>
			</td>
		  </tr>
		</table>
	  </body>
	</html>';
	return $html;
}


//ALL WALLET FUNCTION
function manageWallet($uid,$amt,$type,$msg,$payment_id='')
{	
	global $conn;
	$added_on = date('Y-m-d h:i:s');
	$query = mysqli_query($conn,"INSERT INTO `wallet`(`user_id`, `amt`, `msg`, `type`,`payment_id`, `added_on`) VALUES ('$uid','$amt','$msg','$type','$payment_id','$added_on')");
}

//to fetch all data of wallet
function getWallet($uid)
{
	global $conn;
	$data = array();
	$query = mysqli_query($conn,"select * from wallet where user_id = '$uid' order by `id` desc");
	while($row = mysqli_fetch_assoc($query))
	{
		$data[] = $row;
	}
	return $data;
}

function getWalletAmt($uid)
{
	global $conn;
	$credit = 0;
	$debit = 0;
	$query = mysqli_query($conn,"select * from wallet where user_id = '$uid'");
	while($row = mysqli_fetch_assoc($query))
	{
		if($row['type'] == 'in')
		{
			$credit = $credit + $row['amt'];
		}
		if($row['type'] == 'out')
		{
			$debit = $debit + $row['amt'];
		}
	}

	$totalWalleAmt = $credit - $debit;
	return $totalWalleAmt;
}



/************************************WEBSITE FRONT VIEW FUNCTIONS*******************************************/
?>