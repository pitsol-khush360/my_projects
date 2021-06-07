<?php
header("Content-Type:application/json"); // setting content as well as we will convert data into json type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$connection->autocommit(FALSE);
$flag = true;

$query="";

if(isset($_REQUEST['admin_id']) && $_REQUEST['admin_id']!="" && isset($_REQUEST['current_user']) && $_REQUEST['current_user']!="" )
{
	$admin_id = $_REQUEST['admin_id'];
	$current_user = $_REQUEST['current_user'];

	$query = "  SELECT 
					 userid,
					 status,
					 last_modified_by
				FROM 
					admin_user 
				WHERE
					userid='".$admin_id."'";
					
	$query=query($query);
	confirm($query);
	$temp = array();
	$temp = fetch_array($query);
 	
    
    if($temp['status'] == 'Captured' && $temp['last_modified_by']!= $current_user)
    {
			
		$query = "  UPDATE 
						admin_user
					SET 
						status = 'Active'
					WHERE
						userid='".$admin_id."'";
	
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
	
				echo json_encode(array("getauthorizeuserdetails"=>$temp));
				close();
				exit();
			}
			else
			{
				rollback();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";

				echo json_encode(array("getauthorizeuserdetails"=>$temp));
				close();
				exit();
			}
	}
	else
	if($temp['status'] == 'Captured' && $temp['last_modified_by'] == $current_user)
	{
		$temp['response_code']=405;
		$temp['response_desc']="You can't authorized your own edit";
		
		echo json_encode(array("getauthorizeuserdetails"=>$temp));
		close();
		exit();
	}
	else
	{
		$temp['response_code']=405;
		$temp['response_desc']="Record Not found";
		echo json_encode(array("getauthorizeuserdetails"=>$temp));
		close();
		exit();
	}
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	
	echo json_encode(array("getauthorizeuserdetails"=>$temp));
	close();
	exit();
}
?>
