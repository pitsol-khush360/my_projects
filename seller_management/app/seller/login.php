<?php 
  require_once("../../config/config.php"); 
  require_once("../../config/".ENV."_config.php");
?>

<?php

$showinformation=0;
$message="";

$flag="";

if(isset($_POST['resendotp']))
{
  // code for sending otp
  $otp=rand(1000,9999);
  $text="Your One Time Password is ".$otp;

  // Api for sending otp
  $_SESSION['otp']=$otp;
  $mobile=$_POST['mobile_number'];

  $data['mobile']=$_POST['mobile_number'];
  $data['text']=$text;

  $url=DOMAIN.'/rest/seller/sendSmsApiRest.php';
  $output=getRestApiResponse($url,$data);

  if(!(isset($output['smsstatus']) && $output['smsstatus']['response_code'] == 200))
  {
    $showinformation=1;
    $message='<p class="text-danger">Unable to send OTP!</p>';
  }
}

if(isset($_POST['checkmobilenumber']))
{
  $data['mobile']=$_POST['mobile_number'];
  $url=DOMAIN.'/rest/seller/verifyUserMobileRest.php';
  $output=getRestApiResponse($url,$data);

  if(isset($output['login']) && $output['login']['response_code']==200)
  {
    $mobile=$_POST['mobile_number'];
    $flag="mobilenumberverified";
  }
  else
  if(isset($output['login']) && $output['login']['response_code']==405)
  {
    $showinformation=1;
    $message='<p class="text-danger">'.$output['login']['response_desc'].'</p>';
  }
  else
  if(isset($output['login']) && $output['login']['response_code']==500)
  {
    redirect("signup.php?mobile=".$_POST['mobile_number']."&notexist");
  }
}

