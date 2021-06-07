<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php
	if(isset($_POST['submit_exam_syllabus']))
	{
		$exam=$_POST['exam'];

		if(isset($_POST['topics']) && isset($_POST['subtopics']))
		{
			foreach($_POST['topics'] as $key => $value)
			{
				$topic="";
				$topic=$_POST['topics'][$key];
				$subtopics="";
				$count=0;

				foreach($_POST['subtopics'][$key] as $stkey => $stvalue)
				{
					if($count==0)
					{
						if($count==(count($_POST['subtopics'][$key])-1))
							$subtopics.=$stvalue;
						else
						{
							$subtopics.=$stvalue.",";
							$count++;
						}
					}
					else
					{
						if($count==(count($_POST['subtopics'][$key])-1))
							$subtopics.=" ".$stvalue;
						else
						{
							$subtopics.=" ".$stvalue.",";
							$count++;
						}
					}
				}

				$date=date("Y-m-d H:i:s");

				$query=query("insert into exam_syllabus (eid,topic,sub_topics,syllabus_publish_date) values('".$exam."','".$topic."','".$subtopics."','".$date."')");
				confirm($query);
			}
		}

		if(isset($_POST['atopics']) && isset($_POST['asubtopics']))
		{
			foreach ($_POST['atopics'] as $key => $value) 
			{
				if(trim($_POST['atopics'][$key])!="")
				{
					$topic=trim($_POST['atopics'][$key]);
					$subtopics=trim($_POST['asubtopics'][$key][0]);

					$date=date("Y-m-d H:i:s");

					$query=query("insert into exam_syllabus (eid,topic,sub_topics,syllabus_publish_date) values('".$exam."','".$topic."','".$subtopics."','".$date."')");
					confirm($query);
				}
			}
		}
		redirect("admin_exam_syllabus.php");
	}
