<?php	
header("Content-Type:application/json"); // setting content as well as we will convert data into json type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$connection->autocommit(FALSE);
$flag = true; 
$query="";

if(isset($_REQUEST['comission_type']) && $_REQUEST['comission_type']!="" && isset($_REQUEST['current_user']) && $_REQUEST['current_user']!="" && isset($_REQUEST['comission_percentage']) && $_REQUEST['comission_percentage']!="" && isset($_REQUEST['tax_on_commission']) && $_REQUEST['tax_on_commission']!="" )
{
	$comission_type=$_REQUEST['comission_type'];
	$tax_on_commission = $_REQUEST['tax_on_commission'];
	$comission_percentage = $_REQUEST['comission_percentage'];
	$current_user = $_REQUEST['current_user'];

	$query="INSERT  INTO    commission_charges
									(
										comission_type,
										comission_percentage,
										tax_on_commission,
										last_modified_by

									)
					VALUES     
									(
										'".$comission_type."',
										'".$comission_percentage."',
										'".$tax_on_commission."',
										'".$current_user."'
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

					echo json_encode(array("addcommisioncharge"=>$temp));
					close();
					exit();
				}
				else
				{
					rollback();
					$temp['response_code']=404;
					$temp['response_desc']="Invalid Operation";

					echo json_encode(array("addcommisioncharge"=>$temp));
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
