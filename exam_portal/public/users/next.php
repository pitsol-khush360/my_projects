<?php include("validateUserMultipleLogin.php"); ?>

<?php
	if(!isset($_SESSION['exam_portal']))
	{
		redirect("index.php?show_exams");
		exit;
	}
	
	if(isset($_SESSION['seid']) && isset($_GET['question']) && isset($_SESSION['exam_portal']))
	{
		$que=$_GET['question'];   // current question 
		// $total=totalquestions($sectionid);
		$total=totalquestions($_SESSION['seid']);

		// checking last question of section or not.
		$questions=$_SESSION['qids'];
		$count=0;
		$status="continue";

		foreach($questions as $q) 
		{
			$count++;

			if($q==$que && $count==$total)
			{
				$temp=$_SESSION['seids'];
				$occured=0;

				foreach($temp as $t)
				{
					if($t!="" && !is_null($t))
						$last=$t;
				}

				if($last!=$_SESSION['seid'])  // if not last section
					$status="stop_next_section";
				else
					if($last==$_SESSION['seid']) // if it is last
						$status="stop_last";
			}
		}
			echo $status;  // using it on exam.php for next question.
	}
	else
		redirect("final_result.php?issue");
?>