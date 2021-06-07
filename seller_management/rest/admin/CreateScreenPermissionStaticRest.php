<?php	
header("Content-Type:application/json"); // setting content as well as we will convert data into json type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$connection->autocommit(FALSE);
$flag = true; 
$query="";

if(isset($_REQUEST['screen_name']) && $_REQUEST['screen_name']!="" && isset($_REQUEST['permission_name']) && $_REQUEST['permission_name']!="" && isset($_REQUEST['ROLE_NAME']) && $_REQUEST['ROLE_NAME']!="")
{
	$screen_name=$_REQUEST['screen_name'];
	$permission_name=$_REQUEST['permission_name'];
	$ROLE_NAME=$_REQUEST['ROLE_NAME'];
	
	$query="INSERT  INTO    application_role
									(
										screen_name,
										permission_name,
										ROLE_NAME
									)
					VALUES     
									(
										'".$screen_name."',
										'".$permission_name."',
										'".$ROLE_NAME."'
									)
			";
			

				
			

				$query=query($query);
				$result = confirm($query);
				if( !$result)
				{
					$flag = false;
				}
				$temp = array();
				if($flag)
				{
					commit();
					$temp['response_code']=200;
					$temp['response_desc']="Succces";
					

					echo json_encode(array("addpermission"=>$temp));
					close();
					exit();
				}
				else
				{
					rollback();
					$temp['response_code']=404;
					$temp['response_desc']="Invalid Operation";

					echo json_encode(array("addpermission"=>$temp));
					close();
					exit();
				}
		
}
else
{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";

	echo json_encode(array("addcommisioncharge"=>$temp));
	close();
	exit();
}	
?>
