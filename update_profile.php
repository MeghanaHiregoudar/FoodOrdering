<?php
session_start();
include('db_con.php');
include('function.inc.php');
include('constant_inc.php');
if(!isset($_SESSION['FOOD_USER_ID']))
{
    redirectPage(FRONT_SITE_PATH."shop");
}

$type = get_safe_value($_POST['type']);
$user_id = $_SESSION['FOOD_USER_ID'];

if($type == 'profile')
{
    $name = get_safe_value($_POST['name']);
    $mobile = get_safe_value($_POST['mobile']);

    //renaming session when name is changed
    $_SESSION['FOOD_USER_NAME']=$name;

    mysqli_query($conn,"UPDATE `user` SET `name`= '$name' ,`mobile`= '$mobile'  WHERE `id` = '$user_id'");
    $arr=array('status'=>'success','msg'=>'Profile has been updated');
	echo json_encode($arr);
}

if($type == 'change_password')
{
    $old_password = get_safe_value($_POST['old_password']);
    $new_password = get_safe_value($_POST['new_password']);
    $res = mysqli_query($conn,"SELECT `password` FROM `user` WHERE `id` = '$user_id' ");
    $row = mysqli_fetch_assoc($res);
    $db_password = $row['password'];
    if(password_verify($old_password,$db_password))
    {
        $new_db_password = password_hash($new_password,PASSWORD_BCRYPT);
        mysqli_query($conn,"UPDATE `user` SET `password`='$new_db_password' WHERE `id` = '$user_id' ");
        $arr=array('status'=>'success','msg'=>'Password Changes Successfully');
    }
    else
    {
        $arr=array('status'=>'error','msg'=>'Old Password Id Wrong ');
    }
    echo json_encode($arr);
}

?>