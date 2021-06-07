<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!='')
{
	$user_id=$_REQUEST['user_id'];
	$start=0;
	$end=10;
	if(isset($_REQUEST['start']) &&$_REQUEST['start']!='')
	{
		$start=$_REQUEST['start'];
	}
	if(isset($_REQUEST['end']) &&$_REQUEST['end']!='')
	{
		$end=$_REQUEST['end'];
	}
	if(isset($_REQUEST['ticket_id'])&&$_REQUEST['ticket_id']!='')
	{
		$query="
				SELECT
				    `ticket_id`,
				    `seller_id`,
				    `mobile`,
				    `subject`,
				    `description`,
				    `resolution_remarks`,
				    `status`,
				    `created_date`
				FROM
				    tickets
				WHERE
				    seller_id = '".$user_id."' 
				    AND 
				    ticket_id = '".$_REQUEST['ticket_id']."'
				";
	}
	else
	{
		$query="
				SELECT
				    *
				FROM
				    tickets
				WHERE
				    seller_id = '".$user_id."'
				ORDER BY
				    ticket_id
				DESC
				LIMIT ".$start.", ".$end."
				";
	}
	$query=query($query);
	confirm($query);
	$row='';
	$mobile='';
	$temp=array();
	while($row=fetch_array($query))
	{	
	$temp[]=$row;
	}
	$rows=mysqli_num_rows($query);
	if($rows>0)
	{
		$temp['rows']=$rows;
		$temp['response_code']=200;			
		$temp['response_desc']="Success";
		echo json_encode(array("ticketlist"=>$temp));
		close();
		exit();
	}
	else
	{
		$temp['rows']=$rows;
		$temp['response_code']=405;			
		$temp['response_desc']="No Rcords Found";
		echo json_encode(array("ticketlist"=>$temp));
		close();
		exit();
	}
						
	
	
}
else
{
	$temp=array();
	$temp['response_code']=400;			
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("ticketlist"=>$temp));
	close();
	exit();
}
close();
?>
