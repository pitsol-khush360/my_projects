<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$query="";

if(isset($_REQUEST['collection_id']) && $_REQUEST['collection_id']!="")
{
	$collection_id=$_REQUEST['collection_id'];
	
	$query="SELECT
				collection_id,
				collection_name,
				image_name
			FROM 
				collection_library
			WHERE
				collection_id='".$collection_id."'
			ORDER BY
				collection_id DESC
			";
					
}
else
if(isset($_REQUEST['collection_name']) && $_REQUEST['collection_name']!="")
{
	$collection_name=$_REQUEST['collection_name'];
	$query="SELECT
				collection_id,
				collection_name,
				image_name
			FROM 
				collection_library
			WHERE
				collection_name='".$collection_name."'
			ORDER BY
				collection_id DESC
			";
									
}
else
{
	$query="SELECT
				collection_id,
				collection_name,
				image_name
			FROM 
				collection_library
			ORDER BY
				collection_id DESC
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
