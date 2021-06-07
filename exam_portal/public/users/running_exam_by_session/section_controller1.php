<?php include("../../resources/config.php"); ?>

<?php
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

		//echo '<script>alert("seid='.$_SESSION['seid'].'")</script>';
		//echo '<script>alert("last='.$last.'")</script>';

					if($temp_count==1)       // if only one section.
					{
						//echo '<script>alert("only one section='.$_SESSION['section'].'")</script>';
						// counting result on ending of every section.
						// though,this is not last section so only calculate result.

						if(!isset($_SESSION['right_answers']))
							$_SESSION['right_answers']=0;

						if(!isset($_SESSION['wrong_answers']))
							$_SESSION['wrong_answers']=0;

						if(!isset($_SESSION['total_attempted']))
							$_SESSION['total_attempted']=0;

						if(!isset($_SESSION['section_marks']))
							$_SESSION['section_marks']=0;

						$count=0;                // to count total score.
						$mm=0;					// for main marks
						$right=0;				// for right answers
						$wrong=0;				// for wrong answers
						$total_questions=0;     // for total questions
	
						if(isset($_SESSION['seid']))
						{
							$query_section=query("select * from section where seid='".$_SESSION['seid']."'");
							confirm($query_section);
							$row_section=fetch_array($query_section);

							$pm=intval($row_section['pm']);            // positive marks in section.
							$nm=floatval($row_section['nm']);            // negative marks in section.
							//$mm=$row_section['mm'];            // main marks in section.
							$t1=$_SESSION['qids'];
							// $_SESSION['total_questions']+=count($t1);
							$total_questions=count($t1);
							$mm=$total_questions*$pm;

							if(!isset($_SESSION['total_section_marks']))
								$_SESSION['total_section_marks']=$mm;

							// echo '<script>alert("pm='.$pm.'")</script>';
							// echo '<script>alert("nm='.$nm.'")</script>';
							// echo '<script>alert("mm='.$mm.'")</script>';
							// echo '<script>alert("total section marks='.$_SESSION['total_section_marks'].'")</script>';
							// echo '<script>alert("total_questions='.$_SESSION['total_questions'].'")</script>';
						}

						if(isset($_SESSION['section']))
						{
							//echo '<script>alert("total section marks='.$_SESSION['total_section_marks'].'")</script>';
							$pair=explode(",",$_SESSION['section']);
							$j=0;
							
							for($j=0;$j<count($pair);$j++)
							{
								if(!empty($pair[$j]))
								{
									//echo '<script>alert("pair j='.$pair[$j].'")</script>';
									$temp1=explode(" ",$pair[$j]);
									//echo '<script>alert("temp1='.$temp1.'")</script>';
									$q=$temp1[0];     $ans=$temp1[1];
									//echo '<script>alert("$q='.$q.'")</script>';
									//echo '<script>alert("ans='.$ans.'")</script>';
									//echo '<script>alert("sectionid='.$_SESSION['seid'].'")</script>';
									$query=query("select answer from question where qid='".$q."'");
									confirm($query);
									$row=fetch_array($query);

									//echo '<script>alert("co ans='.$row['answer'].'")</script>';
									if($row['answer']==$ans)
									{
										//echo '<script>alert("true")</script>';
										$count+=$pm;      // + $pm for right answer.
										$right++;
									}
									else
									if($row['answer']!=$ans)
									{
										//echo '<script>alert("false")</script>';
										$count-=$nm;   // negative marking - $nm
										$wrong++;
									}
								}	
							}

							$ta=($right+$wrong);
							$_SESSION['right_answers']+=$right;
							$_SESSION['wrong_answers']+=$wrong;
							$_SESSION['total_attempted']+=$ta;
							$_SESSION['section_marks']+=$count;

							// echo '<script>alert("right answers='.$_SESSION['right_answers'].'")</script>';
							// echo '<script>alert("wrong answers='.$_SESSION['wrong_answers'].'")</script>';
							// echo '<script>alert("total_attempted='.$_SESSION['total_attempted'].'")</script>';
							// echo '<script>alert("section marks='.$_SESSION['section_marks'].'")</script>';
							// echo '<script>alert("total section marks='.$_SESSION['total_section_marks'].'")</script>';
							unset($_SESSION['section']);
						}
						header("refresh:0;url=final_result.php?completed");
						exit;
					}

					if($temp_count!=1 || $last==$_SESSION['seid'])
					{
						$counting=0;
						$occured=0;

						foreach($temp as $v)         // only unique sectionids will occur in an exam.
						{
							//echo '<script>alert("foreach counting='.$counting.'")</script>';
							//echo '<script>alert("$v='.$v.'")</script>';
							$counting++;

							if(!isset($_SESSION['right_answers']))
								$_SESSION['right_answers']=0;

							if(!isset($_SESSION['wrong_answers']))
								$_SESSION['wrong_answers']=0;

							if(!isset($_SESSION['total_attempted']))
								$_SESSION['total_attempted']=0;

							if(!isset($_SESSION['section_marks']))
								$_SESSION['section_marks']=0;

							if($occured==1 || $v==$last)
							{		
								//echo '<script>alert("entered in occured=1,section value='.$_SESSION['section'].'")</script>';
								// echo '<script>alert("section='.$_SESSION['section'].'")</script>';
								// counting result on ending of every section.
								// though,this is not last section so only calculate result.

								$count=0;                // to count total score.
								$mm=0;					// for main marks
								$right=0;				// for right answers
								$wrong=0;				// for wrong answers
								$total_questions=0;     // for total questions
	
								if(isset($_SESSION['seid']))
								{
									$sectionid=$_SESSION['seid'];

									$query_section=query("select * from section where seid='".$sectionid."'");
									confirm($query_section);
									$row_section=fetch_array($query_section);

									$pm=intval($row_section['pm']);            // positive marks in section.
									$nm=floatval($row_section['nm']);            // negative marks in section.
									//$mm=$row_section['mm'];            // main marks in section.
									$t1=$_SESSION['qids'];
									$total_questions=count($t1);
									//$_SESSION['total_questions']+=count($t1);
									$mm=$total_questions*$pm;
									//$_SESSION['total_section_marks']+=$mm;
								}

								if(isset($_SESSION['section']))
								{
									$pair=explode(",",$_SESSION['section']);
									$j=0;
									
									for($j=0;$j<count($pair);$j++)
									{
										if(!empty($pair[$j]))
										{
											$temp1=explode(" ",$pair[$j]);
											$q=$temp1[0];     $ans=$temp1[1];
											
											$query=query("select answer from question where qid='".$q."'");
											confirm($query);
											$row=fetch_array($query);

											if($row['answer']==$ans)
											{	$count+=$pm;      // + $pm for right answer.
												$right++;
											}
											else
											if($row['answer']!=$ans)
											{
												$count-=$nm;   // negative marking - $nm
												$wrong++;
											}
										}	
									}

									$ta=$right+$wrong;
									$_SESSION['right_answers']+=$right;
									$_SESSION['wrong_answers']+=$wrong;
									$_SESSION['total_attempted']+=$ta;
									$_SESSION['section_marks']+=$count;

									unset($_SESSION['section']);

									if($last==$_SESSION['seid'])
									{
										header("refresh:0;url=final_result.php?completed");
										exit;
									}
									else
									{
										$_SESSION['seid']=$v;
										header("refresh:0;url=exam1.php?username={$_SESSION['username']}&ulid={$_SESSION['ulid']}&eid={$_SESSION['eid']}");
										exit;
									}
								}
								else
								if(!isset($_SESSION['section']) && $last!=$_SESSION['seid'])
								{
									$_SESSION['seid']=$v;
									header("refresh:0;url=exam1.php?username={$_SESSION['username']}&ulid={$_SESSION['ulid']}&eid={$_SESSION['eid']}");
									exit;
								}
								else
								if(!isset($_SESSION['section']) && $last==$_SESSION['seid'])
								{
									header("refresh:0;url=final_result.php?completed");
									exit;
								}

								$occured=0;
								break;
							}   // if($occured==1)  ends.
							// else 
							// if(!isset($_SESSION['section'])) // if $_SESSION['section'] is not set.Direct submit
							// {
							// 	if(!isset($_SESSION['right_answers']))
							// 		$_SESSION['right_answers']=0;

							// 	if(!isset($_SESSION['wrong_answers']))
							// 		$_SESSION['wrong_answers']=0;

							// 	if(!isset($_SESSION['total_attempted']))
							// 		$_SESSION['total_attempted']=0;

							// 	if(!isset($_SESSION['section_marks']))
							// 		$_SESSION['section_marks']=0;

							// 	if(!isset($_SESSION['total_section_marks']))
							// 		$_SESSION['total_section_marks']=0;

							// 	if(!isset($_SESSION['total_questions']))
							// 		$_SESSION['total_questions']=0;

							// 	header("refresh:0;url=final_result.php?completed");
							// 	exit;
							// }
							
							if($v==$_SESSION['seid'])
								$occured=1;
						}                    // foreach loop ends
					}
	}
	else
		redirect("final_result.php?issue");
?>