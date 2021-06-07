<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$connection->autocommit(FALSE);
$flag = true;

if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!="")
{
	$user_id=$_REQUEST['user_id'];

	$query="SELECT 
				user_id,
				status
			FROM 
				users
			WHERE
				user_id ='".$user_id."'
			";
	$query=query($query);
	confirm($query);
	$temp = array();
	$temp = fetch_array($query);
	
	if($temp['user_id'] == $user_id)
	{
		if($temp['status'] == 'I' || $temp['status'] == 'i')
	    {
		
			$query = "  UPDATE 
							users 
						SET 
							status          ='A',
							updated_datetime=NOW()
						WHERE
							user_id         ='".$user_id."'
						";
	
			$query=query($query);
			$result=confirm($query);
			if(!$result)
			{
				$flag = false;
			}
			$temp=array();
			if($flag)
			{
				commit();
				$temp['response_code']=200;
				$temp['response_desc']="Success";
	
				echo json_encode(array("updatestatus"=>$temp));
				close();
				exit();
			}
			else
			{
				rollback();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";

				echo json_encode(array("updatestatus"=>$temp));
				close();
				exit();
			}
		}
		if($temp['status'] == 'A' || $temp['status'] == 'a')
	    {
		
			$query = "  UPDATE 
							users 
						SET 
							status='I',
							updated_datetime=NOW()
						WHERE
							user_id='".$user_id."'
						";
	
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
	
				echo json_encode(array("updatestatus"=>$temp));
				close();
				exit();
			}
			else
			{
				rollback();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";

				echo json_encode(array("updatestatus"=>$temp));
				close();
				exit();
			}
		}

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
