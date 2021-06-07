<?php
  require("../../resources/config.php");
?>
<!DOCTYPE HTML>
<html>
<head>
<title>login to admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
 <!-- Bootstrap Core CSS -->
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- jQuery -->
<script src="js/jquery.min.js"></script>
<!----webfonts--->
<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900' rel='stylesheet' type='text/css'>
<!---//webfonts--->  
<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
</head>
<body id="login">
  <div class="login-logo">
    <a href="index.html"><img src="images/.png" alt=""/></a>
  </div>
  <h2 class="form-heading">Login</h2>
  <div class="app-cam">
	  <form method="post" action="admin_login_data.php">
		<input type="text" name="admin_username" class="text" placeholder="Enter Username" required pattern="[A-Za-z0-9]+" minlength="3" maxlength="20" title="Only Enter Your Username Between 3 to 20 chars without any special character..">
		<input type="password" name="admin_password" placeholder="Enter Password" required minlength="6" maxlength="15" title="Only Enter Your Password Between 6 to 15 chars..">
    <input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
		<div class="submit"><input type="submit" value="Login"></div>
			<div class="clearfix"></div>
	</form>
  </div>
   <div class="copy_layout login">
      <p>Copyright &copy; 2020 pitsol. All Rights Reserved | Design by <a href="http://www.pitsol.com/" target="_blank">Pitsol</a> </p>
   </div>
</body>
</html>
