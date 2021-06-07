<?php
header("Content-Type:application/json"); // setting content as well as we will convert data into json type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$query="";

if(isset($_REQUEST['admin_id']) && isset($_REQUEST['password']) && $_REQUEST['admin_id']!="" && $_REQUEST['password']!="")
{
	$admin_id = $_REQUEST['admin_id'];
	$password = $_REQUEST['password'];
	
	$query = "  SELECT 
					userid ,
					password,
					full_name,
					role,
					status,
					last_modified_by
				FROM 
					admin_user 
				WHERE
					userid='".$admin_id."'
					AND
					password='".$password."'";
					

	$query=query($query);
	confirm($query);
	$rows=mysqli_num_rows($query);

	if($rows==1)	// Valid Request, Data Found.
	{
		$temp=array();
		$temp=fetch_array($query);
		$temp['response_code']=200;
		$temp['response_desc']="Success";

		echo json_encode(array("getadmindetails"=>$temp));
		close();
		exit();
	}
	else
	{
		$temp['response_code']=405;
		$temp['response_desc']="Record Not Found";
		
		echo json_encode(array("getadmindetails"=>$temp));
		close();
		exit();
	}
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	
	echo json_encode(array("getadmindetails"=>$temp));
	close();
	exit();
}
?>
