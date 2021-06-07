<?php
	session_start();
	if(isset($_GET['value']))
	{
		//echo $_GET['value'];
		$_SESSION['theme_color']=$_GET['value'];
	}
	else
		echo "You Don't Have Permission To Access This Page";
?>