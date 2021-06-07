<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!="")
{		
	$start='0';
	$end='100';
	if(isset($_REQUEST['start']))
		$start=$_REQUEST['start'];

	if(isset($_REQUEST['end']))
		$end=$_REQUEST['end'];

	$user_id=$_REQUEST['user_id'];
	
	$name="";
	$status="";

	if(isset($_REQUEST['cataloguename']))
		$name=$_REQUEST['cataloguename'];

	if(isset($_REQUEST['cataloguestatus']))
		$status=$_REQUEST['cataloguestatus'];

	$query="";

	if(isset($name) && $name!="")
	{
		$query=query("
						SELECT
						    `catalogue_id`,
						    `catalogue_seller_id`,
						    `catalogue_Name`,
						    `creation_datetime`,
						    `modification_datetime`,
						    `updatedby`,
						    `catalogue_status`,
						    `catalogue_image`
						FROM
						    product_catalogue
						WHERE
						    catalogue_seller_id = '".$user_id."' AND catalogue_Name LIKE '%".$name."%'
						ORDER BY
						    catalogue_id
						DESC
    				");
	}
	else if(isset($_REQUEST['all']) && $_REQUEST['all']=="ALL")
	{
		$query=query("
						SELECT
						    `catalogue_id`,
						    `catalogue_seller_id`,
						    `catalogue_Name`,
						    `creation_datetime`,
						    `modification_datetime`,
						    `updatedby`,
						    `catalogue_status`,
						    `catalogue_image`
						FROM
						    product_catalogue
						WHERE
						    catalogue_seller_id = '".$user_id."'
						ORDER BY
						    catalogue_id
						DESC
					");
	}
	else if(isset($_REQUEST['catalogueid']) && $_REQUEST['catalogueid']!='')
	{
		$query=query("
						SELECT
						    `catalogue_id`,
						    `catalogue_seller_id`,
						    `catalogue_Name`,
						    `creation_datetime`,
						    `modification_datetime`,
						    `updatedby`,
						    `catalogue_status`,
						    `catalogue_image`
						FROM
						    product_catalogue
						WHERE
						    catalogue_seller_id = '".$user_id."' 
						    AND 
						    catalogue_id = '".$_REQUEST['catalogueid']."'
					");
	}
	else if(isset($status) && $status!="")
	{
		$query=query("
						SELECT
						    `catalogue_id`,
						    `catalogue_seller_id`,
						    `catalogue_Name`,
						    `creation_datetime`,
						    `modification_datetime`,
						    `updatedby`,
						    `catalogue_status`,
						    `catalogue_image`
						FROM
						    product_catalogue
						WHERE
						    catalogue_seller_id = '".$user_id."' AND catalogue_status = '".$status."'
						ORDER BY
						    catalogue_id
						DESC
					");
	}
	else
	{
		$query=query("
						SELECT
						    `catalogue_id`,
						    `catalogue_seller_id`,
						    `catalogue_Name`,
						    `creation_datetime`,
						    `modification_datetime`,
						    `updatedby`,
						    `catalogue_status`,
						    `catalogue_image`
						FROM
						    product_catalogue
						WHERE
						    catalogue_seller_id = '".$user_id."'
						ORDER BY
						    catalogue_id
						DESC
						LIMIT ".$start.",".$end
					);
	}
		
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
		$temp['response_desc']="success";
		$temp['rows']=$rows;
 		echo json_encode(array("getcatalogue"=>$temp));
 		close();
 		exit();
 	}
	else
	{
		$temp['response_code']=405;
		$temp['response_desc']="No Records Found";
		$temp['rows']=$rows;
 		echo json_encode(array("getcatalogue"=>$temp));
 		close();
 		exit();
	}
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("getcatalogue"=>$temp));
	close();
	exit();
}
close();
?>
