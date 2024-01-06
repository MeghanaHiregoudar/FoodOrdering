<?php 
    session_start();
    include('db_con.php');
    include('function.inc.php');
    include('constant_inc.php'); 
    
    //this function is to check weather site is open or close
    $getSetting = getSetting();
    $website_close = $getSetting['website_close'];
    $website_close_msg = $getSetting['website_close_msg'];
    $cart_min_price = $getSetting['cart_min_price'];
    $cart_min_price_msg = $getSetting['cart_min_price_msg'];
    $wallet_amt = $getSetting['wallet_amt'];
    
    //this is to check the status of dish which are added to cart weather they are active or deactive
    //(if deactive before loading all data deactive dish will be removed from cart)
    getDishStatusInCart();

    //to update qty in top cart when updated in cart page
    if(isset($_POST['update_cart_qty']))
    {
        foreach($_POST['qty'] as $key=>$val)
        {
            if(isset($_SESSION['FOOD_USER_ID']))
            {
                $user_id = $_SESSION['FOOD_USER_ID'];
                if($val[0] == 0)
                {
                    //delete from dish_cart table when qty updated to 0 in cart page
                    mysqli_query($conn, "DELETE FROM `dish_cart` WHERE `dish_details_id` = '$key' AND `user_id` = '$user_id'"); 
                } else {
                    //Update qty in dish_cart table when updated in cart page
                    mysqli_query($conn, " UPDATE `dish_cart` SET `qty`='$val[0]' WHERE `dish_details_id` = '$key' AND `user_id` = '$user_id'"); 
                }  
            }
            else
            {
                if($val[0] == 0)
                {
                    unset($_SESSION['cart'][$key]);
                } else {
                    $_SESSION['cart'][$key]['qty'] = $val[0];
                }
            }
        }
    }

    //TO CHECK WEATHER DISH IS IN SESION OR IN DB
    $cartArr = getUserDetailCart();
    
    //to get count and total amount of dished added
    $totaldishCount = count($cartArr);
    
    //To get total price 
    $total_price = getcartTotalPrice();

    //to get all data of wallet
    if(isset($_SESSION['FOOD_USER_ID'])) {
        $getWallet = getWallet($_SESSION['FOOD_USER_ID']);
        $getWalletAmt = getWalletAmt($_SESSION['FOOD_USER_ID']);
    }
?>

