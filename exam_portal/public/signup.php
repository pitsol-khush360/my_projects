<?php include("../resources/config.php"); ?>
<?php 
  $title="Register";
  include(TEMPLATE_FRONT.DS."header.php"); 
?>

<?php
	if(isset($_POST['signup']))
		user_signup();
?>

<div class="container">
	<div class="row">
		<div class="offset-lg-3 offset-md-3 col-lg-6 offset-md-3 col-md-6 col-xs-12" style="margin-top:5rem;">
			<h4 style="margin-top:10px;"><?php displaymessage(); ?></h4>
			<div id="login_box">
			<p class="text-danger" style="font-size:20px;"><?php displaymessage(); ?></p>
			<form class="form" method="post">
			<h3 id="login_box_h3">Register</h3>
			<div class="textbox">
				<i class="fa fa-user" class="textbox_i" aria-hidden="true"></i>
				<input type="text" class="textbox_input" placeholder="Username" name="username" required>
			</div>

			<div class="textbox">
				<i class="fa fa-lock" class="textbox_i" aria-hidden="true"></i>
				<input type="password" class="textbox_input" placeholder="Password" name="password" required minlength="6" maxlength="15">
				<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
			</div>

			<div class="textbox">
				<i class="fa fa-mobile" class="textbox_i" aria-hidden="true"></i>
				<input type="text" class="textbox_input" placeholder="Mobile" name="mobile" pattern="[5-9]{1}[0-9]{9}">
			</div>

			<button id="btn" type="submit" name="signup">Register</button>
			</form>
			<p>Already Have An Account?&nbsp;<a href="signin.php" style="color:<?php echo $_SESSION['theme_color']; ?>;font-size:20px;text-decoration:none;">Login</a></p>
			</div>
		</div> 
	</div>
</div>

<?php include(TEMPLATE_FRONT.DS."footer.php"); ?>