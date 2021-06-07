<?php include("navigation.php"); ?>

<?php
  if(!(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2))
    redirect("login.php");
?>

<?php 
if(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2)
{
	$showinformation=0;
	$message="";

	if(isset($_POST['setorderstatus']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['order_id']=$_POST['oid'];
		$data['order_type']=$_POST['order_type'];
		$data['order_status']=$_POST['order_status'];

		$url='';

		if($_POST['order_status']=="Accepted")
		{
			$url=DOMAIN.'/rest/seller/UpdateOrderStatusRest.php';
			$output=getRestApiResponse($url,$data);

			if(isset($output['seller']) && $output['seller']['response_code']==200)
			{
				$showinformation=1;
				$message='<p class="text-success">Order '.$_POST['order_status'].' Successfully</p>';
			}
			else
			{
				$showinformation=1;
				$message='<p class="text-danger">Unable to perform this operation</p>';
			}
		}
		else
		if($_POST['order_status']=="Declined")
		{
			$url=DOMAIN.'/rest/seller/changeOrderStatusToRejectedRest.php';
			$output=getRestApiResponse($url,$data);

			if(isset($output['changestatustoDeclined']) && $output['changestatustoDeclined']['response_code']==200)
			{
				$showinformation=1;
				$message='<p class="text-success">Order '.$_POST['order_status'].' Successfully</p>';
			}
			else
			{
				$showinformation=1;
				$message='<p class="text-danger">Unable to perform this operation</p>';
			}
		}
		else
		if($_POST['order_status']=="Shipped")
		{
			$url=DOMAIN.'/rest/seller/changeOrderStatusToShippedRest.php';
			$output=getRestApiResponse($url,$data);

			if(isset($output['changestatustoshipped']) && $output['changestatustoshipped']['response_code']==200)
			{
				$showinformation=1;
				$message='<p class="text-success">Order '.$_POST['order_status'].' Successfully</p>';
			}
			else if(isset($output['changestatustoshipped']) && $output['changestatustoshipped']['response_code']==405)
			{
				$showinformation=1;
				$message='<p class="text-danger">'.$output['changestatustoshipped']['response_desc'].'</p>';
			}
			else
			{
				$showinformation=1;
				$message='<p class="text-danger">Unable to perform this operation</p>';
			}
		}
		else
		if($_POST['order_status']=="Delivered")
		{
			$url=DOMAIN.'/rest/seller/changeOrderStatusToDeliveredRest.php';
			$output=getRestApiResponse($url,$data);

			if(isset($output['changestatustodelivered']) && $output['changestatustodelivered']['response_code']==200)
			{
				$showinformation=1;
				$message='<p class="text-success">Order '.$_POST['order_status'].' Successfully</p>';
			}
			else
			{
				$showinformation=1;
				$message='<p class="text-danger">Unable to perform this operation</p>';
			}
		}
		else
		if($_POST['order_status']=="Returned")
		{
			$url=DOMAIN.'/rest/seller/changeOrderStatusToReturnedRest.php';
			$output=getRestApiResponse($url,$data);

			if(isset($output['changestatustoreturned']) && $output['changestatustoreturned']['response_code']==200)
			{
				$showinformation=1;
				$message='<p class="text-success">Order '.$_POST['order_status'].' Successfully</p>';
			}
			else
			{
				$showinformation=1;
				$message='<p class="text-danger">Unable to perform this operation</p>';
			}
		}
	}

	if(isset($_POST['orderitems']) && isset($_POST['oid']) && isset($_SESSION['user_id']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['order_id']=$_POST['oid'];

		$url=DOMAIN.'/rest/seller/getBuyerOrderDetailsRest.php';
		$buyerorders=getRestApiResponse($url,$data);

		if(isset($buyerorders['getorders']) && count($buyerorders['getorders'])>2)
		{
?>
	<div class="container-fluid">	
		<div class="row mt-4">
			<div class="col-6">
				<b>Order Id : </b> <?php echo $buyerorders['getorders']['order_id']; ?><br>
				<b>Order Date : </b> <?php echo $buyerorders['getorders']['order_date']; ?><br>
				<b>Order Status : </b> 
				<?php 
					if($buyerorders['getorders']['order_status']=="Pending")
						echo '<span class="text-warning">'.$buyerorders['getorders']['order_status'].'</span>';
					else
					if($buyerorders['getorders']['order_status']=="Accepted" || $buyerorders['getorders']['order_status']=="Delivered")
						echo '<span class="text-success">'.$buyerorders['getorders']['order_status'].'</span>';
					else
					if($buyerorders['getorders']['order_status']=="Declined" || $buyerorders['getorders']['order_status']=="Returned")
						echo '<span class="text-danger">'.$buyerorders['getorders']['order_status'].'</span>';
					else
					if($buyerorders['getorders']['order_status']=="Shipped")
						echo '<span class="text-info">'.$buyerorders['getorders']['order_status'].'</span>';
				?>
			</div>
			<div class="col-6 text-right">
				<b>Total Items :</b> <?php echo $buyerorders['getorders']['rows']; ?><br>
			</div>
		</div>
		<div class="row mt-5">
			<div class="col-12 table-responsive">
			<table class="table table-hover table-bordered text-center pl-0" width="100%">
				<thead>
					<tr>
						<th>Product Image</th>
						<th>Product Name</th>
						<th>Quantity</th>
						<th>Price</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						if(isset($buyerorders['getorders']['rows']) && $buyerorders['getorders']['rows']!=0)
						{
							$seller_to_root=SELLER_TO_ROOT;
							for($i=0;$i<$buyerorders['getorders']['rows'];$i++)
							{
									$record=<<< record
									<tr>
									<td><img src="{$seller_to_root}{$buyerorders['getorders'][$i]['productimage']}" class="list-image"></td>
									<td>{$buyerorders['getorders'][$i]['product_name']}</td>
									<td>{$buyerorders['getorders'][$i]['order_quantity']}</td>
									<td>{$buyerorders['getorders'][$i]['product_price']}</td>
									<td>{$buyerorders['getorders'][$i]['order_amount_total']}</td>
								</tr>
record;
								echo $record;
							}
						}
					?>
				</tbody>
			</table>
		</div>
		</div>

		<div class="row">
			<div class="col-12 col-md-6 mt-4">
				<h4 class="text-left">Buyer Details</h4>
				 Name : <?php echo $buyerorders['getorders']['customer_name']; ?><br>
				 Mobile : <?php echo $buyerorders['getorders']['customer_mobile']; ?><br>
				 Email : <?php echo $buyerorders['getorders']['customer_email']; ?><br>
				 Address : <?php echo $buyerorders['getorders']['delivery_address']; ?><br>
				 Payment Type : <?php echo $buyerorders['getorders']['payment_method']; ?>
			</div>
			<div class="col-md-2 mt-4"></div>
			<div class="col-12 col-md-4 mt-4">
				<h4 class="text-left">Order Summary</h4>
					<div class="row">
						<div class="col-6">
							Item Total
						</div>
						<div class="col-6 text-right">
							<?php echo $buyerorders['getorders']['item_total']; ?>
						</div>
						<div class="col-6">
							Discount :
						</div>
						<div class="col-6 text-right">
							<?php echo $buyerorders['getorders']['discount']; ?>
						</div>
						<div class="col-6">
							Delivery Charge :
						</div>
						<div class="col-6 text-right">
							<?php echo $buyerorders['getorders']['delivery_charge']; ?>
						</div>
						<div class="col-6">
							Grand Total :
						</div>
						<div class="col-6 text-right text-right">
							<?php echo $buyerorders['getorders']['total_amount']; ?>
						</div>
					</div>
			</div>
		</div>

		<div class="row mt-2">
		<?php
			$record="";
			if($buyerorders['getorders']['order_status']=="Pending")
			{
				$record=<<< record
				<div class="col-6 text-right">
					<button class="btn btn-success enable-order-confirmation-modal" order_id="{$buyerorders['getorders']['order_id']}" order_type="{$buyerorders['getorders']['order_type']}" order_status="Accepted" order_info="Do you really want to accept this order?" order_button="Accept">Accept</button>
				</div>
				<div class="col-6 text-left">
					<button class="btn btn-danger enable-order-confirmation-modal" order_id="{$buyerorders['getorders']['order_id']}" order_type="{$buyerorders['getorders']['order_type']}" order_status="Declined" order_info="Do you really want to decline this order?" order_button="Decline">Decline</button>
				</div>
record;
			}
			else if($buyerorders['getorders']['order_status']=="Accepted")
			{
				$record=<<< record
				<div class="col-6 text-right">
					<button class="btn btn-info enable-order-confirmation-modal" order_id="{$buyerorders['getorders']['order_id']}" order_type="{$buyerorders['getorders']['order_type']}" order_status="Shipped" order_info="Do you really want to ship this order?" order_button="Ship">Ship</button>
				</div>
				<div class="col-6 text-left">
					<button class="btn btn-danger enable-order-confirmation-modal" order_id="{$buyerorders['getorders']['order_id']}" order_type="{$buyerorders['getorders']['order_type']}" order_status="Declined" order_info="Do you really want to decline this order?" order_button="Decline">Decline</button>
				</div>
record;
			}
			else if($buyerorders['getorders']['order_status']=="Shipped")
			{
				$record=<<< record
				<div class="col-6 text-right">
					<button class="btn btn-success enable-order-confirmation-modal" order_id="{$buyerorders['getorders']['order_id']}" order_type="{$buyerorders['getorders']['order_type']}" order_status="Delivered" order_info="Do you really want to deliver this order?" order_button="Deliver">Deliver</button>
				</div>
				<div class="col-6 text-left">
					<button class="btn btn-danger enable-order-confirmation-modal" order_id="{$buyerorders['getorders']['order_id']}" order_type="{$buyerorders['getorders']['order_type']}" order_status="Returned" order_info="Do you really want to return this order?" order_button="Return">Return</button>
				</div>
record;
			}

			echo $record;
		?>
		</div>
		<div class="row mt-5">
			<div class="col-12">
				<a href="javascript:history.go(-1)"><button class="btn btn-success">Back</button></a>
			</div>
		</div>
	</div>

	<script>
		$(".enable-order-confirmation-modal").on("click",
			function(){
				oid=$(this).attr('order_id');
				otype=$(this).attr('order_type');
				ostatus=$(this).attr('order_status');
				oinfo=$(this).attr('order_info');
				obutton=$(this).attr('order_button');

				$("#setoid").val(oid);
				$("#setotype").val(otype);
				$("#setostatus").val(ostatus);

				$("#order-info").html(oinfo);
				$("#order-button").text(obutton);
				$("#order-confirmation-modal").modal('show');
		});
	</script>
<?php
		}
	}
	else
	{
?>

<div class="container-fluid">	

<?php 
	// Setting array to pass on end point
	$data1['user_id']=$_SESSION['user_id'];
	$data1['start']=0;

	if(isset($_SESSION['pages']))
		$data1['end']=$_SESSION['pages'];
	else
		$data1['end']=10;

	if(isset($_GET['orderstatus']))
		$data1['order_status']=$_GET['orderstatus'];
	else
	if(isset($_REQUEST['customer_orders']))
		$data1['customer_mobile']=$_POST['customer_mobile'];
	else
	if(isset($_REQUEST['ordertype']) && ($_REQUEST['ordertype']=="Prepaid" || $_REQUEST['ordertype']=="COD"))
	{
		$data1['order_type']=$_REQUEST['ordertype'];
	}
	// else
	// if(isset($_REQUEST['ordertype']) && $_REQUEST['ordertype']=="All")

	$url=DOMAIN.'/rest/seller/getSellerOrderdetailsRest.php';

	$output=getRestApiResponse($url,$data1);

	if(isset($output['getorders']) && count($output['getorders'])>2)
	{
?>
	<div class="row mt-4">
		<div class="col-7 col-md-4">
			Show
			<select id="pages">
				<option value="<?php if(isset($_SESSION['pages'])) echo $_SESSION['pages']; ?>">
					<?php if(isset($_SESSION['pages'])) echo $_SESSION['pages']; ?>
				</option>
				<option value="1">1</option>
				<option value="10">10</option>
				<option value="25">25</option>
				<option value="50">50</option>
				<option value="100">100</option>
			</select>
			Entries
		</div>
	</div>
	<div class="row mt-3">
		<div class="col-12 col-md-6 text-center">
			<div class="row">
				<div class="col-4 mt-2">
					<div class="form-check-inline">
		              <label class="form-check-label">
		                <input type="radio" class="form-check-input searchbyordertype" name="ordertype" value="Prepaid" required autocomplete="off" <?php if(isset($_REQUEST['ordertype']) && $_REQUEST['ordertype']=="Prepaid") echo "checked"; ?>>&nbsp;Prepaid
		              </label>
		            </div>
		        </div>
		        <div class="col-4 mt-2">
					<div class="form-check-inline">
		              <label class="form-check-label">
		                <input type="radio" class="form-check-input" name="ordertype" value="COD" required autocomplete="off" <?php if(isset($_REQUEST['ordertype']) && $_REQUEST['ordertype']=="COD") echo "checked"; ?>>&nbsp;COD
		              </label>
		            </div>
		        </div>
		        <div class="col-4 mt-2">
					<div class="form-check-inline">
		              <label class="form-check-label">
		                <input type="radio" class="form-check-input" name="ordertype" value="All" required autocomplete="off" <?php if(isset($_REQUEST['ordertype']) && $_REQUEST['ordertype']=="All") echo "checked"; ?>>&nbsp;All
		              </label>
		            </div>
		        </div>
		    </div>
		</div>
		<div class="col-6 col-md-3 mt-3 mt-md-0">
			<div class="input-group">
		    	<select class="form-control" id="searchbystatusfield">
		    		<option value="">Search By Status</option>
		    		<option value="Pending">Pending</option>
		    		<option value="Accepted">Accepted</option>
		    		<option value="Declined">Declined</option>
		    		<option value="Shipped">Shipped</option>
		    		<option value="Delivered">Delivered</option>
		    		<option value="Returned">Returned</option>
		    		<option value="Cancelled">Cancelled</option>
		    	</select>
			    <div class="input-group-append">
			      <button class="btn btn-secondary" type="button" id="searchbystatus">
			        <i class="fa fa-search"></i>
			      </button>
			    </div>
		  	</div>
		</div>
		<div class="col-6 col-md-3 mt-3 mt-md-0">
			<div class="input-group">
		    	<input type="date" class="form-control" id="searchbydatefield">
			    <div class="input-group-append">
			      <button class="btn btn-secondary" type="button" id="searchbydate">
			        <i class="fa fa-search"></i>
			      </button>
			    </div>
		  	</div>
		</div>
	</div>

	<div class="row mt-4">
		<div class="col-12 table-responsive">
			<table class="table table-hover table-bordered text-center w-auto table-sm">
				<thead>
					<tr>
						<th>Order Id</th>
						<th>Order Type</th>
						<th>Customer Name</th>
						<th>No. Of Items</th>
						<th>Cart Total</th>
						<th>DateTime</th>
						<th>Status</th>
						<th colspan="3">Action</th>
					</tr>
				</thead>
				<tbody id="order_body">
					<?php 
						if(isset($output['getorders']['rows']) && $output['getorders']['rows']!=0)
						{
							for($i=0;$i<$output['getorders']['rows'];$i++)
							{
								$record="";
								$record.=<<< record
								<tr>
								<td>{$output['getorders'][$i]['basket_order_id']}</td>
								<td>{$output['getorders'][$i]['order_type']}</td>
								<td>{$output['getorders'][$i]['customer_name']}</td>
								<td>{$output['getorders'][$i]['total_items']}</td>
								<td>{$output['getorders'][$i]['net_amount']}</td>
								<td>{$output['getorders'][$i]['order_date']}</td>
record;

								if($output['getorders'][$i]['order_status']=="Pending")
								{
									$record.=<<< record
									<td><p class="text text-warning">{$output['getorders'][$i]['order_status']}</p></td>
									<td>
										<form action="displaySellerOrders.php" method="post">
											<input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
											<input type="hidden" name="orderdate" value="{$output['getorders'][$i]['order_date']}">
											<button type="submit" name="orderitems" class="btn btn-primary">View</button>
										</form>
									</td>
									<td>
										<button class="btn btn-success enable-order-confirmation-modal" order_id="{$output['getorders'][$i]['basket_order_id']}" order_type="{$output['getorders'][$i]['order_type']}" order_status="Accepted" order_info="Do you really want to accept this order?" order_button="Accept">Accept</button>
									</td>
									<td>
										<button class="btn btn-danger enable-order-confirmation-modal" order_id="{$output['getorders'][$i]['basket_order_id']}" order_type="{$output['getorders'][$i]['order_type']}" order_status="Declined" order_info="Do you really want to decline this order?" order_button="Decline">Decline</button>
									</td>
record;
								}
								else if($output['getorders'][$i]['order_status']=="Accepted")
								{
									$record.=<<< record
									<td><p class="text text-success">{$output['getorders'][$i]['order_status']}</p></td>
									<td>
									<form action="displaySellerOrders.php" method="post">
										<input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
										<input type="hidden" name="orderdate" value="{$output['getorders'][$i]['order_date']}">
										<button type="submit" name="orderitems" class="btn btn-primary">View</button>
									</form>
									</td>
									<td>
										<button class="btn btn-info enable-order-confirmation-modal" order_id="{$output['getorders'][$i]['basket_order_id']}" order_type="{$output['getorders'][$i]['order_type']}" order_status="Shipped" order_info="Do you really want to ship this order?" order_button="Ship">Ship</button>
									</td>
									<td>
										<button class="btn btn-danger enable-order-confirmation-modal" order_id="{$output['getorders'][$i]['basket_order_id']}" order_type="{$output['getorders'][$i]['order_type']}" order_status="Declined" order_info="Do you really want to decline this order?" order_button="Decline">Decline</button>
									</td>
record;
								}
								else if($output['getorders'][$i]['order_status']=="Declined")
								{
									$record.=<<< record
									<td><p class="text text-danger">{$output['getorders'][$i]['order_status']}</p></td>
									<td>
									<form action="displaySellerOrders.php" method="post">
										<input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
										<input type="hidden" name="orderdate" value="{$output['getorders'][$i]['order_date']}">
										<button type="submit" name="orderitems" class="btn btn-primary">View</button>
									</form>
									</td>
									<td></td><td></td>
record;
								}
								else if($output['getorders'][$i]['order_status']=="Shipped")
								{
									$record.=<<< record
									<td><p class="text text-info">{$output['getorders'][$i]['order_status']}</p></td>
									<td>
									<form action="displaySellerOrders.php" method="post">
										<input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
										<input type="hidden" name="orderdate" value="{$output['getorders'][$i]['order_date']}">
										<button type="submit" name="orderitems" class="btn btn-primary">View</button>
									</form>
									</td>
									<td>
										<button class="btn btn-success enable-order-confirmation-modal" order_id="{$output['getorders'][$i]['basket_order_id']}" order_type="{$output['getorders'][$i]['order_type']}" order_status="Delivered" order_info="Do you really want to deliver this order?" order_button="Deliver">Deliver</button>
									</td>
									<td>
										<button class="btn btn-danger enable-order-confirmation-modal" order_id="{$output['getorders'][$i]['basket_order_id']}" order_type="{$output['getorders'][$i]['order_type']}" order_status="Returned" order_info="Do you really want to return this order?" order_button="Return">Return</button>
									</td>
record;
								}
								else if($output['getorders'][$i]['order_status']=="Delivered")
								{
									$record.=<<< record
									<td><p class="text text-success">{$output['getorders'][$i]['order_status']}</p></td>
									<td>
									<form action="displaySellerOrders.php" method="post">
										<input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
										<input type="hidden" name="orderdate" value="{$output['getorders'][$i]['order_date']}">
										<button type="submit" name="orderitems" class="btn btn-primary">View</button>
									</form>
									</td>
									<td></td><td></td>
record;
								}
								else
								{
									$record.=<<< record
									<td><p class="text text-danger">{$output['getorders'][$i]['order_status']}</p></td>
									<td>
									<form action="displaySellerOrders.php" method="post">
										<input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
										<input type="hidden" name="orderdate" value="{$output['getorders'][$i]['order_date']}">
										<button type="submit" name="orderitems" class="btn btn-primary">View</button>
									</form>
									</td>
									<td></td><td></td>
record;
								}

								$record.=<<< record
							</tr>
record;
								echo $record;
							}
						}
					?>
<script>
	$(".enable-order-confirmation-modal").on("click",
		function(){
			oid=$(this).attr('order_id');
			otype=$(this).attr('order_type');
			ostatus=$(this).attr('order_status');
			oinfo=$(this).attr('order_info');
			obutton=$(this).attr('order_button');

			$("#setoid").val(oid);
			$("#setotype").val(otype);
			$("#setostatus").val(ostatus);

			$("#order-info").html(oinfo);
			$("#order-button").text(obutton);
			$("#order-confirmation-modal").modal('show');
	});
</script>
				</tbody>
			</table>
		</div>
	</div>

	<?php 
		// Setting Pagination
		// $sql="select * from promocodes where seller_id='".$sid."'";
		// setupPagination($sql);

		if(isset($_GET['orderstatus']))
      		$qp=query("select * from basket_order where seller_id='".$_SESSION['user_id']."' and order_status='".$_GET['orderstatus']."'");
      	else
      	if(isset($_POST['customer_orders']) && isset($_POST['customer_mobile']))
      		$qp=query("select * from basket_order where seller_id='".$_SESSION['user_id']."' and customer_mobile='".$_POST['customer_mobile']."'");
      	else
      		$qp=query("select * from basket_order where seller_id='".$_SESSION['user_id']."' AND order_status NOT IN('Draft', 'deleted')");

        confirm($qp);

        if(mysqli_num_rows($qp)!=0)
        {
            $p=intval(mysqli_num_rows($qp));

            if(isset($_SESSION['pages']))
            	$n=intval($p/$_SESSION['pages']);
            else
            	$n=intval($p/10);

            if(isset($_SESSION['pages']))
            	$rem=intval($p%$_SESSION['pages']);
            else
            	$rem=intval($p%10);

            $pages=$n;

            if($rem!=0)
              $pages+=1;

            $i=1;
            $start=0;

            if(isset($_SESSION['pages']))
            	$end=$_SESSION['pages'];
            else
            	$end=10;
            // Pagination
            echo "<div class='row mt-2'>
                    <div class='col-md-12'>
                      <ul class='pagination justify-content-center'>
                      <li id='prev' class='page-item'><span style='cursor:pointer;' class='page-link'><i class='fa fa-backward'></i></span></li>
                      <li class='page-item active page' start='$start' end='$end' id='button$i'><span style='cursor:pointer;' class='page-link'>$i</span></li>";

            if(isset($_SESSION['pages']))
            	$start+=$_SESSION['pages'];
            else
            	$start+=10;

            for($i=2;$i<=$pages;$i++)
            {
              echo "<li class='page-item page' start='$start' end='$end' id='button$i'><span style='cursor:pointer;' class='page-link'>$i</span></li>";

              if(isset($_SESSION['pages']))
              	$start+=$_SESSION['pages'];
              else
              	$start+=10;
            }
            echo "<li id='next' class='page-item'><span style='cursor:pointer;' class='page-link'><i class='fa fa-forward'></i></span></li>";
            echo '</ul>
                    </div>
                      </div>';
        }
    ?>

    <?php
	}		// if for table
	else
	{
		if(isset($_GET['orderstatus']))
		{
			echo '<div class="row mt-5">
				<div class="col-12 text-center text-danger mt-3">
					<h5>You Don\'t Have Any '.$_GET['orderstatus'].' Order Yet!</h5>
				</div>
			</div>';
		}
		else
		{
			echo '<div class="row mt-5">
				<div class="col-12 text-center text-danger mt-3">
					<h5>You Don\'t Have Any Order Yet!</h5>
				</div>
			</div>';
		}
	}
	?>

</div>

<?php
	}
?>

<!-- Modal for confirmation -->
<div class="modal fade" id="order-confirmation-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-question text-danger"></i>&nbsp;Order Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="order-info">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
         <form action="displaySellerOrders.php" method="post">
         	<input type="hidden" name="oid" value="" id="setoid">
         	<input type="hidden" name="order_type" value="" id="setotype">
         	<input type="hidden" name="order_status" value="" id="setostatus">
        	<button type="submit" name="setorderstatus" class="btn btn-danger text-white" id="order-button"></button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
	if($showinformation==1)
		echo '<script>
				$("#information").html(\''.$message.'\');
				$("#information-modal").modal("show");
			</script>';
}
else
	echo "Login First";
?>


        </div>
        <!-- content end -->
    </div>
    <!-- page content -->
</div>
<!-- page wrapper end -->

<?php include("footer.php"); ?>

<script>
  $(".page").on("click",
    function()
    {
      start=$(this).attr("start");
      end=$(this).attr("end");

      $(".page").removeClass("active");
      $(this).addClass("active");
      
      $.get("changeSellerOrderList.php?start="+start+"&end="+end,
        function(data,status)
        {
          $("#order_body").html(data);
        });
    });

    pageNumber=5;     // for tracing five pages at a perticular time.
    totalPages=<?php echo $pages; ?>;

    $(".page").css("display","none");           
    $("#prev").css("display","inline-block");
    $("#button1").css("display","inline-block");
    $("#button2").css("display","inline-block");
    $("#button3").css("display","inline-block");
    $("#button4").css("display","inline-block");
    $("#button5").css("display","inline-block");
    $("#next").css("display","inline-block");

   	$("#prev").click(function(){
      if(pageNumber==5) // means no page before page 1 show return will end the script.
        return 0;

   		$(".page").css("display","none"); // again all pages to hide but showing previous five pages
    	pageNumber-=5;
     	$("#button"+(pageNumber-4)).css("display","inline-block");
     	$("#button"+(pageNumber-3)).css("display","inline-block");
     	$("#button"+(pageNumber-2)).css("display","inline-block");
     	$("#button"+(pageNumber-1)).css("display","inline-block");
     	$("#button"+pageNumber).css("display","inline-block");
    });

    $("#next").click(function(){
        //if(pageNumber><?php //echo floor($pages/10)/*-1*/; ?>) // means no page after page ceil($pages/5)-1 to show. return will end the script. devide by 5 because we are showing 5 records in one page
        if(pageNumber>=totalPages)
          return 0;
          
        $(".page").css("display","none");
        $("#button"+(pageNumber+1)).css("display","inline-block");
        $("#button"+(pageNumber+2)).css("display","inline-block");
        $("#button"+(pageNumber+3)).css("display","inline-block");
        $("#button"+(pageNumber+4)).css("display","inline-block");
        $("#button"+(pageNumber+5)).css("display","inline-block");
        pageNumber+=5;
      });
</script>

<script>
	$('#searchbystatusfield').keypress(
		function(event){
        if(event.which==13)
        {
            $('#searchbystatus').click();
        }
    });

	$("#searchbystatus").on("click",
		function()
		{
			orderstatus=$('#searchbystatusfield').val();
			
			$.get("searchOrder.php?orderstatus="+orderstatus,
				function(data,status)
				{
					$("#order_body").html(data);
				});
		});

	$('#searchbydatefield').keypress(
		function(event){
        if(event.which==13)
        {
            $('#searchbydate').click();
        }
    });

	$("#searchbydate").on("click",
		function()
		{
			orderdate=$("#searchbydatefield").val();
			
			$.get("searchOrder.php?orderdate="+orderdate,
				function(data,status)
				{
					$("#order_body").html(data);
				});
		});

	$('input:radio[name=ordertype]').click(
		function()
		{
			type=$(this).val();
			location.href="displaySellerOrders.php?ordertype="+type;
		});
</script>

<script>
	$("#pages").change(function()
	{
		pages=$(this).val();
		$.get("../recordperpage.php?pages="+pages,
			function(data,status)
			{

			});
		location.href="displaySellerOrders.php";
	});
</script>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

</body>
</html>
