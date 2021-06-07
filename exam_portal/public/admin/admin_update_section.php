<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
	if(isset($_GET['tid'])&&isset($_GET['seid'])&& isset($_GET['ccid']) && isset($_GET['scid']) && isset($_GET['sec_cnt'])) 
	{
		$tid=$_GET['tid'];
		$seid=$_GET['seid'];
		$ccid=$_GET['ccid'];
		$scid=$_GET['scid'];
		$sec_cnt=$_GET['sec_cnt'];

		$query=query("select * from section where seid='".$seid."'");
		confirm($query);
		$row=fetch_array($query);
		
		if(isset($_POST['submit_update_section']))
		{

			$section_name=$_POST['section_name'];
			$section_question_no=$_POST['section_question_no'];
			$section_timing=$_POST['section_timing'];
			$pm=$_POST['pm'];
			$nm=$_POST['nm'];
			$mm=$_POST['mm'];
			$query=query("UPDATE section SET tid = '".$tid."',  section_name = '".$section_name."',section_question_no = '".$section_question_no."',section_timing = '".$section_timing."', pm = '".$pm."',nm = '".$nm."', mm = '".$mm."' WHERE seid = ".$seid.";");
			confirm($query); 
			redirect("admin_section.php?ccid=".$ccid."&scid=".$scid."&tid=".$tid."&sec_cnt=".$sec_cnt);
		}
?>
<div class="graphs">
	<div class="xs">
		<div class="row">
			<div class="col-12">
				<a href="admin_section.php?ccid=<?php echo $ccid; ?>&scid=<?php echo $scid; ?>&tid=<?php echo $tid; ?>&sec_cnt=<?php echo $sec_cnt; ?>" class="btn btn-info btn-sm">Back To Sub-Category Sections</a>
			</div>
		</div>
		<div class="row text-center">
  			<div class="col-md-12"><span><big><b>Update Section</b></big></span></div>
	  	</div>
		 <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post" > 
								<div class="form-group">
									<label for="section_name" class="col-sm-2 control-label">Name of Section  </label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="section_name" id="section_name" placeholder="Write Section Name" value="<?php echo $row['section_name']; ?>" required autofocus>
									</div>
									<div class="col-sm-2">
										<p class="help-block">name of the section</p>
									</div>
								</div>
								<div class="form-group">
									<label for="section_question_no" class="col-sm-2 control-label">Number of Question in Section</label>
									<div class="col-sm-8">
										<input type="number" class="form-control1" name="section_question_no" id="section_question_no" value="<?php echo $row['section_question_no']; ?>" placeholder="Write Number of Question in Section" required>
									</div>
									<div class="col-sm-2">
										<p class="help-block">no. of question in this section</p>
									</div>
								</div>
								<div class="form-group">
									<label for="section_timing" class="col-sm-2 control-label">Section Timing(in minute)</label>
									<div class="col-sm-8">
										<input type="number" class="form-control1" name="section_timing" id="section_timing" value="<?php echo $row['section_timing']; ?>" placeholder="Write Timing of Section (in minute)" required >
									</div>
									<div class="col-sm-2">
										<p class="help-block">Timing of the section</p>
									</div>
								</div>
								<div class="form-group">
									<label for="pm" class="col-sm-2 control-label">Positive Mark per Question</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="pm" id="pm" value="<?php echo $row['pm']; ?>" placeholder="Write Positive Mark per Question" required >
									</div>
									<div class="col-sm-2">
										<p class="help-block">+ev mark per question</p>
									</div>
								</div>
								<div class="form-group">
									<label for="nm" class="col-sm-2 control-label">Negative Mark per Question</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="nm" id="nm" value="<?php echo $row['nm']; ?>" placeholder="Write Negative Mark per Question" required >
									</div>
									<div class="col-sm-2">
										<p class="help-block">-ev mark per question</p>
									</div>
								</div>
								<div class="form-group">
									<label for="mm" class="col-sm-2 control-label">Maximum Mark of section</label>
									<div class="col-sm-8">
										<input type="text" value="<?php echo $row['mm']; ?>" class="form-control1" name="mm" id="mm" placeholder="Write Maximum Mark of section" required >
									</div>
									<div class="col-sm-2">
										<p class="help-block">Maximun mark of section</p>
									</div>
								</div>
								<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
								<div class="form-group">
									<div class="col-sm-12 text-center">
										<input type="submit" name="submit_update_section" class="btn btn_5 btn-lg btn-primary " value="Add Section">
									</div>
								</div>
							</form>
						</div>
					</div>
	</div>
</div>
<script type="text/javascript">
		 $("#pm").keyup(function()
		 {
		 	$("#mm").val($("#section_question_no").val()*$("#pm").val());		
		 });
</script>
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