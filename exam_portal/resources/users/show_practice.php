<?php
    if(isset($_SESSION['username']) && isset($_SESSION['ulid']))
    {
?>
<div class="container">
	<div class="row" style="margin-top:4rem;">
		<?php
		if(isset($_GET['show_practice']) && isset($_POST['show_result']))
		{
			$right=0; 
			$wrong=0;
			$attempted=0;

			foreach($_POST as $key=>$value)
			{
				if($key!="show_result" && $key!="total_questions")
				{
					$query_result=query("select answer from question where qid='".$key."'");
					confirm($query_result);
					$row_result=fetch_array($query_result);
					
					if($value==$row_result['answer'])
					{
						$right++;
					}
					else
					{
						$wrong++;	
					}
				}
			}

			$attempted=$right+$wrong;

			echo '<div class="row">';
			$result=<<< result
				<div class="col-12 col-sm-9 col-md-10 col-lg-12">
					<div class="card card1">
						<div class="card-header card1-header text-center"><h5>Your Result</h5></div>
  						<div class="card-body card1-body">
  							Total Questions : {$_POST['total_questions']}<br>
  							Total Attempted : {$attempted}<br>
  							Right Answers :   {$right} <br>
  							Wrong Answers : {$wrong}<br>
  						</div>
					</div>
				</div>
result;
				echo $result;

				echo "<div class='row'>
							<div class='col-12 col-sm-9 col-md-10 col-lg-12 text-center'>
								<a href='index.php?show_practice' class='btn btn-info'>Go For More Practice</a>
							</div>
						</div>";
		}
		else
		if(isset($_GET['show_practice']) && !isset($_GET['practicequestions']) && !isset($_GET['subtopics']) && !isset($_GET['qtid']) && !isset($_GET['qstid']))
		{
			echo'<div class="row">
				<div class="col-12">
					<h3 class="text-center">Practice Topics</h3>
				</div>
			</div>';

			$query_p=query("select * from user_practice_preference where ulid='".$_SESSION['ulid']."'");
			confirm($query_p);

			if(mysqli_num_rows($query_p)!=0)
			{
				$row_p=fetch_array($query_p);

				$stids=explode("#",$row_p['qstid']);

				// Making a session index for qstids
				//$_SESSION['practice_stids']=$row_p['qstid'];

				$tids="";
				foreach($stids as $value) 
				{
					if($value!="" && $value!=" " && !is_null($value))
					{
						$query_st=query("select * from question_sub_topic where qstid='".$value."'");
						confirm($query_st);

						if(mysqli_num_rows($query_st)!=0)
						{
							$row_st=fetch_array($query_st);
							$tids.=$row_st['qtid']."#";
						}
					}
				}

				// Forming array from $tids String.Then Storing Only Unique values in that array.
				$tids_array=explode("#",$tids);
				$tids_array=array_unique($tids_array);

				echo '<div class="row" style="margin-top:2rem;">
						<div class="col-12 col-sm-8 col-md-9 col-lg-11">
							<div class="row">';
				foreach($tids_array as $value) 
				{
					if($value!="" && $value!=" " && !is_null($value))
					{
						$query_t=query("select * from question_topic where qtid='".$value."'");
						confirm($query_t);

						if(mysqli_num_rows($query_t)!=0)
						{
							$row_t=fetch_array($query_t);

							$query_st1=query("select * from question_sub_topic where qtid='".$value."'");
							confirm($query_st1);

							if(mysqli_num_rows($query_st1)!=0)
							{
								$total_sub_topics=0;
								while($row_st1=fetch_array($query_st1))
								{
									foreach($stids as $value1) 
									{
										if($value1!="" && $value1!=" " && !is_null($value1))
										{
											if($row_st1['qstid']==$value1)
												$total_sub_topics++;
										}
									}
								}
							}

							$topics=<<< topic
							<div class="col-12 col-sm-6 col-md-4 col-lg-4 card-parent-div">
							<a href="index.php?show_practice&subtopics&qtid={$row_t['qtid']}" style="text-decoration:none;">
								<div class="card card1">
									<div class="card-header card1-header"><p>{$row_t['question_topic']}</p></div>
			  						<div class="card-body card1-body">
			  							<div class="row">
			  								<div class="col-md-12 col-xs-12">
			  								  Sub Topics For Practice: {$total_sub_topics}
			  								</div>
			  							</div>
									</div>
								</div>
							</a>
							</div>
topic;
							echo $topics;
						}
					}
				}
				echo '</div>
					</div>
					</div>';
			}
			else
			{
				echo "<h4 class='text-center text-danger' style='margin-top:4em;'>Your Practice Preference Is Not Setted !</h4>";
				echo "<div class='col-12 text-center'><a href='index.php?test_preference'><button class='btn btn-info'>Set Practice Preference Now</button></a></div>";
			}
		}
		else
			// subtopic code
		if(isset($_GET['show_practice']) && isset($_GET['subtopics']) && isset($_GET['qtid']))
		{
			echo'<div class="row">
				<div class="col-12">
					<h3 class="text-center">Practice Sub-Topics</h3>
				</div>
			</div>';

			$query_st2=query("select * from question_sub_topic where qtid='".$_GET['qtid']."'");
			confirm($query_st2);

			if(mysqli_num_rows($query_st2)!=0)
			{
				echo '<div class="row" style="margin-top:2rem;">
						<div class="col-12 col-sm-8 col-md-9 col-lg-11">
							<div class="row">';

				while($row_st2=fetch_array($query_st2))
				{
					// echo '<script>alert("all ids='.$_SESSION['practice_stids'].'")</script>';
					$query_p=query("select * from user_practice_preference where ulid='".$_SESSION['ulid']."'");
					confirm($query_p);

					if(mysqli_num_rows($query_p)!=0)
					{
						$row_p=fetch_array($query_p);
						$temp_stids=explode("#",$row_p['qstid']);

						foreach($temp_stids as $value) 
						{
							//echo '<script>alert("stid='.$value.'")</script>';
							if($row_st2['qstid']==$value)
							{
								$query_q=query("select * from question where qstid='".$value."' and for_practice=1");
								confirm($query_q);
								$total_practice_questions=intval(mysqli_num_rows($query_q));

								$sub_topic=<<< subtopic
								<div class="col-12 col-sm-6 col-md-4 col-lg-4 card-parent-div">
								<a href="index.php?show_practice&practicequestions&qstid={$value}" style="text-decoration:none;">
									<div class="card card1">
										<div class="card-header card1-header"><p>{$row_st2['question_sub_topic']}</p></div>
				  						<div class="card-body card1-body">
				  							<div class="row">
				  								<div class="col-md-12 col-xs-12">
				  									Total Questions : {$total_practice_questions}
				  								</div>
				  							</div>
										</div>
									</div>
								</a>
								</div>
subtopic;
								echo $sub_topic;
							}
						}
					}
				}
			echo '</div>
				</div>
			   </div>';
			}
		} // subtopics ends
		else
			// For Showing Practice Questions Related To A Perticular Sub-Topic
		if(isset($_GET['show_practice']) && isset($_GET['practicequestions']) && isset($_GET['qstid']))
		{
			$query=query("select * from question where qstid='".$_GET['qstid']."' and for_practice=1");
			confirm($query);

			$q=1;
			$total=intval(mysqli_num_rows($query));

			echo '<form class="form" action="" method="post">';
			$total_questions=0;

			if(mysqli_num_rows($query)!=0)
			{
				while($row=fetch_array($query))
				{
					$total_questions++;
					$questions="";

					$pra_question=html_entity_decode($row['question']);
					$pra_question_op1=html_entity_decode($row['op1']);
					$pra_question_op2=html_entity_decode($row['op2']);
					$pra_question_op3=html_entity_decode($row['op3']);
					$pra_question_op4=html_entity_decode($row['op4']);
					$pra_question_op5="";
					if($row['op5']!="")
						$pra_question_op5=html_entity_decode($row['op5']);

					$questions=<<< questions
					<div class="col-12 col-sm-8 col-md-9 col-lg-11 card-parent-div">
						<div class="card">
							<div class="card-header card1-header">
								<div class="col-12">
									Question {$q} : {$pra_question}
								</div>
							</div>
	  						<div class="card-body card1-body">
questions;
					if($row['question_img']!="")
					{
						$img_path=image_path_question($row['question_img']);
						$questions.=<<<questions
						<div class="col-12 text-center">
							<img src="../../resources/{$img_path}" id="practice-que-img">
						</div>
questions;
					}

	  				$questions.=<<<questions
	  				<div class="col-12" style="margin-top:15px;">
		  				<label for="lab{$row['qid']}_op1">
		  					<input type="radio" id="lab{$row['qid']}_op1" name="{$row['qid']}" value="op1">&nbsp;{$pra_question_op1}
		  				</label>
	  				</div>
	  				<div class="col-12">
		  				<label for="lab{$row['qid']}_op2">
		  					<input type="radio" id="lab{$row['qid']}_op2" name="{$row['qid']}" value="op2">&nbsp;{$pra_question_op2}
		  				</label>
	  				</div>
	  				<div class="col-12">
		  				<label for="lab{$row['qid']}_op3">	
		  					<input type="radio" id="lab{$row['qid']}_op3" name="{$row['qid']}" value="op3">&nbsp;{$pra_question_op3}
		  				</label>
	  				</div>
	  				<div class="col-12">
		  				<label for="lab{$row['qid']}_op4">
		  					<input type="radio" id="lab{$row['qid']}_op4" name="{$row['qid']}" value="op4">&nbsp;{$pra_question_op4}
		  				</label>
	  				</div>
questions;
	  				if($row['op5']!="")
					{
						$questions.=<<<questions
						<div class="col-12">
			  				<label for="lab{$row['qid']}_op5">
			  					<input type="radio" id="lab{$row['qid']}_op5" name="{$row['qid']}" value="op5">&nbsp;{$pra_question_op5}
			  				</label>
		  				</div>
questions;
					}

					$questions.=<<<questions
					<div class="col-12">
						<div class="dropdown show">
							<a class="btn btn-info dropdown-toggle" href="#" role="button" id="que{$row['qid']}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Show Answer
							</a>
							<div class="dropdown-menu" aria-labelledby="que{$row['qid']}">
					    		<p class="text-center">{$row["{$row['answer']}"]}</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
questions;
					echo $questions;
					$q++;
				}
			}
			echo "<input type='hidden' name='total_questions' value='{$total_questions}'>
					<input type='hidden' name='{$_SESSION["csrf_name"]}' value='{$_SESSION["csrf_value"]}'>
					<div class='row'>
						<div class='col-12 col-sm-8 col-md-9 col-lg-11 card-parent-div text-center'>
							<button type='submit' class='btn btn-warning' name='show_result'>Get Result</button>
						</div>
					</div>
				</form>
				";
		} // questions for perticular subtopic ends
?>
	</div>
</div>
<?php
    }
    else
        redirect("..".DS."signin.php");
?>