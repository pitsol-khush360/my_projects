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

	if(isset($_POST['setcataloguestatus']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['catalogue_id']=$_POST['cid'];
		$data['catalogue_status']=$_POST['catalogue_status'];

		$url=DOMAIN.'/rest/seller/updateCatalogueStatusRest.php';
		$output=getRestApiResponse($url,$data);
		
		if(isset($output['updatecatalogue']) && $output['updatecatalogue']['response_code']==200)
		{
			if($_POST['catalogue_status']=="Active")
			{
				$showinformation=1;
				$message='<p class="text-success">Collection activated successfully</p>';
			}
			else
			if($_POST['catalogue_status']=="Inactive")
			{
				$showinformation=1;
				$message='<p class="text-secondary">Collection deactivated successfully</p>';
			}
		}
		else
		{
			$showinformation=1;
			$message='<p class="text-danger">Unable to update collection status</p>';
		}
	}

	$sid=$_SESSION['user_id'];

	if(isset($_POST['save_catalogue']) && $_POST['save_catalogue']=="savecatalogue")
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['catalogue_Name']=$_POST['catalogue_Name'];

		$image=$_FILES['catalogue_image']['tmp_name'];

		if(empty($image) || $image=="")
			$data['imagestatus']=0;
		else
		{
			$data['imagestatus']=1;
			$image = file_get_contents($image);
			$image= base64_encode($image);
			$data['image']=$image;
		}

		$url=DOMAIN.'/rest/seller/CreateCatalogueDetailsScreenRest.php';
		$output=getRestApiResponse($url,$data);
		
		// If status code is 200 then successful.
		if(isset($output['catalogue']) && $output['catalogue']['response_code']==200)
		{
			$showinformation=1;
			$message='<p class="text-success">Collection added successfully</p>';
		}
		else
		{
			$showinformation=1;
			$message='<p class="text-danger">Unable to add collection</p>';
		}
	}
	else
	if(isset($_POST['edit_catalogue']) && $_POST['edit_catalogue']=="editcatalogue")
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['catalogue_Name']=$_POST['catalogue_Name'];
		$data['catalogue_id']=$_POST['catalogue_id'];

		$image=$_FILES['catalogue_image']['tmp_name'];

		if(empty($image) || $image=="")
		{
			$data['imagestatus']=0;
			$data['image_name']=$_POST['hidden_catalogue_image'];
		}
		else
		{
			$data['imagestatus']=1;
			$data['image_name']=$_POST['hidden_catalogue_image'];
			$image = file_get_contents($image);
			$image= base64_encode($image);
			$data['image']=$image;
		}

		$url=DOMAIN.'/rest/seller/UpdateCatalogueDetailsRest.php';
		$output=getRestApiResponse($url,$data);
		
		// If status code is 200 then successful.
		if(isset($output['updatecatalogue']) && $output['updatecatalogue']['response_code']==200)
		{
			$showinformation=1;
			$message='<p class="text-success">Collection updated successfully</p>';
		}
		else
		{
			$showinformation=1;
			$message='<p class="text-danger">Unable to update collection</p>';
		}
	}	
	else
	if(isset($_POST['delete_catalogue']))
	{
		$data['user_id']=$_SESSION['user_id']; 
		$data['catalogue_id']=$_POST['cid'];
		$data['confirm']='';

		$url=DOMAIN.'/rest/seller/DeleteCatalogueDetailsRest.php';
		$output=getRestApiResponse($url,$data);

		// If status code is 200 then successful.
		if(isset($output['deletecatalogue']) && $output['deletecatalogue']['response_code']==200)
		{
			$showinformation=1;
			$message='<p class="text-success">Collection deleted successfully</p>';
		}
		else
		if(isset($output['deletecatalogue']) && $output['deletecatalogue']['response_code']==405)
		{
			$showinformation=1;
			$message='<p class="text-danger">'.$output['deletecatalogue']['response_desc'].'</p>';
		}
		else
		{
			$showinformation=1;
			$message='<p class="text-danger">Unable to delete collection</p>';
		}
	}
?>

