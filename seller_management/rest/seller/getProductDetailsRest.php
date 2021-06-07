<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='')
{		
	$start=0;
	$end=10;
	if(isset($_REQUEST['start']))
		$start=$_REQUEST['start'];

	if(isset($_REQUEST['end']))
		$end=$_REQUEST['end'];

	$user_id=$_REQUEST['user_id'];
	
	$name="";
	$cid="";
	$pid="";

	if(isset($_REQUEST['productname']))
	$name=$_REQUEST['productname'];

	if(isset($_REQUEST['catalogueid']))
		$cid=$_REQUEST['catalogueid'];
	if(isset($_REQUEST['productid']))
		$pid=$_REQUEST['productid'];
	$status='';
	if(isset($_REQUEST['status']))
		$status=$_REQUEST['status'];

	$query='';

	if(isset($name) && $name!="" && $cid=='' && $status=='')
	{
		$query=query("
						SELECT
						    PC.`catalogue_id`,
						    PC.`catalogue_seller_id`,
						    PC.`catalogue_Name`,
						    PC.`creation_datetime`,
						    PC.`modification_datetime`,
						    PC.`updatedby`,
						    PC.`catalogue_status`,
						    PC.`catalogue_image`,
						    PD.`product_id`,
						    PD.`product_seller_id`,
						    PD.`productimage`,
						    PD.`product_name`,
						    PD.`product_price`,
						    PD.`product_price_currency`,
						    PD.`product_brand`,
						    PD.`product_category`,
						    PD.`product_sub_category`,
						    PD.`product_store`,
						    PD.`product_description`,
						    PD.`product_creation_datetime`,
						    PD.`product_modification_datetime`,
						    PD.`updatedby`,
						    PD.`product_catalogue_id`,
						    PD.`warranty_duration`,
						    PD.`warranty_days_mon_yr`,
						    PD.`warrant_type`,
						    PD.`valid_from`,
						    PD.`product_model`,
						    PD.`product_offer_price`,
						    PD.`tax_type`,
						    PD.`tax_percent`,
						    PD.`free_shipping`,
						    PD.`cancellation_available`,
						    PD.`cash_on_delivery`,
						    PD.`return_available`,
						    PD.`product_inventory`,
						    PD.`product_unit`,
						    PD.`product_status`,
						    PD.`discount_type`,
						    PD.`discount`
						FROM
						    product_details PD,
						    product_catalogue PC
						WHERE
						    PD.product_seller_id = '".$user_id."' 
						    AND 
						    PD.product_name LIKE '%".$name."%' 
						    AND 
						    PD.product_catalogue_id = PC.catalogue_id
						ORDER BY
						    PD.product_id
						DESC
    				");
		//$query=query("SELECT * FROM product_details where product_seller_id='".$user_id."' and product_name like '%".$name."%' order by product_id  desc limit ".$start.",".$end);
	}
	else if(isset($cid)  && $cid!="" && $name=="" && $status=='')
	{
		$query=query("
						SELECT
						    PC.`catalogue_id`,
						    PC.`catalogue_seller_id`,
						    PC.`catalogue_Name`,
						    PC.`creation_datetime`,
						    PC.`modification_datetime`,
						    PC.`updatedby`,
						    PC.`catalogue_status`,
						    PC.`catalogue_image`,
						    PD.`product_id`,
						    PD.`product_seller_id`,
						    PD.`productimage`,
						    PD.`product_name`,
						    PD.`product_price`,
						    PD.`product_price_currency`,
						    PD.`product_brand`,
						    PD.`product_category`,
						    PD.`product_sub_category`,
						    PD.`product_store`,
						    PD.`product_description`,
						    PD.`product_creation_datetime`,
						    PD.`product_modification_datetime`,
						    PD.`updatedby`,
						    PD.`product_catalogue_id`,
						    PD.`warranty_duration`,
						    PD.`warranty_days_mon_yr`,
						    PD.`warrant_type`,
						    PD.`valid_from`,
						    PD.`product_model`,
						    PD.`product_offer_price`,
						    PD.`tax_type`,
						    PD.`tax_percent`,
						    PD.`free_shipping`,
						    PD.`cancellation_available`,
						    PD.`cash_on_delivery`,
						    PD.`return_available`,
						    PD.`product_inventory`,
						    PD.`product_unit`,
						    PD.`product_status`,
						    PD.`discount_type`,
						    PD.`discount`
						FROM
						    product_details PD,
						    product_catalogue PC
						WHERE
						    PD.product_seller_id = '".$user_id."' 
						    AND 
						    PD.product_catalogue_id = PC.catalogue_id 
						    AND 
						    PD.product_catalogue_id = ".$cid
					);
								//$query=query("SELECT * FROM product_details where product_seller_id='".$user_id."' and product_id = ".$pid);
	}
	else if(isset($pid)  && $pid!="" && $name=="" && $status=='')
	{
		$query=query("
						SELECT
						    PC.`catalogue_id`,
						    PC.`catalogue_seller_id`,
						    PC.`catalogue_Name`,
						    PC.`creation_datetime`,
						    PC.`modification_datetime`,
						    PC.`updatedby`,
						    PC.`catalogue_status`,
						    PC.`catalogue_image`,
						    PD.`product_id`,
						    PD.`product_seller_id`,
						    PD.`productimage`,
						    PD.`product_name`,
						    PD.`product_price`,
						    PD.`product_price_currency`,
						    PD.`product_brand`,
						    PD.`product_category`,
						    PD.`product_sub_category`,
						    PD.`product_store`,
						    PD.`product_description`,
						    PD.`product_creation_datetime`,
						    PD.`product_modification_datetime`,
						    PD.`updatedby`,
						    PD.`product_catalogue_id`,
						    PD.`warranty_duration`,
						    PD.`warranty_days_mon_yr`,
						    PD.`warrant_type`,
						    PD.`valid_from`,
						    PD.`product_model`,
						    PD.`product_offer_price`,
						    PD.`tax_type`,
						    PD.`tax_percent`,
						    PD.`free_shipping`,
						    PD.`cancellation_available`,
						    PD.`cash_on_delivery`,
						    PD.`return_available`,
						    PD.`product_inventory`,
						    PD.`product_unit`,
						    PD.`product_status`,
						    PD.`discount_type`,
						    PD.`discount`
						FROM
						    product_details PD,
						    product_catalogue PC
						WHERE
						    PD.product_seller_id = '".$user_id."' 
						    AND 
						    PD.product_catalogue_id = PC.catalogue_id 
						    AND 
						    PD.product_id = ".$pid
					);
		
		//$query=query("SELECT * FROM product_details where product_seller_id='".$user_id."' and product_id = ".$pid);
	}
	else if(isset($name) && $name!="" && $cid!="" && $status=='' && $pid=="")
	{
		$query=query("
						SELECT
						    PC.`catalogue_id`,
						    PC.`catalogue_seller_id`,
						    PC.`catalogue_Name`,
						    PC.`creation_datetime`,
						    PC.`modification_datetime`,
						    PC.`updatedby`,
						    PC.`catalogue_status`,
						    PC.`catalogue_image`,
						    PD.`product_id`,
						    PD.`product_seller_id`,
						    PD.`productimage`,
						    PD.`product_name`,
						    PD.`product_price`,
						    PD.`product_price_currency`,
						    PD.`product_brand`,
						    PD.`product_category`,
						    PD.`product_sub_category`,
						    PD.`product_store`,
						    PD.`product_description`,
						    PD.`product_creation_datetime`,
						    PD.`product_modification_datetime`,
						    PD.`updatedby`,
						    PD.`product_catalogue_id`,
						    PD.`warranty_duration`,
						    PD.`warranty_days_mon_yr`,
						    PD.`warrant_type`,
						    PD.`valid_from`,
						    PD.`product_model`,
						    PD.`product_offer_price`,
						    PD.`tax_type`,
						    PD.`tax_percent`,
						    PD.`free_shipping`,
						    PD.`cancellation_available`,
						    PD.`cash_on_delivery`,
						    PD.`return_available`,
						    PD.`product_inventory`,
						    PD.`product_unit`,
						    PD.`product_status`,
						    PD.`discount_type`,
						    PD.`discount`
						FROM
						    product_details PD,
						    product_catalogue PC
						WHERE
						    PD.product_seller_id = '".$user_id."' 
						    AND 
						    PD.product_catalogue_id = ".$cid." 
						    AND 
						    PD.product_name LIKE '%".$name."%' 
						    AND 
						    PD.product_catalogue_id = PC.catalogue_id
						ORDER BY
						    PD.product_id
						DESC
						LIMIT ".$start.", ".$end
					);
		//$query=query("SELECT * FROM product_details where product_seller_id='".$user_id."' and product_id =".$pid." and product_name like '%".$name."%' order by product_id  desc limit ".$start.",".$end);
	}
	else if($cid!="" && $name=""  && $status=='' && $pid=="")
	{
		$query=query("
						SELECT
						    PC.`catalogue_id`,
						    PC.`catalogue_seller_id`,
						    PC.`catalogue_Name`,
						    PC.`creation_datetime`,
						    PC.`modification_datetime`,
						    PC.`updatedby`,
						    PC.`catalogue_status`,
						    PC.`catalogue_image`,
						    PD.`product_id`,
						    PD.`product_seller_id`,
						    PD.`productimage`,
						    PD.`product_name`,
						    PD.`product_price`,
						    PD.`product_price_currency`,
						    PD.`product_brand`,
						    PD.`product_category`,
						    PD.`product_sub_category`,
						    PD.`product_store`,
						    PD.`product_description`,
						    PD.`product_creation_datetime`,
						    PD.`product_modification_datetime`,
						    PD.`updatedby`,
						    PD.`product_catalogue_id`,
						    PD.`warranty_duration`,
						    PD.`warranty_days_mon_yr`,
						    PD.`warrant_type`,
						    PD.`valid_from`,
						    PD.`product_model`,
						    PD.`product_offer_price`,
						    PD.`tax_type`,
						    PD.`tax_percent`,
						    PD.`free_shipping`,
						    PD.`cancellation_available`,
						    PD.`cash_on_delivery`,
						    PD.`return_available`,
						    PD.`product_inventory`,
						    PD.`product_unit`,
						    PD.`product_status`,
						    PD.`discount_type`,
						    PD.`discount`
						FROM
						    product_details PD,
						    product_catalogue PC
						WHERE
						    PD.product_seller_id = '".$user_id."' 
						    AND 
						    PD.product_catalogue_id = '".$_REQUEST['catalogueid']."' 
						    AND 
						    PD.product_catalogue_id = PC.catalogue_id
						ORDER BY
						    PD.product_id
						DESC
						    ");
		//$query=query("SELECT * FROM product_details where product_seller_id='".$user_id."' and product_catalogue_id=".$_REQUEST['product_catalogue_id']." order by product_id  desc limit ".$start.",".$end);
	}
	else if($cid!="" && $name=="" && isset($status) && $status!="" && $pid=="")
	{
		$query=query("
						SELECT
						    PC.`catalogue_id`,
						    PC.`catalogue_seller_id`,
						    PC.`catalogue_Name`,
						    PC.`creation_datetime`,
						    PC.`modification_datetime`,
						    PC.`updatedby`,
						    PC.`catalogue_status`,
						    PC.`catalogue_image`,
						    PD.`product_id`,
						    PD.`product_seller_id`,
						    PD.`productimage`,
						    PD.`product_name`,
						    PD.`product_price`,
						    PD.`product_price_currency`,
						    PD.`product_brand`,
						    PD.`product_category`,
						    PD.`product_sub_category`,
						    PD.`product_store`,
						    PD.`product_description`,
						    PD.`product_creation_datetime`,
						    PD.`product_modification_datetime`,
						    PD.`updatedby`,
						    PD.`product_catalogue_id`,
						    PD.`warranty_duration`,
						    PD.`warranty_days_mon_yr`,
						    PD.`warrant_type`,
						    PD.`valid_from`,
						    PD.`product_model`,
						    PD.`product_offer_price`,
						    PD.`tax_type`,
						    PD.`tax_percent`,
						    PD.`free_shipping`,
						    PD.`cancellation_available`,
						    PD.`cash_on_delivery`,
						    PD.`return_available`,
						    PD.`product_inventory`,
						    PD.`product_unit`,
						    PD.`product_status`,
						    PD.`discount_type`,
						    PD.`discount`
						FROM
						    product_details PD,
						    product_catalogue PC
						WHERE
						    PD.product_seller_id = '".$user_id."' 
						    AND 
						    PD.product_catalogue_id = '".$_REQUEST['catalogueid']."' 
						    AND 
						    PD.product_status = '".$status."' 
						    AND 
						    PD.product_catalogue_id = PC.catalogue_id
						ORDER BY
						    PD.product_id
						DESC
    
					");
		//$query=query("SELECT * FROM product_details where product_seller_id='".$user_id."' and product_catalogue_id=".$_REQUEST['product_catalogue_id']." order by product_id  desc limit ".$start.",".$end);
	}
	else if(  $cid=="" && isset($status) && $status!="" && $pid=="")
	{
		$query=query("
						SELECT
						    PC.`catalogue_id`,
						    PC.`catalogue_seller_id`,
						    PC.`catalogue_Name`,
						    PC.`creation_datetime`,
						    PC.`modification_datetime`,
						    PC.`updatedby`,
						    PC.`catalogue_status`,
						    PC.`catalogue_image`,
						    PD.`product_id`,
						    PD.`product_seller_id`,
						    PD.`productimage`,
						    PD.`product_name`,
						    PD.`product_price`,
						    PD.`product_price_currency`,
						    PD.`product_brand`,
						    PD.`product_category`,
						    PD.`product_sub_category`,
						    PD.`product_store`,
						    PD.`product_description`,
						    PD.`product_creation_datetime`,
						    PD.`product_modification_datetime`,
						    PD.`updatedby`,
						    PD.`product_catalogue_id`,
						    PD.`warranty_duration`,
						    PD.`warranty_days_mon_yr`,
						    PD.`warrant_type`,
						    PD.`valid_from`,
						    PD.`product_model`,
						    PD.`product_offer_price`,
						    PD.`tax_type`,
						    PD.`tax_percent`,
						    PD.`free_shipping`,
						    PD.`cancellation_available`,
						    PD.`cash_on_delivery`,
						    PD.`return_available`,
						    PD.`product_inventory`,
						    PD.`product_unit`,
						    PD.`product_status`,
						    PD.`discount_type`,
						    PD.`discount`
						FROM
						    product_details PD,
						    product_catalogue PC
						WHERE
						    PD.product_seller_id = '".$user_id."' 
						    AND  
						    PD.product_status = '".$status."' 
						    AND 
						    PD.product_catalogue_id = PC.catalogue_id
						ORDER BY
						    PD.product_id
						DESC
    
					");
		//$query=query("SELECT * FROM product_details where product_seller_id='".$user_id."' and product_catalogue_id=".$_REQUEST['product_catalogue_id']." order by product_id  desc limit ".$start.",".$end);
	}
	else 
	{
		$query=query("
						SELECT
						    PC.`catalogue_id`,
						    PC.`catalogue_seller_id`,
						    PC.`catalogue_Name`,
						    PC.`creation_datetime`,
						    PC.`modification_datetime`,
						    PC.`updatedby`,
						    PC.`catalogue_status`,
						    PC.`catalogue_image`,
						    PD.`product_id`,
						    PD.`product_seller_id`,
						    PD.`productimage`,
						    PD.`product_name`,
						    PD.`product_price`,
						    PD.`product_price_currency`,
						    PD.`product_brand`,
						    PD.`product_category`,
						    PD.`product_sub_category`,
						    PD.`product_store`,
						    PD.`product_description`,
						    PD.`product_creation_datetime`,
						    PD.`product_modification_datetime`,
						    PD.`updatedby`,
						    PD.`product_catalogue_id`,
						    PD.`warranty_duration`,
						    PD.`warranty_days_mon_yr`,
						    PD.`warrant_type`,
						    PD.`valid_from`,
						    PD.`product_model`,
						    PD.`product_offer_price`,
						    PD.`tax_type`,
						    PD.`tax_percent`,
						    PD.`free_shipping`,
						    PD.`cancellation_available`,
						    PD.`cash_on_delivery`,
						    PD.`return_available`,
						    PD.`product_inventory`,
						    PD.`product_unit`,
						    PD.`product_status`,
						    PD.`discount_type`,
						    PD.`discount`
						FROM
						    product_details PD,
						    product_catalogue PC
						WHERE
						    PD.product_seller_id = '".$user_id."' 
						    AND 
						    PD.product_catalogue_id = PC.catalogue_id
						ORDER BY
						    product_id
						DESC
						LIMIT ".$start.", ".$end
					);
		//$query=query("SELECT * FROM product_details where product_seller_id='".$user_id."' order by product_id desc limit ".$start.",".$end);
		
	}
		
	confirm($query);
	$rows=mysqli_num_rows($query);
	
	if($rows>0)	
	{
		$temp=array();
		while($row=fetch_array($query))
		{
			$temp[]=$row;
		}
		$temp['response_code']=200;
		$temp['response_desc']="Success";
		$temp['rows']=$rows;
		
 		echo json_encode(array("getproducts"=>$temp));
 		close();
 		exit();
 	}
 	else
 	{
 		$temp['response_code']=405;
		$temp['response_desc']="No Records Found";
		$temp['rows']=$rows;
 		echo json_encode(array("getproducts"=>$temp));
 		close();
 		exit();
 	}	
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("getproducts"=>$temp));
	close();
 	exit();
}
close();
?>
