<?php include('top.php'); 

//Code to Delete and Status update
if(isset($_GET['type']) && $_GET['type']!== '' && isset($_GET['id']) && $_GET['id'] > 0 )
{
  $type = $_GET['type'];
  $id = $_GET['id'];

  if($type == 'active' || $type == 'deactive')
  {
    $status = 1;
    if($type == 'deactive')
    {
      $status = 0;
    }
    mysqli_query($conn,"update user set status = '$status' where id = '$id' ");
    redirectPage('user.php');
  }
}

//Query to Fetch
$query = mysqli_query($conn,"select * from user order by id desc");
?>

<div class="card">
   <div class="card-body">
      <h4>User Master </h4>
      <div class="row">
         <div class="col-12">
            <div class="table-responsive">
               <table id="order-listing" class="table">
                  <thead>
                     <tr>
                        <th>S.No #</th>
                        <th>Name </th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Wallet Amt</th>
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
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['mobile']; ?></td>
                        <td class="text-center"><?php echo "₹ ".getWalletAmt($row['id']); ?></td>
                        <td>
                           <?php
                              //Code to show custome date format
                              $dateStr = strtotime($row['added_on']);
                              echo date('d-m-Y',$dateStr); ?>
                        </td>
                        <td>
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
                            <a href="add_money.php?id=<?php echo $row['id'];?>" class="hand_cursor" ><label class="badge badge-success">Add Money</label></a> &nbsp;
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