<?php	
header("Content-Type:application/json"); // setting content as well as we will convert data into json type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$connection->autocommit(FALSE);
$flag = true; 
$query="";

if(isset($_REQUEST['seller_id']) && $_REQUEST['seller_id']!="" && isset($_REQUEST['current_user']) && $_REQUEST['current_user']!="" && isset($_REQUEST['shipping_amount']) && $_REQUEST['shipping_amount']!="")
{
	$seller_id=$_REQUEST['seller_id'];
	$current_user = $_REQUEST['current_user'];
	$shipping_amount = $_REQUEST['shipping_amount'];

	$query="UPDATE 
				shipping_charges
			SET 
				shipping_amount = '".$shipping_amount."',
				last_modified_by ='".$current_user."'
			WHERE
				seller_id='".$seller_id."'
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

					echo json_encode(array("modifyshippingcharge"=>$temp));
					close();
					exit();
				}
				else
				{
					rollback();
					$temp['response_code']=404;
					$temp['response_desc']="Invalid Request";

					echo json_encode(array("modifyshippingcharge"=>$temp));
					close();
					exit();
				}
		
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";

	echo json_encode(array("modifyshippingcharge"=>$temp));
	close();
	exit();
}	

?>
