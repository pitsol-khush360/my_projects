<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='' && isset($_REQUEST['acceptonlinepayment'])&&$_REQUEST['acceptonlinepayment']!='' )
{	
	
		$query="
				SELECT
				    SD.accept_cod_payments,
				    SD.beneficiary_id,
				    SD.beneficiary_name,
				    SD.account_number,
				    SD.bank_account_verified,
				    SD.ifsc_code,
				    SD.seller_email,
				    U.mobile,
				    SD.seller_address1,
				    SD.seller_city,
				    SD.seller_pin,
				    SD.seller_state
				FROM
				    seller_details SD,
				    users U
				WHERE
				    SD.seller_id = '".$_REQUEST['user_id']."' 
				    AND 
				    U.user_id = SD.seller_id
			    ";
		$query=query($query);
		$row=fetch_array($query);
		$benificiary_id='';
		$codStatus=$row['accept_cod_payments'];
		if($codStatus=='0' && $_REQUEST['acceptonlinepayment']=='0')
		{
			$temp=array();
			$temp['response_code']=405;
			$temp['response_desc']="Atleast one Payment Method should be active";
			echo json_encode(array("updateonlinepaymentstatus"=>$temp));
			close();
	 		exit();
		}
		if($row['bank_account_verified']!='Yes')
		{
			$temp=array();
			$temp['response_code']=405;
			$temp['response_desc']="Your Bank Account details are not verified , hence online payments cannot be enabled";
			echo json_encode(array("updateonlinepaymentstatus"=>$temp));
			close();
	 		exit();
		}
		if($row['seller_email']=='' || $row['seller_email']=='null')
		{
			$email='contactuatcode@gmail.com';
		}
		else
		{
			$email=$row['seller_email'];
		}
		$beneficiary_id=$row['beneficiary_id'];
		if(($row['beneficiary_id']=='' or $row['beneficiary_id']==NULL or $row['beneficiary_id']==0) and $row['bank_account_verified']='Yes')
		{
			$beneficiary_id='BEN'.date('YmdHis').rand(100,1000).'S'.$_REQUEST['user_id'];
			//$check=0;
			//while($check==0)
			//{
				//check benificiatprsent in cashfree if not then make check=1 else regenerate benificiary id

			//}
			//inserat benificiary record in cashfree using apis
			$data=array("beneId"=> $beneficiary_id,
		    "name"=> $row['beneficiary_name'],
		    "email"=> $email,
		    "phone"=> $row['mobile'],
		    "bankAccount"=> $row['account_number'],
		    "ifsc"=> $row['ifsc_code'],
		    "address1"=> $row['seller_address1'],
		    "city"=> $row['seller_city'],
		    "state"=> $row['seller_state'],
		    "pincode"=> $row['seller_pin']);

		    //send data to api
		}
		$query="
				UPDATE
				    seller_details
				SET
				    accept_online_payments = '".$_REQUEST['acceptonlinepayment']."',
				    beneficiary_id = '".$beneficiary_id."'
				WHERE
				    seller_id = '".$_REQUEST['user_id']."' 
				    AND 
				    bank_account_verified = 'Yes' 
				    AND 
				    kyc_completed = '1'
			    ";
		//echo $query;
		$query=query($query);
		
		$result=confirm($query);
		if(!$result)
		{
			$flag=false;
		}
		if($flag)
		{
			commit();
			$temp=array();
			$temp['response_code']=200;
			$temp['response_desc']="Success";
	 		echo json_encode(array("updateonlinepaymentstatus"=>$temp));
	 		close();
	 		exit();

		}
		else
		{
			rollback();
			$temp=array();
			$temp['response_code']=405;
			$temp['response_desc']="Your Bank Account details are not verified , hence online payments cannot be enabled";
			echo json_encode(array("updateonlinepaymentstatus"=>$temp));
			close();
	 		exit();
		}
	
	
	
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("updateonlinepaymentstatus"=>$temp));
	close();
	exit();
}
close();	
?>
