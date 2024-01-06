<?php
session_start();
include('db_con.php');
include('function.inc.php');
include('constant_inc.php');
include('vendor/autoload.php');


/***************THIS PAGE IS USED FOR BOTH FRONT END AND ADMIN INVOICE DOWNLOAD***************/

//Checkin weather admin is logged in then ok
if(isset($_SESSION['ADMIN_NAME']))
{

} else {
    //else it will check for user login
    if(!isset($_SESSION['FOOD_USER_ID']))
    {
        redirectPage(FRONT_SITE_PATH."shop");
    }
}


if(isset($_GET['id']) && $_GET['id']>0 )
{
    $order_id = get_safe_value($_GET['id']);

   $res = mysqli_query($conn,"SELECT * FROM `order_master` WHERE `id` = '$order_id'");

    //After getting order id in url checking weather admin logged in
    if(isset($_SESSION['ADMIN_NAME']))
    {
        $row = mysqli_fetch_assoc($res);
        //tofetch user id when user is not logged in in front website
        $uid = $row['user_id'];
    }
    else
    {
        //else verifying logged user order details only getting fetched (coz ot to download with other id in url)
        $check = mysqli_fetch_assoc($res);
        //Chekcing if logged user and id getting from url are of same person
        if($check['user_id'] != $_SESSION['FOOD_USER_ID'])
        {
            redirectPage(FRONT_SITE_PATH."shop");
        }
        $uid = $_SESSION['FOOD_USER_ID'];
    }


    $invoiceBody = Order_emailInvoice($order_id,$uid);

    // Create an instance of the class:
    $mpdf = new \Mpdf\Mpdf();
    // Write some HTML code:
    $mpdf->WriteHTML($invoiceBody);
    // Output a PDF file directly to the browser
    $mpdf->Output();

    //To DOWNLOAD IT FORCEFULLY
    // $file=time().'.pdf';
	// $mpdf->Output($file,'D');
    
}



?>