<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php 
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
	if(isset($_GET['id']))
	{
		$id=$_GET['id'];
		$query=query("select * from faqs where faq_id='".$id."'");
		confirm($query);
		$row=fetch_array($query);
		$q_o=$row['question'];
		$a_o=$row['answer'];

		if(isset($_POST['submit_update_faq']))
		{	
			$fid=$_POST['fid'];
			$question=$_POST['question'];
			$answer=$_POST['answer'];
			$query=query("UPDATE faqs SET question = '".$question."',answer='".$answer."' WHERE faq_id = '".$fid."'");
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
  			<div class="col-md-4 col-xs-6"><span><big><b>Update FAQ</b></big></span></div>
	  	</div>
		 <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post" style="margin:18% auto 21% auto;">
								<div class="form-group">
									<label for="fid" class="col-sm-2 control-label">FAQ ID</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="fid" val id="fid" value="<?php echo $id; ?>" readonly>
									</div>
									<div class="col-sm-2">
										<p class="help-block">FAQ Id</p>
									</div>
								</div>
								<div class="form-group">
									<label for="question" class="col-sm-2 control-label">Question</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" value="<?php echo $q_o ?>"  name="question" id="question" val autofocus required>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Question</p>
									</div>
								</div>
								<div class="form-group">
									<label for="answer" class="col-sm-2 control-label">Answer</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" value="<?php echo $a_o ?>"  name="answer" id="answer" val autofocus required>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Answer</p>
									</div>
								</div>
								<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
								<div class="form-group">
									<label for="submit_insert" class="col-sm-2 control-label"></label>
									<div class="col-sm-8 text-center">
										<input type="submit" name="submit_update_faq" class="btn btn_5 btn-lg btn-primary " id="submit_insert" value="Update FAQ">
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
		redirect("admin_faqs.php");
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