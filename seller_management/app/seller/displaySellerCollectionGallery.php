<?php 
	require_once("../../config/config.php"); 
	require_once("../../config/".ENV."_config.php");
	include("header.php"); 
?>

<?php
  if(!(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2))
    redirect("login.php");
?>

<?php 
if(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2)
{
	$showinformation=0;
	$message="";

	if(isset($_POST['submitgallery']))
	{
		// $data['user_id']=$_SESSION['user_id'];
		// $data['products']=$_POST['products'];
		// $data['collection']=$_POST['collection'];
		
		if(count(array_filter($_POST['products']))!=0)
		{
			//$url=DOMAIN.'/rest/seller/createCollectionProductsFromLibraryRest.php';

			// $defaults = array(
			// CURLOPT_URL => $url,
			// CURLOPT_POST => true,
			// CURLOPT_POSTFIELDS => array('data'=>serialize($data)),
			// );

			// $client=curl_init();
			// curl_setopt_array($client,$defaults);
			// curl_setopt($client,CURLOPT_RETURNTRANSFER,true);

			// $output=curl_exec($client);
			// curl_close($client);

			// $output=json_decode($output,JSON_FORCE_OBJECT);

			// Method 2 new api
			$data[0]=$_SESSION['user_id'];
			$data[1]=$_POST['collection'];

			$count=0;
			$k=3;

			foreach($_POST['products'] as $value)
			{
				if($value!="" && !is_null($value))
				{
					$count++;
					$data[$k++]=$value;
				}
			}

			$data[2]=$count;

			$url=DOMAIN.'/rest/seller/createCollectionProductsFromLibraryForAndroidRest.php';
			$output=getRestApiResponse($url,$data);

			if(isset($output['createcollectionproduct']) && $output['createcollectionproduct']['response_code']=200)
			{
				$showinformation=1;
                $message='<p class="text-success">Collection and its products added successfully</p>';
			}
			else
			{
				$showinformation=1;
                $message='<p class="text-danger">Unable to add collection and its products!</p>';
			}
		}
		else
		{
			$showinformation=1;
            $message='<p class="text-danger">Unable to add this collection because you didnt selected any product!</p>';
		}
	}
?>
	<div class="container-fluid">
		<div class="row mt-4 mt-md-5">
			<div class="col-9 col-md-3">
				<a href="displaySellerCatalogues.php" class="btn btn-success w-100">Back To Collections</a>
			</div>
		</div>
		<div class="row mt-3">
			<div class="col-12 text-center">
				<h4>Collection Gallery</h4>
			</div>
		</div>
		<hr>

		<?php 
			$data1=array();

			$url=DOMAIN.'/rest/seller/getCollectionLibraryRest.php';

			$output=getRestApiResponse($url,$data1);
			
			if(isset($output['getcollections']) && count($output['getcollections'])>3)
			{
		?>
			<div class="row mt-2 mt-md-4">
				<div class="col-4 col-md-3">
					<div class='row'>
						<div class='col-12 text-center'>
							<h5>Collections</h5>
						</div>
					</div>
					<div class="row mt-0 mt-md-3 cg-scroll">
				<?php 
					for($i=0;$i<$output['getcollections']['rows'];$i++)
					{
				?>
				<div class="col-12 col-md-12 mt-3">
					<div class="card selectcollection" id="collection-box<?php echo $output['getcollections'][$i]['collection_id']; ?>">
						<div class="card-body p-0">
							<img src="<?php echo SELLER_TO_ROOT.$output['getcollections'][$i]['image_name']; ?>" class="cg-image">
						</div>
						<div class="card-footer pl-3">
							<div class="row pl-0 pl-md-1">
								<div class="col-12 col-md-6 pl-0 pl-md-1">
									<p class="card-text cg-nml-txt"><?php echo $output['getcollections'][$i]['collection_name']; ?></p>
								</div>
								<div class="col-12 col-md-6 mt-1 mt-md-0 pl-0 pl-md-1">
									<button class="btn btn-primary btn-sm showproducts" id="<?php echo $output['getcollections'][$i]['collection_id']; ?>" cname="<?php echo $output['getcollections'][$i]['collection_name']; ?>" cimage="<?php echo $output['getcollections'][$i]['image_name']; ?>">Show Products</button>
								</div>
							</div>
						</div>
					</div>	
				</div>
				<?php
					}
				?>
					</div>
				</div>
				<div class="col-8 col-md-9" id="product-gallery">
				</div>
			</div>
		<?php
			}
			else
				echo '<div class="row mt-5">
						<div class="col-12 text-danger text-center">
							<h5>Items Not Available</h5>
						</div>
					</div>';
		?>
	</div>

<?php
}
?>

<div class="modal fade" id="information-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <i class="fas fa-bell fa-2x text-warning"></i>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="information">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">OK</button>
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
	$(".showproducts").on("click",
		function()
		{
			cid=$(this).attr("id");
			cname=$(this).attr("cname");
			cimage=$(this).attr("cimage");

			$(".selectcollection").css("border","#D3D3D3 1px solid");
			$("#collection-box"+cid).css("border","green 3px solid");

			$.get("changeProductGallery.php?cid="+cid+"&cname="+cname+"&cimage="+cimage,
				function(data)
				{
					$("#product-gallery").html(data);
				});
		});
</script>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

</body>
</html>