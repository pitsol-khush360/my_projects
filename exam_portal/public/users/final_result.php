<?php include("validateUserMultipleLogin.php"); ?>

<!DOCTYPE html>
<html>
<head>
	<title>Your Response</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>

<?php 

if(!isset($_SESSION['exam_portal']))
{
	redirect("index.php?show_exams");
	exit;
}

if(isset($_GET['issue']))
{
	if(isset($_SESSION['eid']))
		unset($_SESSION['eid']);

	if(isset($_SESSION['seids']))
		unset($_SESSION['seids']);

	if(isset($_SESSION['seid']))
		unset($_SESSION['seid']);

	if(isset($_SESSION['qids']))
		unset($_SESSION['qids']);

	if(isset($_SESSION['qid']))
		unset($_SESSION['qid']);

	if(isset($_SESSION['right_answers']))
		unset($_SESSION['right_answers']);

	if(isset($_SESSION['wrong_answers']))
		unset($_SESSION['wrong_answers']);

	if(isset($_SESSION['total_attempted']))
		unset($_SESSION['total_attempted']);

	if(isset($_SESSION['section_marks']))		
		unset($_SESSION['section_marks']);

	if(isset($_SESSION['total_section_marks']))
		unset($_SESSION['total_section_marks']);

	if(isset($_SESSION['total_questions']))
		unset($_SESSION['total_questions']);

	if(isset($_SESSION['set']))
		unset($_SESSION['set']);

	if(isset($_SESSION['exam_portal']))
		unset($_SESSION['exam_portal']);

	redirect("index.php?show_exams");     // redirect to main exam page.
}

