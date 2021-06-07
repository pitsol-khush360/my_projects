<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php 
	if(isset($_GET['ccid']) && isset($_GET['scid']))	
	{	
		$ccid=$_GET['ccid'];
		$scid=$_GET['scid'];

		if(isset($_POST['submit_insert_sec_cnt']))
		{
			$sec_cnt=$_POST['sec_cnt'];
			$scid=$_POST['scid'];
			$query=query("insert into test(scid,no_of_section) value('".$scid."','".$sec_cnt."')");
			confirm($query);
			redirect("admin_test.php?ccid=".$ccid."&scid=".$scid);
		}
?>
<div class="graphs">
	<div class="xs">
		<div class="row">
  			<div class="col-md-4 col-xs-6"><span><big><b>Add Number of Section</b></big></span></div>
	  	</div>
		 <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post" style="margin:18% auto 21% auto;">
								<input type="hidden" name="scid" value="<?php echo $scid ; ?>">
								<div class="form-group">
									<label for="sec_cnt" class="col-sm-2 control-label">Number of section</label>
									<div class="col-sm-8">
										<input type="number" class="form-control1" name="sec_cnt" id="sec_cnt" placeholder="Enter the number of section " required autofocus>
									</div>
									<div class="col-sm-2">
										<p class="help-block">no. of section of sub-category</p>
									</div>
								</div>
								<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
								<div class="form-group">
									<label for="submit_insert" class="col-sm-2 control-label"></label>
									<div class="col-sm-8 text-center">
										<input type="submit" name="submit_insert_sec_cnt" class="btn btn_5 btn-lg btn-primary " value="Add">
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