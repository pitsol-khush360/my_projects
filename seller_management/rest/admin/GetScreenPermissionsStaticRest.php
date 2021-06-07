<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');


	if(isset($_REQUEST['id']) && $_REQUEST['id']!="")
	{
		$id=$_REQUEST['id'];
	
		$query="SELECT
					id,
					screen_name,
					permission_name,
					ROLE_NAME
				FROM 
					application_role
				WHERE
					id    ='".$id."'
				ORDER BY
					id DESC
				";
					
	}
	else
	if(isset($_REQUEST['ROLE_NAME']) && $_REQUEST['ROLE_NAME']!="")
	{
		$ROLE_NAME=$_REQUEST['ROLE_NAME'];
		$query="SELECT
					id,
					screen_name,
					permission_name,
					ROLE_NAME
				FROM 
					application_role
				WHERE
					ROLE_NAME    ='".$ROLE_NAME."'
				ORDER BY
					id DESC
				";
									
	}
	else
	{
		$query="SELECT
					id,
					screen_name,
					permission_name,
					ROLE_NAME
				FROM 
					application_role
				ORDER BY
					id DESC
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
		echo json_encode(array("getpermissiondetails"=>$temp));
		close();
		exit();
	}
	else
	{
		$temp['response_code']=405;
		$temp['response_desc']="Record Not Found";
		echo json_encode(array("getpermissiondetails"=>$temp));
		close();
		exit();
	}
?>
