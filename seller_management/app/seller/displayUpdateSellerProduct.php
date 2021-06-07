<?php include("navigation.php"); ?>

<?php
  if(!(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2))
    redirect("login.php");
?>

<?php
	$showinformation=0;
	$message='';

	if(isset($_POST['editbasicdetails']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['product_id']=$_POST['pid'];
		$data['product_catalogue_id']=$_POST['pcid'];
		$data['product_name']=escape_string(trim($_POST['product_name']));
		$data['product_price']=$_POST['product_price'];
		$data['product_unit']=$_POST['product_unit'];
		$data['product_description']=escape_string(trim($_POST['product_desc']));

		$image=$_FILES['productimage']['tmp_name'];

		if(empty($image) || $image=="")
		{
			$data['imagestatus']=0;
			$data['image_name']=$_POST['hidden_product_image'];
		}
		else
		{
			$data['imagestatus']=1;
			$data['image_name']=$_POST['hidden_product_image'];
			$image = file_get_contents($image);
			$image= base64_encode($image);
			$data['productimage']=$image;
		}
		
		$url=DOMAIN.'/rest/seller/updateSellerSaveBasicProductDetailsRest.php';
		$output=getRestApiResponse($url,$data);
	
		if(isset($output['updateproduct']) && $output['updateproduct']['response_code']==200)
		{
			$showinformation=1;
        	$message='<p class="text-success">Product basic details updated successfully</p>';
		}
		else
		{
			$showinformation=1;
        	$message='<p class="text-danger">Unable to update product basic details</p>';
		}
	}
	else
	if(isset($_POST['editcategorydetails']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['product_id']=$_POST['pid'];
		$data['product_category']=escape_string(trim($_POST['product_category']));
		$data['product_sub_category']=escape_string(trim($_POST['product_sub_category']));

		$url=DOMAIN.'/rest/seller/updateSellerSaveCategoryProductDetailsRest.php';
		$output=getRestApiResponse($url,$data);
	
		if(isset($output['updateproduct']) && $output['updateproduct']['response_code']==200)
		{
			$showinformation=1;
        	$message='<p class="text-success">Product category setting updated successfully</p>';
		}
		else
		{
			$showinformation=1;
        	$message='<p class="text-danger">Unable to update product category setting</p>';
		}
	}
	else
	if(isset($_POST['editdiscountdetails']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['product_id']=$_POST['pid'];
		$data['discount_type']=$_POST['discounttype'];
		$data['product_price']=$_POST['price'];

		if(isset($_POST['discountpercent']))
			$data['discount']=$_POST['discountpercent'];
		
		$url=DOMAIN.'/rest/seller/updateSellerSaveDiscountProductDetailsRest.php';
		$output=getRestApiResponse($url,$data);
	
		if(isset($output['updateproduct']) && $output['updateproduct']['response_code']==200)
		{
			$showinformation=1;
        	$message='<p class="text-success">Product discount setting updated successfully</p>';
		}
		else
		{
			$showinformation=1;
        	$message='<p class="text-danger">Unable to update product discount setting</p>';
		}
	}
	else
	if(isset($_POST['edittaxdetails']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['product_id']=$_POST['pid'];
		$data['tax_type']=$_POST['tax_type'];

		if(isset($_POST['tax_percent']))
			$data['tax_percent']=$_POST['tax_percent'];

		$url=DOMAIN.'/rest/seller/updateSellerSaveTaxProductDetailsRest.php';
		$output=getRestApiResponse($url,$data);
	
		if(isset($output['updateproduct']) && $output['updateproduct']['response_code']==200)
		{
			$showinformation=1;
        	$message='<p class="text-success">Product tax setting updated successfully</p>';
		}
		else
		{
			$showinformation=1;
        	$message='<p class="text-danger">Unable to update product tax setting</p>';
		}
	}
	/*else
	if(isset($_POST['editshippingdetails']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['product_id']=$_POST['pid'];
		$data['free_shipping']=$_POST['free_shipping'];
		$data['return_available']=$_POST['return_available'];
		$data['cash_on_delivery']=$_POST['cash_on_delivery'];
		
		$url=DOMAIN.'/rest/seller/updateSellerSaveShippingSettingsRest.php';
		$output=getRestApiResponse($url,$data);
	
		if(isset($output['updateproduct']) && $output['updateproduct']['response_code']==200)
			echo '<script>alert("Product shipping setting updated successfully");</script>';
		else
			echo '<script>alert("Unable to update product shipping setting");</script>';

		header("refresh:0;url=displaySellerProducts.php");
	}*/
	else
	if(isset($_POST['editwarrantydetails']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['product_id']=$_POST['pid'];
		$data['product_brand']=$_POST['product_brand'];
		$data['product_model']=$_POST['product_model'];
		$data['warrant_type']=$_POST['warrant_type'];

		if(isset($_POST['warrant_duration']))
			$data['warranty_duration']=$_POST['warrant_duration'];

		if(isset($_POST['warranty_days_mon_yr']))
			$data['warranty_days_mon_yr']=$_POST['warranty_days_mon_yr'];
		
		$url=DOMAIN.'/rest/seller/updateSellerSaveProductSettingsRest.php';
		$output=getRestApiResponse($url,$data);
	
		if(isset($output['updateproduct']) && $output['updateproduct']['response_code']==200)
		{
			$showinformation=1;
        	$message='<p class="text-success">Product branding & warranty setting updated successfully</p>';
		}
		else
		{
			$showinformation=1;
        	$message='<p class="text-danger">Unable to update product branding & warranty setting</p>';
		}
	}
?>

<?php 
if(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2 && isset($_POST['pid']))
{	
?>

<div class="container-fluid">
	<div class="row mt-3">	
		<div class="col-12">
			<a href="displaySellerProducts.php" class="btn btn-success">Back To Products</a>
		</div>
	</div>

	<div class="row mt-2">	
		<div class="col-12">
			<div class="card">
				<div class="card-header bg-info text-white">
					Edit Product Details
				</div>
				<div class="card-body">
	<?php
		$data['user_id']=$_SESSION['user_id'];
		$data['productid']=$_POST['pid'];
		
		$url=DOMAIN.'/rest/seller/getProductDetailsRest.php';
		$output=getRestApiResponse($url,$data);

		if(isset($output['getproducts']) && count($output['getproducts'])>2 && $output['getproducts']['rows']!=0)
		{
	?>
	<hr>

	<div class="row">	
		<div class="col-12">
			<form enctype="multipart/form-data" action="" method="post" id="form1">
			  	<div class="row mt-3">
			  		<div class="col-6">
			    		<div class="form-group">
				      		<label for="pcid"><b>Select Collection:</b></label>
				      		<select class="form-control border border-left-0 border-top-0 border-right-0" name="pcid" id="pcid" required>
				      			<?php getCatalogues($output['getproducts'][0]['product_catalogue_id']); ?>
				      		</select>
			    		</div>
			   		</div>
			  		<div class="col-6">
			  			<input type="hidden" name="pid" value="<?php echo $_POST['pid']; ?>">
			    		<div class="form-group">
			      		<label for="product_name"><b>Product Name:</b></label>
			      		<input type="text" name="product_name" id="product_name" class="form-control border border-left-0 border-top-0 border-right-0" value="<?php echo $output['getproducts'][0]['product_name']; ?>" required>
			    		</div>
			   		</div>
			   		<div class="col-12 col-md-6">
			   			<div class="row">
			   				<div class="col-12">
						<div class="row">
							<div class="col-5">
							    <div class="form-group">
							        <label for="product_price"><b>Product Price:</b></label>
							        <input type="number" name="product_price" id="product_price" class="form-control border border-left-0 border-top-0 border-right-0" value="<?php echo $output['getproducts'][0]['product_price']; ?>" min="1" required>
							    </div>
							</div>
							<div class="col-1">
								<i class="fas fa-rupee-sign mt-5"></i>
							</div>
							<div class="col-6">
								<div class="form-group">
						      		<label for="product_unit"><b>Unit:</b></label>
						      		<select class="form-control border border-left-0 border-top-0 border-right-0" name="product_unit" id="product_unit">
						      			<?php
							        		if($output['getproducts'][0]['product_unit']==1)
							        			echo '<option value="1">Per Item</option><option value="2">Per Kg</option><option value="3">Per Litre</option><option value="4">Per Pair</option><option value="5">Pack Of 4</option>'; 
							        		else
							        		if($output['getproducts'][0]['product_unit']==2)
							        			echo '<option value="2">Per Kg</option><option value="1">Per Item</option><option value="3">Per Litre</option><option value="4">Per Pair</option><option value="5">Pack Of 4</option>';
										    else
							        		if($output['getproducts'][0]['product_unit']==3)
							        			echo '<option value="3">Per Litre</option><option value="1">Per Item</option><option value="2">Per Kg</option><option value="4">Per Pair</option><option value="5">Pack Of 4</option>';
										    else
							        		if($output['getproducts'][0]['product_unit']==4)
							        			echo '<option value="4">Per Pair</option><option value="1">Per Item</option><option value="2">Per Kg</option><option value="3">Per Litre</option><option value="5">Pack Of 4</option>';
										    else
							        		if($output['getproducts'][0]['product_unit']==5)
							        			echo '<option value="5">Pack Of 4</option><option value="1">Per Item</option><option value="2">Per Kg</option><option value="3">Per Litre</option><option value="4">Per Pair</option>';
							        	?>
						      		</select>
			    				</div>
							</div>
						</div>
					</div>
						<div class="col-6">
						    <div class="form-group">
						        <label for="product_image"><b>Choose Product Image:</b></label>
						        <input type="hidden" name="hidden_product_image" value="<?php echo $output['getproducts'][0]['productimage']; ?>">
						        <input type="file" accept="image/*" name="productimage" id="product_image" class="form-control border border-left-0 border-top-0 border-right-0" style="overflow:hidden;">
						    </div>
						</div>
						<div class="col-6">
							<div class="col-12 text-right">
								<img src="<?php echo SELLER_TO_ROOT.$output['getproducts'][0]['productimage']; ?>" height="80" width="100">
							</div>
						</div>
					</div>
					</div>
					<div class="col-12 col-md-6">
					    <div class="form-group">
					        <label for="product_desc"><b>Product Description:</b></label>
					        <textarea name="product_desc" id="product_desc" class="form-control" rows="5" cols="20"><?php echo $output['getproducts'][0]['product_description']; ?></textarea>
					    </div>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-6 text-right">
						<input type="reset" class="btn btn-primary w-50" value="Cancel">
					</div>
					<div class="col-6">
					    <div class="form-group">
					      <input type="submit" name="editbasicdetails" class="btn btn-danger btn-md" value="Save Basic Details">
					    </div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<hr>
	
	<div class="accordion" id="accordionheader1">
        <div class="card">
            <div class="card-header" id="headingOne">
            	<div class="row">
            		<div class="col-6">
	            		Category Settings
	            	</div>
	            	<div class="col-6 text-right">
	                	<button type="button" class="btn btn-sm text-right" data-toggle="collapse" data-target="#collapseOne"><i class="fas fa-plus"></i></button>	
	                </div>
	            </div>								
            </div>
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionheader1">
                <div class="card-body">
                    <div class="row">	
						<div class="col-12">	
							<form action="" method="post" id="form2">
								<input type="hidden" name="pid" value="<?php echo $_POST['pid']; ?>">
								<div class="row mt-3">
			  						<div class="col-12 col-md-6">
									    <div class="form-group row">
									        <label for="product_category" class="col-6 col-form-label"><b>Product Category:</b></label>
									        <div class="col-6 text-left">
									        <input type="text" name="product_category" id="product_category" value="<?php echo $output['getproducts'][0]['product_category']; ?>" class="form-control border border-left-0 border-top-0 border-right-0">
									    	</div>
									    </div>
									</div>
									<div class="col-12 col-md-6">
									    <div class="form-group row">
									        <label for="product_sub_category" class="col-6 col-form-label"><b>Product Sub Category:</b></label>
									        <div class="col-6">
									        <input type="text" name="product_sub_category" id="product_sub_category" value="<?php echo $output['getproducts'][0]['product_sub_category']; ?>" class="form-control border border-left-0 border-top-0 border-right-0">
									    	</div>
									    </div>
									</div>
			  					</div>
			  					<div class="row mt-3">
			  						<div class="col-6 text-right">
										<input type="reset" class="btn btn-primary w-50" value="Cancel">
									</div>
			  						<div class="col-6">
									    <div class="form-group">
									      <input type="submit" name="editcategorydetails" class="btn btn-danger btn-md" value="Save Category Details">
									    </div>
									</div>
			  					</div>
							</form>
						</div>
					</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" id="headingSecond">
            	<div class="row">
            		<div class="col-6">
            			Discount Settings
            		</div>
            		<div class="col-6 text-right">
                		<button type="button" class="btn btn-sm pull-right" data-toggle="collapse" data-target="#collapseSecond"><i class="fas fa-plus"></i></button>
                	</div>		
                </div>							
            </div>
            <div id="collapseSecond" class="collapse" aria-labelledby="headingSecond" data-parent="#accordionheader1">
                <div class="card-body">
                    <div class="row">	
						<div class="col-12">	
							<form action="" method="post" id="form3">
								<input type="hidden" name="pid" value="<?php echo $_POST['pid']; ?>">
								<div class="form-group row">
			                        <label for="discounttype" class="col-3 col-form-label"><b>Discount Type:</b></label>
			                        <div class="col-4">
				                        <select name="discounttype" id="discounttype" class="form-control border border-left-0 border-top-0 border-right-0">
				                        	<?php
								        		if($output['getproducts'][0]['discount_type']=="" || $output['getproducts'][0]['discount_type']=="None")
								        			echo '<option value="None">None</option><option value="Percentage">Percentage</option><option value="Flat">Flat</option>'; 
								        		else
								        		if($output['getproducts'][0]['discount_type']=="Percentage")
								        			echo '<option value="Percentage">Percentage</option><option value="Flat">Flat</option><option value="None">None</option>';
								        		else
								        			echo '<option value="Flat">Flat</option><option value="Percentage">Percentage</option><option value="None">None</option>';
								        	?>
				                        </select>
			                        </div>
			                        <div class="col-4">
			                            <div class="row">
			                                <div class="col-8 col-md-6">
			                                	<?php
			                                		if($output['getproducts'][0]['discount_type']=="Percentage")
			                                		{
			                                			echo '<input type="hidden" name="price" value="'.$output['getproducts'][0]['product_price'].'"><input type="number" name="discountpercent" id="discountpercent" class="form-control border border-left-0 border-top-0 border-right-0" value="'.$output["getproducts"][0]["discount"].'" min="1" max="100">';
			                                		}
			                                		else
			                                		if($output['getproducts'][0]['discount_type']=="Flat")
			                                		{
			                                			echo '<input type="hidden" name="price" value="'.$output['getproducts'][0]['product_price'].'"><input type="number" name="discountpercent" id="discountpercent" class="form-control border border-left-0 border-top-0 border-right-0" value="'.$output["getproducts"][0]["discount"].'" min="1">';
			                                		}
			                                		else
			                                		if($output['getproducts'][0]['discount_type']=="None" || $output['getproducts'][0]['discount_type']=="")
			                                		{
			                                			echo '<input type="hidden" name="price" value="'.$output['getproducts'][0]['product_price'].'"><input type="number" name="discountpercent" id="discountpercent" class="form-control border border-left-0 border-top-0 border-right-0 d-none" value="'.$output["getproducts"][0]["discount"].'" min="1" max="100" disabled>';
			                                		}
			                              		?>
			                                </div>
			                                <div class="col-4 col-md-6">
			                                	<?php
			                                		if($output['getproducts'][0]['discount_type']=="Percentage")
			                                    		echo '<span class="fa-lg text-secondary" id="percentsymbol">&#37;</span>';
			                                    	else
			                                    	if($output['getproducts'][0]['discount_type']=="Flat" || $output['getproducts'][0]['discount_type']=="None" || $output['getproducts'][0]['discount_type']=="")
			                                    		echo '<span class="fa-lg text-secondary d-none" id="percentsymbol">&#37;</span>';
			                                    ?>
			                                </div>
			                            </div>
			                        </div>
			                    </div>
			                    <div class="row mt-3">
			                    	<div class="col-6 text-right">
										<input type="reset" class="btn btn-primary w-50" value="Cancel">
									</div>
									<div class="col-6">
									    <div class="form-group">
									      <input type="submit" name="editdiscountdetails" class="btn btn-danger btn-md" value="Save Discount Details">
									    </div>
									</div>
			  					</div>
							</form>
						</div>
					</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" id="headingThird">
            	<div class="row">
            		<div class="col-6">
            			Tax Settings
            		</div>
            		<div class="col-6 text-right">
                		<button type="button" class="btn btn-sm pull-right" data-toggle="collapse" data-target="#collapseThird"><i class="fas fa-plus"></i></button>
                	</div>
                </div>							
            </div>
            <div id="collapseThird" class="collapse" aria-labelledby="headingThird" data-parent="#accordionheader1">
                <div class="card-body">
                    <div class="row">	
						<div class="col-12">	
							<form action="" method="post" id="form4">
								<input type="hidden" name="pid" value="<?php echo $_POST['pid']; ?>">
								<div class="form-group row">
			                        <label for="taxtype" class="col-3 col-form-label"><b>Tax Type:</b></label>
			                        <div class="col-4">
				                        <select name="tax_type" id="taxtype" class="form-control border border-left-0 border-top-0 border-right-0">
				                            <?php
								        		if($output['getproducts'][0]['tax_type']=="" || $output['getproducts'][0]['tax_type']=="None")
								        			echo '<option value="None">None</option><option value="Percentage">Percentage</option><option value="GST">GST</option>'; 
								        		else
								        		if($output['getproducts'][0]['tax_type']=="Percentage")
								        			echo '<option value="Percentage">Percentage</option><option value="GST">GST</option><option value="None">None</option>';
								        		else
								        			echo '<option value="GST">GST</option><option value="Percentage">Percentage</option><option value="None">None</option>';
								        	?>
				                        </select>
			                        </div>
			                        <div class="col-4">
			                            <div class="row">
			                                <div class="col-8 col-md-6">
			                                    <?php
			                                		if($output['getproducts'][0]['tax_type']=="Percentage" || $output['getproducts'][0]['tax_type']=="GST")
			                                		{
			                                			echo '<input type="number" name="tax_percent" id="taxpercent" class="form-control border border-left-0 border-top-0 border-right-0" value="'.$output["getproducts"][0]["tax_percent"].'" min="1" max="100">';
			                                		}
			                                		else
			                                		if($output['getproducts'][0]['tax_type']=="None" || $output['getproducts'][0]['tax_type']=="")
			                                		{
			                                			echo '<input type="number" name="tax_percent" id="taxpercent" class="form-control border border-left-0 border-top-0 border-right-0 d-none" value="'.$output["getproducts"][0]["tax_percent"].'" min="1" max="100" disabled>';
			                                		}
			                              		?>
			                                </div>
			                                <div class="col-4 col-md-6">
			                                    <?php
			                                		if($output['getproducts'][0]['tax_type']=="Percentage" || $output['getproducts'][0]['tax_type']=="GST")
			                                    		echo '<span class="fa-lg text-secondary" id="percentsymbol1">&#37;</span>';
			                                    	else
			                                    	if($output['getproducts'][0]['tax_type']=="None" || $output['getproducts'][0]['tax_type']=="")
			                                    		echo '<span class="fa-lg text-secondary d-none" id="percentsymbol1">&#37;</span>';
			                                    ?>
			                                </div>
			                            </div>
			                        </div>
			                    </div>
			                    <div class="row mt-3">
			                    	<div class="col-6 text-right">
										<input type="reset" class="btn btn-primary w-50" value="Cancel">
									</div>
									<div class="col-6">
									    <div class="form-group">
									      <input type="submit" name="edittaxdetails" class="btn btn-danger btn-md" value="Save Tax Details">
									    </div>
									</div>
			  					</div>
							</form>
						</div>
					</div>
                </div>
            </div>
        </div>

        <!-- <div class="card">
            <div class="card-header" id="headingFourth">
            	Shipping Settings
                <button type="button" class="btn btn-sm pull-right" data-toggle="collapse" data-target="#collapseFourth"><i class="fa fa-plus"></i></button>									
            </div>
            <div id="collapseFourth" class="collapse" aria-labelledby="headingFourth" data-parent="#accordionheader1">
                <div class="card-body">
                    <div class="row">	
						<div class="col-12">	
							<form action="" method="post" id="form5">
								<input type="hidden" name="pid" value="<?php //echo $_POST['pid']; ?>">
								<div class="row">
				                    <div class="col-12 col-md-4">
				                        <div class="form-group row">
				                            <label for="free_shipping" class="col-8 col-form-label"><b>Free Shipping:</b></label>
				                            <div class="col-4">
				                            	<?php 
				                            		/*if($output['getproducts'][0]['free_shipping']==1)
				                            		{
				                            			echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" checked id="get_free_shipping">
				                                			<input type="hidden" name="free_shipping" id="set_free_shipping" value="1">';
				                            		}
				                            		else
				                            		{
				                            			echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" id="get_free_shipping">
				                                			<input type="hidden" name="free_shipping" id="set_free_shipping" value="0">';
				                            		}*/
				                                ?>
				                            </div>
				                        </div>
				                    </div>
				                    <div class="col-12 col-md-4">
				                        <div class="form-group row">
				                            <label for="return_available" class="col-8 col-form-label"><b>Return Available:</b></label>
				                            <div class="col-4">
				                                <?php 
				                            		/*if($output['getproducts'][0]['return_available']==1)
				                            		{
				                            			echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" checked id="get_return_available">
				                                			<input type="hidden" name="return_available" id="set_return_available" value="1">';
				                            		}
				                            		else
				                            		{
				                            			echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" id="get_return_available">
				                                			<input type="hidden" name="return_available" id="set_return_available" value="0">';
				                            		}*/
				                                ?>
				                            </div>
				                        </div>
				                    </div>
				                    <div class="col-12 col-md-4">
				                        <div class="form-group row">
				                            <label for="cash_on_delivery" class="col-8 col-form-label"><b>Cash On Delivery:</b></label>
				                            <div class="col-4">
				                                <?php 
				                            		/*if($output['getproducts'][0]['cash_on_delivery']==1)
				                            		{
				                            			echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" checked id="get_cash_on_delivery">
				                                			<input type="hidden" name="cash_on_delivery" id="set_cash_on_delivery" value="1">';
				                            		}
				                            		else
				                            		{
				                            			echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" id="get_cash_on_delivery">
				                                			<input type="hidden" name="cash_on_delivery" id="set_cash_on_delivery" value="0">';
				                            		}*/
				                                ?>
				                            </div>
				                        </div>
				                    </div>
				                </div> 
				                <div class="row mt-3">
				                	<div class="col-6 text-right">
										<input type="reset" class="btn btn-primary w-50" value="Cancel">
									</div>
				                	<div class="col-6">
									    <div class="form-group">
									      <input type="submit" name="editshippingdetails" class="btn btn-danger btn-md" value="Save Shipping Settings">
									    </div>
									</div>
				                </div>   
							</form>
						</div>
					</div>
                </div>
            </div>
        </div> -->

        <div class="card">
            <div class="card-header" id="headingFifth">
            	<div class="row">
            		<div class="col-6">
            			Branding & Warranty Settings
            		</div>
            		<div class="col-6 text-right">
                		<button type="button" class="btn btn-sm pull-right" data-toggle="collapse" data-target="#collapseFifth"><i class="fas fa-plus"></i></button>
                	</div>	
                </div>								
            </div>
            <div id="collapseFifth" class="collapse" aria-labelledby="headingFifth" data-parent="#accordionheader1">
                <div class="card-body">
                    <div class="row">	
						<div class="col-12">	
							<form action="" method="post" id="form6">
								<input type="hidden" name="pid" value="<?php echo $_POST['pid']; ?>">
								<div class="row mt-3">
			  						<div class="col-12 col-md-6">
									    <div class="form-group row">
									        <label for="product_brand" class="col-6 col-form-label"><b>Product Brand:</b></label>
									        <div class="col-6">
									        <input type="text" name="product_brand" id="product_brand" value="<?php echo $output['getproducts'][0]['product_brand']; ?>" class="form-control border border-left-0 border-top-0 border-right-0">
									    	</div>
									    </div>
									</div>
									<div class="col-12 col-md-6">
									    <div class="form-group row">
									        <label for="product_model" class="col-6 col-form-label"><b>Product Model:</b></label>
									        <div class="col-6">
									        <input type="text" name="product_model" id="product_model" value="<?php echo $output['getproducts'][0]['product_model']; ?>" class="form-control border border-left-0 border-top-0 border-right-0">
									    	</div>
									    </div>
									</div>
			  					</div>
			  					<div class="row">
			  						<div class="col-12 col-md-6">
				                        <div class="form-group row">
				                            <label for="warrant_type" class="col-6 col-md-4 col-form-label"><b>Warranty Type:</b></label>
				                            <div class="col-6 col-md-8">
				                                <select name="warrant_type" id="warrant_type" class="form-control border border-left-0 border-top-0 border-right-0">
				                                    <?php
										        		if($output['getproducts'][0]['warrant_type']=="" || $output['getproducts'][0]['warrant_type']=="Not Applicable")
										        			echo '<option value="Not Applicable">Not Applicable</option><option value="Warranty">Warranty</option><option value="Gaurantee">Gaurantee</option>'; 
										        		else
										        		if($output['getproducts'][0]['warrant_type']=="Warranty")
										        			echo '<option value="Warranty">Warranty</option><option value="Gaurantee">Gaurantee</option><option value="Not Applicable">Not Applicable</option>';
										        		else
										        			echo '<option value="Gaurantee">Gaurantee</option><option value="Warranty">Warranty</option><option value="Not Applicable">Not Applicable</option>';
										        	?>
				                                </select>
				                            </div>
				                        </div>
				                    </div>
				                    <?php
					        			if($output['getproducts'][0]['warrant_type']=="Warranty" || $output['getproducts'][0]['warrant_type']=="Gaurantee")
					        			{
								    ?>
					                    <div class="col-12 col-md-6">
					                        <div class="form-group row">
					                            	<?php 
					                            		if($output['getproducts'][0]['warrant_type']=="Warranty")
					                            			echo "<label for=\"warrant_duration\" class=\"col-5 col-form-label text-md-right\" id=\"warrant_duration_label\"><b>Warranty Duration:</b></label>";
					                            		else
					                            		if($output['getproducts'][0]['warrant_type']=="Gaurantee")
					                            			echo "<label for=\"warrant_duration\" class=\"col-5 col-form-label text-md-right\" id=\"warrant_duration_label\"><b>Gaurantee Duration:</b></label>";
					                            		else
					                            			echo "<label for=\"warrant_duration\" class=\"col-5 col-form-label text-md-right d-none\" id=\"warrant_duration_label\"><b>Warranty Duration:</b></label>";
					                            	?>
					                            <div class="col-3">
					                            <input type="number" name="warrant_duration" id="warrant_duration" class="form-control border border-left-0 border-top-0 border-right-0" value="<?php echo $output['getproducts'][0]['warranty_duration']; ?>" min="1">
					                            </div>
					                            <div class="col-4">
					                                <select name="warranty_days_mon_yr" class="form-control border border-left-0 border-top-0 border-right-0" id="warranty_days_mon_yr">
					                                    <?php
											        		if($output['getproducts'][0]['warranty_days_mon_yr']=="" || $output['getproducts'][0]['warranty_days_mon_yr']=="Days")
											        			echo '<option value="Days">Days</option><option value="Months">Months</option><option value="Years">Years</option>'; 
											        		else
											        		if($output['getproducts'][0]['warranty_days_mon_yr']=="Months")
											        			echo '<option value="Months">Months</option><option value="Days">Days</option><option value="Years">Years</option>';
											        		else
											        			echo '<option value="Years">Years</option><option value="Months">Months</option><option value="Days">Days</option>';
											        	?>
					                                </select>
					                            </div>
					                        </div>
					                    </div>
				                    <?php
					                	}
					                	else if($output['getproducts'][0]['warrant_type']=="Not Applicable" || $output['getproducts'][0]['warrant_type']=="")
					                	{
				                	?>
				                		<div class="col-12 col-md-6">
					                        <div class="form-group row">
					                            <label for="warrant_duration" class="col-5 col-form-label text-md-right d-none" id="warrant_duration_label"><b>Warranty Duration:</b></label>
					                            <div class="col-3">
					                            <input type="number" name="warrant_duration" id="warrant_duration" class="form-control border border-left-0 border-top-0 border-right-0 d-none" value="<?php echo $output['getproducts'][0]['warranty_duration']; ?>" min="1" disabled>
					                            </div>
					                            <div class="col-4">
					                                <select name="warranty_days_mon_yr" class="form-control border border-left-0 border-top-0 border-right-0 d-none" id="warranty_days_mon_yr" disabled>
					                                    <?php
											        		if($output['getproducts'][0]['warranty_days_mon_yr']=="" || $output['getproducts'][0]['warranty_days_mon_yr']=="Days")
											        			echo '<option value="Days">Days</option><option value="Months">Months</option><option value="Years">Years</option>'; 
											        		else
											        		if($output['getproducts'][0]['warranty_days_mon_yr']=="Months")
											        			echo '<option value="Months">Months</option><option value="Days">Days</option><option value="Years">Years</option>';
											        		else
											        			echo '<option value="Years">Years</option><option value="Months">Months</option><option value="Days">Days</option>';
											        	?>
					                                </select>
					                            </div>
					                        </div>
					                    </div>
				                	<?php
				                		}
				                	?>
			  					</div>
			  					<div class="row mt-3">
			  						<div class="col-6 text-right">
										<input type="reset" class="btn btn-primary w-50" value="Cancel">
									</div>
			  						<div class="col-6">
									    <div class="form-group">
									      <input type="submit" name="editwarrantydetails" class="btn btn-danger btn-md" value="Save Settings">
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

	<?php
		}
		else
		{
			echo '<div class="row"><div class="col-12 text-center text-danger"><h4>Product Not Found</h4></div></div>';
		}
	?>

<?php
    if($showinformation==1)
        echo '<script>
                $("#information").html(\''.$message.'\');
                $("#information-modal").modal("show");
            </script>';
?>
			</div>
		</div>
	</div>	<!-- card ends -->
</div>

</div>

        </div>
        <!-- content end -->
    </div>
    <!-- page content -->
</div>
<!-- page wrapper end -->

<?php include("footer.php"); ?>

<script>
	$(document).ready(function(){
        // Add minus icon for collapse element which is open by default
        $(".collapse.show").each(function(){
        	$(this).prev(".card-header").find(".fas").addClass("fa-minus").removeClass("fa-plus");
        });
        
        // Toggle plus minus icon on show hide of collapse element
        $(".collapse").on('show.bs.collapse', function(){
        	$(this).prev(".card-header").find(".fas").removeClass("fa-plus").addClass("fa-minus");
        }).on('hide.bs.collapse', function(){
        	$(this).prev(".card-header").find(".fas").removeClass("fa-minus").addClass("fa-plus");
        });
    });
</script>

<script>
	// $(".cancelactivity").on("click",function()
	// {
	// 	cancelid=$(this).attr("id");
	// 	formnum=cancelid.substr(cancelid.length-1);
	// 	$("#form"+formnum)[0].reset();
	// });
</script>

<script>
	// $('#get_free_shipping').change(function(){
	//   if($(this).prop('checked'))
	//   {
	//    $('#set_free_shipping').val(1);
	//   }
	//   else
	//   {
	//    $('#set_free_shipping').val(0);
	//   }
	//  });

	// $('#get_return_available').change(function(){
	//   if($(this).prop('checked'))
	//   {
	//    $('#set_return_available').val(1);
	//   }
	//   else
	//   {
	//    $('#set_return_available').val(0);
	//   }
	//  });

	// $('#get_cash_on_delivery').change(function(){
	//   if($(this).prop('checked'))
	//   {
	//    $('#set_cash_on_delivery').val(1);
	//   }
	//   else
	//   {
	//    $('#set_cash_on_delivery').val(0);
	//   }
	//  });
</script>

<script>
	$("#discounttype").change(function()
	{
		type=$(this).val();
		if(type=="None")
		{
			if(!$("#discountpercent").hasClass("d-none"))
			{
				$("#discountpercent").addClass("d-none");
				$("#discountpercent").attr("disabled",true);
			}

			if(!$("#percentsymbol").hasClass("d-none"))
				$("#percentsymbol").addClass("d-none");
		}
		else
		if(type=="Flat")
		{
			if($("#discountpercent").hasClass("d-none"))
			{
				$("#discountpercent").removeClass("d-none");
				$("#discountpercent").attr("disabled",false);
				$("#discountpercent").attr("max",false);
			}

			if(!$("#percentsymbol").hasClass("d-none"))
				$("#percentsymbol").addClass("d-none");
		}
		else
		if(type=="Percentage")
		{
			if($("#discountpercent").hasClass("d-none"))
			{
				$("#discountpercent").removeClass("d-none");
				$("#discountpercent").attr("disabled",false);
				$("#discountpercent").attr("max",true);
			}

			if($("#percentsymbol").hasClass("d-none"))
				$("#percentsymbol").removeClass("d-none");
		}
	});

	$("#taxtype").change(function()
	{
		type=$(this).val();
		if(type=="None")
		{
			if(!$("#taxpercent").hasClass("d-none"))
			{
				$("#taxpercent").attr("disabled",true);
				$("#taxpercent").addClass("d-none");
			}

			if(!$("#percentsymbol1").hasClass("d-none"))
				$("#percentsymbol1").addClass("d-none");
		}
		else
		if(type=="GST")
		{
			if($("#taxpercent").hasClass("d-none"))
			{
				$("#taxpercent").removeClass("d-none");
				$("#taxpercent").attr("disabled",false);
			}

			if($("#percentsymbol1").hasClass("d-none"))
				$("#percentsymbol1").removeClass("d-none");
		}
		else
		if(type=="Percentage")
		{
			if($("#taxpercent").hasClass("d-none"))
			{
				$("#taxpercent").removeClass("d-none");
				$("#taxpercent").attr("disabled",false);
			}

			if($("#percentsymbol1").hasClass("d-none"))
				$("#percentsymbol1").removeClass("d-none");
		}
	});

	$("#warrant_type").change(function()
	{
		type=$(this).val();
		if(type=="Not Applicable")
		{
			if(!$("#warrant_duration_label").hasClass("d-none"))
				$("#warrant_duration_label").addClass("d-none");

			if(!$("#warrant_duration").hasClass("d-none"))
			{
				$("#warrant_duration").attr("disabled",true);
				$("#warrant_duration").addClass("d-none");
			}

			if(!$("#warranty_days_mon_yr").hasClass("d-none"))
			{
				$("#warranty_days_mon_yr").attr("disabled",true);
				$("#warranty_days_mon_yr").addClass("d-none");
			}
		}
		else
		if(type=="Warranty")
		{
			if($("#warrant_duration_label").hasClass("d-none"))
				$("#warrant_duration_label").removeClass("d-none");

			$("#warrant_duration_label").html("<b>Warranty Duration:</b>");

			if($("#warrant_duration").hasClass("d-none"))
			{
				$("#warrant_duration").removeClass("d-none");
				$("#warrant_duration").attr("disabled",false);
			}

			if($("#warranty_days_mon_yr").hasClass("d-none"))
			{
				$("#warranty_days_mon_yr").removeClass("d-none");
				$("#warranty_days_mon_yr").attr("disabled",false);
			}
		}
		else
		if(type=="Gaurantee")
		{
			if($("#warrant_duration_label").hasClass("d-none"))
				$("#warrant_duration_label").removeClass("d-none");

			$("#warrant_duration_label").html("<b>Gaurantee Duration:</b>");
			
			if($("#warrant_duration").hasClass("d-none"))
			{
				$("#warrant_duration").removeClass("d-none");
				$("#warrant_duration").attr("disabled",false);
			}

			if($("#warranty_days_mon_yr").hasClass("d-none"))
			{
				$("#warranty_days_mon_yr").removeClass("d-none");
				$("#warranty_days_mon_yr").attr("disabled",false);
			}
		}
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
	redirect("displaySellerProducts.php");
?>
</body>
</html>
