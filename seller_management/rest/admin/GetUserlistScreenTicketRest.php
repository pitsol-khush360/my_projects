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
				ticket_id,
				seller_id,
				mobile,
				subject,
				description,
				resolution_remarks,
				status,
				created_date
			FROM 
				tickets 
			WHERE
				seller_id='".$seller_id."'
				ORDER BY 
				created_date DESC
			";
}
else		
if(isset($_REQUEST['ticket_id'])&&$_REQUEST['ticket_id']!='')
{
	$ticket=$_REQUEST['ticket_id'];
	$query="SELECT 
				ticket_id,
				seller_id,
				mobile,
				subject,
				description,
				resolution_remarks,
				status,
				created_date
			FROM 
				tickets
			WHERE
				ticket_id='".$ticket."'
				ORDER BY 
				created_date DESC
			";
}
else
if(isset($_REQUEST['status'])&&$_REQUEST['status']!='')
{
	$status=$_REQUEST['status'];
	$query="SELECT 
				ticket_id,
				seller_id,
				mobile,
				subject,
				description,
				resolution_remarks,
				status,
				created_date
			FROM 
				tickets
			WHERE
				status='".$status."'
				ORDER BY 
				created_date DESC
			";
}
else
{
	$query="SELECT 
				ticket_id,
				seller_id,
				mobile,
				subject,
				description,
				resolution_remarks,
				status,
				created_date
			FROM 
				tickets
				ORDER BY 
				created_date DESC
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
	echo json_encode(array("getticketdetails"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="Record Not Found";
	echo json_encode(array("getticketdetails"=>$temp));
	close();
	exit();
}
?>
