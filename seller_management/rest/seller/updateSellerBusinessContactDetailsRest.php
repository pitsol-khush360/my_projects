<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='' && isset($_REQUEST['businessname'])&&$_REQUEST['businessname']!=''&& isset($_REQUEST['alt_number'])&&$_REQUEST['alt_number']!='' )
{	
	
		$query="
				UPDATE
				    users
				SET
				    business_name = '".$_REQUEST['businessname']."'
				WHERE
				    user_id = '".$_REQUEST['user_id']."'
				";
		//echo $query;
		$query=query($query);
		
		$result=confirm($query);
		if(!$result)
		{
			$flag=false;
		}
		$query="
				UPDATE
				    seller_details
				SET
				    seller_business_name = '".$_REQUEST['businessname']."',
				    seller_email = '".$_REQUEST['email']."',
				    email_verified = '1',
				    alternate_contact_verified = 'Yes',
				    seller_alternate_number = '".$_REQUEST['alt_number']."'
				WHERE
				    seller_id = '".$_REQUEST['user_id']."'
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
			$temp['businessname']=$_REQUEST['businessname'];
			$temp['response_code']=200;
			$temp['response_desc']="Success";
	 		echo json_encode(array("updatebusinessname"=>$temp));
	 		close();
	 		exit();

		}
		else
		{
			rollback();
			$temp=array();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("updatebusinessname"=>$temp));
			close();
			exit();
		}
	
	
	
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("updatebusinessname"=>$temp));
	close();
	exit();
}
	close();
?>
