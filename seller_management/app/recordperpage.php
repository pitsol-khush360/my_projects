<?php 
	require_once("../config/config.php"); 
    require_once("../config/".ENV."_config.php"); 
?>

<?php
	if(isset($_GET['pages']))
	{
		if(!isset($_SESSION['pages']))
			$_SESSION['pages']=$_GET['pages'];
		else
			$_SESSION['pages']=$_GET['pages'];
	}
?>