<div class="container-fluid">
	<div class="row">	
		<div class="col-12 border border-dark rounded">

		<?php
			if(!isset($_POST['edit_catalogue_form']))
			{
		?>	
		<form enctype="multipart/form-data" action="" method="post" id="save-collection-form">
		  	<div class="row mt-5">
		  		<div class="col-12 col-md-5">
		    		<input type="hidden" name="sid" value="<?php echo $sid; ?>">
		    		<div class="form-group row">
		      		<label for="catalogue_Name" class="col-6 col-form-label"><b>Collection Name:</b></label>
		      		<div class="col-6">
		      		<input type="text" name="catalogue_Name" id="catalogue_Name" class="form-control border border-top-0 border-left-0 border-right-0">
		      		</div>
		    		</div>
		   		</div>
		   		<div class="col-12 col-md-7">
				    <div class="form-group row">
				        <label for="catalogue_image" class="col-6 col-form-label text-md-right"><b>Choose Collection Image:</b></label>
				        <div class="col-6">
				        <input type="file" accept="image/*" name="catalogue_image" id="catalogue_image" class="form-control border border-top-0 border-left-0 border-right-0" title="Upload Collection Image" style="overflow:hidden;"><br>
				    	</div>
				    </div>
				</div>
		  	</div>

			<div class="row">
				<div class="col-12">
				    <div class="form-group text-right">
				      <input type="hidden" name="save_catalogue" value="savecatalogue">
				      <button class="btn btn-primary btn-md text-center enable-save-edit-confirmation" id="for-save-collection-form">Add Collection</button>
				    </div>
				</div>
			</div>
		</form>
		<?php
			}
			else
			{
				$data1['user_id']=$_SESSION['user_id'];
				$data1['catalogueid']=$_POST['cid'];
				$url=DOMAIN.'/rest/seller/getCatalogueListScreenRest.php';
				$output=getRestApiResponse($url,$data1);
				
				if(isset($output['getcatalogue']) && count($output['getcatalogue'])>2)
				{
					$id=$output['getcatalogue'][0]['catalogue_id'];
					$name=$output['getcatalogue'][0]['catalogue_Name'];
					$image=$output['getcatalogue'][0]['catalogue_image'];
		?>
		<form enctype="multipart/form-data" action="" method="post" id="edit-collection-form">
		  	<div class="row mt-5">
		  		<div class="col-12 col-md-5">
		    		<input type="hidden" name="catalogue_id" value="<?php echo $id; ?>">
		    		<div class="form-group row">
		      		<label for="catalogue_Name" class="col-6 col-form-label"><b>Collection Name:</b></label>
		      		<div class="col-6">
		      		<input type="text" name="catalogue_Name" id="catalogue_Name" class="form-control border border-top-0 border-left-0 border-right-0" value="<?php echo $name; ?>" required>
		      		</div>
		    		</div>
		   		</div>
		   		<div class="col-12 col-md-7">
				    <div class="form-group row">
				        <label for="catalogue_image" class="col-12 col-md-5 col-form-label text-md-right"><b>Edit Collection Image:</b></label>
				        <div class="col-2">
				        	<img src="<?php echo SELLER_TO_ROOT.$image; ?>" class="list-image">
				        </div>
				        <div class="col-10 col-md-5">
				        <input type="file" accept="image/*" name="catalogue_image" id="catalogue_image" class="form-control border border-top-0 border-left-0 border-right-0" title="Change Collection Image" style="overflow:hidden;">
				        <input type="hidden" name="hidden_catalogue_image" value="<?php echo $image; ?>">
				    	</div>
				    </div>
				</div>
		  	</div>

			<div class="row">
				<div class="col-12">
				    <div class="form-group text-right">
				      <input type="hidden" name="edit_catalogue" value="editcatalogue">
				      <button class="btn btn-primary btn-md text-center enable-save-edit-confirmation" id="for-edit-collection-form">Update Collection</button>
				    </div>
				</div>
			</div>
		</form>
		<?php
				}
			}
		?>
	</div>
	</div>	

	<div class="row mt-3">
		<div class="col-12 text-center">
			<a href="displaySellerCollectionGallery.php" class="btn btn-primary">Quick Add Via Collection Gallery</a>
		</div>
	</div>

