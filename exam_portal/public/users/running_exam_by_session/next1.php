<?php include("../../resources/config.php"); ?>

<?php
	if(isset($_SESSION['seid']) && isset($_GET['question']) && isset($_GET['answer']))
	{
		$sectionid=$_SESSION['seid'];
		$que=$_GET['question'];   // current question 
		$total=totalquestions($sectionid);

		// checking last question of section or not.
		$questions=$_SESSION['qids'];
		$count=0;
		$status="continue";

		foreach ($questions as $q) 
		{
			$count++;

			if($q==$que and $count==$total)
			{
				$temp=$_SESSION['seids'];
				$occured=0;
				$now=$_SESSION['seid'];

				foreach($temp as $t)
				{
					$last=$t;
				}

				if($last!=$_SESSION['seid'])  // if not last section
					$status="stop_next_section";
				else
					if($last==$_SESSION['seid']) // if it is last
						$status="stop_last";
			}
		}

		// storing question-answer pair in $_SESSION['section'] of a perticular section.
		if(!isset($_SESSION['section']))
			$_SESSION['section']="";
		
		if($_SESSION['section']!="")
		{		
			$section_temp="";

			$pair=explode(",",$_SESSION['section']);

			if(count($pair)!=0)
			{
				$j=0;

				for($j=0;$j<count($pair);$j++)
				{
					if(!empty($pair[$j]))
					{
						$temp=explode(" ",$pair[$j]);

						$q=$temp[0];

						if($q!=$que) // if previously same qid is available then not add it into $section_temp
						{
							$section_temp=$section_temp."".$pair[$j].",";
						}
					}	
				}
	
				if($_GET['answer']!="none")
					$section_temp=$section_temp.$que." ".$_GET['answer'].",";
			}
			$_SESSION['section']=$section_temp;
		}
		else
		{
			if($_GET['answer']!="none")
				$_SESSION['section']=$que." ".$_GET['answer'].",";
		}
			echo $status;  // using it on exam.php for next question.
	}
	else
		redirect("final_result.php?issue");
?>