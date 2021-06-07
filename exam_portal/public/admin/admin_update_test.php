<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php 
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
	if(isset($_GET['tid'])&&isset($_GET['scid'])&&isset($_GET['ccid']))
	{
		$scid=$_GET['scid'];
		$ccid=$_GET['ccid'];
		$tid=$_GET['tid'];
		$query=query("select * from test where tid='".$tid."'");
		confirm($query);
		$row=fetch_array($query);
		$sec_cnt=$row['no_of_section'];
		if(isset($_POST['submit_update_sec_cnt']))
		{	
			$tid=$_POST['tid'];
			$sec_cnt=$_POST['sec_cnt'];
			$query1=query("UPDATE test SET no_of_section = '".$sec_cnt."' WHERE tid = ".$tid.";");
			confirm($query1);
			redirect("admin_test.php?ccid=".$ccid."&scid=".$scid);

		}
?>

<div class="graphs">
	<div class="xs">
		<div class="row">
			<div class="col-12">
				<a href="admin_test.php?ccid=<?php echo $_GET['ccid']; ?>&scid=<?php echo $scid; ?>" class="btn btn-info btn-sm">Back To Sub-Categories Sections</a>
			</div>
		</div>
		<div class="row">
  			<div class="col-md-4 col-xs-6"><span><big><b>Update no. of section </b></big></span></div>
	  	</div>
		 <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post" style="margin:18% auto 21% auto;">
								<div class="form-group">
									<label for="tid" class="col-sm-2 control-label">ID</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="tid" id="tid" value="<?php echo $tid; ?>" readonly>
									</div>
									<div class="col-sm-2">
										<p class="help-block">ID of the no. of Section</p>
									</div>
								</div>
								<div class="form-group">
									<label for="cc" class="col-sm-2 control-label">No.of section</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" value="<?php echo $sec_cnt ?>"  name="sec_cnt" id="sec_cnt" placeholder="enter the section" required autofocus>
									</div>
									<div class="col-sm-2">
										<p class="help-block">no. of section</p>
									</div>
								</div>
								<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
								<div class="form-group">
									<label for="submit_insert" class="col-sm-2 control-label"></label>
									<div class="col-sm-8 text-center">
										<input type="submit" name="submit_update_sec_cnt" class="btn btn_5 btn-lg btn-primary " value="update">
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