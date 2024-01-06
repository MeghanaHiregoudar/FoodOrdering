//User Register
jQuery("#register_form").on("submit" , function(e){
    jQuery('#error_email').html('');
    jQuery('#register_submit').attr('disabled',true);
	jQuery('#success_message').html('Please wait...');

    $.ajax({
        url:FRONT_SITE_PATH+"login_register_submit",
        method:"POST",
        data:jQuery("#register_form").serialize(),
        dataType:"JSON",
        success:function(response)
        {
            jQuery('#success_message').html('');
			jQuery('#register_submit').attr('disabled',false);
            // var data=jQuery.parseJSON(response);
			if(response.status=='error'){
				jQuery('#error_email').html(response.msg);
			}
			if(response.status=='success'){
				jQuery('#success_message').html(response.msg);
                jQuery("#register_form")[0].reset();
			}
        }
    }); //End of ajax
    e.preventDefault();
});

//User Login
jQuery("#login_form").on("submit", function(e){
    e.preventDefault();
    jQuery('#login_submit').attr('disabled',true);
    $.ajax({
        url:FRONT_SITE_PATH+"login_register_submit",
        method:"POST",
        data:jQuery("#login_form").serialize(),
        dataType:"JSON",
        success:function(response)
        {
            jQuery('#login_submit').attr('disabled',false);
            if(response.status == "error")
            {
                jQuery("#message").text(response.msg);
            }
            else
            {   
                jQuery("#login_form")[0].reset();
                var is_checkout = jQuery("#is_checkout").val();
                if(is_checkout == 'yes')
                {
                   window.location.href = FRONT_SITE_PATH+"checkout";
                } else if(response.status == "success") {
                    window.location.href =response.redirect ;
                }
            }
        }
    }); //End of ajax
});

//User forgot password
jQuery("#forgot_password_form").on("submit", function(e){
    jQuery('#forgot_submit').attr('disabled',true);
	jQuery('#form_forgot_msg').html('Please wait...');
  
    // alert("forgot");
    $.ajax({
        url:FRONT_SITE_PATH+"login_register_submit",
        method:"POST",
        data:jQuery("#forgot_password_form").serialize(),
        success:function(result)
        {
            jQuery('#form_forgot_msg').html('');
			jQuery('#forgot_submit').attr('disabled',false);
			var data=jQuery.parseJSON(result);
			if(data.status=='error'){
				jQuery('#form_forgot_msg').html(data.msg);
			}
			if(data.status=='success'){
				jQuery('#form_forgot_msg').html(data.msg);
			}
        }
    }); //End of ajax
    e.preventDefault();
});


//User Edit Profile
jQuery("#profile_form").on("submit", function(e){
    e.preventDefault();
    $.ajax({
        url:FRONT_SITE_PATH+"update_profile",
        method:"POST",
        data:jQuery("#profile_form").serialize(),
        dataType:"JSON",
        success:function(response)
        {
            jQuery("#profile_top_name").html(jQuery("#uname").val());
            swal("Success Message",response.msg , "success");
        }
    }); //End of 
});

//User change Password
jQuery("#change_Password_form").on("submit", function(e){
    e.preventDefault();
    var password = jQuery("#new_password").val();
    var confirm_password = jQuery("#confirm_password").val();
    if(password != confirm_password)
    {
        swal("Failed"," Password Mismatch", "error");
    }
    else
    {
        $.ajax({
            url:FRONT_SITE_PATH+"update_profile",
            method:"POST",
            data:jQuery("#change_Password_form").serialize(),
            dataType:"JSON",
            success:function(response)
            {
                console.log(response);
                jQuery("#change_Password_form")[0].reset();
                if(response.status=='success'){
                    swal("Success Message", response.msg, "success");
                }
                if(response.status=='error'){
                    swal("Error Message", response.msg, "error");
                }
            }
        }); //End of 
    }
});


