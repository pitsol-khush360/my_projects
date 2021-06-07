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
// echo "Mahesh";
//echo $signature;
 $computedSignature = base64_encode($hash_hmac);
//echo $signature."  ".$computedSignature;
 if ($txStatus=='SUCCESS') 
 {
    if($signature==$computedSignature)
    {	

    	$query ="
    			SELECT
				    order_status,
				    order_type,
				    seller_id,
				    order_date,
				    customer_email,
				    customer_mobile,
				    customer_name
				FROM
				    basket_order
				WHERE
				    basket_order_id = '".$orderId."'
				";
		//echo $query;
		$query=query($query);
		$result=confirm($query);
		$row=fetch_array($query);
		if($row['order_status']=='Pending')
		{
			$temp=array();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("checkout"=>$temp));
			close();
			exit();
		}
		if($row['order_status']=='Draft')
		{
	    	$query="
	    			UPDATE
					    basket_order
					SET
					    payment_reference = '".$referenceId."',
					    order_status = 'Pending',
					    payment_method = '".$paymentMode."',
					    amount_received = '".$orderAmount."',
					    payment_gateway_status = '".$txStatus."',
					    payment_transaction_time = '".$txTime."'
					WHERE
					    basket_order_id = '".$orderId."'
	    			";
	    	//echo $query;
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
			//echo $flag."\n";
			
			//print_r($row);
			$user_id=$row['seller_id'];
			$customer_email=$row['customer_email'];
			$customer_name=$row['customer_name'];
			$customer_mobile=$row['customer_mobile'];
			$order_type=$row['order_type'];
			$seller_email='';
			$seller_business_name='';
			$seller_alternate_number='';
			$username='';
			$order_date=date('Y-m-d',strtotime($row['order_date']));
			$query="SELECT
					    seller_alternate_number,
					    alternate_contact_verified,
					    seller_email,
						status,
					    logistics_integrated,
					    kyc_completed,
					    waive_platform_fees,
					    accept_online_payments,
					    email_verified,
					    notification_email,
					    notification_sms,
					    notification_whatsapp,
					    seller_business_name,
					    username
					FROM
					    users,
					    seller_details
					WHERE
					    user_id = '".$user_id."' 
					    AND 
					    users.user_id = seller_details.seller_id";

			//echo $query;
			$query=query($query);
			confirm($query);

			if(mysqli_num_rows($query)==0)
			{
				rollback();
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
			$seller_business_name=$row['seller_business_name'];
			$alternate_contact_verified=$row['alternate_contact_verified'];
			$username=$row['username'];
			if($row['status']!="A")
			{
					rollback();
					$temp=array();
					$temp['response_code']=405;
					$temp['response_desc']="Please Contact CustomerCare to Activate Your Account";
					echo json_encode(array("checkout"=>$temp));
					close();
					exit();
			}
			if($order_type=='Prepaid')
			{
				if($row['kyc_completed']!='1')
				{
					rollback();
					$temp=array();
					$temp['response_code']=405;
					$temp['response_desc']="Please Complete KYC";
					echo json_encode(array("checkout"=>$temp));
					close();
					exit();
				}
				if($row['accept_online_payments']!='1')
				{
					rollback();
					$temp=array();
					$temp['response_code']=405;
					$temp['response_desc']="Please Accept Online Payments";
					echo json_encode(array("checkout"=>$temp));
					close();
					exit();
				}
							
				
				$newclosingbalance=0;
				$paymentReference=$referenceId;
				//$newclosingbalance=$closingbalance;
			//$flag=true;
			//;
				if($orderAmount>0)
				{
					$cashmovementid=$user_id+date("YmdHis").rand(100,1000);
					$cashMovement = new CashMovement();
					$cashMovement->cash_movement_id 				= $cashmovementid;
					$cashMovement->linked_movement 					= $cashmovementid;
					$cashMovement->order_id 						= $orderId;
					$cashMovement->seller_id 						= $user_id;
					$cashMovement->entry_side 						= 'seller';
					$cashMovement->opening_balance 					= '0.00';
					$cashMovement->amount 							= $orderAmount;
					$cashMovement->amount_currency 					= 'INR';	
					$cashMovement->dr_cr_indicator 					= 'C';
					$cashMovement->closing_balance 					= '0.00';
					$cashMovement->movement_type 					= '1';   // NET AMOUNT
					$cashMovement->settled_amount 					= '0.00';
					$cashMovement->payment_reference 				= $paymentReference;
					$cashMovement->movement_status 					= '1';   // Generated  (Pending)
					$cashMovement->created_date_time 				= 'NOW()';
					$cashMovement->last_modification_datetime 		= 'NOW()';
					$cashMovement->movement_date 					= 'CURDATE()';
				    $cashMovement->service_charge 					= '0.00';
				    $cashMovement->service_tax 						= '0.00';
					$cashMovement->order_date 						= $order_date;
					$cashMovement->value_date 						= date('Y-m-d',strtotime($txTime));
					$cashMovement->movement_description 			= '';
					$result=$cashMovement->insertCashMovementSellerSide();
					
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
						
					$cashmovementid1				= $user_id+date("YmdHis").rand(100,1000);
					$cashMovement->cash_movement_id = $cashmovementid1;
					$cashMovement->entry_side 		= 'offset';
					$cashMovement->opening_balance  = '0.00';
					$cashMovement->amount 			= $orderAmount*(-1);	
					$cashMovement->dr_cr_indicator  = 'D';
					$cashMovement->closing_balance  = '0.00';
					$cashMovement->movement_type    = '1'; // Net Amount
					$cashMovement->movement_status  = '1'; // Generated  (Pending)

					$result=$cashMovement->insertCashMovementOffsetSide();

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
						
					$query=query("
									SELECT
									    comission_percentage,
									    tax_on_commission
									FROM
									    commission_charges
									WHERE
									    comission_type = 'GATEWAY_CHARGES'
								");
					$row=fetch_array($query);
					$comission_percentage=round($row['comission_percentage'],2);
					$tax_on_commission=round($row['tax_on_commission'],2);
					//echo $query;
					$gross_platform_fees;
					$net_platform_fees=0;
					if(mysqli_num_rows($query)!=0)
					{
						$gross_platform_fees = round(($comission_percentage*$orderAmount)/100,2);
						$net_platform_fees = round($gross_platform_fees + ($gross_platform_fees  * $tax_on_commission)/100,2);
					}
					
					$cashmovementid=$user_id+date("YmdHis").rand(100,1000);

					$cashMovement->cash_movement_id 		= $cashmovementid;
					$cashMovement->linked_movement 			= $cashmovementid;
					$cashMovement->entry_side 				= 'seller';
					$cashMovement->opening_balance 			= '0.00';
					$cashMovement->amount 					= $net_platform_fees*(-1);	
					$cashMovement->dr_cr_indicator 			= 'D';
					$cashMovement->closing_balance 			= '0.00';
					$cashMovement->movement_type 			= '4'; //Ready for Settlement
					$cashMovement->movement_status 			= '1'; //Generated  (Pending)
					$cashMovement->value_date 				= date('Y-m-d',strtotime($txTime));
					$cashMovement->movement_description 	= 'Gateway Charges';

					$result=$cashMovement->insertCashMovementSellerSide();
					
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
										
					$cashmovementid1=$user_id+date("YmdHis").rand(100,1000);
					$cashMovement->cash_movement_id = $cashmovementid1;
					$cashMovement->entry_side = 'offset';
					$cashMovement->opening_balance = '0.00';
					$cashMovement->amount = $net_platform_fees;	
					$cashMovement->dr_cr_indicator = 'C';
					$cashMovement->closing_balance = '0.00';
					$cashMovement->movement_type = '4';  //Ready For Settlement
					$cashMovement->movement_status = '1'; //Generated (Pending)
					$result=$cashMovement->insertCashMovementOffsetSide();
					
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
						//echo $flag."\n";
					
					if($flag)
					{
						//$body=BODY_ORDER.'<h1>'.$_REQUEST['order_id'].'</h1>';
						$body=BODY_ORDER.'<h1>'.$_REQUEST['orderId'].'</h1><br><table><tr><td>'.$seller_business_name.'</td></tr><tr><td>'.$seller_alternate_number.'</td></tr><tr><td>Click here to continue shopping</td><td>'.DOMAIN.'/app/?s='.$username.'</td></tr></table>';
						$body1=BODY_ORDER.$_REQUEST['orderId'].'   '.$seller_business_name.'   '.$seller_alternate_number.' Click here to continue shopping  '.DOMAIN.'/app/?s='.$username;
						$seller_body=SELLER_BODY_ORDER.'<h1>'.$_REQUEST['orderId'].'  '.'</h1><br><h3> from </h3><br><table><tr><td>'.$customer_name.'</td></tr><tr>'.$customer_mobile.'</td></tr><tr><td></tr></table>';
						$seller_body1=SELLER_BODY_ORDER.$_REQUEST['orderId'].' from  '.$customer_name.' '.$customer_mobile;

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
							sendMail($customer_email,SUBJECT_ORDER,$body);
						}
						if($customer_mobile!=''||$customer_mobile!=NULL)
						{
							
							sendMessage($customer_mobile,$body1);
							//sendWhatsappMessage($customer_mobile,$data1);
						}
						commit();
						$temp=array();
						$temp['response_code']=200;
						$temp['response_desc']="Sucess";
						echo json_encode(array("checkout"=>$temp));
					}
					else
					{
						rollback();
						$temp=array();
						$temp['response_code']=404;
						$temp['response_desc']="Invalid Operation";
						echo json_encode(array("checkout"=>$temp));
						close();
						exit();
					}
					
				}	
				else
				{	
					$temp=array();
					$temp['response_code']=405;
					$temp['response_desc']="Please Enter Amount greater then Zero";
					echo json_encode(array("checkout"=>$temp));
				}						
			}
    	}
    }
    //print_r($_REQUEST);
}
 
