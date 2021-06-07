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
				S.seller_id,
				S.shipping_amount,
				U.status,
				S.last_modified_datetime,
				S.last_modified_by
			FROM 
				shipping_charges S,users U
			WHERE
				S.seller_id ='".$seller_id."'
				AND
				S.seller_id = U.user_id
			";
					
}
else
{
	$query="SELECT 
				S.seller_id,
				S.shipping_amount,
				U.status,
				S.last_modified_datetime,
				S.last_modified_by
			FROM 
				shipping_charges S,users U
			WHERE
				S.seller_id = U.user_id
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
	
	echo json_encode(array("getshippingchargesdetails"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="Record Not Found";

	echo json_encode(array("getshippingchargesdetails"=>$temp));
	close();
	exit();
}
?>
