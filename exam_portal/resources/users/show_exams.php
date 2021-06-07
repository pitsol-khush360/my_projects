<?php
    if(isset($_SESSION['username']) && isset($_SESSION['ulid']))
    {
?>

<div class="container">
<div class="row" style="margin:5rem 3rem 4rem 0.5rem ;">
	<div class="col-12 col-sm-9 col-md-10 col-lg-12 text-center">
		<h5 style="font-size:30px;">Exams</h5>
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-8 col-md-9 col-lg-11">
		<div class="row">
<?php
if(isset($_GET['show_exams']))
{
	if(isset($_SESSION['pdf_data']))
		unset($_SESSION['pdf_data']);
	
	$query_sc=query("select * from user_test_preference where ulid='".$_SESSION['ulid']."'");
	confirm($query_sc);
	$r_sc=fetch_array($query_sc);

	$exams_found=0;

	if(mysqli_num_rows($query_sc)!=0 && $r_sc['scid']!="")
	{
		$scids=explode("#",$r_sc['scid']);
		
		foreach($scids as $v)
		{
			if($v!="" && !is_null($v) && $v!=" ")
			{
				$current_date=date("Y-m-d H:i:s");

				$query_exam=query("select * from exam where scid='".$v."' and ('".$current_date."' between start_time and end_time)");

				confirm($query_exam);
				
				$total_sections=0;
				$total_questions=0;
				$total_timing=0;          // sum of all section timings.

				if(mysqli_num_rows($query_exam)!=0)
				{
					$exams_found=1;

					while($r_exam=fetch_array($query_exam))
					{
						$set="A";
						// $random=mt_rand(1,5);  // generating random no. between 1 to 5.

						// if($random==1)
						// 	$set="A";
						// else
						// 	if($random==2)
						// 		$set="B";
						// else
						// 	if($random==3)
						// 		$set="C";
						// else
						// 	if($random==4)
						// 		$set="D";
						// else
						// 	if($random==5)
						// 		$set="E";

						$q_eq=query("select * from exam_questions where eid='".$r_exam['eid']."' and set_of_exam='".$set."'");
						confirm($q_eq);

						if(mysqli_num_rows($q_eq)!=0)
						{
							while($r_eq=fetch_array($q_eq))
							{
								$q_section=query("select * from section where seid='".$r_eq['seid']."'");
								confirm($q_section);

								if(mysqli_num_rows($q_section)!=0)
								{
									$total_sections+=1;
									$r_section=fetch_array($q_section);
									$total_timing+=intval($r_section['section_timing']);
								}

								$temp=explode("#",$r_eq['qid']);

								$count_q=0;

								foreach($temp as $value) 
								{
									if($value!="" && !is_null($value) && $value!=" ")
									{
										$q_qb=query("select * from question_bank where qid='".$value."'");
										confirm($q_qb);

										if(mysqli_num_rows($q_qb)!=0)
										{
											$r_qb=fetch_array($q_qb);

											$q_cq=query("select * from question where qid='".$r_qb['qids']."'");
											confirm($q_cq);

											if(mysqli_num_rows($q_cq)!=0)
												$count_q++;
										}
									}
								}
								$total_questions+=$count_q;
							}

							// checking exam status that payment for that exam has done or not.

							// $query_epaid=query("select * from user_exam_paid where eid='".$r_exam['eid']."' and ulid='".$_SESSION['ulid']."'");
							// confirm($query_epaid);
							// $r_epaid=fetch_array($query_epaid);

							$query_epaid=query("select * from result where eid='".$r_exam['eid']."' and ulid='".$_SESSION['ulid']."'");
							confirm($query_epaid);

							// if entry found in exam paid table then checking if free trial(2) is completed
							// if entry not found means first time then showing exam

							//if((mysqli_num_rows($query_epaid)!=0 && ($r_epaid['exam_count']<=2 || $r_epaid['exam_status']=="Yes" || $r_epaid['exam_month_status']=="Yes" || $r_epaid['exam_year_status']=="Yes")) || mysqli_num_rows($query_epaid)==0)

							if(mysqli_num_rows($query_epaid)==0)
							{
								$exams=<<< exams
									<div class="col-12 col-sm-6 col-md-4 col-lg-4 card-parent-div">
									<a href="show_instructions.php?eid={$r_exam['eid']}&set={$set}" style="text-decoration:none;">
										<div class="card card1">
											<div class="card-header card1-header">
												<div class="row">
													<div class="col-12"> 
														<p>{$r_exam['title']}</p>
													</div>
													<div class="col-12"> 
														<p>set {$set}</p>
													</div>
												</div>
											</div>
					  						<div class="card-body card1-body">
					  							<div class="row">
					  								<div class="col-12">Total Sections : {$total_sections}</div>
					  								<div class="col-12">Total Questions : {$total_questions}</div>
					  								<div class="col-12">Total Timing : {$total_timing} minutes</div>
					  							</div>
					  							<div class="row text-center">
					  								<button class="btn btn-warning card1-btn">Start Test</button>
					  							</div>
					  						</div>
										</div>
									</a>
									</div>
exams;
								echo $exams;
							}
							else
							{
								$exams=<<< exams
									<div class="col-12 col-sm-6 col-md-4 col-lg-4 card-parent-div">
										<div class="card card1">
											<div class="card-header card1-header">
												<div class="row">
													<div class="col-12"> 
														<p>{$r_exam['title']}</p>
													</div>
													<div class="col-12"> 
														<p>set {$set}</p>
													</div>
												</div>
											</div>
					  						<div class="card-body card1-body">
					  							<div class="row">
					  								<div class="col-12">Total Sections : {$total_sections}</div>
					  								<div class="col-12">Total Questions : {$total_questions}</div>
					  								<div class="col-12">Total Timing : {$total_timing} minutes</div>
					  							</div>
					  							<div class="row text-center">
					  								<h5 class="text-success card1-btn">You have already completed this exam</h5>
					  							</div>
					  						</div>
										</div>
									</div>
exams;
									echo $exams;
							}
							/*else
							{
								$exams=<<< exams
									<div class="col-12 col-sm-6 col-md-4 col-lg-4 card-parent-div">
									<a href="choose_course_pay.php?select_payment_type" style="text-decoration:none;">
										<div class="card card1">
											<div class="card-header card1-header">
												<div class="row">
													<div class="col-12"> 
														<p>{$r_exam['title']}</p>
													</div>
													<div class="col-12"> 
														<p>set {$set}</p>
													</div>
												</div>
											</div>
					  						<div class="card-body card1-body">
					  							<div class="row">
					  								<div class="col-12">Total Sections : {$total_sections}</div>
					  								<div class="col-12">Total Questions : {$total_questions}</div>
					  								<div class="col-12">Total Timing : {$total_timing} minutes</div>
					  							</div>
					  							<div class="row text-center">
					  								<button class="btn btn-info card1-btn">Enable Test</button>
					  							</div>
					  						</div>
										</div>
									</a>
									</div>
exams;
								echo $exams;				
							}*/

							$total_sections=0;
							$total_questions=0;
							$total_timing=0;
						}  // if ends
					}
				}
			}
		}

		if($exams_found==0)
			echo "<div class='col-12 text-center text-danger'><p style='font-size:20px;'>Exams are not available based on your preference!</p></div>";
	}
	else
	{
		echo "<div class='col-12 text-center text-danger'><p style='font-size:20px;'>Your Exam Preference Is Not Setted ! Or Refresh The Page If You Recently Setted Your Exam Preference.</p></div>";
		echo "<div class='col-12 text-center' style='margin-top:2rem;'><a href='index.php?test_preference'><button class='btn btn-info'>Set Exam Preference Now</button></a></div>";
	}
}
else
	echo "Sorry! You Don't Have Permission";
?>
			</div>
		</div>
	</div> <!-- row ends exam.php?username={$username}&ulid={$ulid}&show_exams&eid={$r_exam['eid']} -->
</div>

<?php
    }
    else
        redirect("..".DS."signin.php");
?>