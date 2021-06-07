<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(	
	isset($_REQUEST['user_id'])
	&&
	$_REQUEST['user_id']!='' 
	&& 
	isset($_REQUEST['address1'])
	&&
	$_REQUEST['address1']!='' 
	&& 
	isset($_REQUEST['address2'])
	&&
	$_REQUEST['address2']!='' 
	&& 
	isset($_REQUEST['city'])
	&&
	$_REQUEST['city']!='' 
	&& 
	isset($_REQUEST['state'])
	&&
	$_REQUEST['state']!=''&& 
	isset($_REQUEST['pincode'])
	&&
	$_REQUEST['pincode']!=''&& 
	isset($_REQUEST['country'])
	&&
	$_REQUEST['country']!=''
   ){	
	
		
		$query="
				UPDATE
				    seller_details
				SET
				    seller_address1 = '".$_REQUEST['address1']."',
				    seller_address2 = '".$_REQUEST['address2']."',
				    seller_city = '".$_REQUEST['city']."',
				    seller_state = '".$_REQUEST['state']."',
				    seller_pin = '".$_REQUEST['pincode']."',
				    seller_country = '".$_REQUEST['country']."'
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
	 		echo json_encode(array("updatecontactdetails"=>$temp));
	 		close();
	 		exit();
	 	}
	 	else
	 	{
	 		rollback();
	 		$temp=array();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("updatecontactdetails"=>$temp));
			close();
			exit();
	 	}
	
	
	}
	else
	{
		$temp=array();
		$temp['response_code']=400;
		$temp['response_desc']="Invalid Request";
		echo json_encode(array("updatecontactdetails"=>$temp));
		close();
		exit();
	}
close();	
?>
