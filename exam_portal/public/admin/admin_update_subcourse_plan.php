<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php 	
if(isset($_GET['scpid']))
{
	$cpid=$_GET['scpid'];

	if(isset($_POST['submit_update_subcourse_plan']))
	{
		$id=$_POST['cpid'];
		$ccid=$_POST['course_id'];
		$scid=$_POST['subcourse_id'];
		$amount=$_POST['amount'];
		$duration=$_POST['duration'];
		$plan_type=$_POST['plan_type'];

		$query=query("update subcourse_plans set ccid='".$ccid."', scid='".$scid."', plan_type='".$plan_type."', amount='".$amount."', duration='".$duration."' where id='".$id."'");
		confirm($query);
		redirect("admin_subcourse_plans.php");
	}

	$q_cp=query("select * from subcourse_plans where id='".$cpid."'");
	confirm($q_cp);

	if(mysqli_num_rows($q_cp)!=0)
	{
		$r_cp=fetch_array($q_cp);

		$ccid=$r_cp['ccid'];
		$scid=$r_cp['scid'];
		$plan_type=$r_cp['plan_type'];
		$amount=$r_cp['amount'];
		$duration=$r_cp['duration'];

		$q_cc=query("select * from course_category");
		confirm($q_cc);

		$cv="";

		while($r_cc=fetch_array($q_cc))
		{
			if($r_cc['ccid']==$ccid)
			{
				$cv.=<<< cv
				<option value="{$r_cc['ccid']}" selected>{$r_cc['category_name']}</option>
cv;
			}
			else
			{
				$cv.=<<< cv
				<option value="{$r_cc['ccid']}">{$r_cc['category_name']}</option>
cv;
			}
		}

		$q_sc=query("select * from sub_category where ccid='".$ccid."'");
		confirm($q_sc);

		$sv="";

		while($r_sc=fetch_array($q_sc))
		{
			if($r_sc['scid']==$scid)
			{
				$sv.=<<< sv
				<option value="{$r_sc['scid']}" selected>{$r_sc['sub_category_name']}</option>
sv;
			}
			else
			{
				$sv.=<<< sv
				<option value="{$r_sc['scid']}">{$r_sc['sub_category_name']}</option>
sv;
			}
		}
?>
<div class="graphs">
	<div class="xs">
		<div class="row">
			<div class="col-12">
				<a href="admin_subcourse_plans.php?ccid=<?php echo $ccid; ?>" class="btn btn-info">Back To Sub-Course Plans</a>
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
								<?php echo $cv; ?>
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
								<?php echo $sv; ?>
							</select>
						</div>
						<div class="col-sm-2">
							<p class="help-block">Sub-Course</p>
						</div>
					</div>
					<div class="form-group">
						<label for="plan_type" class="col-sm-2 control-label">Plan Type</label>
						<div class="col-sm-8">
							<select name="plan_type" id="plan_type" required autofocus>
								<?php
									if($plan_type=="Single_Course")
										echo '<option value="Single_Course" selected>Single Course</option><option value="Monthly">Monthly</option>';
									else
									if($plan_type=="Monthly")
										echo '<option value="Monthly" selected>Monthly</option><option value="Single_Course">Single Course</option>';
								?>
							</select>
						</div>
						<div class="col-sm-2">
							<p class="help-block">Plan Type</p>
						</div>
					</div>
					<div class="form-group">
						<label for="amount" class="col-sm-2 control-label">Amount</label>
						<div class="col-sm-8">
							<input type="text" class="form-control1" name="amount" id="amount" value="<?php echo $amount; ?>" val autofocus required>
						</div>
						<div class="col-sm-2">
							<p class="help-block">Amount</p>
						</div>
					</div>
					<div class="form-group">
						<label for="duration" class="col-sm-2 control-label">Duration (In Months)</label>
						<div class="col-sm-8">
							<input type="text" class="form-control1" name="duration" id="duration" value="<?php echo $duration; ?>" val autofocus required pattern="[0-9]+" minlength="1" maxlength="3" title="Please Enter Only Digits">
						</div>
						<div class="col-sm-2">
							<p class="help-block">Duration (Course Payment remain valid till this duration)</p>
						</div>
					</div>
					<input type="hidden" name="cpid" value="<?php echo $cpid; ?>">
					<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
					<div class="form-group">
						<label for="submit_insert" class="col-sm-2 control-label"></label>
						<div class="col-sm-8 text-center">
							<input type="submit" name="submit_update_subcourse_plan" class="btn btn_5 btn-lg btn-primary " id="submit_insert" value="Update Plan">
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
		echo '<div class="row">
				<div class="col-12">
					<a href="admin_subcourse_plans.php" class="btn btn-info" style="margin-top:1rem;margin-left:3em;">Back To Sub-Course Plans</a>
				</div>
			</div>
			<div class="row mt-5">
				<div class="col-12 text-center text-danger">
					<h4 style="margin-top:2rem;">Sub-Course Plan Not Found</h4>
				</div>
			</div>';
}
else
	redirect("admin_subcourse_plans.php");
?>

<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 