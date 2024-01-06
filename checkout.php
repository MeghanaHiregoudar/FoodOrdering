<?php include('header.php');

//if website is close then this page should not display
if($website_close == 1) {
   redirectPage(FRONT_SITE_PATH.'shop');
}

$cartArr = getUserDetailCart();
if(count($cartArr) > 0){

} else {
   redirectPage(FRONT_SITE_PATH."shop");
}

//This ti show&hide login panel from user and other information(oI)
if(isset($_SESSION['FOOD_USER_ID']))
{
   $is_show = '';
   $box_id = '';
   $panel_oI_show = 'show';
   $panel_oI_id = 'payment-2';
}
else
{
   $is_show = 'show';
   $box_id = 'payment-1';
   $panel_oI_show = '';
   $panel_oI_id = '';
}

//to Display name,email,mobile if user id logged in
if(isset($_SESSION['FOOD_USER_ID']))
{
   $userArr = getUserDetailsById();
}

$cart_min_val_error ='';
//Add data in order_master table
if(isset($_POST['place_order']))
{
  //checking for cart minimum price
   if($cart_min_price != '')
   {
      $cart_price_check = $total_price;
      if($_POST['coupon_code'] != '')
      {
         $cart_price_check = $_SESSION['COUPON_FINAL_PRICE'];
      }
      if($cart_price_check >= $cart_min_price)
      {
         $cart_min_val_error ='';
      }
      else
      {
         $cart_min_val_error = 'yes';
      }
   }

   //insert data and send mail
   if($cart_min_val_error == '')
   {
      $user_id = $_SESSION['FOOD_USER_ID'];
      $checkout_name = get_safe_value($_POST['checkout_name']);
      $checkout_email = get_safe_value($_POST['checkout_email']);
      $checkout_mobile = get_safe_value($_POST['checkout_mobile']);
      $checkout_address = get_safe_value($_POST['checkout_address']);
      $checkout_zipcode = get_safe_value($_POST['checkout_zipcode']);
      $payment_type = get_safe_value($_POST['payment_type']);
      $added_on=date('Y-m-d h:i:s');

      if(isset($_SESSION['COUPON_CODE']) && isset($_SESSION['COUPON_FINAL_PRICE']))
      {
         $coupon_code = get_safe_value($_SESSION['COUPON_CODE']);
         $final_price = get_safe_value($_SESSION['COUPON_FINAL_PRICE']);
      }
      else
      {
         $coupon_code = '';
         $final_price = $total_price;
      }
   
      $query = mysqli_query($conn,"INSERT INTO `order_master` (`user_id`, `name`, `email`, `mobile`, `address`, `zipcode`,`total_price`,`coupon_code`, `final_price`, `payment_status`, `payment_type`,`order_status`, `added_on`) VALUES ('$user_id','$checkout_name','$checkout_email','$checkout_mobile','$checkout_address','$checkout_zipcode','$total_price','$coupon_code','$final_price','pending','$payment_type','1','$added_on')");
   
      if($query)
      {
         $order_id = mysqli_insert_id($conn);
         //To display order id in sucess page
         $_SESSION['ORDERED_ID'] = $order_id;
         foreach($cartArr as $key=>$val)
         {
            $order_detail_query = mysqli_query($conn,"INSERT INTO `order_detail`( `order_id`, `dish_details_id`, `price`, `qty`) VALUES ('$order_id','".$key."','".$val['price']."','".$val['qty']."')");
         }

         if($order_detail_query)
         {
            emptyCart();
            //To get user email id
            $getUserDetailsById = getUserDetailsById();
            if($checkout_email == $getUserDetailsById['email'] )
            {
               $email = $getUserDetailsById['email'];
            }
            else
            {
               $email = $checkout_email;
            }
            
            //if payment status is cod
            if($payment_type == 'cod')
            {
               //Calling function to get html body to send email
               $orderBody = Order_emailInvoice($order_id);

               //Sending mail to ordered person
               include('smtp/PHPMailerAutoload.php');
               send_email($email,$orderBody,'Order Placed');

               redirectPage(FRONT_SITE_PATH.'success');
            }

            //if payment type is wallet
            if($payment_type == 'wallet')
            {
               //Insert the wallet debited amt
               manageWallet($_SESSION['FOOD_USER_ID'],$final_price,'out','Order Id'.$order_id);
               //update payment ststua as success
               mysqli_query($conn,"update `order_master` set `payment_status` = 'success' where `id` = '$order_id' ");
               
               $orderBody = Order_emailInvoice($order_id);
               //Sending mail to ordered person
               include('smtp/PHPMailerAutoload.php');
               send_email($email,$orderBody,'Order Placed');

               redirectPage(FRONT_SITE_PATH.'success');
            }

            //if payment type is paytm
            if($payment_type == 'paytm')
            {
               $paytm_oid=$order_id;
               $paytm = '<form method="post" action="pgRedirect.php" name="paymenform" style="display:none">
                           <label>ORDER_ID::*</label>
                           <input id="ORDER_ID" tabindex="1" maxlength="20" size="20" name="ORDER_ID" autocomplete="off" value="'.$paytm_oid.'">
                           <label>CUSTID ::*</label>
                           <input id="CUST_ID" tabindex="2" maxlength="12" size="12" name="CUST_ID" autocomplete="off" value="'.$_SESSION['FOOD_USER_ID'].'">
                           <label>INDUSTRY_TYPE_ID ::*</label>
                           <input id="INDUSTRY_TYPE_ID" tabindex="4" maxlength="12" size="12" name="INDUSTRY_TYPE_ID" autocomplete="off" value="Retail">
                           <label>Channel ::*</label>
                           <input id="CHANNEL_ID" tabindex="4" maxlength="12" size="12" name="CHANNEL_ID" autocomplete="off" value="WEB">
                           <label>txnAmount*</label>
                           <input title="TXN_AMOUNT" tabindex="10" type="text" name="TXN_AMOUNT" value="'.$final_price.'">
                           <input value="CheckOut" type="submit"	onclick="">
                        </form>
                        <script type="text/javascript">
			                  document.paymenform.submit();
		                  </script>';
               echo $paytm;
            }
         }
      }
   }


}

