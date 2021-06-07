<?php

header("Content-Type:application/json");

require_once('../config/config.php');
require_once("../config/".ENV."_config.php");

$connection->autocommit(FALSE);
$flag = true;

if((isset($_REQUEST['name']) && $_REQUEST['name']!="") && (isset($_REQUEST['email']) && $_REQUEST['email']!="") && (isset($_REQUEST['mobile']) && $_REQUEST['mobile']!="") && (isset($_REQUEST['message']) && $_REQUEST['message']!=""))
{	
	$query=query("
				INSERT INTO contact_us(
				    name,
				    email,
				    mobile,
				    message,
				    status
				)
				VALUES(
				    '".$_REQUEST['name']."',
				    '".$_REQUEST['email']."',
				    '".$_REQUEST['mobile']."',
				    '".$_REQUEST['message']."',
				    '0'
				)
				");
	$result=confirm($query);

	if(!$result)
	{
		$flag = false;
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("contactus"=>$temp));
		close();
		exit();
	}

	if($flag)
	{
		commit();
		$temp=array();
		$temp['response_code']=200;
		$temp['response_desc']="Success";
		echo json_encode(array("contactus"=>$temp));
		close();
		exit();
	}
	else
	{
		rollback();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("contactus"=>$temp));
		close();
		exit();
	}
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("contactus"=>$temp));
	close();
	exit();
}
close();
?>
