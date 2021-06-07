<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php 
		if(isset($_POST['submit_insert_faq']))
		{	
			$question=$_POST['question'];
			$answer=$_POST['answer'];
			$query=query("insert into faqs (question,answer) values('".$question."','".$answer."')");
			confirm($query);
			redirect("admin_faqs.php");
		}
?>

<div class="graphs">
	<div class="xs">
		<div class="row">
			<div class="col-12">
				<a href="admin_faqs.php" class="btn btn-info">Back To FAQ's</a>
			</div>
		</div>
		<div class="row">
  			<div class="col-md-4 col-xs-6"><span><big><b>Add FAQ</b></big></span></div>
	  	</div>
		 <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post" style="margin:18% auto 21% auto;">
								<div class="form-group">
									<label for="question" class="col-sm-2 control-label">Question</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="question" id="question" placeholder="Enter Question" val autofocus required>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Question</p>
									</div>
								</div>
								<div class="form-group">
									<label for="answer" class="col-sm-2 control-label">Answer</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="answer" id="answer" placeholder="Enter Answer" val autofocus required>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Answer</p>
									</div>
								</div>
								<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
								<div class="form-group">
									<label for="submit_insert" class="col-sm-2 control-label"></label>
									<div class="col-sm-8 text-center">
										<input type="submit" name="submit_insert_faq" class="btn btn_5 btn-lg btn-primary " id="submit_insert" value="Update FAQ">
									</div>
								</div>
							</form>
						</div>
					</div>
	</div>
</div>

<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 