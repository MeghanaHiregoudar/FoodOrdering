<?php include('top.php'); 

//Code to Delete and Status update
if(isset($_GET['type']) && $_GET['type']!== '' && isset($_GET['id']) && $_GET['id'] > 0 )
{
  $type = $_GET['type'];
  $id = $_GET['id'];
  if($type == 'delete')
  {
    mysqli_query($conn,"delete from coupon_code where id = '$id' ");
    redirectPage('coupon_code.php');
  }

  if($type == 'active' || $type == 'deactive')
  {
    $status = 1;
    if($type == 'deactive')
    {
      $status = 0;
    }
    mysqli_query($conn,"update coupon_code set status = '$status' where id = '$id' ");
    redirectPage('coupon_code.php');
  }
}

//Query to Fetch
$query = mysqli_query($conn,"select * from coupon_code order by id");
?>

<div class="card">
   <div class="card-body">
      <h4>Coupon Code Master </h4>
      <a href="manage_coupon_code.php" class=" btn btn-primary add_btn "> Add Coupon Code</a>
      <div class="row">
         <div class="col-12">
            <div class="table-responsive">
               <table id="order-listing" class="table">
                  <thead>
                     <tr>
                        <th>S.No #</th>
                        <th>Code  </th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Cart Min Value</th>
                        <th>Expires On</th>
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
                        <td><?php echo $row['coupon_code']; ?></td>
                        <td><?php echo $row['coupon_type']; ?></td>
                        <td><?php echo $row['coupon_value']; ?></td>
                        <td><?php echo $row['cart_min_value']; ?></td>
                        <td>
                            <?php 
                            if($row['expired_on'] == '0000-00-00')
                            {
                                echo "Not Defined";
                            }
                            else
                            {
                                echo $row['expired_on'];
                            }  ?>
                        </td>
                        <td>
                           <?php
                              //Code to show custome date format
                              $dateStr = strtotime($row['added_on']);
                              echo date('d-m-Y',$dateStr); ?>
                        </td>
                        <td>
                           <a href="manage_coupon_code.php?id=<?php echo $row['id']; ?>" class="hand_cursor"><label class="badge badge-primary">Edit</label></a> &nbsp;
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