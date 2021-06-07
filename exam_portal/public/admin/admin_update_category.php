<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php 
	if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
  	{
	if(isset($_GET['id']))
	{
		$id=$_GET['id'];
		$query=query("select * from course_category where ccid='".$id."'");
		confirm($query);
		$row=fetch_array($query);
		$cat_name=$row['category_name'];
		if(isset($_POST['submit_update']))
		{	
			$ccid=$_POST['id'];
			$cc=$_POST['cc'];
			$query=query("UPDATE course_category SET category_name = '".$cc."' WHERE ccid = ".$ccid.";");
			confirm($query);
			redirect("admin_category.php");

		}
?>

<div class="graphs">
	<div class="xs">
		<div class="row">
			<div class="col-12">
				<a href="admin_category.php" class="btn btn-info btn-sm">Back To Categories</a>
			</div>
		</div>
		<div class="row">
  			<div class="col-md-4 col-xs-6"><span><big><b>Course Category</b></big></span></div>
	  	</div>
		 <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post" style="margin:18% auto 21% auto;">
								<div class="form-group">
									<label for="ccid" class="col-sm-2 control-label">ID</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="id" val id="ccid" value="<?php echo $id; ?>" readonly>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Category Id</p>
									</div>
								</div>
								<div class="form-group">
									<label for="cc" class="col-sm-2 control-label">Category</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" value="<?php echo $cat_name ?>"  name="cc" id="cc" placeholder="Write Category" val autofocus>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Category of the exam</p>
									</div>
								</div>
								<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
								<div class="form-group">
									<label for="submit_insert" class="col-sm-2 control-label"></label>
									<div class="col-sm-8 text-center">
										<input type="submit" name="submit_update" class="btn btn_5 btn-lg btn-primary " id="submit_insert" value="update">
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