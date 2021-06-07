<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$query="";

if(isset($_REQUEST['order_id']) && $_REQUEST['order_id']!="")
{
	$order_id=$_REQUEST['order_id'];
	
	$query="SELECT 
				cash_movement_id,
				entry_side,
				opening_balance,
				amount,
				amount_currency,
				dr_cr_Indicator,
				closing_balance,
				Movement_type,
				payment_reference,
				movement_status,
				movement_description,
				settled_amount,
				created_date_time
			FROM 
				cash_movements 
			WHERE
				order_id='".$order_id."'
			ORDER BY
				Movement_type,
				entry_side
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
	echo json_encode(array("getwalletcashmovementdetails"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="Record Not Found";
	echo json_encode(array("getwalletcashmovementdetails"=>$temp));
	close();
	exit();
}

?>
