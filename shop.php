<?php include('header.php'); 

//checking weather website is close or open
$readOnly_opt = '';
if($website_close == 1)
{
   $readOnly_opt = "disabled='disabled'";
}


$category_dish='';
$type_dish = '';
$search_str = '';
$catDish_arr =array();
//Filter based on category checkbox
if(isset($_GET['category_dish']) && $_GET['category_dish'] != '')
{
   $category_dish = get_safe_value($_GET['category_dish']);
   //breaks string to array and remove empty/null array
   $catDish_arr = array_filter(explode(':',$category_dish));
   $cartegory_dish_id = implode(',',$catDish_arr);  
}

//Filter based on Dish type radio
if(isset($_GET['type_dish']) && $_GET['type_dish'] != '' )
{
   $type_dish = get_safe_value($_GET['type_dish']);
}

//Filter based on user searched text
if(isset($_GET['search_str']) && $_GET['search_str'] != '' )
{
   $search_str = get_safe_value($_GET['search_str']);
}

$dish_typeArr = array("veg","non-veg","egg","both");

?>

<div class="breadcrumb-area gray-bg">
   <div class="container">
      <div class="breadcrumb-content">
         <ul>
            <li><a href="<?php echo FRONT_SITE_PATH; ?>shop">Home</a></li>
            <li class="active">Shop Grid Style </li>
         </ul>
      </div>
   </div>
</div>
<div class="website_close_message mt-3 mb-0">
   <?php if($website_close == 1) {
      echo "<h3 class='text-center'>$website_close_msg</h3>";
   } ?>
