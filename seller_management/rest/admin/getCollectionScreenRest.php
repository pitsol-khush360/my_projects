<?php
header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$query="";

if(isset($_REQUEST['catalogue_id']) && $_REQUEST['catalogue_id']!="")
{
	$catalogue_id=$_REQUEST['catalogue_id'];
	$query="SELECT 
					catalogue_id ,
					catalogue_seller_id ,
					catalogue_Name,
					catalogue_status,
					creation_datetime,
					modification_datetime,
					updatedby
							FROM 
					product_catalogue
							WHERE
					catalogue_id='".$catalogue_id."'";
					
}
else
if(isset($_REQUEST['catalogue_seller_id']) && $_REQUEST['catalogue_seller_id']!="")
{
	$catalogue_seller_id=$_REQUEST['catalogue_seller_id'];
	$query="SELECT 
					catalogue_id ,
					catalogue_seller_id ,
					catalogue_Name,
					catalogue_status,
					creation_datetime,
					modification_datetime,
					updatedby
							FROM 
					product_catalogue
							WHERE
					catalogue_seller_id='".$catalogue_seller_id."'";
}
else
if(isset($_REQUEST['catalogue_Name']) && $_REQUEST['catalogue_Name']!="")
{
	$catalogue_Name=$_REQUEST['catalogue_Name'];
	$query="SELECT 
					
					catalogue_id ,
					catalogue_seller_id ,
					catalogue_Name,
					catalogue_status,
					creation_datetime,
					modification_datetime,
					updatedby
							FROM 
					product_catalogue
							WHERE
					catalogue_Name='".$catalogue_Name."'";
					
}
else
if(isset($_REQUEST['catalogue_status']) && $_REQUEST['catalogue_status']!="")
{
	$catalogue_status=$_REQUEST['catalogue_status'];
	$query="SELECT 
					catalogue_id ,
					catalogue_seller_id ,
					catalogue_Name,
					catalogue_status,
					creation_datetime,
					modification_datetime,
					updatedby
							FROM 
					product_catalogue
							WHERE
					catalogue_status='".$catalogue_status."'";
					
}
else
{
	$query="SELECT 
					catalogue_id ,
					catalogue_seller_id ,
					catalogue_Name,
					catalogue_status,
					creation_datetime,
					modification_datetime,
					updatedby
							FROM 
					product_catalogue";
					
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
	$temp['response_desc']="success";
	$temp['rows']=$rows;
	//print_r($temp);
	echo json_encode(array("getcollectiondetails"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="No Records Found";
	echo json_encode(array("getcollectiondetails"=>$temp));
	close();
	exit();
}
?>
