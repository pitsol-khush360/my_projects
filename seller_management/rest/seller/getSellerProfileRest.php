<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['sid']) && $_REQUEST['sid']!='')
{
	$sid=$_REQUEST['sid'];
	//$sid=2;
	/*$query=query("SELECT * FROM seller_details where seller_id='".$sid."'");
	confirm($query);

	$temp=array();

	if(mysqli_num_rows($query)!=0)
	{
		$row=fetch_array($query);
		$temp[]=$row;
	}
*/
	$temp=array();
	$query1=query("
					SELECT
					    US.`user_id`,
					    US.`role`,
					    US.`mobile`,
					    US.`password`,
					    US.`old_password`,
					    US.`username`,
					    US.`business_name`,
					    US.`status`,
					    US.`created_datetime`,
					    US.`updated_datetime`,
					    US.`mobile_verified`,
					    US.`accept_terms_and_conditions`,
					    SD.`seller_id`,
					    SD.`seller_email`,
					    SD.`email_verified`,
					    SD.`notification_email`,
					    SD.`notification_sms`,
					    SD.`notification_whatsapp`,
					    SD.`seller_image`,
					    SD.`seller_alternate_number`,
					    SD.`alternate_contact_verified`,
					    SD.`seller_address1`,
					    SD.`seller_address2`,
					    SD.`seller_city`,
					    SD.`seller_pin`,
					    SD.`seller_state`,
					    SD.`seller_country`,
					    SD.`seller_business_name`,
					    SD.`seller_gst`,
					    SD.`seller_panname`,
					    SD.`seller_pannum`,
					    SD.`updatedby`,
					    SD.`created_datetime`,
					    SD.`updated_datetime`,
					    SD.`beneficiary_name`,
					    SD.`beneficiary_id`,
					    SD.`account_number`,
					    SD.`cheque_image`,
					    SD.`bank_account_verified`,
					    SD.`ifsc_code`,
					    SD.`kyc_application_status`,
					    SD.`accept_online_payments`,
					    SD.`accept_cod_payments`,
					    SD.`pan_card_image`,
					    SD.`gst_certificate_image`,
					    SD.`gst_verified`,
					    SD.`address_proof_image`,
					    SD.`kyc_completed`,
					    SD.`logistics_integrated`,
					    SD.`waive_platform_fees`
					FROM
					    seller_details SD,
					    users US
					WHERE
					    SD.seller_id = '".$sid."' 
					    AND 
					    SD.seller_id = US.user_id
				");
	confirm($query1);

	if(mysqli_num_rows($query1)>0)		// Valid Request, Data Found.
	{
		$row1=fetch_array($query1);
		$temp[]=$row1;
 	}
 	else
 	{
 		$temp[1]['store_name']="";
		$temp[1]['store_email']="";
		$temp[1]['store_mobile']="";
		$temp[1]['store_phone']="";
		$temp[1]['store_pin']="";
		$temp[1]['store_address']="";
		$temp[1]['store_city']="";
		$temp[1]['store_state']="";
		$temp[1]['store_country']="";
		$temp[1]['store_status']=0;
		$temp[1]['store_verified']=0;
 	}

 	$temp['response_code']=200;
 	$temp['response_desc']="success";

 	echo json_encode(array("seller"=>$temp));
 	close();
 	exit();
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("seller"=>$temp));
	close();
	exit();
}
close();
?>
