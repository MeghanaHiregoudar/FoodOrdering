<?php include('header.php'); 
if(!isset($_SESSION['FOOD_USER_ID']))
{
    redirectPage(FRONT_SITE_PATH."shop");
}
$uid= $_SESSION['FOOD_USER_ID'];

//to cancel order from frontend by user who ordered
if(isset($_GET['cancel_oid']) && $_GET['cancel_oid'] > 0)
{
    $cancel_oid = get_safe_value($_GET['cancel_oid']);
    $cancel_at = date('Y-m-d h:i:s');
    mysqli_query($conn,"UPDATE `order_master` SET order_status = '5' , `cancel_by` = 'user' , `cancel_at` = '$cancel_at'  where `id` = '$cancel_oid' and `order_status` = '1' and `user_id` = '$uid' ");
    redirectPage(FRONT_SITE_PATH.'order_history');
}

$query = mysqli_query($conn,"SELECT om.*,os.order_status as order_status_str FROM `order_master` AS om INNER JOIN `order_status` AS os on om.order_status = os.id where om.`user_id` = '$uid' ORDER BY om.`id` DESC");

?>

<div class="cart-main-area pt-95 pb-100">
    <div class="container">
        <h3 class="page-title">Order History</h3>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="table-content table-responsive">
					<table>
                        <thead>
                            <tr>
                                <th >Order No</th>
                                <th >Address</th>
                                <th >Price</th>
                                <th> Coupon</th>
                                <th >Order Status</th>
                                <th >Payment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($query) > 0) { 
                                while($row = mysqli_fetch_assoc($query)) {  ?>
                                <tr>
                                    <td>
                                        <a href="order_detail.php?id=<?php echo $row['id']; ?>" class="order_id_row"><div ><?php echo $row['id']; ?></div></a> 
                                        <a href="<?php echo FRONT_SITE_PATH?>download_invoice?id=<?php echo $row['id']; ?>" target="_blank" title="Download Invoice"> <img src="<?php echo FRONT_SITE_PATH?>assets/img/icon-img/pdf_icon.png" alt="PDF Download" width="30px" ></a>
                                    </td>
                                    <td>
                                        <p><?php echo $row['address']; ?><br>
                                        <?php echo $row['zipcode']; ?></p>
                                    </td>
                                    <td><?php echo "₹ ".$row['total_price']; ?></td>
                                    <td>
                                        <?php if($row['coupon_code'] != '') {
                                            //to get Coupon code value
                                            $coupon_val = mysqli_fetch_assoc(mysqli_query($conn,"select * from `coupon_code` where `coupon_code` = '".$row['coupon_code']."'"));
                                            $coupon_amount = 0;
                                            if($coupon_val['coupon_type'] == 'F')
                                            {
                                                $coupon_amount = $coupon_val['coupon_value'];
                                            } else {
                                                $coupon_amount = ($coupon_val['coupon_value'] * $row['total_price'] / 100 );
                                            }  ?>
                                        <p> <strong>Coupon : </strong><?php echo "₹ ".$coupon_amount; ?></p>
                                        <?php }?>
                                        <p> <strong>Final Price :</strong> <?php echo "₹ ".$row['final_price']; ?></p>
                                    </td>
                                    
                                    <td>
                                        <?php if($row['order_status_str'] == 'Cancel') {
                                                echo "Cancelled"; } else {
                                                echo $row['order_status_str']; }
                                        ?>
                                        
                                        <!-- this id cancel the order -->
                                        <?php if($row['order_status'] == 1) {?>
                                        <div class="checkout-login-btn" >
                                            <a href="?cancel_oid=<?php echo $row['id'];?>">Cancel</a>
                                       </div> 
                                       <?php } ?>  
                                    </td>
                                    <td>
                                        <div class="payment_status payment_status_<?php echo $row['payment_status'];?>"><?php echo ucfirst($row['payment_status']); ?></div>
                                    </td>
                                </tr>
                            <?php } } else {?>
                            <tr>
                                <td colspan="6"> <h3> No Orders So far</h3> </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
include("footer.php");
?>



