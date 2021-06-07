<?php include("../config/config.php"); ?>
<?php include("../config/".ENV."_config.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<title><?php echo APP; ?> - Customer Orders</title>

  	<link rel="stylesheet" href="../public/font-awesome/css/fontawesome.min.css" rel="stylesheet" type="text/css">
  	<link rel="stylesheet" href="../public/font-awesome/css/all.min.css" rel="stylesheet" type="text/css">
    
  	<link rel="stylesheet" href="../public/css/bootstrap/bootstrap.min.css">
  	<script src="../public/js/jquery.min.js"></script>
  	<script src="../public/js/bootstrap.min.js"></script>

  	<style type="text/css">
  		body
		{
		    background:#F0F3F4;
		}

		.list-img
		{
			height:100px;
			width:100%;
		}
  	</style>
</head>
<body>
<?php
	if(isset($_REQUEST['mobile']) && $_REQUEST['mobile']!="" && isset($_REQUEST['s']) && $_REQUEST['s']!="")
	{
		if(!isset($_POST['show-suborders']))
		{
			$data['user_name']=$_REQUEST['s'];
			$data['customer_mobile']=$_REQUEST['mobile'];

			$url=DOMAIN.'/rest/seller/getCustomerOrderDetailsRest.php';
			$output=getRestApiResponse($url,$data);

			if(isset($output['getsellercustomers']) && $output['getsellercustomers']['response_code']==200)
			{
				if(isset($output['getsellercustomers']['rows']) && $output['getsellercustomers']['rows']!=0)
			    {
?>
				<div class="container">
					<div class="row mt-3">
						<div class="col-12 mt-3">
							<a href="javascript:history.go(-1)" class="btn btn-success">Continue Shopping</a>
						</div>
					</div>
					<div class="row mt-4">
						<div class="col-12 text-center">
							<h5>Orders For - <?php echo $_REQUEST['mobile']; ?></h5>
						</div>
					</div>

					<div class="row mt-2">
						<div class="col-12">
							<?php
								for($i=0;$i<$output['getsellercustomers']['rows'];$i++) 
          						{
          						    $record="";
          						    
          							$record=<<< record
          							<div class="row mt-3">
          								<div class="col-md-4">
          									<b>Order Id - </b>{$output['getsellercustomers'][$i]['order_id']}<br>
          									<b>Order Date - </b>{$output['getsellercustomers'][$i]['order_date']}<br>
          								</div>
          								<div class="col-md-3 mt-2 mt-md-0">
          									<b>Total Items - </b>{$output['getsellercustomers'][$i]['total_items']}<br>
record;
      								if($output['getsellercustomers'][$i]['order_status']=="Pending")
      								{
      									$record.=<<< record
      									<b>Order Status - </b><span class="text-warning">{$output['getsellercustomers'][$i]['order_status']}</span>
record;
      								}
      								else
      								if($output['getsellercustomers'][$i]['order_status']=="Accepted" || $output['getsellercustomers'][$i]['order_status']=="Delivered")
      								{
      									$record.=<<< record
      									<b>Order Status - </b><span class="text-success">{$output['getsellercustomers'][$i]['order_status']}</span>
record;
      								}
      								else
      								if($output['getsellercustomers'][$i]['order_status']=="Shipped")
      								{
      									$record.=<<< record
      									<b>Order Status - </b><span class="text-info">{$output['getsellercustomers'][$i]['order_status']}</span>
record;
      								}
      								else
      								if($output['getsellercustomers'][$i]['order_status']=="Declined" || $output['getsellercustomers'][$i]['order_status']=="Returned")
      								{
      									$record.=<<< record
      									<b>Order Status - </b><span class="text-danger">{$output['getsellercustomers'][$i]['order_status']}</span>
record;
      								}

          							$record.=<<< record
          							</div>
record;
      								if($output['getsellercustomers'][$i]['order_type']=="Prepaid")
      								{
      								    $record.=<<< record
      								    <div class="col-md-1 mt-2 mt-md-0">
      								    <button class="btn btn-success" disabled>{$output['getsellercustomers'][$i]['order_type']}</button>
      								    </div>
record;
      								}
      								else
      								if($output['getsellercustomers'][$i]['order_type']=="COD")
      								{
      									$record.=<<< record
      									<div class="col-md-1 mt-2 mt-md-0">
      										<button class="btn btn-secondary" disabled>{$output['getsellercustomers'][$i]['order_type']}</button>
      									</div>
record;
      								}

          							$record.=<<< record
          								<div class="col-md-4 mt-2 mt-md-0 text-right">
          									<b>Net Amount</b>&nbsp;&nbsp;<i class="fas fa-rupee-sign text-secondary"></i> {$output['getsellercustomers'][$i]['net_amount']}
          									<form action="" method="post">
          										<input type="hidden" name="order_id" value="{$output['getsellercustomers'][$i]['order_id']}">
          										<button type="submit" class="btn btn-primary mt-2" name="show-suborders">View Order Details</button>
          									</form>
          								</div>
          							</div>
          							<hr>
record;
          							echo $record;
          						}
							?>
						</div>
					</div>
				</div>
<?php
			    }
			    else
				{
					echo '<div class="container">
							<div class="row mt-5">
								<div class="col-12 text-danger text-center mt-5">
									<h3>You don\'t have any past orders</h3>
								</div>
							</div>
						</div>';
				}
			}
			else
			{
				echo '<div class="container">
						<div class="row mt-5">
							<div class="col-12 text-danger text-center mt-5">
								<h3>No Records Found</h3>
							</div>
						</div>
					</div>';
			}
		}
		else
		if(isset($_POST['show-suborders']))
		{
			$data1['user_name']=$_REQUEST['s'];
			$data1['customer_mobile']=$_REQUEST['mobile'];
			$data1['order_id']=$_POST['order_id'];

			$url=DOMAIN.'/rest/seller/getCustomerOrderDetailsRest.php';
			$output=getRestApiResponse($url,$data1);

			if(isset($output['getsellercustomers']) && $output['getsellercustomers']['response_code']==200)
			{
				if(isset($output['getsellercustomers']['rows']) && $output['getsellercustomers']['rows']!=0)
			    {
?>
					<div class="container">
						<div class="row mt-4">
							<div class="col-12 text-center">
								<h5><span class="text-secondary">Orders Details For Order Id - <?php echo $_POST['order_id']; ?><span></h5>
							</div>
						</div>

						<div class="row mt-4">
							<div class="col-12 col-md-6">
								<b>Order Id - </b> <?php echo $output['getsellercustomers'][0]['order_id']; ?><br>
								<b>Order Date - </b> <?php echo $output['getsellercustomers'][0]['order_date']; ?><br>
								<b>Order Status - </b> 
								<?php 
									if($output['getsellercustomers']['order_status']=="Pending")
										echo '<span class="text-warning">'.$output['getsellercustomers']['order_status'].'</span>';
									else
									if($output['getsellercustomers']['order_status']=="Accepted" || $output['getsellercustomers']['order_status']=="Delivered")
										echo '<span class="text-success">'.$output['getsellercustomers']['order_status'].'</span>';
									else
									if($output['getsellercustomers']['order_status']=="Declined" || $output['getsellercustomers']['order_status']=="Returned")
										echo '<span class="text-danger">'.$output['getsellercustomers']['order_status'].'</span>';
									else
									if($output['getsellercustomers']['order_status']=="Shipped")
										echo '<span class="text-info">'.$output['getsellercustomers']['order_status'].'</span>';
								?>
							</div>
							<div class="col-12 col-md-6 text-md-right">
								<b>Total Items - </b><?php echo $output['getsellercustomers']['rows']; ?>
							</div>
						</div>

						<div class="row mt-3">
							<div class="col-12">
								<?php
									for($i=0;$i<$output['getsellercustomers']['rows'];$i++) 
	          						{
	          						    $record="";
	          						    
	          							$record=<<< record
	          							<div class="row mt-3">
	          								<div class="col-md-2">
	          									<img src="..{$output['getsellercustomers'][$i]['productimage']}" class="list-img">
	          								</div>
	          								<div class="col-md-6 mt-3">
	          									<b>Product Name - </b>{$output['getsellercustomers'][$i]['product_name']}<br>
	          									<b>Product Price - </b>{$output['getsellercustomers'][$i]['product_price']}
	          								</div>
	          								<div class="col-md-4 mt-md-3">
	          									<b>Order Quantity - </b>{$output['getsellercustomers'][$i]['order_quantity']}<br>
	          									<b>Order Amount Total - </b>{$output['getsellercustomers'][$i]['order_amount_total']}
	          								</div>
	          							</div>
	          							<hr>
record;
	          							echo $record;
	          						}
								?>

								<div class="row">
									<div class="col-12 col-md-6 mt-4">
										<h4 class="text-left">Customer Details</h4>
										 Name : <?php echo $output['getsellercustomers']['customer_name']; ?><br>
										 Mobile : <?php echo $output['getsellercustomers']['customer_mobile']; ?><br>
										 Email : <?php echo $output['getsellercustomers']['customer_email']; ?><br>
										 Address : <?php echo $output['getsellercustomers']['delivery_address']; ?><br>
										 Payment Type : <?php echo $output['getsellercustomers']['payment_method']; ?>
									</div>
									<div class="col-md-2 mt-4"></div>
									<div class="col-12 col-md-4 mt-4">
										<h4 class="text-left">Order Summary</h4>
											<div class="row">
												<div class="col-6">
													Item Total
												</div>
												<div class="col-6 text-right">
													<?php echo $output['getsellercustomers']['item_total']; ?>
												</div>
												<div class="col-6">
													Discount :
												</div>
												<div class="col-6 text-right">
													<?php echo $output['getsellercustomers']['discount']; ?>
												</div>
												<div class="col-6">
													Delivery Charge :
												</div>
												<div class="col-6 text-right">
													<?php echo $output['getsellercustomers']['delivery_charge']; ?>
												</div>
												<div class="col-6">
													Grand Total :
												</div>
												<div class="col-6 text-right text-right">
													<?php echo $output['getsellercustomers']['total_amount']; ?>
												</div>
											</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row mt-3">
							<div class="col-12 mt-5">
								<a href="javascript:history.go(-1)" class="btn btn-success">Back</a>
							</div>
						</div>
					</div>				
<?php
			    }
			}
			else
			{
				echo '<div class="container">
						<div class="row mt-5">
							<div class="col-12 text-danger text-center mt-5">
								<h3>No Details Found</h3>
							</div>
						</div>
						<div class="row mt-3">
							<div class="col-12">
								<a href="javascript:history.go(-1)" class="btn btn-success">Back</a>
							</div>
						</div>
					</div>';
			}
		}
	}
	else
	{
?>
		<div class="container">
			<div class="row mt-5">
				<div class="col-12 text-danger text-center mt-5">
					<h3>Invalid Request</h3>
				</div>
			</div>
		</div>
<?php
	}
?>
</body>
</html>