<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
include('../../config/config.php');
require_once("../../config/".ENV."_config.php");
require_once("../validation.php");
if(isset($_REQUEST['user_id']) && $_REQUEST['user_id'])
	{
		$query="SELECT
				    rating,
				    COUNT(rating) AS count
				FROM
				    reviews
				GROUP BY
				    rating
			    ";
		$query=query($query);
		confirm($query);
		$total=0;
		$sum=0;
		while($row=fetch_array($query))
		{
			$total+=$row['rating']*$row['count'];
			$sum+=$row['count'];
		}
		$query="
				SELECT
				    US.username,
				    RS.rating,
				    RS.review_title,
				    RS.review
				FROM
				    users US,
				    reviews RS
				WHERE
				    US.user_id = RS.seller_id 
				    AND 
				    RS.seller_id = '".$_REQUEST['user_id']."'
				";
		$query=query($query);
		confirm($query);
		$temp=array();
		while($row=fetch_array($query))
		{
			$temp['username']=$row['username'];
			$temp['rating']=$row['rating'];
			$temp['review_title']=$row['review_title'];
			$temp['review']=$row['review'];
		}
		$query="
				SELECT
				    US.username,
				    RS.rating,
				    RS.review_title,
				    RS.review,
				    RS.creation_date_time,
				    DATEDIFF(
				        CURRENT_TIMESTAMP,
				        RS.creation_date_time
				    ) AS day
				FROM
				    users US,
				    reviews RS
				WHERE
				    US.user_id = RS.seller_id
				ORDER BY
				    RS.creation_date_time
				DESC
    
				";
		$query=query($query);
		confirm($query);
		$rows=mysqli_num_rows($query);
		while($row=fetch_array($query))
		{
			if($row['day']==0)
			{
				$row['day']='Today';
			}
			else if($row['day']==1)
			{
				$row['day']='Yesterday';
			}
			else if($row['day']<=31)
			{
				$row['day']=$row['day']." days ago";
			}
			else
			{
				$row['day']=date('d-M-Y',strtotime($row['creation_date_time']));
			}
			$temp[]=$row;
		}
		if($rows>0)
		{
			$rating = round($total/$sum);
			$temp['total_rating']=$rating;
			$temp['rows']=mysqli_num_rows($query);
			$temp['response_code']=200;
			$temp['response_desc']="Success";
			$temp['rows']=$rows;
			echo json_encode(array("getreviews"=>$temp));
			close();
			exit();
		}
		
		else
		{
			$temp['response_code']=405;
			$temp['response_desc']="No results Found";
			$temp['rows']=$rows;
			echo json_encode(array("getreviews"=>$temp));
			close();
			exit();
		}
		//print_r($temp);
 		
	}
else
	{
		$temp=array();
		$temp['response_code']=400;
		$temp['response_desc']="Invalid Request";
		echo json_encode(array("getreviews"=>$temp));
		close();
		exit();
	}

close();
?>
