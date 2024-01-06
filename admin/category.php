<?php include('top.php'); 

//Code to Delete and Status update
if(isset($_GET['type']) && $_GET['type']!== '' && isset($_GET['id']) && $_GET['id'] > 0 )
{
  $type = $_GET['type'];
  $id = $_GET['id'];
  if($type == 'delete')
  {
    mysqli_query($conn,"delete from category where id = '$id' ");
    redirectPage('category.php');
  }

  if($type == 'active' || $type == 'deactive')
  {
    $status = 1;
    if($type == 'deactive')
    {
      $status = 0;
    }
    mysqli_query($conn,"update category set status = '$status' where id = '$id' ");
    redirectPage('category.php');
  }
}

//Query to Fetch
$query = mysqli_query($conn,"select * from category order by order_number");
?>

<div class="card">
   <div class="card-body">
      <h4>Category Master </h4>
      <a href="manage_category.php" class=" btn btn-primary add_btn "> Add Category</a>
      <div class="row">
         <div class="col-12">
            <div class="table-responsive">
               <table id="order-listing" class="table">
                  <thead>
                     <tr>
                        <th>S.No #</th>
                        <th>Category </th>
                        <th>Order Number</th>
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
                        <td><?php echo $row['category']; ?></td>
                        <td><?php echo $row['order_number']; ?></td>
                        <td>
                           <a href="manage_category.php?id=<?php echo $row['id']; ?>" class="hand_cursor"><label class="badge badge-primary">Edit</label></a> &nbsp;
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