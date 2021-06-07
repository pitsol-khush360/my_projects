<?php 
  require_once("../../config/config.php"); 
  require_once("../../config/".ENV."_config.php");
  include("header.php");
  
if(isset($_SESSION['business_name_for_otp'])){
  ?>
<div class="container">

<form class="" action="" method="post" class="form">
  <div class="form-group col-md-4">
    <p>Entered Mobile Number :</p>
<input type="tel" name="mobile" value="<?php echo 	$_SESSION['mobile_for_otp'] ; ?>" class="form-control">
  </div>

<div class="form-group col-md-4" style="display:none;" id="otp_field">
  <p>Enter Otp :</p>
<input type="text" name="otp" value="" placeholder="Enter Otp"  class="form-control">
</div>

<div class="form-group col-md-4 ">
<a href="#" class="btn btn-primary btn-lg" id="otp"> Generate OTP </a>
</div>

<div class="form-group col-md-4">
<input type="submit" name="otpverify" value="Verify Otp" placeholder="Verify OTP" class="btn btn-primary btn-lg" id="verify_otp" style="display:none;">
</div>

<a href="login.php" class="btn btn-danger"> Back </a>

</form>
</div>

  <?php
  if(isset($_POST['otpverify'])){
    $data['business_name'] = $_SESSION['business_name_for_otp'];
    $data['mobile'] = 	$_SESSION['mobile_for_otp'];
    $data['password'] = $_SESSION['password_for_otp'];

    $url=DOMAIN.'/rest/seller/createUserRegistrationRest.php';
    
      $defaults = array(
      CURLOPT_URL => $url,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => $data,
      );

      $client=curl_init();
      curl_setopt_array($client,$defaults);
      curl_setopt($client,CURLOPT_RETURNTRANSFER,true);

      $output=curl_exec($client);
      curl_close($client);      // To close curl.
      $output=json_decode($output,JSON_FORCE_OBJECT);

      if(isset($output['register']) && $output['register']['response_code'] == 200)
      {
        $_SESSION['user_id'] = $output['register']['user_id'];
        $_SESSION['role'] =$output['register']['role'];
        $_SESSION['username'] = $output['register']['username'];
        $_SESSION['business_name'] = $output['register']['business_name'];
        $_SESSION['mobile'] = $output['register']['mobile'];

        redirect("displaySellerDashboard.php");
      }
      else
      if(isset($output['register']) && $output['register']['response_code']==405)
      {
        echo '<script>alert("Mobile Number Already Exist!")</script>';
      }
  }



}else {
  echo "Not allowed Please SignUp and come";
}


 ?>
 <script type="text/javascript">
   var otp = document.getElementById("otp");
   var otp_verify = document.getElementById("verify_otp");
   var otp_field = document.getElementById("otp_field");
   otp.addEventListener('click',() =>{
     otp_field.style.display="block";
     otp.style.display ="none";
     otp_verify.style.display ="block";
   });
 </script>
