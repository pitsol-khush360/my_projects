<?php

header("Content-Type:application/json");
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$flag = true;
$connection->autocommit(FALSE);
//print_r($_REQUEST);
  $output=$_REQUEST;
 
 $user_id=$output[0];
 //print_r($output[1]);
 $collections=explode("#",$output[1]);
 $id="";
 if($collections[0]!='' && $collections[1]!='')
 {
 	$query='
 			INSERT INTO product_catalogue(
			    catalogue_seller_id,
			    catalogue_Name,
			    creation_datetime,
			    modification_datetime,
			    updatedby,
			    catalogue_status,
			    catalogue_image
			)
			VALUES(
				    "'.$user_id.'",
				    "'.$collections[0].'",
				    NOW(), 
				    NOW(), 
				    "'.$user_id.'", 
				    "Active", 
				    "'.$collections[1].'"
				)';
 
 	$query=query($query);
 	$result=confirm($query);
 	if(!$result)
	{
		$flag = false;
		rollback();
		$temp=array();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("createcollectionproduct"=>$temp));
		close();
		exit();
	}
 	$id=$connection->insert_id;
 
 
 	$query="
				SELECT
				    *
				FROM
				    product_default_settings
				WHERE
				    seller_id = '".$user_id."'";
	$query=query($query);
	confirm($query);
	$row=fetch_array($query);
	$productofferprice=0;
	$discount_type=$row['discount_type'];
	$discount_value=$row['discount_percent'];
	$rows =$output[2]+3;
	 for($i=3;$i<$rows;$i++)
	 	{
	 		if($flag==true)
	 		{
	 		//echo $output[$i];
	 		$temp=explode("#",$output[$i]);   // 0 1 2
	 		
			$query='INSERT INTO product_details(
						    updatedby,
						    product_catalogue_id,
						    product_seller_id,
						    product_name,
						    product_description,
						    product_creation_datetime,
						    product_modification_datetime,
						    product_status,
						    product_price,
						    product_offer_price,
						    product_price_currency,
						    discount_type,
						    discount,
						    productimage
					)
					VALUES(';
								$query.='"'.$user_id.'",'
								;
								$query.='"'.$id.'",'
								;
								$query.='"'.$user_id.'",'
								;
								$query.='"'.$temp[0].'",'
								;
								$query.='"'.$temp[2].'",'
								;
								$query.='NOW(),'
								;
								$query.='NOW(),'
								;
								$query.='"Active",'
								;
								$query.='"0",'
								;
								$query.='"0",'
								;
								$query.='"INR",'
								;
								$query.='"'.$discount_type.'",'
								;
								$query.='"'.$discount_value.'",'
								;
								$query.='"'.$temp[1].'"
							)';
			//echo $query;
			$query=query($query);
			$result=confirm($query);
			if(!$result)
			{
				$flag = false;
				rollback();
				$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Request";
				echo json_encode(array("createcollectionproduct"=>$temp));
				close();
				exit();
			}
			
	 		
	 	}
	 }
	 
	 if($flag)
	{
		commit();
		$temp=array();
		$temp['response_code']=200;
		$temp['response_desc']="Success";
		echo json_encode(array("createcollectionproduct"=>$temp));
		close();
		exit();
	}
	else
	{
		rollback();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("createcollectionproduct"=>$temp));
		close();
		exit();
	}

}
 
close();
?>
