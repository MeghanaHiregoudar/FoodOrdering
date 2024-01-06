<?php
session_start();
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");


include('db_con.php');
include('function.inc.php');
include('constant_inc.php');

$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;


$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application�s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


if($isValidChecksum == "TRUE") 
{
	echo "<b>Checksum matched and following are the transaction details:</b>" . "<br/>";
	if ($_POST["STATUS"] == "TXN_SUCCESS") {
		
		//Process your transaction here as success transaction.
		//Verify amount & order id received from Payment gateway with your application's order id and amount.
		$TXNID=$_POST['TXNID']; 
		$oid = $_POST["ORDERID"];
		$amt=$_POST['TXNAMOUNT'];

		/*if it comes from add wallet then oid will start with "ORDS" if its from wallet than add to wallet */
		$order_is_split = explode("_",$oid);
		if($order_is_split[0] == 'ORDS'){
			$uid=$order_is_split[1];
			manageWallet($uid,$amt,'in','Added By Bank',$TXNID);
			if(!isset($_SESSION['FOOD_USER_ID']))
			{
				$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `user` where id = '$uid' "));
				$_SESSION['FOOD_USER_ID']=$row['id'];
				$_SESSION['FOOD_USER_NAME']=$row['name'];
				$_SESSION['FOOD_USER_EMAIL']=$row['email'];
			}
			redirectPage(FRONT_SITE_PATH.'wallet');
		}
		//Else go to oreder master 
		else
		{
			$oid = $_POST["ORDERID"]; 
	
			//update payment id and status to table
			mysqli_query($conn,"UPDATE `order_master` SET `payment_status` = 'success' , `payment_id` = '".$TXNID."' WHERE `id` = '$oid' ");

			$_SESSION['ORDERED_ID'] = $oid;
			
			if(!isset($_SESSION['FOOD_USER_ID']))
			{
				$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT user.* FROM `user`,`order_master` where user.id = order_master.user_id AND `order_master`.`payment_id` = '$TXNID'"));
				$_SESSION['FOOD_USER_ID']=$row['id'];
				$_SESSION['FOOD_USER_NAME']=$row['name'];
			}
		
			$getUserDetailsById = getUserDetailsById();
			$email = $getUserDetailsById['email'];
			//Calling function to get html body to send email
			$orderBody = Order_emailInvoice($oid);
			
			//Sending mail to ordered person
			include('smtp/PHPMailerAutoload.php');
			send_email($email,$orderBody,'Order Placed');

			redirectPage(FRONT_SITE_PATH.'success');
		}
	}
	else {

		$TXNID=$_POST['TXNID'];
		$oid = $_POST["ORDERID "];
		
		//update payment id and status to table
		mysqli_query($conn,"UPDATE `order_master` SET `payment_status` = 'failed' , `payment_id` = '".$TXNID."' WHERE `id` = '$oid' ");

		redirectPage(FRONT_SITE_PATH.'error');
		
	}
}
else 
{
	//Process transaction as suspicious.
	$TXNID=$_POST['TXNID'];
	$oid = $_POST["ORDERID "];
		
	//update payment id and status to table
	mysqli_qeury($conn,"UPDATE `order_master` SET `payment_status` = 'failed' , `payment_id` = '".$TXNID."' WHERE `id` = '$oid' ");

	redirectPage(FRONT_SITE_PATH.'error');
		
}

?>