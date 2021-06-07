<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

if(isset($_REQUEST['collection_id']) && $_REQUEST['collection_id']!='')
{

	$collection_id = $_REQUEST['collection_id'];

	$query="";

	if(isset($_REQUEST['product_id']) && $_REQUEST['product_id']!="")
	{
		$product_id=$_REQUEST['product_id'];
	
		$query="SELECT
					product_id,
					product_name,
					product_description,
					image_name
				FROM 
					product_library
				WHERE
					product_id    ='".$product_id."'
					AND
					collection_id = '".$collection_id."'
				ORDER BY
					product_id DESC
				";
					
	}
	else
	if(isset($_REQUEST['product_name']) && $_REQUEST['product_name']!="")
	{
		$product_name=$_REQUEST['product_name'];
		$query="SELECT
					product_id,
					product_name,
					product_description,
					image_name
				FROM 
					product_library
				WHERE
					product_name  ='".$product_name."'
					AND
					collection_id = '".$collection_id."'
				ORDER BY
					product_id DESC
				";
									
	}
	else
	{
		$query="SELECT
					product_id,
					product_name,
					product_description,
					image_name
				FROM 
					product_library
				WHERE
					collection_id = '".$collection_id."'
				ORDER BY
					product_id DESC
				";
	}
	$query=query($query);
	confirm($query);
	$rows=mysqli_num_rows($query);

	if($rows!=0)	// Valid Request, Data Found.
	{
		$temp=array();
		while($row=fetch_array($query))
		{
			$temp[]=$row;
		}
		$temp['response_code']=200;
		$temp['response_desc']="Success";
		$temp['rows']=$rows;
		//print_r($temp);
		echo json_encode(array("getproductdetails"=>$temp));
		close();
		exit();
	}
	else
	{
		$temp['response_code']=405;
		$temp['response_desc']="Record Not Found";
		echo json_encode(array("getproductdetails"=>$temp));
		close();
		exit();
	}
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("getproductdetails"=>$temp));
	close();
	exit();
}
?>
