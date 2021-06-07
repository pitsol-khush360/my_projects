<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");

	$query='';
	
	
	$query="
			SELECT
			    `collection_id`,
			    `collection_name`,
			    `image_name`
			FROM
			    collection_library
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
		$temp=array();
		$temp['response_code']=405;
		$temp['response_desc']="No Records Found";
		$temp['rows']=$rows;
 		echo json_encode(array("getcollections"=>$temp));
 		close();
 		exit();
	}
	
 	close();
?>
