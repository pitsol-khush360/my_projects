<?php
header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$query="";

if(isset($_REQUEST['product_id']) && $_REQUEST['product_id']!="")
{
	$product_id=$_REQUEST['product_id'];
	$query="SELECT 
					product_id,
					product_seller_id,
					product_name ,
					product_price,
					product_offer_price,
					product_category,
					product_status,
					product_description,
					warranty_duration,
					warranty_days_mon_yr,
					warrant_type
			FROM 
					product_details
			WHERE
					product_id='".$product_id."'
			ORDER BY
					product_id 
			DESC
			";
					
}
else
if(isset($_REQUEST['product_seller_id']) && $_REQUEST['product_seller_id']!="")
{
	$product_seller_id=$_REQUEST['product_seller_id'];
	$query="SELECT 
					product_id,
					product_seller_id,
					product_name ,
					product_price,
					product_offer_price,
					product_category,
					product_status,
					product_description,
					warranty_duration,
					warranty_days_mon_yr,
					warrant_type
			FROM 
					product_details
			WHERE
					product_seller_id='".$product_seller_id."'
			ORDER BY
					product_id
			DESC
		";
}
else
if(isset($_REQUEST['product_name']) && $_REQUEST['product_name']!="")
{
	$product_name=$_REQUEST['product_name'];
	$query="SELECT 
					
					product_id,
					product_seller_id,
					product_name ,
					product_price,
					product_offer_price,
					product_category,
					product_status,
					product_description,
					warranty_duration,
					warranty_days_mon_yr,
					warrant_type
			FROM 
					product_details
			WHERE
					product_name='".$product_name."'
			ORDER BY
					product_id
			DESC
		";
					
}
else
if(isset($_REQUEST['product_category']) && $_REQUEST['product_category']!="")
{
	$product_category=$_REQUEST['product_category'];
	$query="SELECT 
					product_id,
					product_seller_id,
					product_name ,
					product_price,
					product_offer_price,
					product_category,
					product_status,
					product_description,
					warranty_duration,
					warranty_days_mon_yr,
					warrant_type
			FROM 
					product_details
			WHERE
					product_category='".$product_category."'
			ORDER BY
					product_id
			DESC
		";
					
}
else
if(isset($_REQUEST['product_status']) && $_REQUEST['product_status']!="")
{
	$product_status=$_REQUEST['product_status'];
	$query="SELECT 
					product_id,
					product_seller_id,
					product_name ,
					product_price,
					product_offer_price,
					product_category,
					product_status,
					product_description,
					warranty_duration,
					warranty_days_mon_yr,
					warrant_type
			FROM 
					product_details
			WHERE
					product_status='".$product_status."'
			ORDER BY
					product_id
			DESC
		";
					
}
else
{
	$query="SELECT 
					product_id,
					product_seller_id,
					product_name ,
					product_price,
					product_offer_price,
					product_category,
					product_status,
					product_description,
					warranty_duration,
					warranty_days_mon_yr,
					warrant_type
			FROM 
					product_details
			ORDER BY
					product_id
			DESC
		";
					
}
$query=query($query);
confirm($query);
$rows=mysqli_num_rows($query);

if($rows!=0)	// Valid Request, Data Found.
{
	$temp=array();
	while($row=fetch_array($query))
	{
		$temp[]=$row;
	}
	$temp['response_code']=200;
	$temp['response_desc']="success";
	$temp['rows']=$rows;
	//print_r($temp);
	echo json_encode(array("getproductdetails"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="No Records Found";
	echo json_encode(array("getproductdetails"=>$temp));
	close();
	exit();
}
?>