<?php 
	$data2['user_id']=$_SESSION['user_id'];
	$data2['start']=0;

	if(isset($_SESSION['pages']))
		$data2['end']=$_SESSION['pages'];
	else
		$data2['end']=10;

	$url=DOMAIN.'/rest/seller/getCatalogueListScreenRest.php';

	$output=getRestApiResponse($url,$data2);
	
	if(isset($output['getcatalogue']) && count($output['getcatalogue'])>2)
	{
?>
	<div class="row mt-5">
		<div class="col-7 col-md-4">
			Show
			<select id="pages" placeholder="">
				<option><?php if(isset($_SESSION['pages']))
								echo $_SESSION['pages']; ?>
				</option>
				<option value="1">1</option>
				<option value="10">10</option>
				<option value="25">25</option>
				<option value="50">50</option>
				<option value="100">100</option>
			</select>
			Entries
		</div>
		<div class="col-6 offset-md-2 col-md-3 mt-3 mt-md-0">
			<div class="input-group">
		    	<select class="form-control" id="searchbystatusfield">
		    		<option value="">Search By Status</option>
		    		<option value="Active">Active</option>
		    		<option value="Inactive">Inactive</option>
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
		    	<input type="text" class="form-control" placeholder="Search By Collection" id="searchfield">
			    <div class="input-group-append">
			      <button class="btn btn-secondary" type="button" id="search">
			        <i class="fa fa-search"></i>
			      </button>
			    </div>
		  	</div>
		</div>
	</div>

	<div class="row mt-4">
		<div class="col-12 table-responsive">
			<table class="table table-hover table-bordered text-center table-sm">
				<thead>
					<tr>
						<th>Collection Image</th>
						<th>Collection Name</th>
						<th title="Only active collections & its products will be displayed on your website">Status</th>
						<th colspan="5">Action</th>
						<th title="Share Collections On Whatsapp">Share</th>
					</tr>
				</thead>
				<tbody id="catalogue_body">
					<?php 
						if(isset($output['getcatalogue']['rows']) && $output['getcatalogue']['rows']!=0)
						{
							for($i=0;$i<$output['getcatalogue']['rows'];$i++)
							{
								$seller_to_root=SELLER_TO_ROOT;
								$record="";
								$collectionurl=DOMAIN."/app/?s=".$_SESSION['username']."&cid=".$output['getcatalogue'][$i]['catalogue_id'];
								$collectionurl=urlencode($collectionurl);	// will encode non-alphanumeric chars

                                $text="Check out *".$_SESSION['business_name']."'s* latest product collection %0DOrder 24x7 - Click on the link to place an order%0D%0D".$collectionurl.".\n%20%0D%0D";
    
								$record.=<<< record
								<tr>
									<td><img src="{$seller_to_root}{$output['getcatalogue'][$i]['catalogue_image']}" class="list-image"></td>
									<td>{$output['getcatalogue'][$i]['catalogue_Name']}</td>
record;

								if($output['getcatalogue'][$i]['catalogue_status']=="Active")
								{
									$record.=<<< record
									<td><p class='text-success'>Active</p></td>
									<td>
										<form action="displaySellerCatalogues.php" method="post">
											<input type="hidden" name="cid" value="{$output['getcatalogue'][$i]['catalogue_id']}">
											<input type="hidden" name="catalogue_status" value="Inactive">
											<button type="submit" name="setcataloguestatus" class="btn btn-warning">Deactivate</button>
										</form>
									</td>
									<td>
										<form action="displaySellerCatalogues.php" method="post">
											<input type="hidden" name="cid" value="{$output['getcatalogue'][$i]['catalogue_id']}">
											<button type="submit" name="edit_catalogue_form" class="btn btn-primary">Edit</button>
										</form>
									</td>
record;
								}
								else
								if($output['getcatalogue'][$i]['catalogue_status']=="Inactive")
								{
									$record.=<<< record
									<td><p class='text-danger'>Inactive</p></td>
									<td>
										<form action="displaySellerCatalogues.php" method="post">
											<input type="hidden" name="cid" value="{$output['getcatalogue'][$i]['catalogue_id']}">
											<input type="hidden" name="catalogue_status" value="Active">
											<button type="submit" name="setcataloguestatus" class="btn btn-info">Activate</button>
										</form>
									</td>
									<td>
										<button class="btn btn-secondary" disabled>Edit</button>
									</td>
record;
								}
									
								if($output['getcatalogue'][$i]['catalogue_status']=="Active")
								{
									$record.=<<< record
									<td>
									<button catid="{$output['getcatalogue'][$i]['catalogue_id']}" catname="{$output['getcatalogue'][$i]['catalogue_Name']}" class="btn btn-primary addproduct">Add Products</button>
									</td>
									<td>
										<form action="displaySellerProducts.php" method="post">
											<input type="hidden" name="cid" value="{$output['getcatalogue'][$i]['catalogue_id']}">
											<button type="submit" name="showproductsbycid" class="btn btn-success">Show Products</button>
										</form>
									</td>
record;
								}
								else
								{
									$record.=<<< record
									<td>
									<button class="btn btn-secondary" disabled>Add Products</button>
									</td>
									<td>
									<button class="btn btn-secondary" disabled>Show Products</button>
									</td>
record;
								}
								$record.=<<< record
									<td>
										<button type="button" class="btn btn-danger enabledeletepopup" cid="{$output['getcatalogue'][$i]['catalogue_id']}"><i class="fa fa-trash"></i></button>
									</td>
record;
								if($output['getcatalogue'][$i]['catalogue_status']=="Active")
								{
									$record.=<<< record
									<td>
										<a href="https://api.whatsapp.com/send?text={$text}" target="_blank" title="Share Collection Link On Whatsapp"><i class="fab fa-whatsapp text-success fa-2x"></i></a>
									</td>
								</tr>
record;
								}
								else if($output['getcatalogue'][$i]['catalogue_status']=="Inactive")
								{
									$record.=<<< record
									<td>
										<a title="Collection is inactive" disabled><i class="fab fa-whatsapp text-secondary fa-2x"></i></a>
									</td>
								</tr>
record;
								}
								echo $record;
							}
						}
					?>
<script>
	$(".enabledeletepopup").on("click",
		function()
		{
			cid=$(this).attr('cid');
			$("#setcid").val(cid);
			$("#delete-confirmation-modal").modal('show');
		});

	$(".addproduct").on("click",
		function()
		{
			catid=$(this).attr('catid');
			catname=$(this).attr('catname');

			$("#addproductform")[0].reset();
			$("#spcid").val(catid);				// setting catalogue id automatically to respective field
			$("#spcname").val(catname);

			$("#addproductmodal").modal('show');
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

      	$qp=query("select * from product_catalogue where catalogue_seller_id='".$sid."'");
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
	?>

</div>

<?php
	if($showinformation==1)
		echo '<script>
				$("#information").html(\''.$message.'\');
				$("#information-modal").modal("show");
			</script>';
?>

<!-- Modal for confirmation -->
<div class="modal fade" id="delete-confirmation-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-exclamation-triangle text-danger"></i>&nbsp;Delete Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Do you really want to delete this collection ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         <form action="displaySellerCatalogues.php" method="post">
         	<input type="hidden" name="cid" value="" id="setcid">
        	<button type="submit" name="delete_catalogue" class="btn btn-danger text-white" id="yesdelete">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Popup Modal for adding products related to a catalogue -->
<div class="modal fade" id="addproductmodal" tabindex="-1" role="dialog" aria-labelledby="addproductforcatalogue" aria-hidden="true">
  <div class="modal-dialog modal-lg mw-100 w-75 mx-auto" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addproductforcatalogue">Add Products To Collection</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        	<form enctype="multipart/form-data" action="" method="post" id="addproductform">
		  	<div class="row mt-1">
		  		<div class="col-12 col-md-6">
		    		<div class="form-group row">
			      		<label for="spcid" class="col-6 col-form-label"><b>Collection Name:</b></label>
			      		<div class="col-6">
			      		<input type="hidden" name="pcid" id="spcid" value="" class="form-control">
			      		<input type="text" id="spcname" value="" class="form-control border border-top-0 border-left-0 border-right-0" readonly>
			      		</div>
		    		</div>
		   		</div>
		  		<div class="col-12 col-md-6">
		    		<div class="form-group row">
		      		<label for="mproduct_name" class="col-6 col-form-label text-md-right"><b>Product Name:</b></label>
		      		<div class="col-6">
		      		<input type="text" name="product_name" id="mproduct_name" class="form-control border border-top-0 border-left-0 border-right-0" required>
		      		</div>
		    		</div>
		   		</div>
		   		<div class="col-12 col-md-9">
				    <div class="form-group row">
				        <label for="mproduct_image" class="col-12 col-md-4 col-form-label"><b>Choose Product Image:</b></label>
				        <div class="col-12 col-md-8">
				        <input type="file" accept="image/*" name="productimage" id="mproduct_image" class="form-control border border-top-0 border-left-0 border-right-0" title="Upload Product Image" style="overflow:hidden;">
				    	</div>
				    </div>
				</div>
				<div class="col-12 col-md-9">
					<div class="row">
						<div class="col-12">
						    <div class="form-group row">
						        <label for="mproduct_price" class="col-5 col-md-4 col-form-label"><b>Product Price:</b></label>
						        <div class="col-5 col-md-3">
						        	<input type="number" name="product_price" id="mproduct_price" class="form-control border border-top-0 border-left-0 border-right-0 text-right" required min="1">
						    	</div>
						    	<div class="col-2 col-md-1">
									<i class="fas fa-rupee-sign mt-3"></i>
								</div>
								<div class="col-12 col-md-4">
									<div class="form-group row">
							      		<label for="product_unit" class="col-5 col-form-label"><b>Unit:</b></label>
							      		<div class="col-7">
							      		<select class="form-control border border-top-0 border-left-0 border-right-0" name="product_unit" id="product_unit" required>
							      			<option value="1">Per Item</option>
							      			<option value="2">Per Kg</option>
							      			<option value="3">Per Litre</option>
							      			<option value="4">Per Pair</option>
							      			<option value="5">Pack Of 4</option>
							      		</select>
							      		</div>
						    		</div>
								</div>
						    </div>
						</div>
					</div>
				</div>
				<div class="col-12">
				    <div class="form-group">
				        <label for="mproduct_desc"><b>Product Description:</b></label>
				        <textarea type="text" name="product_desc" id="mproduct_desc" class="form-control" rows="3" cols="20"></textarea>
				    </div>
				</div>
		  	</div>

			<div class="row">
				<div class="col-12">
				    <div class="form-group">
				      <input type="submit" name="save_product" id="save_product" class="btn btn-primary btn-md w-25" value="Add">
				    </div>
				</div>
			</div>
			</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- add Product modal ends -->

<?php
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

<!-- Libraries -->

<script>
  $(".page").on("click",
    function()
    {
      start=$(this).attr("start");
      end=$(this).attr("end");

      // alert(start);
      // alert(end);

      $(".page").removeClass("active");
      $(this).addClass("active");
      
      $.get("changeSellerCatalogueList.php?start="+start+"&end="+end,
        function(data,status)
        {
          $("#catalogue_body").html(data);
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
        if(event.which==13)	// if enter is clicked
        {
            $('#searchbystatus').click(); //Trigger click
        }
    });

    $('#searchfield').keypress(
		function(event){
        if(event.which==13)
        {
            $('#search').click();
        }
    });

	$("#search").on("click",
		function()
		{
			catalogue=$("#searchfield").val();

			$.get("searchCatalogue.php?cataloguename="+catalogue,
				function(data,status)
				{
					$("#catalogue_body").html(data);
				});
		});

	$("#searchbystatus").on("click",
		function()
		{
			cataloguestatus=$('#searchbystatusfield').val();
			
			$.get("searchCatalogue.php?cataloguestatus="+cataloguestatus,
				function(data,status)
				{
					$("#catalogue_body").html(data);
				});
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
		location.href="displaySellerCatalogues.php";
	});
</script>

<script>
	$(".enable-save-edit-confirmation").on("click",
		function(event)
		{
			event.preventDefault();
			id=$(this).attr("id");

			if(id=="for-save-collection-form")
			{
				$("#save-edit-confirmation-modal-title").html("Save Confirmation");
				$("#save-edit-confirmation-modal-body").html("Do you really want to save this collection?");
				$("#yes-save-edit").attr("fortype","for-save-collection-form");
			}
			else
			{
				$("#save-edit-confirmation-modal-title").html("Update Confirmation");
				$("#save-edit-confirmation-modal-body").html("Do you really want to update this collection?");
				$("#yes-save-edit").attr("fortype","for-edit-collection-form");
			}
			$("#save-edit-confirmation-modal").modal('show');
		});

	$("#yes-save-edit").on("click",function()
	{
		formtype=$("#yes-save-edit").attr("fortype");

		if($("#catalogue_Name").val()!="")
		{
			if(formtype=="for-save-collection-form")
				$("#save-collection-form").submit();
			else
			if(formtype=="for-edit-collection-form")
				$("#edit-collection-form").submit();
		}
		else
		{
			$("#save-edit-confirmation-modal").modal('hide');
			$("#catalogue_Name").focus();
		}
	});
</script>

<script>
	$("#addproductform").on("submit",
		function(event)
		{
			event.preventDefault();
			name=$("#mproduct_name").val();
			price=$("#mproduct_price").val();

		 	if(name!="" && price!="")
		 	{
		 		$.ajax({
		            type: 'POST',
		            url: 'addProductsByCatalogue.php',
		            data: new FormData(this),
		            dataType: 'json',
		            contentType: false,
		            cache: false,
		            processData:false,
		            success: function(response)
		            { 	
		                if(response.status == 1)
		                {
		                    $('#addproductform')[0].reset();
		                    $('#information').html("<p class='text-success'>Product added successfully</p>");
		                    $('#information-modal').modal('show');
		                }
		                else
		                {
		                	$('#addproductform')[0].reset();
		                    $('#information').html("<p class='text-danger'>Unable to add product</p>");
		                    $('#information-modal').modal('show');
		                }
		            }
		        });

		        $("#addproductmodal").modal('hide');
		 	}
		});
</script>

<?php include("footer.php"); ?>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

</body>
</html>