//function to filter through checkbox(category_dish)
function set_cat_checkbox(id){
    var category_dish=jQuery('#category_dish').val();
  //To uncheck checkbox
  var id_check = category_dish.search(":"+id);
  if(id_check != '-1')
  {
     category_dish=category_dish.replace(":"+id,'');
  }
  else
  {
     category_dish=category_dish+":"+id;
  }
    
    jQuery('#category_dish').val(category_dish);
    jQuery('#CatDish_form')[0].submit();
}

//function to filter through radio(type_dish)
function setFoodtype(type)
{
  jQuery('#type_dish').val(type);
    jQuery('#CatDish_form')[0].submit();
}

function setSearch()
{
    jQuery('#search_str').val(jQuery('#search_data').val());
    jQuery('#CatDish_form')[0].submit();
}


//function adding dish to cart
function add_to_cart(dish_id,type)
{      
  var qty = jQuery("#quantity"+dish_id).val();
  var dish_attr = jQuery('input[name="radio_'+dish_id+'"]:checked').val();

  if(qty == '')
  {
    swal("Failed!", "Select Quanity!", "error");
  } 
  else if(dish_attr == undefined)
  {
    swal("Failed!", "Select Dish!", "error");
  }
  else
  {
    $.ajax({
        url:FRONT_SITE_PATH+"manage_cart",
        method:"POST",
        data:{qty:qty,dish_attr:dish_attr,type:type},
        success:function(response)
        {
            var data = jQuery.parseJSON(response);
           
            swal("Congrates !", "Dish Added To cart!", "success");
            //to display quantity in added msg when dish added to cart without page reload
            jQuery("#shop_added_msg_"+dish_attr).html("(Added - "+qty+")");

            //to find count  and total amount of dish added to array and display without page load
            jQuery("#totaldishCount").html(data.totaldishCount);
            jQuery("#dishtotal_price").html("₹ "+data.total_price);

            //if count == 1 then show added dish to top dropdown cart else append to dish detail to ul display without page load
            if(data.totaldishCount == 1 )
            {
                var tprice = qty * data.price;
                var html = '<div class="shopping-cart-content"> <ul id="cart_ul">'+
                                '<li class="single-shopping-cart" id="attr_'+dish_attr+'">'+
                                    '<div class="shopping-cart-img"> <a href="javascript:void(0)"><img src="'+SITE_DISH_IMAGE+data.image+'" width= "100%" alt="Dish Image"></a> </div>'+
                                    '<div class="shopping-cart-title"> '+
                                        '<h4><a href="javascript:void(0)">'+data.dish+' </a></h4>'+data.attribute+''+
                                        '<h6>Qty:'+qty+' </h6>'+
                                        '<span>₹ '+tprice+' </span>'+
                                    '</div>'+
                                    '<div class="shopping-cart-delete"> <a href="javascript:void(0)" onclick=delete_dish_cart("'+dish_attr+'")><i class="ion ion-close"></i></a> </div> '+
                                '</li> </ul>'+
                                '<div class="shopping-cart-total"> <!-- <h4>Shipping : <span>$20.00</span></h4> -->'+
                                    '<h4>Total : <span class="shop-total" id="shop_total_price">₹ '+tprice+' </span></h4> '+
                                '</div>'+
                                '<div class="shopping-cart-btn">'+
                                    '<a href="'+FRONT_SITE_PATH+'cart">View Cart</a>'+
                                    '<a href="'+FRONT_SITE_PATH+'checkout">Checkout</a>'+
                                '</div>'+
                            '</div>';
                jQuery(".header-cart").append(html);
            }
            else
            {
                var tprice = qty * data.price;
                jQuery("#attr_"+dish_attr).remove();
                var html ='<li class="single-shopping-cart"  id="attr_'+dish_attr+'">'+
                    '<div class="shopping-cart-img"> <a href="javascript:void(0)"><img src="'+SITE_DISH_IMAGE+data.image+'" width= "100%" alt="Dish Image"></a> </div>'+
                    '<div class="shopping-cart-title"> '+
                        '<h4><a href="javascript:void(0)">'+data.dish+' </a></h4>'+data.attribute+''+
                        '<h6>Qty:'+qty+' </h6>'+
                        '<span>₹ '+tprice+' </span>'+
                    '</div>'+
                    '<div class="shopping-cart-delete"> <a href="javascript:void(0)" onclick=delete_dish_cart("'+dish_attr+'")><i class="ion ion-close"></i></a> </div> '+
                '</li>';
                jQuery("#cart_ul").append(html);

                //Change the grand total price with out page load
                jQuery("#shop_total_price").html("₹ "+data.total_price);
            }
        }

    }); //end of ajax
  }
}

