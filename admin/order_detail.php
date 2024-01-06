<?php 
include('top.php');

if(isset($_GET['id']) && $_GET['id'] > 0)
{
  $order_id = get_safe_value($_GET['id']);

    //Update Order status in order master table
    if(isset($_GET['order_status']))
    {
      $order_status_id = $_GET['order_status'];
      if($order_status_id == 5)
      {
        $cancel_at = date('Y-m-d h:i:s');
        $sql = "UPDATE `order_master` SET order_status = '$order_status_id' , `cancel_by` = 'admin' , `cancel_at` = '$cancel_at' where `id` = '$order_id' ";
      }
      else
      {
        $sql ="update order_master set `order_status` = '$order_status_id' where `id` = '$order_id' ";
      }
      mysqli_query($conn,$sql);

      //when user orders his firdt dish then add money as refered amt
      /*if($order_status_id == 4)
      {
        $getOrderById = getOrderById($order_id);
        $user_id = $getOrderById[0]['user_id'];
        $row = mysqli_fetch_assoc(mysqli_query($conn,"select count(user_id) as total_order from `order_master` where user_id = '$user_id' and `order_status` = '4' "));
        $total_order = $row['total_order'];
        //if user order is delivered for fist time then refered user will get money
        if($total_order == 1)
        {
          $res = mysqli_query($conn,"SELECT `from_referral_code`,`email` FROM `user`  WHERE `id` = '$user_id' ");
          if(mysqli_num_rows($res) > 0)
          {
              $row = mysqli_fetch_assoc($res);
              $email = $row['email'];
              $from_referral_code = $row['from_referral_code'];
              $referal_query = mysqli_fetch_assoc(mysqli_query($conn,"SELECT `id` FROM `user`  WHERE `referral_code` = '$from_referral_code'"));
              $uid = $referal_query['id'];
              $getSetting = getSetting();
              $referal_msg = "Referral Amt from ".$email;
              manageWallet($uid,$getSetting['referal_amt'],'in',$referal_msg);
          }
        }
      }*/
      redirectPage(FRONT_SITE_PATH.'admin/order_detail.php?id='.$order_id);
    }

    if(isset($_GET['delivey_boy_status']))
    {
      $delivey_boy_id = $_GET['delivey_boy_status'];
      mysqli_query($conn,"update order_master set `delivery_boy_id` = '$delivey_boy_id' where `id` = '$order_id'");
      redirectPage(FRONT_SITE_PATH.'admin/order_detail.php?id='.$order_id);
    }
  
    //Query to Fetch
    $query = mysqli_query($conn,"SELECT om.*,os.order_status as order_status_str FROM `order_master` AS om INNER JOIN `order_status` AS os on om.order_status = os.id where om.id ='$order_id' ORDER BY om.`id` DESC");
    if(mysqli_num_rows($query) > 0)
    {
      $row = mysqli_fetch_assoc($query);
      $coupon = mysqli_fetch_assoc(mysqli_query($conn,"select * from coupon_code where `coupon_code` = '".$row['coupon_code']."'"));
      $coupon_amount = 0;
      if($coupon['coupon_type'] == 'F')
      {
        $coupon_amount = $coupon['coupon_value'];
      } else {
        $coupon_amount = ($coupon['coupon_value'] * $row['total_price'] / 100 );
      }
     
      $getOrderDetailById = getOrderDetailById($order_id);
    }
    else
    {
      redirectPage('order.php');
    }

} 
else 
{
  redirectPage('order.php');
}

