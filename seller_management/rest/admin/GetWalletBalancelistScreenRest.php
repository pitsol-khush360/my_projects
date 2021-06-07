<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$query="";

if(isset($_REQUEST['seller_id']) && $_REQUEST['seller_id']!="" && isset($_REQUEST['value_date']) && $_REQUEST['value_date']!="")
{
	$seller_id=$_REQUEST['seller_id'];
	$value_date=$_REQUEST['value_date'];
	
	$query="SELECT 
				seller_id,
				value_date,
				opening_balance,
				closing_balance,
				balance_currency
			FROM 
				wallet_balance 
			WHERE
				seller_id='".$seller_id."'
				AND
				value_date='".$value_date."'
			ORDER BY
				value_date,
				seller_id DESC
			";
}
else
if(isset($_REQUEST['seller_id']) && $_REQUEST['seller_id']!="")
{
	$seller_id=$_REQUEST['seller_id'];
	
	$query="SELECT 
				seller_id,
				value_date,
				opening_balance,
				closing_balance,
				balance_currency
			FROM 
				wallet_balance 
			WHERE
				seller_id='".$seller_id."'
			ORDER BY
				value_date,
				seller_id DESC
			";
}
else
if(isset($_REQUEST['value_date']) && $_REQUEST['value_date']!="")
{
	$value_date=$_REQUEST['value_date'];

	$query="SELECT 
				seller_id,
				value_date,
				opening_balance,
				closing_balance,
				balance_currency
			FROM 
				wallet_balance 
			WHERE
				value_date='".$value_date."'
			ORDER BY
				value_date,
				seller_id DESC
			";					
}
else
{
	$query="SELECT 
				seller_id,
				value_date,
				opening_balance,
				closing_balance,
				balance_currency
			FROM 
				wallet_balance 
			ORDER BY
				value_date,
				seller_id DESC
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
