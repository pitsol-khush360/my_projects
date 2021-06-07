<?php 
  require_once("../../config/config.php"); 
  require_once("../../config/".ENV."_config.php");
?>

<?php

$showinformation=0;
$message="";

if(isset($_POST['sendotp']) || isset($_POST['resendotp']))
{
  // code for sending otp
  $otp=rand(1000,9999);
  $text="Your one time password is ".$otp;

  // Api for sending otp
  $_SESSION['otp']=$otp;

  $data['mobile']=$_SESSION['mobile_for_otp'];
  $data['text']=$text;

  $url=DOMAIN.'/rest/seller/sendSmsApiRest.php';
  $output=getRestApiResponse($url,$data);

  if(!(isset($output['smsstatus']) && $output['smsstatus']['response_code'] == 200))
  {
    $showinformation=1;
    $message='<p class="text-danger">Unable to send OTP!</p>';
  }
}

if(isset($_POST['verifynumberonregister']))
{
  if(trim($_POST["password"]) == trim($_POST["cpassword"]))
  {
    $_SESSION['your_name_for_otp'] = $_POST["your_name"];
    $_SESSION['mobile_for_otp'] = $_POST["mobile_number"];
    $_SESSION['password_for_otp'] =  $_POST["password"];
  }
  else 
  {
    $showinformation=1;
    $message='<p class="text-danger">Password and Confirm Password are not same!</p>';
  }
}

