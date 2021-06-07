<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php 	
		if(isset($_POST['submit_insert']))
		{
			$name=$_POST['admin_name'];
			$user=$_POST['admin_username'];
			$password=$_POST['admin_password'];
			
			$query=query("insert into admin_login(admin_name,admin_username,admin_password,role) value('".$name."','".$user."','".$password."','2')");
			confirm($query);
			redirect("admin_manage_admin.php");
		}
?>
<div class="graphs">
	<div class="xs">
		<div class="row">
			<div class="col-12">
				<a href="admin_manage_admin.php" class="btn btn-info btn-sm">Back To Admins</a>
			</div>
		</div>
		 <div class="tab-content">
			<div class="tab-pane active" id="horizontal-form">
				<form class="form-horizontal" method="post" style="margin:18% auto 21% auto;">
					<div class="form-group">
						<label for="admin_name" class="col-sm-2 control-label">Name</label>
						<div class="col-sm-8">
							<input type="text" class="form-control1" name="admin_name" id="admin_name" placeholder="Admin Name" autofocus required pattern="[A-Za-z]+" maxlength="50" title="Only Enter Name Upto 50 chars..">
						</div>
						<div class="col-sm-2">
							<p class="help-block">Name Of Admin</p>
						</div>
					</div>
					<div class="form-group">
						<label for="admin_username" class="col-sm-2 control-label">Username</label>
						<div class="col-sm-8">
							<input type="text" class="form-control1" name="admin_username" id="admin_username" placeholder="Admin Username" autofocus required pattern="[A-Za-z0-9]+" minlength="3" maxlength="20" title="Only Enter Username Between 3 to 20 chars without any special character..">
						</div>
						<div class="col-sm-2">
							<p class="help-block">Username Of Admin Login</p>
						</div>
					</div>
					<div class="form-group">
						<label for="admin_password" class="col-sm-2 control-label">Password</label>
						<div class="col-sm-8">
							<input type="text" class="form-control1" name="admin_password" id="admin_password" placeholder="Admin Password" autofocus required minlength="6" maxlength="15" title="Only Enter Password Between 6 to 15 chars..">
						</div>
						<div class="col-sm-2">
							<p class="help-block">Password For Admin Login</p>
						</div>
					</div>
					<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
					<div class="form-group">
						<label for="submit_insert" class="col-sm-2 control-label"></label>
						<div class="col-sm-8 text-center">
							<input type="submit" name="submit_insert" class="btn btn_5 btn-lg btn-primary " id="submit_insert" value="Create Admin">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 