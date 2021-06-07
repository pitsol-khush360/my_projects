<?php
$key='';

if(isset($_REQUEST['key']))
	$key=$_REQUEST['key'];
	//print_r(apache_request_headers() );
$secreatKey = 'e16b50357d2fa3971bd0ffdd9708f9e330cef047';
$secreatKey = md5($secreatKey);
if($key!=$secreatKey && $key!='')
{
	$temp=array();
	$temp['response_code']=400;
	$temp['response_desc']="Invalid Request";
	echo json_encode(array("validation"=>$temp));
	close();
	exit();
}

?>