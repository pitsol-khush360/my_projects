<?php
header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$query="";

if(isset($_REQUEST['seller_id']) && $_REQUEST['seller_id']!="")
{
	$seller_id=$_REQUEST['seller_id'];
	$query="SELECT 
					seller_id  ,
					catalogue_id  ,
					product_id ,
					promo_code,
					minimum_order_amount,
					discount_type,
					discount_value,
					is_active,
					expiry_date
							FROM 
					promocodes
							WHERE
					seller_id='".$seller_id."'";
					
}
else
if(isset($_REQUEST['catalogue_id']) && $_REQUEST['catalogue_id']!="")
{
	$catalogue_id=$_REQUEST['catalogue_id'];
	$query="SELECT 
					seller_id  ,
					catalogue_id  ,
					product_id ,
					promo_code,
					minimum_order_amount,
					discount_type,
					discount_value,
					is_active,
					expiry_date
							FROM 
					promocodes
							WHERE
					catalogue_id='".$catalogue_id."'";
}
else
if(isset($_REQUEST['product_id']) && $_REQUEST['product_id']!="")
{
	$product_id=$_REQUEST['product_id'];
	$query="SELECT 
					
					seller_id  ,
					catalogue_id  ,
					product_id ,
					promo_code,
					minimum_order_amount,
					discount_type,
					discount_value,
					is_active,
					expiry_date
							FROM 
					promocodes
							WHERE
					product_id='".$product_id."'";
					
}
else
if(isset($_REQUEST['promo_code']) && $_REQUEST['promo_code']!="")
{
	$promo_code=$_REQUEST['promo_code'];
	$query="SELECT 
					seller_id  ,
					catalogue_id  ,
					product_id ,
					promo_code,
					minimum_order_amount,
					discount_type,
					discount_value,
					is_active,
					expiry_date
							FROM 
					promocodes
							WHERE
					promo_code='".$promo_code."'";
					
}
else
if(isset($_REQUEST['is_active']) && $_REQUEST['is_active']!="")
{
	$is_active=$_REQUEST['is_active'];
	$query="SELECT 
					seller_id  ,
					catalogue_id  ,
					product_id ,
					promo_code,
					minimum_order_amount,
					discount_type,
					discount_value,
					is_active,
					expiry_date
							FROM 
					promocodes
							WHERE
					is_active='".$is_active."'";
					
}
else
{
	$query="SELECT 
					seller_id  ,
					catalogue_id  ,
					product_id ,
					promo_code,
					minimum_order_amount,
					discount_type,
					discount_value,
					is_active,
					expiry_date
							FROM 
					promocodes";
					
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
	echo json_encode(array("getpromocodedetails"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="No Records Found";
	echo json_encode(array("getpromocodedetails"=>$temp));
	close();
	exit();
}
?>
