<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$connection->autocommit(FALSE);
$flag = true;

$query="";

if(isset($_REQUEST['seller_id']) && $_REQUEST['seller_id']!="" && isset($_REQUEST['ticket_id']) && $_REQUEST['ticket_id']!="" && isset($_REQUEST['resolution_remarks']) && $_REQUEST['resolution_remarks']!="" )
{
	$seller_id=$_REQUEST['seller_id'];
	$ticket_id=$_REQUEST['ticket_id'];
	$resolution_remarks=$_REQUEST['resolution_remarks'];
	
	$query="UPDATE 
				tickets
			SET 
				resolution_remarks = '".$resolution_remarks."',
				status= 2  
			WHERE 
				ticket_id ='".$ticket_id."' 
				AND
				seller_id = '".$seller_id."'";


	$query=query($query);
	$result = confirm($query);
	if( !$result)
	{
		$flag = false;
	}
	$temp=array();
	if($flag){
		commit();
		$temp['response_code']=200;
		$temp['response_desc']="Success";
			
		echo json_encode(array("getticketdetails"=>$temp));
		close();
		exit();
	}
	else
	{
		rollback();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";

		echo json_encode(array("getticketdetails"=>$temp));
		close();
		exit();
	}
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("getticketdetails"=>$temp));
	close();
	exit();
}
?>
