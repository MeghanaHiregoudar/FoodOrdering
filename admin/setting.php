<?php include('top.php'); 

if(isset($_POST['submit']))
{   
    $cart_min_price = get_safe_value($_POST['cart_min_price']);
    $cart_min_price_msg = get_safe_value($_POST['cart_min_price_msg']);
    $website_close = get_safe_value($_POST['website_close']);
    $website_close_msg = get_safe_value($_POST['website_close_msg']);
    $wallet_amt = get_safe_value($_POST['wallet_amt']);
    $referal_amt = get_safe_value($_POST['referal_amt']);
    mysqli_query($conn, "UPDATE  `setting` SET `cart_min_price`='$cart_min_price', `cart_min_price_msg`= '$cart_min_price_msg', `wallet_amt` = '$wallet_amt', `website_close`='$website_close', `website_close_msg` ='$website_close_msg' , `referal_amt` = '$referal_amt' where `id` = '1'"); 
}

    $data = mysqli_fetch_assoc(mysqli_query($conn,"select * from setting where id = '1'"));
    $cart_min_price = $data['cart_min_price'];
    $cart_min_price_msg = $data['cart_min_price_msg'];
    $website_close = $data['website_close'];
    $website_close_msg = $data['website_close_msg'];
    $wallet_amt =  $data['wallet_amt'];
    $referal_amt =  $data['referal_amt'];

    $websiteCloseArr=array('No','Yes');
?>

<div class="row">
   <h1 class="card-title ml10">Setting</h1>    
   <div class="col-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <form class="forms-sample" method="POST">

               <div class="form-group">
                  <label for="cart_min_price">Cart Min Price</label>
                  <input type="text" class="form-control" name="cart_min_price" placeholder="Cart Min Price" value="<?php echo $cart_min_price; ?>" required>                  
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail3">Cart Min Price Msg</label>
                  <input type="text" class="form-control" name="cart_min_price_msg" placeholder="Cart Min Price Msg" value="<?php echo $cart_min_price_msg; ?>"  required>
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail3">Wallet Amount</label>
                  <input type="text" class="form-control" name="wallet_amt" placeholder="Wallet Amount" value="<?php echo $wallet_amt; ?>"  required>
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail3">Referal Amount</label>
                  <input type="text" class="form-control" name="referal_amt" placeholder="Referal Amount" value="<?php echo $referal_amt; ?>"  required>
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail3">Website Close</label>
                  <select name="website_close" class="form-control">
                        <option value="">Select option</option>
                        <?php foreach($websiteCloseArr as $key => $val) {
                            if($website_close == $key){?>
                                <option value="<?php echo $key ?>" selected><?php echo $val; ?></option>
                            <?php } else {?>
                                <option value="<?php echo $key ?>" ><?php echo $val; ?></option>    
                        <?php } }?>
                  </select>
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail3">Website Close Msg</label>
                  <input type="text" class="form-control" name="website_close_msg" placeholder="Website Close Msg" value="<?php echo $website_close_msg; ?>" required>
               </div>
               <button type="submit" name="submit" class="btn btn-primary mr-2">Submit</button>
            </form>
         </div>
      </div>
   </div>
</div>
<?php include('footer.php'); ?>