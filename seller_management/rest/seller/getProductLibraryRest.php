<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$query='';
if(isset($_REQUEST['collection_id'])&&$_REQUEST['collection_id'])
{
	
	$query="
			SELECT
			    `product_id`,
			    `collection_id`,
			    `product_name`,
			    `product_description`,
			    `image_name`
			FROM
			    product_library
			WHERE
			    collection_id = '".$_REQUEST['collection_id']."'
			";
}
else
{
	$query="
			SELECT
			    `product_id`,
			    `collection_id`,
			    `product_name`,
			    `product_description`,
			    `image_name`
			FROM
			    product_library
			";

}	

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
	//print_r($temp);
	$temp['response_code']=200;
	$temp['response_desc']="success";
	$temp['rows']=$rows;
	echo json_encode(array("getcollections"=>$temp));
	close();
 	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="No Results Found";
	$temp['rows']=$rows;
	echo json_encode(array("getcollections"=>$temp));
	close();
 	exit();
}
 close();	
?>
