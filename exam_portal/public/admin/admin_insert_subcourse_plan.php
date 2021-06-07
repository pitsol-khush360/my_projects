<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
	if(isset($_POST['submit_insert_subcourse_plan']))
	{
		$ccid=$_POST['course_id'];
		$scid=$_POST['subcourse_id'];
		$amount=$_POST['amount'];
		$duration=$_POST['duration'];
		$plan_type=$_POST['plan_type'];

		$query=query("insert into subcourse_plans (ccid,scid,plan_type,amount,duration) value('".$ccid."','".$scid."','".$plan_type."','".$amount."','".$duration."')");
		confirm($query);
		redirect("admin_subcourse_plans.php");
	}

	$q_c=query("select * from course_category");
	confirm($q_c);

	$v="";
	$v.="<option value=\"\">-- Select Course --</option>";

	while($r_c=fetch_array($q_c))
	{
		$v.=<<< v
		<option value="{$r_c['ccid']}">{$r_c['category_name']}</option>
v;
	}
?>
<div class="graphs">
	<div class="xs">
		<div class="row">
			<div class="col-12">
				<a href="admin_subcourse_plans.php" class="btn btn-info">Back To Sub-Course Plans</a>
			</div>
		</div>
		 <div class="tab-content">
			<div class="tab-pane active" id="horizontal-form">
				<p class="text-danger text-center" style="font-size:20px;"><?php displaymessage(); ?></p>
				<form class="form-horizontal" enctype="multipart/form-data" method="post" style="margin:5% auto 5% auto;">
					<div class="form-group">
						<label for="course_id" class="col-sm-2 control-label">Course</label>
						<div class="col-sm-8">
							<select name="course_id" id="course_id" required autofocus>
								<?php echo $v; ?>
							</select>
						</div>
						<div class="col-sm-2">
							<p class="help-block">Course</p>
						</div>
					</div>
					<div class="form-group">
						<label for="subcourse_id" class="col-sm-2 control-label">Sub-Course</label>
						<div class="col-sm-8">
							<select name="subcourse_id" id="subcourse_id" required autofocus>
								
							</select>
						</div>
						<div class="col-sm-2">
							<p class="help-block">Course</p>
						</div>
					</div>
					<div class="form-group">
						<label for="plan_type" class="col-sm-2 control-label">Plan Type</label>
						<div class="col-sm-8">
							<select name="plan_type" id="plan_type" required autofocus>
								<option value="">-- Select Plan --</option>
								<option value="Single_Course">Single Course</option>
								<option value="Monthly">Monthly</option>
							</select>
						</div>
						<div class="col-sm-2">
							<p class="help-block">Plan Type</p>
						</div>
					</div>
					<div class="form-group">
						<label for="amount" class="col-sm-2 control-label">Amount</label>
						<div class="col-sm-8">
							<input type="text" class="form-control1" name="amount" id="amount" val autofocus required>
						</div>
						<div class="col-sm-2">
							<p class="help-block">Amount</p>
						</div>
					</div>
					<div class="form-group">
						<label for="duration" class="col-sm-2 control-label">Duration (In Months)</label>
						<div class="col-sm-8">
							<input type="text" class="form-control1" name="duration" id="duration" placeholder="Plan Duration (In Months)" val autofocus required pattern="[0-9]+" minlength="1" maxlength="3" title="Please Enter Only Digits">
						</div>
						<div class="col-sm-2">
							<p class="help-block">Duration (Sub-Course Payment remain valid till this duration)</p>
						</div>
					</div>
					
					<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
					<div class="form-group">
						<label for="submit_insert" class="col-sm-2 control-label"></label>
						<div class="col-sm-8 text-center">
							<input type="submit" name="submit_insert_subcourse_plan" class="btn btn_5 btn-lg btn-primary " id="submit_insert" value="Add Plan">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$("#course_id").on("click",function(){

		ccid=$(this).val();
		$.get("ajax_get_subcategories.php?ccid="+ccid,function(data)
			{
				$("#subcourse_id").html(data);
			});
	});
</script>

<?php
}
else
echo '<div class="bs-example4" data-example-id="contextual-table"><div class="row">
        <div class="col-12 text-center text-danger">
          <h4 style="margin-top:5em;">You Don\'t Have Permission To Access This Page</h4>
        </div>
      </div></div>';
?>

<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 