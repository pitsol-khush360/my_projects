<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='' && isset($_REQUEST['username'])&&$_REQUEST['username']!='' )
{	
	$query="
			SELECT
			    username,
			    user_id
			FROM
			    users
			WHERE
			    username = '".$_REQUEST['username']."'
			";
	$query=query($query);
	confirm($query);
	$row=fetch_array($query);
	$rows=mysqli_num_rows($query);
	if($rows==0)
	{
		$query="
				UPDATE
				    users
				SET
				    username = '".$_REQUEST['username']."'
				WHERE
				    user_id = '".$_REQUEST['user_id']."'
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
	 		echo json_encode(array("updateusername"=>$temp));
	 		close();
	 		exit();
	 	}
	 	else
	 	{
	 		rollback();
	 		$temp=array();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("updateusername"=>$temp));
			close();
			exit();
	 	}
	}
	else
	{
		$temp=array();
		$temp['response_code']=405;
		$temp['response_desc']="Already Username Exists";
		echo json_encode(array("updateusername"=>$temp));
		close();
		exit();
		
	}
	
	
	
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("updateusername"=>$temp));
	close();
	exit();
}
close();	
?>
