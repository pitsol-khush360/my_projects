<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
	if(isset($_GET['seid'])&&isset($_GET['qid'])) 
	{	
		$seid=$_GET['seid'];
		$qid=$_GET['qid'];

		$query=query("select * from question_bank where qid='".$qid."';");
			confirm($query);
		$row=fetch_array($query);


		if(isset($_POST['submit_update_question']))
		{
			$questions=$_POST['questions'];
			$op1=$_POST['op1'];
			$op2=$_POST['op2'];
			$op3=$_POST['op3'];
			$op4=$_POST['op4'];
			$op5=$_POST['op5'];
			$answer=$_POST['answer'];
			$question_img=$_FILES['question_img']['name'];
			if (!empty($question_img)) 
			{
				unlink(ADMINQUESTION_UPLOAD.DS.$row['question_img']);

				$query=query("UPDATE question_bank SET seid = '".$seid."', questions = '".$questions."',op1 = '".$op1."',op2 = '".$op2."',op3 = '".$op3."',op4 = '".$op4."',op5 = '".$op5."',answer = '".$answer."', question_img='".$question_img."' WHERE qid ='".$qid."';");

				move_uploaded_file($_FILES['question_img']['tmp_name'],ADMINQUESTION_UPLOAD.DS.$question_img);
			}
			else
			{
				$query=query("UPDATE question_bank SET seid = '".$seid."', questions = '".$questions."',op1 = '".$op1."',op2 = '".$op2."',op3 = '".$op3."',op4 = '".$op4."',op5 = '".$op5."',answer = '".$answer."' WHERE qid ='".$qid."';");

			}
			confirm($query);
			redirect("admin_question.php?seid=".$seid);
		}
?>
<div class="graphs">
	<div class="xs">
		<div class="row text-center">
  			<div class="col-md-12"><span><big><b>Add Questions</b></big></span></div>
	  	</div>
		 <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post" enctype="multipart/form-data">
								<div class="form-group">
									<label for="questions" class="col-sm-2 control-label">Question</label>
									<div class="col-sm-8">
										<textarea name="questions" id="questions" cols="50" rows="5" class="form-control1" placeholder="Type your question here" autofocus required><?php echo $row['questions']; ?>
										</textarea>
									</div>
									<div class="col-sm-2">
										<p class="help-block"> Write Question</p>
									</div>
								</div>
								<div class="form-group">
									<label for="op1" class="col-sm-2 control-label">Option 1. </label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="op1" id="op1" placeholder="Type first option" value="<?php echo $row['op1']; ?>" required="">
									</div>
									<div class="col-sm-2">
										<p class="help-block">Write first option</p>
									</div>
								</div>
								<div class="form-group">
									<label for="op2" class="col-sm-2 control-label">Option 2. </label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="op2" id="op2" placeholder="Type second option" value="<?php echo $row['op2']; ?>" required="">
									</div>
									<div class="col-sm-2">
										<p class="help-block">Write second option</p>
									</div>
								</div>
								<div class="form-group">
									<label for="op3" class="col-sm-2 control-label">Option 3. </label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="op3" id="op3" placeholder="Type third option" value="<?php echo $row['op3']; ?>" required="">
									</div>
									<div class="col-sm-2">
										<p class="help-block">Write third option</p>
									</div>
								</div>
								<div class="form-group">
									<label for="nm" class="col-sm-2 control-label">Option 4</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="op4" id="op4" placeholder="Type fourth option" value="<?php echo $row['op4']; ?>" required="">
									</div>
									<div class="col-sm-2">
										<p class="help-block">Write fourth option</p>
									</div>
								</div>
								<div class="form-group">
									<label for="op5" class="col-sm-2 control-label">Option 5.</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="op5" id="op5" value="<?php echo $row['op5']; ?>" placeholder="Type fiveth option(only if your Question contain five option)">
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
											<option>click to select correct answer</option>
											<option selected value="op1">Option 1</option>
											<option value="op2">Option 2</option>
											<option value="op3">Option 3</option>
											<option value="op4">Option 4</option>
											<option value="op5">Option 5(only if in you are created this option.)</option>
										</select>
									<?php } else if($row['answer']=="op2"){ ?>
										<select name="answer" class="form-control1" required>
											<option>click to select correct answer</option>
											<option value="op1">Option 1</option>
											<option selected value="op2">Option 2</option>
											<option value="op3">Option 3</option>
											<option value="op4">Option 4</option>
											<option value="op5">Option 5(only if in you are created this option.)</option>
										</select>
									<?php } else if($row['answer']=="op3"){ ?>
										<select name="answer" class="form-control1" required>
											<option>click to select correct answer</option>
											<option value="op1">Option 1</option>
											<option value="op2">Option 2</option>
											<option selected value="op3">Option 3</option>
											<option value="op4">Option 4</option>
											<option value="op5">Option 5(only if in you are created this option.)</option>
										</select>
									<?php } else if($row['answer']=="op4"){ ?>
										<select name="answer" class="form-control1" required>
											<option>click to select correct answer</option>
											<option value="op1">Option 1</option>
											<option value="op2">Option 2</option>
											<option value="op3">Option 3</option>
											<option selected value="op4">Option 4</option>
											<option value="op5">Option 5(only if in you are created this option.)</option>
										</select>
									<?php } else if($row['answer']=="op5"){ ?>
										<select name="answer" class="form-control1" required>
											<option>click to select correct answer</option>
											<option value="op1">Option 1</option>
											<option value="op2">Option 2</option>
											<option value="op3">Option 3</option>
											<option value="op4">Option 4</option>
											<option selected value="op5">Option 5(only if in you are created this option.)</option>
										</select>
									<?php } else { ?>
										<select  name="answer" class="form-control1" required>
											<option selected>click to select correct answer</option>
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
									<label for="question_img" class="col-sm-2 control-label">Choose Image</label>
									<div class="col-sm-8">
										<div class="col-sm-1">	
											<img src="../../resources/
											<?php 
												$question_path=image_path_question($row['question_img']); 
												echo $question_path; 
											?>" width="50" height="50">
										</div>
										<div class="col-sm-11">
											<input type="file" class="form-control1" name="question_img" id="question_img" >
										</div>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Choose the if image you want to add with this Question</p>
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