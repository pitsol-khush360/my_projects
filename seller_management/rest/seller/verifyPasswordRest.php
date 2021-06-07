<?php

header("Content-Type:application/json");
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");

if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!=""&&isset($_REQUEST['password']) && $_REQUEST['password']!="")
{	
	
	
	$query="
			SELECT
			    *
			FROM
			    users
			WHERE
			    user_id = '".$_REQUEST['user_id']."'
			    AND 
			    password = '".$_REQUEST['password']."'
			";
	$query=query($query);
	confirm($query);
	if(mysqli_num_rows($query)!=0)
	{
		
		
		$temp=array();
		$temp['response_code']=200;			
		$temp['response_desc']="success";
		echo json_encode(array("verifypassword"=>$temp));
		close();
		exit();

	}
	else
	{	
		
		$temp=array();
		$temp['response_code']=405;
		$temp['response_desc']="Wrong Password";
		echo json_encode(array("verifypassword"=>$temp));
		close();
		exit();
	}
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("verifyverifypassword"=>$temp));
}
close();	
?>
