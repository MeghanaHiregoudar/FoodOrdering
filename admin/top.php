<?php
session_start();
include('../db_con.php');
include('../function.inc.php');
include('../constant_inc.php');

$pathStr = $_SERVER['REQUEST_URI'];

$pathArr = explode('/',$pathStr);

$current_path = $pathArr[count($pathArr) - 1];

if(!isset($_SESSION['IS_LOGIN']))
{
    redirectPage('login.php');
}

$pageTitle = '';
if($current_path == '' || $current_path == 'index.php' )
{
  $pageTitle = "Dashboard";
}
elseif($current_path == 'category.php' || $current_path == 'manage_category.php' || $current_path == 'manage_category.php?id=')
{
  $pageTitle='Category';
}
elseif($current_path == 'coupon_code.php' || $current_path == 'manage_coupon_code.php')
{
  $pageTitle='Coupon Code';
}
elseif($current_path == 'delivery_boy.php' || $current_path == 'manage_delivery_boy.php')
{
  $pageTitle='Delivery Boy';
}
elseif($current_path == 'dish.php'|| $current_path == 'manage_dish.php')
{
  $pageTitle='Dish';
}
elseif($current_path == 'user.php')
{
  $pageTitle='Users';
}
elseif($current_path == 'banner.php' || $current_path == 'manage_banner.php')
{
  $pageTitle='Banner';
}elseif($current_path == 'contactUs.php')
{
  $pageTitle='Contact Us';
}elseif($current_path == 'order.php')
{
  $pageTitle='Orders';
}
elseif($current_path == 'setting.php')
{
  $pageTitle='Setting';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?php echo $pageTitle.'-'.SITE_NAME; ?></title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="assets/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="assets/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="assets/css/bootstrap-datepicker.min.css">
  <!-- End plugin css for this page -->
  
  <!-- inject:css -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="sidebar-light">
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="navbar-menu-wrapper d-flex align-items-stretch justify-content-between">
        <ul class="navbar-nav mr-lg-2 d-none d-lg-flex">
          <li class="nav-item nav-toggler-item">
            <button class="navbar-toggler align-self-center" type="button" data-toggle="minimize">
              <span class="mdi mdi-menu"></span>
            </button>
          </li>
          
        </ul>
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" href="index.php"><img src="assets/images/logo.png" alt="logo"/></a>
          <a class="navbar-brand brand-logo-mini" href="index.php"><img src="assets/images/logo.png" alt="logo"/></a>
        </div>
        <ul class="navbar-nav navbar-nav-right">
          
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <span class="nav-profile-name"><?php echo $_SESSION['ADMIN_NAME']; ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <div class="dropdown-divider"></div>
              <a href="logout.php" class="dropdown-item">
                <i class="mdi mdi-logout text-primary"></i>
                Logout
              </a>
            </div>
          </li>
          
          <li class="nav-item nav-toggler-item-right d-lg-none">
            <button class="navbar-toggler align-self-center" type="button" data-toggle="offcanvas">
              <span class="mdi mdi-menu"></span>
            </button>
          </li>
        </ul>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="index.php">
              <i class="mdi mdi-view-quilt menu-icon"></i>
              <span class="menu-title">Dashboard </span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="order.php">
              <i class="mdi mdi-view-headline menu-icon"></i>
              <span class="menu-title">Order</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="category.php">
              <i class="mdi mdi-view-headline menu-icon"></i>
              <span class="menu-title">Category</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="user.php">
              <i class="mdi mdi-account menu-icon"></i>
              <span class="menu-title">Users</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="delivery_boy.php">
              <i class="mdi mdi-view-headline menu-icon"></i>
              <span class="menu-title">Delivery Boy</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="coupon_code.php">
              <i class="mdi mdi-view-headline menu-icon"></i>
              <span class="menu-title">Coupon Code</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="dish.php">
              <i class="mdi mdi-view-headline menu-icon"></i>
              <span class="menu-title">Dish</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contactUs.php">
              <i class="mdi mdi-view-headline menu-icon"></i>
              <span class="menu-title">Contact Us</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="banner.php">
              <i class="mdi mdi-view-headline menu-icon"></i>
              <span class="menu-title">Banner</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="setting.php">
              <i class="mdi mdi-view-headline menu-icon"></i>
              <span class="menu-title">Setting</span>
            </a>
          </li>
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">

        