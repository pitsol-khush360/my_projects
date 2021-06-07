<?php
header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['status'])&&$_REQUEST['status']!=''&&isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='')
{	
	$query="
		SELECT
		    accept_online_payments
		FROM
		    seller_details
		WHERE
		    seller_id = '".$_REQUEST['user_id']."'";
	$query=query($query);
	$row=fetch_array($query);
	$onlineStatus=$row['accept_online_payments'];
	if($onlineStatus=='0' && $_REQUEST['status']=='0')
	{
		$temp=array();
		$temp['response_code']=405;
		$temp['response_desc']="Atleast one Payment Method should be active";
		echo json_encode(array("updatecod"=>$temp));
		close();
 		exit();
	}
	$query="
			UPDATE
			    seller_details
			SET
			    accept_cod_payments = '".$_REQUEST['status']."'
			WHERE
			    seller_id = '".$_REQUEST['user_id']."'
    		";
		//echo $query;
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
 		echo json_encode(array("updatecod"=>$temp));
 		close();
 		exit();

	}
	else
	{
		rollback();
		$temp=array();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("updatecod"=>$temp));
		close();
		exit();
	}
	
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("updatecod"=>$temp));
	close();
	exit();
}
close();
?>
