<?php
require('getSellerWalletBalanceRest.php');
require('getPaymentIntegrationRest.php');
$flag=true;
if(isset($_REQUEST['order_status'])&&$_REQUEST['order_status']!=''&&isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!=''&&isset($_REQUEST['order_id'])&&$_REQUEST['order_id']!='')
{	
	$query="SELECT
				order_type,
			    order_status,
			    customer_email,
			    customer_mobile
			FROM
			    basket_order
			WHERE
			    basket_order_id = '".$_REQUEST['order_id']."' 
			    AND 
			    seller_id = '".$_REQUEST['user_id']."'";
	$query=query($query);
	if(!$query)
	{
		error_log(error(),0);
		$temp=array();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("changestatustoDeclined"=>$temp));
		close();
		exit();
	}
	$row=fetch_array($query);
	$orderType = $row['order_type'];
	$customer_email=$row['customer_email'];
	$customer_mobile=$row['customer_mobile'];
	$seller_email='';
	$email_verified='';
	$notification_whatsapp='';
	$notification_sms='';
	$notification_email='';
	$seller_alternate_number='';
	$seller_business_name='';
	$username='';
	$alternate_contact_verified='';
	if(($row['order_status']=='Accepted'||$row['order_status']=='Pending')&&$_REQUEST['order_status']=='Declined')
	{
		
		$query=query("SELECT
					    SD.seller_business_name,
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
					    U.username
					FROM
					    users U,
					    seller_details SD
					WHERE
					    U.user_id = '".$_REQUEST['user_id']."' 
					    AND 
					    U.user_id = SD.seller_id
				");
		confirm($query);
		if(!$query)
		{
			error_log(error(),0);
			$temp=array();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("changestatustoDeclined"=>$temp));
			close();
			exit();
		}
		if(mysqli_num_rows($query)==0)
		{
			$temp=array();
			$temp['response_code']=405;
			$temp['response_desc']="No records Found";
			echo json_encode(array("changestatustoDeclined"=>$temp));
			exit();
		}
		$row=fetch_array($query);
		$seller_email				=	$row['seller_email'];
		$email_verified				=	$row['email_verified'];
		$notification_whatsapp		=	$row['notification_whatsapp'];
		$notification_sms			=	$row['notification_sms'];
		$notification_email			=	$row['notification_email'];
		$seller_alternate_number	=	$row['seller_alternate_number'];
		$seller_business_name		=	$row['seller_business_name'];
		$alternate_contact_verified	=	$row['alternate_contact_verified'];
		$username = $row['username'];
		if($row['status']!="A"  )
		{
			$temp=array();
			$temp['response_code']=405;
			$temp['response_desc']="This User Account Is Not Active. Please Contact Customer Care";
			echo json_encode(array("changestatustoDeclined"=>$temp));
			exit();
		}

		if($orderType=='Prepaid')	
		{
			$walletbalance = new Wallet();
			$walletbalance->getWalletDetails($_REQUEST['user_id']);
			$valuedate 		= $walletbalance->value_date;
			$openingbalance = round($walletbalance->opening_balance,2);
			$closingbalance = round($walletbalance->closing_balance,2);
			//echo $openingbalance."\n".$closingbalance."\n";
			$newclosingbalance=0;
			$query="SELECT
					    cash_movement_id,
					    movement_type,
					    entry_side,
					    amount,
					    amount_currency
					FROM
					    cash_movements
					WHERE
					    seller_id = '".$_REQUEST['user_id']."' 
					    AND 
					    order_id = '".$_REQUEST['order_id']."' 
					    AND 
					    movement_type in (1,4)";
	//echo $query;

			$query=query($query);
			if(!$query)
			{
				error_log(error(),0);
				$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("changestatustoDeclined"=>$temp));
				close();
				exit();
			}
			if(mysqli_num_rows($query)==0)
			{
				$temp=array();
				$temp['response_code']=405;
				$temp['response_desc']="No records Found";
				echo json_encode(array("changestatustoDeclined"=>$temp));
				close();
				exit();
			}
			while($row1=fetch_array($query))
			{
				if($row1['movement_type'] == 1)   //Net Amount
				{
					$query1="UPDATE
							    cash_movements
							SET
							    movement_status = 3,
							    last_modification_datetime = NOW()
							WHERE
							    cash_movement_id = '".$row1['cash_movement_id']."'";
				}
				if($row1['movement_type'] == 4 && $row1['entry_side']=='offset') //Gateway Charge
				{
					$query1="UPDATE
						    cash_movements
						SET
						    movement_status = 2,
						    settled_amount = '".$row1['amount']."',
						    last_modification_datetime = NOW()
						WHERE
						    cash_movement_id = '".$row1['cash_movement_id']."'";
				}
				if($row1['movement_type'] == 4 && $row1['entry_side']=='seller')  //Gateway Charge
				{
					$newclosingbalance =  $closingbalance + $row1['amount'];
					$query1="UPDATE
						    cash_movements
						SET
						    movement_status = 2,
						    settled_amount = '".$row1['amount']."',
						    opening_balance = '".$closingbalance."',
						    closing_balance = '".$newclosingbalance."',
						    last_modification_datetime = NOW(),
						    settled_amount = '".$row1['amount']*(-1)."'
						WHERE
						    cash_movement_id = '".$row1['cash_movement_id']."'";
				}
				//echo $query1;
				$query1=query($query1);
				$result=confirm($query1);
				if(!$result)
				{
					error_log(error(),0);
					$flag = false;
					rollback();
					$temp=array();
					$temp['response_code']=404;
					$temp['response_desc']="Invalid Operation";
					echo json_encode(array("changestatustoDeclined"=>$temp));
					close();
					exit();
				}
				$openingbalance = round($closingbalance,2);
				$closingbalance = $newclosingbalance;
				$result         = $walletbalance->upadteWalletDetails($_REQUEST['user_id'],$openingbalance,$closingbalance,$valuedate);
				if(!$result)
				{
					error_log(error(),0);
					$flag = false;
					rollback();
					$temp=array();
					$temp['response_code']=404;
					$temp['response_desc']="Invalid Operation";
					echo json_encode(array("changestatustoDeclined"=>$temp));
					close();
					exit();
				}
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
				error_log(error(),0);
				$flag=false;
				rollback();
				$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("changestatustoDeclined"=>$temp));
				close();
				exit();
			}
			if($flag)
			{
				$body='Your Order- <h1>'.$_REQUEST['order_id'].'</h1> has been Declined by Seller. <br><table><tr><td>'.$seller_business_name.'</td></tr><tr><td>'.$seller_alternate_number.'</td></tr><tr><td>Click here to continue shopping</td></tr><tr><td>'.DOMAIN.'/app/?s='.$username.'</td></tr></table>';
				$body1='Your Order-'.$_REQUEST['order_id'].' has been Declined by Seller.  '.$seller_business_name.'  '.$seller_alternate_number.' Click here to continue shopping   '.DOMAIN.'/app/?s='.$username;
				// if($notification_email=='1'&&$email_verified=='1'&&($seller_email!=''||$seller_email!=NULL))
				// {
				// 	sendMail($seller_email,SUBJECT_REJECTED,$body);
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
					sendMail($customer_email,SUBJECT_REJECTED,$body);
				}
				if($customer_mobile!=''||$customer_mobile!=NULL)
				{
					
					sendMessage($customer_mobile,$body1);
					//sendWhatsappMessage($customer_mobile,SUBJECT_DELIVERED,$data1);
				}
				commit();
				$temp=array();
				$temp['response_code']=200;
				$temp['response_desc']="Sucess";
				echo json_encode(array("changestatustoDeclined"=>$temp));
				close();
				exit();
			}
			else
			{
				error_log(error(),0);
				rollback();
				$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("changestatustoDeclined"=>$temp));
				close();
				exit();
			}
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
			if(!$result)
			{
				error_log(error(),0);
				$flag=false;
				rollback();
				$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("changestatustoDeclined"=>$temp));
				close();
				exit();
			}

			if($flag)
			{
				$body='Your Order- <h1>'.$_REQUEST['order_id'].'</h1> has been Declined by Seller. <br><table><tr><td>'.$seller_business_name.'</td></tr><tr><td>'.$seller_alternate_number.'</td></tr><tr><td>Click here to continue shopping</td></tr><tr><td>'.DOMAIN.'/app/?s='.$username.'</td></tr></table>';
				$body1='Your Order-'.$_REQUEST['order_id'].' has been Declined by Seller.  '.$seller_business_name.'  '.$seller_alternate_number.' Click here to continue shopping   '.DOMAIN.'/app/?s='.$username;
				
				// if($notification_email=='1'&&$email_verified=='1'&&($seller_email!=''||$seller_email!=NULL))
				// {
				// 	sendMail($seller_email,SUBJECT_REJECTED,$body);
				// }
				// if($alternate_contact_verified=='Yes'&&$notification_whatsapp=='1'&&($seller_alternate_number!=''||$seller_alternate_number!=NULL))
				// {
				// 					}
				// if($alternate_contact_verified=='Yes'&&$notification_sms=='1'&&($seller_alternate_number!=''||$seller_alternate_number!=NULL))
				// {
				// 	sendMessage($seller_alternate_number,$body);

				// }
				if($customer_email!=''||$customer_email!=NULL)
				{
					sendMail($customer_email,SUBJECT_REJECTED,$body);
				}
				if($customer_mobile!=''||$customer_mobile!=NULL)
				{
					
					sendMessage($customer_mobile,$body1);
					//sendWhatsappMessage($customer_mobile,SUBJECT_DELIVERED,$data1);
				}
				commit();
				$temp=array();
				$temp['response_code']=200;
				$temp['response_desc']="Sucess";
				echo json_encode(array("changestatustoDeclined"=>$temp));
			}
			else
			{
				error_log(error(),0);
				rollback();
				$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("changestatustoDeclined"=>$temp));
				close();
				exit();
			}
		}	
	}
		
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("changestatustoDeclined"=>$temp));
	close();
	exit();
}
?>
