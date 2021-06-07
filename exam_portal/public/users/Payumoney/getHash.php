<?php
	// custom validation. if user loggedin from other device during payment then redirect to signin. 
	include("validateUserMultipleLogin.php"); 
?>
<?php

// In- Built code
// checking if ajax request came via 'post' method. generating 'hash' key and returning. in handling jquery script, we are setting hash key to the 'hash' key field.
if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') == 0)
{
	//Request hash
	$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';	
	if(strcasecmp($contentType, 'application/json') == 0){
		$data = json_decode(file_get_contents('php://input'));
		$hash=hash('sha512', $data->key.'|'.$data->txnid.'|'.$data->amount.'|'.$data->pinfo.'|'.$data->fname.'|'.$data->email.'|||||'.$data->udf5.'||||||'.$data->salt);
		$json=array();
		$json['success'] = $hash;
    	echo json_encode($json);
	
	}
	exit(0);
}

?>