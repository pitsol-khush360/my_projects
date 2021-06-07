<?php 
	session_start();
	ob_start();

	if(!isset($_SESSION['theme_color']))
		$_SESSION['theme_color']="#ff9933";

	// if(isset($_COOKIE['PHPSESSID']))
	// {
	// 	echo $_SESSION['sessid']," ",$_COOKIE['PHPSESSID'];
	// }
	// if(!isset($_SESSION['sessid']))
	// {
	// 	$_SESSION['sessid']=$_COOKIE['PHPSESSID'];
	// }
	// else if($_SESSION['sessid']!=$_COOKIE['PHPSESSID'])
	// {
	// 	exit;
	// }

foreach ($_POST as $key => $value) 
{
	if(gettype($_POST[$key])!="array")
	{
		$_POST[$key]=htmlentities($_POST[$key]);
		$_POST[$key]=addslashes($_POST[$key]);
	}
	else 						// if array
	{	
		foreach ($_POST[$key] as $k => $v) 
		{
			if(is_array($_POST[$key][$k]))
			{
				foreach ($v as $s => $t)
				{
					$_POST[$key][$k][$s]=htmlentities($_POST[$key][$k][$s]);
					$_POST[$key][$k][$s]=addslashes($_POST[$key][$k][$s]);
				}
			}
			else
			{
				foreach ($_POST[$key] as $k => $v) 
				{
					$_POST[$key][$k]=htmlentities($_POST[$key][$k]);
					$_POST[$key][$k]=addslashes($_POST[$key][$k]);
				}
			}
		}
	}
}

foreach ($_GET as $key => $value) 
{
	if(gettype($_GET[$key])!="array")
	{
		$_GET[$key]=htmlentities($_GET[$key]);
		$_GET[$key]=addslashes($_GET[$key]);
	}
	else
	{	
		foreach ($_GET[$key] as $k => $v) 
		{
			$_GET[$key][$k]=htmlentities($_GET[$key][$k]);
			$_GET[$key][$k]=addslashes($_GET[$key][$k]);
		}
	}
}


	defined("DS") ? null : define("DS",DIRECTORY_SEPARATOR);

	defined("TEMPLATE_FRONT") ? null : define("TEMPLATE_FRONT",__DIR__.DS."front");

	defined("TEMPLATE_BACK") ? null : define("TEMPLATE_BACK",__DIR__.DS."back");

	defined("TEMPLATE_USERS") ? null : define("TEMPLATE_USERS",__DIR__.DS."users");

	defined("USERPROFILE_UPLOAD") ? null : define("USERPROFILE_UPLOAD",__DIR__.DS."userprofile_upload");

	defined("USEROFFER_UPLOAD") ? null : define("USEROFFER_UPLOAD",__DIR__.DS."useroffer_upload");

	defined("ADMINQUESTION_UPLOAD") ? null : define("ADMINQUESTION_UPLOAD",__DIR__.DS."adminquestion_upload");
    
	// Merchant Key
	defined("KEY") ? null : define("KEY","1llBOlBG");
	// Merchant Salt
	defined("SALT") ? null : define("SALT","SFHYwMm1Pc");
	
	defined("DB_HOST") ? null : define("DB_HOST","localhost");
	//defined("DB_USER") ? null : define("DB_USER","abhyasclasses_td");
	defined("DB_USER") ? null : define("DB_USER","root");
	//defined("DB_PASSWORD") ? null : define("DB_PASSWORD","aBhcLSs360");
	defined("DB_PASSWORD") ? null : define("DB_PASSWORD","");
	//defined("DB_NAME") ? null : define("DB_NAME","abhyasclasses_td");
	defined("DB_NAME") ? null : define("DB_NAME","test_desk");

	defined("DOMAIN") ? null : define("DOMAIN","http://localhost/exam_portal");
	defined("APP") ? null : define("APP","AbhyasClasses");

	$connection=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	require_once("functions.php");
?>