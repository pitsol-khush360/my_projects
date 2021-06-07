<?php include("../resources/config.php"); ?>
<?php include(TEMPLATE_FRONT.DS."header.php"); ?>

<?php

	if(isset($_POST['submit_otp']) && !isset($_GET['passwordchanged']))
	{
		if($_POST['otp']==$_SESSION['otp'])
		{
			if(isset($_SESSION['new_password']))
				update_password($_SESSION['new_password'],$_SESSION['ulid']);
		}
		else
		{
			setmessage("Invalid OTP");
			redirect("forgotpassword.php");
		}
	}

	if(isset($_GET['otpsent']) && !isset($_GET['passwordchanged']))
	{
?>
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<h4 class="text-white"><?php displaymessage(); ?></h4>
			<div id="login_box">
				<form method="post" >
					<h3 id="login_box_h3" align="center">Reset Password</h3>
			
					<div id="textbox">
						<i class="fa fa-envelope" id="textbox_i" aria-hidden="true"></i>
						<input type="text" id="textbox_input" placeholder="Enter OTP" name="otp">
						<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
					</div>

					<button id="btn" type="submit" name="submit_otp">Submit OTP</button>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
	}
	else
		if(isset($_GET['passwordchanged']))
		{
			unset($_SESSION['username']);
			unset($_SESSION['ulid']);
			redirect("signin.php");
		}
	else
		redirect("forgotpassword.php");
?>
<?php include(TEMPLATE_FRONT.DS."footer.php"); ?>