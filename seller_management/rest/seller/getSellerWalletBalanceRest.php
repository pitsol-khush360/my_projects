<?php
header("Content-Type:application/json");

include('../../config/email_functions.php');
require_once("../validation.php");
$connection->autocommit(FALSE);
class Wallet
{
	public $value_date;
	public $opening_balance;
	public $closing_balance;
	public $balance_currency;
	public $creation_datetime;
	public $seller_id;
	public $flag=true;
  // Methods
  function getWalletDetails($user_id) {
	 $query="
	 			SELECT
				    seller_id,
				    value_date,
				    opening_balance,
				    closing_balance,
				    balance_currency,
				    creation_datetime
				FROM
				    wallet_balance
				WHERE
				    seller_id = '".$user_id."' 
				    AND 
				    value_date =(
				    SELECT
				        MAX(value_date)
				    FROM
				        wallet_balance
				    WHERE
				        seller_id = '".$user_id."'
					)
	 		";
    $query=query($query);
    confirm($query);
    $row=array();
    $flag=false;
    $rows=0;
    while($row1=fetch_array($query))
	{	
		$row=$row1;
		$flag=true;
		$rows=1;
	}
	if($rows==1)
	{
		$this->seller_id=$row['seller_id'];
		$this->value_date=$row['value_date'];
		$this->opening_balance=$row['opening_balance'];
		$this->closing_balance=$row['closing_balance'];
		$this->balance_currency=$row['balance_currency'];
		$this->creation_datetime=$row['creation_datetime'];
		$rows=0;
		return $row;
	}
	else
	{
		$row=array();
		$row['seller_id']=$user_id;
		$row['value_date']='';
		$row['opening_balance']=0;
		$row['closing_balance']=0;
		$row['balance_currency']='INR';
		$row['creation_datetime']='';
		$rows=0;
		$this->seller_id=$row['seller_id'];
		$this->value_date='';
		$this->opening_balance=0;
		$this->closing_balance=0;
		$this->balance_currency="INR";
		$this->creation_datetime='';
		return $row;
	}
  }

