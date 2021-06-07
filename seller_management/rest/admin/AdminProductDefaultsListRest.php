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
				product_category,
				discount_type,
				discount_percent,
				tax_type,
				tax_percent,
				warrant_type,
				warrant_duration,
				warranty_days_mon_yr
			FROM 
				product_default_settings
			WHERE
				seller_id='".$seller_id."'
			ORDER BY
				seller_id DESC
			";
					
}
else
{
	$query="SELECT 
				seller_id,
				product_category,
				discount_type,
				discount_percent,
				tax_type,
				tax_percent,
				warrant_type,
				warrant_duration,
				warranty_days_mon_yr
			FROM 
				product_default_settings
			ORDER BY
				seller_id DESC
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
	
	echo json_encode(array("getdefaultproductdetails"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="Record Not Found";
	echo json_encode(array("getdefaultproductdetails"=>$temp));
	close();
	exit();
}
?>
