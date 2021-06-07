<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php 
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']))
{
	if(isset($_GET['qstid']) && isset($_GET['qtid']))
	{
		$qstid=$_GET['qstid'];
		$qtid=$_GET['qtid'];

		$query=query("select * from question_sub_topic where qstid='".$qstid."'");
		confirm($query);

		$row=fetch_array($query);
		$qst=$row['question_sub_topic'];


		if(isset($_POST['submit_update']))
		{	
			$qstid=$_POST['qstid'];
			$qst=$_POST['qst'];
			$query=query("UPDATE question_sub_topic SET question_sub_topic = '".$qst."' WHERE qstid = ".$qstid.";");
			confirm($query);
			redirect("admin_sub_topic.php?qtid={$qtid}");

		}
?>

<div class="graphs">
	<div class="xs">
		<div class="row">
			<div class="col-12">
				<a href="admin_sub_topic.php?qtid=<?php echo $qtid; ?>" class="btn btn-info btn-sm">Back To Sub-Topics</a>
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
										<input type="text" class="form-control1" name="qstid" id="qstid" value="<?php echo $qstid; ?>" readonly>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Question sub Topic Id</p>
									</div>
								</div>
								<div class="form-group">
									<label for="qst" class="col-sm-2 control-label">Question Sub Topic</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" value="<?php echo $qst ?>"  name="qst" id="qst" placeholder="Write Topic" autofocus>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Sub Topic For Exam</p>
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
		redirect("admin_sub_topic.php?qstid={$id}");
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