   function getWalletTransactionDetails($user_id,$status) {
   	$str='';
   	if($status=='Processed' || $status=='')
   	{
   		$str='(2,5)';
   	}
   	else if($status=='Pending')
   	{
   		$str='(1,3,4,6)';
   	}
   	else if($status=='Refund')
   	{
   		$str='(7)';
   	}
   
   		$query="
   				SELECT
   					 cash_movement_id,
				    order_id,
				    value_date AS DATE,
				    opening_balance AS opening_balance,
				    closing_balance AS closing_balance,
				    payment_reference,
				    movement_status,
				    amount,
				    dr_cr_Indicator,
				    created_date_time,
				    movement_description
				FROM
				    cash_movements
				WHERE
				    seller_id = '".$user_id."' 
				    AND 
				    entry_side = 'seller' 
				    AND 
				    movement_status IN ".$str."
				ORDER BY
				    cash_movements.created_date_time
				DESC
    
				";
   	
   	
    $query=query($query);
    confirm($query);
    $row=array();
    while($row1=fetch_array($query))
		{	
			$date=$row1['created_date_time'];
			$row1['date']=date('d-M-Y',strtotime($date));
			$row1['time']=date('h.i A',strtotime($date));
			$row[]=$row1;
		}
		$row['rows']=mysqli_num_rows($query);
	return $row;
  }
  function getWalletTransactionStatement($user_id,$interval,$startDate,$endDate) {
   	$str='(2,5)';
   	$start='';
   	$end='';
   	if($interval==1)
   	{
   		$start=date('Y-m-d',strtotime((-date('d')+1).'days'));
   		$end=date('Y-m-d');
   	}
   	if($interval==2)
   	{
   		$start=date('Y-m-d',strtotime('-1month'));
   		$end=date('Y-m-d');
   	}
   	if($interval==3)
   	{
   		$start=date('Y-m-d',strtotime('-3month'));
   		$end=date('Y-m-d');
   	}
   	if($interval==4)
   	{
   		$start=date('Y-m-d',strtotime('-6month'));
   		$end=date('Y-m-d');
   	}
   	if($interval==5)
   	{
   		$y=date('Y');
   		$start=$y.'-1-1';
   		$start=date($start);
   		$end=date('Y-m-d');
   	}
   	if($interval==6)
   	{
   		$start=date('Y-m-d',strtotime('-1year'));
   		$end=date('Y-m-d');
   	}
   	if($interval==7)
   	{
   		$start=$startDate;
   		$end=$endDate;
   	}
   		$query="
   				SELECT
				    CM.cash_movement_id,
				    CM.order_id,
				    CM.value_date AS DATE,
				    CM.opening_balance AS opening_balance,
				    CM.closing_balance AS closing_balance,
				    CM.payment_reference,
				    CM.movement_status,
				    CM.amount,
				    CM.dr_cr_Indicator,
				    CM.created_date_time,
				    CM.movement_description
				FROM
				    cash_movements CM
				WHERE
				    CM.seller_id = '".$user_id."' 
				    AND  
				    CM.movement_status 
				    IN 
				    ".$str." 
				    AND 
				    CM.entry_side = 'seller' 
				    AND 
				    DATE(
				        CM.created_date_time
				    ) >= '".$start."' 
				    AND 
				    DATE(
				        CM.created_date_time
				    ) <= '".$end."'
				ORDER BY
				    CM.created_date_time
				DESC
				    
				";
   	
   	//echo $query;
    $query=query($query);
    confirm($query);
    $row=array();
    while($row1=fetch_array($query))
		{	
			$date=$row1['created_date_time'];
			$row1['date']=date('d-M-Y',strtotime($date));
			$row1['time']=date('h.i A',strtotime($date));
			$row[]=$row1;
		}
		$row['rows']=mysqli_num_rows($query);
	return $row;
  }
  function getWalletTransactionDetails1($movement_id) {
	 $query="
	 		SELECT
			    CM.cash_movement_id,
			    CM.order_id,
			    CM.value_date AS DATE,
			    CM.opening_balance AS opening_balance,
			    CM.closing_balance AS closing_balance,
			    CM.payment_reference,
			    CM.movement_status,
			    CM.amount,
			    CM.dr_cr_Indicator,
			    CM.created_date_time,
			    CM.movement_description
			FROM
			    cash_movements CM
			WHERE
			    CM.cash_movement_id = '".$movement_id."'  
			";

    $query=query($query);
    confirm($query);
    $row=array();
    while($row1=fetch_array($query))
		{	
			$date=$row1['created_date_time'];
			$row1['date']=date('d-M-Y',strtotime($date));
			$row1['time']=date('h.i A',strtotime($date));
			$row[]=$row1;
		}
		$row['rows']=mysqli_num_rows($query);
	return $row;
  }
   function upadteWalletDetails($seller_id,$opening_balance,$closingbalance,$value_date){
			//opening_balance ='".$opening_balance."',
   			$query='';
   			
			if($value_date ==date("Y-m-d")) 
			{
				$query="
						UPDATE
						    wallet_balance
						SET
						    opening_balance = '".$opening_balance."',
						    closing_balance = '".$closingbalance."'
						WHERE
						    seller_id = '".$this->seller_id."' 
						    AND 
						    value_date = '".date('Y-m-d')."'
						";

			}
			else
			{
				$query="
						INSERT INTO wallet_balance(
						    seller_id,
						    opening_balance,
						    closing_balance,
						    balance_currency,
						    value_date,
						    creation_datetime
						)
						VALUES(
						    '".$seller_id."',
						    '".$opening_balance."',
						    '".$closingbalance."',
						    'INR',
						    '".date("Y-m-d")."',
						    NOW()
						)
						";
				
							
			}

    //echo $query;
    //$query1=$query;
	//return $query;
    $query=query($query);
    $result=confirm($query);
    return $result;
    
  }
  
}

?>
