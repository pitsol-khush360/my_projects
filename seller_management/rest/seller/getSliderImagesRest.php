<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['application']) && $_REQUEST['application']!=''&&isset($_REQUEST['purpose']) && $_REQUEST['purpose']!=''&&isset($_REQUEST['placeholder']) && $_REQUEST['placeholder']!='')
{		

	$query="
			SELECT
			    image_name,
			    sequence
			FROM
			    slider
			WHERE
			    application = '".$_REQUEST['application']."' 
			    AND 
			    purpose = '".$_REQUEST['purpose']."' 
			    AND 
			    placeholder = '".$_REQUEST['placeholder']."'
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
 		echo json_encode(array("getsliderimages"=>$temp));
 		close();
 		exit();
 	}
 	else{
 		$temp['response_code']=405;
		$temp['response_desc']="No Results Found";
		$temp['rows']=$rows;
 		echo json_encode(array("getsliderimages"=>$temp));
 		close();
 		exit();
 	}		
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("getsliderimages"=>$temp));
	close();
	exit();
}
close();
?>
