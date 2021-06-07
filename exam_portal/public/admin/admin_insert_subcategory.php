<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php 
		if(isset($_GET['cat_id']))	
		{	
		if(isset($_POST['submit_insert']))
		{
			$scn=$_POST['scn'];
			$cat=$_POST['cat_id'];
			$query=query("insert into sub_category(ccid,sub_category_name) value('".$cat."','".$scn."')");
			confirm($query);
			redirect("admin_subcategory.php?id=$cat");
		}
?>
<div class="graphs">
	<div class="xs">
		<div class="row">
			<div class="col-12">
				<a href="admin_subcategory.php?id=<?php echo $_GET['cat_id']; ?>" class="btn btn-info btn-sm">Back To Sub-Categories</a>
			</div>
		</div>
		<div class="row">
  			<div class="col-md-4 col-xs-6"><span><big><b>Add Sub-Category</b></big></span></div>
	  	</div>
		 <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post" style="margin:18% auto 21% auto;">
								<input type="hidden" name="cat_id" value="<?php echo $_GET['cat_id']; ?>">
								<div class="form-group">
									<label for="scn" class="col-sm-2 control-label">Sub-Category</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="scn" id="scn" placeholder="Write Sub-Category" autofocus>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Sub-Category of the exam</p>
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
<?php }
	  else 
	  {		
	  		echo "ja ja phela category set kere aa.......oo le link........<a href='admin_category'>jali su nikal</a>";
	  } ?>
<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 