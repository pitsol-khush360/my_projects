<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$query="";

if(isset($_REQUEST['seller_id']) && $_REQUEST['seller_id']!="" )
{
	$seller_id=$_REQUEST['seller_id'];
	
	$query="SELECT 
				seller_id,
				order_id,
				amount,
				wallet_opening_balance,
				wallet_closing_balance,
				payment_reference,
				order_status,
				gateway_response_status,
				created_date_time
			FROM 
				wallet_order 
			WHERE
				seller_id='".$seller_id."'
			ORDER BY
				created_date_time
				DESC
			";
}
else
if(isset($_REQUEST['order_id']) && $_REQUEST['order_id']!="")
{
	$order_id=$_REQUEST['order_id'];
	
	$query="SELECT 
				seller_id,
				order_id,
				amount,
				wallet_opening_balance,
				wallet_closing_balance,
				payment_reference,
				order_status,
				gateway_response_status,
				created_date_time
			FROM 
				wallet_order 
			WHERE
				order_id='".$order_id."'
			ORDER BY
				created_date_time
				DESC
			";
}
else
if(isset($_REQUEST['payment_reference']) && $_REQUEST['payment_reference']!="")
{
	$payment_reference=$_REQUEST['payment_reference'];
	
	$query="SELECT 
				seller_id,
				order_id,
				amount,
				wallet_opening_balance,
				wallet_closing_balance,
				payment_reference,
				order_status,
				gateway_response_status,
				created_date_time
			FROM 
				wallet_order 
			WHERE
				payment_reference='".$payment_reference."'
			ORDER BY
				created_date_time
				DESC
			";
}
else
if(isset($_REQUEST['order_status']) && $_REQUEST['order_status']!="")
{
	$order_status=$_REQUEST['order_status'];

	$query="SELECT 

				seller_id,
				order_id,
				amount,
				wallet_opening_balance,
				wallet_closing_balance,
				payment_reference,
				order_status,
				gateway_response_status,
				created_date_time
			FROM 
				wallet_order 
			WHERE
				order_status='".$order_status."'
			ORDER BY
				created_date_time
				DESC
			";					
}
else
{
	$query="SELECT 
				seller_id,
				order_id,
				amount,
				wallet_opening_balance,
				wallet_closing_balance,
				payment_reference,
				order_status,
				gateway_response_status,
				created_date_time
			FROM 
				wallet_order 
			ORDER BY
				created_date_time
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
	$temp['response_desc']="Success";
	$temp['rows']=$rows;
	//print_r($temp);
	echo json_encode(array("getwalletdetails"=>$temp));
	close();
	exit();
}

else
{
	$temp['response_code']=405;
	$temp['response_desc']="Record Not Found";
	echo json_encode(array("getwalletdetails"=>$temp));
	close();
	exit();
}

?>
