<?php include("../resources/config.php"); ?>
<?php 
	$title="Change Password";
	include(TEMPLATE_FRONT.DS."header.php"); 
?>

<div class="container">
	<div class="row">
	<div class="col-md-2 col-xs-2"></div>
	<div class="col-md-8 col-xs-8">
	<?php send_otp(); ?> 
	<h4 class="text-danger"><?php displaymessage(); ?></h4>
	<div id="login_box" style="margin-top:5rem;">
		<form method="post">
			<h3 id="login_box_h3" align="center">Reset Password</h3>
			
			<div class="textbox">
				<i class="fa fa-envelope" class="textbox_i" aria-hidden="true"></i>
				<input type="email" class="textbox_input" placeholder="Enter Email" name="email">
			</div>

			<h4 style="text-align:center;">OR</h4>

			<div class="textbox">
				<i class="fa fa-tablet" class="textbox_i" aria-hidden="true"></i>
				<input type="text" class="textbox_input" placeholder="10 Digit Mobile Number" name="mobile" pattern="[5-9]{1}[0-9]{9}">
			</div>

			<div class="textbox">
				<i class="fa fa-lock" class="textbox_i" aria-hidden="true"></i>
				<input type="password" class="textbox_input" placeholder="New Password" name="new_password" required minlength="6" maxlength="15">
				<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
			</div>

			<div class="textbox">
				<i class="fa fa-lock" class="textbox_i" aria-hidden="true"></i>
				<input type="password" class="textbox_input" placeholder="Confirm Password" name="confirm_password" required minlength="6" maxlength="15">
			</div>

			<input id="btn" type="submit" name="reset_password" value="Reset">
			<!-- <button type="submit" class="btn btn-danger" name="reset_password">Reset</button> -->
		</form>
	</div>
	</div>
	</div>
</div>

<?php include(TEMPLATE_FRONT.DS."footer.php"); ?>