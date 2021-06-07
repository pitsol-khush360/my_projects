<?php 
	require_once("../../config/config.php"); 
	require_once("../../config/".ENV."_config.php");
?>

<?php
	if(isset($_SESSION['user_id']))
	{
		if(isset($_POST['username']))
		{
			$data['user_id']=$_SESSION['user_id'];
			$data['username']=$_POST['username'];

			$url=DOMAIN.'/rest/seller/verifyUserNameRest.php';
			$output=getRestApiResponse($url,$data);
		
			if(isset($output['verifyusername']) && $output['verifyusername']['response_code']==200)
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