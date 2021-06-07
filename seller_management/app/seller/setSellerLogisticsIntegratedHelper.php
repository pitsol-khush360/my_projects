<?php 
	require_once("../../config/config.php"); 
	require_once("../../config/".ENV."_config.php");
?>

<?php
	if(isset($_SESSION['user_id']))
	{
		if(isset($_POST['logistics']))
		{
			$data['user_id']=$_SESSION['user_id'];
			$data['logistics_integrated']=$_POST['logistics'];

			$url=DOMAIN.'/rest/seller/updateSellerLogisticsRest.php';
			$output=getRestApiResponse($url,$data);
		
			if(isset($output['updatelogistics']) && $output['updatelogistics']['response_code']==200)
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