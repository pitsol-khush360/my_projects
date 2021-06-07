<?php
header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['product_id']) && $_REQUEST['product_id']!="" && isset($_REQUEST['imagestatus']) && $_REQUEST['imagestatus']!="" && isset($_REQUEST['product_name']) && $_REQUEST['product_name']!="" && isset($_REQUEST['product_description']) && $_REQUEST['product_description']!="")
{


	$check_status='';
	$productimage='';
	if($_REQUEST['imagestatus']=='1')
	{
		$product=$_REQUEST['image'];
		$productimage=$_REQUEST['image_name'];

		if(	$productimage=='/images/product_library/defaultpic.jpg'||
			$productimage=='/images/product_library/defaultpic.png'||
			$productimage==''||
			$productimage==NULL)
		{
			$date=date('Ymd').rand(10000,100000);
			$productimage="/images/product_library/".$_REQUEST['product_name'].$date.'.png';
		}
		else
		{
			$productimage1=UPLOAD_DIRECTORY.$productimage;
			if (file_exists($productimage1)) {
			    unlink($productimage1);
			  } 
			$date=date('Ymd').rand(10000,100000);
			$productimage="/images/product_library/".$_REQUEST['product_name'].$date.'.png';
		}
		$productimage1=UPLOAD_DIRECTORY.$productimage;
		$check_status=imageupload($product,$productimage1);
	}
	else
	{
		$productimage=$_REQUEST['image_name'];
	}
	
	if($check_status=="Invalid")
	{
		$productimage="/images/product_library/defaultpic.jpg";
	}
	
	$query="UPDATE
			    product_library
			SET
			    product_name        = '".$_REQUEST['product_name']."',
			    product_description = '".$_REQUEST['product_description']."',
			    image_name          = '".$productimage."'
			WHERE
			    product_id   = '".$_REQUEST['product_id']."'
			";
	$query=query($query);
	$result=confirm($query);

	if(!$result)	// Valid Request, Data Found.
	{
		$flag=false;
		rollback();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
	 	echo json_encode(array("updateproduct"=>$temp));
	 	close();
 		exit();

	}
	if($flag)
	{
		commit();
		$temp=array();
		$temp['response_code']=200;
		$temp['response_desc']="Success";
		echo json_encode(array("updateproduct"=>$temp));
		close();
 		exit();
	}
	else
	{
		rollback();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
	 	echo json_encode(array("updateproduct"=>$temp));
	 	close();
 		exit();
	}
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
 	echo json_encode(array("updateproduct"=>$temp));
 	close();
 	exit();
}

?>
