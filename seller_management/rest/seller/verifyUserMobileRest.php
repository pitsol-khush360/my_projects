<?php

header("Content-Type:application/json");
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['mobile']) && $_REQUEST['mobile']!="")
{	
	
	
	$query="SELECT
				status
			FROM
			    users
			WHERE
			    mobile = '".$_REQUEST['mobile']."'
		    ";
	$query=query($query);
	confirm($query);
	$row=fetch_array($query);
	$rows=mysqli_num_rows($query);
	if($rows!=0 && $row['status']=='A')
	{
		
		$temp=array();
		$temp['response_code']=200;			//  200 means 'Success'
		$temp['response_desc']="success";
		echo json_encode(array("login"=>$temp));
		close();
		exit();

	}
	if($rows!=0 && $row['status']=='I')
	{
		
		$temp=array();
		$temp['response_code']=405;
		$temp['response_desc']="Your account has been blocked, contact customer support Team to activate your account";
		echo json_encode(array("login"=>$temp));
		close();
		exit();

	}
	if($rows==0)
	{	
		$temp=array();
		$temp['response_code']=500;
		$temp['response_desc']="User Not Found";
		echo json_encode(array("login"=>$temp));
		close();
		exit();
	}
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("login"=>$temp));
	close();
	exit();
}
close();	
?>
