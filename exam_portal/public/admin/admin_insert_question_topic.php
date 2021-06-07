<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php 	
		if(isset($_POST['submit_insert']))
		{
			$qt=$_POST['qt'];
			
			$query=query("insert into question_topic(question_topic) value('".$qt."')");
			confirm($query);
			redirect("admin_question_topic.php");
		}
?>
<div class="graphs">
	<div class="xs">
		<div class="row">
			<div class="col-12">
				<a href="admin_question_topic.php" class="btn btn-info btn-sm">Back To Topics</a>
			</div>
		</div>
		<div class="row">
  			<div class="col-md-4 col-xs-6"><span><big><b>Insert Topic</b></big></span></div>
	  	</div>
		 <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post" style="margin:18% auto 21% auto;">
								<div class="form-group">
									<label for="qt" class="col-sm-2 control-label">Topic</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="qt" id="qt" placeholder="Write topic " autofocus>
									</div>
									<div class="col-sm-2">
										<p class="help-block">topic</p>
									</div>
								</div>
								<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
								<div class="form-group">
									<label for="submit_insert" class="col-sm-2 control-label"></label>
									<div class="col-sm-8 text-center">
										<input type="submit" name="submit_insert" class="btn btn_5 btn-lg btn-primary " id="submit_insert" value="Add">
									</div>
								</div>
							</form>
						</div>
					</div>
	</div>
</div>
<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 