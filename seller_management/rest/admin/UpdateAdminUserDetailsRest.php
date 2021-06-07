<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$connection->autocommit(FALSE);
$flag = true;

$query="";

if(isset($_REQUEST['admin_id']) && $_REQUEST['admin_id']!="")
{
	$admin_id=$_REQUEST['admin_id'];

	if(isset($_REQUEST['password']) && $_REQUEST['password']!='' && isset($_REQUEST['confirm_password']) && $_REQUEST['confirm_password']!='' && isset($_REQUEST['full_name'])&&$_REQUEST['full_name']!='' && isset($_REQUEST['role']) && $_REQUEST['role']!='' )
	{
			$password = $_REQUEST['password'];
			$confirm_password = $_REQUEST['confirm_password'];
			$full_name = $_REQUEST['full_name'];
			$role = $_REQUEST['role'];

			if($password == $confirm_password)
			{
				$query="UPDATE 
								admin_user
						SET 
								password = '".$password."',
								full_name = '".$full_name."',
								role ='".$role."'
						WHERE
								userid='".$admin_id."'";	
			}
			else
			{
				$temp['response_code']=405;
				$temp['response_desc']="password don't match";

				echo json_encode(array("updateuserdetails"=>$temp));
				close();
				exit();
			}
	}
	else
	if(isset($_REQUEST['full_name']) && $_REQUEST['full_name']!="" && isset($_REQUEST['role']) && $_REQUEST['role']!='')
	{
		$full_name=$_REQUEST['full_name'];
		$role = $_REQUEST['role'];
	
		$query="UPDATE 
						admin_user
				SET 
						full_name = '".$full_name."',
						role ='".$role."'

				WHERE
						userid='".$admin_id."'";
	}
	else
	if(isset($_REQUEST['password']) && $_REQUEST['password']!='' && isset($_REQUEST['confirm_password']) && $_REQUEST['confirm_password']!='' && isset($_REQUEST['full_name'])&&$_REQUEST['full_name']!='' )
	{
		$password = $_REQUEST['password'];
		$confirm_password = $_REQUEST['confirm_password'];
		$full_name = $_REQUEST['full_name'];

		if($password == $confirm_password)
		{
			$query="UPDATE 
							admin_user
					SET 
							password = '".$password."',
							full_name = '".$full_name."'
					WHERE
							userid='".$admin_id."'";	
		}
		else
		{
			$temp['response_code']=405;
			$temp['response_desc']="password don't match";

			echo json_encode(array("updateuserdetails"=>$temp));
			close();
			exit();
		}	
	}
	else
	if(isset($_REQUEST['password']) && $_REQUEST['password']!='' && isset($_REQUEST['confirm_password']) && $_REQUEST['confirm_password']!='' && isset($_REQUEST['role'])&&$_REQUEST['role']!='' )
	{
		$password = $_REQUEST['password'];
		$confirm_password = $_REQUEST['confirm_password'];
		$role = $_REQUEST['role'];

		if($password == $confirm_password)
		{
			$query="UPDATE 
							admin_user
					SET 
							password = '".$password."',
							role = '".$role."'
					WHERE
							userid='".$admin_id."'";	
		}
		else
		{
			$temp['response_code']=405;
			$temp['response_desc']="password don't match";

			echo json_encode(array("updateuserdetails"=>$temp));
			close();
			exit();
		}	
					
	}
	else
	if(isset($_REQUEST['full_name']) && $_REQUEST['full_name']!="")
	{
		$full_name = $_REQUEST['full_name'];
		$query="UPDATE 
						admin_user
				SET 
						full_name = '".$full_name."'
				WHERE
						userid='".$admin_id."'";
	}
	else		
	if(isset($_REQUEST['role'])&&$_REQUEST['role']!='')
	{
		$role=$_REQUEST['role'];
	
		$query="UPDATE 
						admin_user
				SET 
						role = '".$role."'
				WHERE
						userid='".$admin_id."'";
	}
	else
	if(isset($_REQUEST['password']) && $_REQUEST['password']!='' && isset($_REQUEST['confirm_password']) && $_REQUEST['confirm_password']!='')
	{
		$password = $_REQUEST['password'];
		$confirm_password = $_REQUEST['confirm_password'];

		if($password == $confirm_password)
		{
			$query="UPDATE 
							admin_user
					SET 
							password = '".$password."'
					WHERE
							userid='".$admin_id."'";	
		}
		else
		{
			$temp['response_code']=405;
			$temp['response_desc']="password don't match";

			echo json_encode(array("updateuserdetails"=>$temp));
			close();
			exit();
		}
	}
	else
	{
		$temp['response_code']=400;
		$temp['response_desc']="Invalid Request";

		echo json_encode(array("updateuserdetails"=>$temp));
		close();
		exit();
	}
	
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";

	echo json_encode(array("updateuserdetails"=>$temp));
	close();
	exit();	
}

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
			
	echo json_encode(array("updateuserdetails"=>$temp));
	close();
	exit();
}
else
{
	rollback();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";

	echo json_encode(array("updateuserdetails"=>$temp));
	close();
	exit();
}
?>
