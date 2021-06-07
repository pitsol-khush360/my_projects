<?php

header("Content-Type:application/json");
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['mobile']) && $_REQUEST['mobile']!="")
{	
	$name=$_REQUEST['business_name'];
	$name = explode(' ',$name);
	$username = $name[0].rand(100,1000);
	try
	{
		while(true)
		{
			$query="
					SELECT
					    username
					FROM
					    users
					WHERE
					    username = '".$username."'
					";
			$query=query($query);
			confirm($query);
			if(mysqli_num_rows($query)==0)
			{
				break;
			}
			else
			{
				$username = $name.rand(100,1000);	
			}
		}
		
		$query="SELECT 
					* 
				FROM 
					users 
				WHERE
					mobile = '".$_REQUEST['mobile']."'";
		$query=query($query);
		//confirm($query);
		if(mysqli_num_rows($query)==0)
		{
		
			$query='INSERT INTO users(
					    mobile,
					    password,
					    username,
					    business_name,
					    mobile_verified,
					    accept_terms_and_conditions,
						status,
					    updated_datetime
					)
					VALUES(';
							$query.='"'.$_REQUEST['mobile'].'",'
							;
							$query.='"'.$_REQUEST['password'].'",'
							;
							$query.='"'.$username.'",'
							;
							$query.='"'.$_REQUEST['business_name'].'",'
							;
							$query.='"'.$_REQUEST['mobile_verified'].'",'
							;
							$query.='"'.$_REQUEST['accept_terms_and_conditions'].'",'
							;
							$query.='"A",'
							;
							$query.='NOW()
					)';
			$query=query($query);
			$result=confirm($query);
			if(!$result)
			{
				$flag=false;
			}
			$query="SELECT 
						* 
					FROM 
						users 
					WHERE
						mobile='".$_REQUEST['mobile']."'";
			$query=query($query);
			confirm($query);
			$row=fetch_array($query);
			$query='INSERT INTO seller_details(
					    seller_id,
					    seller_image,
					    seller_business_name,
					    pan_card_image,
					    gst_certificate_image,
					    address_proof_image,
					    cheque_image,
					    seller_alternate_number,
					    alternate_contact_verified,
					    accept_cod_payments,
					    email_verified,
					    notification_sms,
					    notification_email,
					    notification_whatsapp,
					    updated_datetime
					)
					VALUES(';
						$query.='"'.$row['user_id'].'",'
						;
						$query.='"/images/sellers/defaultpic.jpg",'
						;
						$query.='"'.$_REQUEST['business_name'].'",'
						;
						$query.='"/images/pancards/defaultpic.png",'
						;
						$query.='"/images/gst/defaultpic.png",'
						;
						$query.='"/images/addressproofs/defaultpic.png",'
						;
						$query.='"/images/cheques/defaultpic.png",'
						;
						$query.='"'.$_REQUEST['mobile'].'",'
						;
						$query.='"Yes",'
						;
						$query.='"1",'
						;
						$query.='"1",'
						;
						$query.='"0",'
						;
						$query.='"1",'
						;
						$query.='"0",'
						;
						$query.='NOW()
					)';
			$query=query($query);
			$result=confirm($query);
			if(!$result)
			{
				$flag=false;
				rollback();
				$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("register"=>$temp));
				close();
				exit();
			}
			$query='INSERT INTO product_default_settings(
					    seller_id,
					    discount_type,
					    discount_percent,
					    tax_type,
					    tax_percent,
					    free_shipping,
					    return_available,
					    cash_on_delivery,
					    warrant_type,
					    warrant_duration,
					    warranty_days_mon_yr
					)
					VALUES(';
							$query.='"'.$row['user_id'].'",'
							;
							$query.='"None",'
							;
							$query.='0,'
							;
							$query.='"None",'
							;
							$query.='0,'
							;
							$query.='0,'
							;
							$query.='0,'
							;
							$query.='1,'
							;
							$query.='"Not Applicable",'
							;
							$query.='0,'
							;
							$query.='0)
						';
			$query=query($query);
			$result=confirm($query);
			if(!$result)
			{
				$flag=false;
				rollback();
				$temp=array();
				$temp['response_code']=404;
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("register"=>$temp));
				close();
				exit();
			}
			if($flag)
			{
			commit();
			$temp=array();
			$temp['user_id']=$row['user_id'];			
			$temp['mobile']=$row['mobile'];
			$temp['business_name']=$row['business_name'];		
			$temp['username']=$row['username'];
			$temp['role']=$row['role'];
			$temp['response_code']=200;			
			$temp['response_desc']="success";
			echo json_encode(array("register"=>$temp));
			close();
			exit();
			}
			else
			{
			rollback();
			$temp=array();
			$temp['response_code']=404;
			$temp['response_desc']="Invalid Operation";
			echo json_encode(array("register"=>$temp));
			close();
			exit();
			}
		}
		else
		{	
			$temp=array();
			$temp['response_code']=405;
			$temp['response_desc']="Mobile number already exists";
			echo json_encode(array("register"=>$temp));
			close();
			exit();
		}
	}
	catch(Exception $e)
	{
		rollback();
		close();
		$temp=array();
		$temp['response_code']=500;
		$temp['response_desc']="Unable to do operations";
		echo json_encode(array("register"=>$temp));
		return ;
	}
}
else
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Mobile number And Password Are Required";
	echo json_encode(array("register"=>$temp));
	close();
	exit();
}
$connection->autocommit(FALSE);
close();
?>
