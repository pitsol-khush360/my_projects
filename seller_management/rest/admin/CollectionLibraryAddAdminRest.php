<?php
header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['collection_name']) && $_REQUEST['collection_name']!="" && isset($_REQUEST['imagestatus']) && $_REQUEST['imagestatus']!="")
{


	$check_status='';
	$collectionimage='';
	if($_REQUEST['imagestatus']=='1')
	{
		$collection=$_REQUEST['image'];
		$date=date('Ymd').rand(10000,100000);
		$collectionimage="/images/collection_library/".$_REQUEST['collection_name'].$date.'.png';
		$collectionimage1=UPLOAD_DIRECTORY.$collectionimage;
		$check_status=imageupload($collection,$collectionimage1);
	}
	else
	{
		$collectionimage="/images/collection_library/defaultpic.jpg";
	}
	
	if($check_status=="Invalid")
	{
		$collectionimage="/images/collection_library/defaultpic.jpg";
	}
	
	$query="INSERT INTO collection_library(
			    	collection_name,
			    	image_name
			)
			VALUES(
				'".$_REQUEST['collection_name']."',
				'".$collectionimage."'
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
	 	echo json_encode(array("createcollection"=>$temp));
	 	close();
 		exit();

	}
	if($flag)
	{
		commit();
		$temp=array();
		$temp['response_code']=200;
		$temp['response_desc']="Success";
		echo json_encode(array("createcollection"=>$temp));
		close();
 		exit();
	}
	else
	{
		rollback();
		$temp['response_code']=404;
		$temp['response_desc']="Invalid Operation";
	 	echo json_encode(array("createcollection"=>$temp));
	 	close();
 		exit();
	}
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
 	echo json_encode(array("createcollection"=>$temp));
 	close();
 	exit();
}

?>
