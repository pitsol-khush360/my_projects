<?php	
header("Content-Type:application/json"); // setting content as well as we will convert data into json type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$connection->autocommit(FALSE);
$flag = true; 
$query="";

if(isset($_REQUEST['product_id']) && $_REQUEST['product_id']!="")
{
	$product_id=$_REQUEST['product_id'];
	
	$query="DELETE
			FROM
				product_library
			WHERE
				product_id='".$product_id."'
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
					$temp['response_desc']="Succces";

					echo json_encode(array("deleteproduct"=>$temp));
					close();
					exit();
				}
				else
				{
					rollback();
					$temp['response_code']=404;
					$temp['response_desc']="Invalid Operation";

					echo json_encode(array("deleteproduct"=>$temp));
					close();
					exit();
				}
		
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";

	echo json_encode(array("deletecommisioncharge"=>$temp));
	close();
	exit();
}	

?>
