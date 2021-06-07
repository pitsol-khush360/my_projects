<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php
	if(isset($_GET['qtid'])&&isset($_GET['qstid'])) 
	{	
		$qtid=$_GET['qtid'];
		$qstid=$_GET['qstid'];


		if(isset($_POST['submit_insert_question']))
		{
			if($_POST['answer']!="notset")
			{
				//$questions=trim($_POST['question']);
				$question=str_replace(' ','&nbsp;',$_POST['question']);
				$question=nl2br($question);

				$op1=$_POST['op1'];
				$op2=$_POST['op2'];
				$op3=$_POST['op3'];
				$op4=$_POST['op4'];
				$op5=$_POST['op5'];
				$answer=$_POST['answer'];
				$for_practice=$_POST['for_practice'];
				$des=trim($_POST['description']);

				if($for_practice=="yes")
					$for_practice=1;
				else
				if($for_practice=="no")
					$for_practice=0;
				
				$question_img=$_FILES['question_img']['name'];

				if(!empty($_FILES['question_img']['name']) && ($_FILES['question_img']['type']=="image/jpg" || $_FILES['question_img']['type']=="image/jpeg" || $_FILES['question_img']['type']=="image/png")) 
				{
					// renaming image
					$question_img=date("YmdHis").'queimg'.$question_img;

					$query=query("insert into question(qtid,qstid,question,op1,op2,op3,op4,op5,answer,question_img,for_practice,description) values('".$qtid."','".$qstid."','".$question."','".$op1."','".$op2."','".$op3."','".$op4."','".$op5."','".$answer."','".$question_img."','".$for_practice."','".$des."')");
					
					confirm($query);

					move_uploaded_file($_FILES['question_img']['tmp_name'],ADMINQUESTION_UPLOAD.DS.$question_img);
				
					redirect("admin_question_bank.php?qtid={$qtid}&qstid={$qstid}");
				}
				else
				{
					$query=query("insert into question(qtid,qstid,question,op1,op2,op3,op4,op5,answer,for_practice,description) values('".$qtid."','".$qstid."','".$question."','".$op1."','".$op2."','".$op3."','".$op4."','".$op5."','".$answer."','".$for_practice."','".$des."')");
					confirm($query);
					redirect("admin_question_bank.php?qtid={$qtid}&qstid={$qstid}");
				}
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
  			<div class="col-md-12"><span><big><b>Add Questions</b></big></span></div>
	  	</div>
		 <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post" enctype="multipart/form-data">
								<div class="form-group">
									<label for="question" class="col-sm-2 control-label">Question</label>
									<div class="col-sm-8">
										<textarea name="question" id="question" cols="50" rows="10" class="form-control1" placeholder="Type your question here" autofocus required style="height:100px;"></textarea>
									</div>
									<div class="col-sm-2">
										<p class="help-block"> Write Question</p>
									</div>
								</div>
								<div class="form-group">
									<label for="op1" class="col-sm-2 control-label">Option 1. </label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="op1" id="op1" placeholder="Type first option" required="">
									</div>
									<div class="col-sm-2">
										<p class="help-block">Write first option</p>
									</div>
								</div>
								<div class="form-group">
									<label for="op2" class="col-sm-2 control-label">Option 2. </label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="op2" id="op2" placeholder="Type second option" required="">
									</div>
									<div class="col-sm-2">
										<p class="help-block">Write second option</p>
									</div>
								</div>
								<div class="form-group">
									<label for="op3" class="col-sm-2 control-label">Option 3. </label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="op3" id="op3" placeholder="Type third option" required="">
									</div>
									<div class="col-sm-2">
										<p class="help-block">Write third option</p>
									</div>
								</div>
								<div class="form-group">
									<label for="nm" class="col-sm-2 control-label">Option 4</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="op4" id="op4" placeholder="Type fourth option" required="">
									</div>
									<div class="col-sm-2">
										<p class="help-block">Write fourth option</p>
									</div>
								</div>
								<div class="form-group">
									<label for="op5" class="col-sm-2 control-label">Option 5.</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="op5" id="op5" placeholder="Type fiveth option(only if your Question contain five option)">
									</div>
									<div class="col-sm-2">
										<p class="help-block">Write fifth option</p>
									</div>
								</div>
								<div class="form-group">
									<label for="answer" class="col-sm-2 control-label">Answer</label>
									<div class="col-sm-8">
										<select name="answer" class="form-control1" required>
											<option value="notset">Click To Select Correct Answer</option>
											<option value="op1">Option 1</option>
											<option value="op2">Option 2</option>
											<option value="op3">Option 3</option>
											<option value="op4">Option 4</option>
											<option value="op5">Option 5(only if in you are created this option.)</option>
										</select>
									</div>
									<div class="col-sm-2">
										<p class="help-block">select your answer</p>
									</div>
								</div>
								<div class="form-group">
									<label for="des" class="col-sm-2 control-label">Description</label>
									<div class="col-sm-8">
										<textarea name="description" id="des" cols="50" rows="5" class="form-control1" placeholder="Type Description here" autofocus required></textarea>
									</div>
									<div class="col-sm-2">
										<p class="help-block"> Write Description</p>
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
										<input type="file" class="form-control1" name="question_img" id="question_img" >
									</div>
									<div class="col-sm-2">
										<p class="help-block">Choose the if image you want to add with this Question</p>
									</div>
								</div>
								<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
								<div class="form-group">
									<div class="col-sm-12 text-center">
										<input type="submit" name="submit_insert_question" class="btn btn_5 btn-lg btn-primary " value="Add Question">
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
?>
<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 