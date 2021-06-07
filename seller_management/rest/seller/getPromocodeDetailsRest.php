<?php
header("Content-Type:application/json");
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['user_id'])&&isset($_REQUEST['promocode'])&&$_REQUEST['user_id']!=''&&$_REQUEST['promocode']!='')
{
	$query="
			SELECT
			    promo_code,
			    minimum_order_amount,
			    discount_type,
			    discount_value,
			    is_active,
			    expiry_date
			FROM
			    promocodes
			WHERE
			    seller_id = '".$_REQUEST['user_id']."' 
			    AND 
			    promo_code = '".$_REQUEST['promocode']."'
			";
	$query=query($query);
	confirm($query);
	$rows=mysqli_num_rows($query);
	$row=fetch_array($query);
	if($rows==0)
	{
		$temp=array();
		$temp['response_code']=405;
		$temp['response_desc']="No Records Found";
		echo json_encode(array("getpromocode"=>$temp));
		close();
		exit();
	}
	if($rows>0)
	{
		if($row['expiry_date']>=date("Y-m-d")&&$row['is_active']=='Yes')
		{
			$temp=array();
			$temp[]=$row;
			$temp['response_code']=200;
			$temp['response_desc']="Sucess";
			$temp['rows']=$rows;
			echo json_encode(array("getpromocode"=>$temp));
		}
		if($row['expiry_date']>=date("Y-m-d")&&$row['is_active']=='No')
		{
			$temp=array();
			$temp['response_code']=405;
			$temp['rows']=$rows;
			$temp['response_desc']="Promo Code cannot be applied since it is not available anymore";
			echo json_encode(array("getpromocode"=>$temp));
			close();
 			exit();
		}
		if($row['expiry_date']<date("Y-m-d"))
		{
			$temp=array();
			$temp['response_code']=405;
			$temp['rows']=$rows;
			$temp['response_desc']="Promo Code cannot be applied since its Expired";
			echo json_encode(array("getpromocode"=>$temp));
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
	echo json_encode(array("getpromocode"=>$temp));
}
?>