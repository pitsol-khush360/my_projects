<?php
	require("../../resources/config.php");
?>
<?php
	$admin_username=$_POST['admin_username'];
	$admin_password=$_POST['admin_password'];

	$query=query("select * from admin_login where admin_username='".$admin_username."' and admin_password='".$admin_password."'");
	confirm($query);
	if(mysqli_num_rows($query)!=0) 
	{
		$r_l=fetch_array($query);

		$_SESSION['admin_username']=$admin_username;
		$_SESSION['admin_role']=$r_l['role'];
		redirect('index.php');
	}
	else
	{
		echo "<script>alert('INVALID ACCESS')</script>";
		redirect("admin_login.php");
	}
?>