<?php 
	require_once("../../config/config.php"); 
	require_once("../../config/".ENV."_config.php");
?>

<?php
	if(isset($_SESSION['current_user']))
	{
		if(isset($_POST['admin_id']) && isset($_POST['permissions_count']) && isset($_POST['permissions']))
		{
			$arr=explode(",",$_POST['permissions']);

			$data=array();

			$data[0]=$_POST['admin_id'];
			$data[1]=$_POST['permissions_count'];
			
			$i=2;

			foreach($arr as $value)
			{
				if($value!="" && !is_null($value))
				{
					$data[$i]=$value;
					$i++;
				}
			}

			$url=DOMAIN.'/rest/admin/UpdateAdminUserPermissionsRest.php';
			$output=getRestApiResponse($url,$data);

			if(isset($output['updateuserpermission']) && $output['updateuserpermission']['response_code']==200)
			{
				$response['status']=1;
				echo json_encode($response);
			}
			else
			{
				$response['status']=0;
				echo json_encode($response);
			}
		}
	}
?>