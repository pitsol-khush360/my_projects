<?php 
	require_once("../../config/config.php"); 
	require_once("../../config/".ENV."_config.php");
?>

<?php 
if(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2 && isset($_GET['cid']))
{
	$data['collection_id']=$_GET['cid'];
	$url=DOMAIN.'/rest/seller/getProductLibraryRest.php';
	$output=getRestApiResponse($url,$data);
	
	$cname=$_GET['cname'];
	$cimage=$_GET['cimage'];

	$record="";
	if(isset($output['getcollections']) && count($output['getcollections'])>3)
	{
		$record.="<div class='row'>
					<div class='col-12 text-center'>
						<h5>Products</h5>
					</div>
				  </div>";

		$record.="<form action='displaySellerCollectionGallery.php' method='post'><div class='row mt-0 mt-md-3 cg-scroll' id='collectiongalleryform'>
					<input type='hidden' name='collection' value='".$cname."#".$cimage."'>";

		$seller_to_root=SELLER_TO_ROOT;

		for($i=0;$i<$output['getcollections']['rows'];$i++)
		{	
			$record.=<<< record
			<div class="col-6 col-md-3 mt-3">
				<div class="card" id="product-box{$output['getcollections'][$i]['product_id']}">
					<div class="card-body p-0">
						<img src="{$seller_to_root}{$output['getcollections'][$i]['image_name']}" class="cg-image">
						<input type="checkbox" class="form-control checkboxtoselect cg-checkbox" name="selectproduct[]" product-name="{$output['getcollections'][$i]['product_name']}" product-image="{$output['getcollections'][$i]['image_name']}" product-description="{$output['getcollections'][$i]['product_description']}" product-id="{$output['getcollections'][$i]['product_id']}">

						<input type="hidden" name="products[{$output['getcollections'][$i]['product_id']}]" id="producthidden{$output['getcollections'][$i]['product_id']}">
					</div>
					<div class="card-footer">
						<div class="row pl-0 pl-md-1">
							<div class="col-12 pl-0 pl-md-1">
								<p class="card-text cg-nml-txt">{$output['getcollections'][$i]['product_name']}</p>
							</div>
						</div>
					</div>
				</div>	
			</div>
record;
		}

		$record.="</div>
					<div class='row mt-5 mb-5'>
						<div class='offset-3 col-6 offset-md-4 col-md-3'>
							<input type='submit' class='btn btn-primary w-100' name='submitgallery' id='submitgallery' value='Save'>
						</div>
					</div>
				</form>";
	}
	echo $record;
}
?>

<script>
	$(".checkboxtoselect").on("click", 
		function() 
		{
			product_image=$(this).attr("product-image");
			product_name=$(this).attr("product-name");
			product_des=$(this).attr("product-description");
			pid=$(this).attr("product-id");

        	if($(this).is(":checked"))
        	{
            	$("#product-box"+pid).css("border","green 3px solid");
            	str=product_name+"#"+product_image+"#"+product_des;
            	$("#producthidden"+pid).val(str);
        	}
        	else 
        	if($(this).is(":not(:checked)"))
        	{
            	$("#product-box"+pid).css("border","#D3D3D3 1px solid");
            	$("#producthidden"+pid).val("");
        	}
	});
</script>