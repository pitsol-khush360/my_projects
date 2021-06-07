<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['username'])&&$_REQUEST['username']!='')
{
	$rows=0;
	$start=0;
	$end=0;
	$query=query("
					SELECT
						US.status,
					    US.user_id,
					    US.business_name,
					    SD.seller_alternate_number,
					    SD.kyc_completed,
					    SD.accept_cod_payments,
					    SD.accept_online_payments
					FROM
					    users US,
					    seller_details SD
					WHERE
					    US.username = '".$_REQUEST['username']."' 
					    AND 
					    US.user_id = SD.seller_id
				");
	confirm($query);
	$userid='';
	$business_name='';
	$mobile='';
	$status='';
	$kyc_completed='';
	$accept_cod_payments='';
	$accept_online_payments='';
	while($row1=fetch_array($query))
	{
		$userid=$row1['user_id'];
		$business_name=$row1['business_name'];
		$mobile=$row1['seller_alternate_number'];
		$status=$row1['status'];
		$kyc_completed=$row1['kyc_completed'];
		$accept_cod_payments=$row1['accept_cod_payments'];
		$accept_online_payments=$row1['accept_online_payments'];
	}
	if($status!='A')
	{
		$temp=array();
		$temp['response_code']=405;
		$temp['response_desc']="This Collection Page is temporary disabled. Please contact product seller for additional information";
 		echo json_encode(array("getproducts"=>$temp));
 		close();
 		exit();

	}
	$row1=array();
	$query='';
	$query1='';
	if(isset($_REQUEST['cid'])&&$_REQUEST['cid']!='')
	{
		
		$query=query("
					SELECT
					    catalogue_id,
					    catalogue_name
					FROM
					    product_catalogue
					WHERE
					    catalogue_status = 'Active' 
					    AND 
					    catalogue_seller_id = '".$userid."' 
					    AND 
					    catalogue_id = '".$_REQUEST['cid']."'
				");
		confirm($query);
		$rows=mysqli_num_rows($query);

		if($rows!=0)	// Valid Request, Data Found.
		{
			$rows=0;
			$temp=array();
			while($row=fetch_array($query))
			{	
				$query1=query("
								SELECT
								    PD.product_id,
								    PD.product_seller_id,
								    PD.productimage,
								    PD.product_name,
								    PD.product_price,
								    PD.product_price_currency,
								    PD.product_category,
								    PD.product_sub_category,
								    PD.product_description,
								    PD.product_catalogue_id,
								    PD.warranty_duration,
								    PD.warranty_days_mon_yr,
								    PD.warrant_type,
								    PD.valid_from,
								    PD.product_model,
								    PD.product_offer_price,
								    PD.tax_type,
								    PD.tax_percent,
								    PD.free_shipping,
								    PD.cancellation_available,
								    PD.cash_on_delivery,
								    PD.return_available,
								    PD.product_inventory,
								    PD.product_unit,
								    PD.product_status,
								    PD.discount_type,
								    PD.discount,
								    DC.delivery_charge,
								    DC.delivery_free_above,
								    SD.accept_online_payments,
								    SD.accept_cod_payments,
								    SD.logistics_integrated
								FROM
								    seller_details SD
		                     	INNER JOIN 
		                        	product_details PD	 
		                        ON 
		                        	PD.product_seller_id = SD.seller_id
		                        LEFT OUTER JOIN 
		                        	delivery_charges DC
		                        ON 
		                         	SD.seller_id = DC.seller_id
								WHERE
								    PD.product_seller_id = '".$userid."' 
								    AND 
								    PD.product_catalogue_id = '".$row['catalogue_id']."' 
								    AND 
								    PD.product_status = 'Active' 
								    AND 
								    PD.product_price > 0 
								    AND 
								    PD.product_offer_price > 0
							");
				confirm($query1);
				

				while($row1=fetch_array($query1))
				{   $row1['cataloguename']=$row['catalogue_name'];
					$row1['catalogue_id']=$row1['product_catalogue_id'];
					$temp[]=$row1;
					$rows+=1;
				}

			}
		
		
			$temp['business_name']=$business_name;
			$temp['mobile'] =$mobile;
			$temp['user_id'] =$userid;
			$temp['kyc_completed']=$kyc_completed;
			$temp['accept_cod_payments']=$accept_cod_payments;
			$temp['accept_online_payments']=$accept_online_payments;
			$temp['rows']=$rows;
			$temp['response_code']=200;
			$temp['response_desc']="success";
			//print_r($temp);
	 		echo json_encode(array("getproducts"=>$temp));
	 		close();
	 		exit();

	 	}
		
	 	else
		{
			$temp['response_code']=405;
			$temp['response_desc']="No Results Found";
	 		echo json_encode(array("getproducts"=>$temp));
	 		close();
	 		exit();
		}
	}
	else if(isset($_REQUEST['pid'])&&$_REQUEST['pid']!='')
	{
		$query1=query("
						SELECT
						    PD.product_id,
						    PD.product_seller_id,
						    PD.productimage,
						    PD.product_name,
						    PD.product_price,
						    PD.product_price_currency,
						    PD.product_category,
						    PD.product_sub_category,
						    PD.product_description,
						    PD.product_catalogue_id,
						    PD.warranty_duration,
						    PD.warranty_days_mon_yr,
						    PD.warrant_type,
						    PD.valid_from,
						    PD.product_model,
						    PD.product_offer_price,
						    PD.tax_type,
						    PD.tax_percent,
						    PD.free_shipping,
						    PD.cancellation_available,
						    PD.cash_on_delivery,
						    PD.return_available,
						    PD.product_inventory,
						    PD.product_unit,
						    PD.product_status,
						    PD.discount_type,
						    PD.discount,
						    DC.delivery_charge,
						    DC.delivery_free_above,
						    SD.accept_online_payments,
						    SD.accept_cod_payments,
						    SD.logistics_integrated,
						    PC.catalogue_id,
						    PC.catalogue_name as cataloguename
						FROM
						    seller_details SD
                     	INNER JOIN 
                        	product_details PD	 

                        ON 
                        	PD.product_seller_id = SD.seller_id
                        INNER JOIN
                        	product_catalogue PC
                        ON
                        	PC.catalogue_id = PD.product_catalogue_id
                        LEFT OUTER JOIN 
                        	delivery_charges DC
                        ON 
                         	SD.seller_id = DC.seller_id
						WHERE
						    PD.product_seller_id = '".$userid."' 
						    AND 
						    PD.product_id = '".$_REQUEST['pid']."' 
						    AND 
						    PD.product_status = 'Active' 
						    AND 
						    PC.catalogue_status = 'Active'
						    AND
						    PD.product_price > 0 
						    AND 
						    PD.product_offer_price > 0

					");
			confirm($query1);
			$rows=mysqli_num_rows($query1);
			while($row1=fetch_array($query1))
			{   
				$row1['catalogue_id']=$row1['product_catalogue_id'];
				$temp[]=$row1;
				
			}
			
			$temp['business_name']=$business_name;
			$temp['kyc_completed']=$kyc_completed;
			$temp['accept_cod_payments']=$accept_cod_payments;
			$temp['accept_online_payments']=$accept_online_payments;
			$temp['mobile'] =$mobile;
			$temp['user_id'] =$userid;
			$temp['rows']=$rows;
			$temp['response_code']=200;
			$temp['response_desc']="success";
			echo json_encode(array("getproducts"=>$temp));
			close();
			exit();
	}
	else if(isset($_REQUEST['cname'])&&$_REQUEST['cname']=='' && isset($_REQUEST['pname'])&&$_REQUEST['pname']!='')
	{
		$query1=query("
						SELECT
						    PD.product_id,
						    PD.product_seller_id,
						    PD.productimage,
						    PD.product_name,
						    PD.product_price,
						    PD.product_price_currency,
						    PD.product_category,
						    PD.product_sub_category,
						    PD.product_description,
						    PD.product_catalogue_id,
						    PD.warranty_duration,
						    PD.warranty_days_mon_yr,
						    PD.warrant_type,
						    PD.valid_from,
						    PD.product_model,
						    PD.product_offer_price,
						    PD.tax_type,
						    PD.tax_percent,
						    PD.free_shipping,
						    PD.cancellation_available,
						    PD.cash_on_delivery,
						    PD.return_available,
						    PD.product_inventory,
						    PD.product_unit,
						    PD.product_status,
						    PD.discount_type,
						    PD.discount,
						    DC.delivery_charge,
						    DC.delivery_free_above,
						    SD.accept_online_payments,
						    SD.accept_cod_payments,
						    SD.logistics_integrated,
						    PC.catalogue_id,
						    PC.catalogue_name as cataloguename
						FROM
						    seller_details SD
                     	INNER JOIN 
                        	product_details PD	 

                        ON 
                        	PD.product_seller_id = SD.seller_id
                        INNER JOIN
                        	product_catalogue PC
                        ON
                        	PC.catalogue_id = PD.product_catalogue_id
                        LEFT OUTER JOIN 
                        	delivery_charges DC
                        ON 
                         	SD.seller_id = DC.seller_id
						WHERE
						    PD.product_seller_id = '".$userid."' 
						    AND 
						    PD.product_name LIKE '%".$_REQUEST['pname']."%' 
						    AND 
						    PD.product_status = 'Active' 
						    AND 
						    PC.catalogue_status = 'Active'
						    AND
						    PD.product_price > 0 
						    AND 
						    PD.product_offer_price > 0

					");
			confirm($query1);
			$rows=mysqli_num_rows($query1);
			while($row1=fetch_array($query1))
			{   
				$row1['catalogue_id']=$row1['product_catalogue_id'];
				$temp[]=$row1;
				
			}
			
			$temp['business_name']=$business_name;
			$temp['kyc_completed']=$kyc_completed;
			$temp['accept_cod_payments']=$accept_cod_payments;
			$temp['accept_online_payments']=$accept_online_payments;
			$temp['mobile'] =$mobile;
			$temp['user_id'] =$userid;
			$temp['rows']=$rows;
			$temp['response_code']=200;
			$temp['response_desc']="success";
			echo json_encode(array("getproducts"=>$temp));
			close();
			exit();
	}
	else if(isset($_REQUEST['cname'])&&$_REQUEST['cname']!='' && isset($_REQUEST['pname'])&&$_REQUEST['pname']=='')
	{
		$query1=query("
						SELECT
						    PD.product_id,
						    PD.product_seller_id,
						    PD.productimage,
						    PD.product_name,
						    PD.product_price,
						    PD.product_price_currency,
						    PD.product_category,
						    PD.product_sub_category,
						    PD.product_description,
						    PD.product_catalogue_id,
						    PD.warranty_duration,
						    PD.warranty_days_mon_yr,
						    PD.warrant_type,
						    PD.valid_from,
						    PD.product_model,
						    PD.product_offer_price,
						    PD.tax_type,
						    PD.tax_percent,
						    PD.free_shipping,
						    PD.cancellation_available,
						    PD.cash_on_delivery,
						    PD.return_available,
						    PD.product_inventory,
						    PD.product_unit,
						    PD.product_status,
						    PD.discount_type,
						    PD.discount,
						    DC.delivery_charge,
						    DC.delivery_free_above,
						    SD.accept_online_payments,
						    SD.accept_cod_payments,
						    SD.logistics_integrated,
						    PC.catalogue_id,
						    PC.catalogue_name as cataloguename
						FROM
						    seller_details SD
                     	INNER JOIN 
                        	product_details PD	 

                        ON 
                        	PD.product_seller_id = SD.seller_id
                        INNER JOIN
                        	product_catalogue PC
                        ON
                        	PC.catalogue_id = PD.product_catalogue_id
                        LEFT OUTER JOIN 
                        	delivery_charges DC
                        ON 
                         	SD.seller_id = DC.seller_id
						WHERE
						    PD.product_seller_id = '".$userid."' 
						    AND 
						    PC.	catalogue_Name LIKE '%".$_REQUEST['cname']."%' 
						    AND 
						    PD.product_status = 'Active' 
						    AND 
						    PC.catalogue_status = 'Active'
						    AND
						    PD.product_price > 0 
						    AND 
						    PD.product_offer_price > 0

					");
			confirm($query1);
			$rows=mysqli_num_rows($query1);
			while($row1=fetch_array($query1))
			{   
				$row1['catalogue_id']=$row1['product_catalogue_id'];
				$temp[]=$row1;
				
			}
			
			$temp['business_name']=$business_name;
			$temp['kyc_completed']=$kyc_completed;
			$temp['accept_cod_payments']=$accept_cod_payments;
			$temp['accept_online_payments']=$accept_online_payments;
			$temp['mobile'] =$mobile;
			$temp['user_id'] =$userid;
			$temp['rows']=$rows;
			$temp['response_code']=200;
			$temp['response_desc']="success";
			echo json_encode(array("getproducts"=>$temp));
			close();
			exit();
	}
	else if(isset($_REQUEST['cname'])&&$_REQUEST['cname']!='' && isset($_REQUEST['pname'])&&$_REQUEST['pname']!='')
	{
		$query1=query("
						SELECT
						    PD.product_id,
						    PD.product_seller_id,
						    PD.productimage,
						    PD.product_name,
						    PD.product_price,
						    PD.product_price_currency,
						    PD.product_category,
						    PD.product_sub_category,
						    PD.product_description,
						    PD.product_catalogue_id,
						    PD.warranty_duration,
						    PD.warranty_days_mon_yr,
						    PD.warrant_type,
						    PD.valid_from,
						    PD.product_model,
						    PD.product_offer_price,
						    PD.tax_type,
						    PD.tax_percent,
						    PD.free_shipping,
						    PD.cancellation_available,
						    PD.cash_on_delivery,
						    PD.return_available,
						    PD.product_inventory,
						    PD.product_unit,
						    PD.product_status,
						    PD.discount_type,
						    PD.discount,
						    DC.delivery_charge,
						    DC.delivery_free_above,
						    SD.accept_online_payments,
						    SD.accept_cod_payments,
						    SD.logistics_integrated,
						    PC.catalogue_id,
						    PC.catalogue_name as cataloguename
						FROM
						    seller_details SD
                     	INNER JOIN 
                        	product_details PD	 

                        ON 
                        	PD.product_seller_id = SD.seller_id
                        INNER JOIN
                        	product_catalogue PC
                        ON
                        	PC.catalogue_id = PD.product_catalogue_id
                        LEFT OUTER JOIN 
                        	delivery_charges DC
                        ON 
                         	SD.seller_id = DC.seller_id
						WHERE
						    PD.product_seller_id = '".$userid."' 
						    AND 
						    PC.	catalogue_Name LIKE '%".$_REQUEST['cname']."%' 
						    AND 
						    PD.product_name LIKE '%".$_REQUEST['pname']."%'
						    AND 
						    PD.product_status = 'Active' 
						    AND 
						    PC.catalogue_status = 'Active'
						    AND
						    PD.product_price > 0 
						    AND 
						    PD.product_offer_price > 0

					");
			confirm($query1);
			$rows=mysqli_num_rows($query1);
			while($row1=fetch_array($query1))
			{   
				$row1['catalogue_id']=$row1['product_catalogue_id'];
				$temp[]=$row1;
				
			}
			
			$temp['business_name']=$business_name;
			$temp['kyc_completed']=$kyc_completed;
			$temp['accept_cod_payments']=$accept_cod_payments;
			$temp['accept_online_payments']=$accept_online_payments;
			$temp['mobile'] =$mobile;
			$temp['user_id'] =$userid;
			$temp['rows']=$rows;
			$temp['response_code']=200;
			$temp['response_desc']="success";
			echo json_encode(array("getproducts"=>$temp));
			close();
			exit();
	}
	else
	{
	$query='';
	$query=query("
					SELECT
					    catalogue_id,
					    catalogue_name
					FROM
					    product_catalogue
					WHERE
					    catalogue_status = 'Active' 
					    AND 
					    catalogue_seller_id = '".$userid."'
				");
	confirm($query);
	$rows=mysqli_num_rows($query);

	if($rows!=0)	// Valid Request, Data Found.
	{
		$rows=0;
		$temp=array();
		while($row=fetch_array($query))
		{	
			
			$query1=query("
							SELECT
							    PD.product_id,
							    PD.product_seller_id,
							    PD.productimage,
							    PD.product_name,
							    PD.product_price,
							    PD.product_price_currency,
							    PD.product_category,
							    PD.product_sub_category,
							    PD.product_description,
							    PD.product_catalogue_id,
							    PD.warranty_duration,
							    PD.warranty_days_mon_yr,
							    PD.warrant_type,
							    PD.valid_from,
							    PD.product_model,
							    PD.product_offer_price,
							    PD.tax_type,
							    PD.tax_percent,
							    PD.free_shipping,
							    PD.cancellation_available,
							    PD.cash_on_delivery,
							    PD.return_available,
							    PD.product_inventory,
							    PD.product_unit,
							    PD.product_status,
							    PD.discount_type,
							    PD.discount,
							    DC.delivery_charge,
							    DC.delivery_free_above,
							    SD.accept_online_payments,
							    SD.accept_cod_payments,
							    SD.logistics_integrated
							FROM
							    seller_details SD
                            INNER JOIN 
                            	product_details PD
                            ON 
                            	PD.product_seller_id = SD.seller_id
                            LEFT OUTER JOIN 
                            	delivery_charges DC
                            ON 
                             	SD.seller_id = DC.seller_id
							WHERE
							    PD.product_seller_id = '".$userid."' 
							    AND 
							    PD.product_catalogue_id = '".$row['catalogue_id']."'  
							    AND 
							    PD.product_status = 'Active' 
							    AND 
							    PD.product_price > 0 
							    AND 
							    PD.product_offer_price > 0
						");
			confirm($query1);

			while($row1=fetch_array($query1))
			{   $row1['cataloguename']=$row['catalogue_name'];
				$row1['catalogue_id']=$row1['product_catalogue_id'];
				$temp[]=$row1;
				$rows+=1;
			}

		}
		$temp['business_name']=$business_name;
		$temp['mobile'] =$mobile;
		$temp['user_id'] =$userid;
		$temp['kyc_completed']=$kyc_completed;
		$temp['accept_cod_payments']=$accept_cod_payments;
		$temp['accept_online_payments']=$accept_online_payments;
		$temp['rows']=$rows;
		$temp['response_code']=200;
		$temp['response_desc']="success";
		//print_r($temp);

 		echo json_encode(array("getproducts"=>$temp));

 	}
	
 	else
	{
		$temp=array();
		$temp['response_code']=405;
		$temp['response_desc']="No Records Found";
		$temp['rows']=$rows;
 		echo json_encode(array("getproducts"=>$temp));
	}
}

}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
 	echo json_encode(array("getproducts"=>$temp));
}
close();
?>
