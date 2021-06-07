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
				basket_order_id,
				order_type,
				net_amount,
				order_date,
				seller_id,
				payment_gateway_status,
				order_status,
				payment_reference,
				created_datetime
			FROM 
				basket_order
			WHERE
				seller_id='".$seller_id."'
			ORDER BY
				created_datetime DESC
			";
					
}
else
if(isset($_REQUEST['basket_order_id']) && $_REQUEST['basket_order_id']!="")
{
	$basket_order_id=$_REQUEST['basket_order_id'];
	$query="SELECT
				basket_order_id,
				order_type,
				net_amount,
				order_date,
				seller_id,
				payment_gateway_status,
				order_status,
				payment_reference,
				created_datetime
			FROM 
				basket_order
			WHERE
				basket_order_id = '".$basket_order_id."'
			ORDER BY
				created_datetime DESC
			";
					
}
else
if(isset($_REQUEST['order_type']) && $_REQUEST['order_type']!="")
{
	$order_type=$_REQUEST['order_type'];
	$query="SELECT
				basket_order_id,
				order_type,
				net_amount,
				order_date,
				seller_id,
				payment_gateway_status,
				order_status,
				payment_reference,
				created_datetime
			FROM 
				basket_order
			WHERE
				order_type = '".$order_type."'
			ORDER BY
				created_datetime DESC
			";
					
}
else
if(isset($_REQUEST['order_date']) && $_REQUEST['order_date']!="")
{
	$order_date=$_REQUEST['order_date'];
	$query="SELECT
				basket_order_id,
				order_type,
				net_amount,
				order_date,
				seller_id,
				payment_gateway_status,
				order_status,
				payment_reference,
				created_datetime
			FROM 
				basket_order
			WHERE
				order_date='".$order_date."'
			ORDER BY
				created_datetime DESC
			";
					
}
else
if(isset($_REQUEST['order_status']) && $_REQUEST['order_status']!="")
{
	$order_status=$_REQUEST['order_status'];
	$query="SELECT
				basket_order_id,
				order_type,
				net_amount,
				order_date,
				seller_id,
				payment_gateway_status,
				order_status,
				payment_reference,
				created_datetime
			FROM 
				basket_order
			WHERE
				order_status = '".$order_status."'
			ORDER BY
				created_datetime DESC
			";
					
}

else
if(isset($_REQUEST['payment_reference']) && $_REQUEST['payment_reference']!="")
{
	$payment_reference=$_REQUEST['payment_reference'];
	$query="SELECT
				basket_order_id,
				order_type,
				net_amount,
				order_date,
				seller_id,
				payment_gateway_status,
				order_status,
				payment_reference,
				created_datetime
			FROM 
				basket_order
			WHERE
				payment_reference='".$payment_reference."'
			ORDER BY
				created_datetime DESC
			";
					
}
else
{
	$query="SELECT
				basket_order_id,
				order_type,
				net_amount,
				order_date,
				seller_id,
				payment_gateway_status,
				order_status,
				payment_reference,
				created_datetime
			FROM 
				basket_order
			ORDER BY
				created_datetime DESC
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
	$temp['response_desc']="Success";
	$temp['rows']=$rows;
	//print_r($temp);
	echo json_encode(array("getorderdetails"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="No Records Found";
	echo json_encode(array("getorderdetails"=>$temp));
	close();
	exit();
}
?>
