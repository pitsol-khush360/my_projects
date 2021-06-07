<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
	if(isset($_GET['ulid']) && isset($_GET['upid'])) 
	{
		$ulid=$_GET['ulid'];
		$upid=$_GET['upid'];

		$query=query("SELECT ul.*, up.* FROM user_login ul 
                  LEFT JOIN user_personal up ON ul.userid = up.ulid 
                  WHERE ul.userid='".$ulid."'");
      	confirm($query);
		$row=fetch_array($query);
		
		if(isset($_POST['submit_update_users']) && isset($_POST['userid']))
		{
			$userid=$_POST['userid'];
			$upid=$_POST['upid'];
			$name=trim($_POST['name']);
			$email=$_POST['email'];
			$mobile=$_POST['mobile'];
			$country=trim($_POST['country']);
			$state=trim($_POST['state']);
			$district=trim($_POST['district']);
			$username=trim($_POST['username']);
			$password=trim($_POST['password']);

			$q_check=query("SELECT ul.username, up.mobile FROM user_login ul
							LEFT JOIN user_personal up ON ul.userid=up.ulid
							WHERE (ul.username='".$username."' OR up.mobile='".$mobile."') AND ul.userid <> '".$userid."'");
			confirm($q_check);

			if(mysqli_num_rows($q_check)==0)
			{
				// $query=query("UPDATE user_personal SET name = '".$name."',email = '".$email."',mobile = '".$mobile."', country = '".$country."',state = '".$state."', district = '".$district."' WHERE upid = ".$upid.";");
				// confirm($query); 

				$query=query("UPDATE 
								user_login ul
							LEFT JOIN 
								user_personal up ON ul.userid = up.ulid
							SET  
								ul.username = '".$username."', ul.password='".$password."', 
								up.name = '".$name."', up.email = '".$email."', up.mobile = '".$mobile."',
								up.country = '".$country."', up.state = '".$state."', up.district = '".$district."'
							WHERE   
								ul.userid='".$userid."'");
				confirm($query);
			}
			else
			{
				echo '<script>alert("Either username or mobile already exist!")</script>';
			}
			redirect("admin_users.php?users");
		}
?>
<div class="graphs">
	<div class="xs">
		<div class="row">
			<div class="col-12">
				<a href="admin_users.php?users" class="btn btn-info">Back To Users</a>
			</div>
		</div>
		<div class="row text-center">
  			<div class="col-md-12"><span><big><b>Update User</b></big></span></div>
	  	</div>
		 <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post">
								<div class="form-group">
									<label for="username" class="col-sm-2 control-label">Username</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="username" id="username" value="<?php echo $row['username']; ?>" autofocus required>
									</div>
									<div class="col-sm-2">
										<p class="help-block">name of the user</p>
									</div>
								</div>
								<div class="form-group">
									<label for="password" class="col-sm-2 control-label">Password</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="password" id="password" value="<?php echo $row['password']; ?>" autofocus required minlength="6" maxlength="15">
									</div>
									<div class="col-sm-2">
										<p class="help-block">name of the user</p>
									</div>
								</div> 
								<div class="form-group">
									<label for="name" class="col-sm-2 control-label">Name</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="name" id="name" value="<?php echo $row['name']; ?>" autofocus required>
									</div>
									<div class="col-sm-2">
										<p class="help-block">name of the user</p>
									</div>
								</div>
								<div class="form-group">
									<label for="email" class="col-sm-2 control-label">Email</label>
									<div class="col-sm-8">
										<input type="email" class="form-control1" name="email" id="email" value="<?php echo $row['email']; ?>">
									</div>
									<div class="col-sm-2">
										<p class="help-block">Email of the user</p>
									</div>
								</div>
								<div class="form-group">
									<label for="mobile" class="col-sm-2 control-label">Mobile</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="mobile" id="mobile" value="<?php echo $row['mobile']; ?>" minlength="10" maxlength="10" pattern="[5-9]{1}[0-9]{9}" required title="Enter Correct Mobile Number Format">
									</div>
									<div class="col-sm-2">
										<p class="help-block">mobile number of the user</p>
									</div>
								</div>
								<div class="form-group">
									<label for="country" class="col-sm-2 control-label">Country</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="country" id="country" value="<?php echo $row['country']; ?>">
									</div>
									<div class="col-sm-2">
										<p class="help-block">Country of the user</p>
									</div>
								</div>
								<div class="form-group">
									<label for="state" class="col-sm-2 control-label">State</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="state" id="state" value="<?php echo $row['state']; ?>">
									</div>
									<div class="col-sm-2">
										<p class="help-block">State of the user</p>
									</div>
								</div>
								<div class="form-group">
									<label for="district" class="col-sm-2 control-label">District</label>
									<div class="col-sm-8">
										<input type="text" value="<?php echo $row['district']; ?>" class="form-control1" name="district" id="district">
									</div>
									<div class="col-sm-2">
										<p class="help-block">District of the user</p>
									</div>
								</div>
								<input type="hidden" name="userid" value="<?php echo $ulid; ?>">
								<input type="hidden" name="upid" value="<?php echo $upid; ?>">
								<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
								<div class="form-group">
									<div class="col-sm-12 text-center">
										<input type="submit" name="submit_update_users" class="btn btn_5 btn-lg btn-primary " value="Update User">
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
		redirect("admin_users.php");
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