<?php 
	require_once("../config/config.php"); 
	require_once("../config/".ENV."_config.php");
?>

<?php
	$response_code=0;

	if(isset($_POST['action']) && ($_POST['action']=="sendotp" || $_POST['action']=="resendotp") && isset($_POST['mobile_number']) && $_POST['mobile_number']!="")
	{
		$otp=rand(1000,9999);
		$text="Your One Time Password is ".$otp;

		$_SESSION['otp']=$otp;

		$data['mobile']=$_POST['mobile_number'];
		$data['text']=$text;

	  	$url=DOMAIN.'/rest/seller/sendSmsApiRest.php';
	  	$output=getRestApiResponse($url,$data);

	  	if(isset($output['smsstatus']) && $output['smsstatus']['response_code'] == 200)
	  		$response_code=1;
	}
	else
	if(isset($_POST['action']) && $_POST['action']=="verifyotp" && isset($_POST['otp']) && $_POST['otp']!="")
	{
		if(isset($_SESSION['otp']) && $_SESSION['otp']!="")
		{
			if($_SESSION['otp']==$_POST['otp'])
			{
				$response_code=1;
				unset($_SESSION['otp']);
			}
		}
	}

	$response['status']=$response_code;
	echo json_encode($response);
?>