<?php
header("Content-Type:application/json"); // setting content as well as we will convert data into json type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$query="";

if(isset($_REQUEST['userid']) && $_REQUEST['userid']!="")
{
	$userid = $_REQUEST['userid'];
	
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
					userid='".$userid."'";
					

    $query=query($query);
	confirm($query);

	$rows=mysqli_num_rows($query);

	if($rows == 1)	// Valid Request, Data Found.
	{
		$row=array();
		$row=fetch_array($query);
		$row['response_code']=200;
		$row['response_desc']="Success";

		echo json_encode(array("getadmindetailsformodify"=>$row));
		close();
		exit();
	}
	else
	{
		$row['response_code']=405;
		$row['response_desc']="Record not Found";
		echo json_encode(array("getadmindetailsformodify"=>$row));
		close();
		exit();
	}
}
?>