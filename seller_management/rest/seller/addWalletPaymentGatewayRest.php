<?php
header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
require_once("../../config/config.php"); 
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
if(isset($_REQUEST['amount'])!=''&&$_REQUEST['amount']!=''&&isset($_REQUEST['customer_name'])&&$_REQUEST['customer_name']!='' && isset($_REQUEST['customer_mobile'])&&$_REQUEST['customer_mobile']!=''&&isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!=''&&isset($_REQUEST['wallet_opening_balance'])&&$_REQUEST['wallet_opening_balance']!='')
{	
	$user_id=$_REQUEST['user_id'];
	$query="SELECT
			    kyc_completed,
			    accept_online_payments,
			    bank_account_verified
			FROM
			    seller_details
			WHERE
			    seller_id = '".$user_id."'
			";
	//echo $query;
	$query=query($query);
	confirm($query);
	$row=fetch_array($query);
	if($row['kyc_completed']=='1')
	{
		if($row['bank_account_verified']=='Yes')
		{
			if($row['accept_online_payments']=='1')
			{
		 		  $order_id=date("YmdHis").rand(10000,100000);
		 		  $amount=$_REQUEST['amount'];
		 		  $str=$_REQUEST['sd'].$order_id;
		 		  $str=md5($str);
		 		  $wallet_opening_balance=$_REQUEST['wallet_opening_balance'];
		 		  $secretKey = SECREATKEY;
				  $postData = array( 
				  "secretKey" => SECREATKEY,
				  "appId" => APPID, 
				  "orderId" => $order_id, 
				  "orderAmount" => $_REQUEST['amount'], 
				  "orderCurrency" => "INR", 
				  "orderNote" => "Test", 
				  "customerName" => $_REQUEST['customer_name'], 
				  "customerPhone" => $_REQUEST['customer_mobile'], 
				  "customerEmail" => $_REQUEST['customer_email'],
				  "returnUrl" => DOMAIN."/app/seller/displaySellerPaymentConfirmation.php?sd=".$str,
				  "notifyUrl" => DOMAIN."/rest/seller/addMoneyToWalletRest.php");

				$apiEndpoint = PRODUCTION;
			    $opUrl = $apiEndpoint."/api/v1/order/create";
				$timeout = 10;
			   
			   $request_string = "";
			   foreach($postData as $key=>$value) {
			     $request_string .= $key.'='.rawurlencode($value).'&';
			   }
			   $query="INSERT INTO wallet_order(
						    seller_id,
						    order_id,
						    amount,
						    wallet_opening_balance,
						    wallet_closing_balance,
						    payment_reference,
						    order_status,
						    gateway_response_status,
						    created_date_time
						)
						VALUES(
						    '".$user_id."',
						    '".$order_id."',
						    '".$amount."',
						    '".$wallet_opening_balance."',
						    '0',
						    '',
						    'Draft',
						    '',
						    NOW())
						";
		   		$query=query($query);
		   		$result=confirm($query);
			   	if($result==true)
			   	{
			   	 
			   	 	commit();
				   $ch = curl_init();
				   curl_setopt($ch, CURLOPT_URL,"$opUrl?");
				   curl_setopt($ch,CURLOPT_POST, count($postData));
				   curl_setopt($ch,CURLOPT_POSTFIELDS, $request_string);
				   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				   curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
				   $curl_result=curl_exec ($ch);
				   curl_close ($ch);
			   		
				   $jsonResponse = json_decode($curl_result);
				   //print_r($jsonResponse);
				   if ($jsonResponse->{"status"}=='OK') {

				   		//echo $query;
				     $paymentLink = $jsonResponse->{"paymentLink"};
				     //print_r($jsonResponse);
				     //Send this payment link to customer over email/SMS OR redirect to this link on browser
				     //echo $paymentLink;
				     $temp=array();
					 $temp['response_code']=200;
					 $temp['response_desc']="Success";
					 $temp['paymentlink']=$paymentLink;
					 echo json_encode(array("changestatustorefund"=>$temp));
					 close();
					 exit();
				    //header("Location: ".$paymentLink);
					//exit;
				     
				   } else {
				   	//print_r($jsonResponse);
				   	 $temp=array();
					 $temp['response_code']=404;
					 $temp['response_desc']="Payment Fail";
					 echo json_encode(array("changestatustorefund"=>$temp));
				    //Log request, $jsonResponse["reason"]
				         close();
					 exit();
			 	   		} 
			 	   	}
		 		}
		 		else
		 		{
		 			 $temp=array();
					 $temp['response_code']=405;
					 $temp['response_desc']="Wallet Recharge not allowed, enable online payments first";
					 echo json_encode(array("changestatustorefund"=>$temp));
					 close();
					 exit();
		 		}
		 	}
		 	else
	 		{
	 			 $temp=array();
				 $temp['response_code']=405;
				 $temp['response_desc']="Please submit your Bank Account Details for verification before adding funds";
				 echo json_encode(array("changestatustorefund"=>$temp));
				 close();
				 exit();
	 		}
	 	}
	 	else
 		{
 			 $temp=array();
			 $temp['response_code']=405;
			 $temp['response_desc']="Wallet Recharge not allowed, KYC not complete";
			 echo json_encode(array("changestatustorefund"=>$temp));
			 close();
			 exit();
 		}
 	}
 	else
 	{
 		 $temp=array();
		 $temp['response_code']=400;
		 $temp['response_desc']="Invalid Request";
		 echo json_encode(array("changestatustorefund"=>$temp));
		 close();
		 exit();
 	}
?>