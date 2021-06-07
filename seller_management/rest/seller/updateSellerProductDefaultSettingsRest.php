<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
// if(isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='' && isset($_REQUEST['discount_type']) &&$_REQUEST['discount_type']!='' &&isset($_REQUEST['tax_type'])&&$_REQUEST['tax_type']!='' && isset($_REQUEST['free_shipping'])&&$_REQUEST['free_shipping']!='' && isset($_REQUEST['return_available'])&&$_REQUEST['return_available']!=''&&isset($_REQUEST['cash_on_delivery'])&&$_REQUEST['cash_on_delivery']!='' && isset($_REQUEST['warrant_type'])&&$_REQUEST['warrant_type']!='' )
if(isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='' && isset($_REQUEST['discount_type']) &&$_REQUEST['discount_type']!='' &&isset($_REQUEST['tax_type'])&&$_REQUEST['tax_type']!=''&& isset($_REQUEST['warrant_type'])&&$_REQUEST['warrant_type']!='' )
{	
	$query="
			SELECT
			    *
			FROM
			    product_default_settings
			WHERE
			    seller_id = '".$_REQUEST['user_id']."'
			";
	$query=query($query);	
	confirm($query);
	$rows=mysqli_num_rows($query);

	$warranty_days_mon_yr=0;
	$warranty_duration=0;
	$tax_percent=0;
	$discount=0;

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
	if($_REQUEST['tax_type']=='None')
	{
		$tax_percent=0;
	}
	else
	{
		$tax_percent=$_REQUEST['tax_percent'];
	}
	if($_REQUEST['discount_type']=='None')
	{
		$discount=0;
	}
	else
	{
		$discount=$_REQUEST['discount_percent'];
	}
		
	if($rows!=0)
	{	
		// $query="update product_default_settings set 
		// 			product_category ='".$_REQUEST['product_category']."',
		// 			discount_type ='".$_REQUEST['discount_type']."',
		// 			discount_percent ='".$discount."',
		// 			tax_type ='".$_REQUEST['tax_type']."',
		// 			tax_percent ='".$tax_percent."',
		// 			free_shipping ='".$_REQUEST['free_shipping']."',
		// 			return_available ='".$_REQUEST['return_available']."',
		// 			cash_on_delivery ='".$_REQUEST['cash_on_delivery']."',
		// 			warrant_type ='".$_REQUEST['warrant_type']."',
		// 			warrant_duration ='".$warranty_duration."',
		// 			warranty_days_mon_yr ='".$warranty_days_mon_yr."'
		// 			where  
		// 				seller_id='".$_REQUEST['user_id']."'";
		$query="
				UPDATE
				    product_default_settings
				SET
				    product_category = '".$_REQUEST['product_category']."',
				    discount_type = '".$_REQUEST['discount_type']."',
				    discount_percent = '".$discount."',
				    tax_type = '".$_REQUEST['tax_type']."',
				    tax_percent = '".$tax_percent."',
				    warrant_type = '".$_REQUEST['warrant_type']."',
				    warrant_duration = '".$warranty_duration."',
				    warranty_days_mon_yr = '".$warranty_days_mon_yr."'
				WHERE
				    seller_id = '".$_REQUEST['user_id']."'
				";
	}
	else
	{
		// $query="INSERT INTO product_default_settings (seller_id, product_category,discount_type, discount_percent,tax_type,tax_percent,free_shipping,return_available,cash_on_delivery,warrant_type,warrant_duration,warranty_days_mon_yr)
		// 		values	(
		// 				'".$_REQUEST['user_id']."',
		// 				'".$_REQUEST['product_category']."',
		// 				'".$_REQUEST['discount_type']."',
		// 				'".$discount."',
		// 				'".$_REQUEST['tax_type']."',
		// 				'".$tax_percent."',
		// 				'".$_REQUEST['free_shipping']."',
		// 				'".$_REQUEST['return_available']."',
		// 				'".$_REQUEST['cash_on_delivery']."',
		// 				'".$_REQUEST['warrant_type']."',
		// 				'".$warranty_duration."',
		// 				'".$warranty_days_mon_yr."'
		// 				) ";
		$query="
				INSERT INTO product_default_settings(
				    seller_id,
				    product_category,
				    discount_type,
				    discount_percent,
				    tax_type,
				    tax_percent,
				    warrant_type,
				    warrant_duration,
				    warranty_days_mon_yr
				)
				VALUES(
						'".$_REQUEST['user_id']."',
						'".$_REQUEST['product_category']."',
						'".$_REQUEST['discount_type']."',
						'".$discount."',
						'".$_REQUEST['tax_type']."',
						'".$tax_percent."',
						'".$_REQUEST['warrant_type']."',
						'".$warranty_duration."',
						'".$warranty_days_mon_yr."'
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
	 		echo json_encode(array("updateproductdefault"=>$temp));
	 		close();
	 		exit();
	 	}
	 	else
	 	{
	 		rollback();
	 		$temp=array();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("updateproductdefault"=>$temp));
			close();
			exit();
	 	}
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("updateproductdefault"=>$temp));
	close();
	exit();
}
close();
?>
