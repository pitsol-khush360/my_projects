<?php 
	require_once("../../config/config.php"); 
	require_once("../../config/".ENV."_config.php");
?>

<?php
	if(isset($_SESSION['user_id']))
	{
		if(isset($_POST['onlinepayment']) || isset($_POST['codpayment']))
		{
			$data['user_id']=$_SESSION['user_id'];

			if(isset($_POST['onlinepayment']))
			{
				$data['acceptonlinepayment']=$_POST['onlinepayment'];
				$url=DOMAIN.'/rest/seller/updateSellerAcceptOnlinePaymentRest.php';

				$output=getRestApiResponse($url,$data);
		
				if(isset($output['updateonlinepaymentstatus']) && $output['updateonlinepaymentstatus']['response_code']==200)
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
			if(isset($_POST['codpayment']))
			{
				$data['status']=$_POST['codpayment'];
				$url=DOMAIN.'/rest/seller/updateSellerAcceptCodPaymentRest.php';

				$output=getRestApiResponse($url,$data);
		
				if(isset($output['updatecod']) && $output['updatecod']['response_code']==200)
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