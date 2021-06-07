<?php
header("Content-Type:application/json"); // setting content as well as we will convert data into json type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$query="";

if(isset($_REQUEST['admin_id']) && $_REQUEST['admin_id']!="" && isset($_REQUEST['screen']) && $_REQUEST['screen']!="")
{
	$admin_id=$_REQUEST['admin_id'];
	$screen=$_REQUEST['screen'];

	$query = " SELECT  
					R.id,
					R.ROLE_NAME
				FROM
					application_role R, user_role U 
				WHERE  
					R.id = U.APPLICATION_ROLE_ID 
					AND
					U.USER_NAME = '".$admin_id."'
					AND 
					R.screen_name IN('".$_REQUEST['screen']."','ADMIN_APPLICATION')
				";
	//echo $query;			
	$query=query($query);
	confirm($query);
	$rows=mysqli_num_rows($query);

	if($rows!=0)	// Valid Request, Data Found.
	{
		$temp=array();
		while($row=fetch_array($query))
		{
			$temp[$row['ROLE_NAME']]=true;
		}
		$temp['response_code']=200;
		$temp['response_desc']="success";
		$temp['rows']=$rows;
	
	echo json_encode(array("givenpermissiondetails"=>$temp));
	close();
	exit();
	}
	else
	{
	$temp['response_code']=405;
	$temp['response_desc']="No Results Found";
	echo json_encode(array("givenpermissiondetails"=>$temp));
	close();
	exit();
	}
	
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="Record not found";
	echo json_encode(array("givenpermissiondetails"=>$temp));
	close();
	exit();
}
?>