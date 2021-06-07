<?php include("../../resources/config.php"); ?>

<?php
	if(isset($_SESSION['username']))
	{
		foreach($_SESSION as $key => $value)
		{
			if($key!="theme_color" && $key!="csrf_name" && $key!="csrf_value")
				unset($_SESSION[$key]);
		}
		header("location:..".DS."signin.php");
	}
	else
		header("location:..".DS."signin.php");
?>