<!doctype html>
<html class="no-js" lang="zxx">
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title><?php echo FRONT_SITE_NAME; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?php echo FRONT_SITE_PATH?>assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo FRONT_SITE_PATH?>assets/css/animate.css">
        <link rel="stylesheet" href="<?php echo FRONT_SITE_PATH?>assets/css/owl.carousel.min.css">
        <link rel="stylesheet" href="<?php echo FRONT_SITE_PATH?>assets/css/slick.css">
        <link rel="stylesheet" href="<?php echo FRONT_SITE_PATH?>assets/css/chosen.min.css">
        <link rel="stylesheet" href="<?php echo FRONT_SITE_PATH?>assets/css/ionicons.min.css">
        <link rel="stylesheet" href="<?php echo FRONT_SITE_PATH?>assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo FRONT_SITE_PATH?>assets/css/simple-line-icons.css">
        <link rel="stylesheet" href="<?php echo FRONT_SITE_PATH?>assets/css/jquery-ui.css">
        <link rel="stylesheet" href="<?php echo FRONT_SITE_PATH?>assets/css/meanmenu.min.css">
        <link rel="stylesheet" href="<?php echo FRONT_SITE_PATH?>assets/css/style.css">
        <link rel="stylesheet" href="<?php echo FRONT_SITE_PATH?>assets/css/responsive.css">
        <script src="<?php echo FRONT_SITE_PATH?>assets/js/vendor/modernizr-2.8.3.min.js"></script>
       </head>
    <body>
        <!-- header start -->
        <header class="header-area">
            <div class="header-top black-bg">
                <div class="container">
                    <div class="row">
                        <?php if(isset($_SESSION['FOOD_USER_NAME'])) {?>
                        <div class="col-lg-4 col-md-4 col-12 col-sm-4">
                            <div class="welcome-area">
                                <p> </p>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-12 col-sm-8">
                            <div class="account-curr-lang-wrap f-right">
                                <ul>                                   
                                    <li class="top-hover text-white">WelCome <a href="javascript:void(0)" id="profile_top_name" > <?php echo $_SESSION['FOOD_USER_NAME']; ?>  <i class="ion-chevron-down"></i></a>
                                        <ul>
                                            <li><a href="<?php echo FRONT_SITE_PATH; ?>profile">Profile</a></li>
                                            <li><a href="<?php echo FRONT_SITE_PATH; ?>order_history">Order History</a></li>
                                            <li><a href="<?php echo FRONT_SITE_PATH; ?>logout">Logout</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="header-middle">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-12 col-sm-4">
                            <div class="logo">
                                <a href="<?php echo FRONT_SITE_PATH; ?>shop">
                                    <img alt="" src="assets/img/logo/logo.png">
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8 col-12 col-sm-8">
                            <div class="header-middle-right f-right">
                                <div class="header-login">
                                    <?php if(!isset($_SESSION['FOOD_USER_NAME'])){ ?>
                                        <a href="<?php echo FRONT_SITE_PATH; ?>login_register">
                                            <div class="header-icon-style">
                                                <i class="icon-user icons"></i>
                                            </div>
                                            <div class="login-text-content">
                                                <p>Register <br> or <span>Sign in</span></p>
                                            </div>
                                        </a>
                                    <?php } ?>
                                </div>
                                <div class="header-wishlist">
                                   &nbsp;
                                   <?php if(isset($_SESSION['FOOD_USER_NAME'])){  ?>
                                    <div class="header-icon-style">
                                        <a href="<?php echo FRONT_SITE_PATH; ?>wallet">
                                            <i class="icon-wallet icons " aria-hidden="true"></i>
                                            <span class="font-weight-bold text-success" ><?php echo "₹ ".$getWalletAmt; ?></span>
                                        </a>   
                                   </div>
                                    <?php } ?>
                                </div>
                                <div class="header-cart">
                                    <a href="javascript:void(0)">
                                        <div class="header-icon-style">
                                            <i class="icon-handbag icons"></i>
                                            <span class="count-style" id="totaldishCount"><?php echo $totaldishCount;?></span>
                                        </div>
                                        <div class="cart-text">
                                            <span class="digit">My Cart</span>
                                            <span class="cart-digit-bold" id="dishtotal_price"> 
                                                <?php if($total_price != 0) { echo "₹ ".$total_price;  }  ?>
                                            </span>
                                        </div>
                                    </a>
                                    <?php if($totaldishCount > 0) {?>
                                        <div class="shopping-cart-content">
                                            <ul  id="cart_ul">
                                            <?php foreach($cartArr as $key => $list) {?>
                                                <li class="single-shopping-cart"  id="attr_<?php echo $key; ?>">
                                                    <div class="shopping-cart-img">
                                                        <a href="javascript:void(0)"><img  src="<?php echo SITE_DISH_IMAGE.$list['image']; ?>" width= "100%" alt="Dish Image"></a>
                                                    </div>
                                                    <div class="shopping-cart-title">
                                                        <h4><a href="javascript:void(0)"><?php echo $list['dish']; ?> </a></h4>
                                                            <?php echo $list['attribute']; ?> 
                                                        <h6>Qty: <?php echo $list['qty']; ?> </h6>
                                                        <span><?php echo "₹ ".$list['qty'] * $list['price'] ; ?> </span>
                                                    </div>
                                                    <div class="shopping-cart-delete">
                                                        <a href="javascript:void(0)" onclick="delete_dish_cart('<?php echo $key; ?>')"><i class="ion ion-close"></i></a>
                                                    </div>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                            <div class="shopping-cart-total">
                                                <!-- <h4>Shipping : <span>$20.00</span></h4> -->
                                                <h4>Total : <span class="shop-total" id="shop_total_price"><?php  echo "₹ ".$total_price; ?></span></h4>
                                            </div>
                                            <div class="shopping-cart-btn">
                                                <a href="javascript:void(0)" id="top_view_cart">View Cart</a>
                                                <a href="javascript:void(0)" id="top_checkout">Checkout</a>
                                            </div>
                                        </div> 
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-bottom transparent-bar black-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="main-menu">
                                <nav>
                                    <ul>
                                        <li><a href="<?php echo FRONT_SITE_PATH; ?>shop">Shop</a></li>
                                        <li><a href="<?php echo FRONT_SITE_PATH; ?>about_us">about us</a></li>
                                        <li><a href="<?php echo FRONT_SITE_PATH; ?>contact_us">contact us</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- mobile-menu-area-start -->
			<div class="mobile-menu-area">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<div class="mobile-menu">
								<nav id="mobile-menu-active">
									<ul class="menu-overflow" id="nav">
                                        <li><a href="<?php echo FRONT_SITE_PATH; ?>shop">Shop</a></li>
                                        <li><a href="<?php echo FRONT_SITE_PATH; ?>about_us">about us</a></li>
                                        <li><a href="<?php echo FRONT_SITE_PATH; ?>contact_us">contact us</a></li>
									</ul>
								</nav>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- mobile-menu-area-end -->
        </header>