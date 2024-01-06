<?php
session_start();
include('db_con.php');
include('function.inc.php');
include('constant_inc.php');
include('smtp/PHPMailerAutoload.php');

$type = get_safe_value($_POST['type']);
$added_on = date('Y-m-d h:i:s');

if($type == "registration")
{
    
    $name = get_safe_value($_POST['name']);
    $email = get_safe_value($_POST['email']);
    $mobile = get_safe_value($_POST['mobile']);
    $password = get_safe_value($_POST['password']);
    $from_referral_code = get_safe_value($_POST['from_referral_code']);

    $email_check = mysqli_query($conn,"select * from user where `email` = '$email' ");
    if(mysqli_num_rows($email_check) > 0)
    {
        $response = array('status' => 'error' , 'msg' => 'Email Already Registered', 'feild' => 'error_email');
    }
    else
    {
        $password_encrypt = password_hash($password,PASSWORD_BCRYPT);

        //This is to generate random string for email verification and referal code
        $random_str = random_str();
        $referral_code = random_str();
        
        $query = mysqli_query($conn,"INSERT INTO `user`( `name`, `email`, `mobile`, `password`,`random_str`, `referral_code`,`from_referral_code`,`added_on`) VALUES ('$name','$email','$mobile','$password_encrypt','$random_str','$referral_code','$from_referral_code','$added_on')");

        $insert_id = mysqli_insert_id($conn);
        //when user gets registered default amt in wallet
        if($query)
        {
            $getSetting = getSetting();
            $wallet_amt = $getSetting['wallet_amt'];
            if($wallet_amt > 0)
            {
                manageWallet($insert_id,$wallet_amt,'in','Registration Offer');
            }
        }

        // $id = mysqli_insert_id($conn);
        $body=FRONT_SITE_PATH."verify?id=".$random_str;
        send_email($email,$body,"Verify Your Email Id To Login Food Ordering App");

        $response = array('status' => 'success' , 'msg' => 'ThankYou for register. please checkyour email id, to verify your account!!', 'feild' => 'message');    
    }
    echo json_encode($response);
}

if($type == "login")
{   
    $user_email = get_safe_value($_POST['user_email']);
    $user_password = get_safe_value($_POST['user_password']);  
    
    $login_res = mysqli_query($conn,"SELECT * FROM `user` WHERE `email` = '$user_email' ");

    if(mysqli_num_rows($login_res) > 0)
    {
        $login_row = mysqli_fetch_assoc($login_res);
        $status = $login_row['status'];
        $verify_email = $login_row['verify_email'];
        $dbpassword = $login_row['password'];
        if($verify_email == 1)
        {
            if($status == 1)
            {
                if(password_verify($user_password,$dbpassword))
                {
                    $_SESSION['FOOD_USER_ID']=$login_row['id'];
					$_SESSION['FOOD_USER_NAME']=$login_row['name'];
					$_SESSION['FOOD_USER_EMAIL']=$login_row['email'];
                    //To check Weather Added dish is session before login.. if its present then add to db
                    if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0)
                    {
                        foreach($_SESSION['cart'] as $key=>$val)
                        {
                            manageUserCart($_SESSION['FOOD_USER_ID'],$key,$val['qty']);
                        }
                    }

                    $arr=array('status'=>'success','msg'=>'','redirect'=>FRONT_SITE_PATH.'shop');
                }
                else
                {
                    $arr=array('status'=>'error','msg'=>'Please enter correct password');
                }
            }
            else
            {
                $arr=array('status'=>'error','msg'=>'Your account has been deactivated.');
            }
        }
        else
        {
            $arr=array('status'=>'error','msg'=>'Please verify your email id');
        }
    }
    else
    {
		$arr=array('status'=>'error','msg'=>'Please enter valid email id');	
	}
	echo json_encode($arr);
}

if($type == "forgot_password")
{   
    $user_email = get_safe_value($_POST['user_email']);
    
    $forgot_res = mysqli_query($conn,"SELECT * FROM `user` WHERE `email` = '$user_email' ");

    if(mysqli_num_rows($forgot_res) > 0)
    {
        $forgot_row = mysqli_fetch_assoc($forgot_res);
        $status = $forgot_row['status'];
        $verify_email = $forgot_row['verify_email'];
        $id = $forgot_row['id'];
        if($verify_email == 1)
        {
            if($status == 1)
            {
                $rand_password = rand(11111,99999);
                $new_password = password_hash($rand_password,PASSWORD_BCRYPT);
                mysqli_query($conn,"UPDATE `user` SET `password`='$new_password' WHERE `id` = '$id' ");
                $body = "<h5>Here is your new password. Login with this credentials and change your password</h5> <p><strong>Email Id :</strong>".$user_email."</p> <p><strong>Password :</strong>".$rand_password."</p> ";
                send_email($user_email,$body,"Your New Password");
                $arr=array('status'=>'error','msg'=>'Password has been reset and send it to your email id');
            }
            else
            {
                $arr=array('status'=>'error','msg'=>'Your account has been deactivated.');
            }
        }
        else
        {
            $arr=array('status'=>'error','msg'=>'Please verify your email id');
        }
    }
    else
    {
		$arr=array('status'=>'error','msg'=>'Please enter valid email id');	
	}
	echo json_encode($arr);
}

?>