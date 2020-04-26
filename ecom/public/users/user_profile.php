<?php require_once("../../resources/config.php");  ?>
<?php include(TEMPLATE_USERS.DS."header.php");  ?>

<?php 
		if(isset($_GET['name']))
		{
			$query=query("select * from users where username like '".escape_string($_GET['name'])."'");
			confirm($query);
			$row=fetch_array($query);
			$img_path=image_path_profile($row['profile_picture']);
?>

<div class="container">
	<div class="row">
		<div class="col-lg-2">
			<div style="background-color:grey;border-radius:50%;height:150px;width:150px;margin-top:100px;"><img src="../../resources/<?php echo $img_path; ?>" style="width:140px;height:140px;border-radius:50%;margin-left:5px;margin-top:5px;">
			</div>
		</div>
		<div class="col-lg-3">
				<div class="pull-left">
					<h3><i><b> Username</b></i></h3>
					<h5><?php echo $row['username']; ?><h5>
					<h3><i><b> Password</b></i></h3>
					<h5><?php echo $row['password']; ?><h5>
					<h3><i><b> Firstname</b></i></h3>
					<h5><?php echo $row['firstname']; ?><h5>
					<h3><i><b> Lastname</b></i></h3>
					<h5><?php echo $row['lastname']; ?><h5>
					<h3><i><b> Email</b></i></h3>
					<h5><?php echo $row['email']; ?><h5>
					<h3><i><b> Address</b></i></h3>
					<h5><?php echo $row['address']; ?><h5>
					<h3><i><b> Mobile Number</b></i></h3>
					<h5><?php echo $row['mobile_number']; ?><h5>

			<a href="../signup_complete.php?name=<?php echo $row['username']; ?>"><button type="submit" name="update_user" class="btn pull-right" style="background-color:orange;color:white;">Update Account</button></a>

				</div>
		</div>
	</div>
</div>
<?php
		}
		else
		{
			echo "<h3><p class='text-danger text-center'>Can't Access</p></h3>";
		}
?>

<?php include(TEMPLATE_USERS.DS."footer.php");  ?>