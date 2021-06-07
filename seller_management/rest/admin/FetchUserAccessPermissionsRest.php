<?php
header("Content-Type:application/json"); // setting content as well as we will convert data into json type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");

require_once('../validation.php');

$query="";

if(isset($_REQUEST['admin_id']) && $_REQUEST['admin_id']!="")
{
	$userid = $_REQUEST['admin_id'];

	$query = "  SELECT 
						X.id,
						X.ROLE_NAME
						 
			    FROM 
			    		application_role X 
			    WHERE   
			    		X.id NOT IN (
			    		
			    		SELECT  
			    				R.id 
			    		FROM 
			    				application_role R, user_role U 
			    		WHERE 
			    				R.id = U.APPLICATION_ROLE_ID 
			    				AND 
			    				U.USER_NAME = '".$userid."')";
					
	$query=query($query);

	confirm($query);
	$temp1 = array();
	$rows=mysqli_num_rows($query);

	$temp=array();
	
	if($rows!=0)	// Valid Request, Data Found.
	{
		while($row=fetch_array($query))
		{
			$temp[]=$row;
		}
	}
	$temp1['availablepermission'] = $temp;
	$query1 = " SELECT  
						R.id,
						R.ROLE_NAME
				FROM
						application_role R, user_role U 
				WHERE  
						R.id = U.APPLICATION_ROLE_ID 
						AND
						U.USER_NAME = '".$userid."'";
					
	$query1=query($query1);
	confirm($query1);
	$rows1=mysqli_num_rows($query1);

	
		$temp=array();
		while($row1=fetch_array($query1))
		{
			$temp[]=$row1;
		}
		$temp1['givenpermission'] = $temp;
		$temp1['response_code']=200;
		$temp1['response_desc']="success";
		$temp1['rows']=$rows1;
	
	echo json_encode(array("permissiondetails"=>$temp1));
	close();
	exit();
}
else
	{
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("permissiondetails"=>$temp));
	close();
	exit();
	}
?>
