<?php 
    session_start();
    include('../db_con.php');
    include('../function.inc.php');
    $msg = "";
    if(isset($_POST['submit']))
    {
      $mobile = get_safe_value($_POST['mobile']);
      $password = get_safe_value($_POST['password']);
      $query = mysqli_query($conn,"select * from delivery_boy where mobile = '$mobile' and password = '$password'");
      if(mysqli_num_rows($query) > 0)
      {
        $row = mysqli_fetch_assoc($query);
        $_SESSION['IS_DELIVERY_BOY'] = 'yes';
        $_SESSION['DELIVERY_BOY_NAME'] = $row['name'];
        $_SESSION['DELIVERY_BOY_ID'] = $row['id'];
        redirectPage('index.php');
      }
      else{
        $msg = "Please Enter Valid Credentials";
      }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Food Ordering</title>
  <!-- inject:css -->
  <link rel="stylesheet" href="../admin/assets/css/style.css">
</head>
<body class="sidebar-light">
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
            <div class="text-danger font-weight-bold mb-3"> <?php echo $msg; ?></div>
              <div class="brand-logo text-center">
                <img src="../admin/assets/images/logo.png" alt="logo">
              </div>
              <h6 class="font-weight-light">Sign in to continue.</h6>
              <form class="pt-3" method="POST">
                <div class="form-group">
                  <input type="text" name="mobile" class="form-control form-control-lg" id="mobile" placeholder="Phone Number" required>
                </div>
                <div class="form-group">
                  <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="Password" required>
                </div>
                <div class="mt-3">
                  <input type="submit" name="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" value="SIGN IN">    
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
</body>
</html>