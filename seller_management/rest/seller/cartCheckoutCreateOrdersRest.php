<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/email_functions.php');
require_once("../validation.php");
$array = unserialize($_POST['data']);
$connection->autocommit(FALSE);
$flag=true;
if($array['order_type']!='' && $array['customer_name']!='' &&$array['customer_mobile']!=''&&$array['total_items']!=''&&$array['net_amount']!=''&&$array['user_id']!=''&&$array['delivery_address1']!='')
{
	$basket_order_id=date("YmdHis").rand(10000,100000);
	//  	//echo json_encode($_RESQUEST['0']['user_id']);
	// // }
		//echo json_encode($array['payment_method']);
 	if($array['order_type']=='Prepaid')
 	{
 		$order_status='Draft';
 	}
 	if($array['order_type']=='COD')
 	{
 		$order_status='Pending';
 	}
	$time='NOW()';
	$customer_mobile=$array['customer_mobile'];
	$customer_email='customerorders@uatcode.com';
	if($array['customer_email']!='')
	{
		$customer_email=$array['customer_email'];
	}
	$customer_name=$array['customer_name'];
	$query='INSERT INTO basket_order(
		    basket_order_id,
		    order_type,
		    customer_name,
		    customer_mobile,
		    customer_email,
		    total_items,
		    tax_amount,
		    net_amount,
		    order_date,
		    seller_id,
		    delivery_address1,
		    delivery_address2,
		    city,
		    state,
		    pincode,
		    country,
		    payment_method,
		    order_status,
		    discount,
		    promo_code,
		    delivery_charge,
		    created_datetime,
		    updated_datetime
		)
		VALUES(';
				$query.=''.$basket_order_id.','
				;
				$query.='"'.$array['order_type'].'",'
				;
				$query.='"'.$array['customer_name'].'",'
				;
				$query.='"'.$array['customer_mobile'].'",'
				;
				$query.='"'.$customer_email.'",'
				;
				$query.='"'.$array['total_items'].'",'
				;
				$query.='"'.$array['tax_amount'].'",'
				;
				$query.='"'.round($array['net_amount'],2).'",'
				;
				$query.='CURDATE(),'
				;
				$query.='"'.$array['user_id'].'",';
				$query.='"'.$array['delivery_address1'].'",'
				;
				$query.='"'.$array['delivery_address2'].'",'
				;
				$query.='"'.$array['city'].'",'
				;
				$query.='"'.$array['state'].'",'
				;
				$query.='"'.$array['pincode'].'",'
				;
				$query.='"'.$array['country'].'",'
				;
				$query.='"'.$array['payment_method'].'",'
				;
				$query.='"'.$order_status.'",'
				;
				$query.='"'.$array['discount'].'",'
				;
				$query.='"'.$array['promo_code'].'",'
				;
				$query.='"'.$array['delivery_charge'].'",'
				;
				$query.='NOW(),';
				$query.='NOW()
			)';

	$query=query($query);
	$result=confirm($query);
	//echo json_encode($query);
	if(!$result)
	{
		$flag = false;
		rollback();
		$temp=array();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("checkout"=>$temp));
		close();
		exit();
	}	
	
	for ($i=0;$i<(sizeof($array)-$array['fieldcount']);$i++)
	{		
			
			
		$query='INSERT INTO orders(
				    order_id,
				    order_quantity,
				    product_price,
				    tax_amount,
				    order_amount_total,
				    order_date,
				    seller_id,
				    product_id,
				    catalogue_id,
				    created_datetime,
				    updated_datetime
				)
				VALUES(';
						$query.=''.$basket_order_id.','
						;
						$query.='"'.$array[$i]['order_quantity'].'",'
						;
						$query.='"'.$array[$i]['product_price'].'",'
						;
						$query.='"'.$array[(string)$i]['tax_amount'].'",'
						;
						$query.='"'.round($array[(string)$i]['order_amount_total'],2).'",'
						;
						$query.='NOW(),'
						;
						$query.='"'.$array[(string)$i]['user_id'].'",'
						;
						$query.='"'.$array[(string)$i]['product_id'].'",'
						;
						$query.='"'.$array[(string)$i]['catalogue_id'].'",'
						;
						$query.='NOW(),';
						$query.='NOW()
					  )';
				
		//echo json_encode($query);
		//echo json_decode($query);
		$query=query($query);
		$result=confirm($query);

		if(!$result)
		{
			$flag = false;
			rollback();
			$temp=array();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("checkout"=>$temp));
			close();
			exit();
		}

		
	}

	if($array['order_type']=='Prepaid')
	{
		  $secretKey =SECREATKEY;
		  $postData = array( 
		  "secretKey" => SECREATKEY,
		  "appId" => APPID, 
		  "orderId" => $basket_order_id, 
		  "orderAmount" => round($array['net_amount'],2), 
		  "orderCurrency" => "INR", 
		  "orderNote" => "Test", 
		  "customerName" => $array['customer_name'], 
		  "customerPhone" => $array['customer_mobile'], 
		  "customerEmail" => $customer_email,
		   "returnUrl" => "http://localhost:80/ecom/app/displayCustomerOrderConfirmation.php?sd=".$array['sellerdomain'], 
		   "notifyUrl" => "http://localhost:80/ecom/rest/seller/updateBasketOrderRest.php");

			$apiEndpoint = PRODUCTION;
		    $opUrl = $apiEndpoint."/api/v1/order/create";
			$timeout = 10;
		   
		   $request_string = "";
		   foreach($postData as $key=>$value) {
		     $request_string .= $key.'='.rawurlencode($value).'&';
		   }
		   
		   $ch = curl_init();
		   curl_setopt($ch, CURLOPT_URL,"$opUrl?");
		   curl_setopt($ch,CURLOPT_POST, count($postData));
		   curl_setopt($ch,CURLOPT_POSTFIELDS, $request_string);
		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		   curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		   $curl_result=curl_exec ($ch);
		   curl_close ($ch);
		   $jsonResponse = json_decode($curl_result);
		   //$d=array($curl_result);
		   //echo json_encode($jsonResponse->{'status'});
		    $paymentLink='';
		    //print_r($jsonResponse);
		    if (isset($jsonResponse)&&$jsonResponse->{'status'} == "OK") {
		    $paymentLink = $jsonResponse->{"paymentLink"}; 
		   // echo json_encode($jsonResponse->{'status'});
		   }
		   else
		   {
		   	$flag=false;
		   	rollback();
			$temp=array();
			$temp['order_type']='Prepaid';
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("checkout"=>$temp));
			close();
			exit();
		   }
		   //echo json_encode($flag);
	 	   if($flag)
			{

				commit();
				$temp=array();
				$temp['order_type']='Prepaid';
				$temp['paymentLink']=$paymentLink;
				$temp['response_code']=200;			
				$temp['response_desc']="Success";
				echo json_encode(array("checkout"=>$temp));
				close();
				exit();
			}
			else
			{
				rollback();
				$temp=array();
				$temp['order_type']='Prepaid';
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("checkout"=>$temp));
				close();
				exit();
			}
	 	   //echo json_decode($flag);
	}
	if($array['order_type']=='COD')		
	{
		$query=query("SELECT
					    SD.seller_alternate_number,
					    SD.alternate_contact_verified,
						U.status,
					    SD.logistics_integrated,
					    SD.kyc_completed,
					    SD.waive_platform_fees,
					    SD.seller_email,
					    SD.email_verified,
					    SD.notification_email,
					    SD.notification_sms,
					    SD.notification_whatsapp,
					    SD.seller_business_name,
					    U.username
					FROM
					    users U,
					    seller_details SD
					WHERE
					    U.user_id = '".$array['user_id']."' 
					    AND 
					    U.user_id = SD.seller_id");
		confirm($query);
		if(mysqli_num_rows($query)==0)
		{
			$temp=array();
			$temp['response_code']=405;
			$temp['response_desc']="No records Found";
			echo json_encode(array("checkout"=>$temp));
			close();
			exit();
		}
		$row=fetch_array($query);
		$seller_email=$row['seller_email'];
		$email_verified=$row['email_verified'];
		$notification_whatsapp=$row['notification_whatsapp'];
		$notification_sms=$row['notification_sms'];
		$notification_email=$row['notification_email'];
		$seller_alternate_number=$row['seller_alternate_number'];
		$alternate_contact_verified=$row['alternate_contact_verified'];
		$seller_business_name=$row['seller_business_name'];
		$username=$row['username'];
		if($flag)
			{
				$body=BODY_ORDER.'<h1>'.$basket_order_id.'</h1><br><h3>Contact Details</h3><br><table><tr><td>'.$seller_business_name.'</td></tr><tr><td>'.$seller_alternate_number.'</td></tr><tr><td>Click here to continue shopping</td><td>'.DOMAIN.'/app/?s='.$username.'</td></tr></table>';
				$body1=BODY_ORDER.$basket_order_id.'   '.$seller_business_name.'   '.$seller_alternate_number.'Click here to continue shopping  '.DOMAIN.'/app/?s='.$username;
				$seller_body=SELLER_BODY_ORDER.'<h1>'.$basket_order_id.'  '.'</h1><br><h3> from </h3><br><table><tr><td>'.$customer_name.'</td></tr><tr>'.$customer_mobile.'</td></tr><tr><td></tr></table>';
				$seller_body1=SELLER_BODY_ORDER.$basket_order_id.' from  '.$customer_name.' '.$customer_mobile;

				if($notification_email=='1'&&$email_verified=='1'&&($seller_email!=''||$seller_email!=NULL))
				{
					sendMail($seller_email,SUBJECT_ORDER,$seller_body);
				}
				if($alternate_contact_verified=='Yes'&&$notification_whatsapp=='1'&&($seller_alternate_number!=''||$seller_alternate_number!=NULL))
				{
					
				}
				if($alternate_contact_verified=='Yes'&&$notification_sms=='1'&&($seller_alternate_number!=''||$seller_alternate_number!=NULL))
				{
					sendMessage($seller_alternate_number,$seller_body1);

				}
				if($customer_email!=''||$customer_email!=NULL)
				{
					sendMail($customer_email,SUBJECT_ORDER,$body1);
				}
				if($customer_mobile!=''||$customer_mobile!=NULL)
				{
					
					sendMessage($customer_mobile,$body1);
					//sendWhatsappMessage($customer_mobile,$data1);
				}
				commit();
				$temp=array();
				$temp['order_type']='COD';
				$temp['orderId']=$basket_order_id;
				$temp['response_code']=200;			
				$temp['response_desc']="Success";
				echo json_encode(array("checkout"=>$temp));
				close();
				exit();
			}
			else
			{
				rollback();
				$temp=array();
				$temp['order_type']='COD';
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("checkout"=>$temp));
				close();
				exit();
			}	
	 }						
											
 }
else
{
	$temp=array();
	$temp['response_code']=400;			
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("checkout"=>$temp));
	close();
	exit();
}
close();
	
?>
