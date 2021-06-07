<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag=true;
if(isset($_REQUEST['pid']) && $_REQUEST['pid']!=''&&isset($_REQUEST['pname']) && $_REQUEST['pname']!='')
{
		$pid=$_REQUEST['pid'];
		$pname=$_REQUEST['pname'];
		$query="SELECT
				    order_status
				FROM
				    orders,
				    basket_order
				WHERE
				    promo_code = '".$pname."' 
				    AND 
				    order_id = basket_order_id
				";
		$query=query($query);
		confirm($query);
		$rows=mysqli_num_rows($query);
		//print_r($rows);
		if($rows>0)
		{
			$temp=array();
			$temp['response_code']=405;			// 405 means 'Not Allowed'
			$temp['response_desc']="Deletion is not allowed , since orders were booked on this PromoCode.Please deactivate the PromoCode.";
			echo json_encode(array("promos"=>$temp));
		}
		else
		{
			$query=query("
							DELETE
							FROM
							    promocodes
							WHERE
							    id = '".$pid."'
						");
			$result=confirm($query);
			if(!$result)
			{
				$flag=false;
				rollback();
				$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("promos"=>$temp));
				close();
				exit();
			}
			if($flag)
			{
				commit();
				$temp=array();
				$temp['response_code']=200;
				$temp['response_desc']="success";
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
