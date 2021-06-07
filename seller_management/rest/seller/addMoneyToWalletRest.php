<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
require('getSellerWalletBalanceRest.php');
require('getPaymentIntegrationRest.php');
$flag = true;
$connection->autocommit(FALSE);

 $orderId = $_REQUEST["orderId"];
 $orderAmount = $_REQUEST["orderAmount"];
 $referenceId = $_REQUEST["referenceId"];
 $txStatus = $_REQUEST["txStatus"];
 $paymentMode = $_REQUEST["paymentMode"];
 $txMsg = $_REQUEST["txMsg"];
 $txTime = $_REQUEST["txTime"];
 $signature = $_REQUEST["signature"];
 $secretkey = SECREATKEY;
 $data = $orderId.$orderAmount.$referenceId.$txStatus.$paymentMode.$txMsg.$txTime;
 $hash_hmac = hash_hmac('sha256', $data, $secretkey, true) ;

 $computedSignature = base64_encode($hash_hmac);
//echo $signature."  ".$computedSignature;
 //$txStatus=="SUCCESS"
 //$signature==$computedSignature
$query="SELECT
		    seller_id,
		    wallet_opening_balance,
		    order_id,
		    order_status,
		    created_date_time
		FROM
		    wallet_order
		WHERE
		    order_id = '".$orderId."'
		";
		//echo $query;
