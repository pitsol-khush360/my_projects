<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!=''&&isset($_REQUEST['imagestatus'])&&$_REQUEST['imagestatus']!='')
{

	$user_id=$_REQUEST['user_id'];
	$sellerimage='';
	$check_status='';
	if($_REQUEST['imagestatus']=='1')
	{
		$seller=$_REQUEST['seller_image'];
		$date=date('Ymd').rand(10000,100000);
		$sellerimage=$_REQUEST['image_name'];
		if($sellerimage=='/images/sellers/defaultpic.png'||$sellerimage=='/images/sellers/defaultpic.jpg'||$sellerimage==''||$sellerimage==NULL)
		{
			$sellerimage='/images/sellers/'.'seller'.$date.$user_id.'.png';
		}
		else
		{
			$sellerimage1=UPLOAD_DIRECTORY.$sellerimage;
			if (file_exists($sellerimage1)) {
			    unlink($sellerimage1);
			  } 
			$date=date('Ymd').rand(10000,100000);
			$sellerimage='/images/sellers/'.'seller'.$date.$user_id.'.png';
		}
		$sellerimage1=UPLOAD_DIRECTORY.$sellerimage;
		$check_status=imageupload($seller,$sellerimage1);
	}
	else
	{
			$sellerimage=$_REQUEST['image_name'];
	}
	if($check_status=="Invalid")
	{
		$sellerimage="/images/sellers/defaultpic.jpg";
	}
	$query="
			UPDATE
			    seller_details
			SET
			    seller_image = '".$sellerimage."'
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
			$temp['response_code']=200;
			$temp['image']=$sellerimage;
			$temp['response_desc']="Success";
	 		echo json_encode(array("updatesellerimage"=>$temp));
	 		close();
	 		exit();

		}
		else
		{
			rollback();
			$temp=array();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("updatesellerimage"=>$temp));
			close();
			exit();
		}
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("updatesellerimage"=>$temp));
	close();
	exit();
}
close();
?>
