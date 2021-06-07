<?php	
header("Content-Type:application/json"); // setting content as well as we will convert data into json type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$connection->autocommit(FALSE);
$flag = true; 

$query="";

if(isset($_REQUEST['admin_id']) && $_REQUEST['admin_id']!="")
{
	$admin_id = $_REQUEST['admin_id'];
	
	$query = "  SELECT 
					userid,
					password,
					full_name,
					role,
					status,
					last_modified_by
				FROM 
					admin_user 
				WHERE
					userid='".$admin_id."'
				";

    $query=query($query);
	confirm($query);
	$rows=mysqli_num_rows($query);

	if($rows==1)	// Valid Request, Data Found.
	{
		$temp=array();
		$temp=fetch_array($query);
		$temp['response_code']=405;
		$temp['response_desc']="User name already exist";

		echo json_encode(array("getcreateadmindetails"=>$temp));
		close();
		exit();
	}
					
}
if((isset($_REQUEST['admin_id']) && $_REQUEST['admin_id']!="") && (isset($_REQUEST['full_name']) && $_REQUEST['full_name']!="") && (isset($_REQUEST['role']) && $_REQUEST['role']!="") && (isset($_REQUEST['password']) && $_REQUEST['password']!="") && (isset($_REQUEST['confirm_password']) && $_REQUEST['confirm_password']!="") && (isset($_REQUEST['current_user']) && $_REQUEST['current_user']!=""))
{

		$admin_id = $_REQUEST['admin_id'];
		$full_name = $_REQUEST['full_name'];
		$role = $_REQUEST['role'];
		$password = $_REQUEST['password'];
		$confirm_password = $_REQUEST['confirm_password'];
		$last_modified_by = $_REQUEST['current_user'];

			if($password == $confirm_password)
			{

				$query = "  INSERT  INTO admin_user
												(
													userid,
													full_name,
													role,
													password,
													last_modified_by
												)

									VALUES     
												(
													'".$admin_id."',
													'".$full_name."',
													'".$role."',
													'".$password."',
													'".$last_modified_by."'
			
												)";
			
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

				echo json_encode(array("getcreateadmindetails"=>$temp));
				close();
				exit();
				}
				else
				{
					rollback();
					$temp['response_code']=404;
					$temp['response_desc']="Invalid Operation";

					echo json_encode(array("getcreateadmindetails"=>$temp));
					close();
					exit();
				}
			}
			else
			{
				$temp['response_code']=405;
				$temp['response_desc']="Password don't matched";

				echo json_encode(array("getcreateadmindetails"=>$temp));
				close();
				exit();
			}
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";

	echo json_encode(array("getcreateadmindetails"=>$temp));
	close();
	exit();
}	

?>
