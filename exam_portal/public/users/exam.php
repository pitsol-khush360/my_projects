<?php include("validateUserMultipleLogin.php"); ?>

<?php 
if(!isset($_SESSION['exam_portal']))
{
	redirect("index.php?show_exams");
	exit;
}

if(!isset($_SESSION['set']) && !isset($_GET['set']))   // if set is not available then it is issue.
	redirect("final_result.php?issue");

	if(isset($_SESSION['username']) && isset($_SESSION['ulid']) && isset($_GET['eid']))
	{
?>

<?php
	if(!isset($_SESSION['eid']) || !isset($_SESSION['set']))
	{
		$_SESSION['eid']=$_GET['eid'];
		$_SESSION['set']=$_GET['set'];
	}

	$sectionid="";

	if(!isset($_SESSION['seids']) && !isset($_SESSION['seid']))      // if first time loads.
	{
		$query_eq=query("select * from exam_questions where eid='".$_GET['eid']."' and set_of_exam='".$_SESSION['set']."'");
		confirm($query_eq);
		
		$seids=array();	            // declaring array after it record will append in this array.

		if(mysqli_num_rows($query_eq)!=0)
		{
			while($row_eq=fetch_array($query_eq))     // for only seids
			{
				$q_cs=query("select * from section where seid='".$row_eq['seid']."'");
				confirm($q_cs);

				if(mysqli_num_rows($q_cs)!=0)
					array_push($seids,$row_eq['seid']);
			}
		}

		$_SESSION['seids']=$seids;   // session for section array.

		if(!isset($_SESSION['seid']))
			$sectionid=$seids[0];

		$query_q=query("select * from exam_questions where eid='".$_GET['eid']."' and seid='".$sectionid."' and set_of_exam='".$_SESSION['set']."'"); // for qids
		confirm($query_q);
		$row_q=fetch_array($query_q);

		$q=$row_q['qid'];            // fetching string containing qids
		$qids=explode("#",$q);     // split string on space.

		// exam_questions -->  question_bank  --> question
		$temp_qids=array();
		foreach($qids as $value)
		{
			if($value!="" && !is_null($value) && $value!=" " && $value!="undefined")
			{
				$q_qb=query("select * from question_bank where qid='".$value."'");
				confirm($q_qb);

				if(mysqli_num_rows($q_qb)!=0)
				{
					$r_qb=fetch_array($q_qb);

					$q_cq=query("select * from question where qid='".$r_qb['qids']."'");
					confirm($q_cq);

					if(mysqli_num_rows($q_cq)!=0)
					{
						// Making original qids array (these ids are available in question table)
						array_push($temp_qids,$r_qb['qids']);
					}
				}
			}
		}

		$_SESSION['qids']=$temp_qids;   // question array
		$_SESSION['seid']=$sectionid;
	}
	else
	{
			$sectionid=$_SESSION['seid'];

			$query_q=query("select * from exam_questions where eid='".$_GET['eid']."' and seid='".$_SESSION['seid']."' and set_of_exam='".$_SESSION['set']."'"); // for qids
			confirm($query_q);
			$row_q=fetch_array($query_q);
			$q=$row_q['qid'];            // fetching string containing qids
			$qids=explode("#",$q);     // split string on space.
			
			// exam_questions -->  question_bank  --> question
			$temp_qids=array();
			foreach($qids as $value)
			{
				if($value!="" && $value!="undefined" && $value!=" ")
				{
					$q_qb=query("select * from question_bank where qid='".$value."'");
					confirm($q_qb);

					if(mysqli_num_rows($q_qb)!=0)
					{
						$r_qb=fetch_array($q_qb);

						$q_cq=query("select * from question where qid='".$r_qb['qids']."'");
						confirm($q_cq);

						if(mysqli_num_rows($q_cq)!=0)
						{
							// Making original qids array (these ids are available in question table)
							array_push($temp_qids,$r_qb['qids']);
						}
					}
				}
			}

			$_SESSION['qids']=$temp_qids;   // question array
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo APP; ?> - Live Exam</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <link href="css/exam.css?<?php echo time(); ?>" rel="stylesheet">
    
    <style type="text/css">
    	@import "https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css";
        <?php include("../css/styles.php"); ?>
    </style>

    <script type="text/javascript">
    	function timer() 
    	{
    		<?php
				$query_time=query("select * from section where seid='".$sectionid."'");
				confirm($query_time);
				$row_time=fetch_array($query_time);
				// we have section timing in minutes.
				$timing=$row_time['section_timing'];
			?>

    		var temp=(<?php echo $timing; ?>); // in minutes

    		// 1 minute=60*1000 milliseconds
    		var countdown=temp*60*1000;

    		var timer=setInterval(run,1000);  // 1s=1000ms

    		function run()
			{
				countdown-=1000;

				// 1 hour = 60*60*1000 ms. to change in milliseconds to minute devide by 60 * 1000
				var hour=Math.floor(countdown/(60*60*1000));
				// 1 minute=60*1000 ms. to change in milliseconds to minute devide by 60 * 1000
				// removing hours from countdown that has considered already.
				var min = Math.floor((countdown-(hour*60*60*1000))/(60*1000));
				// 1s=1000ms. to change in milliseconds to minute devide by 1000
				// removing minutes from countdown that has considered already.
					var sec = Math.floor((countdown-(min*60*1000))/1000);

				// adding extraa seconds that are greater than 60 to minutes.
				if(sec>=60)
				{
					while(sec>=60)
					{
						sec%=60;
						min+=1;
					}
					min-=1;  // because it is running one extraa minute.
					// from above operation if minutes become > 60 then add extraa to hours.
					while(min>=60)
					{
						min%=60;
						hour+=1;
					}
				}
				
				if(countdown<=0)
				{
					clearInterval(run);
					document.getElementById("form").submit();
				} 
				else
				{
					document.getElementById('timer').innerHTML=hour+" : "+min+" : "+sec;
					//$("#timer").html(hour +" : "+ min + " : " + sec);
				}
			}
    	}

    	// jquery validation for mouse leave the exam.
		/*$(document).mouseleave(function()
		{
			action=confirm("mouse leaved! This Action will submit your test.Are You Sure ?");
			// confirm dialog box in js returns boolean value true/false.
			if(action==true)
				window.location.href="final_result.php";
		});*/


		// javascript api for enter fullscreen (by clicking button) and leave fullscreen mode (by pressing esc).

		/*$(document).on('keydown',function(event) {
			// alert(event.which)
			// return false;
       		if(event.key=="Escape") 
       		{
           		action=confirm('This Action Will Submit Your Test.Are You Sure ?'); 

           		if(action==true)
           		{
           			event.preventDefault();  // prevent the default behaviour without it can not be redirected to final_result.php
           			window.location.href="final_result.php"; //and then redirect to result page
           		}
       		}*/

       		/*else if(event.keyCode==17)
       		{
       			//if ctrl+t is pressed
       			//ctrl+T opens a new tab in chrome so disabling ctrl
       			action=confirm('This Action Will Submit Your Test.Are You Sure ?'); 

           		if(action==true)
           		{
           			event.preventDefault();  // prevent the default behaviour without it can not be redirected to final_result.php
           			window.location.href="final_result.php"; //and then redirect to result page
           		}
           		event.preventDefault();
           		return false;
       		}
   		});*/

		function viewPage()
		{
			var el=document.documentElement;
			toggleFullscreen(el);
		}
		
		function toggleFullscreen(el)
		{	/* document.mozFullscreenElement -> to check element in fullscreen or not in 	                                    mozila firefox.

			   document.msFullscreenElement -> to check element in fullscreen or not in 	  microsoft browsers like Internet Explorer,microsoft edge.

			   document.webkitFullscreenElement -> to check element in fullscreen or not in   browsers like chrome.

			   as document.fullscreenElement
			  */

			if(document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement)
			{
				if(document.exitFullscreen)
					document.exitFullscreen();
				else
					if(document.mozCancelFullScreen)
						document.mozCancelFullScreen();
					else
						if(document.webkitExitFullscreen)
							document.webkitExitFullscreen();
						else
							if(document.msExitFullscreen)
								document.msExitFullscreen();
			}
			else
			{
				if(document.documentElement.requestFullscreen)
					el.requestFullscreen();
				else
					if(document.documentElement.mozRequestFullScreen)
						el.mozRequestFullScreen();
					else
						if(document.documentElement.webkitRequestFullscreen)
						{
							el.webkitRequestFullscreen();
						}
						else
							if(document.documentElement.msRequestFullscreen)
								el.msRequestFullscreen();
			}
		}

		var currentid=1;  // to access question no.
		function next() // only first time runs.
		{
			document.getElementById("que_"+currentid).style.display="block";
			document.getElementById("question_"+currentid).style.display="block";
			currentid++;
		}
    </script>
</head>
<body onload="timer();next();">
	<div class="container-fluid">
		<div class="row mt-1">
			<div class="col-12 col-md-4 text-center text-md-left">
				<h3><?php echo APP; ?></h3>
			</div>
			<div class="col-6 col-md-4 text-md-center mt-2 mt-md-0">
				<i class="fa fa-clock-o" style="font-size:25px;color:grey;"></i>&nbsp;
					<span style="font-size:25px;" id="timer"></span>
			</div>
			<div class="col-6 col-md-4 text-right text-md-center mt-2 mt-md-0">
				<button class="mt-1" id="fullscreenbutton" onclick="viewPage()">Full Screen</button>
			</div>
		</div>

		<?php
			$currentid=1;  // for question numbering.
			echo "<form action='section_controller.php' method='post' id='form'>";  // form will contain all questions.
			foreach($temp_qids as $q)
			{
				if($q!="" && $q!="undefined")
				{
					$query=query("select * from question where qid='".$q."'");
					confirm($query);

					if(mysqli_num_rows($query)!=0)
					{
						$row=fetch_array($query);
						$img_path=image_path_question($row['question_img']);
						$total=totalquestions($sectionid);

						$e_question=html_entity_decode($row['question']);
						$e_question_op1=html_entity_decode($row['op1']);
						$e_question_op2=html_entity_decode($row['op2']);
						$e_question_op3=html_entity_decode($row['op3']);
						$e_question_op4=html_entity_decode($row['op4']);

						$e_question_op5="";
						
						if($row['op5']!="") 
							$e_question_op5=html_entity_decode($row['op5']);

		$question=<<< question
		<span id="que_{$currentid}" style="display:none;">        <!-- changable question content -->
		<div class="row border border-left-0 border-right-0 mt-3">
			<div class="col-lg-4 col-sm-4 col-xs-4">
				<h4>Question {$currentid} / {$total}
				</h4>
			</div>
		</div>
		</span>

		<span id="question_{$currentid}" style="display:none;">
		<div class="row border border-top-0 border-left-0 border-right-0" style="font-size:1.1rem;">
			<div class="col-12 col-md-6 border border-left-0 border-top-0 border-bottom-0">
								<div class="card-body" for_id="{$q}" value="id_{$currentid}">
								<div class="col-12">Question {$currentid} :</div> 
								<div class="col-12 mt-2" style="word-wrap:break-word;">{$e_question}</div>
question;
?>
								<?php
									if(isset($row['question_img']) && $row['question_img']!="")
									{
										$question.=<<<question
										<div class="col-12 mt-3 mt-md-5">
										<img src="../../resources/{$img_path}" id="exam-que-img" style="">
										</div>
question;
									}
								?>

								<?php
								$question.=<<<question
								</div>
			</div>
			<div class="col-12 col-md-6 border border-left-0 border-top-0 border-bottom-0">
				<p class="text-center">
					<div class="card-body">
					<div class="col-12">
						<label for="que{$currentid}op1">
							<input type="radio" id="que{$currentid}op1" name="{$row['qid']}" value="op1">&nbsp;
							{$e_question_op1}
						</label>
					</div>
					<div class="col-12">
						<label for="que{$currentid}op2">
							<input type="radio" id="que{$currentid}op2" name="{$row['qid']}" value="op2">&nbsp;
							{$e_question_op2}
						</label>
					</div>
					<div class="col-12">
						<label for="que{$currentid}op3">
							<input type="radio" id="que{$currentid}op3" name="{$row['qid']}" value="op3">&nbsp;
							{$e_question_op3}
						</label>
					</div>
					<div class="col-12">
						<label for="que{$currentid}op4">
							<input type="radio" id="que{$currentid}op4" name="{$row['qid']}" value="op4">&nbsp;
							{$e_question_op4}
						</label>
					</div>
					<div class="col-12">
question;
		?>
						<?php 
						if($row['op5']!="") 
						{
						$question.= <<<question
						<label for="que{$currentid}op5">
							<input type="radio" id="que{$currentid}op5" name="{$row['qid']}" value="op5">&nbsp;
							{$e_question_op5}
						</label>
question;
						} 
						?>
					<?php
					$question.=<<< question
					</div>
					</div>       <!-- card ends -->
				</p>
			</div>
			</div>
			</span> <!-- changable span ends -->
question;
						echo $question;
						$currentid++;   // increment question no. by 1
					}
				}  // if($q!="") ends
			} // foreach loop ends
			echo "<input type=\"hidden\" name=\"".$_SESSION['csrf_name']."\" value=\"".$_SESSION['csrf_value']."\">";
			echo "</form>";
		?>	
</div><!-- container-fluid ends -->

<div class="container">
		<div class="row mt-3">
			<div class="col-3 col-md-4">
				<button class="btn mx-auto d-block" style="background-color:skyblue;width:100px;" id="submitbutton">Submit</button>
			</div>
			<div class="col-4 col-md-4">
				<button class="btn mx-auto d-block" style="background-color:orange;width:100px;" id="reviewbutton">Review</button>
			</div>
			<div class="col-5 col-md-4">
				<button class="btn btn-success mx-auto d-block" style="width:120px;" id="nextbutton">Save & Next</button>
			</div>
		</div>
		<div class="row">	
			<div class="col-xs-12 col-md-12">
					<div>
						<div class="card-body">
								<?php echo show_questions_grid($sectionid); ?>
						</div>
					</div>
			</div>
		</div>
</div>

	<script type="text/javascript">
		$("#nextbutton").on("click",			
			function()
			{
				// fetching id of grid button by its for_id attribute(created manually).
				question=$("[value=id_"+(currentid-1)+"]").attr("for_id");
				// fetching id of grid button by its value attribute.
				grid_que=$("[value="+(currentid-1)+"]").attr("id");  
			  	$("#"+grid_que).css("backgroundColor","green");
			  	// checking if last section or other sections are available
			  	$.get("next.php?question="+question,
			  		function(data,status1)
			  		{
			  			if(data.includes("continue"))
			  				status="continue";
			  			else
			  				if(data.includes("stop_next_section"))
			  					status="stop_next_section";
			  				else
			  					if(data.includes("stop_last"))
			  						status="stop_last";

			  			show();
			  		}
			  		 );

			  	<?php
			 		$len=$_SESSION['qids'];  // calculating length of qids array.
			 		$length=0;
			 		foreach($len as $l)
			 		{
			 			if($l!="" && !is_null($l))
			 				$length++;

			 		}
			 	?>

			 	total=<?php echo $length; ?>;

			 	function show()
			 	{
			  		if(currentid==(total+1) && status=="stop_next_section")
			  		{
			  			$('#next_available').modal('show');
			  		}
			  		else
			  		if(currentid==(total+1) && status=="stop_last")
			  		{
			  			$('#last_available').modal('show');
			  		}
			  		else
			  		if(currentid<=total && status=="continue")
			  		{
			  			$("#que_"+(currentid-1)).css("display","none");
			  			$("#question_"+(currentid-1)).css("display","none"); // previous question span display none.
			  			$("#que_"+currentid).css("display","block");
			 			$("#question_"+currentid).css("display","block");     // show next span question.
			 			currentid++;     // increment currentid
			 		}
			 	}
			  });
		</script>
		<script type="text/javascript">
			$(".gridquestionbutton").on("click",
				function()
				{
					$("#que_"+(currentid-1)).css("display","none");
					$("#question_"+(currentid-1)).css("display","none"); // recent question span display none.
					currentid=$(this).val();  // set clicked currentid.
					$("#que_"+currentid).css("display","block");
					$("#question_"+currentid).css("display","block");
					currentid++;
				}
				);
		</script>
		<script type="text/javascript">
			$("#reviewbutton").on("click",
				function()
				{
					<?php
			 			$len=$_SESSION['qids'];  // calculating length of qids array.
			 			$length=0;
				 		foreach($len as $l)
				 		{
				 			if($l!="" && !is_null($l))
				 				$length++;

				 		}
			 		?>

			 		total=<?php echo $length; ?>;

					if(currentid==(total+1))
					{
						grid_que=$("[value="+(currentid-1)+"]").attr("id");
						$("#"+grid_que).css("backgroundColor","orange");
					}
					else
					{
						grid_que=$("[value="+(currentid-1)+"]").attr("id");  
						$("#"+grid_que).css("backgroundColor","orange");

						$("#que_"+(currentid-1)).css("display","none");
			  			$("#question_"+(currentid-1)).css("display","none"); // previous question display none.
			  			$("#que_"+currentid).css("display","block");
			 			$("#question_"+currentid).css("display","block");     // show next span question.
			 			currentid++;     // increment currentid
					}
				}
						);
		</script>
		<script type="text/javascript">
			$("#submitbutton").on("click",
				function()
				{
					$('#to_submit').modal('show');
				}
						);
		</script>
		<div id="next_available" class="modal fade">
    		<div class="modal-dialog">
        		<div class="modal-content">
            		<div class="modal-header">
            			<h4>Instruction</h4>
                		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            		</div>
            		<div class="modal-body">
            			Your Section Is Completed. Please Wait For TimeUp For Entering In Next Section.
            		</div>
        		</div>
    		</div>
		</div>

		<div id="to_submit" class="modal fade">
    		<div class="modal-dialog">
        		<div class="modal-content">
            		<div class="modal-header">
            			<h4>Confirmation</h4>
                		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            		</div>
            		<div class="modal-body">
            			Are You Sure, You Want To Submit The Current Section ?
            		</div>
            		<div class="modal-footer">
            			<button class="btn btn-info" id="ok">Yes, Submit It</button>
            			<button class="btn btn-default" style="background-color:lightgrey;" id="no">Cancel</button>
            		</div>
        		</div>
    		</div>
		</div>

		<div id="last_available" class="modal fade">
    		<div class="modal-dialog">
        		<div class="modal-content">
            		<div class="modal-header">
            			<h4>Instruction</h4>
                		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            		</div>
            		<div class="modal-body">
            			Last Section Is Completed. Please Wait For TimeUp Or Click On Submit Button To Submit Your Exam.
            		</div>
        		</div>
    		</div>
		</div>

		<script type="text/javascript">
			$("#ok").on("click",
				function()
				{
					//location.href="section_controller1.php";
					$("#form").submit();
				}
						);
		</script>
		<script type="text/javascript">
			$("#no").on("click",
				function()
				{
					$("#to_submit").modal('hide');
				}
						);
		</script>

<?php
	}
	else
		echo '<p style="text-align:center;font-size:30px;">Sorry! Exam Can\'t Be Started...</p>';
?>
</body>
</html>