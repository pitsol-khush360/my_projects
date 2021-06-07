<?php

header("Content-Type:application/json");
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['username']) && $_REQUEST['username']!="")
{	
	
	
	$query="
			SELECT
			    *
			FROM
			    users
			WHERE
			    username = '".$_REQUEST['username']."'
			";
	$query=query($query);
	confirm($query);
	if(mysqli_num_rows($query)==0)
	{
		

		$temp=array();
		$temp['response_code']=200;			
		$temp['response_desc']="success";
		echo json_encode(array("verifyusername"=>$temp));
		close();
		exit();

	}
	else
	{	
		$temp=array();
		$temp['response_code']=405;
		$temp['response_desc']="Alreay User Name Exists";
		echo json_encode(array("verifyusername"=>$temp));
		close();
		exit();
	}
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("verifyusername"=>$temp));
	close();
	exit();
}
close();	
?>
