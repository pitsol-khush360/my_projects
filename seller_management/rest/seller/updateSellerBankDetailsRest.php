<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!='' && isset($_REQUEST['beneficiary_name']) && $_REQUEST['beneficiary_name']!=''&& isset($_REQUEST['account_number']) && $_REQUEST['account_number']!=''&& isset($_REQUEST['ifsc_code']) && $_REQUEST['ifsc_code']!='')
{

	$user_id=$_REQUEST['user_id'];
	$beneficiary_name=$_REQUEST['beneficiary_name'];
	$account_number = $_REQUEST['account_number'];
	$ifsc_code = $_REQUEST['ifsc_code'];
	$check_status='';
	$chequeimage='';
	if($_REQUEST['imagestatus']=='1')
	{
			$cheque=$_REQUEST['cheque_image'];
			$chequeimage=$_REQUEST['image_name'];
			$date=date('Ymd').rand(10000,100000);
			if($chequeimage=='/images/cheques/defaultpic.jpg'||$chequeimage=='/images/cheques/defaultpic.png'||$chequeimage==NULL||$chequeimage=='')
			{
				$chequeimage='/images/cheques/cheque'.$date.$user_id.'.png';
			}
			
			$chequeimage1=UPLOAD_DIRECTORY.$chequeimage;
			//echo $chequeimage1;
			$check_status=imageupload($cheque,$chequeimage1);
	}
	else
	{
		$chequeimage=$_REQUEST['image_name'];
	}
	
	if($check_status=="Invalid")
	{
		$chequeimage="/images/cheques/defaultpic.png";
	}
	$query="
			UPDATE
			    seller_details
			SET
			    beneficiary_name = '".$beneficiary_name."',
			    account_number = '".$account_number."',
			    cheque_image = '".$chequeimage."',
			    bank_account_verified = 'No',
			    ifsc_code = '".$ifsc_code."'
			WHERE
			    seller_id = '".$user_id."'
    	" ;
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
	 		echo json_encode(array("updatebankdetails"=>$temp));
	 		close();
	 		exit();

		}
		else
		{
			rollback();
			$temp=array();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("updatebankdetails"=>$temp));
			close();
			exit();
		}
	
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("updatebankdetails"=>$temp));
	close();
	exit();
}
close();
?>
