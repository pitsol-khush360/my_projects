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
					seller_id ,
					opening_balance,
					closing_balance,
					closing_balance,
					balance_currency,
					value_date,
					creation_datetime
							FROM 
					wallet_balance 
							WHERE
					seller_id='".$seller_id."'";
					
}
else
if(isset($_REQUEST['value_date']) && $_REQUEST['value_date']!="")
{
	$value_date=$_REQUEST['value_date'];
	$query="SELECT 
					seller_id ,
					opening_balance,
					closing_balance,
					closing_balance,
					balance_currency,
					value_date,
					creation_datetime
							FROM 
					wallet_balance 
							WHERE
					value_date='".$value_date."'";
					
}

else
{
	$query="SELECT 
					seller_id ,
					opening_balance,
					closing_balance,
					closing_balance,
					balance_currency,
					value_date,
					creation_datetime
							FROM 
					wallet_balance";
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
	echo json_encode(array("getwalletdetails"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="No Records Found";
	echo json_encode(array("getwalletdetails"=>$temp));
	close();
	exit();
}
?>
