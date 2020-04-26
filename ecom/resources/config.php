<?php
// Output Buffering
ob_start();
//starts session
session_start();

foreach ($_POST as $key => $value) {
	$_POST[$key]=htmlentities($_POST[$key]);
	$_POST[$key]=addslashes($_POST[$key]);
}
foreach ($_GET as $key => $value) {
	$_GET[$key]=htmlentities($_GET[$key]);
	$_GET[$key]=addslashes($_GET[$key]);
}

defined("DS") ? null : define("DS",DIRECTORY_SEPARATOR);

defined("TEMPLATE_FRONT") ? null : define("TEMPLATE_FRONT",__DIR__.DS."templates".DS."front");
defined("TEMPLATE_BACK") ? null : define("TEMPLATE_BACK",__DIR__.DS."templates".DS."back");
defined("TEMPLATE_USERS") ? null : define("TEMPLATE_USERS",__DIR__.DS."templates".DS."users");

defined("DB_HOST") ? null : define("DB_HOST","localhost");
defined("DB_USER") ? null : define("DB_USER","root");
defined("DB_PASSWORD") ? null : define("DB_PASSWORD","");
defined("DB_NAME") ? null : define("DB_NAME","ecom_db");

defined("UPLOAD_DIRECTORY_PRODUCTS") ? null : define("UPLOAD_DIRECTORY_PRODUCTS",__DIR__.DS."uploads".DS);
defined("UPLOAD_DIRECTORY_PROFILE") ? null : define("UPLOAD_DIRECTORY_PROFILE",__DIR__.DS."profile_upload".DS);
defined("SLIDER_DIRECTORY") ? null : define("SLIDER_DIRECTORY",__DIR__.DS."sliders".DS);

$connection=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
require_once("functions.php");
require_once("cart.php");
?>
