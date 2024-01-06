<?php
session_start();
include('db_con.php');
include('function.inc.php');
include('constant_inc.php');


$dish_attr = get_safe_value($_POST['dish_attr']);
$type = get_safe_value($_POST['type']);
$added_on = date('Y-m-d h:i:s');

//Functionality is when user adds dish to cart. check weathe he is logged in or not.
//IF LOGIN THEN ADD TO DB ELSE ADD TO SESSION ONCE USER LOGINS THEN ADD THAT SESSION DATA TO DB.

if($_POST['type'] == 'add')
{
    $qty = get_safe_value($_POST['qty']);
    //this is to check user id login?
    if(isset($_SESSION['FOOD_USER_ID']))
    {
        $user_id = $_SESSION['FOOD_USER_ID'];
        //written in function_inc file.function to insert dish to cart by checking if present update else add
        manageUserCart($user_id,$dish_attr,$qty);
    }
    else
    {
        //if data is present den update quantity else add data
        $_SESSION['cart'][$dish_attr]['qty'] = $qty;
    }
    
    //to find count of dish added to array and display without page load 
    $totaldishCount = count(getUserDetailCart());

    //to find total amount of dish added to array and display without page load 
    $getUserDetailCart = getUserDetailCart();
    $total_price = 0;
    foreach($getUserDetailCart as $list)
    {
        $total_price = $total_price+($list['qty'] * $list['price']);
    }
    $getdishDetailById = getdishDetailById($dish_attr);
    $dish = $getdishDetailById['dish'];
    $attribute = $getdishDetailById['attribute'];
    $image = $getdishDetailById['image'];
    $price = $getdishDetailById['price'];
    
    $arr = array('totaldishCount' => $totaldishCount , "total_price" => $total_price ,"dish" => $dish, "attribute" => $attribute, "image" => $image , "price" =>$price);

    echo json_encode($arr);
}


if($_POST['type'] == 'delete_cart_dish')
{
    removedishfromcartbyid($dish_attr);
    $totaldishCount = count(getUserDetailCart());
    $getUserDetailCart = getUserDetailCart();
    $total_price = 0;
    foreach($getUserDetailCart as $list)
    {
        $total_price = $total_price+($list['qty'] * $list['price']);
    }
    $arr = array('totaldishCount' => $totaldishCount , "total_price" => $total_price );

    echo json_encode($arr);
}

?>