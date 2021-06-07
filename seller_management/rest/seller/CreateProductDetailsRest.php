<?php
header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='')
{	
	if(isset($_REQUEST['product_catalogue_id'])&&$_REQUEST['product_catalogue_id']!=''&&$_REQUEST['product_name']!=''&&$_REQUEST['product_price']!=''&&$_REQUEST['product_unit']!=''&&$_REQUEST['product_price_currency']!='')
	{
		$product_name=$_REQUEST['product_name'];
		//$productimage="defaultpic.jpg";
		$productimage='';
		$check_status='';
		if($_REQUEST['imagestatus']=='1')
		{
			$data=$_REQUEST['image'];
			$date=date('Ymd').rand(10000,100000);
			$name = preg_replace('/[^A-Za-z0-9\-]/', '',$_REQUEST['product_name']);
			$productimage="/images/products/".$name.$_REQUEST['user_id'].$date.'.png';
			$productimage1=UPLOAD_DIRECTORY.$productimage;
			$check_status=imageupload($data,$productimage1);
		}
		else
		{
			$productimage="/images/products/defaultpic.jpg";
		}
		
		if($check_status=="Invalid")
		{
			$productimage="/images/products/defaultpic.jpg";
		}
		$query="
				SELECT
				    *
				FROM
				    product_default_settings
				WHERE
				    seller_id = '".$_REQUEST['user_id']."'";
		$query=query($query);
		confirm($query);
		$row=fetch_array($query);
		$productofferprice=0;
		if($row['discount_type']=='None')
		{
			$productofferprice=$_REQUEST['product_price'];
		}
		else if($row['discount_type']=='Flat')
		{
			$productofferprice=$_REQUEST['product_price']-$row['discount_percent'];
		}
		else if($row['discount_type']=='Percentage')
		{
			$productofferprice=$_REQUEST['product_price']-(($_REQUEST['product_price']/100)*$row['discount_percent']);

		}
		//print_r($row);
		$query='INSERT INTO product_details(
					    updatedby,
					    product_catalogue_id,
					    product_seller_id,
					    product_name,
					    product_price,
					    product_offer_price,
					    product_unit,
					    product_price_currency,
					    product_description,
					    product_creation_datetime,
					    product_modification_datetime,
					    productimage,
					    discount_type,
					    discount,
					    tax_type,
					    tax_percent,
					    free_shipping,
					    return_available,
					    cash_on_delivery,
					    warrant_type,
					    warranty_duration,
					    warranty_days_mon_yr
				)
				VALUES(';
						$query.='"'.$_REQUEST['user_id'].'",'
						;
						$query.='"'.$_REQUEST['product_catalogue_id'].'",'
						;
						$query.='"'.$_REQUEST['user_id'].'",'
						;
						$query.='"'.$_REQUEST['product_name'].'",'
						;
						$query.='"'.$_REQUEST['product_price'].'",'
						;
						$query.='"'.$productofferprice.'",'
						;
						$query.='"'.$_REQUEST['product_unit'].'",'
						;
						$query.='"'.$_REQUEST['product_price_currency'].'",'
						;
						$query.='"'.$_REQUEST['product_description'].'",'
						;
						$query.='NOW(),'
						;
						$query.='NOW(),'
						;
						$query.='"'.$productimage.'",'
						;
						$query.='"'.$row['discount_type'].'",'
						;
						$query.='"'.$row['discount_percent'].'",'
						;
						$query.='"'.$row['tax_type'].'",'
						;
						$query.='"'.$row['tax_percent'].'",'
						;
						$query.='"'.$row['free_shipping'].'",'
						;
						$query.='"'.$row['return_available'].'",'
						;
						$query.='"'.$row['cash_on_delivery'].'",'
						;
						$query.='"'.$row['warrant_type'].'",'
						;
						$query.='"'.$row['warrant_duration'].'",'
						;
						$query.='"'.$row['warranty_days_mon_yr'].'"
				)
				';
		//echo $query;
		$query=query($query);

		$result=confirm($query);
		if(!$result)
		{
			$flag=false;
			rollback();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
		 	echo json_encode(array("createproduct"=>$temp));
		 	close();
	 		exit();

		}
		//echo $result;
		if($flag)
		{
			commit();
			$temp=array();
			$temp['response_code']=200;
			$temp['response_desc']="Success";
			echo json_encode(array("createproduct"=>$temp));
			close();
	 		exit();
		}
		else
		{
			rollback();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
		 	echo json_encode(array("createproduct"=>$temp));
		 	close();
	 		exit();
		}
		
	}
	else
	{
		$temp['response_code']=400;
		$temp['response_desc']="Invalid Request";
	 	echo json_encode(array("createproduct"=>$temp));
	 	close();
	 	exit();
	}
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
 	echo json_encode(array("createproduct"=>$temp));
 	close();
 	exit();
}
close();
?>
