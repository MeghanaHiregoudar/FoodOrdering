<?php include('top.php'); 

//Code to Delete and Status update
if(isset($_GET['type']) && $_GET['type']!== '' && isset($_GET['id']) && $_GET['id'] > 0 )
{
  $type = $_GET['type'];
  $id = $_GET['id'];
  if($type == 'delete')
  {
      //To Delete Old uploaded Image 
      $old_img_row = mysqli_fetch_assoc(mysqli_query($conn,"select image from dish where id='$id'"));
      $old_image = $old_img_row['image'];
      unlink(SERVER_DISH_IMAGE.$old_image);
      mysqli_query($conn,"delete from dish where id = '$id' ");
      redirectPage('dish.php');
  }

  if($type == 'active' || $type == 'deactive')
  {
    $status = 1;
    if($type == 'deactive')
    {
      $status = 0;
    }
    mysqli_query($conn,"update dish set status = '$status' where id = '$id' ");
    redirectPage('dish.php');
  }
}

//Query to Fetch
$query = mysqli_query($conn,"select dish.*, category.category from dish inner join category on category.id = dish.category_id order by dish.id desc");
?>

<div class="card">
   <div class="card-body">
      <h4>Dish Master </h4>
      <a href="manage_dish.php" class=" btn btn-primary add_btn "> Add Dish </a>
      <div class="row">
         <div class="col-12">
            <div class="table-responsive">
               <table id="order-listing" class="table">
                  <thead>
                     <tr>
                        <th>S.No #</th>
                        <th>Dish</th>
                        <th>Category</th>
                        <th>Image</th>
                        <th>Added On</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if(mysqli_num_rows($query) > 0) { 
                        $i = 1;
                        while($row = mysqli_fetch_assoc($query))
                        { ?>
                     <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row['dish'] ." (".strtoupper($row['dish_type']).")"; ?></td>
                        <td><?php echo $row['category']; ?></td>
                        <td>
                           <a target="_blank" href="<?php echo SITE_DISH_IMAGE.$row['image']; ?>">
                              <img src="<?php echo SITE_DISH_IMAGE.$row['image']; ?>" alt="Dish Image" width="20">
                           </a>
                        </td>
                        <td>
                           <?php
                              //Code to show custome date format
                              $dateStr = strtotime($row['added_on']);
                              echo date('d-m-Y',$dateStr); ?>
                        </td>
                        <td>
                           <a href="manage_dish.php?id=<?php echo $row['id']; ?>" class="hand_cursor"><label class="badge badge-primary">Edit</label></a> &nbsp;
                           <?php if($row['status'] == 1)
                              { ?>
                           <a href="?id=<?php echo $row['id'];?>&type=deactive" class="hand_cursor" ><label class="badge badge-info">Active</label></a> &nbsp;
                           <?php 
                              } 
                              else
                              { ?>
                           <a href="?id=<?php echo $row['id'];?>&type=active" class="hand_cursor" ><label class="badge badge-danger">Deactive</label></a> &nbsp;
                           <?php  
                              } ?>
                           <a href="?id=<?php echo $row['id']; ?>&type=delete" class="hand_cursor" ><label class="badge badge-danger delete_red">Delete</label></a>
                        </td>
                     </tr>
                     <?php
                        $i++;
                        }
                        }
                        else
                        { ?>
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