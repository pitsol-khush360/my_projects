<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['admin_id'])&&$_REQUEST['admin_id']!='')
{	
	if(isset($_REQUEST['cur_password'])&&$_REQUEST['cur_password']!=''&&isset($_REQUEST['new_password'])&&$_REQUEST['new_password']!='')
	{
		$uid=$_REQUEST['admin_id'];
		$query="select password from admin_user where userid='".$uid."'";
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
					    admin_user
					SET 
						password = '".$_REQUEST[' new_password ']."'
					WHERE
					    userid = '".$uid."'
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
		 	}
		 	else
		 	{
		 		rollback();
		 		$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("updatepassword"=>$temp));
		 	}
		}
		else
		{
			$temp=array();
			$temp['response_code']=405;			
			$temp['response_desc']="Not Allowed";
			echo json_encode(array("updatepassword"=>$temp));
		}
	}
	else
		{
			
			$temp=array();
			$temp['response_code']=400;
			$temp['response_desc']="Invalid Request";
			echo json_encode(array("updatepassword"=>$temp));
		}	
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("updatepassword"=>$temp));
}
close();
?>
