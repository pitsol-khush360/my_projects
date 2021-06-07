<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!='')
{

	$user_id=$_REQUEST['user_id'];
	$check_status='';
	$date=date('Ymd').rand(10000,100000);
	$pancard='';
	$address='';
	
	if($_REQUEST['imagestatus']=='1')
	{
		$pancard=$_REQUEST['image_name'];
		$pan=$_REQUEST['pan_card_image'];
		if($pancard=='/images/pancards/defaultpic.jpg'||$pancard=='/images/pancards/defaultpic.png'||$pancard==NULL||$pancard=='')
		{
			$pancard="/images/pancards/"."pancard".$_REQUEST['user_id'].$date.'.png';
		}
		else
		{
			$pancard1=UPLOAD_DIRECTORY.$pancard;
			if (file_exists($pancard1)) {
			    unlink($pancard1);
			  } 
			$date=date('Ymd').rand(10000,100000);
			$pancard="/images/pancards/"."pancard".$_REQUEST['user_id'].$date.'.png';
		}
		$pancard1=UPLOAD_DIRECTORY.$pancard;
		$check_status=imageupload($pan,$pancard1);
	}
	else
	{
		$pancard=$_REQUEST['image_name'];
	}
	if($check_status=="Invalid")
	{
		$pancard="/images/pancards/defaultpic.png";
	}
	
	if($_REQUEST['imagestatus1']=='1')
	{
		$address = $_REQUEST['image_name1'];
		$add=$_REQUEST['address_proof_image'];
		if($address=='/images/addressproofs/defaultpic.jpg'||$address=='/images/addressproofs/defaultpic.png'||$address==NULL||$address=='')
			{
				$address="/images/addressproofs/"."address".$date.$user_id.'.png';
			}
		else
		{
			$address1=UPLOAD_DIRECTORY.$address;
			if (file_exists($address1)) {
			    unlink($address1);
			  } 
			$date=date('Ymd').rand(10000,100000);
			$address="/images/addressproofs/"."address".$date.$user_id.'.png';
		}
		
		$address1=UPLOAD_DIRECTORY.$address;
		$check_status=imageupload($add,$address1);
	}
	else
	{
		$address = $_REQUEST['image_name'];
	}
	

	if($check_status=="Invalid")
	{
		$address="/images/addressproofs/defaultpic.png";
	}
	$query="UPDATE
			    seller_details
			SET
			    address_proof_image = '".$address."',
			    pan_card_image = '".$pancard."',
			    kyc_application_status = 'Submitted'
			WHERE
			    seller_id = '".$user_id."'
		    ";

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
		$temp['status']="Submitted";
		$temp['response_code']=200;
		$temp['response_desc']="success";
		echo json_encode(array("updatekyc"=>$temp));
		close();
		exit();
	}
	else
	{
		rollback();
		$temp=array();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("updatekyc"=>$temp));
		close();
		exit();	
	}
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("updatekyc"=>$temp));
	close();
	exit();
}
close();
?>
