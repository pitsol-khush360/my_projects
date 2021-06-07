<?php	
header("Content-Type:application/json"); // setting content as well as we will convert data into json type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$connection->autocommit(FALSE);
$flag = true; 
$query="";

if(isset($_REQUEST['seller_id']) && $_REQUEST['seller_id']!="" && isset($_REQUEST['current_user']) && $_REQUEST['current_user']!="" && isset($_REQUEST['shipping_amount']) && $_REQUEST['shipping_amount']!="" )
{
	$seller_id=$_REQUEST['seller_id'];
	$current_user = $_REQUEST['current_user'];
	$shipping_amount = $_REQUEST['shipping_amount'];

	$query="INSERT  INTO    shipping_charges
									(
										seller_id,
										shipping_amount,
										last_modified_by

									)
					VALUES     
									(
										'".$seller_id."',
										'".$shipping_amount."',
										'".$current_user."'
									)
			";
			
				$query=query($query);
				$result = confirm($query);
				if( !$result)
				{
					$flag = false;
				}
				$temp=array();
				if($flag)
				{
					commit();
					$temp['response_code']=200;
					$temp['response_desc']="Succcess";

					echo json_encode(array("addshippingcharge"=>$temp));
					close();
					exit();
				}
				else
				{
					rollback();
					$temp['response_code']=404;
					$temp['response_desc']="Invalid Request";

					echo json_encode(array("addshippingcharge"=>$temp));
					close();
					exit();
				}
		
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";

	echo json_encode(array("addshippingcharge"=>$temp));
	close();
	exit();
}	
?>
