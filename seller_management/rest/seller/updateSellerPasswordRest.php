<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='')
{	
	if(isset($_REQUEST['cur_password'])&&$_REQUEST['cur_password']!=''&&isset($_REQUEST['new_password'])&&$_REQUEST['new_password']!='')
	{
		$uid=$_REQUEST['user_id'];
		$query="SELECT 
				password 
			FROM 
				users 
			WHERE 
				user_id='".$uid."'";
		$query=query($query);
		confirm($query);
		$password='';
		while($row=fetch_array($query))
		{
			$password=$row['password'];
		}
		if($password==$_REQUEST['cur_password'])
		{
		
			$query="
					UPDATE
					    users
					SET 
					    password = '".$_REQUEST['new_password']."',
					    old_password = '".$_REQUEST['cur_password']."',
					    updated_datetime = NOW()
					WHERE
					    user_id = '".$uid."'
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
				$temp['response_desc']="Success";
		 		echo json_encode(array("updatepassword"=>$temp));
		 		close();
		 		exit();
		 	}
		 	else
		 	{
		 		rollback();
		 		$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("updatepassword"=>$temp));
				close();
		 		exit();
		 	}
		}
		else
		{
			$temp=array();
			$temp['response_code']=405;			// 405 means Not Allowed
			$temp['response_desc']="Wrong Password";
			echo json_encode(array("updatepassword"=>$temp));
			close();
		 	exit();
		}
	}
	else
		{
			
			$temp=array();
			$temp['response_code']=400;
			$temp['response_desc']="Invalid Request";
			echo json_encode(array("updatepassword"=>$temp));
			close();
		 	exit();
		}	
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("updatepassword"=>$temp));
	close();
	exit();
}
close();
?>
