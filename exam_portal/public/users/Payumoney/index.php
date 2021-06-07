<?php include("validateUserMultipleLogin.php"); ?>

<?php 
if(isset($_POST['submit_payment_details']) && isset($_SESSION['username']) && isset($_SESSION['ulid']) && isset($_POST['amount']) && $_POST['amount']!=0 && isset($_POST['course']) && $_POST['course']!="")
{
	//$course="";
	$subcourse="";

	$subcourseid="";

	$courseid=$_POST['course'];

	$plan_type=$_POST['plan_type'];
	$amount=$_POST['amount'];

	// $q_c=query("select category_name from course_category where ccid='".$courseid."'");
	// 	confirm($q_c);

	// if(mysqli_num_rows($q_c)!=0)
	// {
	// 	$r_c=fetch_array($q_c);
	// 	$course=$r_c['category_name'];
	// }

	$_SESSION['payumoney_plan_type']=$plan_type;
	$_SESSION['payumoney_courseid']=$courseid;

	//$mode=$_POST['mode'];

	//$_SESSION['payumoney_mode']=$mode;

	$subcourseid=$_POST['subcourse'];

	$q_sc=query("select sub_category_name from sub_category where scid='".$subcourseid."'");
	confirm($q_sc);

	if(mysqli_num_rows($q_sc)!=0)
	{
		$r_sc=fetch_array($q_sc);
		$subcourse=$r_sc['sub_category_name'];
	}

	$_SESSION['payumoney_subcourseid']=$subcourseid;

	$valid=1;

	$query_check=query("select * from user_payments where ulid='".$_SESSION['ulid']."' and ccid='".$courseid."' and scid='".$subcourseid."' and plan_type='".$plan_type."' and payment_status='SUCCESS' order by id desc limit 0,1");

	confirm($query_check);

	if(mysqli_num_rows($query_check)!=0)
	{
		$row_check=fetch_array($query_check);

		$c_ptime=$row_check['payment_time'];
		$c_plan_type=$row_check['plan_type'];
		$c_ccid=$row_check['ccid'];
		$c_scid=$row_check['scid'];
			
		if($c_scid!="")
		{
			$q_get_due=query("select duration from subcourse_plans where ccid='".$c_ccid."' and scid='".$c_scid."' and plan_type='".$c_plan_type."'");
			confirm($q_get_due);

			if(mysqli_num_rows($q_get_due)!=0)
			{
				$r_get_due=fetch_array($q_get_due);
				$duration=$r_get_due['duration'];

				$current_date=date("Y-m-d H:i:s");
				$passed_month=0;

				$c_ptime=strtotime($c_ptime);
				$current_date=strtotime($current_date);
				$min_date=min($c_ptime, $current_date);
				$max_date=max($c_ptime, $current_date);

				// checking how many months have been passed after payment
				$passed_month=0;
				while (($min_date=strtotime("+1 MONTH",$min_date))<=$max_date) 
				{
					$passed_month++;
				}

				if($passed_month>=$duration)
					$valid=1;
				else
					$valid=0;
			}
		}
	}

	if($valid==1)
	{
?>

<?php
 
// Returning on 'response.php' after payment. or can set any other url where we want to redirect after payment.
// on both success/failure returning to response.php
function getCallbackUrl()
{
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	//return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . 'response.php';
	return $protocol . 'localhost/exam_portal/public/users/Payumoney/response.php';
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo APP; ?> - Your Payment Details</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<!-- this meta viewport is required for BOLT //-->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" >

<!-- Bootstrap Core CSS -->
<link href="../css/bootstrap.min.css" rel='stylesheet' type='text/css' />

<!-- BOLT Sandbox/test //-->
<script id="bolt" src="https://sboxcheckout-static.citruspay.com/bolt/run/bolt.min.js" bolt-
color="e34524" bolt-logo="http://boltiswatching.com/wp-content/uploads/2015/09/Bolt-Logo-e14421724859591.png"></script>
<!-- BOLT Production/Live //-->
<!--// script id="bolt" src="https://checkout-static.citruspay.com/bolt/run/bolt.min.js" bolt-color="e34524" bolt-logo="http://boltiswatching.com/wp-content/uploads/2015/09/Bolt-Logo-e14421724859591.png"></script //-->

</head>
<style type="text/css">
	.main {
		margin-left:30px;
		font-family:Verdana, Geneva, sans-serif, serif;
	}
	.text {
		float:left;
		width:180px;
	}
	.dv {
		margin-bottom:5px;
	}
</style>
<body>

<div class="container main">
	<div class="row" style="margin-top:5em;">
		<div class="col-md-4"></div>
		<div class="col-12 col-md-5">
			<div>
		    	<img src="images/payumoney.png" />
		    </div>
		    <div>
		    	<h3>PHP7 BOLT Kit</h3>
		    </div>
			<form action="#" id="payment_form">
		    <input type="hidden" id="udf5" name="udf5" value="BOLT_KIT_PHP7" />
		    <input type="hidden" id="surl" name="surl" value="<?php echo getCallbackUrl(); ?>" />
		    <div class="dv">
		    <!-- <span class="text"><label>Merchant Key:</label></span> 1llBOlBG -->
		    <span><input type="hidden" id="key" name="key" placeholder="Merchant Key" value="<?php echo KEY; ?>" /></span>
		    </div>
		    
		    <div class="dv">
		    <!-- <span class="text"><label>Merchant Salt:</label></span> SFHYwMm1Pc -->
		    <span><input type="hidden" id="salt" name="salt" placeholder="Merchant Salt" value="<?php echo SALT; ?>" /></span>
		    </div>
		    
		    <div class="dv">
		    <!-- <span class="text"><label>Transaction/Order ID:</label></span> -->
		    	<?php
		    		$transaction_id="Txn".date("YmdHis").rand(10000,99999999);
		    	?>
		    <span><input type="hidden" id="txnid" name="txnid" placeholder="Transaction ID" value="<?php echo $transaction_id; ?>" readonly/></span>
		    </div>
		    
		    <div class="dv">
		    <span class="text"><label>Course:</label></span>
		    <span><input type="text" id="course" name="course" value="<?php echo $subcourse; ?>" readonly/></span>    <!-- <input type="hidden" name="courseid" id="courseid" value="<?php //echo $courseid; ?>"> -->
		    </div>

		    <div class="dv">
		    <span class="text"><label>Course Plan:</label></span>
		    <span><input type="text" id="course_plan" name="plan_type" value="<?php echo $plan_type; ?>" readonly/></span>    
		    </div>

		    <div class="dv">
		    <span class="text"><label>Course Amount:</label></span>
		    <span><input type="text" id="amount" name="amount" placeholder="Amount" value="<?php echo $amount; ?>" readonly /></span>    
		    </div>
		    
		    <!-- <div class="dv">
		    <span class="text"><label>Product Info:</label></span> -->
		    <span><input type="hidden" id="pinfo" name="pinfo" placeholder="Product Info" value="Course Payment" /></span>
		    <!-- </div> -->
		    
		    <div class="dv">
		    <span class="text"><label>First Name:</label></span>
		    <span><input type="text" id="fname" name="fname" placeholder="First Name" value="" required/></span>
		    </div>
		    
		    <div class="dv">
		    <span class="text"><label>Email ID:</label></span>
		    <span><input type="email" id="email" name="email" placeholder="Email ID" value="" required/>
		    	<p class="text-info">If you don't have any email. Then use "abhyastest@gmail.com"</p>
		    </span>
		    </div>
		    
		    <div class="dv">
		    <span class="text"><label>Mobile/Cell Number:</label></span>
		    <span><input type="text" id="mobile" name="mobile" placeholder="Mobile/Cell Number" value="" /></span>
		    </div>
		    
		    <div class="dv">
		    <!-- <span class="text"><label>Hash:</label></span> -->
		    <span><input type="hidden" id="hash" name="hash" placeholder="Hash" value=""/></span>
		    </div>
		    
		    <!-- custom validation for csrf token -->
		    <input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>" id="custom_csrf">
		    
		    <div><input type="submit" class="btn btn-success" value="Pay" onclick="launchBOLT(); return false;" /></div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4"></div>
		<div class="col-12 col-md-6" style="margin-top:2em;margin-bottom:1em;">
			<a href="../index.php?home" class="btn btn-primary">Back To User Panel</a>
		</div>
	</div>
</div>

<script type="text/javascript"><!--
// after filling other input fields we are generating hash key and setting in hash key field.
$('#payment_form').bind('keyup blur', function(){
	$.ajax({
          url: 'getHash.php',
          type: 'post',
          data: JSON.stringify({ 
            key: $('#key').val(),
			salt: $('#salt').val(),
			txnid: $('#txnid').val(),
			amount: $('#amount').val(),
		    pinfo: $('#pinfo').val(),
            fname: $('#fname').val(),
			email: $('#email').val(),
			mobile: $('#mobile').val(),
			udf5: $('#udf5').val()
          }),
		  contentType: "application/json",
          dataType: 'json',
          success: function(json) {
            if (json['error']) {
			 $('#alertinfo').html('<i class="fa fa-info-circle"></i>'+json['error']);
            }
			else if (json['success']) {	
				$('#hash').val(json['success']);
            }
          }
        }); 
});
//-->
</script>
<script type="text/javascript"><!--

// In this launchBOLT() we are sending our fields for payment. And after payment response will came. so we are taking these fields again from response via 'responseHandler' and submitting the form to 'response.php'
function launchBOLT()
{
	// var csrf_index=$("#custom_csrf").attr("name");

	bolt.launch({
	key: $('#key').val(),
	txnid: $('#txnid').val(), 
	hash: $('#hash').val(),
	amount: $('#amount').val(),
	firstname: $('#fname').val(),
	email: $('#email').val(),
	phone: $('#mobile').val(),
	productinfo: $('#pinfo').val(),
	udf5: $('#udf5').val(),
	surl : $('#surl').val(),
	furl: $('#surl').val(),

	// custom fields including CSRF
	// udf1 : 	$("#courseid").val(),
	// udf2 : $("#course_plan").val(),
	// csrf_index : $("#custom_csrf").val(),
	//

	mode: 'dropout'	
},{ responseHandler: function(BOLT){
	console.log( BOLT.response.txnStatus );		
	if(BOLT.response.txnStatus != 'CANCEL')
	{
		// custom fields : course, plan_type, csrf (name,value)

		var fr = '<form action=\"'+$('#surl').val()+'\" method=\"post\">' +
		'<input type=\"hidden\" name=\"key\" value=\"'+BOLT.response.key+'\" />' +
		'<input type=\"hidden\" name=\"salt\" value=\"'+$('#salt').val()+'\" />' +
		'<input type=\"hidden\" name=\"txnid\" value=\"'+BOLT.response.txnid+'\" />' +
		'<input type=\"hidden\" name=\"amount\" value=\"'+BOLT.response.amount+'\" />' +
		'<input type=\"hidden\" name=\"productinfo\" value=\"'+BOLT.response.productinfo+'\" />' +
		'<input type=\"hidden\" name=\"firstname\" value=\"'+BOLT.response.firstname+'\" />' +
		'<input type=\"hidden\" name=\"email\" value=\"'+BOLT.response.email+'\" />' +
		'<input type=\"hidden\" name=\"udf5\" value=\"'+BOLT.response.udf5+'\" />' +
		'<input type=\"hidden\" name=\"mihpayid\" value=\"'+BOLT.response.mihpayid+'\" />' +
		'<input type=\"hidden\" name=\"status\" value=\"'+BOLT.response.status+'\" />' +
		'<input type=\"hidden\" name=\"hash\" value=\"'+BOLT.response.hash+'\" />' +

		// '<input type=\"hidden\" name=\"udf1\" value=\"'+$("#courseid").val()+'\" />' +
		// '<input type=\"hidden\" name=\"udf2\" value=\"'+$("#course_plan").val()+'\" />' +
		//'<input type=\"hidden\" name=\"'+$("#custom_csrf").attr("name")+'\" value=\"'+$("#custom_csrf").val()+'\" />' +

		'</form>';

		var form = jQuery(fr);
		jQuery('body').append(form);								
		form.submit();
	}
},
	catchException: function(BOLT){
		// alert( BOLT.message );
 		alert('Please Enter Valid Name, Email, Mobile Number');
	}
});
}
//--
</script>

<?php
	}
	else
	{
		echo '<script>alert("You Have Already Paid For This Perticular Course/ Sub-Course/ Plan. Please Contact Admin For More Information.")</script>';
		redirect("../choose_course_pay.php?select_payment_type");
	}
}
else
	redirect("../choose_course_pay.php?select_payment_type");
?>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>	

</body>
</html>
	