if(isset($_GET['completed']) && isset($_SESSION['username']) && isset($_SESSION['ulid']))
{
?>

<?php
	if(isset($_GET['completed']) && isset($_SESSION['section_marks']))
	{
		if(isset($_SESSION['exam_portal']))
			unset($_SESSION['exam_portal']);
		// rounding marks obtained ($_SESSION['section_marks']) to 2 decimal point.
		// 2nd arg in round() shows places upto which we have to round.
		$_SESSION['section_marks']=round($_SESSION['section_marks'],2);


		// inserting current score in table

		$exam_name="";
		$completed_time=date("Y-m-d H:i:s");

		$q_e=query("select * from exam where eid='".$_SESSION['eid']."'");
		confirm($q_e);

		if(mysqli_num_rows($q_e)!=0)
		{
			$r_e=fetch_array($q_e);
			$exam_name=$r_e['title'];
			// adding current exam marks in result pdf
			$m_pdf="";
			$m_pdf.="<div>
						<div style=\"text-align:center;\"><h3 style=\"color:red;\">Your Result</h3></div>
						<div style=\"margin-top:1rem;text-align:center;\">
							<span style=\"color:green;font-size:20px;margin-left:auto;margin-right:auto;\">Exam : {$r_e['title']}</span><br>
							<span style=\"color:green;font-size:20px;margin-left:auto;margin-right:auto;\">Set : {$_SESSION['set']}</span><br>
						</div>
						<span>Total Attempted : {$_SESSION['total_attempted']}/{$_SESSION['total_questions']}</span><br>
						<span>Final Result : {$_SESSION['section_marks']}/{$_SESSION['total_section_marks']}</span><br>
						<span>Correct Answers : {$_SESSION['right_answers']}</span><br>
						<span>Wrong Answers : {$_SESSION['wrong_answers']}</span><br>
					</div>
					<hr>";

			$_SESSION['pdf_data']=$m_pdf."".$_SESSION['pdf_data'];
		}
		
		$query=query("insert into result (ulid,eid,exam_title,exam_set,total_questions,total_attempted,right_questions,wrong_questions,marks_obtained,total_marks,completed_time) values('{$_SESSION['ulid']}','{$_SESSION['eid']}','{$exam_name}','{$_SESSION['set']}','{$_SESSION['total_questions']}','{$_SESSION['total_attempted']}','{$_SESSION['right_answers']}','{$_SESSION['wrong_answers']}','{$_SESSION['section_marks']}','{$_SESSION['total_section_marks']}','{$completed_time}')");
		confirm($query);

		// calculating Rank Of User

        $total_students_appeared=0;
        $rank=0;

        $q_gc=query("SELECT count(*) AS totalstudents FROM result WHERE eid='".$_SESSION['eid']."'");
        confirm($q_gc);

        if(mysqli_num_rows($q_gc)!=0)
        {
          $r_gc=fetch_array($q_gc);
          $total_students_appeared=$r_gc['totalstudents'];
        }

        if($total_students_appeared!=0)
        {
          $query_rank=query("SELECT Rank FROM 
            (SELECT @rank:=@rank+1 AS Rank, result.* 
              FROM result, (SELECT @rank:=0) AS i 
              WHERE eid='".$_SESSION['eid']."' ORDER BY marks_obtained DESC, completed_time ASC) AS allranks 
              WHERE allranks.ulid='".$_SESSION['ulid']."'");
          confirm($query_rank);

          if(mysqli_num_rows($query_rank)!=0)
          {
            $row_rank=fetch_array($query_rank);
            $rank=$row_rank['Rank'];
          }
        }
?>

<!-- exam result modal -->
<div class="modal" id="result_modal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Your Recent Exam Result</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="row">
          <div class="col-xs-12 col-md-12">
            <p class='text-center text-success' style="font-size:20px;font-weight:700;">
            	<?php
            		echo "Your Final Result Is : ".$_SESSION['section_marks']." / ".$_SESSION['total_section_marks']."<br>";
            	?>
            </p>
            <p class='text-center text-success' style="font-size:18px;">
            	<?php
            		echo "<br>Your Correct Answers=".$_SESSION['right_answers']."<br>";
            	?>
            </p>
            <p class='text-center text-success' style="font-size:18px;">
            	<?php
            		echo "Your Wrong Answers=".$_SESSION['wrong_answers']."<br>";
            	?>
            </p>
            <p class='text-center text-success' style="font-size:18px;">
            	<?php
            		echo "Total Attempted=".$_SESSION['total_attempted']."<br>";
            	?>
            </p>
            <p class='text-center text-success' style="font-size:18px;">
            	<?php
            		echo "Your Current Rank = ".$rank." / ".$total_students_appeared."<br>";
            	?>
            </p>
          </div>
        </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
      	<div class="row">
      		<div class="col-12 text-info">
        		<p>* This Rank is calculated according to the number of students appeared in the this exam till current time. You can see your updated rank on Home Screen.</p>
        	</div>
      		<div class="col-12 text-right">
        		<button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
        	</div>
        </div>
      </div>

    </div>
  </div>
</div>
<!-- exam result modal ends -->

<?php
		echo '<script>$("#result_modal").modal("show");</script>'; // showing result modal.

		// setting exam_count field for checking that user has used demo exam 2 times
		$q_epaid=query("select * from user_exam_paid where ulid='".$_SESSION['ulid']."' and eid='".$_SESSION['eid']."'");
		confirm($q_epaid);
		$r_epaid=fetch_array($q_epaid);

		/* checking if it is the first time that user with ulid giving exam ( having eid)
			then store 1 in exam_count field for first time */
		if(mysqli_num_rows($q_epaid)==0)
		{
			$q_iepaid=query("insert into user_exam_paid (ulid,eid,exam_count) values('".$_SESSION['ulid']."','".$_SESSION['eid']."',1)");
			confirm($q_iepaid);
		}
		else
		if($r_epaid['exam_count']!=0)
		{
			$q_iepaid=query("update user_exam_paid set exam_count=exam_count+1 where ulid='".$_SESSION['ulid']."' and eid='".$_SESSION['eid']."'");
			confirm($q_iepaid);
		}


		// unsetting exam related session indexes because exam is ended.
		if(isset($_SESSION['eid']))
			unset($_SESSION['eid']);

		if(isset($_SESSION['seids']))
			unset($_SESSION['seids']);

		if(isset($_SESSION['seid']))
			unset($_SESSION['seid']);

		if(isset($_SESSION['qids']))
			unset($_SESSION['qids']);

		if(isset($_SESSION['qid']))
			unset($_SESSION['qid']);

		if(isset($_SESSION['right_answers']))
			unset($_SESSION['right_answers']);

		if(isset($_SESSION['wrong_answers']))
			unset($_SESSION['wrong_answers']);

		if(isset($_SESSION['total_attempted']))
			unset($_SESSION['total_attempted']);

		if(isset($_SESSION['section_marks']))		
			unset($_SESSION['section_marks']);

		if(isset($_SESSION['total_section_marks']))
			unset($_SESSION['total_section_marks']);

		if(isset($_SESSION['total_questions']))
			unset($_SESSION['total_questions']);

		if(isset($_SESSION['set']))
			unset($_SESSION['set']);

		echo '<div class="row">
				<div class="col-12 text-center text-success" style="margin-top:4%;">
					<h4>Your Response Is Submitted Successfully...</h4>
				</div>
			</div>';
		echo "
			<div class=\"row\">
				<div class=\"col-12 col-md-4 text-center mt-3\" style=\"margin-top:4%;\">
					<a href='index.php?show_exams'><button class='btn btn-primary btn-md'>Explore More Exams</button></a>
				</div>
				<div class=\"col-12 col-md-4 text-center mt-3\" style=\"margin-top:4%;\">
					<a href='instant_exam_review.php' class='btn btn-primary btn-md'>Instant Review Your Exam</a>
				</div>
				<div class=\"col-12 col-md-4 text-center mt-3\" style=\"margin-top:4%;\">
					<button class='btn btn-primary btn-md' id='pdfbutton'>Download Your Result As PDF</button>
				</div>
			</div>";

		if(isset($_SESSION['exam_portal']))
			unset($_SESSION['exam_portal']);
	}
	else
	{
		redirect("index.php?home");
	}
?>

<script>
	$("#pdfbutton").click(
		function()
		{
			location.href="resultpdf.php";
		});
</script>

<?php
}
else
	redirect("index.php?show_exams");
?>

</body>
</html>