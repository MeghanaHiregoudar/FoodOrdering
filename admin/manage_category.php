<?php include('top.php'); 

$msg = "";
$category = "";
$order_number = "";
$id = "";

if(isset($_GET['id']) && $_GET['id'] > 0)
{
    $id = $_GET['id'];
    $edit_data = mysqli_fetch_assoc(mysqli_query($conn,"select * from category where id = '$id'"));
    
    $category = $edit_data['category'] ;
    $order_number = $edit_data['order_number'];
}

if(isset($_POST['submit']))
{   
    $category = get_safe_value($_POST['category']);
    $order_number = get_safe_value($_POST['order_number']);
    $added_on = date('Y-m-d h:i:s');
    if($id=='')
    {
        $query = "select * from category where category = '$category'";
    }
    else
    {
        $query = "select * from category where category = '$category' and id != '$id' ";
    }
    
    //Checking for unique
    if(mysqli_num_rows(mysqli_query($conn,$query)) > 0)
    {
       $msg = "Category Already Exists";
    }
    else
    {
        if($id == '')
        {
            mysqli_query($conn, "INSERT INTO `category`( `category`, `order_number`, `status`, `added_on`) VALUES ('$category','$order_number',1,'$added_on')");
            redirectPage('category.php');
        }
        else
        {
            mysqli_query($conn, "UPDATE `category` set `category`='$category', `order_number`='$order_number' where `id` = '$id'");
            redirectPage('category.php');
        }
    }
}

?>

<div class="row">
   <h1 class="card-title ml10">Manage Category</h1>    
   <div class="col-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <form class="forms-sample" method="POST">

               <div class="form-group">
                  <label for="exampleInputName1">Category</label>
                  <input type="text" class="form-control" name="category" placeholder="Category" value="<?php echo $category; ?>" required>
                  <h5 class="error"><?php echo $msg; ?></h5>
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail3">Order Number</label>
                  <input type="text" class="form-control" name="order_number" placeholder="Order Number" value="<?php echo $order_number; ?>" required>
               </div>
               <button type="submit" name="submit" class="btn btn-primary mr-2">Submit</button>
               <a href="category.php" class="btn btn-danger">Back</a>
            </form>
         </div>
      </div>
   </div>
</div>
<?php include('footer.php'); ?>