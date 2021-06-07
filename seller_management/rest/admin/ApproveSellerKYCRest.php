<?php	
header("Content-Type:application/json"); // setting content as well as we will convert data into json type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$connection->autocommit(FALSE);
$flag = true; 
$query="";

if(isset($_REQUEST['seller_id']) && $_REQUEST['seller_id']!="" && isset($_REQUEST['confirm']) && $_REQUEST['confirm']!="" )
{
	$seller_id=$_REQUEST['seller_id'];
	$confirm = $_REQUEST['confirm'];

	if($confirm == 'YES')
	{
	$query="UPDATE 
				seller_details
			SET 
				kyc_completed = 1,
				kyc_application_status = 'Processed'
			WHERE
				seller_id='".$seller_id."'";

				$query=query($query);
				$result = confirm($query);
				if( !$result)
				{
					$flag = false;
				}
				$temp=array();
				if($flag){
				commit();
				$temp['response_code']=200;
				$temp['response_desc']="Succcess";

				echo json_encode(array("approveKYC"=>$temp));
				close();
				exit();
				}
				else
				{
					rollback();
					$temp['response_code']=404;
					$temp['response_desc']="Invalid Operation";

					echo json_encode(array("approveKYC"=>$temp));
					close();
					exit();
				}
		}

		if($confirm == 'NO')
		{
		$query="UPDATE 
				seller_details
			SET 
				kyc_completed = 0,
				kyc_application_status = 'Processed'
			WHERE
				seller_id='".$seller_id."'";

				$query=query($query);
				$result = confirm($query);
				if( !$result)
				{
					$flag = false;
				}
				$temp=array();
				if($flag){
				commit();
				$temp['response_code']=200;
				$temp['response_desc']="Succcess";

				echo json_encode(array("approveKYC"=>$temp));
				close();
				exit();
				}
				else
				{
					rollback();
					$temp['response_code']=404;
					$temp['response_desc']="Invalid Operation";

					echo json_encode(array("approveKYC"=>$temp));
					close();
					exit();
				}
		}
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";

	echo json_encode(array("approveKYC"=>$temp));
	close();
	exit();
}	

?>
