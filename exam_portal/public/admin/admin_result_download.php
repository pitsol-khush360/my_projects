<?php include("../../resources/config.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

</body>
</html>
<?php
	function getResult($eid)
	{
		$data="";

		$query=query("select * from result where eid='".$eid."' order by marks_obtained desc");
		confirm($query);

		$data.=<<<data
		<table style="border:1px solid grey;border-collapse: collapse;width:100%;">
		    <tr>
	          <th style="border:1px solid grey;width:10%;text-align:center;">S.No</th>
	          <th style="border:1px solid grey;width:15%;text-align:center;">Name</th>
	          <th style="border:1px solid grey;width:16%;text-align:center;">Username</th>
	          <th style="border:1px solid grey;width:10%;text-align:center;">Total Question </th>
	          <th style="border:1px solid grey;width:13%;text-align:center;">Total Attempted</th>
	          <th style="border:1px solid grey;width:8%;text-align:center;">Right</th>
	          <th style="border:1px solid grey;width:8%;text-align:center;">Wrong</th>
	          <th style="border:1px solid grey;width:11%;text-align:center;">Marks Obtained</th>
	          <th style="border:1px solid grey;width:9%;text-align:center;">Total Marks</th>
	        </tr>
data;

    	$i=1;
		while($row=fetch_array($query))
		{
			$name="";
		    $username="";
		      
		      $q_ul=query("select * from user_login  where userid='{$row['ulid']}' ");
		      confirm($q_ul);

		      if(mysqli_num_rows($q_ul)!=0)
		      {
		        $r_ul=fetch_array($q_ul);
		        $username=$r_ul['username'];
		      }

		      $q_up=query("select * from user_personal where ulid ='{$r_ul['userid']}'");
		      confirm($q_up);

		      if(mysqli_num_rows($q_ul)!=0)
		      {
		        $r_up=fetch_array($q_up);
		        $name=$r_up['name'];
		      }

	    $data.=<<< data
	    <tr style="background-color:lightblue;">
	      <td style="border:1px solid grey;width:10%;text-align:center;">
	        {$i}
	      </td>
	      <td style="border:1px solid grey;width:15%;">
	        {$name}
	      </td>
	      <td style="border:1px solid grey;width:16%;text-align:center;">
	        {$username}
	      </td>
	      <td style="border:1px solid grey;text-align:right;width:10%;">
	        {$row['total_questions']}
	      </td>
	      <td style="border:1px solid grey;text-align:right;width:13%;">
	        {$row['total_attempted']}
	      </td>
	      <td style="border:1px solid grey;text-align:right;width:8%;">
	        {$row['right_questions']}
	      </td>
	      <td style="border:1px solid grey;text-align:right;width:8%;">
	        {$row['wrong_questions']}
	      </td>
	      <td style="border:1px solid grey;text-align:right;width:11%;">
	        {$row['marks_obtained']}
	      </td>
	      <td style="border:1px solid grey;text-align:right;width:9%;">
	        {$row['total_marks']}
	      </td>
	    </tr>
data;
	    $i++;
	  }

	  $data.="</table>";

	  return $data;
	}

	if(isset($_POST['result_download']) && isset($_POST['eid']))
	{
		$eid=$_POST['eid'];

		// pdf
		ob_start();          // starting output buffering.

		require_once '../../resources/mcpdf/vendor/autoload.php';
		
		$stylesheet = file_get_contents('css/stylepdf.css');

      	$mpdf = new \Mpdf\Mpdf();

      	// content

		  $exam_name="";

	      $query_e=query("select title from exam where eid='".$eid."'");
	      confirm($query_e);

	      if(mysqli_num_rows($query_e)!=0)
	      {
	        $r_e=fetch_array($query_e);
	        $exam_name=$r_e['title'];
	      }


		$data="";
		$data.="<h3 style=\"color:green;text-align:center;\"><b>".APP."</b></h3>";
		$data.="<h4 style=\"text-align:center;\"><b>\"".$exam_name."\" Result</b></h4>";

	  	$data.="<br><br><br>";
		$data.=getResult($eid);

		// pdf
		$mpdf->WriteHTML($stylesheet, 1);

        $mpdf->SetWatermarkText('AbhyasClasses');
		$mpdf->showWatermarkText = true;
		$mpdf->watermarkTextAlpha = 0.1;

		$mpdf->WriteHTML($data);
        $mpdf->Output($exam_name.'_result.pdf',"D");

		redirect("admin_result_result.php?eid=".$eid);
	}
	else
		redirect("admin_result_exam.php");
?>