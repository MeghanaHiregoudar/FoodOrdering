<?php include('header.php');
$msg = "";
if(isset($_GET['id']) && $_GET['id']!='')
{
   $id = get_safe_value($_GET['id']);
   mysqli_query($conn,"UPDATE `user` SET `verify_email`= 1 WHERE `random_str` = '$id' ");

   //To add money to refered user
   $res = mysqli_query($conn,"SELECT `from_referral_code`,`email` FROM `user`  WHERE `random_str` = '$id' ");
   if(mysqli_num_rows($res) > 0)
   {
      $row = mysqli_fetch_assoc($res);
      $email = $row['email'];
      $from_referral_code = $row['from_referral_code'];
      $referal_query = mysqli_fetch_assoc(mysqli_query($conn,"SELECT `id` FROM `user`  WHERE `referral_code` = '$from_referral_code'"));
      $uid = $referal_query['id'];
      $getSetting = getSetting();
      $referal_msg = "Referral Amt from ".$email;
      manageWallet($uid,$getSetting['referal_amt'],'in',$referal_msg);
   }

   $msg = "CONGRATULATION'S EMAIL-ID VERIFIED";
}
else
{
    redirectPage(FRONT_SITE_PATH);
}
?>

<div class="breadcrumb-area gray-bg">
   <div class="container">
      <div class="breadcrumb-content">
         <ul>
            <li><a href="<?php echo FRONT_SITE_PATH?>shop">Home</a></li>
            <li class="active"> Email Verify </li>
         </ul>
      </div>
   </div>
</div>
<div class="contact-area pt-100 pb-100">
   <div class="container">
      <div class="row">
         <div class="col-12">
            <div class="contact-message-wrapper">
               <h4 class="contact-title">
					   <?php echo $msg;?>
					</h4>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include('footer.php'); ?>