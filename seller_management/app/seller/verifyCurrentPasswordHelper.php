<?php 
	require_once("../../config/config.php"); 
	require_once("../../config/".ENV."_config.php");
?>

<?php
	if(isset($_SESSION['user_id']))
	{
		if(isset($_POST['password']))
		{
			$data['user_id']=$_SESSION['user_id'];
			$data['password']=$_POST['password'];

			$url=DOMAIN.'/rest/seller/verifyPasswordRest.php';
			$output=getRestApiResponse($url,$data);
		
			if(isset($output['verifypassword']) && $output['verifypassword']['response_code']==200)
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