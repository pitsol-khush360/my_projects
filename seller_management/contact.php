<?php 
	require_once("config/config.php"); 
	require_once("config/" . ENV . "_config.php");
?>

<?php
	$showinformation=0;
	$message="";

	if(isset($_POST['post_query']))
	{
		$data['name']=escape_string(trim($_POST['customer_name']));
		$data['email']=escape_string(trim($_POST['customer_email']));
		$data['mobile']=escape_string(trim($_POST['customer_mobile']));
		$data['message']=escape_string(trim($_POST['customer_message']));

		$url=DOMAIN.'/rest/createContactUsQueryRest.php';
    	$output=getRestApiResponse($url,$data);
    
	    if(isset($output['contactus']) && $output['contactus']['response_code']==200)
	    {
	      	$showinformation=1;
      	  	$message='<p class="text-success">Your message posted successfully</p>';
	    }
	    else
	    {
	    	$showinformation=1;
      	  	$message='<p class="text-danger">Unable to post your message!</p>';
	    }
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo DOMAIN; ?> - Contact</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="public/font-awesome/css/fontawesome.min.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="public/font-awesome/css/all.min.css" rel="stylesheet" type="text/css">
    
    <link rel="stylesheet" href="public/css/bootstrap/bootstrap.min.css">

    <script src="public/js/jquery.min.js"></script>
    <script src="public/js/bootstrap.min.js"></script>

    <link href="public/css/app-home.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
</head>
<body>

<?php include("nav.php"); ?>

<div class="container-fluid" style="background-color:#e1e7f3;">
	<div class="row mt-5">
		<div class="col-12 mt-5">
			<form class="form" method="post" autocomplete="on">
				<div class="row">
					<h3 id="contactus_login_box_h3">Contact Us</h3>
				</div>
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="row">
							<div class="col-12">
								<div id="contactus_textbox">
									<i class="fas fa-user" id="contactus_textbox_i" aria-hidden="true"></i>
									<input type="text" id="contactus_textbox_input" placeholder="Your Name" name="customer_name" autocomplete="on" required>
								</div>
							</div>
							<div class="col-12">
								<div id="contactus_textbox">
									<i class="fas fa-envelope" id="contactus_textbox_i" aria-hidden="true"></i>
									<input type="email" id="contactus_textbox_input" placeholder="Email" name="customer_email" autocomplete="on" required>
								</div>
							</div>
							<div class="col-12">
								<div id="contactus_textbox">
									<i class="fas fa-mobile" id="contactus_textbox_i" aria-hidden="true"></i>
									<input type="text" pattern="[5-9]{1}[0-9]{9}" id="contactus_textbox_input" placeholder="Mobile Number" name="customer_mobile" autocomplete="on" required>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="row">
							<div class="col-12">
								<div id="contactus-textarea">
									<label for="customer_message"><i class="fas fa-comment" id="contactus_textbox_i" aria-hidden="true"></i> &nbsp;How Can We Help You ?</label>
							
									<textarea placeholder="Your Message" id="customer_message" name="customer_message" rows="7" cols="37" autocomplete="on" required></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-12 text-center">
						<button id="contactus_btn" type="submit" name="post_query">Post Message</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Footer -->
<?php include("footer.php"); ?>

<div class="modal fade" id="information-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <i class="fas fa-bell fa-2x text-warning"></i>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="information">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<?php
  if($showinformation==1)
    echo '<script>
        $("#information").html(\''.$message.'\');
        $("#information-modal").modal("show");
      </script>';
?>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

</body>
</html>