</div>
<div class="shop-page-area pt-80 pb-100">
   <div class="container">
      <div class="row flex-row-reverse">
         <div class="col-lg-9">
            <div class="shop-topbar-wrapper">
               <div class="row">
                  <div class="col-lg-12 col-md-12 col-10" style="width: max-content;">
                     <div class="product-show sorting-style search_box">
                        <div class="input-group"  >
                           <input type="text" class="form-control" id="search_data" placeholder="Search" value="<?php echo $search_str; ?>" >
                           <div class="input-group-append billing-btn">
                              <button type="button" id="search_btn" onclick="setSearch()">Search</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="product-sorting-wrapper pt-3">
                  <div class="product-show sorting-style">
                     <?php foreach($dish_typeArr as $type_list) {
                           $radio_selected = '';
                           if($type_list == $type_dish)
                           { $radio_selected = "checked='ckecked'"; }?>
                        <input type="radio" class="dish_radio" name="dish_type" <?php echo $radio_selected; ?> value="<?php echo $type_list; ?>" onclick="setFoodtype('<?php echo $type_list; ?>')"><?php echo strtoupper($type_list); ?> &nbsp;&nbsp;
                     <?php } ?>
                  </div>
               </div>
            </div>
            <?php  
               $cat_id = 0;
               $dish_query = "SELECT * FROM `dish` WHERE `status` = 1 ";
               //To filter based on category
               if($category_dish != '' )
               {
                  $dish_query .= " AND `category_id` in ($cartegory_dish_id) ";
               }
               if($type_dish != '' && $type_dish != "both")
               {
                  $dish_query .= " AND `dish_type` = '$type_dish' ";
               }
               if($search_str != '')
               {
                  $dish_query .= " AND `dish` like '%$search_str%' AND `dish_detail` like '%$search_str%' ";
               }
               $dish_query .= " order by `dish` desc";
               $dish_res = mysqli_query($conn,$dish_query); ?>
            <div class="grid-list-product-wrapper">
               <div class="product-grid product-view pb-20">
                  <?php if(mysqli_num_rows($dish_res) > 0) { ?>
                  <div class="row">
                     <?php while($dish_row = mysqli_fetch_assoc($dish_res)) { ?>
                     <div class="product-width col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12 mb-30">
                        <div class="product-wrapper">
                           <div class="product-img">
                              <a href="javascript:void(0)">
                                 <img src="<?php echo SITE_DISH_IMAGE.$dish_row['image']; ?>" alt="Dish Image" >
                              </a>
                           </div>
                           <div class="product-content" id="dish_detail">
                              <h4>
                              <?php if($dish_row['dish_type'] == "non-veg" || $dish_row['dish_type'] == "egg") { ?>
                                 <img src="<?php echo FRONT_SITE_PATH?>assets/img/icon-img/non-veg.png" alt="non-veg">
                              <?php } else {?> 
                                 <img src="<?php echo FRONT_SITE_PATH?>assets/img/icon-img/veg.png" alt="veg">
                              <?php } ?>&nbsp;
                                 <a href="javascript:void(0)"><?php echo $dish_row['dish']; ?> </a>
                              <select name="quantity" id="quantity<?php echo $dish_row['id']; ?>" class="qty">
                                 <option value="" >Qty</option>
                                 <?php  for($i=1 ; $i<=10; $i++) {?>
                                    <option value="<?php echo $i; ?>" <?php echo $readOnly_opt;?> ><?php echo $i; ?></option>
                                 <?php } ?>                                
                              </select>
                              <i class="fa fa-shopping-cart cart_icon" aria-hidden="true" onclick="add_to_cart('<?php echo $dish_row['id']; ?>','add')"></i>
                              </h4> 
                               <?php 
                                 //Average Calculation of rating and displaying
                                 getRatingByDishId($dish_row['id']); 
                               ?>
                              <?php $dishAttr_res = mysqli_query($conn,"SELECT * FROM `dish_details` WHERE `status` = 1 and `dish_id` = '".$dish_row["id"]."' ORDER BY `price` ");
                               while($dishAttr_row = mysqli_fetch_assoc($dishAttr_res)) {?>
                              <div class="product-price-wrapper">
                                 <input type="radio" class="dish_radio radio-inline" id="radio_<?php echo $dish_row['id'] ?>" name="radio_<?php echo $dish_row['id'] ?>" value="<?php echo $dishAttr_row['id'] ?>" <?php echo $readOnly_opt;?>  > <?php echo $dishAttr_row['attribute'] ?>
                                 <span> ₹ <?php echo $dishAttr_row['price'] ?></span> 
                                 <?php    //to show how much qty data is added to cart and which dish
                                    $added_msg = '';
                                    if(array_key_exists($dishAttr_row['id'],$cartArr))  
                                    {
                                       $added_qty = getUserDetailCart($dishAttr_row['id']);
                                       $added_msg = "(Added - $added_qty)";
                                       
                                    }                        
                                    echo "<span class='dish_alredy_added' id='shop_added_msg_".$dishAttr_row['id']."'> $added_msg</span>"
                                 ?>
                              </div>
                              <?php } ?>
                           </div>
                        </div>
                     </div>
                     <?php } ?>
                  </div>
                  <?php } else { 
                   echo "No Dish Found!";  
                  }?>
               </div>
            </div>
         </div>
         
         <?php 
            //to fetch Category from admin panel
            $category_res = mysqli_query($conn,"SELECT * FROM `category` WHERE `status` = 1 ORDER BY `order_number` DESC");
         ?>
         <div class="col-lg-3">
            <div class="shop-sidebar-wrapper gray-bg-7 shop-sidebar-mrg">
               <div class="shop-widget">
                  <h4 class="shop-sidebar-title">Shop By Categories</h4>
                  <div class="shop-catigory">
                     <ul id="faq" class="category_list">
                        <a href="<?php echo FRONT_SITE_PATH; ?>shop" class=" btn mb-2"> Clear</a>
                        <?php while($cat_row=mysqli_fetch_assoc($category_res)){
                           $class="selected";
                           if($cat_id==$cat_row['id']){
                              $class="active";
                           } 
                           $is_checked = '';
                           if(in_array($cat_row['id'],$catDish_arr))
                           { $is_checked = "checked='ckecked'"; }
                           ?>
									<li> <label> <input type="checkbox" <?php echo $is_checked; ?> onclick="set_cat_checkbox('<?php echo $cat_row['id']; ?>')" class="category_checkbox" name="category_dish[]" value="<?php echo $cat_row['id']; ?>"><?php echo $cat_row['category']; ?> </label></li>
                        <?php } ?>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- To fetch data accoring to category selected and dish type selected and in search box -->
<!-- the bolow field will go as hidden and set the values user filters-->
<form method="get" id="CatDish_form">
   <!-- to search according to category -->
	<input type="hidden" name="category_dish" id="category_dish" value='<?php echo $category_dish?>'/>
   <!-- to search according to dish type -->
   <input type="hidden" name="type_dish" id="type_dish" value="<?php echo $type_dish; ?>">
   <!-- to search according to user search -->
   <input type="hidden" name="search_str" id="search_str" value="<?php echo $search_str; ?>">

</form>


<?php include('footer.php'); ?>