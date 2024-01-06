<?php include('header.php');
if(!isset($_SESSION['ORDERED_ID']))
{
    redirectPage(FRONT_SITE_PATH."shop");
} 

if(isset($_SESSION['COUPON_CODE']))
{
   unset($_SESSION['COUPON_CODE']);
   unset($_SESSION['COUPON_FINAL_PRICE']);
}
?>

<div class="breadcrumb-area gray-bg">
   <div class="container">
      <div class="breadcrumb-content">
         <ul>
            <li><a href="<?php echo FRONT_SITE_PATH; ?>shop">Home</a></li>
            <li class="active">Order Failed </li>
         </ul>
      </div>
   </div>
</div>
<div class="about-us-area pt-50 pb-100">
   <div class="container">
      <div class="row">
         <div class="col-lg-12 col-md-7 d-flex align-items-center">
            <div class="overview-content-2">
                <h2>Order has been failed. Please try after sometime.</h2>
                <h2>Thank You For Connetcing With <span>Food Ordering</span> Store !</h2>                
            </div>
         </div>
      </div>
   </div>
</div>
<?php 
unset( $_SESSION['ORDERED_ID']);
include('footer.php'); ?>