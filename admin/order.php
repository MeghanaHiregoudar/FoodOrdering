<?php include('top.php'); 

//Query to Fetch
$query = mysqli_query($conn,"SELECT om.*,os.order_status as order_status_str FROM `order_master` AS om INNER JOIN `order_status` AS os on om.order_status = os.id  ORDER BY om.`id` DESC");

?>

<div class="card">
   <div class="card-body">
      <h4>Order Master </h4>
      <div class="row">
         <div class="col-12">
            <div class="table-responsive">
               <table id="order-listing" class="table">
                  <thead>
                     <tr>
                        <th>Order Id</th>
                        <th>Name/Email/Mobile </th>
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
                        <td>
                           <a href="order_detail.php?id=<?php echo $row['id']; ?>" class="order_id_row"><div ><?php echo $row['id']; ?></div></a>
                           
                        </td>
                        <td>
                             <p>N - <?php echo $row['name']; ?></p>
                             <p>E - <?php echo $row['email']; ?></p>
                             <p>M - <?php echo $row['mobile']; ?></p>
                        </td>
                        <td>
                            <p><?php echo $row['address']; ?></p>
                            <p><?php echo $row['zipcode']; ?></p>
                        </td>
                        <td><?php echo $row['payment_type']; ?></td>
                        <td>
                           <?php if($row['coupon_code'] != '') {?>
                              <p><strong> Total :</strong> <?php echo "₹ ".$row['total_price']; ?></p>
                              <p> <strong>Coupon Code : </strong><br><?php echo $row['coupon_code']; ?></p>
                           <?php }?>
                              <p> <strong>Final Price :</strong> <?php echo "₹".$row['final_price']; ?></p>
                        </td>
                        <td>
                            <div class="payment_status payment_status_<?php echo $row['payment_status'];?>"><?php echo ucfirst($row['payment_status']); ?></div> 
                        </td>
                        <td>
                           <?php if($row['order_status_str'] == 'Cancel') 
                                 {  echo "Cancelled By ".ucfirst($row['cancel_by']); } 
                                 else {   echo ucfirst($row['order_status_str']); } 
                           ?>
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
                     <?php }   } else { ?>
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



<?php include('footer.php'); ?>