?>

<div class="breadcrumb-area gray-bg">
   <div class="container">
      <div class="breadcrumb-content">
         <ul>
            <li><a href="<?php echo FRONT_SITE_PATH; ?>shop">Home</a></li>
            <li class="active"> Checkout </li>
         </ul>
      </div>
   </div>
</div>
<!-- checkout-area start -->
<div class="checkout-area pb-80 pt-100">
   <div class="container">
      <div class="row">
         <div class="col-lg-9">
            <div class="checkout-wrapper">
               <div id="faq" class="panel-group">
                  <div class="panel panel-default">
                     <div class="panel-heading">
                        <h5 class="panel-title"><span>1.</span> <a data-toggle="collapse" data-parent="#faq" href="#payment-1">Checkout method</a></h5>
                     </div>
                     <div id="<?php echo $box_id;?>" class="panel-collapse collapse <?php echo $is_show; ?> ">
                        <div class="panel-body">
                           <div class="row">
                              <div class="col-lg-8 offset-lg-2">
                                 <div class="checkout-login">
                                    <div class="title-wrap">
                                       <h4 class="cart-bottom-title section-bg-white">LOGIN</h4>
                                    </div>
                                    <form method="POST" id="login_form">
                                       <div class="login-form">
                                          <label>Email Address * </label>
                                          <input type="email" name="user_email" placeholder="Email Id" required>
                                       </div>
                                       <div class="login-form">
                                          <label>Password *</label>
                                          <input type="password" name="user_password" placeholder="Password" required>
                                       </div>

                                       <div class="login-forget">
                                          <input type="hidden" name="type" value="login">
                                          <input type="hidden" name="is_checkout" id="is_checkout" value="yes">
                                          <p>* Required Fields</p>
                                       </div>
                                       <div class="checkout-login-btn">
                                          <button type="submit" id="login_submit" class="my_btn"><span>Login</span></button>
                                          <a href="<?php echo FRONT_SITE_PATH?>login_register">Register</a>
                                       </div>
                                    </form>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="panel panel-default">
                     <div class="panel-heading">
                        <h5 class="panel-title"><span>2.</span> <a data-toggle="collapse" data-parent="#faq" href="#payment-2">Other Information</a></h5>
                     </div> 
                     <div id="<?php echo $panel_oI_id;?>" class="panel-collapse collapse <?php echo $panel_oI_show;?>">
                        <div class="panel-body">
                           <form method="POST">
                              <div class="billing-information-wrapper">
                                 <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                       <div class="billing-info">
                                          <label>Name</label>
                                          <input type="text" name="checkout_name" placeholder="Name" value="<?php echo $userArr['name']; ?>" required>
                                       </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                       <div class="billing-info">
                                          <label>Email Address</label>
                                          <input type="email" name="checkout_email" placeholder="Email Address" value="<?php echo $userArr['email']; ?>" required>
                                       </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                       <div class="billing-info">
                                          <label>Mobile</label>
                                          <input type="text" name="checkout_mobile" placeholder="Mobile Number" value="<?php echo $userArr['mobile']; ?>" maxlength="10" required>
                                          </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                       <div class="billing-info">
                                          <label>Zip/Postal Code</label>
                                          <input type="text"  name="checkout_zipcode" placeholder="ZipCode" maxlength="6"  required>
                                       </div>
                                    </div>
                                    <div class="col-lg-8 col-md-8">
                                       <div class="billing-info">
                                          <label>Address</label>
                                          <textarea rows="2" name="checkout_address" placeholder="Address" required></textarea>
                                       </div>
                                    </div>
                                    <div class="col-lg-8 col-md-8">
                                       <div class="row">
                                          <div class="col-lg-5 col-md-5">
                                             <div class="billing-info">
                                                <label>Coupon Code</label>
                                                <input type="text"  name="coupon_code" id="coupon_code" placeholder="Coupon Code" >
                                                <div id="coupon_code_error" class="text-danger font-weight-bold"></div>
                                             </div>
                                          </div>
                                          <div class="col-lg-4 col-md-4">
                                             <div class="billing-back-btn">
                                                <div class="billing-btn">
                                                   <button type="button" id="apply_coupon" onclick="applyCouponCode()">Apply Coupon</button>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="ship-wrapper">
                                    <div class="single-ship">
                                       <input type="radio" name="payment_type" value="cod" >
                                       <label>Cash on Delivery(COD)</label>
                                    </div>
                                    <div class="single-ship">
                                       <input type="radio" name="payment_type" value="paytm" checked="checked">
                                       <label>PayTm</label>
                                    </div>
                                    <div class="single-ship">
                                       <?php $id_disable=''; $disable_msg ='' ;
                                          if($getWalletAmt < $total_price){
                                             $id_disable='disabled'; $disable_msg =" <span class='text-danger'>(Wallet Amount is not sufficient to make this Order)</span>";
                                           } ?>
                                       <input type="radio" name="payment_type" value="wallet" <?php echo $id_disable;?> >
                                       <label>Wallet</label><?php echo $disable_msg; ?>
                                       
                                    </div>			
                                 </div>
                                 <div class="billing-back-btn">
                                    <div class="billing-btn">
                                       <button type="submit" name="place_order">Place Order</button>
                                    </div>
                                 </div>
                                 <?php if($cart_min_val_error == 'yes') {?>
                                 <div class="text-danger font-weight-bold"> <?php echo $cart_min_price_msg; ?></div>
                                 <?php } ?>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-lg-3">
            <div class="checkout-progress">
               <h4>Cart Details</h4>
               <ul>
                  <?php foreach($cartArr as $key => $list){ ?>
                     <li class="single-shopping-cart" >
                        <div class="shopping-cart-img">
                           <a href="javascript:void(0)"><img  src="<?php echo SITE_DISH_IMAGE.$list['image']; ?>" width= "100%" alt="Dish Image"></a>
                        </div>
                        <div class="shopping-cart-title">
                           <h4><a href="javascript:void(0)"><?php echo ucfirst($list['dish']); ?> </a></h4>
                              <?php echo "(".$list['attribute'].")"; ?> 
                           <h6>Qty: <?php echo $list['qty']; ?> </h6>
                           <span><?php echo "₹ ".$list['qty'] * $list['price'] ; ?> </span>
                        </div>
                     </li>
                  <?php } ?>
					</ul>
						<div class="shopping-cart-total mb-3 mx-4 mt-0">
							<h4><strong class="total_price">Total :</strong> <span class="shop-total"><?php echo "₹ ".$total_price?> </span></h4>
						</div>
                  <div class="shopping-cart-total coupon_price_box mb-3 mx-4 mt-0">
							<h4><strong>Coupon : </strong> <span class="shop-total coupon_code_price"></span></h4>
						</div>
                  <div class="shopping-cart-total coupon_price_box mb-3 mx-4 mt-0">
							<h4><strong> Total :</strong>  <span class="shop-total final_price"> </span></h4>
						</div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php 

//this is to unset here coz if user reloads page without placing order then coupon details shud get unset
if(isset($_SESSION['COUPON_CODE']))
{
   unset($_SESSION['COUPON_CODE']);
   unset($_SESSION['COUPON_FINAL_PRICE']);
}

include('footer.php'); ?>