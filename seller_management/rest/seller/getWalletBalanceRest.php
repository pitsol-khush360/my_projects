<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
require('getSellerWalletBalanceRest.php');
//include('../../config/config.php');
if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!='')
	{
		
		$walletbalance = new Wallet();
		$temp=array();
		$row=$walletbalance->getWalletDetails($_REQUEST['user_id']);
		$query=query("
					SELECT
					    kyc_completed,
					    accept_online_payments,
					    bank_account_verified
					FROM
					    seller_details
					WHERE
					    seller_id = '".$_REQUEST['user_id']."'
					");
		$row1=fetch_array($query);
		$rows=mysqli_num_rows($query);
		if($rows>0)
		{	
			$temp['bank_account_verified']=$row1['bank_account_verified'];
			$temp['kyc_completed']=$row1['kyc_completed'];
			$temp['accept_online_payments']=$row1['accept_online_payments'];
			$temp['walletbalance']=$row['closing_balance'];
			$temp['response_code']=200;
			$temp['response_desc']="Sucess";
			$temp['rows']=$rows;
			echo json_encode(array("getwalletbalance"=>$temp));
			close();
			exit();
		}
		else
		{
			$temp['response_code']=405;
			$temp['response_desc']="No Records Found";
			$temp['rows']=$rows;
			echo json_encode(array("getwalletbalance"=>$temp));
			close();
			exit();
		}
 		
	}
else
	{
		$temp=array();
		$temp['response_code']=400;
		$temp['response_desc']="Invalid Request";
		echo json_encode(array("getwalletbalance"=>$temp));
		close();
		exit();
	}

close();
?>
