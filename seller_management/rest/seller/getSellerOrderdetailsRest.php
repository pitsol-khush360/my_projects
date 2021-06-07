<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!='')
{		
	if(isset($_REQUEST['start']))
		$start=$_REQUEST['start'];
	else
		$start=0;

	if(isset($_REQUEST['end']))
		$end=$_REQUEST['end'];
	else
		$end=10;

	$user_id=$_REQUEST['user_id'];
	$query='';
	//print_r($_REQUEST);
	if(isset($_REQUEST['order_date']) && $_REQUEST['order_date']!='' && isset($_REQUEST['order_status']) && $_REQUEST['order_status']=='')
	{
		$query="
				SELECT
				    basket_order_id,
				    order_type,
				    order_date,
				    customer_name,
				    total_items,
				    net_amount,
				    order_status
				FROM
				    basket_order
				WHERE
				    seller_id = '".$user_id."' 
				    AND 
				    order_date= '".$_REQUEST['order_date']."' 
				    AND 
				    order_status NOT IN('Draft', 'Deleted')
				ORDER BY
				    id
				DESC
    			";

	}
	else if(isset($_REQUEST['order_status']) && $_REQUEST['order_status']!='' && isset($_REQUEST['order_date']) && $_REQUEST['order_date']!='')
	{
		$query="
				SELECT
				    basket_order_id,
				    order_type,
				    order_date,
				    customer_name,
				    total_items,
				    net_amount,
				    order_status
				FROM
				    basket_order
				WHERE
				    seller_id = '".$user_id."' 
				    AND 
				    order_date = '".$_REQUEST['order_date']."' 
				    AND
				    order_status = '".$_REQUEST['order_status']."' 
				    AND 
				    order_status NOT IN('Draft', 'Deleted')
				ORDER BY
				    id
				DESC
    			";
	}
	else if(isset($_REQUEST['order_status']) && $_REQUEST['order_status']!='' && isset($_REQUEST['order_date'])==false )
	{
		$query="
				SELECT
				    basket_order_id,
				    order_type,
				    order_date,
				    customer_name,
				    total_items,
				    net_amount,
				    order_status
				FROM
				    basket_order
				WHERE
				    seller_id = '".$user_id."' 
				    AND 
				    order_status = '".$_REQUEST['order_status']."' 
				    AND 
				    order_status NOT IN('Draft', 'Deleted')
				ORDER BY
				    id
				DESC
    			";
	}
	else if(isset($_REQUEST['order_status'])==false && isset($_REQUEST['order_date']) && $_REQUEST['order_date']!='')
	{
		$query="
				SELECT
				    basket_order_id,
				    order_type,
				    order_date,
				    customer_name,
				    total_items,
				    net_amount,
				    order_status
				FROM
				    basket_order
				WHERE
				    seller_id = '".$user_id."' 
				    AND 
				    order_date = '".$_REQUEST['order_date']."' 
				    AND
				    order_status NOT IN('Draft', 'Deleted')
				ORDER BY
				    id
				DESC
    			";
	}
	else if(isset($_REQUEST['order_id']) && $_REQUEST['order_id']!='')
	{
		$query="
				SELECT
				    basket_order_id,
				    order_type,
				    order_date,
				    customer_name,
				    total_items,
				    net_amount,
				    order_status
				FROM
				    basket_order
				WHERE
				    seller_id = '".$user_id."' 
				    AND 
				    basket_order_id = '".$_REQUEST['order_id']."' 
				    AND 
				    order_status NOT IN('Draft', 'Deleted')
				ORDER BY
				    id
				DESC
			    ";
	}
	
	else if(isset($_REQUEST['order_type']) && $_REQUEST['order_type']!='')
	{
		$query="
				SELECT
				    basket_order_id,
				    order_type,
				    order_date,
				    customer_name,
				    total_items,
				    net_amount,
				    order_status
				FROM
				    basket_order
				WHERE
				    seller_id = '".$user_id."' 
				    AND 
				    order_type = '".$_REQUEST['order_type']."' 
				    AND 
				    order_status NOT IN('Draft', 'Deleted')
				ORDER BY
				    id
				DESC
    
				";
	}
	else if(isset($_REQUEST['customer_name']) && $_REQUEST['customer_name']!='')
	{
		$query="
				SELECT
				    basket_order_id,
				    order_type,
				    order_date,
				    customer_name,
				    total_items,
				    net_amount,
				    order_status
				FROM
				    basket_order
				WHERE
				    seller_id = '".$user_id."' 
				    AND 
				    customer_name LIKE '%".$_REQUEST['customer_name']."%'
				    AND 
				    order_status NOT IN('Draft', 'Deleted')
				ORDER BY
				    id
				DESC
    
				";
	}
	else if(isset($_REQUEST['customer_mobile']) && $_REQUEST['customer_mobile']!='')
	{
		$query="
				SELECT
				    basket_order_id,
				    order_type,
				    order_date,
				    customer_name,
				    total_items,
				    net_amount,
				    order_status
				FROM
				    basket_order
				WHERE
				    seller_id = '".$user_id."' 
				    AND 
				    customer_mobile = '".$_REQUEST['customer_mobile']."' 
				    AND 
				    order_status NOT IN('Draft', 'Deleted')
				ORDER BY
				    id
				DESC
    
				";
	}
	else
	{
		$query="
				SELECT
				    basket_order_id,
				    order_type,
				    order_date,
				    customer_name,
				    total_items,
				    net_amount,
				    order_status
				FROM
				    basket_order
				WHERE
				    seller_id = '".$user_id."' 
				    AND 
				    order_status NOT IN('Draft', 'Deleted')
				ORDER BY
				    id
				DESC
				LIMIT ".$start.", ".$end."
				";
	}
	//echo $query;
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
		$temp['response_desc']="Success";
		$temp['rows']=$rows;
 		echo json_encode(array("getorders"=>$temp));
 		close();
 		exit();
 	}
 	else
 	{
 		$temp['response_code']=405;
		$temp['response_desc']="No Records Found";
		$temp['rows']=$rows;
 		echo json_encode(array("getorders"=>$temp));
 		close();
 		exit();
 	}		
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("getorders"=>$temp));
	close();
	exit();
}
close();
?>
