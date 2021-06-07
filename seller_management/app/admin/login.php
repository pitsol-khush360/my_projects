 
<?php 
	require_once("../../config/config.php"); 
	require_once("../../config/".ENV."_config.php");
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="<?php echo DOMAIN; ?>/public/css/bootstrap/bootstrap.min.css" crossorigin="anonymous">


  <style>
    .container{
      width: 300px;
      margin: auto;
      margin-top: 12%;
    }

    @media only screen and (max-width: 600px) {
      .container{
        margin-top: 35%;
      }
    }
  </style>
</head>

<body>

  <div class="container-fluid">
    <div class="container">     
    <form action = 'login.php' method = 'POST'>
        <h3 class="text-center" style="margin-bottom: 20px;">Admin Login</h3>
        <div class="form-group">
          <label>Username</label>
          <input type="text" autofocus class="form-control" name="userName" value = "" required>
        </div>

        <div class="form-group">
          <label>Password</label>
          <input type="password" class="form-control" name="pass" value = "" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>
     
      <?php
          $data = array();
         
          if(isset($_POST['userName']) && isset($_POST['pass'])) {
            $data['admin_id'] = $_POST['userName'];
            
            $data['password'] = $_POST['pass'];
            $url = DOMAIN.'/rest/admin/LoginAdminPanelRest.php';
          $output = getRestApiResponse($url,$data);
        
          if($output['getadmindetails']['response_code'] == 200) {
            
            $_SESSION['current_user'] = $_POST['userName'];
            header("location: displayAdminDashboard.php"); 
           }  else {
            echo "failed";
         header("location: login.php"); 
         }

            
            
  
          }

          
    ?>
    </div>
    <p class="text-center" style="margin-top: 20px;">By Logging, you Agree to our Terms Of Service and our Privacy Policy</p>

    <p class="text-center" style="margin-top: 30px;"><a href="<?php echo DOMAIN; ?>/public/Terms_And_Condition.html" target="_blank" style="text-decoration: none;">Terms & Conditions</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>|</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo DOMAIN; ?>/public/privacy_policy.html" target="_blank" style="text-decoration: none;">Privacy Policy</a></p>
    <p class="text-center" style="font-weight: 500;">Copyright © 2020</p>  
       

  </div>

 
  <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
   $(document).ready(() => {
            if ( window.history.replaceState ) {
              window.history.replaceState( null, null, window.location.href );
            }
        });
</script>
</body>

</html>