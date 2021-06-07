<?php

header("Content-Type:application/json");
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='')
{	
	if(isset($_REQUEST['product_id'])&&$_REQUEST['product_id']!=''&&isset($_REQUEST['warrant_type'])&&$_REQUEST['warrant_type']!='')
	{
		$product_id=$_REQUEST['product_id'];
		if($_REQUEST['warrant_type']=='Not Applicable')
		{
			$warranty_days_mon_yr=0;
			$warranty_duration=0;
		}
		else
		{
		$warranty_days_mon_yr=$_REQUEST['warranty_days_mon_yr'];	
		$warranty_duration=$_REQUEST['warranty_duration'];
		
		}
		$query="
				UPDATE
				    product_details
				SET
				    product_brand = '".$_REQUEST['product_brand']."',
				    product_model = '".$_REQUEST['product_model']."',
				    warranty_duration = '".$warranty_duration."',
				    warrant_type = '".$_REQUEST['warrant_type']."',
				    warranty_days_mon_yr = '".$warranty_days_mon_yr."',
				    product_modification_datetime = NOW()
				WHERE
				    product_id = '".$product_id."'
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
		 		echo json_encode(array("updateproduct"=>$temp));
		 		close();
		 		exit();
		 	}
		 	else
		 	{
		 		rollback();
		 		$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("updateproduct"=>$temp));
				close();
				exit();
		 	}
	}		
	else
	{
		$temp=array();
		$temp['response_code']=400;
		$temp['response_desc']="Invalid Request";
		
 		echo json_encode(array("updateproduct"=>$temp));
 		close();
 		exit();
	}
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("updateproduct"=>$temp));
	close();
	exit();
}
close();
?>
