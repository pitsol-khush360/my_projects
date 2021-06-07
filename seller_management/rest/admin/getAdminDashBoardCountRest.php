<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

if(true)
{	
	$date="";
	if(isset($_REQUEST['date'])&&$_REQUEST['date']!='')
	{
		$date = $_REQUEST['date'];
	}
	else
	{
		$date = date("y-m-d");
	}	


	$query="SELECT 
					COUNT(id) AS total
			FROM 
					seller_details
					 ";
	
	$query=query($query);
	confirm($query);
	$total_seller=0;
	while($row=fetch_array($query))
	{
		$total_seller=$row['total'];
	}
	if(!$total_seller)
	{
		$total_seller=0;
	}

	$query="
			SELECT
			    *
			FROM
			    basket_order
			WHERE 
			    order_status = 'deleted' 
			    AND 
			    order_date LIKE '%".$date."%'
			ORDER BY
			    id
			DESC
    		";
	$query=query($query);
	confirm($query);
	$deleted=mysqli_num_rows($query);

	$query="
			SELECT
			    *
			FROM
			    basket_order
			WHERE 
			    order_status = 'Pending' 
			    AND 
			    order_date LIKE '%".$date."%'
			ORDER BY
			    id
			DESC
			";
	$query=query($query);
	confirm($query);
	$pending=mysqli_num_rows($query);

	$query="
			SELECT
			    *
			FROM
			    basket_order
			WHERE
			    order_status = 'Accepted' 
			    AND 
			    order_date LIKE '%".$date."%'
			ORDER BY
			    id
			DESC
			    ";
	$query=query($query);
	confirm($query);
	$accepted=mysqli_num_rows($query);
	$query="
			SELECT
			    *
			FROM
			    basket_order
			WHERE
			    order_status = 'Cancelled' 
			    AND 
			    order_date LIKE '%".$date."%'
			ORDER BY
			    id
			DESC
    
			";
	$query=query($query);
	confirm($query);
	$cancelled=mysqli_num_rows($query);

	$query="SELECT
			    *
			FROM
			    basket_order
			WHERE 
			    order_status = 'Shipped' 
			    AND 
			    order_date LIKE '%".$date."%'
			ORDER BY
			    id
			DESC
    		";
	$query=query($query);
	confirm($query);
	$shipped=mysqli_num_rows($query);

	$query="
			SELECT
			    *
			FROM
			    basket_order
			WHERE 
			    order_status = 'Delivered' 
			    AND 
			    order_date LIKE '%".$date."%'
				ORDER BY
			    	id
				DESC
    			";
	$query=query($query);
	confirm($query);
	$delivered=mysqli_num_rows($query);

	$query="
			SELECT
			    	COUNT(product_id) AS total
			FROM
			    	product_details  
			    	";
	$query=query($query);
	confirm($query);
	$total_product=0;
	while($row=fetch_array($query))
	{
		$total_product=$row['total'];
	}
	if(!$total_product)
	{
		$total_product=0;
	}

	$query="
			SELECT
			    *
			FROM
			    product_catalogue
		
			    ";
	$query=query($query);
	confirm($query);
	$catalogues=mysqli_num_rows($query);

	$query="
			SELECT
			    SUM(net_amount) AS totalsales
			FROM
			    basket_order
			WHERE
			    order_date LIKE '%".$date."%'
				ORDER BY
			    id
				DESC
				";
	$query=query($query);
	confirm($query);
	$total_sales=0;
	while($row=fetch_array($query))
	{
		$total_sales=$row['totalsales'];
	}
	if(!$total_sales)
	{
		$total_sales=0;
	}

	$query="
			SELECT
				status
			FROM
			    tickets
			WHERE
				status = '1'
				";
	$query=query($query);
	$open=mysqli_num_rows($query);

	$query="
			SELECT 
				status 
			FROM 
				tickets 
			WHERE 
				status='4'
				";
	$query=query($query);
	$reopen=mysqli_num_rows($query);

	$temp=array();	
	$temp['total_seller']=$total_seller;	
	$temp['deleted']=$deleted;
	$temp['pending']=$pending;
	$temp['accepted']=$accepted;
	$temp['cancelled']=$cancelled;
	$temp['shipped']=$shipped;
	$temp['delivered']=$delivered;
	$temp['products']=$total_product;
	$temp['catalogues']=$catalogues;
	$temp['total_sales']=$total_sales;
	$temp['open']=$open;
	$temp['reopen']=$reopen;
	$temp['response_code']=200;
	$temp['response_desc']="success";
 	echo json_encode(array("getdashboard"=>$temp));
}	
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("getdashboard"=>$temp));
}
close();
?>
