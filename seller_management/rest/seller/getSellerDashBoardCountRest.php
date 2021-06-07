
<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='')
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

	$user_id=$_REQUEST['user_id'];

	$query="
			SELECT
			    count(*) as count
			FROM
			    basket_order
			WHERE
			    seller_id = '".$user_id."' 
			    AND 
			    order_status = 'deleted' 
			    AND 
			    order_date ='".$date."'
			ORDER BY
			    id
			DESC
    		";
   	//echo $query;
	$query=query($query);
	confirm($query);
	$row = fetch_array($query);
	$deleted=$row['count'];

	$query="
			SELECT
			    count(*) as count
			FROM
			    basket_order
			WHERE
			    seller_id = '".$user_id."' 
			    AND 
			    order_status = 'Pending' 
			    AND 
			    order_date ='".$date."'
			ORDER BY
			    id
			DESC
			";
	$query=query($query);
	confirm($query);
	$row = fetch_array($query);
	$pending=$row['count'];

	$query="
			SELECT
			    count(*) as count
			FROM
			    basket_order
			WHERE
			    seller_id = '".$user_id."' 
			    AND 
			    order_status = 'Accepted' 
			    AND 
			    order_date ='".$date."'
			ORDER BY
			    id
			DESC
			    ";
	$query=query($query);
	confirm($query);
	$row = fetch_array($query);
	$accepted=$row['count'];
	
	$query="
			SELECT
			    count(*) as count
			FROM
			    basket_order
			WHERE
			    seller_id = '".$user_id."' 
			    AND 
			    order_status = 'Cancelled' 
			    AND 
			    order_date ='".$date."'
			ORDER BY
			    id
			DESC
    
			";
	$query=query($query);
	confirm($query);
	$row = fetch_array($query);
	$cancelled=$row['count'];

	$query="SELECT
			    count(*) as count
			FROM
			    basket_order
			WHERE
			    seller_id = '".$user_id."' 
			    AND 
			    order_status = 'Shipped' 
			    AND 
			    order_date ='".$date."'
			ORDER BY
			    id
			DESC
    		";
	$query=query($query);
	confirm($query);
	$row = fetch_array($query);
	$shipped=$row['count'];

	$query="
			SELECT
			    count(*) as count
			FROM
			    basket_order
			WHERE
			    seller_id = '".$user_id."' 
			    AND 
			    order_status = 'Delivered' 
			    AND 
			    order_date ='".$date."'
			ORDER BY
			    id
			DESC
    		";
	$query=query($query);
	confirm($query);
	$row = fetch_array($query);
	$delivered=$row['count'];

	$query="
			SELECT
			    count(*) as count
			FROM
			    product_details
			WHERE
			    product_seller_id = '".$user_id."'
			";
	$query=query($query);
	confirm($query);
	$row = fetch_array($query);
	$products=$row['count'];

	$query="
			SELECT
			    count(*) as count
			FROM
			    product_catalogue
			WHERE
			    catalogue_seller_id = '".$user_id."'
			";
	$query=query($query);
	confirm($query);
	$row = fetch_array($query);
	$catalogues=$row['count'];

	$query="
			SELECT
			    SUM(net_amount) AS totalsales
			FROM
			    basket_order
			WHERE
			    seller_id = '".$user_id."' 
			    AND 
			    order_date ='".$date."'
			    AND
			    order_status = 'Delivered'
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
			    seller_id = '".$user_id."' 
			    AND
				status = '1'
			";
	$query=query($query);
	$open=mysqli_num_rows($query);

	$query="
			SELECT 
				count(*) as count
			FROM 
				tickets 
			WHERE 
				seller_id='".$user_id."'  
				AND 
				status='4'
			";
	$query=query($query);
	$row = fetch_array($query);
	$reopen=$row['count'];

	$temp=array();			
	$temp['deleted']=$deleted;
	$temp['pending']=$pending;
	$temp['accepted']=$accepted;
	$temp['cancelled']=$cancelled;
	$temp['shipped']=$shipped;
	$temp['delivered']=$delivered;
	$temp['products']=$products;
	$temp['catalogues']=$catalogues;
	$temp['total_sales']=$total_sales;
	$temp['open']=$open;
	$temp['reopen']=$reopen;
	$temp['response_code']=200;
	$temp['response_desc']="success";
 	echo json_encode(array("getdashboard"=>$temp));
 	close();
 	exit();
}	
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("getdashboard"=>$temp));
	close();
	exit();
}
close();
?>
