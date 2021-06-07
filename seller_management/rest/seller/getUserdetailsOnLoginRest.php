<?php
header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['authentication_method']) && $_REQUEST['authentication_method']=='password')
{
	if(isset($_REQUEST['mobile']) && $_REQUEST['mobile']!="" && $_REQUEST['password']!="")
	{		
		$query=query("
						SELECT
						    US.user_id,
						    US.mobile,
						    US.business_name,
						    US.username,
							US.status,
						    US.role,
						    SD.seller_image
						FROM
						    users US,
						    seller_details SD
						WHERE
						    US.mobile = '".$_REQUEST['mobile']."' 
						    AND 
						    US.password = '".$_REQUEST['password']."' 
						    AND 
						    US.user_id = SD.seller_id
					");
		confirm($query);

		if(mysqli_num_rows($query)!=0)
		{
			$row=fetch_array($query);

			if($row['status']=="A")
			{
				$temp=array();
				
				$temp['user_id']=$row['user_id'];
				$temp['mobile']=$row['mobile'];
				$temp['business_name']=$row['business_name'];
				$temp['username']=$row['username'];
				$temp['status']=$row['status'];
				$temp['role']=$row['role'];
				$temp['seller_image']=$row['seller_image'];
				$temp['response_code']=200;
				$temp['login_authentication']="success";
				echo json_encode(array("login"=>$temp));
			}
			else
			{
				$temp=array();
				$temp['response_code']=405;
				$temp['login_authentication']="This User Account Is Not Active. Please Contact Customer Care";
				$temp['user_id']=$row['user_id'];
				echo json_encode(array("login"=>$temp));
			}
		}
		else
		{	$temp=array();
			$temp['response_code']=404;
			$temp['login_authentication']="No Results Found";
			echo json_encode(array("login"=>$temp));
		}
	}
}
else if(isset($_REQUEST['authentication_method']) && $_REQUEST['authentication_method']=='otp')
{
	if(isset($_REQUEST['mobile']) && $_REQUEST['mobile']!="" )
	{		
		$query=query("
						SELECT
						    US.user_id,
						    US.mobile,
						    US.business_name,
						    US.username,
							US.status,
						    US.role,
						    SD.seller_image
						FROM
						    users US,
						    seller_details SD
						WHERE
						    US.mobile = '".$_REQUEST['mobile']."' 
						    AND 
						    US.user_id = SD.seller_id
					");
		confirm($query);
		$rows=mysqli_num_rows($query);
		if(mysqli_num_rows($query)!=0)
		{
			$row=fetch_array($query);

			if($row['status']=="A")
			{
				$temp=array();
				
				$temp['user_id']=$row['user_id'];
				$temp['mobile']=$row['mobile'];
				$temp['business_name']=$row['business_name'];
				$temp['username']=$row['username'];
				$temp['status']=$row['status'];
				$temp['role']=$row['role'];
				$temp['seller_image']=$row['seller_image'];
				$temp['response_code']=200;
				$temp['login_authentication']="success";
				echo json_encode(array("login"=>$temp));
				close();
				exit();
			}
			else
			{
				$temp=array();
				$temp['response_code']=405;
				$temp['login_authentication']="Your account has been blocked, contact customer support Team to activate your account";
				$temp['user_id']=$row['user_id'];
				$temp['rows']=$rows;
				echo json_encode(array("login"=>$temp));
				close();
				exit();
			}
		}
		else
		{	$temp=array();
			$temp['response_code']=405;
			$temp['login_authentication']="Invalid User";
			$temp['rows']=$rows;
			echo json_encode(array("login"=>$temp));
			close();
			exit();
		}
	}
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['login_authentication']="Fail";
	echo json_encode(array("login"=>$temp));
}

close();	
?>

