<?php include('header.php');  ?>
<div class="login-register-area pt-95 pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                        <div class="login-register-wrapper">
                            <div class="login-register-tab-list nav">
                                <a class="active" data-toggle="tab" href="#lg1">
                                    <h4> login </h4>
                                </a>
                                <a data-toggle="tab" href="#lg2">
                                    <h4> register </h4>
                                </a>
                            </div>
                            <div class="tab-content">
                                <div id="lg1" class="tab-pane active">
                                    <div class="login-form-container">
                                        <div class="login-register-form">
                                            <form id="login_form" method="post">
                                                <input type="email" name="user_email" placeholder="Email Id" required>
                                                <input type="password" name="user_password" placeholder="Password" required>
                                                <input type="hidden" name="type" value="login">
                                                <input type="hidden" name="is_checkout" id="is_checkout" value="">
                                                <div class="button-box">
                                                    <div class="login-toggle-btn">
                                                        <a href="<?php echo FRONT_SITE_PATH; ?>forgot_password">Forgot Password?</a>
                                                    </div>
                                                    <button type="submit" id="login_submit"><span>Login</span></button>
                                                </div>
                                                <div id="message" class="text-danger"></div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div id="lg2" class="tab-pane">
                                    <div class="login-form-container">
                                        <div class="login-register-form">
                                            <form method="post" id="register_form">
                                                <input type="text" name="name" id="name" placeholder="Full Name" required>
                                                <input name="email" id="email" placeholder="Email" type="email" required>
                                                <div id="error_email" class="error_email_feild"></div>                                                
                                                <input type="password" name="password" id="password" placeholder="Password" required>
                                                <input type="text" name="mobile" id="mobile" placeholder="Mobile Number" required>
                                                <input type="text" name="from_referral_code" id="from_referral_code" placeholder="Referral Code">
                                                <input type="hidden" name="type" value="registration">
                                                <div class="button-box">
                                                    <button type="submit" id="register_submit"><span>Register</span></button>
                                                </div>
                                                <div id="success_message" class="success_msg"></div>
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
<?php include('footer.php'); ?>