<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php 
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
	if(isset($_POST['submit_update'])&&isset($_GET['ccid']))
	{	
		$sub_category_name=$_POST['sub_category_name'];
		$scid=$_POST['id'];
		$query=query("UPDATE sub_category SET sub_category_name='".$sub_category_name."' WHERE scid = '".$scid."'");
		confirm($query);
		redirect("admin_subcategory.php?id=".$_GET['ccid']);

	}

	if(isset($_GET['id']))
	{
		$id=$_GET['id'];
		$query=query("select * from sub_category where scid='".$id."'");
		confirm($query);
		$row=fetch_array($query);
		$sub_category_name=$row['sub_category_name'];
?>

<div class="graphs">
	<div class="xs">
		<div class="row">
			<div class="col-12">
				<a href="admin_subcategory.php?id=<?php echo $_GET['ccid']; ?>" class="btn btn-info btn-sm">Back To Sub-Categories</a>
			</div>
		</div>
		<div class="row">
  			<div class="col-md-4 col-xs-6"><span><big><b>Update Section</b></big></span></div>
	  	</div>
		 <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post" style="margin:5% auto 21% auto;">
								<div class="form-group">
									<label for="id" class="col-sm-2 control-label">ID</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="id" val id="id" value="<?php echo $id; ?>" readonly>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Subcategory Id</p>
									</div>
								</div>
								<div class="form-group">
									<label for="sub_category_name" class="col-sm-2 control-label">Subcategory Name</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" value="<?php echo $sub_category_name ?>"  name="sub_category_name" id="sub_category_name" placeholder="Write Sub Category" val autofocus>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Subcategory Name</p>
									</div>
								</div>
								
								<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
								<div class="form-group">
									<label for="submit_update" class="col-sm-2 control-label"></label>
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