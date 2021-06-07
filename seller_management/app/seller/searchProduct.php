<?php 
	require_once("../../config/config.php"); 
	require_once("../../config/".ENV."_config.php");
?>

<?php
	if(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2 && isset($_GET['search']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['productname']=$_GET['search'];

		$url=DOMAIN.'/rest/seller/getProductDetailsRest.php';

		$output=getRestApiResponse($url,$data);

		if(isset($output['getproducts']) && count($output['getproducts'])>2)
		{
			if(isset($output['getproducts']['rows']) && $output['getproducts']['rows']!=0)
			{
				$record="";
				$seller_to_root=SELLER_TO_ROOT;
				for($i=0;$i<$output['getproducts']['rows'];$i++)
				{
					$producturl=DOMAIN."/app/?s=".$_SESSION['username']."&pid=".$output['getproducts'][$i]['product_id'];
					$producturl=urlencode($producturl);
					
					$record.=<<< record
					<tr>
						<td>{$output['getproducts'][$i]['catalogue_Name']}</td>
						<td><img src="{$seller_to_root}{$output['getproducts'][$i]['productimage']}" class="list-image">
						</td>
						<td>{$output['getproducts'][$i]['product_name']}</td>
record;
						if($output['getproducts'][$i]['product_price']==$output['getproducts'][$i]['product_offer_price'])
						{
							$record.=<<< record
							<td class="text-right"><i class="fas fa-rupee-sign"></i>&nbsp;{$output['getproducts'][$i]['product_price']}</td>
record;
						}
						else
						{
							$record.=<<< record
							<td class="text-right"><i class="fas fa-rupee-sign"></i>&nbsp;<s>{$output['getproducts'][$i]['product_price']}</s>&nbsp;{$output['getproducts'][$i]['product_offer_price']}</td>
record;
						}

						if($output['getproducts'][$i]['product_inventory']==1)
						{
							$record.=<<< record
							<td>
								<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" checked class="setstock" value="{$output['getproducts'][$i]['product_id']}">
						    </td>
record;
						}
						else if($output['getproducts'][$i]['product_inventory']==0)
						{
							$record.=<<< record
							<td>
								<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" class="setstock" value="{$output['getproducts'][$i]['product_id']}">
						     </td>
record;
						}

						if($output['getproducts'][$i]['product_status']=="Active")
						{
							$record.=<<< record
							<td><p class='text-success'>Active</p></td>
							<td>
								<form action="displaySellerProducts.php" method="post">
									<input type="hidden" name="pid" value="{$output['getproducts'][$i]['product_id']}">
									<input type="hidden" name="product_status" value="Inactive">
									<button type="submit" name="setproductstatus" class="btn btn-warning">Deactivate</button>
								</form>
							</td>
							<td>
								<form action="displayUpdateSellerProduct.php" method="post">
									<input type="hidden" name="pid" value="{$output['getproducts'][$i]['product_id']}">
									<button type="submit" name="edit_product" class="btn btn-primary">Edit</button>
								</form>
							</td>
record;
						}
						else if($output['getproducts'][$i]['product_status']=="Inactive")
						{
							$record.=<<< record
							<td><p class='text-danger'>Inactive</p></td>
							<td>
								<form action="displaySellerProducts.php" method="post">
									<input type="hidden" name="pid" value="{$output['getproducts'][$i]['product_id']}">
									<input type="hidden" name="product_status" value="Active">
									<button type="submit" name="setproductstatus" class="btn btn-info">Activate</button>
								</form>
							</td>
							<td>
								<form action="displayUpdateSellerProduct.php" method="post">
									<input type="hidden" name="pid" value="{$output['getproducts'][$i]['product_id']}">
									<button type="submit" name="edit_product" class="btn btn-secondary" disabled>Edit</button>
								</form>
							</td>
record;
						}

						$record.=<<< record
						<td>
							<form action="displaySellerProducts.php" method="post">
								<input type="hidden" name="pid" value="{$output['getproducts'][$i]['product_id']}">
								<button type="submit" name="delete_product" class="btn btn-danger" onclick="return confirm('Do you really want to delete this product')"><i class="fa fa-trash"></i></button>
							</form>
						</td>
record;

						if($output['getproducts'][$i]['product_status']=="Active")
						{
							$record.=<<< record
							<td>
								<a href="https://api.whatsapp.com/send?text={$producturl}" target="_blank" title="Share Product Link On Whatsapp"><i class="fab fa-whatsapp text-success fa-2x"></i></a>
							</td>
						</tr>
record;
						}
						else if($output['getproducts'][$i]['product_status']=="Inactive")
						{
							$record.=<<< record
							<td>
								<a title="Prodcut is inactive" disabled><i class="fab fa-whatsapp text-secondary fa-2x"></i></a>
							</td>
						</tr>
record;
						}
				}
				$record.="
				<script>$(function(){ $('.setstock').bootstrapToggle(); })</script>
				<script>
					$('.setstock').change(
				    	function(){

					      value=0;
					      pid=$(this).val();

					      if($(this).prop('checked'))
					       value=1;
					      else
					       value=0;

					   	  var tobesend = 'pid='+pid+'&value='+value;

					   		$.ajax({
					            type: 'POST',
					            url: 'setSellerProductInStockHelper.php',
					            data: tobesend,
					            dataType: 'json',
					            success: function(response)
					            { 	
					                if(response.status == 1)
					                {
					                    alert('Product stock updated');
					                }
					                else
					                {
					                    alert('Unable to update product stock');
					                }
					            }
						    });
				     	});
				</script>";
				echo $record;
			}
		}
	}
?>