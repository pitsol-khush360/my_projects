<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['pid']))
{
	$pid=$_REQUEST['pid'];
	$sid=$_REQUEST['sid'];
 	$promo=$_REQUEST['promo_code'];
 	$new_promo = $_REQUEST['new_promo_code'];
 	$valid=$_REQUEST['valid_till'];
 	$moa=$_REQUEST['minimum_order_amount'];
	$discounttype=$_REQUEST['discount_type'];
	$discountvalue=$_REQUEST['discount_value'];
	
	if($_REQUEST['discount_type']=='None')
	{
		$discountvalue=0;
	}
	$row=0;
	if($new_promo != $promo)
	{
		$query="
				SELECT
				    *
				FROM
				    promocodes
				WHERE
				    seller_id = '".$sid."' 
				    AND 
				    id != '".$pid."' 
				    AND 
				    promo_code = '".$new_promo."'
				";
		//echo $query;
	 	$query=query($query);
		//confirm($check);
		//echo("select * from promocodes where seller_id='".$sid."'and id !='".$pid."' and promo_code='".$promo."'");
		$row=mysqli_num_rows($query);
		$promo = $new_promo;
	}
	//echo $row;
	if($row==0)
	{
	 	
	 	$query=query("
	 					UPDATE
						    promocodes
						SET
						    promo_code = '".$promo."',
						    expiry_date = '".$valid."',
						    minimum_order_amount = '".$moa."',
						    discount_type = '".$discounttype."',
						    discount_value = '".$discountvalue."',
						    updated_datetime = NOW()
						WHERE
						    seller_id = '".$sid."' 
						    AND 
						    id = '".$pid."'
	 				");
	 	$result=confirm($query);
		if(!$result)
		{
			$flag=false;
		}
		if($flag)
		{	
			commit();
			$temp=array();
			$temp['response_code']=200;
			$temp['response_desc']="Success";
	 		echo json_encode(array("promos"=>$temp));
	 		close();
	 		exit();
	 	}
	 	else
	 	{
	 		rollback();
	 		$temp=array();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("promos"=>$temp));
			close();
			exit();
	 	}
	}
	else
	{
		$temp=array();

		$temp['response_code']=405;			
		$temp['response_desc']="Promocode Already exists with same name.";
		echo json_encode(array("promos"=>$temp));
		close();
		exit();
	}
}
else
{
	$temp=array();

	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("promos"=>$temp));
	close();
	exit();
}
close();
?>
