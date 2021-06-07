<?php
require('getSellerWalletBalanceRest.php');
require('getPaymentIntegrationRest.php');
$flag = true;
if(isset($_REQUEST['order_status'])&&$_REQUEST['order_status']!=''&&isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='')
{	

	$query="SELECT
				order_type,
			    order_status,
			    order_date,
			    customer_email,
			    net_amount,
			    payment_reference,
			    customer_mobile
			FROM
			    basket_order
			WHERE
			    basket_order_id = '".$_REQUEST['order_id']."' 
			    AND 
			    seller_id = '".$_REQUEST['user_id']."'
			";
	//echo $query;
	$query=query($query);
	if(!$query)
	{
		error_log(error(),0);
		$temp=array();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("changestatustoshipped"=>$temp));
		close();
		exit();
	}
	$row=fetch_array($query);
	$orderType=$row['order_type'];
	$customer_email=$row['customer_email'];
	$customer_mobile=$row['customer_mobile'];
	$paymentReference=$row['payment_reference'];
	$seller_email='';
	$seller_alternate_number='';
	$seller_business_name='';
	$email_verified='';
	$notification_whatsapp='';
	$notification_sms='';
	$notification_email='';
	$alternate_contact_verified='';
	$username='';
	$net_amount = round($row['net_amount'],2);
	$order_date=date('Y-m-d',strtotime($row['order_date']));
	if($row['order_status']=='Accepted'&&$_REQUEST['order_status']=='Shipped')
	{
		
		$query=query("
					SELECT
						U.status,
					    SD.logistics_integrated,
					    SD.kyc_completed,
					    SD.seller_email,
					    SD.seller_alternate_number,
					    SD.seller_business_name,
					    SD.email_verified,
					    SD.notification_email,
					    SD.notification_sms,
					    SD.notification_whatsapp,
					    SD.alternate_contact_verified,
					    U.username
					FROM
					    users U,
					    seller_details SD
					WHERE
					    U.user_id = '".$_REQUEST['user_id']."' 
					    AND 
					    U.user_id = SD.seller_id
					");
		if(!$query)
		{
			error_log(error(),0);
			$temp=array();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("changestatustoshipped"=>$temp));
			close();
			exit();
		}
		if(mysqli_num_rows($query)==0)
		{
			$temp=array();
			$temp['response_code']=405;
			$temp['response_desc']="No records Found";
			echo json_encode(array("changestatustoshipped"=>$temp));
			close();
			exit();
		}
		$row=fetch_array($query);
		$seller_email=$row['seller_email'];
		$seller_alternate_number=$row['seller_alternate_number'];
		$seller_business_name=$row['seller_business_name'];
		$email_verified=$row['email_verified'];
		$notification_whatsapp=$row['notification_whatsapp'];
		$notification_sms=$row['notification_sms'];
		$notification_email=$row['notification_email'];
		$alternate_contact_verified=$row['alternate_contact_verified'];
		$username=$row['username'];
		if($row['status']!="A")
		{
			$temp=array();
			$temp['response_code']=405;
			$temp['response_desc']="This User Account Is Not Active. Please Contact Customer Care";
			echo json_encode(array("changestatustoshipped"=>$temp));
			close();
			exit();
		}
		if($orderType=='COD')
		{
		
			$query="UPDATE
					    basket_order
					SET
					    order_status = '".$_REQUEST['order_status']."'
					WHERE
					    basket_order_id = '".$_REQUEST['order_id']."' 
					    AND 
					    seller_id = '".$_REQUEST['user_id']."'
					";
			$query=query($query);
			$result=confirm($query);
			if(!$query)
			{
				error_log(error(),0);
				$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("changestatustoshipped"=>$temp));
				close();
				exit();
			}
			if(!$result)
			{
				error_log(error(),0);
				$flag=false;
				rollback();
				$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("changestatustoshipped"=>$temp));
				close();
				exit();
			}

			if($flag)
			{
				$body='Your Order- <h1>'.$_REQUEST['order_id'].'</h1> has been Shipped. <br><table><tr><td>'.$seller_business_name.'</td></tr><tr><td>'.$seller_alternate_number.'</td></tr><tr><td>Click here to continue shopping</td></tr><tr><td>'.DOMAIN.'/app/?s='.$username.'</td></tr></table>';
				$body1='Your Order-'.$_REQUEST['order_id'].' has been Shipped.  '.$seller_business_name.'  '.$seller_alternate_number.' Click here to continue shopping   '.DOMAIN.'/app/?s='.$username;
				// if($notification_email=='1'&&$email_verified=='1'&&($seller_email!=''||$seller_email!=NULL))
				// {
				// 	sendMail($seller_email,SUBJECT_SHIPPED,$body);
				// }
				// if($alternate_contact_verified=='Yes'&&$notification_whatsapp=='1'&&($seller_alternate_number!=''||$seller_alternate_number!=NULL))
				// {
				// 	//send message to whatsapp number
				// }
				// if($alternate_contact_verified=='Yes'&&$notification_sms=='1'&&($seller_alternate_number!=''||$seller_alternate_number!=NULL))
				// {
				// 	$data1=strip_tags($body);
				// 	sendMessage($seller_alternate_number,$data1);
				// }
				if($customer_email!=''||$customer_email!=NULL)
				{
					sendMail($customer_email,SUBJECT_SHIPPED,$body);
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
				echo json_encode(array("changestatustoshipped"=>$temp));
				close();
				exit();
			}
			else
			{
				rollback();
				$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("changestatustoshipped"=>$temp));
				close();
				exit();
			}
		}
		if($orderType=='Prepaid')
		{
			if($row['logistics_integrated']=='Yes')
			{
				if($row['kyc_completed']!='1')
				{
					$temp=array();
					$temp['response_code']=405;
					$temp['response_desc']="Seller KYC is not complete but logistics is integrated. Turn off logistics integration to proceed";
					echo json_encode(array("changestatustoshipped"=>$temp));
					close();
					exit();
				}
				$query=query("
							SELECT
							    id,
							    shipping_amount,
							    seller_id
							FROM
							    shipping_charges
							WHERE
							    seller_id = '".$_REQUEST['user_id']."'
							");
				confirm($query);
				$rows=mysqli_num_rows($query);
				$shippingAmount = 0;
				$row=fetch_array($query);
				if($rows>0 )
				{
					$shippingAmount = $row['shipping_amount'];
				}
				if($net_amount>0)
				{
				
					$row=fetch_array($query);
					$walletbalance = new Wallet();
					$walletbalance->getWalletDetails($_REQUEST['user_id']);
					$valuedate =$walletbalance->value_date;
					$openingbalance=$walletbalance->opening_balance;
					$closingbalance=$walletbalance->closing_balance;
					$newclosingbalance=0;
					$newclosingbalance=round($closingbalance-round($shippingAmount,2),2);
					$cashmovementid=$_REQUEST['user_id']+date("YmdHis").rand(100,1000);
					$cashMovement = new CashMovement();
					$cashMovement->cash_movement_id 			= $cashmovementid;
					$cashMovement->linked_movement 				= $cashmovementid;
					$cashMovement->order_id 					= $_REQUEST['order_id'];
					$cashMovement->seller_id 					= $_REQUEST['user_id'];
					$cashMovement->entry_side 					= 'seller';
					$cashMovement->opening_balance 				= $closingbalance;
					$cashMovement->amount 						= round($shippingAmount*(-1),2);
					$cashMovement->amount_currency 				='INR';	
					$cashMovement->dr_cr_indicator 				= 'D';
					$cashMovement->closing_balance 				= $newclosingbalance;
					$cashMovement->movement_type 				= '2';  //Delivery Charges
					$cashMovement->settled_amount 				= round($shippingAmount,2);
					$cashMovement->payment_reference 			= $paymentReference;
					$cashMovement->movement_status 				= '2';  //Posted
					$cashMovement->created_date_time 			= 'NOW()';
					$cashMovement->last_modification_datetime 	= 'NOW()';
					$cashMovement->movement_date 				= 'CURDATE()';
				    $cashMovement->service_charge 				= '0.00';
				    $cashMovement->service_tax 					= '0.00';
					$cashMovement->order_date 					= $order_date;
					$cashMovement->value_date 					= 'CURDATE()';
					$cashMovement->movement_description 		= 'Delivery Charges';

					$result=$cashMovement->insertCashMovementSellerSide();
					
					
					if(!$result)
					{
						$flag = false;
						rollback();
						$temp['response_code']=404;
						$temp['response_desc']="Invalid Operation";
						echo json_encode(array("changestatustoshipped"=>$temp));
						close();
						exit();
					}
						
					$cashmovementid1				= $_REQUEST['user_id']+date("YmdHis").rand(100,1000);
					$cashMovement->cash_movement_id = $cashmovementid1;
					$cashMovement->entry_side 		= 'offset';
					$cashMovement->opening_balance 	= $closingbalance;
					$cashMovement->amount 			= round($shippingAmount,2);	
					$cashMovement->dr_cr_indicator 	= 'C';
					$cashMovement->closing_balance 	= $newclosingbalance;
					$cashMovement->movement_type 	= '2';  //Delivery Charges
					$cashMovement->movement_status 	= '2';  //Posted

					$result=$cashMovement->insertCashMovementOffsetSide();
					
					if(!$result)
					{
						$flag = false;
						rollback();
						$temp['response_code']=404;
						$temp['response_desc']="Invalid Operation";
						echo json_encode(array("changestatustoshipped"=>$temp));
						close();
						exit();
					}
					$openingbalance=round($closingbalance,2);
					$closingbalance=$newclosingbalance;
					$result=$walletbalance->upadteWalletDetails($_REQUEST['user_id'],$openingbalance,$closingbalance,$valuedate);
					if(!$result)
					{
						$flag=false;
						rollback();
						$temp['response_code']=404;
						$temp['response_desc']="Invalid Operation";
						echo json_encode(array("changestatustoshipped"=>$temp));
						close();
						exit();
					}
					$query="UPDATE
							    basket_order
							SET
							    order_status = '".$_REQUEST['order_status']."'
							WHERE
							    basket_order_id = '".$_REQUEST['order_id']."' 
							    AND 
							    seller_id = '".$_REQUEST['user_id']."'
							";
					$query=query($query);
					$result=confirm($query);
					if(!$result)
					{
						$flag=false;
						rollback();
						$temp['response_code']=404;
						$temp['response_desc']="Invalid Operation";
						echo json_encode(array("changestatustoshipped"=>$temp));
						close();
						exit();
					}

					if($flag)
					{
						$orderId=$_REQUEST['order_id'];
						$body='Your Order- <h1>'.$_REQUEST['order_id'].'</h1> has been Shipped. <br><table><tr><td>'.$seller_business_name.'</td></tr><tr><td>'.$seller_alternate_number.'</td></tr><tr><td>Click here to continue shopping</td></tr><tr><td>'.DOMAIN.'/app/?s='.$username.'</td></tr></table>';
						$body1='Your Order-'.$_REQUEST['order_id'].' has been Shipped.  '.$seller_business_name.'  '.$seller_alternate_number.' Click here to continue shopping   '.DOMAIN.'/app/?s='.$username;
						// if($notification_email=='1'&&$email_verified=='1'&&($seller_email!=''||$seller_email!=NULL))
						// {
						// 	sendMail($seller_email,SUBJECT_SHIPPED,$body);
						// }
						// if($alternate_contact_verified=='Yes'&&$notification_whatsapp=='1'&&($seller_alternate_number!=''||$seller_alternate_number!=NULL))
						// {
							
						// }
						// if($alternate_contact_verified=='Yes'&&$notification_sms=='1'&&($seller_alternate_number!=''||$seller_alternate_number!=NULL))
						// {
						// 	sendMessage($seller_alternate_number,$body1);
						// }
						if($customer_email!=''||$customer_email!=NULL)
						{
							sendMail($customer_email,SUBJECT_SHIPPED,$body);
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
						echo json_encode(array("changestatustoshipped"=>$temp));
						close();
						exit();
					}
					else
					{
						rollback();
						$temp['response_code']=404;
						$temp['response_desc']="Invalid Operation";
						echo json_encode(array("changestatustoshipped"=>$temp));
						close();
						exit();
					}
				}
			}
			else
			{
				$query="UPDATE
					    basket_order
					SET
					    order_status = '".$_REQUEST['order_status']."'
					WHERE
					    basket_order_id = '".$_REQUEST['order_id']."' 
					    AND 
					    seller_id = '".$_REQUEST['user_id']."'
					";
				$query=query($query);
				$result=confirm($query);
				if(!$result)
				{
					$flag=false;
					rollback();
					$temp=array();
					$temp['response_code']=404;
					$temp['response_desc']="Invalid Operation";
					echo json_encode(array("changestatustoshipped"=>$temp));
					close();
					exit();
				}

				if($flag)
				{
					$body='Your Order- <h1>'.$_REQUEST['order_id'].'</h1> has been Shipped. <br><table><tr><td>'.$seller_business_name.'</td></tr><tr><td>'.$seller_alternate_number.'</td></tr><tr><td>Click here to continue shopping</td></tr><tr><td>'.DOMAIN.'/app/?s='.$username.'</td></tr></table>';
					$body1='Your Order-'.$_REQUEST['order_id'].' has been Shipped.  '.$seller_business_name.'  '.$seller_alternate_number.' Click here to continue shopping   '.DOMAIN.'/app/?s='.$username;
					// if($notification_email=='1'&&$email_verified=='1'&&($seller_email!=''||$seller_email!=NULL))
					// {
					// 	sendMail($seller_email,SUBJECT_SHIPPED,$body);
					// }
					// if($alternate_contact_verified=='Yes'&&$notification_whatsapp=='1'&&($seller_alternate_number!=''||$seller_alternate_number!=NULL))
					// {
					// 	//send message to whatsapp number
					// }
					// if($alternate_contact_verified=='Yes'&&$notification_sms=='1'&&($seller_alternate_number!=''||$seller_alternate_number!=NULL))
					// {
					// 	$data1=strip_tags($body);
					// 	sendMessage($seller_alternate_number,$data1);
					// }
					if($customer_email!=''||$customer_email!=NULL)
					{
						sendMail($customer_email,SUBJECT_SHIPPED,$body);
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
					echo json_encode(array("changestatustoshipped"=>$temp));
					close();
					exit();
				}
				else
				{
					rollback();
					$temp=array();
					$temp['response_code']=404;
					$temp['response_desc']="Invalid Operation";
					echo json_encode(array("changestatustoshipped"=>$temp));
					close();
					exit();
				}
			}
		}

	}
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("changestatustoshipped"=>$temp));
	close();
	exit();
}

?>
