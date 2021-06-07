<?php
require('getSellerWalletBalanceRest.php');
require('getPaymentIntegrationRest.php');
$flag = true;
if(isset($_REQUEST['order_status'])&&$_REQUEST['order_status']!=''&&isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!=''&&isset($_REQUEST['order_id'])&&$_REQUEST['order_id']!='')
{	

	$query="
			SELECT
				order_type,
			    order_date,
			    net_amount,
			    order_status,
			    customer_email,
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
		echo json_encode(array("checkout"=>$temp));
		close();
		exit();
	}
	$row=fetch_array($query);
	$orderType = $row['order_type'];
	$customer_email=$row['customer_email'];
	$customer_mobile=$row['customer_mobile'];
	$order_date=date('Y-m-d',strtotime($row['order_date']));
	$net_amount=round($row['net_amount'],2);
	$paymentReference=$row['payment_reference'];
	$seller_email='';
	$email_verified='';
	$notification_whatsapp='';
	$notification_sms='';
	$notification_email='';
	$seller_alternate_number='';
	$username='';
	$seller_business_name='';
	if($row['order_status']=='Shipped'&&$_REQUEST['order_status']=='Delivered')
	{
		$query=query("SELECT
						U.status,
					    SD.logistics_integrated,
					    SD.kyc_completed,
					    SD.waive_platform_fees,
					    SD.seller_email,
					    SD.email_verified,
					    SD.notification_email,
					    SD.notification_sms,
					    SD.notification_whatsapp,
					    SD.seller_alternate_number,
					    SD.seller_business_name,
					    U.username
					FROM
					    users U,
					    seller_details SD
					WHERE
					    U.user_id = '".$_REQUEST['user_id']."' 
					    AND 
					    U.user_id = SD.seller_id");
		confirm($query);
		if(!$query)
		{
			error_log(error(),0);
			$temp=array();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("changestatustodelivered"=>$temp));
			close();
			exit();
		}
		if(mysqli_num_rows($query)==0)
		{
			$temp=array();
			$temp['response_code']=405;
			$temp['response_desc']="No records Found";
			echo json_encode(array("changestatustodelivered"=>$temp));
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
		$username=$row['username'];
		if($row['status']!="A")
		{
			$temp=array();
			$temp['response_code']=405;
			$temp['response_desc']="This User Account Is Not Active. Please Contact Customer Care";
			echo json_encode(array("changestatustodelivered"=>$temp));
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
					    seller_id = '".$_REQUEST['user_id']."'";
			$query=query($query);
			$result=confirm($query);
			if(!$result)
			{
				$flag=false;
				rollback();
				$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("changestatustodelivered"=>$temp));
				close();
				exit();
			}

			if($flag)
			{
				$body='Your Order- <h1>'.$_REQUEST['order_id'].'</h1> has been Delivered. <br><table><tr><td>'.$seller_business_name.'</td></tr><tr><td>'.$seller_alternate_number.'</td></tr><tr><td>Click here to continue shopping</td></tr><tr><td>'.DOMAIN.'/app/?s='.$username.'</td></tr></table>';
				$body1='Your Order-'.$_REQUEST['order_id'].' has been Delivered.  '.$seller_business_name.'  '.$seller_alternate_number.' Click here to continue shopping   '.DOMAIN.'/app/?s='.$username;
				// if($notification_email=='1'&&$email_verified=='1'&&($seller_email!=''||$seller_email!=NULL))
				// {
				// 	sendMail($seller_email,SUBJECT_ORDER,$body);
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
					sendMail($customer_email,SUBJECT_DELIVERED,$body);
				}
				if($customer_mobile!=''||$customer_mobile!=NULL)
				{
					$data1=strip_tags($body);
					sendMessage($customer_mobile,$body1);
					//sendWhatsappMessage($customer_mobile,SUBJECT_DELIVERED,$data1);
				}
				// echo $seller_email.'\n'.$body;
				// echo 'mahesh';
				commit();
				$temp=array();
				$temp['response_code']=200;
				$temp['response_desc']="Sucess";
				echo json_encode(array("changestatustodelivered"=>$temp));
			}
			else
			{
				error_log(error(),0);
				rollback();
				$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("changestatustodelivered"=>$temp));
				close();
				exit();
			}
		}
		if($orderType=='Prepaid')
		{
			if($row['waive_platform_fees']=='No')
			{
				
				$query=query("SELECT
							    comission_percentage,
							    tax_on_commission
							FROM
							    commission_charges
							WHERE
							    comission_type = 'PLATFORM_FEES'");
				if(!$query)
				{
					error_log(error(),0);
					$temp=array();
					$temp['response_code']=404;
					$temp['response_desc']="Invalid Operation";
					echo json_encode(array("changestatustodelivered"=>$temp));
					close();
					exit();
				}
				$row=fetch_array($query);
				$comission_percentage=round($row['comission_percentage'],2);
				$tax_on_commission=round($row['tax_on_commission'],2);
				if(mysqli_num_rows($query)!=0)
				{
					$gross_platform_fees = round(($comission_percentage*$net_amount)/100,2);
					$net_platform_fees = round($gross_platform_fees + ($gross_platform_fees  * $tax_on_commission)/100,2);

					$cashmovementid=$_REQUEST['user_id']+date("YmdHis").rand(100,1000);

					$cashMovement = new CashMovement();
					$cashMovement->cash_movement_id 			= $cashmovementid;
					$cashMovement->linked_movement 				= $cashmovementid;
					$cashMovement->order_id 					= $_REQUEST['order_id'];
					$cashMovement->seller_id 					= $_REQUEST['user_id'];
					$cashMovement->entry_side 					= 'seller';
					$cashMovement->opening_balance 				= '0.00';
					$cashMovement->amount 						= $net_platform_fees*(-1);
					$cashMovement->amount_currency 				='INR';	
					$cashMovement->dr_cr_indicator 				= 'D';  //Debit
					$cashMovement->closing_balance 				= '0.00';
					$cashMovement->movement_type				= '3';  // Platform Fees
					$cashMovement->settled_amount 				= '0.00';
					$cashMovement->payment_reference 			= $paymentReference;
					$cashMovement->movement_status 				= '4';  // Ready For Settlement
					$cashMovement->created_date_time 			= 'NOW()';
					$cashMovement->last_modification_datetime 	= 'NOW()';
					$cashMovement->movement_date 				= 'CURDATE()';
				    $cashMovement->service_charge 				= '0.00';
				    $cashMovement->service_tax 					= '0.00';
					$cashMovement->order_date 					= $order_date;
					$cashMovement->value_date 					= 'CURDATE()';
					$cashMovement->movement_description 		= 'Platform Fees';

					$result=$cashMovement->insertCashMovementSellerSide();
					if(!$result)
					{
						$flag = false;
						rollback();
						$temp=array();
						$temp['response_code']=404;
						$temp['response_desc']="Invalid Operation";
						echo json_encode(array("changestatustodelivered"=>$temp));
						close();
						exit();
					}

					$cashmovementid1=$_REQUEST['user_id']+date("YmdHis").rand(100,1000);

					$cashMovement->cash_movement_id = $cashmovementid1;
					$cashMovement->entry_side 		= 'offset';
					$cashMovement->opening_balance 	= '0.00';
					$cashMovement->amount 			= $net_platform_fees;	
					$cashMovement->dr_cr_indicator 	= 'C';  //Credit
					$cashMovement->closing_balance 	= '0.00';
					$cashMovement->movement_type 	= '3'; // Platform Fees
					$cashMovement->movement_status 	= '4'; // Ready For Settlement

					$result=$cashMovement->insertCashMovementOffsetSide();
					
					if(!$result)
					{
						$flag = false;
						rollback();
						$temp=array();
						$temp['response_code']=404;
						$temp['response_desc']="Invalid Operation";
						echo json_encode(array("changestatustodelivered"=>$temp));
						close();
						exit();
					}
					$query="SELECT
							    cash_movement_id
							FROM
							    cash_movements
							WHERE
							    seller_id = '".$_REQUEST['user_id']."' 
							    AND 
							    order_id = '".$_REQUEST['order_id']."' 
							    AND 
							    movement_type in (1,4)";    //Net Amount(1) & GateWay Charge(4)
					$query=query($query);
					//movement_status = 4 Ready for Settlement 
					if(!$query)
					{
						error_log(error(),0);
						$temp=array();
						$temp['response_code']=404;
						$temp['response_desc']="Invalid Operation";
						echo json_encode(array("changestatustodelivered"=>$temp));
						close();
						exit();
					}
					while($row1=fetch_array($query))
					{

						$query1="UPDATE
								    cash_movements
								SET
								    movement_status = 4,
								    last_modification_datetime = NOW()
								WHERE
								    cash_movement_id = '".$row1['cash_movement_id']."'
								    "; //  movement_status = 4  GateWay Charge(4)
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
							echo json_encode(array("changestatustodelivered"=>$temp));
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
							     seller_id = '".$_REQUEST['user_id']."'";
					$query=query($query);
					$result=confirm($query);
					if(!$result)
					{
						$flag=false;
						rollback();
						$temp=array();
						$temp['response_code']=404;
						$temp['response_desc']="Invalid Operation";
						echo json_encode(array("changestatustodelivered"=>$temp));
						close();
						exit();
					}

					if($flag)
					{
						
						$body='Your Order- <h1>'.$_REQUEST['order_id'].'</h1> has been Delivered. <br><table><tr><td>'.$seller_business_name.'</td></tr><tr><td>'.$seller_alternate_number.'</td></tr><tr><td>Click here to continue shopping</td></tr><tr><td>'.DOMAIN.'/app/?s='.$username.'</td></tr></table>';
						$body1='Your Order-'.$_REQUEST['order_id'].' has been Delivered.  '.$seller_business_name.'  '.$seller_alternate_number.' Click here to continue shopping   '.DOMAIN.'/app/?s='.$username;
						// if($notification_email=='1'&&$email_verified=='1'&&($seller_email!=''||$seller_email!=NULL))
						// {
						// 	sendMail($seller_email,SUBJECT_DELIVERED,$body);
						// }
						// if($alternate_contact_verified=='Yes'&&$notification_whatsapp=='1'&&($seller_alternate_number!=''||$seller_alternate_number!=NULL))
						// {
							
						// }
						// if($alternate_contact_verified=='Yes'&&$notification_sms=='1'&&($seller_alternate_number!=''||$seller_alternate_number!=NULL))
						// {
						// 	sendMessage($seller_alternate_number,$body1);
						// }
						if(($customer_email!=''||$customer_email!=NULL))
						{
							sendMail($customer_email,SUBJECT_DELIVERED,$body);
						}
						if($customer_mobile!=''||$customer_mobile!=NULL)
						{
							$data1=strip_tags($body);
							sendMessage($customer_mobile,$body1);
							//sendWhatsappMessage($customer_mobile,SUBJECT_DELIVERED,$data1);
						}
						commit();
						$temp=array();
						$temp['response_code']=200;
						$temp['response_desc']="Sucess";
						echo json_encode(array("changestatustodelivered"=>$temp));
					}
					else
					{
						error_log(error(),0);
						rollback();
						$temp=array();
						$temp['response_code']=404;
						$temp['response_desc']="Invalid Operation";
						echo json_encode(array("changestatustodelivered"=>$temp));
						close();
						exit();
					}
				}
						
			}
			// else
			// {
			// 	//waive
			// }
		}
		

	
		
	}
		// else
		// {
		// 	//deliverd
		// }
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("changestatustodelivered"=>$temp));
	close();
	exit();
}
$connection->autocommit(TRUE);
close();

?>
