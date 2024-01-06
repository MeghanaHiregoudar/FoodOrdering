<?php 
include('top.php');

$image="";
$heading="";
$sub_heading = "";
$link = "";
$link_text = "";
$order_number = "";
$id="";
//To make image required when insert 
$image_required = 'required';
$img_error = '';

if(isset($_GET['id']) && $_GET['id']>0){
	$id=get_safe_value($_GET['id']);
	$row=mysqli_fetch_assoc(mysqli_query($conn,"select * from banner where id='$id'"));
	$image=$row['image'];
    $heading=$row['heading'];
	$sub_heading=$row['sub_heading'];
	$link=$row['link'];
    $link_text=$row['link_text'];
    $order_number=$row['order_number'];
    //Image optional when update
    $image_required = '';
}

if(isset($_POST['submit'])){
	$heading=get_safe_value($_POST['heading']);
	$sub_heading=get_safe_value($_POST['sub_heading']);
	$link=get_safe_value($_POST['link']);
    $link_text=get_safe_value($_POST['link_text']);
    $order_number=get_safe_value($_POST['order_number']);
    $added_on=date('Y-m-d h:i:s');
    
    $image_type = $_FILES['image']['type'];
    if($id==''){
        if($image_type != 'image/jpeg' && $image_type != 'image/png' )
        {
          $img_error = "Invalid Image Format";
        }
        else
        {
          $image = $_FILES['image']['name'];
          $image_name = rand(111111111,999999999).'_'.$image;
          move_uploaded_file($_FILES['image']['tmp_name'],SERVER_BANNER_IMAGE.$image_name);
          
          mysqli_query($conn,"insert into banner(`image`, `heading`, `sub_heading`,`link`,`link_text`,`order_number`,`added_on`) values('$image_name','$heading','$sub_heading','$link','$link_text','$order_number','$added_on')");
          
          redirectPage('banner.php');
        }
       
    }else{
        //To check weather user uploads fie while update
        if($_FILES['image']['name'] == '')
        {
            mysqli_query($conn,"update banner set heading='$heading', sub_heading='$sub_heading',link='$link',link_text='$link_text',order_number='$order_number' where id='$id'");
			      redirectPage('banner.php');
        }
        else
        {
            $image = $_FILES['image']['name'];
            if($image_type != 'image/jpeg' && $image_type != 'image/png' )
            {
                $img_error = "Invalid Image Format";
            }
            else
            {
                $image_name = rand(111111111,999999999).'_'.$image;
                move_uploaded_file($_FILES['image']['tmp_name'],SERVER_DISH_IMAGE.$image_name);
              
  
                //To Delete Old uploaded Image 
                $old_img_row = mysqli_fetch_assoc(mysqli_query($conn,"select image from banner where id='$id'"));
                $old_image = $old_img_row['image'];
                unlink(SERVER_BANNER_IMAGE.$old_image);

                mysqli_query($conn,"update banner set heading='$heading', sub_heading='$sub_heading',link='$link',link_text='$link_text',order_number='$order_number',image='$image' where id='$id'");
				        redirectPage('banner.php');
            }

        }
    }
}
?>

<div class="row">
	  <h1 class="grid_title ml10 ml15">Manage Dish</h1>
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <form class="forms-sample" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="exampleInputEmail3">Dish Image</label>
                <input type="file" class="form-control"  name="image" <?php echo $image_required; ?>  >
                <p class="error"><?php echo $img_error; ?></p> <br>
                <?php if($id > 0) { ?>
                    <img src="<?php echo SITE_BANNER_IMAGE.$row['image']; ?>" alt="Banner Image" width="100">
                <?php } ?>
            </div> 
            <div class="form-group">
              <label for="heading" required>Heading</label>
              <input type="textbox" class="form-control" placeholder="Heading" name="heading"  value="<?php echo $heading; ?>" required> 
            </div>
            <div class="form-group">
              <label for="sub_heading">Sub Heading</label>
              <input type="textbox" class="form-control" placeholder="Sub Heading" name="sub_heading"  value="<?php echo $sub_heading; ?>" required> 
            </div>
            <div class="form-group">
              <label for="link">Link</label>
              <input type="textbox" class="form-control" placeholder="Link" name="link"  value="<?php echo $link; ?>" required> 
            </div>
            <div class="form-group">
              <label for="link_text">Link Text</label>
              <input type="textbox" class="form-control" placeholder="Link Text" name="link_text"  value="<?php echo $link_text; ?>" required> 
            </div>
            <div class="form-group">
              <label for="order_number">Order Number</label>
              <input type="textbox" class="form-control" placeholder="Order Number" name="order_number"  value="<?php echo $order_number; ?>" required> 
            </div>
            <button type="submit" class="btn btn-primary mr-2" name="submit">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php include('footer.php');?>