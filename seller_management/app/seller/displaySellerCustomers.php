<?php ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); ?>

<?php include("navigation.php");  ?>

<?php
  if(!(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2))
    redirect("login.php");
?>

<?php
if(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2)
{
	$showinformation=0;
	$message="";

	  if(isset($_POST['sendsms']))
	  {
	  	$storelink=DOMAIN."/app/?s=".$_SESSION['username'];
	    $text="Check out ".$_SESSION['business_name']."'s latest product collection at ".$storelink.". Selling Simplified by ".DOMAIN.". Download at playstore ".APPLINKONPLAYSTORE;

		$data['mobile']=$_POST['mobile'];
		$data['text']=$text;

		$url=DOMAIN.'/rest/seller/sendSmsApiRest.php';
		$output=getRestApiResponse($url,$data);

		if(isset($output['smsstatus']) && $output['smsstatus']['response_code'] == 200)
		{
			$showinformation=1;
	        $message='<p class="text-success">Your store link shared successfully</p>';
		}
		else
		{
			$showinformation=1;
	        $message='<p class="text-danger">Unable to share your store link</p>';
		}
	  }

  $data['user_id']=$_SESSION['user_id'];
  $url=DOMAIN.'/rest/seller/getSellerCustomersRest.php';
  $output=getRestApiResponse($url,$data);
?>

<div class="container-fluid">

<?php
  if(isset($output['getsellercustomers']) && count($output['getsellercustomers'])>2)
  {
  ?>
  	<div class="row mt-3">
		<div class="col-12">
			<h5 class="text-center"> Hey <?php echo $_SESSION['username'];  ?>, These Are  Your Customers </h5>
		</div>
	</div>
  	<div class="row mt-5">
		<div class="col-5 col-md-6">
			Show
			<select id="pages">
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
		<div class="col-7 offset-md-2 col-md-4">
			<div class="input-group">
		    	<input type="text" class="form-control" placeholder="Search By Customer Name" id="searchfield">
			    <div class="input-group-append">
			      <button class="btn btn-secondary" name="search" type="button" id="search">
			        <i class="fa fa-search"></i>
			      </button>
			    </div>
		  	</div>
		</div>
	</div>


  	<div class="row mt-3">
		<div class="col-12 table-responsive">
			<table class="table table-hover table-bordered text-center pl-0" width="100%">
				<thead>
					<tr>
						<th>Customer Name</th>
						<th>Customer Number</th>
						<th>Total Sales</th>
            			<th> View Orders </th>
						<th colspan="2">Share Your Site</th>
					</tr>
				</thead>
				<tbody id="promo_body">
			    <?php
			   		if(isset($output['getsellercustomers']['rows']) && $output['getsellercustomers']['rows']!=0)
			   		{
			     		for($i=0;$i<$output['getsellercustomers']['rows'];$i++)
			     		{
			    ?>
							<tr>
							  <td><?php echo  $output['getsellercustomers'][$i]['customer_name'];?></td>
							  <td><?php echo $output['getsellercustomers'][$i]['customer_mobile']; ?></td>
							  <td><i class="fas fa-inr"></i>&nbsp;<?php echo $output['getsellercustomers'][$i]['amount']; ?></td>
							  <td>
							    <form class="" action="displaySellerOrders.php" method="post">
							      <input type="hidden" name="customer_mobile" value="<?php echo $output['getsellercustomers'][$i]['customer_mobile']; ?>">
							      <input type="submit"class="btn btn-primary" name="customer_orders" value="View Orders" >
							   </form>
							  </td>
							  <td>
							  	<?php
							  		$sellerurl=DOMAIN."/app/?s=".$_SESSION['username'];
							  		$sellerurl=urlencode($sellerurl);
							  	?>
							  	<a href="https://api.whatsapp.com/send?text=<?php echo $sellerurl; ?>" target="_blank" title="Share Your Store Link On Whatsapp"><i class="fab fa-whatsapp fa-2x text-success" aria-hidden="true"></i>
							  	</a>
							  </td>
							  <td>
							  	<form action="" method="post"> 
							  		<input type="hidden" name="mobile" value="<?php echo $output['getsellercustomers'][$i]['customer_mobile']; ?>">
							  		<button type="submit" name="sendsms" class="btn bg-transparent" title="Share Your Store Link Via SMS"><i class="fas fa-sms fa-2x"></i></button>
							  	</form>
							  </td>
							</tr>
        		<?php
     					}

   					}
   			?>	
   				</tbody>
 			</table>
 		</div>
 	</div>

<?php
	}
	else
		echo '<div class="row mt-4">
				<div class="col-12 text-danger">
					<h5>You Don\'t Have Any Customer Yet!</h5>
				</div>
			  </div>
			';
?>

</div>

<?php
}
?>


        </div>
        <!-- content end -->
    </div>
    <!-- page content -->
</div>
<!-- page wrapper end -->

<?php include("footer.php"); ?>
<?php
    if($showinformation==1)
        echo '<script>
                $("#information").html(\''.$message.'\');
                $("#information-modal").modal("show");
            </script>';
?>
<script>
	$("#search").on("click",
		function()
		{
			promo=$("#searchfield").val();

			$.get("searchCustomer.php?search="+promo,
				function(data,status)
				{
					$("#promo_body").html(data);
				});
		});
</script>
