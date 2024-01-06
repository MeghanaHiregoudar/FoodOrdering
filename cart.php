<?php include('header.php'); 

$cartArr = getUserDetailCart();
?>
<div class="website_close_message mt-3 mb-0">
   <?php if($website_close == 1) {
      echo "<h3 class='text-center'>$website_close_msg</h3>";
   } ?>
</div>
<div class="cart-main-area pt-70 pb-100">
   <div class="container">
      <h3 class="page-title">Your cart items</h3>
      <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <?php if(count($cartArr) > 0 ) {?>
            <form method="POST">
               <div class="table-content table-responsive">
                  <table>
                     <thead>
                        <tr>
                           <th>Image</th>
                           <th>Product Name</th>
                           <th>Until Price</th>
                           <th>Qty</th>
                           <th>Subtotal</th>
                           <th>action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach($cartArr as $key=>$list) {?>
                           <tr id="table_dish_attr_<?php echo $key; ?>">
                              <td class="product-thumbnail">
                                 <a href="#"><img src="<?php echo SITE_DISH_IMAGE.$list['image']; ?>" width= "70%" alt="Dish Image"></a>
                              </td>
                              <td class="product-name"><a href="#"> <?php echo $list['dish']."<br>(".$list['attribute'].")" ; ?> </a></td>
                              <td class="product-price-cart"><span class="amount"><?php echo "₹ ".$list['price']; ?></span></td>
                              <td class="product-quantity">
                                 <div class="cart-plus-minus">
                                    <input class="cart-plus-minus-box" type="text" name="qty[<?php echo $key; ?>][]" value="<?php echo $list['qty']; ?>">
                                 </div>
                              </td>
                              <td class="product-subtotal"><?php echo "₹ ".$list['qty']*$list['price']; ?></td>
                              <td class="product-remove">
                                 <a href="#" onclick="delete_dish_cart('<?php echo $key; ?>','load')"><i class="fa fa-times"></i></a>
                              </td>
                           </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
               <div class="row">
                  <div class="col-lg-12">
                     <div class="cart-shiping-update-wrapper">
                        <div class="cart-shiping-update">
                           <a href="<?php echo FRONT_SITE_PATH?>shop">Continue Shopping</a>
                        </div>
                        <div class="cart-clear">
                           <button name="update_cart_qty">Update Shopping Cart</button>
                           <a href="<?php echo FRONT_SITE_PATH?>checkout">CkeckOut</a>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
            <?php } else { echo "<h3>No Items in cart</h3>"; } ?>
         </div>
      </div>
   </div>
</div>

<?php include('footer.php'); ?>