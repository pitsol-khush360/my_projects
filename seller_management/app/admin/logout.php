 
<?php 
	require_once("../../config/config.php"); 
	require_once("../../config/".ENV."_config.php");
?>

<?php
	if(isset($_SESSION['current_user']))
	{
		unset($_SESSION['current_user']);
		redirect("login.php");
	}
	else
	{
		redirect("login.php");
	}
	session_destroy();
?>