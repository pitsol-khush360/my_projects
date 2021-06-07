<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!='')
{		
	

	$user_id=$_REQUEST['user_id'];
	$query='';
	if(isset($_REQUEST['customer_name']) && $_REQUEST['customer_name']!='')
	{
			$query="
					SELECT
					    SUM(net_amount) AS amount,
					    customer_name,
					    customer_mobile
					FROM
					    basket_order
					WHERE
					    seller_id = '".$user_id."' 
					    AND 
					    customer_name = '".$_REQUEST['customer_name']."'
					GROUP BY
					    customer_name,
					    customer_mobile
					ORDER BY
					    id
					DESC
    
					";
	}
	else
	{
			$query="
					SELECT
					    SUM(net_amount) AS amount,
					    customer_name,
					    customer_mobile
					FROM
					    basket_order
					WHERE
					    seller_id = '".$user_id."'
					GROUP BY
					    customer_name,
					    customer_mobile
					ORDER BY
					    id
					DESC
    
					";
	}
	
	$query=query($query);
	confirm($query);
	$rows=mysqli_num_rows($query);
	if($rows>0)
	{
		$temp=array();
		while($row=fetch_array($query))
		{
			$temp[]=$row;
		}
		$temp['response_code']=200;
		$temp['response_desc']="success";
		$temp['rows']=$rows;
 		echo json_encode(array("getsellercustomers"=>$temp));
 		close();
		exit();
 	}
 	else
 	{
 		$temp['response_code']=405;
		$temp['response_desc']="No Records Found";
		$temp['rows']=$rows;
 		echo json_encode(array("getsellercustomers"=>$temp));
 		close();
		exit();
 	}		
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("getsellercustomers"=>$temp));
	close();
	exit();
}
close();
?>
