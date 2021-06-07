<?php 
	require_once("../../config/config.php"); 
	require_once("../../config/".ENV."_config.php");
?>

<?php
	if(isset($_SESSION['user_id']))
	{
		if(isset($_POST['pid']) && isset($_POST['value']))
		{
			$data['user_id']=$_SESSION['user_id'];
			$data['product_id']=$_POST['pid'];
			$data['product_inventory']=$_POST['value'];

			$url=DOMAIN.'/rest/seller/updateProductInventoryRest.php';
			$output=getRestApiResponse($url,$data);
		
			if(isset($output['updateproduct']) && $output['updateproduct']['response_code']==200)
			{
				$response['status']=1;
				echo json_encode($response);
			}
			else
			{
				$response['status']=0;
				echo json_encode($response);
			}
		}
	}
?>