<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='' && isset($_REQUEST['delivery_charge'])&&$_REQUEST['delivery_charge']!='' && isset($_REQUEST['delivery_free_above'])&&$_REQUEST['delivery_free_above']!='')
{	
	$query="SELECT seller_id FROM delivery_charges WHERE seller_id = '".$_REQUEST['user_id']."'";
	$query=query($query);
	confirm($query);
	$rows=mysqli_num_rows($query);
	if($rows!=0)
	{
		$query="
				UPDATE
				    delivery_charges
				SET
				    delivery_charge = '".$_REQUEST['delivery_charge']."',
				    delivery_free_above = '".$_REQUEST['delivery_free_above']."'
				WHERE
				    seller_id = '".$_REQUEST['user_id']."'
				";
	}
	else
	{
		$query= "
				INSERT INTO delivery_charges(
				    seller_id,
				    delivery_charge,
				    delivery_free_above
				)
				VALUES(
				    '".$_REQUEST['user_id']."',
				    '".$_REQUEST['delivery_charge']."',
				    '".$_REQUEST['delivery_free_above']."'
				)
				";
	}

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
	 		echo json_encode(array("updatedeliverycharge"=>$temp));
	 		close();
	 		exit();

		}
		else
		{
			rollback();
			$temp=array();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("updatedeliverycharge"=>$temp));
			close();
	 		exit();
		}
	
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("updatedeliverycharge"=>$temp));
	close();
	exit();
}
close();	
?>
