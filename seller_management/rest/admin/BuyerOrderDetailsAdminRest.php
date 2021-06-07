<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$query="";

if(isset($_REQUEST['basket_order_id']) && $_REQUEST['basket_order_id']!="")
{
	$basket_order_id=$_REQUEST['basket_order_id'];
	
	$query="SELECT 
				order_id,
				order_quantity,
				order_amount_total,
				order_date,
				seller_id,
				product_id,
				catalogue_id
			FROM 
				orders 
			WHERE
				order_id='".$basket_order_id."'
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
	$temp['response_desc']="Record Not Found";
	echo json_encode(array("getorderdetails"=>$temp));
	close();
	exit();
}

?>
