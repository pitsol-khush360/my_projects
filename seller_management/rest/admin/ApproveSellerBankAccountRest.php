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
				bank_account_verified = 'Yes'
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
				$temp['response_desc']="Succces";

				echo json_encode(array("approveBamkAccount"=>$temp));
				close();
				exit();
				}
				else
				{
					rollback();
					$temp['response_code']=404;
					$temp['response_desc']="Invalid Operation";

					echo json_encode(array("approveBamkAccount"=>$temp));
					close();
					exit();
				}
		}

		if($confirm == 'NO')
		{
		$query="UPDATE 
				seller_details
			SET 
			bank_account_verified
			 = 'No'
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
				$temp['response_desc']="Succces";

				echo json_encode(array("approveBamkAccount"=>$temp));
				close();
				exit();
				}
				else
				{
					rollback();
					$temp['response_code']=404;
					$temp['response_desc']="Invalid Operation";

					echo json_encode(array("approveBamkAccount"=>$temp));
					close();
					exit();
				}
		}
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";

	echo json_encode(array("approveBamkAccount"=>$temp));
	close();
	exit();
}	

?>
