<?php 
	require_once("../../config/config.php"); 
	require_once("../../config/".ENV."_config.php");
?>

<?php
	if(isset($_SESSION['user_id']) && isset($_SESSION['role']))
	{
		unset($_SESSION['user_id']);
		unset($_SESSION['role']);
		redirect("login.php");
	}
	else
	{
		redirect("login.php");
	}
?>