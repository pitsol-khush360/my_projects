<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$query="";

if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!="")
{
	$user_id=$_REQUEST['user_id'];
	$query="SELECT 
				user_id,
				mobile,
				username,
				business_name,
				status,
				mobile_verified,
				accept_terms_and_conditions,
				created_datetime
			FROM 
				users
			WHERE
				role='2'
				AND
				user_id='".$user_id."'
				ORDER BY
				created_datetime DESC
			";
}
else
if(isset($_REQUEST['mobile'])&&$_REQUEST['mobile']!='')
{
	$mobile=$_REQUEST['mobile'];
	$query="SELECT 
				user_id,
				mobile,
				username,
				business_name,
				status,
				mobile_verified,
				accept_terms_and_conditions,
				created_datetime
			FROM 
				users
			WHERE
				role='2'
				AND
				mobile='".$mobile."'
				ORDER BY
				created_datetime DESC
			";
}
else
if(isset($_REQUEST['username'])&&$_REQUEST['username']!='')
{
	$username=$_REQUEST['username'];
	$query="SELECT 
				user_id,
				mobile,
				username,
				business_name,
				status,
				mobile_verified,
				accept_terms_and_conditions,
				created_datetime
			FROM 
				users
			WHERE
				role='2'
				AND
				username='".$username."'
				ORDER BY
				created_datetime DESC
			";
}
else
if(isset($_REQUEST['status'])&&$_REQUEST['status']!='')
{
	$status=$_REQUEST['status'];
	$query="SELECT 
				user_id,
				mobile,
				username,
				business_name,
				status,
				mobile_verified,
				accept_terms_and_conditions,
				created_datetime
			FROM 
				users
			WHERE
				role='2'
				AND
				status='".$status."'
				ORDER BY
				created_datetime DESC
			";
					
}
else
{
	$query="SELECT 
				user_id,
				mobile,
				username,
				business_name,
				status,
				mobile_verified,
				accept_terms_and_conditions,
				created_datetime
			FROM 
				users
			WHERE
				role='2'
				ORDER BY
				created_datetime DESC
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

	echo json_encode(array("getuserdetails"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="Record Not Found";
	echo json_encode(array("getuserdetails"=>$temp));
	close();
	exit();
}

?>
