<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
	if(isset($_POST['submit_resolve_query']))
	{
		if(!isset($_POST['rqmode']))
			setmessage("Resolve Mode Must Be Selected As Either Email OR SMS");
		else
		if(trim($_POST['rquery'])=="")
			setmessage("Reply Field Can't Be Empty");
		else
			resolve_user_query();
	}
?>

<?php
	if(isset($_GET['uqid']) && isset($_GET['email']) && isset($_GET['mobile']))
	{
?>
<div class="graphs">
	<div class="row">
		<div class="col-12">
			<a class="btn btn-info btn-sm" href="admin_user_query.php">Back To User Queries</a>
		</div>
	</div>
	<div class="xs">
		<div class="row text-center">
			<h4 class="text-danger"><?php displaymessage(); ?></h4>
  			<div class="col-md-12"><span><big><b>Resolve Query</b></big></span></div>
	  	</div>
		 <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post">
								<div class="form-group text-center">
									<input type="radio" id="r1" name="rqmode" value="email">&nbsp;<label for="r1">Via Email</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" id="r2" name="rqmode" value="mobile">&nbsp;<label for="r2">Via SMS</label>
								</div>
								<div class="form-group">
									<label for="rquery" class="col-sm-2 control-label">Reply</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="rquery" id="rquery" placeholder="Reply" required>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Resolve Query</p>
									</div>
								</div>
								<div class="form-group">
									<label for="email" class="col-sm-2 control-label">Email</label>
									<div class="col-sm-8">
										<input type="email" class="form-control1" name="email" id="email" value="<?php echo $_GET['email']; ?>" readonly>
									</div>
									<div class="col-sm-2">
										<p class="help-block">User Email</p>
									</div>
								</div>
								<div class="form-group">
									<label for="mobile" class="col-sm-2 control-label">Mobile</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="mobile" id="mobile" value="<?php echo $_GET['mobile']; ?>" readonly>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Resolve Query</p>
									</div>
								</div>
								<input type="hidden" name="uqid" value="<?php echo $_GET['uqid']; ?>">
								<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
								<div class="form-group">
									<div class="col-sm-12 text-center">
										<input type="submit" name="submit_resolve_query" class="btn btn_5 btn-lg btn-primary " value="Resolve">
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
		redirect("admin_user_query.php");
}
else
    echo '<div class="bs-example4" data-example-id="contextual-table"><div class="row">
            <div class="col-12 text-center text-danger">
              <h4 style="margin-top:5em;">You Don\'t Have Permission To Access This Page</h4>
            </div>
          </div></div>';
?>
<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?>