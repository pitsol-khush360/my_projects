<?php include("validateUserMultipleLogin.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?php echo APP; ?> - Exam Instructions</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<link href="css/exam.css?<?php echo time(); ?>" rel="stylesheet">
</head>
<body>
<?php
    if(isset($_SESSION['username']) && isset($_SESSION['ulid']))
    {
?>

<?php
	if(isset($_GET['eid']) && isset($_GET['set']))
	{
		// unsetting pdf data if recent exam is given and pdf data is not cleared;
    	if(isset($_SESSION['pdf_data']))
        	unset($_SESSION['pdf_data']);

        // exam related data
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
        
        // setting an index examportal.if this index is set then access exam related part otherwise we wil redirect to show_exams page
        if(!isset($_SESSION['exam_portal']))
        	$_SESSION['exam_portal']=1;

		$query=query("select * from user_personal where ulid='".$_SESSION['ulid']."'");
		confirm($query);
		$row=fetch_array($query);

		if($row['profile_picture']!="")
			$img_path=image_path_profile($row['profile_picture']);
		else
			$img_path=image_path_profile("defaultpic.jpg");

		// fetching exam related part.
			$total_sections=0;
			$total_questions=0;
			$total_timing=0;          // sum of all section timings.
			$total_marks=0;

			$q_eq=query("select * from exam_questions where eid='".$_GET['eid']."' and set_of_exam='".$_GET['set']."'");
			confirm($q_eq);

			// concatinating exam related part in $section variable including heredoc part.
			$section="";

			$section.='<table class="table table-hover table-responsive table-bordered">
						<thead>
							<tr>
								<th>Section No.</th>
								<th>Section Name</th>
								<th>Section Timing</th>
								<th>Questions</th>
								<th>Positive Marking</th>
								<th>Negative Marking</th>
								<th>Total Marks</th>
							</tr>
						</thead>
						<tbody>';

			$i=1;

			if(mysqli_num_rows($q_eq)!=0)
			{
				while($r_eq=fetch_array($q_eq))
				{
					$temp_timing=0;
					$temp_questions=0;
					$temp_marks=0;

					// counting no. of questions in a section of exam.
					$que=explode('#',$r_eq['qid']);

					foreach($que as $v)
					{
						if($v!="" && $v!=" " && !is_null($v))
						{
							$q_qb=query("select * from question_bank where qid='".$v."'");
							confirm($q_qb);

							if(mysqli_num_rows($q_qb)!=0)
							{
								$r_qb=fetch_array($q_qb);

								$q_cq=query("select * from question where qid='".$r_qb['qids']."'");
								confirm($q_cq);

								if(mysqli_num_rows($q_cq)!=0)
									$temp_questions++;
							}
						}
					}

					$q_section=query("select * from section where seid='".$r_eq['seid']."'");
					confirm($q_section);

					if(mysqli_num_rows($q_section)!=0)
					{
						$total_sections++;

						$r_section=fetch_array($q_section);

						$temp_timing=intval($r_section['section_timing']);
						$total_timing+=$temp_timing;

						$temp_marks=$temp_questions*$r_section['pm'];
						$total_marks+=$temp_marks;

						$total_questions+=$temp_questions;

						$section.=<<< section
						<tr>
							<td>{$i}</td>
							<td>{$r_section['section_name']}</td>
							<td>{$r_section['section_timing']} minutes</td>
							<td>{$temp_questions}</td>
							<td>{$r_section['pm']}</td>
							<td>{$r_section['nm']}</td>
							<td>{$temp_marks}</td>
						</tr>
section;
						$i++;
					}
				}
			}

			// Creating Session indexes for total questions and marks.
			$_SESSION['total_questions']=$total_questions;
			$_SESSION['total_section_marks']=$total_marks;

			$section.='</tbody>
					</table>';
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-12 text-center">
			<p><button id="b_ins">Exam Instructions</button></p>
		</div>
	</div>
	<div class="row" id="instruction-row">
		<div class="col-12 col-md-3 col-lg-3">
			<div class="row text-center" id="profile-photo-row">
				<div class="col-12 text-center">	
					<img class="img-circle d-block" src="../../resources/<?php echo $img_path; ?>" width="150" height="150" >
				</div>
				<div class="col-12 text-center">	
					<h3><?php echo $_SESSION['username']; ?></h3>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-9 col-lg-8" id="instruction-text-row">
			<div class="row">
				<div class="col-12">
					<h4><b>General Instructions :</b></h4>
				</div>
				<div class="col-12 instruction-col-gap">
					<p>1. The Clock On the top of window will show the timing for each section. When the timer will reach to zero (0) your section will completed automatically and new section will occur if available.</p>
				</div>
				<div class="col-12 instruction-col-gap">
					<p>2. The Question grid at the right of the window will show the status of each question as following.</p>
				</div>
				<div class="col-12 instruction-col-sub-gap">
					<button id="b_bg"></button>&nbsp;&nbsp;<span>This Will Show The save and next Question.</span>
				</div>
				<div class="col-12 instruction-col-sub-gap">
					<button id="b_bo"></button>&nbsp;&nbsp;<span>This Will Show The Review Question.</span>
				</div>
				<div class="col-12 instruction-col-sub-gap">
					<button id="b_bw"></button>&nbsp;&nbsp;<span>This Will Show That the question is not visited.</span>
				</div>
				<div class="col-12 instruction-col-gap">
					<p>3. The Following Buttons Will show the related instructions.</p>
				</div>
				<div class="col-12 instruction-col-sub-gap">
					<button class="btn btn-success">Save & Next</button>&nbsp;&nbsp;<span>This Will Save Your Question and display the next question if available.</span>
				</div>
				<div class="col-12 instruction-col-sub-gap">
					<button class="btn btn-warning">Review</button>&nbsp;&nbsp;<span>This Will Put Current Question for review and display the next question if available.</span>
				</div>
				<div class="col-12 instruction-col-sub-gap">
					<button class="btn btn-info">Submit</button>&nbsp;&nbsp;<span>This Will Submit Your exam.</span>
				</div>
				<div class="col-12 instruction-col-gap">
					<p>4. About This Exam</p>
					<span>Total Timing : &nbsp;<?php echo $total_timing; ?>&nbsp;minutes</span><br>
					<span>Total Sections : &nbsp;<?php echo $total_sections; ?></span><br>
					<span>Total Questions : &nbsp;<?php echo $total_questions; ?></span><br>
					<span>Total Marks : &nbsp;<?php echo $total_marks; ?></span><br><br>
				</div>
				<div class="col-12 instruction-col-gap">
					<?php echo $section; ?>  <!-- tabular form display -->
				</div>
				<div class="col-12 instruction-col-gap">
					<p>5. You have to click on submit button or you have to wait for time up to end your exam.</p>
				</div>
			</div>
		</div> 
	</div>
	<div class="row">
		<hr>
	</div>
	<div class="row">
		<div class="col-lg-3 col-md-3"></div>
		<div class="col-12 col-sm-10 col-md-8 col-lg-8">
			<p><input type="checkbox" name="ins" id="button_enabler" autocomplete="off">&nbsp;&nbsp;
				<span id="click_button_enabler"><b>I Have read all the instructions carefully. I Agree not to cheat in exam. My all unfair activities will submit the test Immediately.</b></span></p>
		</div>
	</div>
	<div class="row" id="tc-row">
		<div class="col-lg-3 col-md-3"></div>
		<div class="col-12 col-sm-10 col-md-8 col-lg-8">
			<div class="pull-left">
				<a href="index.php?show_exams"><button class="btn btn-info">Go To Exams</button>
				</a>
			</div>
			<div class="pull-right">
				<button class="btn btn-info" id="start_exam" disabled>Start Exam</button>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<?php
	}
	else
		echo "Sorry You Don't Have Access Permission.";
?>

<script type="text/javascript">
	$("#click_button_enabler").on("click",function(){
		$("#button_enabler").click();
	});

	$("#button_enabler").on("click",function()
		{
			if($("#button_enabler").prop("checked") == true) // prop() -> property.
			{	
				$("#start_exam").removeAttr("disabled");
			}
			else
			{
				$("#start_exam").attr("disabled",true);  // if unchecked then disable again.
			}
		});

	$("#start_exam").on("click",function(){window.location.href="exam.php?eid=<?php echo $_GET['eid']; ?>&set=<?php echo $_GET['set']; ?>"});
</script>

<?php
    }
    else
        redirect("..".DS."signin.php");
?>
</body>
</html>