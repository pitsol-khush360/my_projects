<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
$connection->autocommit(FALSE);
$flag = true;
if(isset($_REQUEST['product_id'])&&$_REQUEST['product_id']!='')
{			
	
		$query="SELECT
				    BO.order_status
				FROM
				    orders O,
				    basket_order BO
				WHERE
				    O.product_id = '".$_REQUEST['product_id']."' 
				    AND 
				    O.order_id = BO.basket_order_id
				    AND
				    O.seller_id = '".$_REQUEST['user_id']."'
				    ";
		$query=query($query);
		confirm($query);
		$rows=mysqli_num_rows($query);
		//print_r($rows);
		if($rows!=0)
		{
			$temp=array();
			$temp['response_code']=405;			// 405 means 'Not Allowed'
			$temp['response_desc']="Deletion is not allowed please deactivate the Product.";
			$temp['rows']=$rows;
			echo json_encode(array("deleteproduct"=>$temp));
			close();
			exit();
		}
		if($rows==0)
		{
			$query="DELETE
					FROM
					    product_details
					WHERE
					    product_id = '".$_REQUEST['product_id']."'
					    AND
			    		product_seller_id = '".$_REQUEST['user_id']."'
					";
			$query=query($query);
			$result=confirm($query);
			if(!$result)
			{
				$flag=false;
				rollback();
				$temp=array();
				$temp['response_code']=404;			
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("deleteproduct"=>$temp));
				close();
				exit();
			}
			if($flag)
			{
				commit();
				$temp=array();
				$temp['response_code']=200;			
				$temp['response_desc']="Success";
				echo json_encode(array("deleteproduct"=>$temp));
				close();
				exit();
			}
			else
			{
				rollback();
				$temp=array();
				$temp['response_code']=404;			
				$temp['response_desc']="Invalid Operation";
				echo json_encode(array("deleteproduct"=>$temp));
				close();
				exit();
			}
			
		}	
	
}
else
{
	$temp=array();
	$temp['response_code']=400;			
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("deleteproduct"=>$temp));
	close();
	exit();
}
?>
