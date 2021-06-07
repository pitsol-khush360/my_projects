<?php
header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['amount'])&&$_REQUEST['amount']!=''&&isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='')
{	
	
	
	$query="INSERT INTO `shipping_charges`(
			    seller_id, 
			    shipping_amount)
			VALUES(
					'".$_REQUEST['user_id']"',
					'".$_REQUEST['amount']"'
				)
			";
		//echo $query;
	$query=query($query);
	
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
 		echo json_encode(array("shippingcharges"=>$temp));

	}
	else
	{
		rollback();
		$temp=array();
		$temp['response_code']=405;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("shippingcharges"=>$temp));
	}
	
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("shippingcharges"=>$temp));
}
close();
?>
