<?php include("navigation.php"); ?>

<?php
  if(!(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2))
    redirect("login.php");
?>

<?php

$showinformation=0;
$message="";

if(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2)
{
	if(isset($_POST['saveusername']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['username']=escape_string(trim($_POST['username']));

		$url=DOMAIN.'/rest/seller/updateSellerUsernameRest.php';
		$output=getRestApiResponse($url,$data);

		if(isset($output['updateusername']) && $output['updateusername']['response_code']==200)
		{
			$_SESSION['username']=$_POST['username'];
			$showinformation=1;
			$message='<p class="text-success">Username updated successfully</p>';
		}
		else
		if(isset($output['updateusername']) && $output['updateusername']['response_code']==405)
		{
			$showinformation=1;
			$message='<p class="text-success">This username already exist! You can\'t take this username</p>';
			
		}
		else
		{
			$showinformation=1;
			$message='<p class="text-success">Unable to update username</p>';
		}
	}
	else
	if(isset($_POST['savebusinessdetails']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['businessname']=escape_string(trim($_POST['bname']));
		$data['alt_number']=escape_string(trim($_POST['phone']));
		$data['alt_number_status']="Yes";
		$data['email']=escape_string(trim($_POST['email']));

		$url=DOMAIN.'/rest/seller/updateSellerBusinessContactDetailsRest.php';
		$output=getRestApiResponse($url,$data);

		if(isset($output['updatebusinessname']) && $output['updatebusinessname']['response_code']==200)
		{
			$showinformation=1;
			$message='<p class="text-success">Business and Contact details updated successfully</p>';
			$_SESSION['business_name']=$output['updatebusinessname']['businessname'];
		}
		else
		{
			$showinformation=1;
			$message='<p class="text-success">Unable to update Business and Contact details</p>';
		}
	}
	else
	if(isset($_POST['savestoredetails']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['address1']=escape_string(trim($_POST['address1']));
		$data['address2']=escape_string(trim($_POST['address2']));
		$data['country']=$_POST['country'];
		$data['state']=$_POST['state'];
		$data['city']=escape_string(trim($_POST['city']));
		$data['pincode']=escape_string(trim($_POST['pincode']));

		$url=DOMAIN.'/rest/seller/updateSellerStoreAddressRest.php';
		$output=getRestApiResponse($url,$data);

		if(isset($output['updatecontactdetails']) && $output['updatecontactdetails']['response_code']==200)
		{
			$showinformation=1;
			$message='<p class="text-success">Store address updated successfully</p>';	
		}
		else
		{
			$showinformation=1;
			$message='<p class="text-success">Unable to update store address</p>';
		}
	}
	else
	if(isset($_POST['savepandetails']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['panname']=escape_string(trim($_POST['seller_panname']));
		$data['pannumber']=escape_string(trim($_POST['seller_pannum']));

		$url=DOMAIN.'/rest/seller/updateSellerPanCardDetailsRest.php';
		$output=getRestApiResponse($url,$data);

		if(isset($output['updatepancarddetails']) && $output['updatepancarddetails']['response_code']==200)
		{
			$showinformation=1;
			$message='<p class="text-success">PAN Card details updated successfully</p>';
			
		}
		else
		{
			$showinformation=1;
			$message='<p class="text-success">Unable to update PAN Card details</p>';
		}
	}
	else
	if(isset($_POST['savegstdetails']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['gst_number']=escape_string(trim($_POST['gst_number']));

		$image=$_FILES['gst_certificate_image']['tmp_name'];

		if(empty($image) || $image=="")
		{
			$data['imagestatus']=0;

			if($_POST['hidden_gst_certificate_image']!="")
				$data['image_name']=$_POST['hidden_gst_certificate_image'];
			else
				$data['image_name']="/images/gst/defaultpic.png";
		}
		else
		{
			$data['imagestatus']=1;

			if($_POST['hidden_gst_certificate_image']!="")
				$data['image_name']=$_POST['hidden_gst_certificate_image'];
			else
				$data['image_name']="/images/gst/defaultpic.png";

			$image = file_get_contents($image);
			$image= base64_encode($image);
			$data['gst_certificate_image']=$image;
		}

		$url=DOMAIN.'/rest/seller/updateSellerGstDetailsRest.php';
		$output=getRestApiResponse($url,$data);

		if(isset($output['updategst']) && $output['updategst']['response_code']==200)
		{
			$showinformation=1;
			$message='<p class="text-success">GST details updated successfully</p>';
		}
		else
		{
			$showinformation=1;
			$message='<p class="text-success">Unable to update GST details</p>';
		}
	}
	else
	if(isset($_POST['savekycdetails']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$image1=$_FILES['pan_card_image']['tmp_name'];

		if(empty($image1) || $image1=="")
		{
			$data['imagestatus']=0;
			if($_POST['hidden_pan_card_image']!="")
				$data['image_name']=$_POST['hidden_pan_card_image'];
			else
				$data['image_name']="/images/pancards/defaultpic.png";
		}
		else
		{
			$data['imagestatus']=1;

			if($_POST['hidden_pan_card_image']!="")
				$data['image_name']=$_POST['hidden_pan_card_image'];
			else
				$data['image_name']="/images/pancards/defaultpic.png";

			$image1 = file_get_contents($image1);
			$image1= base64_encode($image1);
			$data['pan_card_image']=$image1;
		}

		$image2=$_FILES['address_proof_image']['tmp_name'];

		if(empty($image2) || $image2=="")
		{
			$data['imagestatus1']=0;
			if($_POST['hidden_address_proof_image']!="")
				$data['image_name1']=$_POST['hidden_address_proof_image'];
			else
				$data['image_name1']="/images/addressproofs/defaultpic.png";
		}
		else
		{
			$data['imagestatus1']=1;

			if($_POST['hidden_address_proof_image']!="")
				$data['image_name1']=$_POST['hidden_address_proof_image'];
			else
				$data['image_name1']="/images/addressproofs/defaultpic.png";

			$image2 = file_get_contents($image2);
			$image2= base64_encode($image2);
			$data['address_proof_image']=$image2;
		}

		$url=DOMAIN.'/rest/seller/updateSellerKycApplicationRest.php';
		$output=getRestApiResponse($url,$data);

		if(isset($output['updatekyc']) && $output['updatekyc']['response_code']==200)
		{
			$showinformation=1;
			$message='<p class="text-success">KYC details updated successfully</p>';
		
		}
		else
		{
			$showinformation=1;
			$message='<p class="text-success">Unable to update KYC details</p>';
		
		}
	}
	else
	if(isset($_POST['savebankdetails']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['beneficiary_name']=escape_string(trim($_POST['beneficiary_name']));
		$data['ifsc_code']=escape_string(trim($_POST['ifsc_code']));
		$data['account_number']=escape_string(trim($_POST['account_number']));

		$image=$_FILES['cancelled_cheque_image']['tmp_name'];

		if(empty($image) || $image=="")
		{
			$data['imagestatus']=0;
			if($_POST['hidden_cancelled_cheque_image']!="")
				$data['image_name']=$_POST['hidden_cancelled_cheque_image'];
			else
				$data['image_name']="/images/cheques/defaultpic.png";
		}
		else
		{
			$data['imagestatus']=1;

			if($_POST['hidden_cancelled_cheque_image']!="")
				$data['image_name']=$_POST['hidden_cancelled_cheque_image'];
			else
				$data['image_name']="/images/cheques/defaultpic.png";

			$image = file_get_contents($image);
			$image= base64_encode($image);
			$data['cheque_image']=$image;
		}

		$url=DOMAIN.'/rest/seller/updateSellerBankDetailsRest.php';
		$output=getRestApiResponse($url,$data);

		if(isset($output['updatebankdetails']) && $output['updatebankdetails']['response_code']==200)
		{
			$showinformation=1;
			$message='<p class="text-success">Bank details updated successfully</p>';
		}
		else
		{
			$showinformation=1;
			$message='<p class="text-success">Unable to update bank details</p>';
		}
	}

	// For Profile Response
	$data['sid']=$_SESSION['user_id'];
	$url=DOMAIN.'/rest/seller/getSellerProfileRest.php';
	$output=getRestApiResponse($url,$data);

	if(isset($output['seller']) && $output['seller']['response_code']==200)
	{
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header bg-info text-white text-center">
					<h5>Profile Management</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-3">
		<div class="col-12">
			<div class="card">
				<div class="card-header text-white bg-secondary">
					Login Details
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-md-6">
							<div class="row">
								<div class="col-6">
									Login Id (Mobile)
								</div>
								<div class="col-6">
									<input type="text" class="form-control" value="<?php echo $_SESSION['mobile']; ?>" readonly>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6 mt-3 mt-md-0">
							<div class="row">
								<div class="col-6 text-md-right">
									Password
								</div>
								<div class="col-6 text-md-right">
									<a href="displaySellerChangePassword.php" class="btn btn-danger btn-sm">Change</a>
								</div>
							</div>
						</div>
						<div class="col-12 mt-3 text-info">
							<p>(** Login Mobile number cannot be changed. Raise a ticket for any change in login mobile number)</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-3">
		<div class="col-12">
			<div class="card">
				<div class="card-header text-white bg-secondary">
					Manage Store Website Link
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-md-7">
							<div class="row">
								<div class="col-10">
									<form method="post" action="">
										<div class="form-group row">
								      		<label for="username" class="col-4 col-md-3 col-form-label"><b>Username:</b></label>
								      		<div class="col-6 col-md-4">
								      		<input type="text" name="username" id="username" class="form-control border border-top-0 border-left-0 border-right-0" value="<?php 
								      			if(isset($_SESSION['username']))
								      			{
								      				echo $_SESSION['username'];
								      			} 
								      			?>" disabled required>
								    		</div>
								    		<div class="col-2 col-md-1 ">
								    			<i class="fa fa-check fa-2x text-success d-none" id="validusername"></i>
								    		</div>
								    		<div class="col-12 col-md-4 mt-3 mt-md-0">
								    			<div class="row">
								    				<div class="col-6 text-right">
								    					<a class="btn btn-primary text-white d-none cancelactivity" id="cancel1">Cancel</a>
								    				</div>
								    				<div class="col-6">
								    					<input type="submit" name="saveusername" class="btn btn-danger d-none" id="username-save" value="Save" disabled>
								    				</div>
								    			</div>
								    		</div>
							    		</div>
							    		<div class="row">
							    			<div class="col-12 text-danger">
							    				<p class="d-none" id="usernameerror">This username already exist, you can't take this username</p>
							    			</div>
							    		</div>
									</form>
								</div>	
								<div class="col-2">
									<button class="btn btn-primary" id="username-enabler">Edit</button>
								</div>	
							</div>
						</div>
						<div class="col-12 col-md-5 mt-3 mt-md-0">
							<div class="row">
								<div class="col-12">
									<b>Store Link:</b><a href="<?php echo DOMAIN.'/app/?s='.$_SESSION['username']; ?>" class="ml-2" target="_blank"><?php echo DOMAIN."/app/?s=".$_SESSION['username']; ?></a>
								</div>
								<div class="col-12">
									<div class="row">
										<div class="col-8 mt-3"><span>Preview Your Business Link</span></div>
										<div class="col-2">
											<a href="<?php echo DOMAIN.'/app/?s='.$_SESSION['username']; ?>" class="text-decoration-none" target="_blank" title="Preview Your Business Link"><i class="fas fa-external-link-alt fa-2x text-dark mt-2"></i></a>
										</div>
										<div class="col-1">
											<?php
												$storelink=DOMAIN."/app/?s=".$_SESSION['username'];
												$storelink=urlencode($storelink);
												
												$text="Check out *".$_SESSION['business_name']."'s* latest product collection %0DOrder 24x7 - Click on the link to place an order%0D%0D".$storelink.".\n%20%0D%0D";
											?>
											<a href="https://api.whatsapp.com/send?text=<?php echo $text; ?>" target="_blank" title="Share Your Store Link On Whatsapp"><i class="fab fa-whatsapp text-success fa-2x mt-2"></i></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-3">
		<div class="col-12">
			<div class="card">
				<div class="card-header text-white bg-secondary">
					Business Name & Contact
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-9 text-info mt-1">
							These details will be displayed on your online store
						</div>
						<div class="col-3 text-right">
							<button class="btn btn-primary" id="businessdetails-enabler">Edit</button>
						</div>
					</div>
					<form method="post" action="" id="form">
						<div class="row mt-4">
							<div class="col-12 col-md-6">
									<div class="form-group row">
						      		<label for="bname" class="col-6 col-md-4 col-form-label"><b>Business Name:</b></label>
						      		<div class="col-6 col-md-8">
						      		<input type="text" name="bname" id="bname" class="form-control border border-top-0 border-left-0 border-right-0" value="<?php
						      				echo $output['seller'][0]['seller_business_name']; ?>" required disabled>
						      		</div>
						    		</div>	
							</div>
							<div class="col-12 col-md-6 text-info">
								(This will be your store name on website)
							</div>
							<div class="col-12 col-md-6 mt-3 mt-md-0">
								<div class="form-group row">
							      <label for="phone" class="col-6 col-md-4 col-form-label"><b>Contact Number:</b></label>
							      <div class="col-6 col-md-8">
							      <input type="text" name="phone" id="phone" class="form-control border border-top-0 border-left-0 border-right-0" value="<?php echo $output['seller'][0]['seller_alternate_number']; ?>" pattern="[7-9]{1}[0-9]{9}" title="Enter Valid 10 Digit Mobile Number" minlength="10" maxlength="10" required disabled readonly>
							  	  </div>
							    </div>
							</div>
							<div class="col-12 col-md-6 text-info">
								(This number will be displayed on website and customers will contact you on this number)
							</div>
							<div class="col-12 col-md-6 mt-3 mt-md-0">
					    		<div class="form-group row">
					      		<label for="email" class="col-6 col-md-4 col-form-label"><b>Email Address:</b></label>
					      		<div class="col-6 col-md-8">
					      		<input type="email" name="email" id="email" class="form-control border border-top-0 border-left-0 border-right-0" value="<?php 
					      				echo $output['seller'][0]['seller_email']; ?>" disabled>
					      		</div>
					    		</div>
					   		</div>
					   		<div class="col-12 col-md-6 text-info">
								(This email id will be displayed on website and customers will contact you on this email id)
							</div>
							<div class="col-12 mt-3">
								<div class="row">
									<div class="col-6 text-right">
										<a class="btn btn-primary text-white d-none cancelactivity w-50" id="cancel2">Cancel</a>
									</div>
									<div class="col-6">
										<input type="submit" class="btn btn-danger d-none w-50" name="savebusinessdetails" value="Save" id="businessdetails-save">
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-3">
		<div class="col-12">
			<div class="card">
				<div class="card-header text-white bg-secondary">
					Your Store Address
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-9 text-info mt-1">
							(Below address will be displayed on the website)
						</div>
						<div class="col-3 text-right">
							<button class="btn btn-primary" id="storedetails-enabler">Edit</button>
						</div>
					</div>
					<form method="post" action="">
						<div class="row mt-4">
							<div class="col-12 col-md-6">
								<form method="post" action="">
									<div class="form-group row">
						      		<label for="address1" class="col-6 col-form-label"><b>Address Line 1:</b></label>
						      		<div class="col-6">
						      		<input type="text" name="address1" id="address1" class="form-control border border-top-0 border-left-0 border-right-0" value="<?php echo $output['seller'][0]['seller_address1']; ?>" disabled required>
						      		</div>
						    		</div>
								</form>		
							</div>
							<div class="col-12 col-md-6 mt-3 mt-md-0">
								<div class="form-group row">
							      <label for="address2" class="col-6 col-form-label text-md-center"><b>Address Line 2:</b></label>
							      <div class="col-6">
							      <input type="text" name="address2" id="address2" class="form-control border border-top-0 border-left-0 border-right-0" value="<?php echo $output['seller'][0]['seller_address2']; ?>" required disabled>
							  	  </div>
							    </div>
							</div>
					   		<div class="col-12 col-md-4">
							    <div class="form-group row">
							      <label for="country" class="col-6 col-form-label"><b>Country:</b></label>
							      <div class="col-6">
							      <select name="country" id="country" class="form-control border border-top-0 border-left-0 border-right-0" disabled required>
							      	<?php getCountries(); ?>
							      </select>
							  		</div>
							    </div>
							</div>
							<div class="col-12 col-md-4">
							    <div class="form-group row">
							      <label for="state" class="col-6 col-md-5 col-form-label text-md-right"><b>State:</b></label>
							      <div class="col-6 col-md-7">
							      <select name="state" id="state" class="form-control border border-top-0 border-left-0 border-right-0" disabled required>
							      	<?php 
							      		if($output['seller'][0]['seller_state']!="")
							      			getStates($output['seller'][0]['seller_state']);
							      		else
							      			getStates("");
							      	?>
							      </select>
							  	</div>
							    </div>
							</div>
							<div class="col-12 col-md-4">
							    <div class="form-group row">
							      <label for="city" class="col-6 col-md-4 col-form-label text-md-right"><b>City:</b></label>
							      <div class="col-6 col-md-8">
							      <input type="text" name="city" id="city" class="form-control border border-top-0 border-left-0 border-right-0" value="<?php 
					      				echo $output['seller'][0]['seller_city']; ?>" disabled required>
							  		</div>
							    </div>
							</div>
							<div class="col-12 col-md-4">
								<div class="form-group row">
					      		<label for="pincode" class="col-6 col-md-6 col-form-label"><b>Pincode:</b></label>
					      		<div class="col-6">
					      		<input type="number" name="pincode" id="pincode" class="form-control border border-top-0 border-left-0 border-right-0 text-right" value="<?php echo $output['seller'][0]['seller_pin']; ?>" minlength="6" maxlength="6" disabled required pattern="[1-9]{1}[0-9]{5}" title="Enter Valid 6 Digit Pincode">
					      		</div>
					    		</div>
							</div>
							<div class="col-12 mt-3">
								<div class="row">
									<div class="col-6 text-right">
										<a class="btn btn-primary text-white d-none cancelactivity w-50" id="cancel3">Cancel</a>
									</div>
									<div class="col-6">
										<input type="submit" class="btn btn-danger d-none w-50" name="savestoredetails" value="Save" id="storedetails-save">
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-3">
		<div class="col-12">
			<div class="card">
				<div class="card-header text-white bg-secondary">
					Pan Card Details
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 text-right">
							<?php
								if($output['seller'][0]['kyc_completed']==0)
									echo '<button class="btn btn-primary" id="pandetails-enabler">Edit</button>';
							?>
						</div>
					</div>
					<form method="post" action="">
						<div class="row mt-3">
							<div class="col-12 col-md-8">
								<div class="form-group row">
						      		<label for="seller_panname" class="col-6 col-md-4 col-form-label"><b>Name (As In PAN):</b></label>
						      		<div class="col-6 col-md-8">
						      		<input type="text" name="seller_panname" id="seller_panname" class="form-control border border-top-0 border-left-0 border-right-0" value="<?php
						      				echo $output['seller'][0]['seller_panname']; ?>" disabled oninput="this.value = this.value.toUpperCase()">
						      		</div>
					    		</div>	
							</div>
							<div class="col-12 col-md-4 mt-3 mt-md-0">
								<div class="form-group row">
								      <label for="seller_pannum" class="col-6 col-form-label text-md-right"><b>PAN Number:</b></label>
								      <div class="col-6">
								      <input type="text" name="seller_pannum" id="seller_pannum" class="form-control border border-top-0 border-left-0 border-right-0" value="<?php echo $output['seller'][0]['seller_pannum']; ?>" disabled pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" title="Enter Valid 10 Digit PAN Number" oninput="this.value = this.value.toUpperCase()">
								  	  </div>
							    </div>
							</div>
							<div class="col-12 mt-3">
								<div class="row">
									<div class="col-6 text-right">
										<a class="btn btn-primary text-white d-none cancelactivity w-50" id="cancel4">Cancel</a>
									</div>
									<div class="col-6">
										<input type="submit" class="btn btn-danger d-none w-50" name="savepandetails" value="Save" id="pandetails-save">
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-3">
		<div class="col-12">
			<div class="card">
				<div class="card-header text-white bg-secondary">
					GST Details
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-md-5 text-info mt-1">
							(This will be displayed on customer invoice)
						</div>
						<div class="col-9 col-md-5 mt-3 mt-md-0">
			      			<div class="row">
			      				<div class="col-8">
			    					<b>GST Number Verified</b>
			    				</div>
			    				<div class="col-1">
					    			<?php
					    				if($output['seller'][0]['gst_verified']=="No")
					    					echo '<i class="fas fa-times fa-2x text-danger"></i>';
					    				else
					    					echo '<i class="fas fa-check fa-2x text-success"></i>';
					    			?>
					    		</div>
					    	</div>
			      		</div>
						<div class="col-3 col-md-2 text-info text-right">
							<?php
								if($output['seller'][0]['gst_verified']=="No")
									echo '<button class="btn btn-primary mt-3 mt-md-0" id="gstdetails-enabler">Edit</button>';
							?>
						</div>
					</div>
					<div class="row mt-4">
						<div class="col-12">
							<form method="post" action="" enctype="multipart/form-data">
								<div class="form-group row">
						      		<label for="gst" class="col-6 col-md-2 col-form-label"><b>GSTIN:</b></label>
						      		<div class="col-6 col-md-4">
						      		<input type="text" name="gst_number" id="gst_number" class="form-control border border-top-0 border-left-0 border-right-0" value="<?php 
						      			if(isset($output['seller'][0]['seller_gst']))
						      			{
						      				echo $output['seller'][0]['seller_gst'];
						      			} 
						      			?>" disabled pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$" title="Enter Valid 15 Digit GST Identification Number" oninput="this.value = this.value.toUpperCase()">
						    		</div>
						    		<div class="col-12 col-md-6 mt-3 mt-md-0">
						    			<input type="hidden" name="hidden_gst_certificate_image" value="<?php 
						      			if(isset($output['seller'][0]['gst_certificate_image']))
						      			{
						      				echo $output['seller'][0]['gst_certificate_image'];
						      			} 
						      			?>">
							      		<input type="file" name="gst_certificate_image" id="gst_certificate_image" class="form-control border border-top-0 border-left-0 border-right-0" disabled title="Upload Gst Certificate Image" style="overflow:hidden;">
						      		</div>
						      		<div class="col-12 mt-3">
						      			<div class="row">
											<div class="col-6 text-right">
												<a class="btn btn-primary text-white d-none cancelactivity w-50" id="cancel5">Cancel</a>
											</div>
											<div class="col-6">
												<input type="submit" class="btn btn-danger d-none w-50" name="savegstdetails" value="Save" id="gstdetails-save">
											</div>
										</div>
									</div>
					    		</div>
							</form>		
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-3">
		<div class="col-12">
			<div class="card">
				<div class="card-header text-white bg-secondary">
					KYC (Know Your Customer)
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							<div class="row">
								<div class="col-6 col-md-2 mt-3">
									<b>KYC Completed</b>
								</div>
								<div class="col-1 mt-2">
									<?php
										if($output['seller'][0]['kyc_completed']==0)
											echo '<i class="fas fa-times fa-2x text-danger"></i>';
										else
											echo '<i class="fas fa-check fa-2x text-success"></i>';
									?>
								</div>
							</div>
						</div>
						<div class="col-12 mt-3">
							<b>Upload Following Documents For KYC</b>
						</div>
					</div>
					<?php
						if($output['seller'][0]['kyc_application_status']=="Pending" || ($output['seller'][0]['kyc_application_status']=="Processed" && $output['seller'][0]['kyc_completed']==0))
							echo '<form method="post" action="" enctype="multipart/form-data">';
					?>
						<div class="row mt-3">
							<div class="col-12 col-md-6">
								<div class="form-group row">
					      		<label for="bname" class="col-6 col-md-4 col-form-label"><b>PAN Card Proof:</b></label>
					      		<div class="col-6 col-md-8">
					      		<input type="file" name="pan_card_image" id="pan_card_image" class="form-control border border-top-0 border-left-0 border-right-0" 
					      				<?php 
					      					if($output['seller'][0]['kyc_application_status']=="Submitted" || ($output['seller'][0]['kyc_application_status']=="Processed" && $output['seller'][0]['kyc_completed']==1))
					      						echo "disabled";
					      				?> title="Upload PAN Card Image" required style="overflow:hidden;">

					      		<input type="hidden" name="hidden_pan_card_image" value="<?php 
					      			if(isset($output['seller'][0]['pan_card_image']))
					      			{
					      				echo $output['seller'][0]['pan_card_image'];
					      			} 
					      			?>">
					      		</div>
					    		</div>		
							</div>
							<div class="col-12 col-md-6 mt-1 mt-md-0">
								<div class="form-group row">
							      <label for="phone" class="col-6 col-md-4 col-form-label"><b>Address Proof:</b></label>
							      <div class="col-6 col-md-8">
							      <input type="file" name="address_proof_image" id="address_proof_image" class="form-control border border-top-0 border-left-0 border-right-0" 
							      <?php 
				      					if($output['seller'][0]['kyc_application_status']=="Submitted" || ($output['seller'][0]['kyc_application_status']=="Processed" && $output['seller'][0]['kyc_completed']==1))
				      						echo "disabled";
				      				?> title="Upload Address Proof Image" required>
							      <input type="hidden" name="hidden_address_proof_image" value="<?php 
					      			if(isset($output['seller'][0]['address_proof_image']))
					      			{
					      				echo $output['seller'][0]['address_proof_image'];
					      			} 
					      			?>">
							  	  </div>
							    </div>
							</div>
							<div class="col-12 mt-3">
								<div class="row">
									<?php
										if($output['seller'][0]['kyc_application_status']=="Pending" || ($output['seller'][0]['kyc_application_status']=="Processed" && $output['seller'][0]['kyc_completed']==0))
										{
											echo '<div class="col-6 text-right">
													<a class="btn btn-primary text-white cancelactivity w-50">Cancel</a>
												</div>
												<div class="col-6">
													<input type="submit" class="btn btn-danger w-50" name="savekycdetails" value="Submit KYC" id="kycdetails-save">
												</div>';
										}
										else
										if($output['seller'][0]['kyc_application_status']=="Submitted")
										{
											echo '
												<div class="col-12 text-center">
													<button class="btn btn-secondary w-50" disabled>Application Submitted</button>
												</div>';
										}
										else
										if($output['seller'][0]['kyc_application_status']=="Processed" && $output['seller'][0]['kyc_completed']==1)
										{
											echo '
												<div class="col-12 text-center">
													<button class="btn btn-success w-50" disabled>Processed</button>
												</div>';
										}
									?>
								</div>
							</div>
						</div>
						<?php
							if($output['seller'][0]['kyc_application_status']=="Pending" || ($output['seller'][0]['kyc_application_status']=="Processed" && $output['seller'][0]['kyc_completed']==0))
								echo "</form>";
						?>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-3">
		<div class="col-12">
			<div class="card">
				<div class="card-header text-white bg-secondary">
					Your Bank Account Details
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-md-5 text-info mt-1">
							* Mandatory for accepting online payments
						</div>
						<div class="col-9 col-md-5 mt-3 mt-md-0">
							<div class="row">
						      <div class="col-8">
						      	<b>Bank Account Verified</b>
						      </div>
						      <div class="col-1">
						      	<?php
									if($output['seller'][0]['bank_account_verified']=="No")
										echo '<i class="fas fa-times fa-2x text-danger"></i>';
									else
										echo '<i class="fas fa-check fa-2x text-success"></i>';
								?>
						      </div>
						    </div>
						</div>
						<div class="col-3 col-md-2 text-right mt-3 mt-md-0">
							<?php
								if($output['seller'][0]['bank_account_verified']=="No")
									echo '<button class="btn btn-primary" id="bankdetails-enabler">Edit</button>';
							?>
						</div>
					</div>
					<form action="" method="post" enctype="multipart/form-data">
						<div class="row mt-3">
							<div class="col-12 col-md-6">
								<div class="form-group row">
					      		<label for="bname" class="col-6 col-form-label"><b>Beneficiary Name:</b></label>
					      		<div class="col-6">
					      		<input type="text" name="beneficiary_name" id="beneficiary_name" class="form-control border border-top-0 border-left-0 border-right-0" value="<?php
					      				echo $output['seller'][0]['beneficiary_name']; ?>" disabled required oninput="this.value = this.value.toUpperCase()">
					      		</div>
					    		</div>	
							</div>
							<div class="col-12 col-md-6 mt-3 mt-md-0">
								<div class="form-group row">
							      <label for="phone" class="col-6 col-form-label text-md-center"><b>IFSC Code:</b></label>
							      <div class="col-6">
							      <input type="text" name="ifsc_code" id="ifsc_code" class="form-control border border-top-0 border-left-0 border-right-0" value="<?php echo $output['seller'][0]['ifsc_code']; ?>" disabled required pattern="^[A-Z]{4}0[A-Z0-9]{6}$" title="Enter Valid IFSC Code" minlength="11" maxlength="11" oninput="this.value = this.value.toUpperCase()">
							  	  </div>
							    </div>
							</div>
							<div class="col-12 col-md-6 mt-3 mt-md-0">
								<div class="form-group row">
							      <label for="phone" class="col-6 col-form-label"><b>Account Number:</b></label>
							      <div class="col-6">
							      <input type="text" name="account_number" id="account_number" class="form-control border border-top-0 border-left-0 border-right-0" value="<?php echo $output['seller'][0]['account_number']; ?>" disabled required pattern="^\d{9,18}$" title="Enter Valid Bank Account Number" minlength="9" maxlength="18">
							  	  </div>
							    </div>
							</div>
							<div class="col-12 col-md-6 mt-3 mt-md-0">
								<div class="form-group row">
							      <label for="cancelled_cheque_image" class="col-6 col-form-label"><b>Cancelled Cheque Image:</b></label>
							      <div class="col-6">
							      <input type="file" accept="image/*" title="Upload Cancelled Cheque Image" name="cancelled_cheque_image" id="cancelled_cheque_image" class="form-control border border-top-0 border-left-0 border-right-0" disabled  style="overflow:hidden;">
							      <input type="hidden" name="hidden_cancelled_cheque_image" value="<?php 
					      			if(isset($output['seller'][0]['cheque_image']))
					      			{
					      				echo $output['seller'][0]['cheque_image'];
					      			} 
					      			?>">
							  	  </div>
							    </div>
							</div>
							<div class="col-12 mt-3">
								<div class="row">
									<div class="col-6 text-right">
										<a class="btn btn-primary text-white d-none cancelactivity w-50" id="cancel7">Cancel</a>
									</div>
									<div class="col-6">
										<input type="submit" class="btn btn-danger d-none w-50" name="savebankdetails" value="Save" id="bankdetails-save">
									</div>
								</div>
							</div>
						</div>
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
?>

<script>
	$("#username-enabler").on("click",
		function()
		{
			$("#username-enabler").addClass("d-none");
			$("#username").attr("disabled",false);
			$("#username-save").removeClass("d-none");
			$("#cancel1").removeClass("d-none");
			$("#validusername").removeClass("fa fa-check text-success d-none");
			$("#validusername").addClass("fa fa-remove text-danger");
			$("#username").focus();
		});

	$("#businessdetails-enabler").on("click",
		function()
		{
			$("#businessdetails-enabler").addClass("d-none");
			$("#bname").attr("disabled",false);
			$("#bname").focus();
			$("#phone").attr("disabled",false);
			$("#email").attr("disabled",false);
			$("#businessdetails-save").removeClass("d-none");
			$("#cancel2").removeClass("d-none");
		});

	$("#storedetails-enabler").on("click",
		function()
		{
			$("#storedetails-enabler").addClass("d-none");
			$("#address1").attr("disabled",false);
			$("#address1").focus();
			$("#address2").attr("disabled",false);
			$("#country").attr("disabled",false);
			$("#state").attr("disabled",false);
			$("#city").attr("disabled",false);
			$("#pincode").attr("disabled",false);
			$("#storedetails-save").removeClass("d-none");
			$("#cancel3").removeClass("d-none");
		});

	$("#pandetails-enabler").on("click",
		function()
		{
			$("#pandetails-enabler").addClass("d-none");
			$("#seller_panname").attr("disabled",false);
			$("#seller_panname").focus();
			$("#seller_pannum").attr("disabled",false);
			$("#pandetails-save").removeClass("d-none");
			$("#cancel4").removeClass("d-none");
		});

	$("#gstdetails-enabler").on("click",
		function()
		{
			$("#gstdetails-enabler").addClass("d-none");
			$("#gst_number").attr("disabled",false);
			$("#gst_number").focus();
			$("#gst_certificate_image").attr("disabled",false);
			$("#gstdetails-save").removeClass("d-none");
			$("#cancel5").removeClass("d-none");
		});

	$("#bankdetails-enabler").on("click",
		function()
		{
			$("#bankdetails-enabler").addClass("d-none");
			$("#beneficiary_name").attr("disabled",false);
			$("#beneficiary_name").focus();
			$("#ifsc_code").attr("disabled",false);
			$("#account_number").attr("disabled",false);
			$("#cancelled_cheque_image").attr("disabled",false);
			$("#bankdetails-save").removeClass("d-none");
			$("#cancel7").removeClass("d-none");
		});

	$(".cancelactivity").on("click",function()
	{
		location.reload();
	});

	$("#username").keyup(
		function()
		{
			username=$("#username").val();
			var tobesend = 'username='+username;

	   		$.ajax({
	            type: 'POST',
	            url: 'verifyUsernameHelper.php',
	            data: tobesend,
	            dataType: 'json',
	            success: function(response)
	            { 	
	                if(response.status == 1)
	                {
	                	$("#usernameerror").addClass("d-none");
	                    $("#username-save").attr("disabled",false);
	                    $("#validusername").removeClass("fa fa-times text-danger");
						$("#validusername").addClass("fa fa-check text-success");
	                }
	                else
	                {
	                	$("#validusername").removeClass("fa fa-check text-success");
						$("#validusername").addClass("fa fa-times text-danger");
	                	$("#username-save").attr("disabled",true);
	                    $("#usernameerror").removeClass("d-none");
	                }
	            }
		    });
		});
</script>

</div>	<!-- container ends -->

        </div>
        <!-- content end -->
    </div>
    <!-- page content -->
</div>
<!-- page wrapper end -->

<?php include("footer.php"); ?>

<script>
	$("#information-modal").on("hidden.bs.modal",
		function(){
	    location.reload();
	});
</script>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

<?php
	}
	else
		echo "Access Denied ! You Are Not A Seller";
}
else
	echo "Login First";
?>
</body>
</html>
