<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='')
{	

		$query="
				SELECT
				    `id`,
				    `seller_id`,
				    `delivery_charge`,
				    `delivery_free_above`
				FROM
				    delivery_charges
				WHERE
				    seller_id = '".$_REQUEST['user_id']."'
		    	";
		$query=query($query);
		confirm($query);
		$temp=array();
		$rows=mysqli_num_rows($query);
		if($rows>0)
		{
		while($row=fetch_array($query))
			{
				$temp['delivery_charge']=$row['delivery_charge'];
				$temp['delivery_free_above']=$row['delivery_free_above'];
			}
		}
		if($rows==0)
		{
		
			$temp['delivery_charge']=0;
			$temp['delivery_free_above']=0;
			
		}
		$query="
				SELECT
				    accept_cod_payments,
				    accept_online_payments,
				    notification_email,
				    notification_sms,
				    notification_whatsapp,
				    logistics_integrated,
				    kyc_completed,
				    bank_account_verified
				FROM
				    seller_details
				WHERE
				    seller_id = '".$_REQUEST['user_id']."'
			    ";
		$query=query($query);
		confirm($query);
		$row=fetch_array($query);
		$temp['accept_cod_payments'] = $row['accept_cod_payments'];
		$temp['accept_online_payments'] = $row['accept_online_payments'];
		$temp['notification_email'] = $row['notification_email'];
		$temp['notification_sms'] = $row['notification_sms'];
		$temp['notification_whatsapp'] = $row['notification_whatsapp'];
		$temp['logistics_integrated'] = $row['logistics_integrated'];
		$temp['kyc_completed'] = $row['kyc_completed'];
		$temp['bank_account_verified'] = $row['bank_account_verified'];
		$temp['response_code']=200;
		$temp['response_desc']="Success";
 		echo json_encode(array("getdeliverycharge"=>$temp));
 		close();
 		exit();
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("getdeliverycharge"=>$temp));
	close();
	exit();
}
close();
?>
