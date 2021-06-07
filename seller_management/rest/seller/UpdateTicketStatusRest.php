<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!=''&&isset($_REQUEST['ticket_id']) && $_REQUEST['ticket_id']!=''&&isset($_REQUEST['status']) && $_REQUEST['status']!='')
{

	$user_id=$_REQUEST['user_id'];
	$ticket_id=$_REQUEST['ticket_id'];
	$query="
			UPDATE
			    tickets
			SET
				status = '".$_REQUEST['status']."'
			WHERE
			    seller_id = '".$user_id."' 
			    AND
			    ticket_id = '".$ticket_id."'
			" ;
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
 		echo json_encode(array("updateticket"=>$temp));
 		close();
 		exit();
 	}
 	else
 	{
 		rollback();
 		$temp=array();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("updateticket"=>$temp));
		close();
		exit();
 	}
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("updateticket"=>$temp));
	close();
	exit();
}
close();
?>
