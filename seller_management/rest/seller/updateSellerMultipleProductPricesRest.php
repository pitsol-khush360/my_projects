<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/email_functions.php');
$array = unserialize($_POST['data']);
$connection->autocommit(FALSE);
$flag=true;
if(isset($array['user_id'])&&$array['user_id']!='')
{	
	
	$query="
		SELECT
		    discount_type,
		    discount
		FROM
		    product_details
		WHERE
		    product_seller_id = '".$array[$i]['user_id']."' 
		    AND 
		    product_id = '".$product_id."'
		";
	$query=query($query);
	confirm($query);
	$row=fetch_array($query);
	$productofferprice=0;
	for($i=0;$i<sizeof($array)-1;$i++)
	{
		$productofferprice=0;
		if($row['discount_type']=='None')
		{
			$productofferprice=$array[$i]['product_price'];
		}
		else if($row['discount_type']=='Flat')
		{
			$productofferprice=$array[$i]['product_price']-$row['discount'];
		}
		else if($row['discount_type']=='Percentage')
		{
			$productofferprice=$array[$i]['product_price']-(($array[$i]['product_price']/100)*$row['discount']);

		}
	
		$query="
				UPDATE
				    product_details
				SET
				    updatedby = '".$array[$i]['user_id']."',    
				    product_price = '".$array[$i]['product_price']."',
				    product_offer_price = '".$productofferprice."',
				    product_price_currency = 'INR',
				    product_modification_datetime = NOW()
				WHERE
				    product_id = '".$product_id."'
				    AND
				    product_catalogue_id ='".$array[$i]['product_catalogue_id']."'
				    AND
				    product_seller_id = '".$array[$i]['user_id']."'
				";
		$query=query($query);
		$result=confirm($query);
		if(!$result)
		{
			$flag=false;
		}
	}
	if($flag)
	{	
		commit();
		$temp=array();
		$temp['response_code']=200;
		$temp['response_desc']="Success";
 		echo json_encode(array("updateproduct"=>$temp));
 		close();
 		exit();
 	}
 	else
 	{
 		rollback();
 		$temp=array();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("updateproduct"=>$temp));
		close();
 		exit();
 	}
	}

}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("updateproduct"=>$temp));
	close();
	exit();
}
close();	
?>
