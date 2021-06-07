<?php
header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['email'])&&$_REQUEST['email']!=''&& isset($_REQUEST['messsage'])&&$_REQUEST['message']!='')
{	
	$email = $_REQUEST['email'];
	$body = $_REQUEST['message'];
	sendMail($email,"OTP",$body);
	
	$temp=array();
	$temp['response_code']=200;
	$temp['response_desc']="Success";
	echo json_encode(array("sendemail"=>$temp));
	
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("sendemail"=>$temp));
}
close();
?>
