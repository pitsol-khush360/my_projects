<?php
header("Content-Type:application/json"); // setting content as well as we will convert data into json type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$query="";
if(isset($_REQUEST['admin_id']) && $_REQUEST['admin_id']!="")
{
	$admin_id = $_REQUEST['admin_id'];
	$query = "  SELECT 
					userid,
					full_name,
					role,
					status,
					last_modified_by,
					password
				FROM 
					admin_user 
				WHERE
					userid='".$admin_id."'
				ORDER BY
					Creation_datetime DESC";


}
else
if(isset($_REQUEST['full_name']) && $_REQUEST['full_name']!="")
{
	$full_name = $_REQUEST['full_name'];
	$query = "  SELECT 
					userid,
					full_name,
					role,
					status,
					last_modified_by,
					password
				FROM 
					admin_user 
				WHERE
					full_name='".$full_name."'
				ORDER BY
					Creation_datetime DESC";


}
else
if(isset($_REQUEST['role']) && $_REQUEST['role']!="")
{
	$role = $_REQUEST['role'];
	$query = "  SELECT 
					userid,
					full_name,
					role,
					status,
					last_modified_by,
					password
				FROM 
					admin_user 
				WHERE
					role='".$role."'
				ORDER BY
					Creation_datetime DESC";


}
else
if(isset($_REQUEST['status']) && $_REQUEST['status']!="")
{
	$status = $_REQUEST['status'];
	$query = "  SELECT 
					userid,
					full_name,
					role,
					status,
					last_modified_by,
					password
				FROM 
					admin_user 
				WHERE
					status='".$status."'
					ORDER BY
					Creation_datetime DESC";


}
else
{
	$query = "  SELECT 
					userid,
					full_name,
					role,
					status,
					last_modified_by,
					password
				FROM 
					admin_user 
				ORDER BY
					Creation_datetime DESC";
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
		$temp['rows']=$rows;
		$temp['response_code']=200;
		$temp['response_desc']="Success";

		echo json_encode(array("getadmindetails"=>$temp));
		close();
		exit();
	}
	else
	{
		$temp['response_code']=405;
		$temp['response_desc']="Record not Found";
		echo json_encode(array("getadmindetails"=>$temp));
		close();
		exit();
	}
?>