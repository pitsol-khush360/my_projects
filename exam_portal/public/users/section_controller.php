<?php include("validateUserMultipleLogin.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title></title>
	<meta charset="utf-8">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>

</body>
</html>

<?php

if(!isset($_SESSION['exam_portal']))
{
	redirect("index.php?show_exams");
	exit;
}

if(isset($_SESSION['seids']) && isset($_SESSION['seid']))
{
	$temp=$_SESSION['seids'];
	$temp_count=0;             // total numbering of sections.

	$occured=0;
	$last=0;

	foreach($temp as $value) 
	{
		if($value!="" && !is_null($value))
		{
			$last=$value;
			$temp_count++;
		}
	}

	// Setting session indexes that will hold values related to all sections
	if(!isset($_SESSION['right_answers']))
		$_SESSION['right_answers']=0;

	if(!isset($_SESSION['wrong_answers']))
		$_SESSION['wrong_answers']=0;

	if(!isset($_SESSION['total_attempted']))
		$_SESSION['total_attempted']=0;

	if(!isset($_SESSION['section_marks']))
		$_SESSION['section_marks']=0;

	// Variables that will help in Calculating result
	$count=0;                // to count total score.
	$mm=0;					// for main marks
	$right=0;				// for right answers
	$wrong=0;				// for wrong answers
	$total_questions=0;     // for total questions

	// If only one section
	if($temp_count==1)
	{
		$query_section=query("select * from section where seid='".$_SESSION['seid']."'");
		confirm($query_section);
		$row_section=fetch_array($query_section);

		$pm=intval($row_section['pm']);            // positive marks in section.
		$nm=floatval($row_section['nm']);            // negative marks in section.

		// calculating main marks for current section.
		$t1=$_SESSION['qids'];
		foreach($t1 as $tt1)
		{
			if($tt1!="" && !is_null($tt1))
				$total_questions++;
		}
		$mm=$total_questions*$pm;

		if(!isset($_SESSION['total_section_marks']))
			$_SESSION['total_section_marks']=$mm;

		$remaining_que_array=$_SESSION['qids'];  // for those qids which are not answered.we remove qids that are answered and then only not answered qids will remain in this array.

		$pdf_data="";            // for creating pdf data
		$pdf_data.="<div style=\"height:10px;\"></div><h2 style=\"text-align:center;color:green;\"><b>Section :</b> ".$row_section['section_name']."</h2><br>";
		// Iterating data coming from form
		$no=1;       // for question numbering in pdf
		foreach($_POST as $key=>$value)
		{
			//echo $key."=>".$value."<br>";
			if($key!="" && !is_null($key) && $value!="" && !is_null($value))
			{
				// Calculating Result
				$query=query("select * from question where qid='".$key."'");
				confirm($query);
				$row=fetch_array($query);

				if($row['answer']==$value)
				{
					$count+=$pm;      // + $pm for right answer.
					$right++;
				}
				else
				if($row['answer']!=$value)
				{
					$count-=$nm;   // negative marking - $nm
					$wrong++;
				}

				// Creating PDF Data
				if($row['question_img']!="")
					$img_path=image_path_question($row['question_img']);

				$pdf_data.="<div><p>&nbsp;&nbsp;<b>Question {$no}: </b>{$row['question']}</p><br>";
				if($row['question_img']!="")
					$pdf_data.="<div style=\"margin-top:0.8rem;\">&nbsp;&nbsp;&nbsp;<img src=\"../../resources/{$img_path}\" style=\"width:400px;height:300px;\"></div>";
				$pdf_data.="<div style=\"margin-top:0.8rem;\"><span>&nbsp;&nbsp;&nbsp;<b>A) </b>{$row['op1']}</span><br>";
				$pdf_data.="<span>&nbsp;&nbsp;&nbsp;<b>B) </b>{$row['op2']}</span><br>";
				$pdf_data.="<span>&nbsp;&nbsp;&nbsp;<b>C) </b>{$row['op3']}</span><br>";
				$pdf_data.="<span>&nbsp;&nbsp;&nbsp;<b>D) </b>{$row['op4']}</span><br>";

				if($row['op5']!="")
					$pdf_data.="<span>&nbsp;&nbsp;&nbsp;<b>E) </b>{$row['op5']}</span></div>";

				$pdf_data.="<p>&nbsp;&nbsp;&nbsp;<b>Your Answer : </b>".$row[$value]."</p>";
				$pdf_data.="<p>&nbsp;&nbsp;&nbsp;<b>Right Answer : </b>".$row[$row['answer']]."</p>";
				$pdf_data.="<p>&nbsp;&nbsp;&nbsp;<b>Description : </b>".$row['description']."</p></div><hr>";

				$no++;

				// removing that question from array which is answered and added to pdf.
				// array_search() returns key on which value exist in array
				$temp_key=array_search($key,$remaining_que_array);
				// echo $temp_key."<br>";
				unset($remaining_que_array[$temp_key]);
				//print_r($remaining_que_array)."<br>";
			}
		}
		//print_r($remaining_que_array);

		// Adding those questions in pdf which is not answered by user
		foreach($remaining_que_array as $r)
		{
			$query=query("select * from question where qid='".$r."'");
			confirm($query);
			$row=fetch_array($query);

			// Creating PDF Data
			if($row['question_img']!="")
				$img_path=image_path_question($row['question_img']);

			$pdf_data.="<div><p>&nbsp;&nbsp;<b>Question {$no}: </b>{$row['question']}</p><br>";
			if($row['question_img']!="")
				$pdf_data.="<div style=\"margin-top:0.8rem;\">&nbsp;&nbsp;&nbsp;<img src=\"../../resources/{$img_path}\" style=\"width:400px;height:300px;\"></div>";
			$pdf_data.="<div style=\"margin-top:0.8rem;\"><span>&nbsp;&nbsp;&nbsp;<b>A) </b>{$row['op1']}</span><br>";
			$pdf_data.="<span>&nbsp;&nbsp;&nbsp;<b>B) </b>{$row['op2']}</span><br>";
			$pdf_data.="<span>&nbsp;&nbsp;&nbsp;<b>C) </b>{$row['op3']}</span><br>";
			$pdf_data.="<span>&nbsp;&nbsp;&nbsp;<b>D) </b>{$row['op4']}</span><br>";

			if($row['op5']!="")
				$pdf_data.="<span>&nbsp;&nbsp;&nbsp;<b>E) </b>{$row['op5']}</span></div>";

			$pdf_data.="<p>&nbsp;&nbsp;&nbsp;<b>Your Answer : </b><span style=\"color:red;\">Not Attempted</span></p>";
			$pdf_data.="<p>&nbsp;&nbsp;&nbsp;<b>Right Answer : </b>".$row[$row['answer']]."</p>";
			$pdf_data.="<p>&nbsp;&nbsp;&nbsp;<b>Description : </b>".$row['description']."</p></div><hr>";

			$no++;		
		}
		
		// if(isset($_SESSION['pdf_data']))
		// 	unset($_SESSION['pdf_data']);
				
		if(!isset($_SESSION['pdf_data']))
			$_SESSION['pdf_data']=$pdf_data;

		$_SESSION['right_answers']+=$right;
		$_SESSION['wrong_answers']+=$wrong;
		$_SESSION['total_attempted']+=($right+$wrong);
		$_SESSION['section_marks']+=$count;

		//echo $_SESSION['pdf_data'];
		redirect("final_result.php?completed");
		exit;         // ends the script
	}
	else
	if($temp_count!=1)
	{
		$counting=0;
		$occured=0;

		foreach($temp as $value) 
		{
			if($value!="" && !is_null($value))
			{
				$counting++;

				// if(!isset($_SESSION['total_section_marks']))
				// 	$_SESSION['total_section_marks']=0;

				if($occured==1 || $value==$last)   // if section is occured or it is last section.
				{
					// Calculating Section Result
					$query_section=query("select * from section where seid='".$_SESSION['seid']."'");
					confirm($query_section);
					$row_section=fetch_array($query_section);

					$pm=intval($row_section['pm']);            // positive marks in section.
					$nm=floatval($row_section['nm']);            // negative marks in section.

					// calculating main marks for current section.
					$t1=$_SESSION['qids'];
					foreach($t1 as $tt1)
					{
						if($tt1!="" && !is_null($tt1))
							$total_questions++;
					}
					$mm=$total_questions*$pm;

					// pdf related
					$remaining_que_array=$_SESSION['qids'];  // for those qids which are not answered.we remove qids that are answered and then only not answered qids will remain in this array.

					$pdf_data="";
					$pdf_data.="<div></div><h2 style=\"text-align:center;color:green;\"><b>Section :</b> ".$row_section['section_name']."</h2><br>";
					// Iterating data coming from form
					$no=1;       // for question numbering in pdf

					//$_SESSION['total_section_marks']+=$mm;
					// Iterating data coming from form
					foreach($_POST as $key=>$v1)
					{
						if($key!="" && !is_null($key) && $v1!="" && !is_null($v1))
						{
							$query=query("select * from question where qid='".$key."'");
							confirm($query);
							$row=fetch_array($query);

							if($row['answer']==$v1)
							{
								$count+=$pm;
								$right++;
							}
							else
							if($row['answer']!=$v1)
							{
								$count-=$nm;
								$wrong++;
							}

							// Creating PDF Data
							if($row['question_img']!="")
								$img_path=image_path_question($row['question_img']);

							$pdf_data.="<div><p>&nbsp;&nbsp;<b>Question {$no}: </b>{$row['question']}</p><br>";
							if($row['question_img']!="")
								$pdf_data.="<div style=\"margin-top:0.8rem;\">&nbsp;&nbsp;&nbsp;<img src=\"../../resources/{$img_path}\" style=\"width:400px;height:300px;\"></div>";
							$pdf_data.="<div style=\"margin-top:0.8rem;\"><span>&nbsp;&nbsp;&nbsp;<b>A) </b>{$row['op1']}</span><br>";
							$pdf_data.="<span>&nbsp;&nbsp;&nbsp;<b>B) </b>{$row['op2']}</span><br>";
							$pdf_data.="<span>&nbsp;&nbsp;&nbsp;<b>C) </b>{$row['op3']}</span><br>";
							$pdf_data.="<span>&nbsp;&nbsp;&nbsp;<b>D) </b>{$row['op4']}</span><br>";

							if($row['op5']!="")
								$pdf_data.="<span>&nbsp;&nbsp;&nbsp;<b>E) </b>{$row['op5']}</span></div>";

							$pdf_data.="<p>&nbsp;&nbsp;&nbsp;<b>Your Answer : </b>".$row[$v1]."</p>";
							$pdf_data.="<p>&nbsp;&nbsp;&nbsp;<b>Right Answer : </b>".$row[$row['answer']]."</p>";
							$pdf_data.="<p>&nbsp;&nbsp;&nbsp;<b>Description : </b>".$row['description']."</p></div><hr>";

							$no++;

							// removing that question from array which is answered and added to pdf.
							// array_search() returns key on which value exist in array
							$temp_key=array_search($key,$remaining_que_array);
							// echo $temp_key."<br>";
							unset($remaining_que_array[$temp_key]);
							//print_r($remaining_que_array)."<br>";
						}
					}

					// Adding those questions in pdf which is not answered by user
					foreach($remaining_que_array as $r)
					{
						$query=query("select * from question where qid='".$r."'");
						confirm($query);
						$row=fetch_array($query);

						// Creating PDF Data
						if($row['question_img']!="")
							$img_path=image_path_question($row['question_img']);

						$pdf_data.="<div><p>&nbsp;&nbsp;<b>Question {$no}: </b>{$row['question']}</p><br>";
						if($row['question_img']!="")
							$pdf_data.="<div style=\"margin-top:0.8rem;\">&nbsp;&nbsp;&nbsp;<img src=\"../../resources/{$img_path}\" style=\"width:400px;height:300px;\"></div>";
						$pdf_data.="<div style=\"margin-top:0.8rem;\"><span>&nbsp;&nbsp;&nbsp;<b>A) </b>{$row['op1']}</span><br>";
						$pdf_data.="<span>&nbsp;&nbsp;&nbsp;<b>B) </b>{$row['op2']}</span><br>";
						$pdf_data.="<span>&nbsp;&nbsp;&nbsp;<b>C) </b>{$row['op3']}</span><br>";
						$pdf_data.="<span>&nbsp;&nbsp;&nbsp;<b>D) </b>{$row['op4']}</span><br>";

						if($row['op5']!="")
							$pdf_data.="<span>&nbsp;&nbsp;&nbsp;<b>E) </b>{$row['op5']}</span></div>";

						$pdf_data.="<p>&nbsp;&nbsp;&nbsp;<b>Your Answer : </b><span style=\"color:red;\">Not Attempted</span></p>";
						$pdf_data.="<p>&nbsp;&nbsp;&nbsp;<b>Right Answer : </b>".$row[$row['answer']]."</p>";
						$pdf_data.="<p>&nbsp;&nbsp;&nbsp;<b>Description : </b>".$row['description']."</p></div><hr>";

						$no++;		
					}
		
					// if(isset($_SESSION['pdf_data']))
					// 	unset($_SESSION['pdf_data']);
							
					if(!isset($_SESSION['pdf_data']))
						$_SESSION['pdf_data']=$pdf_data;
					else
						$_SESSION['pdf_data'].=$pdf_data;

					$_SESSION['right_answers']+=$right;
					$_SESSION['wrong_answers']+=$wrong;
					$_SESSION['total_attempted']+=($right+$wrong);
					$_SESSION['section_marks']+=$count;

					if($last==$_SESSION['seid'])     // if last section then go to final result page
					{
						redirect("final_result.php?completed");
						exit;
					}
					else            // if not last section then go to exam page
					{
						$_SESSION['seid']=$value;
						redirect("exam.php?eid={$_SESSION['eid']}");
						exit;
					}
				}

				if($value==$_SESSION['seid'])
					$occured=1;
			}
		}
	}
}
else
	redirect("final_result.php?issue");
?>