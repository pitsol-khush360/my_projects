<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['order_id']) && $_REQUEST['order_id']!='')
{
	
		$query="
				SELECT
				    U.username,
				    U.user_id,
				    SD.seller_image,
				    U.role,
				    U.mobile,
				    U.business_name
				FROM
				    users U,
				    seller_details SD,
				    wallet_order WO
				WHERE
					WO.order_id = '".$_REQUEST['order_id']."'
					AND
				    SD.seller_id = U.user_id
				    AND 
				    WO.seller_id = U.user_id
				    AND
				    WO.seller_id = SD.seller_id
				";
	//echo $query;
	$query=query($query);
	confirm($query);
	$temp=array();
	$rows=mysqli_num_rows($query);
	while($row=fetch_array($query))
	{
	$temp[]=$row;
	}
	
	if($rows>0)
	{
		
		
		$temp['response_code']=200;			
		$temp['response_desc']="success";
		echo json_encode(array("getvalues"=>$temp));
		close();
		exit();
	}
	else
	{
		
		$temp['response_code']=405;			
		$temp['response_desc']="No Results Found";
		echo json_encode(array("getvalues"=>$temp));
		close();
		exit();
	}
}
else
{
	$temp=array();
	$temp['response_code']=400;			
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("getvalues"=>$temp));
	close();
	exit();
}
close();
?>
