<?php

ob_start();
session_start();

defined("DS") ? null : define("DS",DIRECTORY_SEPARATOR);

defined("DB_HOST") ? null : define("DB_HOST","localhost");
defined("DB_USER") ? null : define("DB_USER","u668905733_uatcode");
defined("DB_PASSWORD") ? null : define("DB_PASSWORD","Dev12345");
defined("DB_NAME") ? null : define("DB_NAME","u668905733_dev");

// To Accessing End Point From Root Directory
defined("DOMAIN") ? null : define("DOMAIN","https://uatcode.com");

// For App Name
defined("APP") ? null : define("APP","UatCode");

// For App Play Store Link
defined("APPLINKONPLAYSTORE") ? null : define("APPLINKONPLAYSTORE","https://play.google.com");

// For Moving Two directory for seller Panel UI Screens
defined("SELLER_TO_ROOT") ? null : define("SELLER_TO_ROOT","..".DS."..");

defined("UPLOAD_DIRECTORY") ? null : define("UPLOAD_DIRECTORY",__DIR__.DS."..".DS);
// API Validation
defined("VALIDATION_KEY") ? null : define("VALIDATION_KEY","e16b50357d2fa3971bd0ffdd9708f9e330cef047");

//SMS GATE WAY INFORMATION
defined("KEY") ? null : define("KEY","35F7DF4C8D9D3F");
defined("SENDER") ? null : define("SENDER","BLUTSE");
defined("SMS_URL") ? null : define("SMS_URL","https://login.easywaysms.com/app/smsapi/index.php");

//PAYMENT GATEWAY INFORMATION
defined("SECREATKEY") ? null : define("SECREATKEY","e16b50357d2fa3971bd0ffdd9708f9e330cef047");
defined("APPID") ? null : define("APPID","387161a63257ae89517b9817561783");

//Email Connections
defined("SENDER_MAIL") ? null : define("SENDER_MAIL","no-reply@uatcode.com");
defined("SENDER_PASSWORD") ? null : define("SENDER_PASSWORD","Abc12345");
defined("EMAIL_HOST") ? null : define("EMAIL_HOST","smtp.hostinger.com");
defined("DOMAIN_NAME") ? null : define("DOMAIN_NAME","BLUTSE");

//GATE WAY URL
//defined("PRODUCTION") ? null : define("PRODUCTION","https://api.cashfree.com");
defined("PRODUCTION") ? null : define("PRODUCTION","https://test.cashfree.com");
$connection=new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

require_once("functions.php");
require_once('notification.php');
?>