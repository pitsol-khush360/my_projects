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
					status
				FROM 
					admin_user 
				WHERE
					userid='".$admin_id."'";

    $query=query($query);
	confirm($query);
	$rows=mysqli_num_rows($query);

	if($rows==1)	// Valid Request, Data Found.
	{
		$temp=array();
		$temp=fetch_array($query);

		if($temp['status'] == 'Active')
		{	

			$temp['response_code']= 405;
			$temp['response_desc']="Active User cannot be deleted.Please suspend the user and then delete";

			echo json_encode(array("deleteadmindetails"=>$temp));
			close();
			exit();
		}
		else
		{
			$query = "  DELETE
						FROM 
							admin_user 
						WHERE
							userid='".$admin_id."'";

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
				$temp['response_desc']="Success";

				echo json_encode(array("deleteadmindetails"=>$temp));
				close();
				exit();
			}
			else
			{
				rollback();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";

				echo json_encode(array("deleteadmindetails"=>$temp));
				close();
				exit();
			}
		}
	}
	else
	{
		$temp['response_code']=405;
		$temp['response_desc']="Record Not found";

		echo json_encode(array("deleteadmindetails"=>$temp));
		close();
		exit();
	}						
}
?>
