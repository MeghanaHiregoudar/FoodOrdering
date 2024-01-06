<?php
include ("header.php");
if(!isset($_SESSION['FOOD_USER_ID']))
{
    redirectPage(FRONT_SITE_PATH."shop");
}

//To fetch profile details of user 
$userArr = getUserDetailsById();
?>
<div class="myaccount-area pb-80 pt-100">
    <div class="container">
        <div class="row">
            <div class="ml-auto mr-auto col-lg-9">
                <div class="checkout-wrapper">
                    <div id="faq" class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title"><span>1</span> <a data-toggle="collapse" data-parent="#faq" href="#my-account-1">Edit your account information </a></h5>
                            </div>
                            <div id="my-account-1" class="panel-collapse collapse show">
                                <div class="panel-body">
                                    <form method="post" id="profile_form">
										<div class="billing-information-wrapper">
											<div class="account-info-wrapper">
												<h4>My Account Information</h4>
												<h5>Your Personal Details</h5>
												<h5 class="font-weight-bold">Reffaral Code : <?php echo $userArr['referral_code']; ?></h5>
											</div>
											<div class="row">
												<div class="col-lg-6 col-md-6">
													<div class="billing-info">
														<label>Name</label>
														<input type="text" id="uname" name="name" required value="<?php echo $userArr['name'];?>">
													</div>
												</div>
												<div class="col-lg-6 col-md-6">
													<div class="billing-info">
														<label>Mobile Number</label>
														<input type="text" name="mobile" value="<?php echo $userArr['mobile'];?>" required>
													</div>
												</div>		
												<div class="col-lg-12 col-md-12">
													<div class="billing-info">
														<label>Email Address</label>
														<input type="email" readonly="readonly" value="<?php echo $userArr['email'];?>">
													</div>
												</div>
										    </div>
                                            <input type="hidden" name="type" value="profile">
											<div class="billing-back-btn">
												<div class="billing-back">
													<a href="#"><i class="ion-arrow-up-c"></i> back</a>
												</div>
												<div class="billing-btn">
													<button type="submit" id="profile_submit">Save</button>
												</div>
											</div>
										</div>
									</form>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title"><span>2</span> <a data-toggle="collapse" data-parent="#faq" href="#my-account-2">Change your password </a></h5>
                            </div>
                            <div id="my-account-2" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form method="post" id="change_Password_form">
										<div class="billing-information-wrapper">
											<div class="account-info-wrapper">
												<h4>Change Password</h4>
												<h5>Your Password</h5>
										    </div>
											<div class="row">
												<div class="col-lg-12 col-md-12">
												    <div class="billing-info">
														<label>Old Password</label>
														<input type="password" name="old_password" required>
													</div>
												</div>
												<div class="col-lg-12 col-md-12">
													<div class="billing-info">
														<label>New Password Confirm</label>
														<input type="password" name="new_password" id="new_password" required>
													</div>
												</div>
                                                <div class="col-lg-12 col-md-12">
													<div class="billing-info">
														<label>Password Confirm</label>
														<input type="password" id="confirm_password" required>
													</div>
												</div>
                                                <input type="hidden" name="type" value="change_password">
											</div>
											<div class="billing-back-btn">
												<div class="billing-back">
													<a href="#"><i class="ion-arrow-up-c"></i> back</a>
												</div>
												<div class="billing-btn">
													<button type="submit" id="password_submit">Save</button>
												</div>
											</div>
										</div>
									</form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include("footer.php");
?>