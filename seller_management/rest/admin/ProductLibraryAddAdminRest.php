<?php
header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$connection->autocommit(FALSE);
$flag = true;

if(isset($_REQUEST['collection_id']) && $_REQUEST['collection_id']!="" &&
 isset($_REQUEST['imagestatus']) && $_REQUEST['imagestatus']!="" && 
 isset($_REQUEST['product_name']) && $_REQUEST['product_name']!="" &&
 isset($_REQUEST['product_description']) && $_REQUEST['product_description']!="")
{


	$check_status='';
	$productimage='';
	if($_REQUEST['imagestatus']=='1')
	{
		$product=$_REQUEST['image'];
		$date=date('Ymd').rand(10000,100000);
		$productimage="/images/product_library/".$_REQUEST['product_name'].$date.'.png';
		$productimage1=UPLOAD_DIRECTORY.$productimage;
		$check_status=imageupload($product,$productimage1);
	}
	else
	{
		$productimage="/images/product_library/defaultpic.jpg";
	}
	
	if($check_status=="Invalid")
	{
		$productimage="/images/product_library/defaultpic.jpg";
	}
	
	$query="INSERT INTO product_library(
			    	collection_id,
			    	product_name,
			    	product_description,
			    	image_name
			)
			VALUES(
				'".$_REQUEST['collection_id']."',
				'".$_REQUEST['product_name']."',
				'".$_REQUEST['product_description']."',
				'".$productimage."'
			)
			";
	$query=query($query);
	$result=confirm($query);

	if(!$result)	// Valid Request, Data Found.
	{
		$flag=false;
		rollback();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
	 	echo json_encode(array("createproduct"=>$temp));
	 	close();
 		exit();

	}
	if($flag)
	{
		commit();
		$temp=array();
		$temp['response_code']=200;
		$temp['response_desc']="Success";
		echo json_encode(array("createproduct"=>$temp));
		close();
 		exit();
	}
	else
	{
		rollback();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
	 	echo json_encode(array("createproduct"=>$temp));
	 	close();
 		exit();
	}
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
 	echo json_encode(array("createproduct"=>$temp));
 	close();
 	exit();
}

?>
