<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='')
{	
	if(isset($_REQUEST['product_id'])&&$_REQUEST['product_id']!='' && isset($_REQUEST['product_catalogue_id']) && $_REQUEST['product_catalogue_id']!='' && isset($_REQUEST['product_price']) && $_REQUEST['product_price']!='')
	{
		$product_id=$_REQUEST['product_id'];
		$query="
			SELECT
			    discount_type,
			    discount
			FROM
			    product_details
			WHERE
			    product_seller_id = '".$_REQUEST['user_id']."' 
			    AND 
			    product_id = '".$product_id."'
			";
		$query=query($query);
		confirm($query);
		$row=fetch_array($query);
		$productofferprice=0;
		if($row['discount_type']=='None')
		{
			$productofferprice=$_REQUEST['product_price'];
		}
		else if($row['discount_type']=='Flat')
		{
			$productofferprice=$_REQUEST['product_price']-$row['discount'];
		}
		else if($row['discount_type']=='Percentage')
		{
			$productofferprice=$_REQUEST['product_price']-(($_REQUEST['product_price']/100)*$row['discount']);

		}
	
		$query="
				UPDATE
				    product_details
				SET
				    updatedby = '".$_REQUEST['user_id']."',    
				    product_price = '".$_REQUEST['product_price']."',
				    product_offer_price = '".$productofferprice."',
				    product_price_currency = 'INR',
				    product_modification_datetime = NOW()
				WHERE
				    product_id = '".$product_id."'
				    AND
				    product_catalogue_id ='".$_REQUEST['product_catalogue_id']."'
				    AND
				    product_seller_id = '".$_REQUEST['user_id']."'
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
