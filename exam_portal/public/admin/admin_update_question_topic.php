<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php 
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']))
{
	if(isset($_GET['qtid']))
	{
		$id=$_GET['qtid'];
		$query=query("select * from question_topic where qtid='".$id."'");
		confirm($query);
		$row=fetch_array($query);
		$qt=$row['question_topic'];
		if(isset($_POST['submit_update']))
		{	
			$qtid=$_POST['qtid'];
			$qt=$_POST['qt'];
			$query=query("UPDATE question_topic SET question_topic = '".$qt."' WHERE qtid = ".$qtid.";");
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
  			<div class="col-md-4 col-xs-6"><span><big><b>Update Question</b></big></span></div>
	  	</div>
		 <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post" style="margin:18% auto 21% auto;">
								<div class="form-group">
									<label for="qtid" class="col-sm-2 control-label">ID</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="qtid" id="qtid" value="<?php echo $id; ?>" readonly>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Question Topic Id </p>
									</div>
								</div>
								<div class="form-group">
									<label for="qt" class="col-sm-2 control-label">Question Topic </label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" value="<?php echo $qt ?>"  name="qt" id="qt" placeholder="Write Topic" autofocus>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Category of the exam</p>
									</div>
								</div>
								<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
								<div class="form-group">
									<div class="col-sm-8 text-center">
										<input type="submit" name="submit_update" class="btn btn_5 btn-lg btn-primary " id="submit_update" value="update">
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