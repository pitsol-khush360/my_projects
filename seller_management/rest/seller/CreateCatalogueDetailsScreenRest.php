<?php

header("Content-Type:application/json");
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!="")
{	
	$catalogue_Name=$_REQUEST['catalogue_Name'];
	$catalogue_image='';
	$check_status='';
	if($_REQUEST['imagestatus']=='1')
	{
	$data=$_REQUEST['image'];
	$date=date('Ymd').rand(10000,100000);
	$name = preg_replace('/[^A-Za-z0-9\-]/', '', $_REQUEST['catalogue_Name']);
	$catalogue_image="/images/collections/".$name.$_REQUEST['user_id'].$date.'.png';
	$catalogue_image1=UPLOAD_DIRECTORY.$catalogue_image;
	$check_status=imageupload($data,$catalogue_image1);
	}
	else
	{
		$catalogue_image="/images/collections/defaultpic.jpg";
	}
	if($check_status=="Invalid")
	{
		$catalogue_image="/images/collections/defaultpic.jpg";
	}
	$query=query("
				INSERT INTO product_catalogue(
				    catalogue_seller_id,
				    catalogue_Name,
				    updatedby,
				    catalogue_image
				)
				VALUES(
				    '".$_REQUEST['user_id']."',
				    '".$_REQUEST['catalogue_Name']."',
				    '".$_REQUEST['user_id']."',
				    '".$catalogue_image."'
				)
				");
	$result=confirm($query);
	if(!$result)
	{
		$flag = false;
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("catalogue"=>$temp));
		close();
		exit();
	}
	if($flag)
	{
		commit();
		$temp=array();
		$temp['response_code']=200;
		$temp['response_desc']="Success";
		echo json_encode(array("catalogue"=>$temp));
		close();
		exit();
	}
	else
	{
		rollback();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("catalogue"=>$temp));
		close();
		exit();
	}
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("catalogue"=>$temp));
	close();
	exit();
}
close();
?>
