<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
require('getSellerWalletBalanceRest.php');
//include('../../config/config.php');
if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!='')
	{
		$status='';
		//$end=10;
		if(isset($_REQUEST['status']) && $_REQUEST['status']!='')
		{
			$status=$_REQUEST['status'];
		}
		// if(isset($_REQUEST['end']) && $_REQUEST['end']!='')
		// {
		// 	$end=$_REQUEST['end'];
		// }
		$walletbalance = new Wallet();
		$temp=array();
		$row=$walletbalance->getWalletDetails($_REQUEST['user_id']);
		$temp=$walletbalance->getWalletTransactionDetails($_REQUEST['user_id'],$status);
		$temp['walletbalance']=$row['closing_balance'];
		$temp['response_code']=200;
		$temp['response_desc']="Sucess";
 		echo json_encode(array("getwalletbalance"=>$temp));

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
