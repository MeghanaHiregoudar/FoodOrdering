<?php 
include('top.php');

$msg="";
$dish="";
$dish_type ="";
$dish_detail="";
$category_id = "";
$image = "";
$id="";
//To make image required when insert 
$image_required = 'required';
$img_error = '';

//To remove dish detail attribute
if(isset($_GET['id']) && $_GET['id']>0 && isset($_GET['dish_details_id']) && $_GET['dish_details_id']>0)
{
  $id = get_safe_value($_GET['id']);
  $dish_details_id = get_safe_value($_GET['dish_details_id']);
  
  if(mysqli_query($conn,"DELETE FROM `dish_details` WHERE `dish_id` = '$id' AND `id` = '$dish_details_id'"))
  {
    redirectPage('manage_dish.php?id='.$id);
  }
}

if(isset($_GET['id']) && $_GET['id']>0){
	$id=get_safe_value($_GET['id']);
	$row=mysqli_fetch_assoc(mysqli_query($conn,"select * from dish where dish.id='$id'"));
	$dish=$row['dish'];
  $dish_type = $row['dish_type'];
  $category_id=$row['category_id'];
	$dish_detail=$row['dish_detail'];
	$image=$row['image'];
  //Image optional when update
  $image_required = '';
}

if(isset($_POST['submit'])){
	$dish=get_safe_value($_POST['dish']);
  $dish_type = get_safe_value($_POST['dish_type']);
	$category_id=get_safe_value($_POST['category']);
	$dish_detail=get_safe_value($_POST['dish_detail']);
	
	$added_on=date('Y-m-d h:i:s');
	
	if($id==''){
		$sql="select * from dish where dish='$dish'";
	}else{
		$sql="select * from dish where dish='$dish' and id!='$id'";
	}	
	if(mysqli_num_rows(mysqli_query($conn,$sql))>0){
		$msg="Dish already added";
	}else{
    $image_type = $_FILES['image']['type'];
		if($id==''){
      if($image_type != 'image/jpeg' && $image_type != 'image/png' )
      {
        $img_error = "Invalid Image Format";
      }
      else
      {
        $image = $_FILES['image']['name'];
        $image_name = $dish.'_'.$image;
        move_uploaded_file($_FILES['image']['tmp_name'],SERVER_DISH_IMAGE.$image_name);
        mysqli_query($conn,"insert into dish(`category_id`, `dish`,`dish_type`, `dish_detail`,`image`,`added_on`) values('$category_id','$dish','$dish_type','$dish_detail','$image_name','$added_on')");
        
        //Insert data in dish_deatils table
        $did = mysqli_insert_id($conn);
        $attributeArr = $_POST['attribute'];
        $priceArr = $_POST['price'];
        $dish_statusArr = $_POST['dish_status'];
        foreach($attributeArr as $key => $value)
        {
          $attribute = $value;
          $price = $priceArr[$key];
          $dish_status = $dish_statusArr[$key];
          if($did > 0)
          {
            mysqli_query($conn,"INSERT INTO `dish_details`(`dish_id`, `attribute`, `price`,`status`, `added_on`) VALUES ('$did','$attribute','$price','$dish_status','$added_on')");
          }
        }
		    redirectPage('dish.php');
      }
     
		}else{
      //To check weather user uploads fie while update
      $image_condition = '';
      if($_FILES['image']['name'] != '')
      {
        if($image_type != 'image/jpeg' && $image_type != 'image/png' )
        {
          $img_error = "Invalid Image Format";
        }
        else
        {
          $image = $_FILES['image']['name'];
          $image_name = $dish.'_'.$image;
          move_uploaded_file($_FILES['image']['tmp_name'],SERVER_DISH_IMAGE.$image_name);
          $image_condition = ", image='$image_name'";

          //To Delete Old uploaded Image 
          $old_img_row = mysqli_fetch_assoc(mysqli_query($conn,"select image from dish where id='$id'"));
          $old_image = $old_img_row['image'];
          unlink(SERVER_DISH_IMAGE.$old_image);
        }
      }
      if($img_error == '')
      {
        $update_query = "update dish set category_id='$category_id', dish='$dish',dish_type='$dish_type' $image_condition , dish_detail='$dish_detail' where id='$id'";
			  $update_data = mysqli_query($conn,$update_query);


        $attributeArr = $_POST['attribute'];
        $priceArr = $_POST['price'];
        $dish_details_idArr = $_POST['dish_details_id'];
        $dish_statusArr = $_POST['dish_status'];
        foreach($attributeArr as $key => $value)
        {
          $attribute = $value;
          $price = $priceArr[$key];
          $dish_status = $dish_statusArr[$key];
          $dish_detail_id = $dish_details_idArr[$key];
          if($update_data)
          {
            if(isset($dish_detail_id)){
              mysqli_query($conn,"UPDATE `dish_details` SET `attribute`='$attribute',`price`= '$price', `status` = '$dish_status' WHERE `id` = '$dish_detail_id' AND `dish_id` = '$id'");
            }
            else {
              mysqli_query($conn,"INSERT INTO `dish_details`(`dish_id`, `attribute`, `price`,`status`,`added_on`) VALUES ('$id','$attribute','$price','$dish_status','$added_on')");
            }
          }
        }
        
		    redirectPage('dish.php');
      }
		}
	}
}