?>
  <div class="page-header">
    <h3 class="page-title"> Order Details </h3>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="card px-2">
        <div class="card-body">
          <div class="container-fluid">
            <h3 class="text-right my-5">Order Id&nbsp;&nbsp;# <?php echo $row['id']; ?> </h3>
            <hr>
          </div>
        <div class="container-fluid d-flex justify-content-between">
          <div class="col-lg-3 pl-0">
            <p class="mt-5 mb-2"><b>Plus Admin</b></p>
            <p>104,<br>Sai Temple,<br>Vijayapur, K1A 0G9.</p>
        </div>
        <div class="col-lg-3 pr-0">
          <p class="mt-5 mb-2 text-right"><b>Invoice to</b></p>
          <p class="text-right"> <?php echo $row['address']; ?> <br> Vijayapur, <?php echo $row['zipcode']; ?>.</p>
        </div>
      </div>
      <div class="container-fluid d-flex justify-content-between">
        <div class="col-lg-3 pl-0">
          <p class="mb-0 mt-5">Order Date : <?php echo date('jS F, Y',strtotime($row['added_on'])); ?></p>
          <!-- <p>Due Date : 25th Jan 2017</p> -->
        </div>
      </div>
      <div class="container-fluid mt-5 d-flex justify-content-center w-100">
        <div class="table-responsive w-100">
          <table class="table">
            <thead>
              <tr class="bg-dark">
                <th>#</th>
                <th>Description</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Unit cost</th>
                <th class="text-right">Total</th>
              </tr>
            </thead>
            <tbody>
              <?php  $i = 1; $total_price = 0;
                foreach($getOrderDetailById as $list) {
                $total_price = $total_price+($list['qty'] * $list['price']); ?>
                <tr  class="text-right">
                  <td class="text-left"> <?php echo $i; ?></td>
                  <td class="text-left"><?php echo ucfirst($list['dish']) . " (".$list['attribute'].") "; ?></td>
                  <td><?php echo $list['qty']; ?></td>
                  <td><?php echo "₹  ".$list['price']; ?></td>
                  <td><?php echo "₹  ".$list['qty'] * $list['price']; ?></td>
                </tr>
              <?php $i++;  } ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="container-fluid mt-5 w-100">
        <p class="text-right mb-2">Sub Total : <?php echo "₹ ".$total_price; ?> </p>
        <p class="text-right">Coupon : <?php echo "<strong>-</strong> ₹ ".$coupon_amount; ?> </p>
        <h5 class="text-right mb-5">Total : <?php echo "₹ ".$row['final_price'].".00"; ?></h5>
        <hr>
      </div>
      <div class="container-fluid w-100">
      
        <a href="<?php echo FRONT_SITE_PATH?>download_invoice.php?id=<?php echo $order_id;?>" target="_blank" class="btn btn-primary float-right mt-4 ml-2"><i class="mdi mdi-file-pdf mr-1"></i>PDF</a>
        <!-- <a href="#" class="btn btn-success float-right mt-4"><i class="mdi mdi-telegram mr-1"></i>Send Invoice</a> -->
      </div>
      <?php 
        $order_status_res = mysqli_query($conn,"SELECT * FROM `order_status` ORDER BY `order_status`");

        $delivey_boy_res = mysqli_query($conn,"SELECT * FROM `delivery_boy` WHERE `status` = '1' ORDER BY `name`");

        //to get delivery boy details
        $getDeliveryBoyById = getDeliveryBoyById($row['delivery_boy_id']); 
        if($getDeliveryBoyById != "") {
          $delivery_boy_details = $getDeliveryBoyById['name']. "  (Mobile : ".$getDeliveryBoyById['mobile'].")";
        } else {
          $delivery_boy_details = " Not Yet Assigned";
        }      

      ?>      
      <div>
        <h5>Order Status:- <?php echo $row['order_status_str']; ?></h5>
        <select name="order_status" class="form-control wSelect200" id="order_status" onchange="update_order_status()">
          <option value=""> Select Order Status</option>
          <?php while($row = mysqli_fetch_assoc($order_status_res)) {?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['order_status']; ?></option>
          <?php } ?>
        </select>  
        <br>
        <h5>Delivery Boy:- <?php echo $delivery_boy_details; ?> </h5>
        <select name="delivey_boy_status" class="form-control wSelect200" id="delivey_boy_status" onchange="update_delivery_boy()">
          <option value=""> Select Delivery Boy</option>
          <?php while($row = mysqli_fetch_assoc($delivey_boy_res)) {?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
          <?php } ?>
        </select>  
        
      </div>
    </div>
  </div>
</div>     

<script>
  function update_order_status()
  {
      var order_status= jQuery("#order_status").val();
      if(order_status != '')
      {
        var order_id = "<?php echo $order_id; ?>";
        window.location.href = '<?php echo FRONT_SITE_PATH?>admin/order_detail.php?id='+order_id+'&order_status='+order_status;
      }
      
  }

  function update_delivery_boy()
  {
    var delivey_boy_id= jQuery("#delivey_boy_status").val();
    
      if(delivey_boy_id != '')
      {
        var order_id = "<?php echo $order_id; ?>";
        window.location.href = '<?php echo FRONT_SITE_PATH?>admin/order_detail.php?id='+order_id+'&delivey_boy_status='+delivey_boy_id;
      }
  }
</script>
<?php include('footer.php');?>