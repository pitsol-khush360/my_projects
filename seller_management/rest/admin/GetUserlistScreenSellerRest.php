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
				seller_id,
				seller_business_name,
				kyc_application_status,
				kyc_completed,
				accept_online_payments,
				gst_verified,
				logistics_integrated,
				seller_alternate_number
			FROM 
				seller_details
			WHERE
				seller_id='".$seller_id."'
				ORDER BY 
				created_datetime DESC
			";
}
else		
if(isset($_REQUEST['kyc_application_status'])&&$_REQUEST['kyc_application_status']!='')
{
	$kyc_application_status=$_REQUEST['kyc_application_status'];
	$query="SELECT 
				seller_id,
				seller_business_name,
				kyc_application_status,
				kyc_completed,
				accept_online_payments,
				gst_verified,
				logistics_integrated,
				seller_alternate_number
			FROM 
				seller_details
			WHERE
				kyc_application_status='".$kyc_application_status."'
				ORDER BY 
				created_datetime DESC
			";
}
else		
if(isset($_REQUEST['kyc_completed'])&&$_REQUEST['kyc_completed']!='')
{
	$kyc_completed=$_REQUEST['kyc_completed'];
	$query="SELECT 
				seller_id,
				seller_business_name,
				kyc_application_status,
				kyc_completed,
				accept_online_payments,
				gst_verified,
				logistics_integrated,
				seller_alternate_number
			FROM 
				seller_details
			WHERE
				kyc_completed='".$kyc_completed."'
				ORDER BY 
				created_datetime DESC
			";
}
else
if(isset($_REQUEST['accept_online_payments'])&&$_REQUEST['accept_online_payments']!='')
{
	$accept_online_payments=$_REQUEST['accept_online_payments'];
	$query="SELECT 
				seller_id,
				seller_business_name,
				kyc_application_status,
				kyc_completed,
				accept_online_payments,
				gst_verified,
				logistics_integrated,
				seller_alternate_number
			FROM 
				seller_details
			WHERE
				accept_online_payments='".$accept_online_payments."'
				ORDER BY 
				created_datetime DESC
			";
}
else
if(isset($_REQUEST['gst_verified'])&&$_REQUEST['gst_verified']!='')
{
	$gst_verified=$_REQUEST['gst_verified'];
	$query="SELECT 
				seller_id,
				seller_business_name,
				kyc_application_status,
				kyc_completed,
				accept_online_payments,
				gst_verified,
				logistics_integrated,
				seller_alternate_number
			FROM 
				seller_details
			WHERE
				gst_verified='".$gst_verified."'
				ORDER BY 
				created_datetime DESC
			";
}
else
if(isset($_REQUEST['logistics_integrated'])&&$_REQUEST['logistics_integrated']!='')
{
	$logistics_integrated=$_REQUEST['logistics_integrated'];
	$query="SELECT 
				seller_id,
				seller_business_name,
				kyc_application_status,
				kyc_completed,
				accept_online_payments,
				gst_verified,
				logistics_integrated,
				seller_alternate_number
			FROM 
				seller_details
			WHERE
				logistics_integrated='".$logistics_integrated."'
				ORDER BY 
				created_datetime DESC
			";
}
else
if(isset($_REQUEST['seller_alternate_number'])&&$_REQUEST['seller_alternate_number']!='')
{
	$seller_alternate_number=$_REQUEST['seller_alternate_number'];
	$query="SELECT 
				seller_id,
				seller_business_name,
				kyc_application_status,
				kyc_completed,
				accept_online_payments,
				gst_verified,
				logistics_integrated,
				seller_alternate_number
			FROM 
				seller_details
			WHERE
				seller_alternate_number='".$seller_alternate_number."'
				ORDER BY 
				created_datetime DESC
			";
}
else
{
	$query="SELECT 
				seller_id,
				seller_business_name,
				kyc_application_status,
				kyc_completed,
				accept_online_payments,
				gst_verified,
				logistics_integrated,
				seller_alternate_number
			FROM 
				seller_details
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
	
	echo json_encode(array("getsellerdetails"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="Record Not Found";
	echo json_encode(array("getsellerdetails"=>$temp));
	close();
	exit();
}
?>
