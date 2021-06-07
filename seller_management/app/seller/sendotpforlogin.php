<?php 
	require_once("../../config/config.php"); 
	require_once("../../config/".ENV."_config.php");
?>

<?php
	if(isset($_POST['mobile']))
	{
	  $otp=rand(1000,9999);
	  $text="Your One Time Password is ".$otp;

	  $_SESSION['otp']=$otp;
	  
	  $data['mobile']=$_POST['mobile'];
	  $data['text']=$text;

	  $url=DOMAIN.'/rest/seller/sendSmsApiRest.php';
	  $output=getRestApiResponse($url,$data);

	  if(!(isset($output['smsstatus']) && $output['smsstatus']['response_code'] == 200))
	    echo "No";
	}
?>