<?php
header("Content-Type:application/json"); // setting content as well as we will convert data into json type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$query="";

if(isset($_REQUEST['admin_id']) && $_REQUEST['admin_id']!="")
{
	$admin_id = $_REQUEST['admin_id'];
	
	$query="SELECT 
				userid,
				full_name,
				role,
				status,
				last_modified_by
			FROM 
				admin_user 
			WHERE
				userid='".$admin_id."'
			";
					
}
else
{
	$query="SELECT 
				userid,
				full_name,
				role,
				status,
				last_modified_by
			FROM 
				admin_user
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
		echo json_encode(array("getadmindetails"=>$temp));
		close();
		exit();
	}
	else
	{
		$temp['response_code']=405;
		$temp['response_desc']="Record Not Found";
		echo json_encode(array("getadmindetails"=>$temp));
		close();
		exit();
	}

?>
