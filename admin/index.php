<?php include('top.php'); ?>

<div class="row">
	<div class="col-md-6 col-lg-3 grid-margin stretch-card">
	  <div class="card">
		<div class="card-body">
		  <h1 class="font-weight-light mb-4">
			<?php 
			$start=date('Y-m-d')." 00:00:00";
			$end=date('Y-m-d')." 23:59:59";
			echo getDashboardSale($start,$end);
			?>
		  </h1>
		  <div class="d-flex flex-wrap align-items-center">
			<div>
			  <h4 class="font-weight-normal">Total Sale</h4>
			  <p class="text-muted mb-0 font-weight-light">Today's Sale </p>
			</div>
			<i class="mdi mdi-shopping icon-lg text-primary ml-auto"></i>
		  </div>
		</div>
	  </div>
	</div>

    <div class="col-md-6 col-lg-3 grid-margin stretch-card">
	  <div class="card">
		<div class="card-body">
		  <h1 class="font-weight-light mb-4">
			<?php 
			$start=strtotime(date('Y-m-d')." 00:00:00");
            $start = strtotime("-7 day",$start);
            $start = date("Y-m-d",$start);
			$end=date('Y-m-d')." 23:59:59";
			echo getDashboardSale($start,$end);
			?>
		  </h1>
		  <div class="d-flex flex-wrap align-items-center">
			<div>
			  <h4 class="font-weight-normal">7 Day's Sale</h4>
			  <p class="text-muted mb-0 font-weight-light">Last 7 Day's Sale </p>
			</div>
			<i class="mdi mdi-shopping icon-lg text-primary ml-auto"></i>
		  </div>
		</div>
	  </div>
	</div>

    <div class="col-md-6 col-lg-3 grid-margin stretch-card">
	  <div class="card">
		<div class="card-body">
		  <h1 class="font-weight-light mb-4">
			<?php 
			$start=strtotime(date('Y-m-d')." 00:00:00");
            $start = strtotime("-30 day",$start);
            $start = date("Y-m-d",$start);
			$end=date('Y-m-d')." 23:59:59";
			echo getDashboardSale($start,$end);
			?>
		  </h1>
		  <div class="d-flex flex-wrap align-items-center">
			<div>
			  <h4 class="font-weight-normal">30 Day's Sale</h4>
			  <p class="text-muted mb-0 font-weight-light">Last 30 Day's Sale </p>
			</div>
			<i class="mdi mdi-shopping icon-lg text-primary ml-auto"></i>
		  </div>
		</div>
	  </div>
	</div>

    <div class="col-md-6 col-lg-3 grid-margin stretch-card">
	  <div class="card">
		<div class="card-body">
		  <h1 class="font-weight-light mb-4">
			<?php 
			$start=strtotime(date('Y-m-d')." 00:00:00");
            $start = strtotime("-365 day",$start);
            $start = date("Y-m-d",$start);
			$end=date('Y-m-d')." 23:59:59";
			echo getDashboardSale($start,$end);
			?>
		  </h1>
		  <div class="d-flex flex-wrap align-items-center">
			<div>
			  <h4 class="font-weight-normal">Year Sale</h4>
			  <p class="text-muted mb-0 font-weight-light">Last Year  Sale </p>
			</div>
			<i class="mdi mdi-shopping icon-lg text-primary ml-auto"></i>
		  </div>
		</div>
	  </div>
	</div>
</div>

<h4 class="">Most Ordered Dish</h4>
<?php $query = mysqli_query($conn,"SELECT count(order_detail.dish_details_id) as dish_total , dish.dish as dish from order_detail INNER JOIN dish_details on order_detail.dish_details_id = dish_details.id  INNER JOIN dish on dish_details.dish_id = dish.id GROUP BY order_detail.dish_details_id ORDER BY dish_total DESC LIMIT 3 "); ?>
<div class="row">
    <?php while($row = mysqli_fetch_assoc($query)) { ?>
	<div class="col-md-6 col-lg-4 grid-margin stretch-card">
	  <div class="card">
		<div class="card-body">
		  <h1 class="font-weight-light mb-4">
			<?php echo $row['dish']; ?>
		  </h1>
		  <div class="d-flex flex-wrap align-items-center">
			<div>
			  <h4 class="font-weight-normal">Most Liked Dish</h4>
			  <p class="text-muted mb-0 font-weight-light"><?php echo $row['dish_total']." times"; ?></p>
			</div>
			<i class="mdi mdi-food icon-lg text-primary ml-auto"></i>
		  </div>
		</div>
	  </div>
	</div>
    <?php } ?>
</div>





<?php 
$res = mysqli_query($conn,"SELECT om.*,os.order_status as order_status_str FROM `order_master` AS om INNER JOIN `order_status` AS os on om.order_status = os.id  ORDER BY om.`id` DESC limit 5");
?>
<div class="row">
	<div class="col-12">
	  <div class="card">
		<div class="card-body">
		  <h4 class="">Latest 5 Order</h4>
		  <div class="table-responsive">
			<table class="table table-hover">
			  <thead>
				<tr>
				   <th width="5%">Order Id</th>
					<th width="20%">Name/Email/Mobile</th>
					<th width="20%">Address/Zipcode</th>
					<th width="5%">Price</th>
					<th width="10%">Payment Type</th>
					<th width="10%">Payment Status</th>
					<th width="10%">Order Status</th>
					<th width="15%">Added On</th>
				</tr>
			  </thead>
			  <tbody>
                        <?php if(mysqli_num_rows($res)>0){
						$i=1;
						while($row=mysqli_fetch_assoc($res)){
						?>
						<tr>
                            <td><?php echo $row['id']?></td>
                            <td>
								<p><?php echo $row['name']?></p>
								<p><?php echo $row['email']?></p>
								<p><?php echo $row['mobile']?></p>
							<td>
								<p><?php echo $row['address']?></p>
								<p><?php echo $row['zipcode']?></p>
							</td>
							<td style="font-size:14px;"><?php echo $row['total_price']?><br/>
								<?php
								if($row['coupon_code']!=''){
								?>
								<?php echo $row['coupon_code']?><br/>
								<?php echo "₹ ".$row['final_price']?>
								<?php } ?>
							
							</td>
							<td><?php echo $row['payment_type']?></td>
							<td>
								<div class="payment_status payment_status_<?php echo $row['payment_status']?>"><?php echo ucfirst($row['payment_status'])?></div>
							</td>
							<td><?php echo $row['order_status_str']?></td>
							<td>
							<?php 
							$dateStr=strtotime($row['added_on']);
							echo date('d-m-Y h:s',$dateStr);
							?>
							</td>
							
                        </tr>
                        <?php 
						$i++;
						} } else { ?>
						<tr>
							<td colspan="6">No data found</td>
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