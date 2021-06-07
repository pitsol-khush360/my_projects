<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['mobile']) && $_REQUEST['mobile']!='')
{
	
	$query="SELECT
			    user_id
			FROM
			    users
			WHERE
			    mobile = '".$_REQUEST['mobile']."'
			";
	$query=query($query);
	confirm($query);
	$row=fetch_array($query);
	$user=$row['user_id'];
	$query="DELETE
			FROM
			    users
			WHERE
			    user_id = '".$user."'
			";
	$query=query($query);
	$result = confirm($query);
	if(!$result)
	{
		$flag=false;
	}
	$query="DELETE
			FROM
			    promocodes
			WHERE
			    seller_id = '".$user."'
			";
	$query=query($query);
	$result = confirm($query);
	if(!$result)
	{
		$flag=false;
	}
	$query="DELETE
			FROM
			    product_catalogue
			WHERE
			    catalogue_seller_id = '".$user."'
			";
	$query=query($query);
	$result = confirm($query);
	if(!$result)
	{
		$flag=false;
	}
	$query="DELETE
			FROM
			    delivery_charges
			WHERE
			    seller_id = '".$user."'
		    ";
	$query=query($query);
	$result = confirm($query);
	if(!$result)
	{
		$flag=false;
	}
	$query="DELETE
			FROM
			    product_default_settings
			WHERE
			    seller_id = '".$user."'
		    ";
	$query=query($query);
	$result = confirm($query);
	if(!$result)
	{
		$flag=false;
	}
	$query="DELETE
			FROM
			    product_details
			WHERE
			    product_seller_id = '".$user."'
			";
	$query=query($query);
	$result = confirm($query);
	if(!$result)
	{
		$flag=false;
	}
	$query="DELETE
			FROM
			    reviews
			WHERE
			    seller_id = '".$user."'
		    ";
	$query=query($query);
	$result=confirm($query);
	if(!$result)
	{
		$flag=false;
	}
	$query="DELETE
			FROM
			    seller_details
			WHERE
			    seller_id = '".$user."'";
	$query=query($query);
	$result=confirm($query);
	
	if(!$result)
	{
		$flag=false;
	}
	$query="DELETE
			FROM
			    shipping_charges
			WHERE
			    seller_id = '".$user."'
			";
	$query=query($query);
	$result=confirm($query);
	if(!$result)
	{
		$flag=false;
	}
	$query="DELETE
			FROM
			    tickets
			WHERE
			    seller_id = '".$user."'
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
		$temp['response_desc']="success";
		echo json_encode(array("delete"=>$temp));
	}
	else
	{
		rollback();
		$temp=array();
		$temp['response_code']=404;			
		$temp['response_desc']="Invalid Operation";
		echo json_encode(array("delete"=>$temp));
	}
}
else
{
	$temp=array();
	$temp['response_code']=400;			
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("delete"=>$temp));
}
close();
?>
