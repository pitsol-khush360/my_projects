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
					customer_name ,
					customer_mobile ,
					net_amount,
					order_date,
					seller_id,
					delivery_address1,
					city,
					payment_method,
					payment_gateway_status,
					order_status,
					delivery_charge,
					created_datetime
							FROM 
					basket_order
							WHERE
					seller_id='".$seller_id."'";
					
}
else
if(isset($_REQUEST['basket_order_id']) && $_REQUEST['basket_order_id']!="")
{
	$basket_order_id=$_REQUEST['basket_order_id'];
	$query="SELECT 
					basket_order_id,
					order_type,
					customer_name ,
					customer_mobile ,
					net_amount,
					order_date,
					seller_id,
					delivery_address1,
					city,
					payment_method,
					payment_gateway_status,
					order_status,
					delivery_charge,
					created_datetime
							FROM 
					basket_order
							WHERE
					basket_order_id='".$basket_order_id."'";
					
}
else
if(isset($_REQUEST['customer_name']) && $_REQUEST['customer_name']!="")
{
	$customer_name=$_REQUEST['customer_name'];
	$query="SELECT 
					basket_order_id,
					order_type,
					customer_name ,
					customer_mobile ,
					net_amount,
					order_date,
					seller_id,
					delivery_address1,
					city,
					payment_method,
					payment_gateway_status,
					order_status,
					delivery_charge,
					created_datetime
							FROM 
					basket_order
							WHERE
					customer_name='".$customer_name."'";
					
}
else
if(isset($_REQUEST['customer_mobile']) && $_REQUEST['customer_mobile']!="")
{
	$customer_mobile=$_REQUEST['customer_mobile'];
	$query="SELECT 
					basket_order_id,
					order_type,
					customer_name ,
					customer_mobile ,
					net_amount,
					order_date,
					seller_id,
					delivery_address1,
					city,
					payment_method,
					payment_gateway_status,
					order_status,
					delivery_charge,
					created_datetime
							FROM 
					basket_order
							WHERE
					customer_mobile='".$customer_mobile."'";
					
}
else
if(isset($_REQUEST['city']) && $_REQUEST['city']!="")
{
	$city=$_REQUEST['city'];
	$query="SELECT 
					basket_order_id,
					order_type,
					customer_name ,
					customer_mobile ,
					net_amount,
					order_date,
					seller_id,
					delivery_address1,
					city,
					payment_method,
					payment_gateway_status,
					order_status,
					delivery_charge,
					created_datetime
							FROM 
					basket_order
							WHERE
					city='".$city."'";
					
}

else
if(isset($_REQUEST['payment_method']) && $_REQUEST['payment_method']!="")
{
	$payment_method=$_REQUEST['payment_method'];
	$query="SELECT 
					basket_order_id,
					order_type,
					customer_name ,
					customer_mobile ,
					net_amount,
					order_date,
					seller_id,
					delivery_address1,
					city,
					payment_method,
					payment_gateway_status,
					order_status,
					delivery_charge,
					created_datetime
							FROM 
					basket_order
							WHERE
					payment_method='".$payment_method."'";
					
}
else
if(isset($_REQUEST['payment_gateway_status']) && $_REQUEST['payment_gateway_status']!="")
{
	$payment_gateway_status=$_REQUEST['payment_gateway_status'];
	$query="SELECT 
					basket_order_id,
					order_type,
					customer_name ,
					customer_mobile ,
					net_amount,
					order_date,
					seller_id,
					delivery_address1,
					city,
					payment_method,
					payment_gateway_status,
					order_status,
					delivery_charge,
					created_datetime
							FROM 
					basket_order
							WHERE
					payment_gateway_status='".$payment_gateway_status."'";
					
}
else
if(isset($_REQUEST['order_status']) && $_REQUEST['order_status']!="")
{
	$order_status=$_REQUEST['order_status'];
	$query="SELECT 
					basket_order_id,
					order_type,
					customer_name ,
					customer_mobile ,
					net_amount,
					order_date,
					seller_id,
					delivery_address1,
					city,
					payment_method,
					payment_gateway_status,
					order_status,
					delivery_charge,
					created_datetime
							FROM 
					basket_order
							WHERE
					order_status='".$order_status."'";
					
}
else
{
	$query="SELECT 
					basket_order_id,
					order_type,
					customer_name ,
					customer_mobile ,
					net_amount,
					order_date,
					seller_id,
					delivery_address1,
					city,
					payment_method,
					payment_gateway_status,
					order_status,
					delivery_charge,
					created_datetime
							FROM 
					basket_order";
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
