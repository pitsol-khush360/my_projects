<?php
header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$query="";

if(isset($_REQUEST['seller_id']) && $_REQUEST['seller_id']!="")
{
	$seller_id=$_REQUEST['seller_id'];
	$query="SELECT 
				seller_id,
				review_title,
				review,
				rating,
				creation_date_time
			FROM 
				reviews
			WHERE
				seller_id='".$seller_id."'
				ORDER BY
				creation_date_time DESC
			";
					
}
else
if(isset($_REQUEST['rating']) && $_REQUEST['rating']!="")
{
	$rating=$_REQUEST['rating'];
	$query="SELECT 
				seller_id,
				review_title,
				review,
				rating,
				creation_date_time
			FROM 
				reviews
			WHERE
				rating='".$rating."'
				ORDER BY
				creation_date_time DESC
			";
}
else
{
	$query="SELECT 
				seller_id,
				review_title,
				review,
				rating,
				creation_date_time
			FROM 
				reviews
			ORDER BY
				creation_date_time DESC
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

	echo json_encode(array("getreviewdetails"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="Record Not Found";
	echo json_encode(array("getreviewdetails"=>$temp));
	close();
	exit();
}
?>
