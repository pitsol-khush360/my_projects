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
					cash_movement_id ,
					opening_balance,
					amount,
					closing_balance,
					movement_date,
					movement_type,
					movement_description,
					seller_id,
					order_id,
					movement_status
							FROM 
					cash_movements 
							WHERE
					seller_id='".$seller_id."'
					AND
					entry_side='seller'";
}
else		
if(isset($_REQUEST['order_id'])&&$_REQUEST['order_id']!='')
{
	$order_id=$_REQUEST['order_id'];
	$query="SELECT 
					cash_movement_id ,
					opening_balance,
					amount,
					closing_balance,
					movement_date,
					movement_type,
					movement_description,
					seller_id,
					order_id,
					movement_status
							FROM 
					cash_movements 
							WHERE
					order_id='".$order_id."'
					AND
					entry_side='seller'";
}
else
if(isset($_REQUEST['cash_movement_id'])&&$_REQUEST['cash_movement_id']!='')
{
	$cash_movement_id=$_REQUEST['cash_movement_id'];
	$query="SELECT 
					cash_movement_id ,
					opening_balance,
					amount,
					closing_balance,
					movement_date,
					movement_type,
					movement_description,
					seller_id,
					order_id,
					movement_status
							FROM 
					cash_movements 
							WHERE
					cash_movement_id='".$cash_movement_id."'
					AND
					entry_side='seller'";
}
else
if(isset($_REQUEST['movement_status'])&&$_REQUEST['movement_status']!='')
{
	$movement_status=$_REQUEST['movement_status'];
	$query="SELECT 
					cash_movement_id ,
					opening_balance,
					amount,
					closing_balance,
					movement_date,
					movement_type,
					movement_description,
					seller_id,
					order_id,
					movement_status
							FROM 
					cash_movements 
							WHERE
					movement_status='".$movement_status."'
					AND
					entry_side='seller'";
}
else
{
	$query="SELECT 
					cash_movement_id ,
					opening_balance,
					amount,
					closing_balance,
					movement_date,
					movement_type,
					movement_description,
					seller_id,
					order_id,
					movement_status
							FROM 
					cash_movements 
						    WHERE
					entry_side='seller'";
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
	echo json_encode(array("getcashmovementseller"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="No Records Found";
	echo json_encode(array("getcashmovementseller"=>$temp));
	close();
	exit();
}
?>