if(isset($_POST['login']))
{
	$data['mobile'] = $_POST['mobile_number'];

  if(isset($_POST['password']) && $_POST['password']!="")
  {
    $data['password'] = $_POST["password"];
    $data['authentication_method'] = "password";
  }
  else
  if(isset($_POST['otp']))
  {
    if(isset($_SESSION['otp']) && $_SESSION['otp']!="")
    {
      if($_SESSION['otp']==$_POST['otp'])
      {
        $data['authentication_method'] = "otp";
        unset($_SESSION['otp']);
      }
      else
      {
        $showinformation=1;
        $message='<p class="text-danger">Invalid OTP!</p>';
        unset($_SESSION['otp']);
      }
    }
  }
  
  if((isset($data['authentication_method']) && $data['authentication_method']=="password") || (isset($data['authentication_method']) && $data['authentication_method']=="otp"))
  {
    $url=DOMAIN.'/rest/seller/getUserdetailsOnLoginRest.php';

    $output=getRestApiResponse($url,$data);

    if(isset($output['login']) && $output['login']['response_code']==200)
    {
    	$_SESSION['user_id'] = $output['login']['user_id'];
    	$_SESSION['role'] = $output['login']['role'];
      $_SESSION['username'] = $output['login']['username'];
      $_SESSION['business_name'] = $output['login']['business_name'];
      $_SESSION['mobile'] = $output['login']['mobile'];
      $_SESSION['seller_image']=$output['login']['seller_image'];

      redirect("displaySellerDashboard.php");
    }
    else
    if(isset($output['login']) && $output['login']['response_code']==405)
    {
      $showinformation=1;
      $message='<p class="text-danger">'.$output['login']['login_authentication'].'</p>';
    }
    else 
    {
      $showinformation=1;
      $message='<p class="text-danger">Invalid Login Credentials!</p>';
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?php echo APP; ?> - Login</title>

  <link rel="stylesheet" href="../../public/css/bootstrap/bootstrap.min.css">
  <script src="../../public/js/jquery.min.js"></script>
  <script src="../../public/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
  <link href="../../public/css/signin.css?<?php echo time(); ?>" rel="stylesheet">
</head>
<body>

<div class="container">
  <div class="row">
    <div class="col-12 offset-md-2 col-md-8">

      <?php 
        if(isset($flag) && $flag=="mobilenumberverified" || isset($_POST['resendotp']))
        {
      ?>
           <div class="row mt-5">
              <div class="col-12 text-center mt-5">
                <p class="title">Login</p>
              </div>
           </div>
           <div class="row mt-3">
              <div class="col-8 col-md-6 signin-signup-nml-txt">
                <p class="text-left"><?php echo $mobile; ?></p>
              </div>
              <div class="col-4 col-md-6 d-flex justify-content-end">
                <form method="post" action="">
                  <input type="hidden" name="number" value="<?php echo $mobile; ?>">
                  <input type="submit" name="changenumber" class="btn text-info bg-transparent d-flex justify-content-end" value="Change">
                </form>
              </div>
            </div>

            <div class="row mt-3">
              <?php
                if(isset($_POST['resendotp']))
                {
              ?>
                <div class="col-12" id="otpnotifier">
              <?php
                }
                else
                {
              ?>
                <div class="col-12 d-none" id="otpnotifier">
              <?php
                }
              ?>
                <div class="row">
                  <div class="col-12 smalltext">
                    <p>Enter 4 digit verification code sent to your mobile number</p>
                  </div>
                  <div class="col-12 d-flex justify-content-end">
                    <form action="" method="post">
                      <input type="hidden" name="mobile_number" value="<?php echo $mobile; ?>">
                      <button type="submit" name="resendotp" class="btn bg-transparent text-info link-to-signin-signup">Resend OTP</button>
                    </form>
                  </div>
                </div>
              </div>

            </div>
          
            <form method="post" action="">
              <div class="row">
                <input type="hidden" name="mobile_number" value="<?php echo $mobile; ?>">
                <?php
                    if(isset($_POST['resendotp']))
                    {
                  ?>
                      <div class="col-12 mt-2">
                        <div class="input-group d-none" id="password_field">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                            </div>
                            <input type="password" name="password" placeholder="Enter Password" class="form-control" id="password-input" minlength="6" maxlength="15">
                          </div>
                      </div>

                      <div class="col-12 mt-2">
                        <div class="input-group" id="otp_field">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                            </div>
                            <input type="password" name="otp" placeholder="Enter OTP" class="form-control" id="otp-input" minlength="4" maxlength="4" required>
                          </div>
                      </div>
                    <?php
                      }
                      else
                      {
                    ?>
                      <div class="col-12 mt-2">
                        <div class="input-group" id="password_field">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                            </div>
                            <input type="password" name="password" placeholder="Enter Password" class="form-control" id="password-input" minlength="6" maxlength="15" required>
                          </div>
                      </div>

                      <div class="col-12 mt-2">
                        <div class="input-group d-none" id="otp_field">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                            </div>
                            <input type="password" name="otp" placeholder="Enter OTP" class="form-control" id="otp-input" minlength="4" maxlength="4">
                          </div>
                      </div>
                    <?php
                      }
                    ?>

                <div class="col-12 mt-3">
                  <input type="submit" name="login" value="Submit" class="btn btn-primary w-100">
                </div>
              </div>
            </form>

          <div class="row mt-3">
            <div class="col-12 d-flex justify-content-center signin-signup-nml-txt">
              <b>OR</b>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-12">
              <h3>
                <?php
                  if(isset($_POST['resendotp']))
                    echo '<a class="btn btn-primary text-white text-center w-100 d-none link-to-signin-signup" mobile="'.$mobile.'" id="otp">Request OTP</a><a class="btn btn-primary text-white text-center w-100 link-to-signin-signup" id="password">Login With Password</a>';
                  else
                    echo '<a class="btn btn-primary text-white text-center w-100 link-to-signin-signup" mobile="'.$mobile.'" id="otp">Request OTP</a><a class="btn btn-primary text-white text-center w-100 d-none link-to-signin-signup" id="password">Login With Password</a>';
                ?>
               </h3>
            </div>
          </div>

          <div class="row mt-4 signin-signup-nml-txt">
            <div class="col-12 text-center text-info">
              <a class="openterms onhovercursor">Terms & Conditions</a><div class="vl"></div><a class="openprivacy onhovercursor">Privacy Policy</a>
            </div>
            <div class="col-12 text-center">
              <p>Copyright &copy; 2020</p>
            </div>
          </div>
        <?php
          }
          else
          {
        ?>
            <div class="row mt-5">
              <div class="col-12 mt-5 text-center">
                <h4 class="title">Login</h4>
              </div>
            </div>
            <form method="post" action="">
             <div class="row mt-3">
                <div class="col-12">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon1"><i>+91</i></span>
                    </div>
                    <?php
                      if(isset($_POST['changenumber']))
                      {
                        echo '<input type="text" pattern="[5-9]{1}[0-9]{9}" title="Enter Valid 10 Digit Mobile Number" name="mobile_number" placeholder="Mobile" class="form-control" value="'.$_POST['number'].'" id="mobile-field" required>';
                      }
                      else
                      {
                        echo '<input type="text" pattern="[5-9]{1}[0-9]{9}" title="Enter Valid 10 Digit Mobile Number" name="mobile_number" placeholder="Mobile" class="form-control" value="" id="mobile-field" required>';
                      }
                    ?>
                  </div>
                </div>

                <div class="col-12 mt-3">
                  <input type="submit" name="checkmobilenumber" value="Continue" class="btn btn-primary w-100" id="signinbtn">
                </div>
              </div>
            </form>

            <div class="row mt-4">
              <div class="col-12 mt-3">
                <p class="smalltext">By Logging, you Agree to our Terms Of Service and our Privacy Policy</p>
              </div>

              <div class="col-12 mt-3">
                <div class="row">
                  <div class="col-6 col-md-6 signin-signup-nml-txt">
                    <p class="mt-2 mt-md-3">New to <?php echo APP; ?>&nbsp;<i class="fa fa-question"></i></p>
                  </div>
                  <div class="col-6 col-md-6 text-right">
                    <a href="signup.php" class="btn btn-danger link-to-signin-signup" id="link-to-signup">Create Your Account</a>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mt-4 signin-signup-nml-txt">
              <div class="col-12 text-center text-info">
                <a class="openterms onhovercursor">Terms & Conditions</a><div class="vl"></div><a class="openprivacy onhovercursor">Privacy Policy</a>
              </div>
              <div class="col-12 text-center">
                <p>Copyright &copy; 2020</p>
              </div>
            </div>
        <?php
          }
        ?>

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
    </div>
  </div>
</div>

<script>
  $("#mobile-field").keyup(
    function()  
    {
      mobile=$("#mobile-field").val();

      if(mobile.length==10)
        $("#link-to-signup").attr("href","signup.php?mobile="+mobile);
      else
        $("#link-to-signup").attr("href","signup.php");
    });
</script>

<script>
  $("#otp").on("click",function()
  {
    $("#password_field").addClass("d-none");
    $("#otpnotifier").removeClass("d-none");
    $("#otp_field").removeClass("d-none");
    $("#password-input").prop("required",false);
    $("#password-input").val("");
    $("#otp-input").prop("required",true);
    $("#otp").addClass("d-none");
    $("#password").removeClass("d-none");

    var tobesend = 'mobile='+$("#otp").attr("mobile");

    $.ajax({
      type : 'post',
      url : 'sendotpforlogin.php',
      data : tobesend,
      dataType: 'json',
      success: function(response)
      {   
          if(response=="No")
          {
            alert("Unable To Send OTP");
          }
      }
    });
  });

  $("#password").on("click",function()
  {
    $("#otpnotifier").addClass("d-none");
    $("#otp_field").addClass("d-none");
    $("#password_field").removeClass("d-none");
    $("#otp-input").prop("required",false);
    $("#otp-input").val("");
    $("#password-input").prop("required",true);
    $("#password").addClass("d-none");
    $("#otp").removeClass("d-none");
  });
</script>

<script>
  $(".openterms").on("click",
    function()
    {
      // $("#opentc").modal('show');
      window.open("<?php echo DOMAIN; ?>"+"/public/Terms_And_Condition.html");
    });

  $(".openprivacy").on("click",
    function()
    {
      //$("#openpp").modal('show');
      window.open("<?php echo DOMAIN; ?>"+"/public/Privacy_Policy.html");
    });
</script>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

  </body>
</html>
