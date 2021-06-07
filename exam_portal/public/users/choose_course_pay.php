<?php include("validateUserMultipleLogin.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Select Payment Type</title>

	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />

    <script src="js/jquery.min.js"></script>

</head>
<body>

<?php 
	if(isset($_GET['select_payment_type']) && isset($_SESSION['username']) && isset($_SESSION['ulid']))
	{
		// if user just back to this page without making payment
		if(isset($_SESSION['payumoney_plan_type']))
			unset($_SESSION['payumoney_plan_type']);

		if(isset($_SESSION['payumoney_courseid']))
			unset($_SESSION['payumoney_courseid']);

		if(isset($_SESSION['payumoney_subcourseid']))
			unset($_SESSION['payumoney_subcourseid']);
?>

<div class="container">
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-12 col-md-6 text-center" style="margin-top:100px;">
		  <form action="Payumoney/index.php" method="post" id="choose_course_form">
		  	<div class="row">
		  		<div class="col-md-12 col-xs-12 text-center">
		  			<h3>Select Payment Type</h3>
		  		</div>
		  	</div>
			<div class="row" style="margin-top:20px;">
				<div class="col-12 col-md-6">
					<input type="radio" name="plan_type" value="Single_Course" id="course_plan" required>&nbsp;<label for="course_plan">Select Complete Course</label>
				</div>
				<div class="col-12 col-md-4">
					<input type="radio" name="plan_type" value="Monthly" id="monthly_plan" required>&nbsp;<label for="monthly_plan">Select Monthly Plan</label>
				</div>
				<!-- <div class="col-md-4 col-xs-4">
					<input type="radio" name="payment_type" value="course_wise" id="course_wise" required>&nbsp;<label for="course_wise">Select Yearly Plan</label>
				</div> -->
			</div>
			<div class="row" style="margin-top:20px;">
				<div class="col-12" style="margin-top:1em;">
					<span class="text"><label for="sco">Select Course</label></span>
				    <select id="sco" name="subcourse" required>
				    	<?php 
				    		$coptions="";
				    		$coptions.="<option value=\"\" ccid=\"\">-- Select Course --</option>";

				    		$q_cc=query("select * from sub_category");
							confirm($q_cc);

							while($r_cc=fetch_array($q_cc))
							{
								$coptions.="<option value=\"{$r_cc['scid']}\" ccid=\"{$r_cc['ccid']}\">{$r_cc['sub_category_name']}</option>";
							}
							echo $coptions;
				    	?>	
				    </select>
				</div>
				
				<input type="hidden" name="course" value="" id="course">

				<div class="col-12" style="margin-top:1em;">
					<span class="text"><label for="amount">Amount</label></span>
					<span><input type="text" id="amount" name="amount" value="0" readonly required/></span>
				</div> 
			</div>

			<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">

			<div class="row" style="margin-top:20px;">
				<div class="col-md-12 col-xs-12">
					<button type="submit" class="btn btn-success" name="submit_payment_details">Proceed</button>
					<button type="reset" class="btn btn-danger">Reset</button>
				</div>
			</div>
		  </form>
		</div>
		<div class="col-md-3"></div>
	</div>
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-12 col-md-6" style="margin-top:100px;">
			<a href="index.php?home" class="btn btn-primary">Back To User Panel</a>
		</div>
	</div>
</div>

<?php
	}
	else
	{
		echo "<p class='text-danger text-center' style='font-size:25px;'>Access Denied</p>";
	}
?>

<script type="text/javascript">
	$(document).ready(function(){

		$("#sco").on("click",function(){

			ccid=$('option:selected',this).attr("ccid");
			scid=$(this).val();

			$("#course").val(ccid);

			plan=$("input[name='plan_type']:checked").val();

			$.get("choose_amount_pay.php?ccid="+ccid+"&scid="+scid+"&plan="+plan,function(data)
				{
					$("#amount").val(data);
				});
		});

		// $("#sco").on("click",function(){

		// 	scid=$(this).val();
		// 	ccid=$("#co").val();
		// 	plan=$("input[name='plan_type']:checked").val();

		// 	if(ccid!="")
		// 	{
		// 		$.get("choose_amount_pay.php?ccid="+ccid+"&scid="+scid+"&plan="+plan,function(data)
		// 			{
		// 				$("#amount").val(data);
		// 			});
		// 	}
		// });

		$("input[name='plan_type']").on("click",
			function(){

			ccid=$('option:selected','#sco').attr("ccid");
			scid=$("#sco").val();

			$("#course").val(ccid);

			plan=$("input[name='plan_type']:checked").val();

			$.get("choose_amount_pay.php?ccid="+ccid+"&scid="+scid+"&plan="+plan,function(data)
				{
					$("#amount").val(data);
				});
		});
	});
</script>
</body>
</html>