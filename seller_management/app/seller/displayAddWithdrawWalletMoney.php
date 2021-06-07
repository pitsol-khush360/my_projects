<?php include("navigation.php"); ?>

<?php
  if(!(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2))
    redirect("login.php");
?>

<?php 
if(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2)
{
	if(isset($_POST['add-money']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['wallet_opening_balance']=$_POST['opening_balance'];
		$data['amount']=$_POST['amount'];
		$data['customer_name']=$_SESSION['username'];
		$data['customer_mobile']=$_SESSION['mobile'];
		$data['customer_email']='data123@gmail.com';

		$temp=explode("/",$_SESSION['seller_image']);

		$merge=$_SESSION['user_id']."#".$_SESSION['role']."#".$_SESSION['username']."#".$_SESSION['business_name']."#".$_SESSION['mobile']."#".$_SESSION['seller_image'];

		//print_r($merge); echo "<br><br>";

		$ciphering = "AES-128-CTR";
	    $iv_length = openssl_cipher_iv_length($ciphering); 
	    $options = 0; 
	    $encryption_iv = '1234567891011121'; 
	    $encryption_key = "UATCODE"; 

    	$encryption = openssl_encrypt($merge,$ciphering,$encryption_key, $options, $encryption_iv);
    	//echo $encryption;  echo "<br><br>";
    	$data['sd']="web";

    	//$decrypted=openssl_decrypt ($encryption, $ciphering, $encryption_key, $options, $encryption_iv); 
			//echo $decrypted;  echo "<br><br>";

		$url=DOMAIN.'/rest/seller/addWalletPaymentGatewayRest.php';
		$output=getRestApiResponse($url,$data);

		if(isset($output['changestatustorefund']) && $output['changestatustorefund']['response_code']==200)
		{
			redirect($output['changestatustorefund']['paymentlink']);
		}
		else
		{
			// $showinformation=1;
			// $message='<p class="text-danger">Unable to update collection status</p>';
		}
	}
?>

<div class="container-fluid">
	<div class="row mt-3">
		<div class="offset-md-2 col-12 col-md-8">
			<div class="card">
				<?php
					$data1['user_id']=$_SESSION['user_id'];

					$url=DOMAIN.'/rest/seller/getWalletBalanceRest.php';
					$output=getRestApiResponse($url,$data1);
					
					$wallet_balance=0.00;
					$kyc_completed=0;
					$bank_account_verified="No";

					if(isset($output['getwalletbalance']) && count($output['getwalletbalance'])>2)
					{
						$wallet_balance=round($output['getwalletbalance']['walletbalance'],2);
						$kyc_completed=$output['getwalletbalance']['kyc_completed'];
						$bank_account_verified=$output['getwalletbalance']['bank_account_verified'];
					}

					if(isset($_GET['addmoney']))
					{
				?>	
				<div class="card-header pt-1 pb-1 bg-info text-center text-white">Add Money</div>
				<div class="card-body">
				<form action="" method="post">
				  	<div class="row mt-5">
				  		<div class="col-12">
				  			<div class="row">
				  				<div class="col-6">
				  					<p>Current Wallet Balance</p>
				  				</div>
				  				<div class="col-6 text-right">
				  					<i class="fas fa-rupee-sign"></i>&nbsp;<?php echo $wallet_balance; ?>
				  				</div>
				  			</div>
				  		</div>
				  		<div class="col-12">
				      		<div class="input-group">
		                      <div class="input-group-prepend">
		                        <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
		                      </div>
		                      <input type="hidden" name="opening_balance" value="<?php echo $wallet_balance; ?>">
		                      <input type="number" class="form-control text-right" name="amount" placeholder="Enter Amount Here">
		                    </div>
				   		</div>
				  	</div>

					<div class="row mt-3">
						<div class="col-12 text-center">
						    <div class="form-group">
						      	<button type="submit" name="add-money" class="btn btn-info btn-md">Continue</button>
						    </div>
						</div>
					</div>
				</form>
				</div>
				<?php
					}
					else
					if(isset($_GET['withdrawmoney']) && !isset($_POST['withdraw-money']))
					{
				?>
				<div class="card-header pt-1 pb-1 bg-info text-center text-white">Withdraw Money</div>
				<div class="card-body">
				<form action="" method="post">
				  	<div class="row mt-5">
				  		<div class="col-12">
				  			<div class="row">
				  				<div class="col-6">
				  					<p>Current Wallet Balance</p>
				  				</div>
				  				<div class="col-6 text-right">
				  					<i class="fas fa-rupee-sign"></i>&nbsp;<?php echo $wallet_balance; ?>
				  				</div>
				  			</div>
				  		</div>
				  		<div class="col-12">
				  			<div class="row">
				  				<div class="col-1">
				  					<i class="fas fa-university fa-2x text-danger"></i>
				  				</div>
				  				<div class="col-11">
				  					<p class="mt-1">Transfer money to your Bank A/C</p>
				  				</div>
				  			</div>
				  		</div>
				  		<div class="col-12 mt-3">
				      		<div class="input-group">
		                      <div class="input-group-prepend">
		                        <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
		                      </div>
		                      <input type="number" class="form-control text-right" name="amount" placeholder="Enter Amount to Withdraw">
		                    </div>
				   		</div>
				  	</div>

					<div class="row mt-3">
						<div class="col-12 text-center">
						    <div class="form-group">
						      	<button type="submit" name="withdraw-money" class="btn btn-info btn-md">Continue</button>
						    </div>
						</div>
					</div>
				</form>
				</div>
				<?php
					}
					else
					if(isset($_GET['withdrawmoney']) && isset($_POST['withdraw-money']))
					{
						$data2['user_id']=$_SESSION['user_id'];

						$url=DOMAIN.'/rest/seller/getSellerBankAccountDetailsRest.php';
						$output=getRestApiResponse($url,$data2);
						
						$bank_account_verified=$output['getsellerbankdetails'][0]['bank_account_verified'];

						if(isset($output['getsellerbankdetails']) && count($output['getsellerbankdetails'])>2 && $bank_account_verified=="Yes")
						{
				?>
				<div class="card-header pt-1 pb-1 bg-info text-center text-white">Withdraw Money</div>
				<div class="card-body">
				<form action="" method="post">
				  	<div class="row mt-5">
				  		<div class="col-12">
				  			<div class="row">
				  				<div class="col-6">
				  					<p>Withdrawl Amount</p>
				  				</div>
				  				<div class="col-6 text-right">
				  					<i class="fas fa-rupee-sign"></i>&nbsp;<?php echo $_POST['amount']; ?>
				  				</div>
				  			</div>
				  		</div>
				  		<div class="col-12">
				  			<p>This money will be transferred to Bank A/C &nbsp;&nbsp;<b><?php echo getMaskedString($output['getsellerbankdetails'][0]['account_number'],'*',0,strlen($output['getsellerbankdetails'][0]['account_number'])-4); ?></b></p>
				  		</div>
				  	</div>

					<div class="row mt-3">
						<div class="col-12 text-center">
						    <div class="form-group">
					  			<button type="submit" name="withdraw" class="btn btn-secondary btn-md" disabled>Confirm</button>
						    </div>
						</div>
					</div>
				</form>
				</div>
				<?php
						}
					}
					else
					{
						echo '<div class="row">
								<div class="col-12 text-center text-danger">
									<h5>Invalid Request</h5>
								</div>
							</div>';
					}
				?>
			</div>	
		</div>

		<div class="offset-md-2 col-12 col-md-8 mt-3">
			<a href="javascript:history.go(-1)" class="btn btn-success">Back</a>
		</div>
	</div>
</div>

<?php
}
else
	echo "Login First";
?>

</div>
    <!-- Main Col END -->
</div>
<!-- body-row END -->

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

</body>
</html>