$category_res = mysqli_query($conn, "SELECT * FROM `category` WHERE `status` = 1 ORDER BY `category`");
$arrDish_type = array("veg","non-veg","egg","both");
?>
  <div class="row">
	  <h1 class="grid_title ml10 ml15">Manage Dish</h1>
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <form class="forms-sample" method="post" enctype="multipart/form-data">
					  <div class="form-group">
              <label for="exampleInputName1">Category</label> 
              <select name="category" required class="form-control" required>
                <option value="">Select Category</option>
                <?php
                while($category_row = mysqli_fetch_assoc($category_res))
                { 
                  if($category_id == $category_row["id"])
                  {
                    echo "<option value='". $category_row["id"]."' selected>". $category_row['category']."</option>";
                  }
                  else
                  {
                    echo "<option value='". $category_row["id"]."'>". $category_row['category']."</option>";
                  }
                }
               ?>
              </select>       
            </div>
            <div class="form-group">
              <label for="exampleInputEmail3" required>Dish</label>
              <input type="textbox" class="form-control" placeholder="Dish" name="dish"  value="<?php echo $dish; ?>" required> 
              <p class="error"><?php echo $msg; ?></p>
            </div>
            <div class="form-group">
              <label for="exampleInputName1">Dish Type</label> 
              <select name="dish_type" required class="form-control" required>
                <option value="">Select Dish Type</option>
                <?php
                foreach($arrDish_type as $dish_list)
                { 
                  if($dish_list == $dish_type)
                  {
                    echo "<option value='". $dish_list."' selected>". strtoupper($dish_list)."</option>";
                  }
                  else
                  {
                    echo "<option value='". $dish_list."'>". strtoupper($dish_list)."</option>";
                  }
                }
               ?>
              </select>       
            </div>
            
            <div class="form-group">
              <label for="exampleTextarea1">Dish Detail</label>
              <textarea class="form-control" name="dish_detail"  rows="4" value="<?php echo $dish_detail; ?>" required> <?php echo $dish_detail?></textarea>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail3">Dish Image</label>
              <input type="file" class="form-control"  name="image" <?php echo $image_required; ?>  >
              <p class="error"><?php echo $img_error; ?></p> <br>
              <?php if($id > 0) { ?>
                <img src="<?php echo SITE_DISH_IMAGE.$row['image']; ?>" alt="Dish Image" width="100">
              <?php } ?>
            </div>    

            <div class="form-group" id="dish_attribute1">
              <label for="exampleInputEmail3" required>Dish Attribute</label>
              <?php if($id == 0) { ?>
                <div class="row">
                  <div class="col-md-4">
                    <label for="exampleInputEmail3" required></label>
                    <input type="textbox" class="form-control" placeholder="Attribute" name="attribute[]" required>
                  </div>
                  <div class="col-md-3">
                    <label for="exampleInputEmail3" required></label>
                    <input type="textbox" class="form-control" placeholder="Price" name="price[]" required>
                  </div>
                  <div class="col-md-3">
                    <label for="exampleInputEmail3" required></label>
                    <select name="dish_status[]" class="form-control" required >
                      <option value="">Select Status</option>
                      <option value="1">Available</option>
                      <option value="0">Unavailable</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label for="exampleInputEmail3" ></label>
                    <button type="button" class="btn btn-info mt-3" onclick="add_more_dish()" >Add More</button>
                  </div>
                </div>
              <?php } else {
                $dish_detail_res = mysqli_query($conn,"SELECT * FROM `dish_details` WHERE `dish_id` = '$id'");
                $count = 1;
                while($dish_detail_row = mysqli_fetch_assoc($dish_detail_res)) { ?>
                  <div class="row">
                    <input type="hidden" name="dish_details_id[]" id="dish_detail_id" class="form-control" value="<?php echo $dish_detail_row['id']; ?>">
                    <div class="col-md-4">
                      <label for="exampleInputEmail3" required></label>
                      <input type="textbox" class="form-control" placeholder="Attribute" name="attribute[]" value="<?php echo $dish_detail_row['attribute']; ?>" required>
                    </div>
                    <div class="col-md-3">
                      <label for="exampleInputEmail3" required></label>
                      <input type="textbox" class="form-control " placeholder="Price" name="price[]" value="<?php echo $dish_detail_row['price']; ?>" required>
                    </div>
                    <div class="col-md-3">
                    <label for="exampleInputEmail3" required></label>
                    <select name="dish_status[]" class="form-control" required >
                      <option value="">Select Status</option>
                      <?php if($dish_detail_row['status'] == 1) {?>
                        <option value="1" selected>Available</option>
                        <option value="0">Unavailable</option>
                      <?php } else {?>
                        <option value="1">Available</option>
                        <option value="0" selected>Unavailable</option>
                      <?php } ?>
                    </select>
                  </div>
                    <div class="col-md-2">
                      <label for="exampleInputEmail3" ></label>
                      <?php if($count == 1) {?>
                        <button type="button" class="btn btn-info mt-3" onclick="add_more_dish()" >Add More</button>
                      <?php } else { ?>
                        <button type="button" class="btn btn-warning mt-4" onclick="remove_in_edit('<?php echo $dish_detail_row['id']; ?>')" >Remove</button>
                      <?php } ?>
                    </div>
                  </div>
              <?php $count++;
            } } ?> 
            </div>    
            <button type="submit" class="btn btn-primary mr-2" name="submit">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>

  var rowCount = 1;
  function add_more_dish()
  {
    rowCount++
    var html = '<div class="row" id="dish_box'+rowCount+'">'+
                '<div class="col-md-4">'+
                  '<label for="exampleInputEmail3" required></label>'+
                  '<input type="textbox" class="form-control" placeholder="Attribute" name="attribute[]" required>'+
                '</div>'+
                '<div class="col-md-3">'+
                  '<label for="exampleInputEmail3" required></label>'+
                  '<input type="textbox" class="form-control mt-1" placeholder="Price" name="price[]" required>'+
                '</div>'+
                '<div class="col-md-3">'+
                  '<label for="exampleInputEmail3" required></label>'+
                  '<select name="dish_status[]" class="form-control" required >'+
                    '<option value="">Select Status</option>'+
                    '<option value="1">Available</option>'+
                    '<option value="0" >Unavailable</option>'+
                  '</select>'+
                '</div>'+
                '<div class="col-md-2">'+
                  '<label for="exampleInputEmail3" ></label>'+
                  '<button type="button" class="btn btn-warning mt-4" onclick=remove_attribute("'+rowCount+'") >Remove</button>'+
                '</div>'+
              '</div>';
    jQuery('#dish_attribute1').append(html);
  }

  function remove_attribute(ind)
  {
    jQuery("#dish_box"+ind).remove();
  }

  function remove_in_edit(id)
  {
    if(confirm("Do You Want To Delete?"))
    {
      var current_path = window.location.href;
      window.location.href = current_path+"&dish_details_id="+id;
    }
  }
  </script>
        
<?php include('footer.php');?>