if(isset($_POST['register']))
{
  if($_SESSION['otp']==$_POST['otp'])
  {
    $data['business_name'] = $_SESSION['your_name_for_otp'];
    $data['mobile'] =   $_SESSION['mobile_for_otp'];
    $data['password'] = $_SESSION['password_for_otp'];
    $data['mobile_verified'] = "Yes";
    $data['accept_terms_and_conditions'] = "Yes";
    $url=DOMAIN.'/rest/seller/createUserRegistrationRest.php';
    $output=getRestApiResponse($url,$data);

    if(isset($output['register']) && $output['register']['response_code'] == 200)
    {
      $_SESSION['user_id'] = $output['register']['user_id'];
      $_SESSION['role'] =$output['register']['role'];
      $_SESSION['username'] = $output['register']['username'];
      $_SESSION['business_name'] = $output['register']['business_name'];
      $_SESSION['mobile'] = $output['register']['mobile'];
      $_SESSION['seller_image']="/images/sellers/defaultpic.jpg";

      unset($_SESSION['your_name_for_otp']);
      unset($_SESSION['mobile_for_otp']);
      unset($_SESSION['password_for_otp']);
      unset($_SESSION['otp']);

      redirect("displaySellerDashboard.php");
    }
    else
    if(isset($output['register']) && $output['register']['response_code']==405)
    {
      unset($_SESSION['your_name_for_otp']);
      unset($_SESSION['mobile_for_otp']);
      unset($_SESSION['password_for_otp']);
      unset($_SESSION['otp']);

      $showinformation=1;
      $message='<p class="text-danger">Mobile number already exist!</p>';
    }
  }
  else
  {
    $showinformation=1;
    $message='<p class="text-danger">OTP doesn\'t matched!</p>';
  }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">

    <title><?php echo APP; ?> - Register</title>

    <link rel="stylesheet" href="../../public/css/bootstrap/bootstrap.min.css">
    <script src="../../public/js/jquery.min.js"></script>
    <script src="../../public/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link href="../../public/css/signin.css?<?php echo time(); ?>" rel="stylesheet">
  </head>
  <!-- <body> -->

<div class="container-signin-signup sign_in_mode">
  <div class="forms-container">
    <div class="signin-signup">

      <?php 
        if(isset($_POST['sendotp']) || isset($_POST['resendotp']))
        {
          echo '<div class="sign-in-form">
            <div class="row">
              <div class="col-12 text-center">
                <p class="title">Phone Verification</p>
              </div>
            </div>
           <div class="row">
              <div class="offset-2 col-8 mt-4 text-center smalltext">
                <p>Enter 4 digit verification code sent to your mobile number</p>
              </div>
            </div>
           
            <form method="post" action="">
              <div class="row mt-2">
                <div class="offset-2 col-8 mt-2">
                  <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                      </div>
                      <input type="password" name="otp" value="" placeholder="Enter OTP" class="form-control" minlength="4" maxlength="4" required>
                    </div>
                </div>
             
                <div class="offset-2 col-8 mt-4">
                  <input type="submit" name="register" value="Submit" class="btn btn-primary w-100">
                </div>
              </div>
            </form>

            <div class="row mt-4">
              <div class="offset-2 col-8 text-center">
                <div class="row">
                  <div class="col-6 text-danger signin-signup-nml-txt">
                    <p>Didn\'t Received Code&nbsp;<i class="fa fa-question"></i></p>
                  </div>
                  <div class="col-6 text-right">
                    <form action="" method="post">
                    <button type="submit" name="resendotp" class="btn bg-transparent text-info link-to-signin-signup">Resend OTP</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mt-3">
              <div class="offset-2 col-8">
                <a href="javascript:history.go(-1)" class="btn btn-danger link-to-signin-signup">Back</a>
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

            </div>
          </div>
        </div>';
        }
        else
        if(isset($_POST['verifynumberonregister']))
        {
      ?>
         <div class="sign-in-form">
          <div class="row">
            <div class="col-12 text-center">
              <p class="title">Verify Your Phone Number</p>
            </div>
          </div>
           
          <div class="row mt-3">
            <div class="col-12 text-center smalltext">
              <p>We will send you a 4 digit OTP code to this number</p>
            </div>
          </div>
           
          <form method="post" action="">
            <div class="row">
              <div class="offset-2 col-8 mt-4">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i>+91</i></span>
                  </div>
                  <input type="text" name="mobile_number" value="<?php echo $_POST['mobile_number']; ?>" class="form-control" readonly>
                </div>
              </div>
            
              <div class="offset-2 col-8 mt-4">
                <input type="submit" name="sendotp" value="Continue" class="btn btn-primary w-100">
              </div>
            </div>
          </form>

          <div class="row mt-5">
              <div class="offset-2 col-8">
                <a href="javascript:history.go(-1)" class="btn btn-danger link-to-signin-signup">Back</a>
              </div>
          </div>

          <div class="row mt-5 signin-signup-nml-txt">
            <div class="col-12 text-center text-info">
              <a class="openterms onhovercursor">Terms & Conditions</a><div class="vl"></div><a class="openprivacy onhovercursor">Privacy Policy</a>
            </div>
            <div class="col-12 text-center">
              <p>Copyright &copy; 2020</p>
            </div>
          </div>

        </div>
      </div>
    </div>
        <?php
          }
          else
          {
        ?>
          <div class="sign-in-form">

            <?php 
              if(isset($_GET['mobile']) && isset($_GET['notexist']))
              {
                echo '<div class="row mt-4">
                        <div class="offset-2 col-8 bg-warning border border-dark signin-signup-nml-txt">
                          <p>Oops! it looks like you don\'t have an account with us. Don\'t worry, just fill the form below and create your account now</p>
                        </div>
                      </div>';
              }
            ?>
            <div class="row">
              <div class="col-12 text-center">
                <p class="title">Sign Up</p>
              </div>
            </div>

           <form method="post" action="">
             <div class="row mt-1">
                <div class="offset-2 col-8 mt-3">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="text" name="your_name" placeholder="Your Name" class="form-control" required>
                  </div>
                </div>
              
                <div class="offset-2 col-8 mt-3">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i>+91</i></span>
                    </div>
                    <?php
                      if(isset($_GET['mobile']))
                      {
                        echo '<input type="text" pattern="[5-9]{1}[0-9]{9}" title="Enter Valid 10 Digit Mobile Number" name="mobile_number" placeholder="Mobile" class="form-control" value="'.$_GET['mobile'].'" required>';
                      }
                      else
                      {
                        echo '<input type="text" pattern="[5-9]{1}[0-9]{9}" title="Enter Valid 10 Digit Mobile Number" name="mobile_number" placeholder="Mobile" class="form-control" required>';
                      }
                    ?>
                  </div>
                </div>
              
                <div class="offset-2 col-8 mt-3">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    </div>
                    <input type="password" name="password" placeholder="Password" class="form-control" minlength="6" maxlength="15" id="passwordfield" required>
                  </div>
                </div>
              
                <div class="offset-2 col-8 mt-3">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    </div>
                    <input type="password" name="cpassword" placeholder="Retype Password" class="form-control border-right-0" minlength="6" maxlength="15" id="confirm_password_field" required>
                    <div class="input-group-append">
                      <span class="input-group-text bg-transparent border-left-0"><i class="" id="confirm_password_notifier"></i></span>
                    </div>
                  </div>
                  <!-- <p id="confirm_password_notifier" class="text-danger"></p> -->
                </div>
              
                <div class="offset-2 col-8 mt-3">
                  <div class="form-check smalltext">
                    <input type="checkbox" class="form-check-input" id="agreetc" autocomplete="off">&nbsp;
                    <label class="form-check-label" for="agreetc">I agree to Terms & Conditions</label>
                  </div>
                </div>
              
                <div class="offset-2 col-8 mt-3">
                  <input type="submit" name="verifynumberonregister" value="Continue" class="btn btn-primary w-100" id="verifynumberonregister" disabled>
                </div>
              </div>
          </form>

          <div class="row mt-4">
            <div class="offset-2 col-8">
              <div class="row">
                <div class="col-9 signin-signup-nml-txt">
                  <p class="mt-3">Already a member&nbsp;<i class="fa fa-question"></i></p>
                </div>
                <div class="col-3 text-right">
                  <a href="login.php" class="btn btn-danger link-to-signin-signup">Login</a>
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

        </div>
      </div>
    </div>
        <?php
          }
        ?>

   <div class="panels-container">
      <!-- <div class="left-panel panel">
       <div class="content">
         <h3>New here?</h3>
         <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit,  enim ad minim veniam, quis nostrud exercit</p>
         <button type="button" name="button" class="btn transparent" id ="sign_up_btn">Sign Up</button>
       </div>
     </div>

     <div class="right-panel panel">
       <div class="content">
         <h3>One of us?</h3>
         <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, enim ad minim veniam, quis nostrud exercit</p>
         <button type="button" name="button" class="btn transparent" id ="sign_in_btn">Sign In</button>

       </div> -->
        <!-- <img src="../../public/signup.svg"  alt="" class="image"> -->
     <!-- </div> -->

   </div>

<!-- <div class="modal fade" id="opentc" tabindex="-1" role="dialog" aria-labelledby="tc" aria-hidden="true">
  <div class="modal-dialog modal-lg mw-100 w-75 mx-auto" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tc">Our Terms & Conditions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <?php //readDataFromFile("../../public/terms_and_conditions.txt"); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="openpp" tabindex="-1" role="dialog" aria-labelledby="pp" aria-hidden="true">
  <div class="modal-dialog modal-lg mw-100 w-75 mx-auto" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pp">Our Privacy Policy</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <?php //readDataFromFile("../../public/privacy_policy.txt"); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div> -->

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

<script>
  $("#confirm_password_field").keyup(
    function()  
    {
      password=$("#passwordfield").val();
      cpassword=$("#confirm_password_field").val();
    
      if(password==cpassword)
      {
        if($("#agreetc").checked)
        {
          // $("#confirm_password_notifier").text("");
          $("#confirm_password_notifier").removeClass("fas fa-times text-danger");
          $("#confirm_password_notifier").addClass("fas fa-check text-success");
          $("#verifynumberonregister").attr("disabled",false);
        }
        else
        {
          $("#confirm_password_notifier").removeClass("fas fa-times text-danger");
          $("#confirm_password_notifier").addClass("fas fa-check text-success");
        }
          // $("#confirm_password_notifier").text("");
      }
      else
      {
        // $("#confirm_password_notifier").text("Password and Confirm Password are not same");
        $("#confirm_password_notifier").removeClass("fas fa-check text-success");
        $("#confirm_password_notifier").addClass("fas fa-times text-danger");
        $("#agreetc").prop("checked",false);
        $("#verifynumberonregister").attr("disabled",true);
      }
    });

  $("#passwordfield").keyup(
    function()  
    {
      password=$("#passwordfield").val();
      cpassword=$("#confirm_password_field").val();
    
      if(cpassword!="")
      {
        if(password==cpassword)
        {
          if($("#agreetc").checked)
          {
            // $("#confirm_password_notifier").text("");
            $("#confirm_password_notifier").removeClass("fas fa-times text-danger");
            $("#confirm_password_notifier").addClass("fas fa-check text-success");
            $("#verifynumberonregister").attr("disabled",false);
          }
          else
          {
            $("#confirm_password_notifier").removeClass("fas fa-times text-danger");
            $("#confirm_password_notifier").addClass("fas fa-check text-success");
          }
            //$("#confirm_password_notifier").text("");
        }
        else
        {
          // $("#confirm_password_notifier").text("Password and Confirm Password are not same");
          $("#confirm_password_notifier").removeClass("fas fa-check text-success");
          $("#confirm_password_notifier").addClass("fas fa-times text-danger");
          $("#agreetc").prop("checked",false);
          $("#verifynumberonregister").attr("disabled",true);
        }
      }
    });
</script>

<script>
  $("#agreetc").on("click",
      function()
      {
        password=$("#passwordfield").val();
        cpassword=$("#confirm_password_field").val();

        if(this.checked && password!="" && cpassword!="" && password==cpassword)
          $("#verifynumberonregister").attr("disabled",false);
        else
        if(password=="" || cpassword=="")
        {
          //$("#confirm_password_notifier").text("Password and Confirm Password must not be blank");
          $("#confirm_password_notifier").removeClass("fas fa-check text-success");
          $("#confirm_password_notifier").addClass("fas fa-times text-danger");
          $("#verifynumberonregister").attr("disabled",true);
          $("#agreetc").prop("checked",false); 
        }
        else
        if(password!=cpassword)
        {
          // $("#confirm_password_notifier").text("Password and Confirm Password are not same");
          $("#confirm_password_notifier").removeClass("fas fa-check text-success");
          $("#confirm_password_notifier").addClass("fas fa-times text-danger");
          $("#verifynumberonregister").attr("disabled",true);
          $("#agreetc").prop("checked",false);
        }
        else
        {
          $("#verifynumberonregister").attr("disabled",true);
          $("#agreetc").prop("checked",false);
        }
      });
</script>

<script>
  $(".openterms").on("click",
    function()
    {
      //$("#opentc").modal('show');
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
