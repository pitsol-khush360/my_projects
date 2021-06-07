<?php include("../resources/config.php"); ?>
<?php 
  $title="Login";
  include(TEMPLATE_FRONT.DS."header.php"); 
?>

<?php
  if(isset($_POST['signin']))
    user_signin();
?>

<div class="container">
  <div class="row">
    <div class="offset-lg-3 col-lg-6 offset-md-3 col-md-6 col-xs-12" style="margin-top:5rem;">
      <h4 style="margin-top:10px;"><?php displaymessage(); ?></h4>
    <div id="login_box">      
      <form class="form" method="post">
        <h3 id="login_box_h3">Login</h3>
        <div class="textbox">
          <i class="fa fa-user" class="textbox_i" aria-hidden="true"></i>
          <input type="text" class="textbox_input" placeholder="Username" name="username" required>
        </div>
        <div class="textbox">
          <i class="fa fa-lock" class="textbox_i" aria-hidden="true"></i>
          <input type="password" class="textbox_input" placeholder="Password" name="password" required minlength="6" maxlength="15">
        </div>
        <a href="forgotpassword.php" class="pull-right" style="color:<?php echo $_SESSION['theme_color']; ?>;text-decoration:none;">forgot password?</a><br>
        <input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
        <button id="btn" type="submit" name="signin">Login</button><br>
      </form>
      <p>Don't Have An Account?&nbsp;<a href="signup.php" style="color:<?php echo $_SESSION['theme_color']; ?>;font-size:20px;text-decoration:none;">Register</a></p>
    </div>
    </div> 

    <!-- <div class="col-lg-3 col-xs-12 col-md-6 signinup_margins border" style="margin-top:7rem;">
      <p class="text-center" style="font-size:20px;">Or Signin With</p>
      <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div> google button
      
    </div> -->
  </div>
</div>

<!-- verification modal -->
<div class="modal" id="verification_modal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Account SetUp Instruction</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="row">
          <div class="col-xs-12 col-md-12">
            <p class='text-center text-success' style="font-size:15px;">One Time Verification. Enter Your Newly Created Username And Password</p>
          </div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
      </div>

    </div>
  </div>
</div>
<!-- contactus confirmation modal ends -->

<!-- google login api script -->
<script>
      // function onSignIn(googleUser) {
      //   // Useful data for your client-side scripts:
      //   var profile = googleUser.getBasicProfile();
      //   console.log("ID: " + profile.getId()); // Don't send this directly to your server!
      //   console.log('Full Name: ' + profile.getName());
      //   console.log('Given Name: ' + profile.getGivenName());
      //   console.log('Family Name: ' + profile.getFamilyName());
      //   console.log("Image URL: " + profile.getImageUrl());
      //   console.log("Email: " + profile.getEmail());

      //   // The ID token you need to pass to your backend:
      //   var id_token = googleUser.getAuthResponse().id_token;
      //   console.log("ID Token: " + id_token);
      // }
    </script>
<!-- google login api script ends -->
    
<?php
  if(isset($_GET['verifyaccount'])) 
  {
    echo '<script>$("#verification_modal").modal("show");</script>';
  }
?>

<script>
  $('#verification_modal').on('hidden.bs.modal', function (e) 
  {
    window.location.href="signin.php";
  });
</script>

<?php include(TEMPLATE_FRONT.DS."footer.php"); ?>