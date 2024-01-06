<?php include('top.php'); 

//Code to Delete 
if(isset($_GET['type']) && $_GET['type']!== '' && isset($_GET['id']) && $_GET['id'] > 0 )
{
  $type = $_GET['type'];
  $id = $_GET['id'];
  if($type == 'delete')
  {
    mysqli_query($conn,"delete from contact_us where id = '$id' ");
    redirectPage('contactUs.php');
  }
}

//Query to Fetch
$query = mysqli_query($conn,"select * from contact_us order by id desc");
?>

<div class="card">
   <div class="card-body">
      <h4>Contact Us </h4>
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
                        <th>subject</th>
                        <th>Message</th>
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
                        <td><?php echo $row['subject']; ?></td>
                        <td><?php echo $row['message']; ?></td>
                        <td>
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