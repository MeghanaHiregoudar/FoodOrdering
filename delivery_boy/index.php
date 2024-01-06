<?php
session_start();
include('../db_con.php');
include('../function.inc.php');
include('../constant_inc.php');

if(!isset($_SESSION['IS_DELIVERY_BOY']))
{
    redirectPage('login.php');
}


if(isset($_GET['set_order_status']) && $_GET['set_order_status']>0)
{
    $order_id = get_safe_value($_GET['set_order_status']);
    $added_on = date('Y-m-d h:i:s');
    mysqli_query($conn,"UPDATE `order_master` SET `order_status` = '4' , `delivered_time` = '$added_on' where `id` = '$order_id' AND `delivery_boy_id` = '".$_SESSION['DELIVERY_BOY_ID']."' ");
    ?>
    <script> alert("Updated as Delivered"); </script>
    <?php redirectPage('index.php');
}

$query = mysqli_query($conn,"SELECT * FROM `order_master` where order_status != '4' and `delivery_boy_id` = '".$_SESSION['DELIVERY_BOY_ID']."'  ORDER BY `id` DESC");
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title><?php echo SITE_NAME; ?></title>
      <!-- plugins:css -->
      <link rel="stylesheet" href="../admin/assets/css/materialdesignicons.min.css">
      <link rel="stylesheet" href="../admin/assets/css/dataTables.bootstrap4.css">
      <!-- endinject -->
      <!-- Plugin css for this page -->
      <link rel="stylesheet" href="../admin/assets/css/bootstrap-datepicker.min.css">
      <!-- End plugin css for this page -->
      <!-- inject:css -->
      <link rel="stylesheet" href="../admin/assets/css/style.css">
   </head>
   <body class="sidebar-light">
      <div class="container-scroller">
         <!-- partial:partials/_navbar.html -->
         <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="navbar-menu-wrapper d-flex align-items-stretch justify-content-between">
               <ul class="navbar-nav mr-lg-2 d-none d-lg-flex">
               </ul>
               <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                  <a class="navbar-brand brand-logo" href="index.php"><img src="../admin/assets/images/logo.png" alt="logo"/></a>
                  <a class="navbar-brand brand-logo-mini" href="index.php"><img src="../admin/assets/images/logo.png" alt="logo"/></a>
               </div>
               <ul class="navbar-nav navbar-nav-right">
                  <li class="nav-item nav-profile dropdown">
                     <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                     <span class="nav-profile-name"><?php echo $_SESSION['DELIVERY_BOY_NAME']; ?></span>
                     </a>
                     <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                        <div class="dropdown-divider"></div>
                        <a href="logout.php" class="dropdown-item">
                        <i class="mdi mdi-logout text-primary"></i>
                        Logout
                        </a>
                     </div>
                  </li>
               </ul>
            </div>
         </nav>
         <!-- partial -->
         <div class="container-fluid page-body-wrapper">
            <div class="main-panel" style="width:100%;">
               <div class="content-wrapper">
               <div class="card">
                        <div class="card-body">
                            <h4>Order Master </h4>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                    <table id="order-listing" class="table">
                                        <thead>
                                            <tr>
                                                <th>SL No</th>
                                                <th>Name/Mobile </th>
                                                <th>Address /ZipCode</th>
                                                <th>Payment Type</th>
                                                <th>Total Price</th>
                                                <th>Payment Status</th>
                                                <th>Order Status</th>
                                                <th>Added On</th>                       
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(mysqli_num_rows($query) > 0) { 
                                                $i = 1;
                                                while($row = mysqli_fetch_assoc($query))
                                                { ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td>
                                                    <p>N - <?php echo $row['name']; ?></p>
                                                    <p>M - <?php echo $row['mobile']; ?></p>
                                                </td>
                                                <td>
                                                    <p><?php echo $row['address']; ?></p>
                                                    <p><?php echo $row['zipcode']; ?></p>
                                                </td>
                                                <td> <?php echo $row['payment_type']; ?> </td>
                                                <td> <?php echo "₹".$row['final_price']; ?> </td>
                                                <td>
                                                    <div class="payment_status payment_status_<?php echo $row['payment_status'];?>"><?php echo ucfirst($row['payment_status']); ?></div> 
                                                </td>
                                                <td>
                                                    <a href="?set_order_status=<?php echo $row['id']; ?>">Update Delivered</a>
                                                </td>
                                                <td>
                                                <?php
                                                    //Code to show custome date format
                                                    $dateStr = strtotime($row['added_on']);
                                                    echo date('Y-m-d H:s',$dateStr); ?>
                                                </td>
                                                <td>
                                                
                                                </td>
                                            </tr>
                                            <?php $i++; }   } else { ?>
                                            <tr>
                                                <td colspan="5">No Data Found</td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
               </div>
               <!-- content-wrapper ends -->
               <!-- partial:partials/_footer.html -->
               <footer class="footer">
                  <div class="d-sm-flex justify-content-center justify-content-sm-between">
                     <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2018 <a href="https://www.urbanui.com/" target="_blank">Urbanui</a>. All rights reserved.</span>
                  </div>
               </footer>
               <!-- partial -->
            </div>
            <!-- main-panel ends -->
         </div>
         <!-- page-body-wrapper ends -->
      </div>
      <!-- container-scroller -->
      <!-- plugins:js -->
      <script src="../admin/assets/js/vendor.bundle.base.js"></script>
      <script src="../admin/assets/js/jquery.dataTables.js"></script>
      <script src="../admin/assets/js/dataTables.bootstrap4.js"></script>
      <!-- endinject -->
      <!-- Custom js for this page-->
      <script src="../admin/assets/js/data-table.js"></script>
      <!-- End custom js for this page-->
   </body>
</html>