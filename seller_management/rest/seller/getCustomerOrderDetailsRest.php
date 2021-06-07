<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['user_name']) && $_REQUEST['user_name']!=''&&isset($_REQUEST['customer_mobile']) && $_REQUEST['customer_mobile']!='')
{		
	

	$user_name=$_REQUEST['user_name'];
	$query=query("
					SELECT
						US.status,
					    US.user_id
					FROM
					    users US
					WHERE
					    US.username = '".$user_name."' 
				");
	confirm($query);
	$user_id='';
	$status='';
	while($row1=fetch_array($query))
	{
		$user_id=$row1['user_id'];
		$status=$row1['status'];
	}
	$query='';
	$sum=0;
	$discount=0;
	$delivery_charge=0;
	$total_amount=0;
	$order_id=0;
	$customer_name="";
	$customer_mobile="";
	$customer_email="";
	$order_date="";
	$order_type="";
	$payment_method="";
	$delivery_address1="";
	$delivery_address2="";
	$order_status="";
	$city='';
	$state='';
	$pincode='';
	$country='';
	$product_price='';
	if(isset($_REQUEST['customer_mobile']) && $_REQUEST['customer_mobile']!='' &&isset($_REQUEST['order_id']) && $_REQUEST['order_id']!='')
	{
		$query="		
		SELECT 
			OD.order_id,
			OD.order_date,
			OD.product_price,
			BO.discount,
			OD.order_amount_total,
			OD.order_quantity,
			BO.customer_mobile,
			BO.customer_email,
			BO.delivery_charge,
			BO.order_type,
			BO.customer_name,
			BO.delivery_address1,
			BO.delivery_address2,
			BO.city,
			BO.state,
			BO.country,
			BO.pincode,
			BO.payment_method,
			BO.total_items,
			BO.net_amount,
			BO.order_status, 
			PD.product_name,
			PD.productimage
		 
		FROM 
			orders OD,
			basket_order BO,
			product_details  PD
		WHERE 
			BO.seller_id='".$user_id."' 
			AND
			OD.seller_id='".$user_id."' 
			AND
			PD.product_seller_id='".$user_id."' 
			AND 
			BO.basket_order_id='".$_REQUEST['order_id']."' 
			AND
			OD.order_id='".$_REQUEST['order_id']."' 
			AND 
			BO.basket_order_id=OD.order_id 
			AND  
			OD.product_id=PD.product_id
			AND
			OD.catalogue_id=PD.product_catalogue_id
			AND
			BO.order_status!='Deleted' 
			AND 
			BO.customer_mobile = '".$_REQUEST['customer_mobile']."'
			
			ORDER BY
			    BO.id
			DESC
		";
		$query=query($query);
		confirm($query);
		$rows=mysqli_num_rows($query);
		if($rows>0)
		{
			$temp=array();
			while($row=fetch_array($query))
			{
				if($row['product_price']==NULL || $row['product_price']=='')
				{
					$row['product_price']=$row['order_amount_total']/$row['order_quantity'];
				}
				$sum+=$row['order_amount_total'];
				$discount=$row['discount'];
				$delivery_charge=$row['delivery_charge'];
				$total_amount=$row['net_amount'];

				$order_id=$row['order_id'];
				$customer_name=$row['customer_name'];
				$customer_mobile=$row['customer_mobile'];
				$customer_email=$row['customer_email'];
				$order_date=$row['order_date'];
				$order_type=$row['order_type'];
				$payment_method=$row['payment_method'];
				$delivery_address1=$row['delivery_address1'];
				$delivery_address2=$row['delivery_address2'];
				$city=$row['city'];
				$state=$row['state'];
				$pincode=$row['pincode'];
				$country=$row['country'];
				$order_status=$row['order_status'];
				$temp[]=$row;
			}

			$temp['item_total']=$sum;
			$temp['discount']=$discount;
			$temp['delivery_charge']=$delivery_charge;
			$temp['total_amount'] =$total_amount;

			$temp['order_id']=$order_id;
			$temp['customer_name']=$customer_name;
			$temp['customer_mobile']=$customer_mobile;
			$temp['customer_email']=$customer_email;
			$temp['order_date']=$order_date;
			$temp['order_type']=$order_type;
			$temp['payment_method']=$payment_method;
			$temp['delivery_address']=$delivery_address1;
			$temp['delivery_address1']=$delivery_address1;
			$temp['delivery_address2']=$delivery_address2;
			$temp['order_status']=$order_status;
			$temp['city']=$city;
			$temp['state']=$state;
			$temp['pincode']=$pincode;
			$temp['country']=$country;
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
	else if(isset($_REQUEST['customer_mobile']) && $_REQUEST['customer_mobile']!='')
	{
		$query="
				SELECT
				    basket_order_id as order_id,
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
				";
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
