<?php
header("Content-Type:application/json"); // setting content as well as we will convert data into json type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$connection->autocommit(FALSE);
$flag = true;

$query="";
//print_r($_REQUEST);

if(isset($_REQUEST[0]) && $_REQUEST[0]!='')
{
	$admin_id = $_REQUEST[0];
	{

		$query ="  DELETE  FROM 
							user_role
						   WHERE
						   	USER_NAME = '".$admin_id."'
				";

		$query=query($query);
	}
	

	$query = "INSERT INTO user_role( USER_NAME , APPLICATION_ROLE_ID) VALUES";
	
	$query_parts = array();
	
	for( $i=2 ; $i < $_REQUEST[1]+2 ; $i++)
	{
		$query_parts[] = "('".$admin_id."' ,'".$_REQUEST[$i]."')";
	}
	$query.=implode(',', $query_parts);
	//echo $query;
	$query=query($query);
	$result = confirm($query);
	if( !$result)
	{
		$flag = false;
	}
	$temp=array();
	if($flag)
	{
		commit();
		$temp['response_code']=200;
		$temp['response_desc']="Success";

		echo json_encode(array("updateuserpermission"=>$temp));
		close();
		exit();
	}
	else
	{
		rollback();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";

		echo json_encode(array("updateuserpermission"=>$temp));
		close();
		exit();
	}
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid request";
	echo json_encode(array("updateuserpermission"=>$temp));
	close();
	exit();
}

?>
