<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['sid']))
{
	$sid=$_REQUEST['sid'];
	
	$name="";
	if(isset($_REQUEST['start']))
		$start=$_REQUEST['start'];
	else
		$start=0;

	if(isset($_REQUEST['end']))
		$end=$_REQUEST['end'];
	else
		$end=10;
	if(isset($_REQUEST['name']))
		$name=$_REQUEST['name'];
	if(isset($_REQUEST['status']))
		$status=$_REQUEST['status'];
	$query='';
	if(isset($_REQUEST['pid']) && $_REQUEST['pid']!="")
	{
		$query=query("
						SELECT
						    PC.id,
						    PC.seller_id,
						    PC.minimum_order_amount,
						    PC.discount_type,
						    PC.discount_value,
						    PC.is_active,
						    PC.expiry_date,
						    PC.promo_code,
						    COUNT(BO.promo_code) AS promos_applied
						FROM
						    promocodes PC
						LEFT JOIN 
							basket_order BO
						ON 
							PC.promo_code = BO.promo_code
						WHERE
						    PC.seller_id = ".$sid." 
						    AND 
						    PC.id = '".$_REQUEST['pid']."'
						GROUP BY
						    PC.promo_code
						ORDER BY
							DATE(PC.expiry_date) >= DATE(NOW()) 
						DESC,
		    				PC.expiry_date 
		    			ASC
    				");
	}
	else if(isset($name) && $name!="")
	{
		$query=query("
						SELECT
						    PC.id,
						    PC.seller_id,
						    PC.minimum_order_amount,
						    PC.discount_type,
						    PC.discount_value,
						    PC.is_active,
						    PC.expiry_date,
						    PC.promo_code,
						    COUNT(BO.promo_code) AS promos_applied
						FROM
						    promocodes PC
						LEFT JOIN 
							basket_order BO
						ON 
							PC.promo_code = BO.promo_code
						WHERE
						    PC.seller_id = ".$sid." 
						    AND 
						    PC.promo_code LIKE '%".$name."%'
						GROUP BY
						    PC.promo_code
						ORDER BY
							DATE(PC.expiry_date) >= DATE(NOW()) 
						DESC,
		    				PC.expiry_date 
		    			ASC
    				");
	}
	else if(isset($status) && $status!="")
	{
		$query=query("
						SELECT
						    PC.id,
						    PC.seller_id,
						    PC.minimum_order_amount,
						    PC.discount_type,
						    PC.discount_value,
						    PC.is_active,
						    PC.expiry_date,
						    PC.promo_code,
						    COUNT(BO.promo_code) AS promos_applied
						FROM
						    promocodes PC
						LEFT JOIN 
							basket_order BO 
						ON
							 PC.promo_code = BO.promo_code
						WHERE
						    PC.seller_id = ".$sid." 
						    AND 
						    PC.is_active = '".$status."'
						GROUP BY
						    PC.promo_code
						ORDER BY
							DATE(PC.expiry_date) >= DATE(NOW()) 
						DESC,
		    				PC.expiry_date 
		    			ASC
						    
					");
	}
	else
	{
		$query="
				SELECT
				    PC.id,
				    PC.seller_id,
				    PC.minimum_order_amount,
				    PC.discount_type,
				    PC.discount_value,
				    PC.is_active,
				    PC.expiry_date,
				    PC.promo_code,
				    COUNT(BO.promo_code) AS promos_applied
				FROM
				    promocodes PC
				LEFT JOIN 
					basket_order BO
				ON 
					PC.promo_code = BO.promo_code
				WHERE
				    PC.seller_id = ".$sid."
				GROUP BY
				    PC.promo_code
				ORDER BY
					DATE(PC.expiry_date) >= DATE(NOW()) 
				DESC,
    				PC.expiry_date 
    			ASC

				LIMIT ".$start.", ".$end."
				";
		//echo $query;
		$query=query($query);
	}
	
	confirm($query);
	$rows=0;
	if(mysqli_num_rows($query)>0)		// Valid Request, Data Found.
	{
		$temp=array();
		while($row=fetch_array($query))
		{
			
			$row['expiry_date']=date('d-M-Y',strtotime($row['expiry_date']));
			$temp[]=$row;
		}
		$temp['response_code']=200;
		$temp['response_desc']="Success";
		$temp['rows']=mysqli_num_rows($query);
		//print_r($temp);
 		echo json_encode(array("promos"=>$temp));
 		close();
 		exit();
 	}
 	else
 	{
 		$temp=array();
		$temp['response_code']=405;
		$temp['response_desc']="No Records Found";
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
}
close();
?>
