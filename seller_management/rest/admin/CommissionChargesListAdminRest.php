<?php
header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$query="";
	
	$query="SELECT 
				comission_type,
				comission_percentage,
				tax_on_commission,
				last_modified_datetime,
				last_modified_by
			FROM 
				commission_charges
			
			";

//echo $query;
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
	
	echo json_encode(array("getcommisionchargesdetails"=>$temp));
	close();
	exit();
}
else
{
	$temp['response_code']=405;
	$temp['response_desc']="Record Not Found";

	echo json_encode(array("getcommisionchargesdetails"=>$temp));
	close();
	exit();
}
?>
