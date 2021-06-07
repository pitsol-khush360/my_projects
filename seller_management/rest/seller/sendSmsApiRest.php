<?php
header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['mobile'])&&$_REQUEST['mobile']!=''&& isset($_REQUEST['text'])&&$_REQUEST['text']!='')
{	
	$mobile = $_REQUEST['mobile'];
	$text = $_REQUEST['text'];
	$response=sendMessage($mobile,$text);
	if(strpos($response, "SMS-SHOOT-ID") !== false)
	{
		$temp=array();
		$temp['response_code']=200;
		$temp['response_desc']="Success";
		echo json_encode(array("smsstatus"=>$temp));
	} 
	else
	{
		$temp=array();
		$temp['response_code']=405;
		$temp['response_desc']=$response;
		echo json_encode(array("smsstatus"=>$temp));
	}
	
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("smsstatus"=>$temp));
}
close();
?>
