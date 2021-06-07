<?php include("../config/config.php"); ?>
<?php include("../config/".ENV."_config.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo APP; ?> - Order Confirmation</title>

  <link rel="stylesheet" href="../public/font-awesome/css/fontawesome.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="../public/font-awesome/css/all.min.css" rel="stylesheet" type="text/css">
    
  <link rel="stylesheet" href="../public/css/bootstrap/bootstrap.min.css">
</head>
<body>
<?php
	if(isset($_REQUEST['orderplaced']) && (isset($_REQUEST['sellerdomain']) && $_REQUEST['sellerdomain']!="") && (isset($_REQUEST['orderid']) && $_REQUEST['orderid']!=""))
	{
		if($_REQUEST['orderplaced']==1)
		{
			$sellerurl=DOMAIN.'/app/?s='.$_REQUEST['sellerdomain'];
			$oid="";
			
			if(isset($_REQUEST['orderid']))
				$oid=$_REQUEST['orderid'];
			
			echo '<div class="container-fluid">
					<div class="row mt-5">
						<div class="col-12 text-center text-success mt-3">
							<h2>Congratulations</h2>
							<h5>Your Order has been placed Successfully</h5>
						</div>
						<div class="col-12 text-center mt-5">
							<p>Your Order Id is &nbsp;<b>'.$oid.'</b></p>
						</div>
					</div>
					<hr>
					<div class="row mt-5">
						<div class="col-12 text-center">
							<a href="'.$sellerurl.'" class="btn btn-primary">Continue Shopping</a>
						</div>
					</div>
				</div>';
		}
	}
	else
	if(isset($_POST['orderId']) && isset($_POST['orderAmount']) && isset($_POST['txStatus']))	
	{
		$txStatus=$_POST['txStatus'];
		$referenceId=$_POST['referenceId'];
		$paymentMode=$_POST['paymentMode'];
		$orderAmount=$_POST['orderAmount'];
		$txTime=$_POST['txTime'];
		$orderId=$_POST['orderId'];

		$flag=true;
		
		$sellerurl="";

		if(isset($_REQUEST['sd']))
		{
			$sellerurl=DOMAIN.'/app/?s='.$_REQUEST['sd'];
		}

		if($txStatus=="SUCCESS")
		{
?>
			<div class="container-fluid">
				<div class="row mt-5">
					<div class="col-12 text-center text-success mt-3">
						<h2>Congratulations</h2>
						<h5>Your Order has been placed Successfully</h5>
					</div>
					<div class="col-12 text-center mt-5">
						<p>Your payment of&nbsp;<b><i class="fas fa-rupee-sign"></i>&nbsp;<?php echo $orderAmount; ?></b>&nbsp;is Successfull</p>
						<p>Your Order Id is &nbsp;<b><?php echo $orderId; ?></b></p>
					</div>
				</div>
				<hr>
				<div class="row mt-5">
					<div class="col-12 text-center">
						<a href="<?php echo $sellerurl; ?>" class="btn btn-primary">Continue Shopping</a>
					</div>
				</div>
			</div>

<?php
		}
		if($txStatus=='PENDING') 
		{
			$query="update basket_order set payment_reference='".$referenceId."',payment_gateway_status='".$txStatus."',payment_method='".$paymentMode."',amount_received='".$orderAmount."',payment_transaction_time='".$txTime."' where basket_order_id='".$orderId."'";
			$query=query($query);
			$result=confirm($query);
			if(!$result)
			{
				$flag=false;
			}
			if($flag)
			{
				commit();
				$temp=array();
				$temp['response_code']=400;
				$temp['response_desc']=$txStatus;
				close();
				?>
			<div class="container-fluid">
				<div class="row mt-5">
					<div class="col-12 text-center text-info mt-3">
						<h5>Thank you for placing your Order. Your payment status is reflected as PENDING and hence payment is being checked with your Bank. We will notify once payment is confirmed</h5>
					</div>
					<div class="col-12 text-center mt-5">
						<p>Your payment of&nbsp;<b><i class="fas fa-rupee-sign"></i>&nbsp;<?php echo $orderAmount; ?></b>&nbsp;is Pending</p>
						<p>Your Order Id is &nbsp;<b><?php echo $orderId; ?></b></p>
					</div>
				</div>
				<hr>
				<div class="row mt-5">
					<div class="col-12 text-center">
						<a href="<?php echo $sellerurl; ?>" class="btn btn-primary">Continue Shopping</a>
					</div>
				</div>
			</div>
		<?php	
			}
			else
			{
				rollback();
				$temp=arry();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Results";
				echo json_encode(array("checkout"=>$temp));
				close();
			}
    	
 		}
		if($txStatus=='FAILED') 
		{
			$query="update basket_order set payment_reference='".$referenceId."',payment_gateway_status='".$txStatus."',payment_method='".$paymentMode."',amount_received='".$orderAmount."',payment_transaction_time='".$txTime."' where basket_order_id='".$orderId."'";
			$query=query($query);
			$result=confirm($query);
			if(!$result)
			{
				$flag=false;
			}
			if($flag)
			{
				commit();
				$temp=array();
				$temp['response_code']=400;
				$temp['response_desc']=$txStatus;
				close();
				?>
			<div class="container-fluid">
				<div class="row mt-5">
					<div class="col-12 text-center text-danger mt-3">
						<h2>Oops!</h2>
						<h5>Your Order has been Failed</h5>
					</div>
					<div class="col-12 text-center mt-5">
						<p>Your payment of&nbsp;<b><i class="fas fa-rupee-sign"></i>&nbsp;<?php echo $orderAmount; ?></b>&nbsp;is Failed</p>
						<p>Your Order Id is &nbsp;<b><?php echo $orderId; ?></b></p>
					</div>
				</div>
				<hr>
				<div class="row mt-5">
					<div class="col-12 text-center">
						<a href="<?php echo $sellerurl; ?>" class="btn btn-primary">Continue Shopping</a>
					</div>
				</div>
			</div>
<?php
			}
			else
			{
				rollback();
				$temp=arry();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Results";
				close();
			}
		    	
		}
		if($txStatus=='CANCELLED') 
		{
			$query="update basket_order set payment_reference='".$referenceId."',payment_gateway_status='".$txStatus."',payment_method='".$paymentMode."',amount_received='".$orderAmount."',payment_transaction_time='".$txTime."' where basket_order_id='".$orderId."'";
			$query=query($query);
			$result=confirm($query);
			if(!$result)
			{
				$flag=false;
			}
			if($flag)
			{
				commit();
				$temp=array();
				$temp['response_code']=400;
				$temp['response_desc']=$txStatus;
				close();
				?>
			<div class="container-fluid">
				<div class="row mt-5">
					<div class="col-12 text-center text-danger mt-3">
						<h2>Oops!</h2>
						<h5>Your Order Is Cancelled</h5>
					</div>
					<div class="col-12 text-center mt-5">
						<p>Your payment of&nbsp;<b><i class="fas fa-rupee-sign"></i>&nbsp;<?php echo $orderAmount; ?></b>&nbsp;is Cancelled</p>
						<p>Your Order Id is &nbsp;<b><?php echo $orderId; ?></b></p>
					</div>
				</div>
				<hr>
				<div class="row mt-5">
					<div class="col-12 text-center">
						<a href="<?php echo $sellerurl; ?>" class="btn btn-primary">Continue Shopping</a>
					</div>
				</div>
			</div>
<?php
			}
			else
			{
				rollback();
				$temp=arry();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Results";
				close();
			}
		    	
		 }
		if($txStatus=='FLAGGED') 
		{
			$query="update basket_order set payment_reference='".$referenceId."',payment_gateway_status='".$txStatus."',payment_method='".$paymentMode."',amount_received='".$orderAmount."',payment_transaction_time='".$txTime."' where basket_order_id='".$orderId."'";
			$query=query($query);
			$result=confirm($query);
			if(!$result)
			{
				$flag=false;
			}
			if($flag)
			{
				commit();
				$temp=array();
				$temp['response_code']=400;
				close();
				?>
			<div class="container-fluid">
				<div class="row mt-5">
					<div class="col-12 text-center text-info mt-3">
						<h5>Thank you for placing your Order. We are checking your payment details with your Bank and will process the order as soon as the Transaction is successfu</h5>
					</div>
					<div class="col-12 text-center mt-5">
						<p>Your payment of&nbsp;<b><i class="fas fa-rupee-sign"></i>&nbsp;<?php echo $orderAmount; ?></b>&nbsp;is Flagged</p>
						<p>Your Order Id is &nbsp;<b><?php echo $orderId; ?></b></p>
					</div>
				</div>
				<hr>
				<div class="row mt-5">
					<div class="col-12 text-center">
						<a href="<?php echo $sellerurl; ?>" class="btn btn-primary">Continue Shopping</a>
					</div>
				</div>
			</div>

<?php
			}
			else
			{
				rollback();
				$temp=arry();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Results";
				close();
			}
		    	
		}
	 	else
	 	{
	 	}
	}
	else
	{
?>
		<div class="container-fluid">
			<div class="row mt-5">
				<div class="col-12 text-center text-danger mt-5">
					<h3>Invalid Request!</h3>
				</div>
			</div>
		</div>
<?php
	}
?>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

</body>
</html>