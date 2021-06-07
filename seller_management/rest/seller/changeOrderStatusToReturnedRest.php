<?php
// setting content as we will convert data in JSON type.
require('getSellerWalletBalanceRest.php');
require('getPaymentIntegrationRest.php');

$flag = true;
if(isset($_REQUEST['order_status'])&&$_REQUEST['order_status']!=''&&isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!=''&&isset($_REQUEST['order_id'])&&$_REQUEST['order_id']!='')
{	
	$query="SELECT
				order_type,
			    order_date,
			    net_amount,
			    order_status,
			    customer_email
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
		echo json_encode(array("changestatustoreturned"=>$temp));
		close();
		exit();
	}
	$row=fetch_array($query);
	$orderType=$row['order_type'];
	$order_date=date('Y-m-d',strtotime($row['order_date']));
	$net_amount=round($row['net_amount'],2);
	$customer_email=$row['customer_email'];
	$seller_email='';

	if(($row['order_status']=='Shipped' || $row['order_status']=='Delivered')&&$_REQUEST['order_status']=='Returned')
	{
		$query=query("SELECT
							status,
						    logistics_integrated,
						    kyc_completed,
						    seller_email
						FROM
						    users,
						    seller_details
						WHERE
						    user_id = '".$_REQUEST['user_id']."' 
						    AND 
						    users.user_id = seller_details.seller_id
						");
		
		confirm($query);
		if(!$query)
		{
			error_log(error(),0);
			$temp=array();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("changestatustoreturned"=>$temp));
			close();
			exit();
		}
		$row=fetch_array($query);
		$seller_email=$row['seller_email'];
		$paymentReference='';
		//echo $query;
		if(mysqli_num_rows($query)==0)
		{
			$temp=array();
			$temp['response_code']=405;
			$temp['response_desc']="No results Found";
			echo json_encode(array("changestatustoreturned"=>$temp));
			close();
			exit();
		}
		if($row['status']!="A" )
		{ 
			$temp=array();
			$temp['response_code']=405;
			$temp['response_desc']="This User Account Is Not Active. Please Contact Customer Care";
			echo json_encode(array("changestatustoreturned"=>$temp));
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
			if(!$result)
			{
				error_log(error(),0);
				$flag=false;
				rollback();
				$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("changestatustoreturned"=>$temp));
				close();
				exit();
			}
		}
		if($orderType=='Prepaid')
		{

			$query="SELECT
					    cash_movement_id,
					    payment_reference
					FROM
					    cash_movements
					WHERE
					    seller_id = '".$_REQUEST['user_id']."' 
					    AND 
					    order_id = '".$_REQUEST['order_id']."' 
					    AND 
					    movement_type in (1,4)
					";
			//echo $query;
			$query=query($query);
			if(!$query)
			{
				error_log(error(),0);
				$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("changestatustoreturned"=>$temp));
				close();
				exit();
			}
			while($row1=fetch_array($query))
			{
				$paymentReference = $row1['payment_reference'];
				$query1="UPDATE
						    cash_movements
						SET
						    movement_status = 6,
						    last_modification_datetime = NOW()
						WHERE
						    cash_movement_id = '".$row1['cash_movement_id']."'
						";
				//movement_status =6  Returned;
				$query1=query($query1);
				//echo 'df';
				$result=confirm($query1);
				//echo $result;
				if(!$result)
				{
					error_log(error(),0);
					$flag = false;
					rollback();
					$temp=array();
					$temp['response_code']=404;
					$temp['response_desc']="Invalid Operation";
					echo json_encode(array("changestatustoreturned"=>$temp));
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
				echo json_encode(array("changestatustoreturned"=>$temp));
				close();
				exit();
			}
			if($row['logistics_integrated']=='Yes')
			{
				if($row['kyc_completed']!='1')
				{

					rollback();
					$temp=array();
					$temp['response_code']=405;
					$temp['response_desc']="Seller KYC is not complete but logistics is integrated. Turn off logistics integration to proceed";
					echo json_encode(array("changestatustoreturned"=>$temp));
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
				if(!$query)
				{
					error_log(error(),0);
					$temp=array();
					$temp['response_code']=404;
					$temp['response_desc']="Invalid Operation";
					echo json_encode(array("changestatustoreturned"=>$temp));
					close();
					exit();
				}
				$row=fetch_array($query);
				$rows=mysqli_num_rows($query);
				$shippingAmount = 0;
				if($rows>0)
				{
					$shippingAmount = $row['shipping_amount'];
				}
				if($net_amount>0)
				{
				
					$row=fetch_array($query);
					$walletbalance = new Wallet();
					$walletbalance->getWalletDetails($_REQUEST['user_id']);
					$valuedate =$walletbalance->value_date;
					$openingbalance=round($walletbalance->opening_balance,2);
					$closingbalance=round($walletbalance->closing_balance,2);
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
					$cashMovement->movement_type 				= '2';   //Delivery Charges
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
						error_log(error(),0);
						$flag=false;
						rollback();
						$temp=array();
						$temp['response_code']=404;
						$temp['response_desc']="Invalid Operation";
						echo json_encode(array("changestatustoreturned"=>$temp));
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
					$cashMovement->movement_type 	= '2';  // Delivery Charges
					$cashMovement->movement_status 	= '2';  // Posted

					$result=$cashMovement->insertCashMovementOffsetSide();
					
					if(!$result)
					{
						error_log(error(),0);
						$flag = false;
						rollback();
						$temp=array();
						$temp['response_code']=404;
						$temp['response_desc']="Invalid Operation";
						echo json_encode(array("changestatustoreturned"=>$temp));
						close();
						exit();
					}
					$openingbalance=round($closingbalance,2);
					$closingbalance=$newclosingbalance;
					$result=$walletbalance->upadteWalletDetails($_REQUEST['user_id'],$openingbalance,$closingbalance,$valuedate);
					if(!$result)
					{
						error_log(error(),0);
						$flag = false;
						rollback();
						$temp=array();
						$temp['response_code']=404;
						$temp['response_desc']="Invalid Operation";
						echo json_encode(array("changestatustoreturned"=>$temp));
						close();
						exit();
					}
					
					
				}
				else
				{
					error_log(error(),0);
					$flag=false;
					rollback();
					$temp=array();
					$temp['response_code']=405;
					$temp['response_desc']="";
					echo json_encode(array("changestatustoreturned"=>$temp));
					close();
					exit();
				}					
			}
			
		}	
		if($flag)
		{
			// $body=BODY_RETURNED.'<h1>'.$_REQUEST['order_id'].'</h1>';
			// if($seller_email!=''||$seller_email!='null')
			// {
			// 	sendMail($seller_email,SUBJECT_RETURNED,$body);
			// }
			// if($customer_email!=''||$customer_email!='null')
			// {
			// 	sendMail($customer_email,SUBJECT_RETURNED,$body);
			// }
			commit();
			$temp=array();
			$temp['response_code']=200;
			$temp['response_desc']="Sucess";
			echo json_encode(array("changestatustoreturned"=>$temp));
		}
		else
		{
			error_log(error(),0);
			rollback();
			$temp=array();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("changestatustoreturned"=>$temp));
			close();
			exit();
		}		
	}
	else
	{
		$temp=array();
		$temp['response_code']=400;
		$temp['response_desc']="Invalid Request";
		echo json_encode(array("changestatustoreturned"=>$temp));
		close();
		exit();
	}
		
}

else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("changestatustoreturned"=>$temp));
	close();
	exit();
}
?>
