<?php
header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$query="";

if(isset($_REQUEST['business_name']) && $_REQUEST['business_name']!="")
{
	$business_name=$_REQUEST['business_name'];
	
	$query="SELECT 
				user_id,
				business_name
			FROM 
				users
			WHERE
				status = 'A'
				AND
				role = 2
				AND
				business_name ='".$business_name."'
			";
					
}
else
{
	$query="SELECT 
				user_id,
				business_name
			FROM 
				users
			WHERE
				status = 'A'
				AND
				role = 2
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
	$temp['response_desc']="success";
	$temp['rows']=$rows;
	
	echo json_encode(array("getactiveseller"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="Record Not Found";

	echo json_encode(array("getactiveseller"=>$temp));
	close();
	exit();
}
?>
