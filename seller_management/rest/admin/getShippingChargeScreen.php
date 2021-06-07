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
					id,
					seller_id,
					shipping_amount
							FROM 
					shipping_charges
							WHERE
					seller_id='".$seller_id."'";
					
}
else
if(isset($_REQUEST['shipping_amount']) && $_REQUEST['shipping_amount']!="")
{
	$shipping_amount=$_REQUEST['shipping_amount'];
	$query="SELECT 
					id,
					seller_id,
					shipping_amount
							FROM 
					shipping_charges
							WHERE
					shipping_amount='".$shipping_amount."'";
					
}
else
{
	$query="SELECT 
					id,
					seller_id,
					shipping_amount
							FROM 
					shipping_charges";
					
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
	echo json_encode(array("getshippingchargesdetails"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="No Records Found";
	echo json_encode(array("getshippingchargesdetails"=>$temp));
	close();
	exit();
}
?>