function delete_dish_cart(dist_attr_id ,page_load)
{
    if(confirm("Do You Want To Delete?"))
    {
        $.ajax({
            url:FRONT_SITE_PATH+"manage_cart",
            method:"POST",
            data:{dish_attr:dist_attr_id,type:"delete_cart_dish"},
            success:function(response)
            {
                if(page_load == 'load')
                {
                    window.location.href='';
                }
                else
                {
                    var data = jQuery.parseJSON(response);
            
                    //to find count  and total amount of dish added to array and display without page load
                    jQuery("#totaldishCount").html(data.totaldishCount);               
                                    
                    //to remove quantity in added msg when dish deleted from cart without page reload
                    jQuery("#shop_added_msg_"+dist_attr_id).html("");

                    if(data.totaldishCount == 0)
                    {
                        // jQuery(".shopping-cart-content").remove('');
                        jQuery(".shopping-cart-content").css("display", "none");
                        jQuery("#dishtotal_price").html("");
                    }
                    else
                    {
                        jQuery("#dishtotal_price").html("₹ "+data.total_price);
                        //Change the grand total price with out page load
                        jQuery("#shop_total_price").html("₹ "+data.total_price);
                        //To remove dish in top cart with out page load
                        jQuery("#attr_"+dist_attr_id).remove();
                        jQuery("#table_dish_attr_"+dist_attr_id).remove();
                    }
                }
                
            }
        });
    }
}

//to redirect page from header.php
jQuery("#top_view_cart").click(function(){
    window.location.href=FRONT_SITE_PATH+'cart';
});
jQuery("#top_checkout").click(function(){
    window.location.href=FRONT_SITE_PATH+'checkout';
});

//To Apply Coupon Code
function applyCouponCode()
{
    var coupon_code = jQuery("#coupon_code").val();
    if(coupon_code == '')
    {
        jQuery("#coupon_code_error").html("Please Enter Coupon Code");
    }
    else
    {
        jQuery("#coupon_code_error").empty();

        $.ajax({
            url:FRONT_SITE_PATH+"apply_coupon",
            method:"POST",
            data:{coupon_code:coupon_code},
            dataType:"JSON",
            success:function(response)
            { 
                if(response.status=='success'){
                    swal("Success Message", response.msg, "success");
                    //Shown this box only when coupon applied
                    jQuery(".coupon_price_box").show();
                    jQuery(".total_price").html("Sub Total");
                    //coupon peice display
                    jQuery(".coupon_code_price").html("- ₹ "+response.coupon_price);
                    //total price after appling coupon
                    jQuery(".final_price").html("₹ "+response.coupon_value_applied);
                    
                }
                if(response.status=='error'){
                    swal("Error Message", response.msg, "error");
                }
            }
        }); //End of ajax
    }
}

//To upadte rating in db
function updaterating(did,oid)
{
    var rating = jQuery("#rate_select_"+did).val();
    var rating_str = jQuery("#rate_select_"+did+" option:selected").text();
    if(rating != '')
    {
        $.ajax({
            url:FRONT_SITE_PATH+"update_rating",
            method:"POST",
            data:{rating:rating,did:did,oid:oid},
            success:function(response)
            { 
                jQuery("#rating"+did).html("<div class='set_rating'>"+rating_str+"</div>");
            }
        }); //end of ajax
    }
}