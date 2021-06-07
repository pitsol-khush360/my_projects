<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']))
{
	if(isset($_GET['qtid'])&&isset($_GET['qstid'])&&isset($_GET['qid'])) 
	{	
		$qtid=$_GET['qtid'];
		$qstid=$_GET['qstid'];
		$qid=$_GET['qid'];

		$query=query("select * from question where qid='".$qid."';");
			confirm($query);
		$row=fetch_array($query);


		if(isset($_POST['submit_update_question']))
		{
			if($_POST['answer']!="notset")
			{
				$questions=str_replace(' ','&nbsp;',$_POST['question']);
				$questions=nl2br($questions);

				$op1=$_POST['op1'];
				$op2=$_POST['op2'];
				$op3=$_POST['op3'];
				$op4=$_POST['op4'];
				$op5=$_POST['op5'];
				$answer=$_POST['answer'];
				$for_practice=$_POST['for_practice'];
				$question_img=$_FILES['question_img']['name'];
				$hiddenquestionimage=$_POST['hidden_question_image'];
				$des=trim($_POST['description']);

				if($for_practice=="yes")
					$for_practice=1;
				else
				if($for_practice=="no")
					$for_practice=0;

				if(!empty($_FILES['question_img']['name']) && ($_FILES['question_img']['type']=="image/jpg" || $_FILES['question_img']['type']=="image/jpeg" || $_FILES['question_img']['type']=="image/png")) 
				{
					if($hiddenquestionimage!="" && !empty($hiddenquestionimage))
						unlink(ADMINQUESTION_UPLOAD.DS.$hiddenquestionimage);

					// renaming
					$question_img=date("YmdHis").'queimg'.$question_img;

					$query=query("UPDATE question SET question = '".$questions."', op1 = '".$op1."', op2 = '".$op2."',op3 = '".$op3."',op4 = '".$op4."',op5 = '".$op5."',answer = '".$answer."', question_img='".$question_img."' , for_practice='".$for_practice."' , description='".$des."' WHERE qid ='".$qid."';");

					move_uploaded_file($_FILES['question_img']['tmp_name'],ADMINQUESTION_UPLOAD.DS.$question_img);
				}
				else
				{
					$query=query("UPDATE question SET question = '".$questions."',op1 = '".$op1."',op2 = '".$op2."',op3 = '".$op3."',op4 = '".$op4."',op5 = '".$op5."',answer = '".$answer."' , for_practice='".$for_practice."' , description='".$des."' WHERE qid ='".$qid."';");

				}
				confirm($query);
				redirect("admin_question_bank.php?qtid={$qtid}&qstid={$qstid}");
			}
			else
			if($_POST['answer']=="notset")
			{
				setmessage("<p class='text-center text-danger'>Please Select Valid Answer !</p>");
			}
			else
			if($_POST['for_practice']=="notset")
			{
				setmessage("<p class='text-center text-danger'>Please Specify Question Is For Practice Or Not !</p>");
			}
		}
?>
<div class="graphs">
	<div class="xs">
		<div class="row">
			<div class="col-12">
				<a href="admin_question_bank.php?qtid=<?php echo $qtid; ?>&qstid=<?php echo $qstid; ?>" class="btn btn-info btn-sm">Back To Questions</a>
			</div>
		</div>
		<div class="row text-center">
			<div class="col-md-12"><?php displaymessage(); ?></div>
  			<div class="col-md-12"><span><big><b>Update Question</b></big></span></div>
	  	</div>
		 <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post" enctype="multipart/form-data">
								<div class="form-group">
									<label for="question" class="col-sm-2 control-label">Question</label>
									<div class="col-sm-8">
										<textarea name="question" id="question" cols="50" rows="5" class="form-control1" placeholder="Type your question here" autofocus required style="height:100px;"><?php 
											$e_question=str_replace("<br />","",$row['question']);
											$e_question=html_entity_decode($e_question);
											echo $e_question;
										?>
										</textarea>
									</div>
									<div class="col-sm-2">
										<p class="help-block"> Write Question</p>
									</div>
								</div>
								<div class="form-group">
									<label for="op1" class="col-sm-2 control-label">Option 1. </label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="op1" id="op1" placeholder="Type first option" value="<?php echo html_entity_decode($row['op1']); ?>" required="">
									</div>
									<div class="col-sm-2">
										<p class="help-block">Write first option</p>
									</div>
								</div>
								<div class="form-group">
									<label for="op2" class="col-sm-2 control-label">Option 2. </label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="op2" id="op2" placeholder="Type second option" value="<?php echo html_entity_decode($row['op2']); ?>" required="">
									</div>
									<div class="col-sm-2">
										<p class="help-block">Write second option</p>
									</div>
								</div>
								<div class="form-group">
									<label for="op3" class="col-sm-2 control-label">Option 3. </label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="op3" id="op3" placeholder="Type third option" value="<?php echo html_entity_decode($row['op3']); ?>" required="">
									</div>
									<div class="col-sm-2">
										<p class="help-block">Write third option</p>
									</div>
								</div>
								<div class="form-group">
									<label for="nm" class="col-sm-2 control-label">Option 4</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="op4" id="op4" placeholder="Type fourth option" value="<?php echo html_entity_decode($row['op4']); ?>" required="">
									</div>
									<div class="col-sm-2">
										<p class="help-block">Write fourth option</p>
									</div>
								</div>
								<div class="form-group">
									<label for="op5" class="col-sm-2 control-label">Option 5.</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="op5" id="op5" value="<?php echo html_entity_decode($row['op5']); ?>" placeholder="Type fiveth option(only if your Question contain five option)">
									</div>
									<div class="col-sm-2">
										<p class="help-block">Write fifth option</p>
									</div>
								</div>
								<div class="form-group">
									<label for="answer" class="col-sm-2 control-label">Answer</label>
									<div class="col-sm-8">
									<?php if ($row['answer']=="op1") { ?>
										<select name="answer" class="form-control1" required>
											<option value="notset">click to select correct answer</option>
											<option selected value="op1">Option 1</option>
											<option value="op2">Option 2</option>
											<option value="op3">Option 3</option>
											<option value="op4">Option 4</option>
											<option value="op5">Option 5(only if in you are created this option.)</option>
										</select>
									<?php } else if($row['answer']=="op2"){ ?>
										<select name="answer" class="form-control1" required>
											<option value="notset">click to select correct answer</option>
											<option value="op1">Option 1</option>
											<option selected value="op2">Option 2</option>
											<option value="op3">Option 3</option>
											<option value="op4">Option 4</option>
											<option value="op5">Option 5(only if in you are created this option.)</option>
										</select>
									<?php } else if($row['answer']=="op3"){ ?>
										<select name="answer" class="form-control1" required>
											<option value="notset">click to select correct answer</option>
											<option value="op1">Option 1</option>
											<option value="op2">Option 2</option>
											<option selected value="op3">Option 3</option>
											<option value="op4">Option 4</option>
											<option value="op5">Option 5(only if in you are created this option.)</option>
										</select>
									<?php } else if($row['answer']=="op4"){ ?>
										<select name="answer" class="form-control1" required>
											<option value="notset">click to select correct answer</option>
											<option value="op1">Option 1</option>
											<option value="op2">Option 2</option>
											<option value="op3">Option 3</option>
											<option selected value="op4">Option 4</option>
											<option value="op5">Option 5(only if in you are created this option.)</option>
										</select>
									<?php } else if($row['answer']=="op5"){ ?>
										<select name="answer" class="form-control1" required>
											<option value="notset">click to select correct answer</option>
											<option value="op1">Option 1</option>
											<option value="op2">Option 2</option>
											<option value="op3">Option 3</option>
											<option value="op4">Option 4</option>
											<option selected value="op5">Option 5(only if in you are created this option.)</option>
										</select>
									<?php } else { ?>
										<select  name="answer" class="form-control1" required>
											<option value="notset" selected>click to select correct answer</option>
											<option value="op1">Option 1</option>
											<option value="op2">Option 2</option>
											<option value="op3">Option 3</option>
											<option value="op4">Option 4</option>
											<option value="op5">Option 5(only if in you are created this option.)</option>
										</select>
									<?php } ?>
									</div>
									<div class="col-sm-2">
										<p class="help-block">select your answer your answer</p>
									</div>
								</div>
								<div class="form-group">
									<label for="des" class="col-sm-2 control-label">Description</label>
									<div class="col-sm-8">
										<textarea name="description" id="des" cols="50" rows="5" class="form-control1" placeholder="Type Description here" autofocus required><?php echo $row['description']; ?></textarea>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Write Description</p>
									</div>
								</div>
								<div class="form-group">
									<label for="for_practice" class="col-sm-2 control-label">Set This Question As Practice Question</label>
									<div class="col-sm-8">
										<select name="for_practice" class="form-control1" required>
											<option value="no">No</option>
											<option value="yes">Yes</option>
										</select>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Practice Question Or Not</p>
									</div>
								</div>
								<div class="form-group">
									<label for="question_img" class="col-sm-2 control-label">Choose Image</label>
									<div class="col-sm-8">
										<input type="hidden" name="hidden_question_image" value="<?php echo $row['question_img']; ?>">
										<?php
											if($row['question_img']!="")
											{
										?>
										<div class="col-sm-1">	
											<img src="../../resources/
											<?php 
												$question_path=image_path_question($row['question_img']); 
												echo $question_path; 
											?>" width="50" height="50">
										</div>
										<div class="col-sm-11">
										<?php
											}
											else
												echo '<div class="col-sm-12">';
										?>
											<input type="file" class="form-control1" name="question_img" id="question_img" >
										</div>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Choose image here if you want to add with this Question</p>
									</div>
								</div>
								<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
								<div class="form-group">
									<div class="col-sm-12 text-center">
										<input type="submit" name="submit_update_question" class="btn btn_5 btn-lg btn-primary " value="Update Question">
									</div>
								</div>
							</form>
						</div>
					</div>
	</div>
</div>
<?php
	}
    else
	{
		redirect("admin_category.php");
	}
}
else
    echo '<div class="bs-example4" data-example-id="contextual-table"><div class="row">
            <div class="col-12 text-center text-danger">
              <h4 style="margin-top:5em;">You Don\'t Have Permission To Access This Page</h4>
            </div>
          </div></div>';
?>
<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 