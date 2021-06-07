<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['user_id'])&&$_REQUEST['user_id']!='')
{		
	$date = date("y-m-d");
	$user_id=$_REQUEST['user_id'];
	$date=strtotime(date('Y-m-d'));
	$date = strtotime("+1 day", $date);
	$query="
			SELECT
			    COUNT(*) AS count,
			    DATE(order_date) AS date
			FROM
			    basket_order
			WHERE
			    order_date 
		    BETWEEN 
		    	DATE(DATE_SUB(DATE(NOW()),INTERVAL 6 DAY)) AND '".date('Y-m-d',$date)."'
			    AND 
		        seller_id = '".$_REQUEST['user_id']."' 
		        AND 
		        order_status NOT IN('Draft')
		    GROUP BY
		        DATE(order_date)
			";
	//echo $query;
	$query=query($query);
	confirm($query);
	
	$temp=array();	
	$date = date('Y-m-d');
	$temp[$date]=0;
	$j=1;
	while($j<7)
	{
					
		$date=$date;
		$date = strtotime($date);
		$date = strtotime("-1 day", $date);
		$date = date('Y-m-d', $date);
		$temp[$date]=0;
		$j++;
	}
	$i=0;
	while($row=fetch_array($query))
		{
				
			$temp[$row['date']]=$row['count'];
			
		}
	ksort($temp);
	$temp1=array();
	foreach ($temp as $key => $value){
		$date = strtotime($key);
		$date = date('d-M', $date);
		$temp1[$date]= $temp[$key];
		}
	$temp1['response_code']=200;
	$temp1['response_desc']="success";
 	echo json_encode(array("getdashboard"=>$temp1));
 	close();
 	exit();
 }
		
	
	else
	{
		$temp=array();
		$temp['response_code']=400;
		$temp['response_desc']="Invalid Request";
 		echo json_encode(array("getdashboard"=>$temp));
 		close();
 		exit();
	}
close();
?>
