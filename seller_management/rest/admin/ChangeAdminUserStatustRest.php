<?php
header("Content-Type:application/json"); // setting content as well as we will convert data into json type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$connection->autocommit(FALSE);
$flag = true;

$query="";

if(isset($_REQUEST['admin_id']) && $_REQUEST['admin_id']!="")
{
	$admin_id = $_REQUEST['admin_id'];

		$query= "SELECT 
					userid,
					status
			  	FROM 
					admin_user 
			  	WHERE
					userid='".$admin_id."'";
					
	$query=query($query);
	confirm($query);
	$temp = array();
	$temp = fetch_array($query);

    if($temp['userid'] == $admin_id)
    {
	
	    if($temp['status'] == 'active' || $temp['status'] == 'Active')
	    {
		
			$query = "  UPDATE 
							admin_user
						SET 
							status = 'Suspended'
						WHERE
							userid='".$admin_id."'";
	
			$query=query($query);
			$result=confirm($query);
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
	
				echo json_encode(array("getchangeadminstatusdetails"=>$temp));
				close();
				exit();
			}
			else
			{
				rollback();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";

				echo json_encode(array("getchangeadminstatusdetails"=>$temp));
				close();
				exit();
			}
		}
		if($temp['status'] == 'suspended' || $temp['status'] == 'Suspended')
		{
			
			$query = "UPDATE admin_user
							SET 
							status = 'Active'
							WHERE
							userid='".$admin_id."'";
	
			$query=query($query);
			$result=confirm($query);
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
	
				echo json_encode(array("getchangeadminstatusdetails"=>$temp));
				close();
				exit();
			}
			else
			{
				rollback();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";

				echo json_encode(array("getchangeadminstatusdetails"=>$temp));
				close();
				exit();
			}	
		}
	}
	else
	{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("getchangeadminstatusdetails"=>$temp));
	close();
	exit();
	}
}

?>
