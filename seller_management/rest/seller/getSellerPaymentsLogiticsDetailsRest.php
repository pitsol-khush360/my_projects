<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='')
{	

		
		$query="
				SELECT
				    accept_cod_payments,
				    accept_online_payments,
				    logistics_integrated,
				    kyc_completed,
				    bank_account_verified
				FROM
				    seller_details
				WHERE
				    seller_id = '".$_REQUEST['user_id']."'
			    ";
		$query=query($query);
		confirm($query);
		$row=fetch_array($query);
		$rows=mysqli_num_rows($query);
		if($rows>0)
		{
			$temp['accept_cod_payments'] = $row['accept_cod_payments'];
			$temp['accept_online_payments'] = $row['accept_online_payments'];
			$temp['logistics_integrated'] = $row['logistics_integrated'];
			$temp['kyc_completed'] = $row['kyc_completed'];
			$temp['bank_account_verified'] = $row['bank_account_verified'];
			$temp['response_code']=200;
			$temp['response_desc']="Success";
	 		echo json_encode(array("getsellerpaymentlogistics"=>$temp));
	 		close();
	 		exit();
	 	}
	 	else
	 	{
	 		$temp['response_code']=405;
			$temp['response_desc']="No Records Found";
	 		echo json_encode(array("getsellerpaymentlogistics"=>$temp));
	 		close();
	 		exit();
	 	}
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("getsellerpaymentlogistics"=>$temp));
	close();
	exit();
}
close();
?>
