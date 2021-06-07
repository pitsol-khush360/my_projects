<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
require('getSellerWalletBalanceRest.php');
$flag = true;
$connection->autocommit(FALSE);
if(isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!=''&&isset($_REQUEST['order_id'])&&$_REQUEST['order_id']!=''&&isset($_REQUEST['order_type'])&&$_REQUEST['order_type']!='')
{	

	
	$query=query("
				SELECT
					status,
				    logistics_integrated,
				    kyc_completed,
				    waive_platform_fees,
				    accept_online_payments
				FROM
				    users,
				    seller_details
				WHERE
				    user_id = '".$_REQUEST['user_id']."' 
				    AND 
				    users.user_id = seller_details.seller_id
				");
	confirm($query);

	if(mysqli_num_rows($query)!=0)
	{
		$row=fetch_array($query);
		
		if($row['status']=="A" )
		{
			if($_REQUEST['order_type']=='Prepaid')
			{
				if($row['accept_online_payments']=='1')
				{
				

					$walletbalance = new Wallet();
					$walletbalance->getWalletDetails($_REQUEST['user_id']);
					$valuedate =$walletbalance->value_date;
					//$openingbalance=$walletbalance->opening_balance;
					//$closingbalance=$walletbalance->closing_balance;
					$openingbalance=0;
					$closingbalance=0;

					//echo $openingbalance."\n".$closingbalance."\n";
					$newclosingbalance=0;
					$paymentrefernce=12456;
					//$newclosingbalance=$closingbalance-$net_platform_fees;
					$cashmovementid=$_REQUEST['user_id']+date("YmdHis").rand(100,1000);
					$query='INSERT INTO cash_movements(
							    cash_movement_id,
							    order_id,
							    seller_id,
							    entry_side,
							    opening_balance,
							    amount,
							    amount_currency,
							    dr_cr_indicator,
							    closing_balance,
							    movement_type,
							    payment_reference,
							    movement_status,
							    created_date_time,
							    order_date,
							    value_date
							)
							VALUES(';
									$query.='"'.$cashmovementid.'",'
									;
									$query.='"'.$_REQUEST['order_id'].'",'
									;
									$query.='"'.$_REQUEST['user_id'].'",'
									;
									$query.='"seller",'
									;
									$query.='"'.$closingbalance.'",'
									;
									$query.='"'.$_REQUEST['basket_order_net_amount'].'",'
									;
									$query.='"INR",'
									;
									$query.='"C",'
									;
									$query.='"'.$newclosingbalance.'",'
									;
									$query.='1,'
									;
									$query.='"'.$paymentrefernce.'",'
									;
									$query.='1,'
									;
									$query.='NOW(),'
									;
									$query.='"'.$_REQUEST['order_date'].'",'
									;
									$query.='CURDATE()
									
								)';
											//echo $query;
						$query=query($query);
						
						$result=confirm($query);
						if(!$result)
						{
							$flag = false;
						}
						
						$cashmovementid1=$_REQUEST['user_id']+date("YmdHis").rand(100,1000);
						$query='INSERT INTO cash_movements(
									    cash_movement_id,
									    order_id,
									    Linked_movement,
									    seller_id,
									    entry_side,
									    opening_balance,
									    amount,
									    amount_currency,
									    dr_cr_indicator,
									    closing_balance,
									    movement_type,
									    payment_reference,
									    movement_status,
									    created_date_time,
									    order_date,
									    value_date
									)
									VALUES(';
											$query.='"'.$cashmovementid1.'",'
											;
											$query.='"'.$_REQUEST['order_id'].'",'
											;
											$query.='"'.$cashmovementid.'",'
											;
											$query.='"'.$_REQUEST['user_id'].'",'
											;
											$query.='"offset",'
											;
											$query.='"'.$openingbalance.'",'
											;
											$query.='"'.$_REQUEST['basket_order_net_amount'].'",'
											;
											$query.='"INR",'
											;
											$query.='"D",';
											$query.='"'.$closingbalance.'",'
											;
											$query.='1,';
											$query.='"'.$paymentrefernce.'",'
											;
											$query.='1,'
											;
											$query.='NOW(),'
											;
											$query.='"'.$_REQUEST['order_date'].'",'
											;
											$query.='CURDATE()
											
											)';
						$query=query($query);
						$result=confirm($query);
						if(!$result)
						{
							$flag = false;
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
						$comission_percentage=$row['comission_percentage'];
						$tax_on_commission=$row['tax_on_commission'];
						//echo $query;
						$gross_platform_fees;
						$net_platform_fees=0;
						if(mysqli_num_rows($query)!=0)
						{
							$gross_platform_fees = ($comission_percentage*$_REQUEST['basket_order_net_amount'])/100;
							$net_platform_fees = $gross_platform_fees + ($gross_platform_fees  * $tax_on_commission)/100;
						}
						$openingbalance=$walletbalance->opening_balance;
						$closingbalance=$walletbalance->closing_balance;
						$newclosingbalance=0;
						$newclosingbalance = $closingbalance-$net_platform_fees;
						$cashmovementid=$_REQUEST['user_id']+date("YmdHis").rand(100,1000);
						$query='INSERT INTO cash_movements(
									    cash_movement_id,
									    order_id,
									    seller_id,
									    entry_side,
									    opening_balance,
									    amount,
									    amount_currency,
									    dr_cr_indicator,
									    closing_balance,
									    movement_type,
									    payment_reference,
									    movement_status,
									    created_date_time,
									    order_date,
									    movement_description,
									    value_date
									)
									VALUES(';
											$query.='"'.$cashmovementid.'",'
											;
											$query.='"'.$_REQUEST['order_id'].'",'
											;
											$query.='"'.$_REQUEST['user_id'].'",'
											;
											$query.='"seller",'
											;
											$query.='"'.$closingbalance.'",'
											;
											$query.='"'.$net_platform_fees*(-1).'",'
											;
											$query.='"INR",'
											;
											$query.='"D",'
											;
											$query.='"'.$newclosingbalance.'",'
											;
											$query.='4,'
											;
											$query.='"'.$paymentrefernce.'",'
											;
											$query.='2,'
											;
											$query.='NOW(),'
											;
											$query.='"'.$_REQUEST['order_date'].'",'
											;
											$query.='"GATEWAY_CHARGES",'
											;
											$query.='CURDATE()
										)';
											//echo $query;
						$query=query($query);
						//$flag = true;
						$result=confirm($query);
						if(!$result)
						{
							$flag = false;
						}
						$openingbalance=0;
						$closingbalance=0;
						$cashmovementid1=$_REQUEST['user_id']+date("YmdHis").rand(100,1000);
						$query='INSERT INTO cash_movements(
									    cash_movement_id,
									    order_id,
									    Linked_movement,
									    seller_id,
									    entry_side,
									    opening_balance,
									    amount,
									    amount_currency,
									    dr_cr_indicator,
									    closing_balance,
									    movement_type,
									    payment_reference,
									    movement_status,
									    created_date_time,
									    order_date,
									    movement_description,
									    value_date
									)
									VALUES(';
											$query.='"'.$cashmovementid1.'",'
											;
											$query.='"'.$_REQUEST['order_id'].'",'
											;
											$query.='"'.$cashmovementid.'",'
											;
											$query.='"'.$_REQUEST['user_id'].'",'
											;
											$query.='"offset",'
											;
											$query.='"'.$openingbalance.'",'
											;
											$query.='"'.$_REQUEST['basket_order_net_amount'].'",'
											;
											$query.='"INR",'
											;
											$query.='"C",'
											;
											$query.='"'.$closingbalance.'",'
											;
											$query.='4,'
											;
											$query.='"'.$paymentrefernce.'",'
											;
											$query.='2,'
											;
											$query.='NOW(),'
											;
											$query.='"'.$_REQUEST['order_date'].'",'
											;
											$query.='"GATEWAY_CHARGES",'
											;
											$query.='CURDATE()
											
										)';
						
						//echo $query;
						$query=query($query);
						$result=confirm($query);
						if(!$result)
						{
							$flag = false;
						}
						$openingbalance=$walletbalance->closing_balance;
						$closingbalance=$newclosingbalance;
					$walletbalance->upadteWalletDetails($_REQUEST['user_id'],$openingbalance,$closingbalance,$valuedate);
					if($flag)
					{
						commit();
						$temp=array();
						$temp['response_code']=200;
						$temp['response_desc']="Sucess";
						echo json_encode(array("checkout"=>$temp));
					}
					else
					{
						rollback();
						$temp['response_code']=404;
						$temp['response_desc']="Invalid Results";
						echo json_encode(array("checkout"=>$temp));

					}
									
					
					
						
					
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
	echo json_encode(array("checkout"=>$temp));
}
$connection->autocommit(TRUE);
close();

?>
