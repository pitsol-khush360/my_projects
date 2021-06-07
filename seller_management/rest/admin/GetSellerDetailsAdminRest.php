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
				U.username,
				SD.id,
				SD.seller_id,
				SD.seller_email,
				SD.seller_image,
				SD.seller_alternate_number,
				SD.alternate_contact_verified,
				SD.seller_address1,
				SD.seller_address2,
				SD.seller_city,
				SD.seller_pin,
				SD.seller_state,
				SD.seller_country,
				SD.beneficiary_id,
				SD.seller_business_name,
				SD.seller_gst,
				SD.seller_panname,
				SD.seller_pannum,
				SD.updatedby,
				SD.created_datetime,
				SD.updated_datetime,
				SD.beneficiary_name,
				SD.account_number,
				SD.ifsc_code,
				SD.cheque_image,
				SD.bank_account_verified,
				SD.kyc_application_status,
				SD.accept_online_payments,
				SD.accept_cod_payments,
				SD.pan_card_image,
				SD.gst_certificate_image,
				SD.gst_verified,
				SD.address_proof_image,
				SD.kyc_completed,
				SD.logistics_integrated,
				SD.waive_platform_fees,
				SD.notification_sms,
				SD.notification_email,
				SD.notification_whatsapp
			FROM 
				seller_details SD,
				users U
			WHERE
				SD.seller_id='".$seller_id."'
				AND 
				U.user_id = SD.seller_id
			";
					
//echo $query;
$query=query($query);
confirm($query);
$rows=mysqli_num_rows($query);

if($rows!=0)	// Valid Request, Data Found.
{
	$temp=array();
	$temp=fetch_array($query);
	$temp['response_code']=200;
	$temp['response_desc']="Success";
	$temp['rows']=$rows;
	
	echo json_encode(array("getreviewdetails"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="Record Not Found";

	echo json_encode(array("getreviewdetails"=>$temp));
	close();
	exit();
}
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";

	echo json_encode(array("getreviewdetails"=>$temp));
	close();
	exit();
}
?>