?>
<div class="graphs">
	<div class="xs">
		<div class="row">
			<div class="col-12">
				<a href="admin_exam_syllabus.php" class="btn btn-info">Back To Exams Syllabus</a>
			</div>
		</div>
		<div class="row text-center">
  			<div class="col-md-12"><p style="margin-top:2em;"><big><b>Add Exam Syllabus</b></big></p></div>
	  	</div>
		 <div class="tab-content">
			<div class="tab-pane active" id="horizontal-form">
				<form class="form-horizontal" method="post">

				<div class="row">
					<div class="col-12">
						<div class="form-group">
							<label for="exam" class="col-sm-2 control-label">Exam</label>
							<div class="col-sm-8">
								<select name="exam" class="form-control1" id="exam" required>
									<option value="">-- click to select exam --</option>
									
									<?php 	
										$query=query("select * from exam");
										confirm($query); 

										if(mysqli_num_rows($query)!=0)
										{
											while($row=fetch_array($query))
											{
									?>
									<option value="<?php echo $row['eid'] ?>"><?php echo $row['title'] ?></option>
									<?php 
											}
										}
									?>
								</select>
							</div>
							<div class="col-sm-2">
								<p class="help-block">select your Exam</p>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-12">
						<div class="form-group">
							<div class="col-sm-12 text-center">
								<h4>Select Topics & Related Sub-Topics</h4>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-2"></div>
							<div class="col-sm-8">
								<div style="min-height:100vh;max-height:100vh;overflow-y:scroll;">
							<?php 
								$q_t=query("select * from question_topic");
								confirm($q_t);

								if(mysqli_num_rows($q_t)!=0)
								{
									$topic=0;

									while($r_t=fetch_array($q_t))
									{
							?>
									<div class="row">
										<div class="col-sm-8">
											<div class="form-check" style="margin-top:2em;">
										    	<input type="checkbox" class="form-check-input topicboxes" name="topics[<?php echo $topic; ?>]" value="<?php echo $r_t['question_topic']; ?>" id="topic<?php echo $r_t['qtid']; ?>" style="height:15px;width:15px;" autocomplete="off">
										    	<label class="form-check-label text-success" for="topic<?php echo $r_t['qtid']; ?>" style="font-size:20px;"><?php echo $r_t['question_topic']; ?></label>
										  	</div>
										</div>
									</div>
									<div class="row">
										<div class="col-12">
							<?php
										$query=query("select * from question_sub_topic where qtid='".$r_t['qtid']."'");
    									confirm($query);

								    	if(mysqli_num_rows($query)!=0)
								    	{
								    		$subtopic=0;
								    		while($row=fetch_array($query))
											{
												$sub=<<< sub
												<div class="col-xs-6 col-sm-6 col-md-3">
													<div class="form-check-inline">
												    	<input type="checkbox" class="form-check-input subtopicboxtopic{$r_t['qtid']}" name="subtopics[{$topic}][{$subtopic}]" value="{$row['question_sub_topic']}" id="subtopic{$row['qstid']}" disabled autocomplete="off">
												    	<label class="form-check-label" for="subtopic{$row['qstid']}">{$row['question_sub_topic']}</label>
												  	</div>
												</div>
sub;
												echo $sub;
												$subtopic++;
											}
										}
							?>
										</div>
									</div>
							<?php
										$topic++;
									}
								}
							?>
							</div>

							<div class="row">
								<div class="form-group text-center">
									<div class="col-sm-2"></div>
									<div class="col-sm-8">
										<div class="form-group">
											<div class="form-check" style="margin-top:2em;">
										    	<input type="checkbox" name="onlyadditionaltopics" class="form-check-input topicboxes" id="onlyadditionaltopics" style="height:15px;width:15px;" autocomplete="off">
										    	<label class="form-check-label text-info" for="onlyadditionaltopics" style="font-size:20px;">Only Add Additional Topics & Sub-Topics</label>
										  	</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row" id="field-row">
								<div class="col-12">
								</div>
							</div>

							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-12">
					<div class="col-sm-12 text-right">
						<button type="button" class="btn btn-info" id="addfield"><i class="fa fa-plus"></i>&nbsp;Add Field For Additional Topic And Sub-Topics</button>
					</div>
					</div>
				</div>

				<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">

				<div class="row">
					<div class="col-12">
					<div class="form-group">
						<div class="col-sm-12 text-center">
							<input type="submit" name="submit_exam_syllabus" class="btn btn_5 btn-lg btn-primary" value="Add Syllabus" id="submitsyllabus" disabled style="margin-top: 2em;">
						</div>
					</div>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){

		var topicselected=false;

		var topics=0;
		var subtopics=0;

		$(".topicboxes").change(
			//alert($(this).attr("id"));
			function(){
				var tid=$(this).attr("id");

				if($(this).prop('checked'))
				{
					$(".subtopicbox"+tid).attr("disabled",false);
				}
				else
				{
					$(".subtopicbox"+tid).prop('checked',false);
					$(".subtopicbox"+tid).attr("disabled",true);
				}

				topicselected=false;

				$('.topicboxes').each(function(){
			        if($(this).is(":checked")) 
			        {
			            topicselected = true;
			        }
			    });

			    if(topicselected==true)
			    	$("#submitsyllabus").attr("disabled",false);
			    else
			    	$("#submitsyllabus").attr("disabled",true);
		});

		$("#addfield").on("click",
			function(){
				$("#field-row").append('<div class="form-group" style="margin-top:2em;"><label class="col-sm-2 control-label">Topic</label><div class="col-sm-8"><input type="text" name="atopics['+topics+']" class="form-control1"></div></div><div class="form-group"><label class="col-sm-2 control-label">Sub-Topics</label><div class="col-sm-8"><textarea name="asubtopics['+topics+']['+subtopics+']" class="form-control1" rows="5" cols="20" style="width:100%;height:120px;"></textarea></div></div>');

				topics++;
		});
	});
</script>

<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 