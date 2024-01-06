<?php include('top.php'); 
$id = "";
$error ='';

if(isset($_POST['submit']))
{   
    $money = get_safe_value($_POST['money']);
    $user_id = get_safe_value($_GET['id']);
    $msg = get_safe_value($_POST['msg']);
    if($money > 0)
    {
        manageWallet($user_id,$money,'in',$msg);
        redirectPage('user.php');
    }
    else
    {
        $error = "Please Enter Valid Amount";
    }
    
}

?>

<div class="row">
   <h1 class="card-title ml10">Manage Money</h1>    
   <div class="col-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <form class="forms-sample" method="POST">

               <div class="form-group">
                  <label for="exampleInputName1">Money</label>
                  <input type="text" class="form-control" name="money" placeholder="Add Money"  required>
                  <h5 class="error"><?php echo $error; ?></h5>
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail3">Reason</label>
                  <input type="text" class="form-control" name="msg" placeholder="Reason To Send Money" required>
               </div>
               <button type="submit" name="submit" class="btn btn-primary mr-2">Submit</button>
               <a href="user.php" class="btn btn-danger">Back</a>
            </form>
         </div>
      </div>
   </div>
</div>
<?php include('footer.php'); ?>