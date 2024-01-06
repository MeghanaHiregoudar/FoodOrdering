<?php
session_start();
include('db_con.php');
include('function.inc.php');
include('constant_inc.php');

$coupon_code = get_safe_value($_POST['coupon_code']);

$query = mysqli_query($conn,"SELECT * FROM `coupon_code` WHERE `coupon_code` = '$coupon_code' and  `status` = 1");
if(mysqli_num_rows($query) > 0)
{
    $row = mysqli_fetch_assoc($query);
    $coupon_type = $row['coupon_type'];
    $coupon_value = $row['coupon_value'];
    $cart_min_value = $row['cart_min_value'];
    $expired_on = strtotime($row['expired_on']);
    $curr_time = strtotime(date('Y-m-d'));
    $cartArr = getUserDetailCart();
   
    $getcartTotalPrice=getcartTotalPrice();
    
    if($getcartTotalPrice>$cart_min_value)
    {
        if($curr_time > $expired_on )
        {
            $response = array('status' => 'error' , 'msg' => 'Coupon Code expired');
        }
        else
        {
            $coupon_value_applied = 0;
            $coupon_price = 0;
            if($coupon_type == 'F')
            {
                $coupon_value_applied = $getcartTotalPrice-$coupon_value;
                $coupon_price = $coupon_value;
            }
            if($coupon_type=='P')
            {
                $coupon_value_applied = $getcartTotalPrice - ($coupon_value * $getcartTotalPrice / 100 );
                $coupon_price = ($cart_min_value * $getcartTotalPrice / 100 );
            }

            //To strore in db store coupon details in session (unset in checkout page)
            $_SESSION['COUPON_CODE']= $coupon_code;
            $_SESSION['COUPON_FINAL_PRICE'] = $coupon_value_applied;

            $response = array('status' => 'success' , 'msg' => 'Coupon Code Applied Successfully','coupon_value_applied' => $coupon_value_applied, 'coupon_price' => $coupon_value);
        }
    }
    else
    {
        $response = array('status' => 'error' , 'msg' => ' To Apply This Coupon. Cart Minimum value should be greater than '.$cart_min_value);
    }
}
else
{
    $response = array('status' => 'error' , 'msg' => 'Coupon Code Does Not Exists.. Try Valid Code!!');    
}

echo json_encode($response);

?>