<?php 
	include("../../resources/config.php"); 

	session_destroy();
	redirect("admin_login.php");

?>