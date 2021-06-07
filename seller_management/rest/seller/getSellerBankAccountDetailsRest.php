<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!='')
{		
	

	$user_id=$_REQUEST['user_id'];
	$query="
			SELECT
    			beneficiary_name,
			    beneficiary_id,
			    account_number,
			    bank_account_verified,
			    ifsc_code
			FROM
			    seller_details
			WHERE
			    seller_id = '".$_REQUEST['user_id']."'
			";
	//echo $query;
	$query=query($query);
	$row=fetch_array($query);
	$rows=mysqli_num_rows($query);
	if($rows>0)
	{
		$temp=array();
		$temp[]=$row;
		$temp['response_code']=200;
		$temp['response_desc']="Success";
		$temp['rows']=$rows;
 		echo json_encode(array("getsellerbankdetails"=>$temp));
 		close();
 		exit();
	}
	else
	{
		$temp=array();
		$temp[]=$row;
		$temp['response_code']=405;
		$temp['response_desc']="No Records Found";
		$temp['rows']=$rows;
 		echo json_encode(array("getsellerbankdetails"=>$temp));
 		close();
 		exit();
	}		
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("getsellercustomers"=>$temp));
}
close();
?>
