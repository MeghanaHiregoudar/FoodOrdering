<?php include('header.php'); 
if(!isset($_SESSION['FOOD_USER_ID']))
{
    redirectPage(FRONT_SITE_PATH."shop");
}

if(isset($_GET['id']) && $_GET['id'] > 0)
{
    $order_id = get_safe_value($_GET['id']);
    $getOrderById = getOrderById($order_id);
    if($getOrderById[0]['user_id'] != $_SESSION['FOOD_USER_ID'] )
    {
        redirectPage(FRONT_SITE_PATH."shop");
    }
}
else {
    redirectPage(FRONT_SITE_PATH."shop");
}

?>

<div class="cart-main-area pt-95 pb-100">
    <div class="container">
        <h3 class="page-title">Order Details</h3>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="table-content table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th width="10%">Dish</th>
                                <th width="10%">Attribute</th>
                                <th width="10%">Qty</th>
                                <th width="10%">Price</th>
                                <th width="10%">Total Price</th>
                                <th width="10%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $getOrderDetailById = getOrderDetailById($order_id);
                                $final_price = 0;
                                
                                foreach($getOrderDetailById as $list) {
                                $final_price = $final_price+($list['qty']*$list['price']);?>
                                <tr>
                                    <td><?php echo $list['dish']; ?></td>
                                    <td><?php echo $list['attribute']; ?></td>
                                    <td><?php echo $list['qty']; ?></td>
                                    <td><?php echo "₹ ".$list['price']; ?></td>
                                    <td><?php echo "₹ ".$list['qty']*$list['price']; ?></td>
                                    <!-- this td is for rating -->
                                    <td id="rating<?php echo $list['dish_details_id']?>">
                                        <?php echo getorsetRating($list['dish_details_id'],$order_id); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td > <strong>Total</strong></td>
                                <td><strong><?php echo $final_price; ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include("footer.php");?>



