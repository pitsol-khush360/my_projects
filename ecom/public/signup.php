<?php require_once("../resources/config.php");  ?>
<?php include(TEMPLATE_FRONT.DS."header.php");  ?>

<!-- Page Content -->
<div class="container">

  <header>
      <h1 class="text-center">Sign Up</h1>
      <h3 class="text-center text-danger bg-danger"><?php displaymessage(); ?></h3>

      <div class="col-sm-4 col-sm-offset-5">   
          <form class="form" action="" method="post" enctype="multipart/form-data">
            <?php 
              user_signup();
            ?> 
              <div class="form-group">
                <label for="username">Username<input type="text" name="username" id="username" class="form-control"></label>
              </div>
              <div class="form-group"><label for="password">
                Password<input type="password" name="password" id="password" class="form-control"></label>
              </div>

              <div id="example1"></div>  <!-- Recaptcha Widget (shows recaptcha) -->

              <div class="form-group">
                <input type="submit" name="signup_submit" class="btn btn-primary" value="Create Account" onclick="return checkdata(grecaptcha.getResponse(widgetId1))">
              </div>
          </form>
      </div>  
  </header>

</div>
<!-- /.container -->

<!-- Google Recaptcha Script -->
<script type="text/javascript">
      var verifyCallback = function(response) {
        alert(response);
      };
      function checkdata(response)
      {
        if(response=="")
        {
          alert("Fill reCAPTCHA");
          return false;
        }
      }
      var widgetId1;
      var onloadCallback = function() {
        // Renders the HTML element with id 'example1' as a reCAPTCHA widget.
        // The id of the reCAPTCHA widget is assigned to 'widgetId1'.
        widgetId1 = grecaptcha.render('example1', {
          'sitekey' : '6LcVs68UAAAAAGBF2P4wVf9HlYgwlkLv_wIbyXFJ',
          'theme' : 'light'
        });
      };
</script>

<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
</script>
<!-- Google Recaptcha Script ends -->

<?php include(TEMPLATE_FRONT.DS."footer.php");  ?>