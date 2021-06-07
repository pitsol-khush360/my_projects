<?php
header("Content-Type:application/json");
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$flag=true;
$connection->autocommit(FALSE);
$query="SELECT
		    cash_movement_id,
		    order_id,
		    payment_reference,
		    movement_status,
		    amount,
		    amount_currency,
		    seller_id
		FROM
		    cash_movements
		WHERE
		    entry_side = 'seller' 
		    AND
		    movement_status IN (3) 
		    AND 
		    movement_type = 1
		ORDER BY
		    cash_movement_id
		";
$query=query($query);
while($row=fetch_array($query))
{
	//print_r($row);
	if($row['movement_status']==3)
	{
		//print_r($row);
		$apiEndpoint = PRODUCTION;
		$opUrl = $apiEndpoint."/api/v1/order/refund";
		$amount=(float)$row['amount'];
		//$str="appId=".APPID."&secretKey=".SECREATKEY."&orderId=".$row['order_id']."&referenceId=".$row['payment_reference']."&refundAmount=".$amount."&refundNote=Refund for Order Id ".$row['order_id']."&refundType=INSTANT&merchantRefundId=".$row['cash_movement_id'];
		$str="appId=".APPID."&secretKey=".SECREATKEY."&orderId=".$row['order_id']."&referenceId=".$row['payment_reference']."&refundAmount=".$amount."&refundNote=Refund for Order Id ".$row['order_id'];
		//echo $str;
	    $curl = curl_init();
	    curl_setopt_array($curl, array(
	    CURLOPT_URL => $opUrl,
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_ENCODING => "",
	    CURLOPT_MAXREDIRS => 10,
	    CURLOPT_TIMEOUT => 30,
	    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	    CURLOPT_CUSTOMREQUEST => "POST",
	    CURLOPT_POSTFIELDS => $str, 
	    CURLOPT_HTTPHEADER => array(
	    "cache-control: no-cache",
	    "content-type: application/x-www-form-urlencoded"
	    ),
	    ));

	    $response = curl_exec($curl);
	    $err = curl_error($curl);
	    curl_close($curl);
	    $jsonResponse = json_decode($response);
	    //print_r($jsonResponse);
	    if ($jsonResponse->{"status"}=='OK') {
	    	$description='Customer Refund : '.$row['order_id'];
	    	$query1="UPDATE
					    cash_movements
					SET
					    movement_status = 7,
					    last_modification_datetime = NOW(), 
					    movement_description = '".$description."', 
					    settled_amount = '".$row['amount']."'
					WHERE
					    cash_movement_id = '".$row['cash_movement_id']."'
					";
	    	//echo $query1;
			$query1=query($query1);
			$result=confirm($query1);
			if(!$result)
			{
				$flag = false;
				rollback();
				$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("movementrefundbatch"=>$temp));
				close();
				exit();
			}
			$query1="
					UPDATE
					    cash_movements
					SET
					    movement_status = 7,
					    last_modification_datetime = NOW(), 
					    movement_description = '".$description."', 
					    settled_amount = '".$row['amount']."'
					WHERE
					    linked_movement = '".$row['cash_movement_id']."' 
					    AND 
					    entry_side = 'offset' 
					    AND 
					    movement_type = 1
					";
	    	//echo $query1;
			$query1=query($query1);
			$result=confirm($query1);
			if(!$result)
			{
				$flag = false;
				rollback();
				$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("movementrefundbatch"=>$temp));
				close();
				exit();
			}
	    }

	}
}
if($flag)
{
	commit();
	$temp['response_code']=200;
	$temp['response_desc']="Sucess";
	echo json_encode(array("movementrefundbatch"=>$temp));
	close();
	exit();
}
else
{
	rollback();
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("movementrefundbatch"=>$temp));
	close();
	exit();
}

?>