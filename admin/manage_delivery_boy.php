<?php include('top.php'); 

$msg = "";
$name = "";
$mobile = "";
$password = "";
$id = "";

if(isset($_GET['id']) && $_GET['id'] > 0)
{
    $id = $_GET['id'];
    $edit_data = mysqli_fetch_assoc(mysqli_query($conn,"select * from delivery_boy where id = '$id'"));
    
    $name = $edit_data['name'] ;
    $mobile = $edit_data['mobile'];
    $password = $edit_data['password'];
}

if(isset($_POST['submit']))
{   
    $name = get_safe_value($_POST['name']);
    $mobile = get_safe_value($_POST['mobile']);
    $password = get_safe_value($_POST['password']);
    $added_on = date('Y-m-d h:i:s');
    if($id=='')
    {
        $query = "select * from delivery_boy where mobile = '$mobile'";
    }
    else
    {
        $query = "select * from delivery_boy where mobile = '$mobile' and id != '$id' ";
    }
    
    //Checking for unique
    if(mysqli_num_rows(mysqli_query($conn,$query)) > 0)
    {
       $msg = "Delivery Boy Already Exists";
    }
    else
    {
        if($id == '')
        {
            mysqli_query($conn, "INSERT INTO `delivery_boy`( `name`, `mobile`, `password`, `added_on`) VALUES ('$name','$mobile','$password','$added_on')");
            redirectPage('delivery_boy.php');
        }
        else
        {
            mysqli_query($conn, "UPDATE `delivery_boy` set `name`='$name', `mobile`='$mobile',`password`='$password' where `id` = '$id'");
            redirectPage('delivery_boy.php');
        }
    }
}

?>

<div class="row">
   <h1 class="card-title ml10">Manage Delivery Boy</h1>    
   <div class="col-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <form class="forms-sample" method="POST">

               <div class="form-group">
                  <label for="exampleInputName1">Name</label>
                  <input type="text" class="form-control" name="name" placeholder="Name" value="<?php echo $name; ?>" required>
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail3">Mobile Number</label>
                  <input type="text" class="form-control" name="mobile" placeholder="Mobile Number" value="<?php echo $mobile; ?>" required>
                  <p class="error"><?php echo $msg; ?></p>
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail3">Password</label>
                  <input type="password" class="form-control" name="password" placeholder="Password" value="<?php echo $password; ?>" required>
               </div>
               <button type="submit" name="submit" class="btn btn-primary mr-2">Submit</button>
               <a href="delivery_boy.php" class="btn btn-danger">Back</a>
            </form>
         </div>
      </div>
   </div>
</div>
<?php include('footer.php'); ?>