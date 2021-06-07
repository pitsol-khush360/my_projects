<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='')
{	

		$query="
				SELECT
				    `id`,
				    `seller_id`,
				    `product_category`,
				    `discount_type`,
				    `discount_percent`,
				    `tax_type`,
				    `tax_percent`,
				    `free_shipping`,
				    `return_available`,
				    `cash_on_delivery`,
				    `warrant_type`,
				    `warrant_duration`,
				    `warranty_days_mon_yr`
				FROM
				    product_default_settings
				WHERE
				    seller_id = '".$_REQUEST['user_id']."'
				";
		$query=query($query);
		confirm($query);
		$temp=array();
		while($row=fetch_array($query))
		{
			$temp[]=$row;
		}
		$temp['response_code']=200;
		$temp['response_desc']="Success";
 		echo json_encode(array("getproductdefault"=>$temp));
 		close();
 		exit();
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("getproductdefault"=>$temp));
	close();
	exit();
}
close();	
?>
