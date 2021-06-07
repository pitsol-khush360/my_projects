<?php
header("Content-Type:application/json");
class CashMovement
{
	public $cash_movement_id;
	public $linked_movement;
	public $order_id;
	public $seller_id;
	public $entry_side;
	public $opening_balance;
	public $amount;
	public $amount_currency;	
	public $dr_cr_indicator;
	public $closing_balance;
	public $movement_type;
	public $settled_amount;
	public $payment_reference;
	public $movement_status;
	public $created_date_time;
	public $last_modification_datetime;
	public $movement_date;
    public $service_charge;
    public $service_tax;
	public $order_date;
	public $value_date;
	public $movement_description;
  // Methods
 //  function get_details($user_id) {
 //    $query="select * from reviews where seller_id='".$user_id."'";
 //    $query=query($query);
 //    confirm($query);
 //    $row=array();
 //    while($row1=fetch_array($query))
	// 	{
				
	// 		$row=$row1;
			
	// 	}
	// $this->seller_id=$row['seller_id'];
	// $this->review_title=$row['review_title'];
	// $this->review=$row['review'];
	// $this->rating=$row['rating'];
	// $this->creation_date_time=$row['creation_date_time'];
 //  }
  // function updateCashMovementSellerSide($cashmovementid,$orderId,$userId,$entrySide,$closingbalance,$amount,$currency,$dr_cr_indicator,$newclosingbalance,$movementType,$settledAmount,$paymentReference,$movementStatus,$createDate,$modifiedDate,$movementDate,$serviceCharge,$serviceTax,$orderDate,$movementDescription,$valueDate) 
	function insertCashMovementSellerSide()
	{
	
	if($this->value_date!='CURDATE()')
	{
		$valueDate = '"'.$this->value_date.'"';
	}
	else
	{
		$valueDate = $this->value_date;
	}
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
							    settled_amount,
							    payment_reference,
							    movement_status,
							    created_date_time,
							    last_modification_datetime,
							    movement_date,
							    service_charge,
							    service_tax,
							    order_date,
							    movement_description,
							    value_date
							)
			VALUES(';
					$query.='"'.$this->cash_movement_id.'",'
					;
					$query.='"'.$this->order_id.'",'
					;
					$query.='"'.$this->seller_id.'",'
					;
					$query.='"'.$this->entry_side.'",'
					;
					$query.='"'.$this->opening_balance.'",'
					;
					$query.='"'.round($this->amount,2).'",'
					;
					$query.='"'.$this->amount_currency.'",'
					;
					$query.='"'.$this->dr_cr_indicator.'",'
					;
					$query.='"'.$this->closing_balance.'",'
					;
					$query.='"'.$this->movement_type.'",'
					;
					$query.='"'.round($this->settled_amount,2).'",'
					;
					$query.='"'.$this->payment_reference.'",'
					;
					$query.='"'.$this->movement_status.'",'
					;
					$query.=$this->created_date_time.','
					;
					$query.=$this->last_modification_datetime.','
					;
					$query.=$this->movement_date.','
					;
					$query.='"'.$this->service_charge.'",'
					;
					$query.='"'.$this->service_tax.'",'
					;
					$query.='"'.$this->order_date.'",'
					;
					$query.='"'.$this->movement_description.'",'
					;
					$query.=$valueDate.'
						
					)';
	//echo $query;
	$query=query($query);
	//echo $connection->error;
    return confirm($query);
  }
  // function updateCashMovementOffsetSide($cashmovementid,$orderId,$linkedId,$userId,$entrySide,$closingbalance,$amount,$currency,$dr_cr_indicator,$newclosingbalance,$movementType,$settledAmount,$paymentReference,$movementStatus,$createDate,$modifiedDate,$movementDate,$serviceCharge,$serviceTax,$orderDate,$movementDescription,$valueDate) 
	function insertCashMovementOffsetSide()
  	{
	
	if($this->value_date!='CURDATE()')
	{
		$valueDate = '"'.$this->value_date.'"';
	}
	else
	{
		$valueDate = $this->value_date;
	}
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
							    settled_amount,
							    payment_reference,
							    movement_status,
							    created_date_time,
							    last_modification_datetime,
							    movement_date,
							    service_charge,
							    service_tax,
							    order_date,
							    movement_description,
							    value_date
							)
			VALUES(';
					
					$query.='"'.$this->cash_movement_id.'",'
					;
					$query.='"'.$this->order_id.'",'
					;
					$query.='"'.$this->linked_movement.'",'
					;
					$query.='"'.$this->seller_id.'",'
					;
					$query.='"'.$this->entry_side.'",'
					;
					$query.='"'.$this->opening_balance.'",'
					;
					$query.='"'.round($this->amount,2).'",'
					;
					$query.='"'.$this->amount_currency.'",'
					;
					$query.='"'.$this->dr_cr_indicator.'",'
					;
					$query.='"'.$this->closing_balance.'",'
					;
					$query.='"'.$this->movement_type.'",'
					;
					$query.='"'.round($this->settled_amount,2).'",'
					;
					$query.='"'.$this->payment_reference.'",'
					;
					$query.='"'.$this->movement_status.'",'
					;
					$query.=$this->created_date_time.','
					;
					$query.=$this->last_modification_datetime.','
					;
					$query.=$this->movement_date.','
					;
					$query.='"'.$this->service_charge.'",'
					;
					$query.='"'.$this->service_tax.'",'
					;
					$query.='"'.$this->order_date.'",'
					;
					$query.='"'.$this->movement_description.'",'
					;
					$query.=$valueDate.'
						
					)';
	//echo $query;
	//echo $connection->error;
	$query=query($query);
	//echo $connection->error;
    return confirm($query);
  }
}

?>
