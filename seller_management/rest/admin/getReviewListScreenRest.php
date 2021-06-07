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
					id,
					seller_id,
					review_title,
					review,
					rating,
					creation_date_time
							FROM 
					reviews
							WHERE
					seller_id='".$seller_id."'";
					
}
else
if(isset($_REQUEST['rating']) && $_REQUEST['rating']!="")
{
	$rating=$_REQUEST['rating'];
	$query="SELECT 
					id,
					seller_id,
					review_title,
					review,
					rating,
					creation_date_time
							FROM 
					reviews
							WHERE
					rating='".$rating."'";
}
else
if(isset($_REQUEST['creation_date_time']) && $_REQUEST['creation_date_time']!="")
{
	$creation_date_time=$_REQUEST['creation_date_time'];
	$query="SELECT 
					id,
					seller_id,
					review_title,
					review,
					rating,
					creation_date_time
							FROM 
					reviews
							WHERE
					creation_date_time='".$creation_date_time."'";
					
}
else
{
	$query="SELECT 
					id,
					seller_id,
					review_title,
					review,
					rating,
					creation_date_time
							FROM 
					reviews";
					
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
	//print_r($temp);
	echo json_encode(array("getreviewdetails"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="No Records Found";
	echo json_encode(array("getreviewdetails"=>$temp));
	close();
	exit();
}
?>
