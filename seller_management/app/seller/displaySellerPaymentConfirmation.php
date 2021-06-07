<?php 
	require_once("../../config/config.php"); 
	require_once("../../config/".ENV."_config.php");
	require_once('header.php');
?>

<?php
	if(isset($_POST['orderId']) && isset($_POST['orderAmount']) && isset($_POST['txStatus']))	
	{
		$txStatus=$_POST['txStatus'];
		$referenceId=$_POST['referenceId'];
		$paymentMode=$_POST['paymentMode'];
		$orderAmount=$_POST['orderAmount'];
		$txTime=$_POST['txTime'];
		$orderId=$_POST['orderId'];

		$flag=true;

		$web=md5("web".$orderId);
		$android=md5("android".$orderId);

		$buttons="";

		if($android==$_REQUEST['sd'])
			$buttons="disabled";
		else
			$buttons="";

		$data['order_id']=$orderId;
		$url=DOMAIN.'/rest/seller/getSessionValuesRest.php';
		$output=getRestApiResponse($url,$data);
			
		if(isset($output['getvalues']) && $output['getvalues']['response_code']==200)
		{
			$_SESSION['user_id']=$output['getvalues'][0]['user_id'];
		  	$_SESSION['role']=$output['getvalues'][0]['role'];
		  	$_SESSION['username']=$output['getvalues'][0]['username'];
		  	$_SESSION['business_name']=$output['getvalues'][0]['business_name'];
		  	$_SESSION['mobile']=$output['getvalues'][0]['mobile'];
		  	$_SESSION['seller_image']=$output['getvalues'][0]['seller_image'];
		}
		else
			redirect("logout.php");

		if($txStatus=="SUCCESS")
		{
?>
			<div class="container-fluid">
				<div class="row mt-5">
					<div class="col-12 text-center text-info">
						<h3>Congratulations</h3>
						<h6>Payment Successful</h6>
					</div>
					<div class="col-12 text-center mt-3">
						<p><i class="fas fa-rupee-sign"></i>&nbsp;<?php echo $orderAmount; ?>&nbsp;Added to your Wallet</p>
					</div>
				</div>
				<hr>
				<?php
					if($buttons!="disabled")
					{
				?>
				<div class="row mt-5">
					<div class="col-6 text-right">
						<a href="<?php echo DOMAIN.'/app/seller/displayAddWithdrawWalletMoney.php?addmoney'; ?>" class="btn btn-primary">Add More Money to Wallet?</a>
					</div>
					<div class="col-6">
						<a href="<?php echo DOMAIN.'/app/seller/displaySellerWallet.php'; ?>" class="btn btn-primary">Back To Wallet</a>
					</div>
				</div>
				<?php
					}
				?>
			</div>
<?php
		}
		if($txStatus=='PENDING') 
		{
// Reject this call
			$query="update wallet_order set payment_reference='".$referenceId."',gateway_response_status='".$txStatus."',payment_date_time='".$txTime."' where basket_order_id='".$orderId."'";
			//echo $query;
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
				echo json_encode(array("checkout"=>$temp));
				close();
				exit();
				?>
			<div class="container-fluid">
				<div class="row mt-5">
					<div class="col-12 text-center text-info mt-3">
						<h5>Thank you for adding money to Wallet. Your payment status is reflected as PENDING and hence payment is being checked with your Bank. We will notify once payment is confirmed.</h5>
					</div>
					<div class="col-12 text-center mt-5">
						<p>Your Transaction amount is&nbsp;<b><i class="fas fa-rupee-sign"></i>&nbsp;<?php echo $orderAmount; ?>
						<p>Your Transaction Reference is &nbsp;<b><?php echo $orderId; ?></b></p>
					</div>
				</div>
				<hr>
				<div class="row mt-5">
					<div class="col-6 text-right">
						<a href="<?php echo DOMAIN.'/app/seller/displayAddWithdrawWalletMoney.php?addmoney'; ?>" class="btn btn-primary">Add More Money to Wallet?</a>
					</div>
					<div class="col-6">
						<a href="<?php echo DOMAIN.'/app/seller/displaySellerWallet.php'; ?>" class="btn btn-primary">Back To Wallet</a>
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
				exit();
			}
    	
 		}
		if($txStatus=='FAILED') 
		{
		// Reject this call
			$query="update wallet_order set payment_reference='".$referenceId."',gateway_response_status='".$txStatus."',payment_date_time='".$txTime."' where basket_order_id='".$orderId."'";
			//echo $query;
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
				echo json_encode(array("checkout"=>$temp));
				close();
				exit();
				?>
			<div class="container-fluid">
				<div class="row mt-5">
					<div class="col-12 text-center text-danger mt-3">	
						<h5>Sorry, Your Transaction has been failed. Please try again</h5>
					</div>
					<div class="col-12 text-center mt-5">
						<p>Your Transaction amount is&nbsp;<b><i class="fas fa-rupee-sign"></i>&nbsp;<?php echo $orderAmount; ?>
						<p>Your Transaction Reference is &nbsp;<b><?php echo $orderId; ?></b></p>
					</div>
				</div>
				<hr>
				<div class="row mt-5">
					<div class="col-6 text-right">
						<a href="<?php echo DOMAIN.'/app/seller/displayAddWithdrawWalletMoney.php?addmoney'; ?>" class="btn btn-primary">Add More Money to Wallet?</a>
					</div>
					<div class="col-6">
						<a href="<?php echo DOMAIN.'/app/seller/displaySellerWallet.php'; ?>" class="btn btn-primary">Back To Wallet</a>
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
				exit();
			}
		    	
		}
		if($txStatus=='CANCELLED') 
		{
			// Reject this call
			$query="update wallet_order set payment_reference='".$referenceId."',gateway_response_status='".$txStatus."',payment_date_time='".$txTime."' where basket_order_id='".$orderId."'";
			//echo $query;
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
				echo json_encode(array("checkout"=>$temp));
				close();
				exit();
		?>
			<div class="container-fluid">
				<div class="row mt-5">
					<div class="col-12 text-center text-danger mt-3">					
						<h5>Your Wallet Recharge Is Cancelled</h5>
					</div>
					<div class="col-12 text-center mt-5">
						<p>Your Transaction amount is&nbsp;<b><i class="fas fa-rupee-sign"></i>&nbsp;<?php echo $orderAmount; ?>
						<p>Your Transaction Reference is &nbsp;<b><?php echo $orderId; ?></b></p>
					</div>
				</div>
				<hr>
				<div class="row mt-5">
					<div class="col-6 text-right">
						<a href="<?php echo DOMAIN.'/app/seller/displayAddWithdrawWalletMoney.php?addmoney'; ?>" class="btn btn-primary">Add More Money to Wallet?</a>
					</div>
					<div class="col-6">
						<a href="<?php echo DOMAIN.'/app/seller/displaySellerWallet.php'; ?>" class="btn btn-primary">Back To Wallet</a>
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
				exit();
			}
		    	
		 }
		if($txStatus=='FLAGGED') 
		{
		// Reject this call
			$query="update wallet_order set payment_reference='".$referenceId."',gateway_response_status='".$txStatus."',payment_date_time='".$txTime."' where basket_order_id='".$orderId."'";
			//echo $query;
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
				echo json_encode(array("checkout"=>$temp));
				close();
				exit();
				?>
			<div class="container-fluid">
				<div class="row mt-5">
					<div class="col-12 text-center text-info mt-3">
						<h5>Thank you for adding money to your wallet. We are checking your payment details with your Bank and will process the order as soon as the Transaction is successful</h5>
					</div>
					<div class="col-12 text-center mt-5">
						<p>Your Transaction amount is&nbsp;<b><i class="fas fa-rupee-sign"></i>&nbsp;<?php echo $orderAmount; ?>
						<p>Your Transaction Reference is &nbsp;<b><?php echo $orderId; ?></b></p>
					</div>
				</div>
				<hr>
				<div class="row mt-5">
					<div class="col-6 text-right">
						<a href="<?php echo DOMAIN.'/app/seller/displayAddWithdrawWalletMoney.php?addmoney'; ?>" class="btn btn-primary">Add More Money to Wallet?</a>
					</div>
					<div class="col-6">
						<a href="<?php echo DOMAIN.'/app/seller/displaySellerWallet.php'; ?>" class="btn btn-primary">Back To Wallet</a>
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
				exit();
			}
		    	
		 }
		 else
		 {
		 }
	}
?>