if ($txStatus=='PENDING') 
{
// Reject this call
	$query="
			UPDATE
			    basket_order
			SET
			    payment_reference = '".$referenceId."',
			    payment_gateway_status = '".$txStatus."',
			    payment_method = '".$paymentMode."',
			    amount_received = '".$orderAmount."',
			    payment_transaction_time = '".$txTime."'
			WHERE
			    basket_order_id = '".$orderId."'
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
		echo json_encode(array("checkout"=>$temp));
		close();
		exit();
	}
	else
	{
		rollback();
		$temp=arry();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("checkout"=>$temp));
		close();
		exit();
	}
    	
 }
 if ($txStatus=='FAILED') 
{
// Reject this call
	$query="UPDATE
			    basket_order
			SET
			    payment_reference = '".$referenceId."',
			    payment_gateway_status = '".$txStatus."',
			    payment_method = '".$paymentMode."',
			    amount_received = '".$orderAmount."',
			    payment_transaction_time = '".$txTime."'
			WHERE
			    basket_order_id = '".$orderId."'
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
		echo json_encode(array("checkout"=>$temp));
		close();
		exit();
	}
	else
	{
		rollback();
		$temp=arry();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("checkout"=>$temp));
		close();
		exit();
	}
    	
 }
 if ($txStatus=='CANCELLED') 
{
// Reject this call
	$query="
			UPDATE
			    basket_order
			SET
			    payment_reference = '".$referenceId."',
			    payment_gateway_status = '".$txStatus."',
			    payment_method = '".$paymentMode."',
			    amount_received = '".$orderAmount."',
			    payment_transaction_time = '".$txTime."'
			WHERE
			    basket_order_id = '".$orderId."'
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
		echo json_encode(array("checkout"=>$temp));
		close();
		exit();
	}
	else
	{
		rollback();
		$temp=arry();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("checkout"=>$temp));
		close();
		exit();
	}
    	
 }
 if ($txStatus=='FLAGGED') 
{
// Reject this call
	$query="
			UPDATE
			    basket_order
			SET
			    payment_reference = '".$referenceId."',
			    payment_gateway_status = '".$txStatus."',
			    payment_method = '".$paymentMode."',
			    amount_received = '".$orderAmount."',
			    payment_transaction_time = '".$txTime."'
			WHERE
			    basket_order_id = '".$orderId."'
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
		echo json_encode(array("checkout"=>$temp));
		close();
		exit();
	}
	else
	{
		rollback();
		$temp=arry();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("checkout"=>$temp));
		close();
		exit();
	}
    	
 }
 else
 {
 }
close();	
?>
