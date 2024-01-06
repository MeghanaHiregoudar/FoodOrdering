<?php
include('db_con.php');
include('function.inc.php');
include('constant_inc.php');

$name = get_safe_value($_POST['name']);
$email = get_safe_value($_POST['email']);
$mobile = get_safe_value($_POST['mobile']);
$subject = get_safe_value($_POST['subject']);
$message = get_safe_value($_POST['message']);
$added_on = date('Y-m-d h:i:s');

$query = mysqli_query($conn,"INSERT INTO `contact_us`( `name`, `email`, `mobile`, `subject`, `message`, `added_on`) VALUES ('$name','$email','$mobile','$subject','$message','$added_on')");
if($query)
{
    echo "Thank you for connecting with us, will get back to you shortly";
}
?>