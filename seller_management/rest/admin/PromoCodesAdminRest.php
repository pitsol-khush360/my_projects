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
					seller_id,
					promo_code,
					discount_type,
					discount_value,
					minimum_order_amount,
					is_active,
					expiry_date
							FROM 
					promocodes
							WHERE
					seller_id='".$seller_id."'
			";
					
}
else
if(isset($_REQUEST['expiry_date']) && $_REQUEST['expiry_date']!="")
{
	$expiry_date=$_REQUEST['expiry_date'];
	$query="SELECT 
				seller_id  ,
				promo_code,
				minimum_order_amount,
				discount_type,
				discount_value,
				is_active,
				expiry_date
			FROM 
				promocodes
			WHERE
				expiry_date='".$expiry_date."'
			ORDER BY
				created_datetime
			";
					
}
else
if(isset($_REQUEST['is_active']) && $_REQUEST['is_active']!="")
{
	$is_active=$_REQUEST['is_active'];
	$query="SELECT 
				seller_id  ,
				promo_code,
				minimum_order_amount,
				discount_type,
				discount_value,
				is_active,
				expiry_date
			FROM 
				promocodes
			WHERE
				is_active='".$is_active."'
			ORDER BY
				created_datetime
			";
					
}
else
{
	$query="SELECT 
				seller_id  ,
				promo_code,
				minimum_order_amount,
				discount_type,
				discount_value,
				is_active,
				expiry_date
			FROM 
				promocodes
			ORDER BY
				created_datetime
			";
					
}
//echo $query;
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
	$temp['response_desc']="Success";
	$temp['rows']=$rows;
	//print_r($temp);
	echo json_encode(array("getpromocodedetails"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="Record Not Found";
	echo json_encode(array("getpromocodedetails"=>$temp));
	close();
	exit();
}
?>
