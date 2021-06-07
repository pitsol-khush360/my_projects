<?php 
	require_once("../../config/config.php"); 
	require_once("../../config/".ENV."_config.php");
?>

<?php
	if(isset($_SESSION['user_id']))
	{
		if(isset($_POST['email']) || isset($_POST['sms']) || isset($_POST['whatsapp']))
		{
			$data['user_id']=$_SESSION['user_id'];

			if(isset($_POST['email']))
			{
				$data['status']=$_POST['email'];
				$url=DOMAIN.'/rest/seller/updateNotificationEmailRest.php';

				$output=getRestApiResponse($url,$data);
		
				if(isset($output['updateemail']) && $output['updateemail']['response_code']==200)
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
			else
			if(isset($_POST['sms']))
			{
				$data['status']=$_POST['sms'];
				$url=DOMAIN.'/rest/seller/updateNotificationSmsRest.php';

				$output=getRestApiResponse($url,$data);
		
				if(isset($output['updatesms']) && $output['updatesms']['response_code']==200)
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
			else
			if(isset($_POST['whatsapp']))
			{
				$data['status']=$_POST['whatsapp'];
				$url=DOMAIN.'/rest/seller/updateNotificationWhatsappRest.php';

				$output=getRestApiResponse($url,$data);
		
				if(isset($output['updatewhatsapp']) && $output['updatewhatsapp']['response_code']==200)
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
	}
?>