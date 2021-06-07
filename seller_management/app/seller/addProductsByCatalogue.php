<?php 
	require_once("../../config/config.php"); 
	require_once("../../config/".ENV."_config.php");
?>

<?php
	if(isset($_SESSION['user_id']))
	{
		if(isset($_POST['product_name']) && isset($_POST['product_price']) && isset($_POST['pcid']))
		{
			$data['user_id']=$_SESSION['user_id'];
			$data['product_name']=$_POST['product_name'];
			$data['product_catalogue_id']=$_POST['pcid'];
			$data['product_price']=$_POST['product_price'];
			$data['product_unit']=$_POST['product_unit'];
			$data['product_price_currency']="INR";
			$data['product_description']=$_POST['product_desc'];

			$image=$_FILES['productimage']['tmp_name'];

			if(empty($image) || $image=="")
				$data['imagestatus']=0;
			else
			{
				$data['imagestatus']=1;
				$image = file_get_contents($image);
				$image= base64_encode($image);
				$data['image']=$image;
			}

			$url=DOMAIN.'/rest/seller/CreateProductDetailsRest.php';
			$output=getRestApiResponse($url,$data);
		
			if(isset($output['createproduct']) && $output['createproduct']['response_code']==200)
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