$query=query($query);
$row=fetch_array($query);
 if($row['order_status']=='Completed')
{
	$temp=array();
	$temp['response_code']=404;
	$temp['response_desc']="Invalid Operation";
	echo json_encode(array("addwallet"=>$temp));
	close();
	exit();
}
if($row['order_status']=='Draft')
{
	if ($txStatus=="SUCCESS") 
	{
	    if($signature==$computedSignature)
	    {	
	    	
			
			$user_id=$row['seller_id'];
			$seller_email='';
			$email_verified='';
			$notification_whatsapp='';
			$notification_sms='';
			$notification_email='';
			$alternate_contact_verified='';
			$seller_alternate_number='';
			$order_date=date("Y-m-d",strtotime($row['created_date_time']));
			$query="SELECT
						status,
					    logistics_integrated,
					    kyc_completed,
					    waive_platform_fees,
					    accept_online_payments,
					    seller_email,
					    email_verified,
					    notification_email,
					    notification_sms,
					    notification_whatsapp,
					    alternate_contact_verified,
					    seller_alternate_number
					FROM
					    users,
					    seller_details
					WHERE
					    user_id = '".$user_id."' 
						AND 
					    users.user_id = seller_details.seller_id
					";

			//echo $query;
			$query=query($query);
			confirm($query);
			$seller_email='';
			if(mysqli_num_rows($query)!=0)
			{
				$row=fetch_array($query);
				//print_r($row);
				$seller_email=$row['seller_email'];
				$email_verified=$row['email_verified'];
				$notification_whatsapp=$row['notification_whatsapp'];
				$notification_sms=$row['notification_sms'];
				$notification_email=$row['notification_email'];
				$alternate_contact_verified=$row['alternate_contact_verified'];
				$seller_alternate_number=$row['seller_alternate_number'];

				if($row['status']=="A")
				{
					if($row['accept_online_payments']=='1')
					{
					

						$walletbalance = new Wallet();
						$walletbalance->getWalletDetails($user_id);
						$valuedate =$walletbalance->value_date;
						$openingbalance=$walletbalance->opening_balance;
						$closingbalance=$walletbalance->closing_balance;
						//$openingbalance=0;
						//$closingbalance=0;

						//echo $openingbalance."\n".$closingbalance."\n";
						$newclosingbalance=round($closingbalance+$orderAmount,2);
						$paymentReference=$referenceId;
						//$newclosingbalance=$closingbalance-$net_platform_fees;
						//$flag=true;
						//;
						if($orderAmount>0)
						{
							$cashmovementid=$user_id+date("YmdHis").rand(100,1000);
							$cashMovement = new CashMovement();
							$cashMovement->cash_movement_id 			= $cashmovementid;
							$cashMovement->linked_movement 				= $cashmovementid;
							$cashMovement->order_id 					= $orderId;
							$cashMovement->seller_id 					= $user_id;
							$cashMovement->entry_side 					= 'seller';
							$cashMovement->opening_balance 				= $closingbalance;
							$cashMovement->amount 						= $orderAmount;
							$cashMovement->amount_currency 				='INR';	
							$cashMovement->dr_cr_indicator 				= 'C';
							$cashMovement->closing_balance 				= $newclosingbalance;
							$cashMovement->movement_type 				= '5'; //Wallet Recharge
							$cashMovement->settled_amount 				= $orderAmount;
							$cashMovement->payment_reference 			= $paymentReference;
							$cashMovement->movement_status 				= '2'; //Posted
							$cashMovement->created_date_time 			= 'NOW()';
							$cashMovement->last_modification_datetime 	= 'NOW()';
							$cashMovement->movement_date 				= 'CURDATE()';
						    $cashMovement->service_charge 				= '0.00';
						    $cashMovement->service_tax 					= '0.00';
							$cashMovement->order_date 					= $order_date;
							$cashMovement->value_date 					= date('Y-m-d',strtotime($txTime));
							$cashMovement->movement_description = 'Wallet Recharge Credit';
							$result=$cashMovement->insertCashMovementSellerSide();
						
							if(!$result)
							{
								$flag = false;
								rollback();
								$temp=array();
								$temp['response_code']=404;
								$temp['response_desc']="Invalid Operation";
								echo json_encode(array("addwallet"=>$temp));
								close();
								exit();
							}
													//echo $flag;
								
							$cashmovementid1				= $user_id+date("YmdHis").rand(100,1000);
							$cashMovement->cash_movement_id = $cashmovementid1;
							$cashMovement->entry_side 		= 'offset';
							$cashMovement->opening_balance  = $closingbalance;
							$cashMovement->amount 			= round($orderAmount*(-1),2);	
							$cashMovement->dr_cr_indicator 	= 'D';
							$cashMovement->closing_balance 	= $newclosingbalance;
							$cashMovement->movement_type 	= '5'; // Wallet Recharge
							$cashMovement->movement_status 	= '2'; //Posted
							$result=$cashMovement->insertCashMovementOffsetSide();
							
							if (!$result) {
								$flag=false;
								rollback();
								$temp=array();
								$temp['response_code']=404;
								$temp['response_desc']="Invalid Operation";
								echo json_encode(array("addwallet"=>$temp));
								close();
								exit();
							}
												
							$result=$walletbalance->upadteWalletDetails($user_id,$openingbalance,$newclosingbalance,$closingbalance,$valuedate);
							//echo $result;

							if(!$result)
							{
								$flag=false;
							}
							//echo $result."md\n";
							
							$query="UPDATE
									    wallet_order
									SET
									    wallet_closing_balance = '".$newclosingbalance."',
									    payment_reference = '".$paymentReference."',
									    order_status = 'Completed',
									    gateway_response_status = '".$txStatus."',
									    payment_date_time = '".$txTime."'
									WHERE
									    order_id = '".$orderId."'
    								";
							//echo $query;
							$query=query($query);
							$result=confirm($query);
							//echo $result;
							//echo $flag;
							if(!$result)
							{
								$flag=false;
								rollback();
								$temp=array();
								$temp['response_code']=404;
								$temp['response_desc']="Invalid Operation";
								echo json_encode(array("addwallet"=>$temp));
								close();
								exit();
							}
							if($flag)
							{
								$body=BODY_MONEYADDED.$orderAmount.' on '.date('Y-M-d',strtotime($txTime)).'<h1>. Available wallet balance INR '.$newclosingbalance.'</h1>';
								if($email_verified=='1'&&($seller_email!=''||$seller_email!='null'))
								{
									sendMail($seller_email,SUBJECT_MONEYADDED,$body);
								}
								if($alternate_contact_verified=='Yes'&&($seller_alternate_number!=''||$seller_alternate_number!=NULL))
								{
									//send message to whatsapp number
								}
								if($alternate_contact_verified=='Yes'&&($seller_alternate_number!=''||$seller_alternate_number!=NULL))
								{
									$data1=strip_tags($body);
									sendMessage($seller_alternate_number,$data1);
								}
								
								commit();
								$temp=array();
								$temp['response_code']=200;
								$temp['response_desc']="Sucess";
								echo json_encode(array("addwallet"=>$temp));
								close();
								exit();
							}
							else
							{
								rollback();
								$temp['response_code']=404;
								$temp['response_desc']="Invalid Operation";
								echo json_encode(array("addwallet"=>$temp));
								close();
								exit();
							}
						}
					}
				}			

	    	}
	//print_r($_REQUEST);
		}
	}
	if ($txStatus=='PENDING') 
	{
	// Reject this call
		$query="
				UPDATE
				    wallet_order
				SET
				    
				    payment_reference = '".$paymentReference."',
				    order_status = 'Completed',
				    gateway_response_status = '".$txStatus."',
				    payment_date_time = '".$txTime."'
				WHERE
				    order_id = '".$orderId."'
				";
		//echo $query;
		$query=query($query);
		$result=confirm($query);
		if(!$result)
		{
			$flag=false;
		}
		if($flag)
		{
			commit();
			$temp=array();
			$temp['response_code']=405;
			$temp['response_desc']=$txStatus;
			echo json_encode(array("addwallet"=>$temp));
			close();
			exit();
		}
		else
		{
			rollback();
			$temp=arry();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("addwallet"=>$temp));
			close();
			exit();
		}
	    	
	 }
	 if ($txStatus=='FAILED') 
	{
	// Reject this call
		$query="UPDATE
				    wallet_order
				SET
				    
				    payment_reference = '".$paymentReference."',
				    order_status = 'Completed',
				    gateway_response_status = '".$txStatus."',
				    payment_date_time = '".$txTime."'
				WHERE
				    order_id = '".$orderId."'
			    ";
		//echo $query;
		$query=query($query);
		$result=confirm($query);
		if(!$result)
		{
			$flag=false;
		}
		if($flag)
		{
			commit();
			$temp=array();
			$temp['response_code']=405;
			$temp['response_desc']=$txStatus;
			echo json_encode(array("addwallet"=>$temp));
			close();
			exit();
		}
		else
		{
			rollback();
			$temp=arry();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("addwallet"=>$temp));
			close();
			exit();
		}
	    	
	 }
	 if ($txStatus=='CANCELLED') 
	{
	// Reject this call
		$query="
				UPDATE
				    wallet_order
				SET
				    
				    payment_reference = '".$paymentReference."',
				    order_status = 'Completed',
				    gateway_response_status = '".$txStatus."',
				    payment_date_time = '".$txTime."'
				WHERE
				    order_id = '".$orderId."'
				";
		//echo $query;
		$query=query($query);
		$result=confirm($query);
		if(!$result)
		{
			$flag=false;
		}
		if($flag)
		{
			commit();
			$temp=array();
			$temp['response_code']=405;
			$temp['response_desc']=$txStatus;
			echo json_encode(array("addwallet"=>$temp));
			close();
			exit();
		}
		else
		{
			rollback();
			$temp=arry();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("addwallet"=>$temp));
			close();
			exit();
		}
	    	
	 }
	 if ($txStatus=='FLAGGED') 
	{
	// Reject this call
		$query="
				UPDATE
				    wallet_order
				SET
				   
				    payment_reference = '".$paymentReference."',
				    order_status = 'Completed',
				    gateway_response_status = '".$txStatus."',
				    payment_date_time = '".$txTime."'
				WHERE
				    order_id = '".$orderId."'
				";
		//echo $query;
		$query=query($query);
		$result=confirm($query);
		if(!$result)
		{
			$flag=false;
		}
		if($flag)
		{
			commit();
			$temp=array();
			$temp['response_code']=405;
			$temp['response_desc']=$txStatus;
			echo json_encode(array("addwallet"=>$temp));
			close();
			exit();
		}
		else
		{
			rollback();
			$temp=arry();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("addwallet"=>$temp));
			close();
			exit();
		}
	    	
	 }
	 else
	 {
	 }
} 


close();	
?>
