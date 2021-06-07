<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$connection->autocommit(FALSE);
$flag = true;

if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!='')
{

	$user_id=$_REQUEST['user_id'];
	
	$query="UPDATE 
				users 
			SET 
				status='I',
				updated_datetime=NOW()
			WHERE
				user_id='".$user_id."'
			";
	$query=query($query);
	$result=confirm($query);
	if(!$result)
	{
		$flag=false;
	}
	if($flag)
	{
		commit();
		$temp=array();
		
		$temp['response_code']=200;
		$temp['response_desc']="success";
		echo json_encode(array("updatestatus"=>$temp));
		close();
		exit();
	}
	else
	{
		rollback();
		$temp=array();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("updatestatus"=>$temp));
		close();
		exit();	
	}
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("updatestatus"=>$temp));
	close();
	exit();
}
close();
?>
