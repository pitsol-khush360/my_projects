<?php include("navigation.php"); ?>

<?php
  if(!(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2))
    redirect("login.php");
?>

<?php
$showinformation=0;
$message="";
if(isset($_POST['submitchangedpassword']))
{
    if(trim($_POST['cur_password'])!="" && trim($_POST['new_password'])!="" && trim($_POST['cnew_password'])!="")
    {
        if(escape_string(trim($_POST['new_password']))==escape_string(trim($_POST['cnew_password'])))
        {
            $data['user_id']=$_SESSION['user_id'];
            $data['cur_password']=escape_string(trim($_POST['cur_password']));
            $data['new_password']=escape_string(trim($_POST['new_password']));
            $url=DOMAIN.'/rest/seller/updateSellerPasswordRest.php';
            $output=getRestApiResponse($url,$data);
            
            if(isset($output['updatepassword']) && $output['updatepassword']['response_code']==200)
            {
                $showinformation=1;
                $message='<p class="text-success">Password updated successfully</p>';
                header("refresh:0;url=logout.php");
            }
            else
            if(isset($output['updatepassword']) && $output['updatepassword']['response_code']==405)
            {
                $showinformation=1;
                $message='<p class="text-danger">Current Password is incorrect</p>';
            }
            else
            {
                $showinformation=1;
                $message='<p class="text-danger">Unable to change your password</p>';
            }
        }
        else
        {
            $showinformation=1;
            $message='<p class="text-danger">Password and Confirm Password are not same</p>';
        }
    }
    else
    {
        $showinformation=1;
        $message='<p class="text-danger">All fields are maindatory!</p>';
    }
}
?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row mt-4">
        <div class="col-lg-12 text-center mt-2">
              <h4>Change Your Password<h4>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 mt-4">
            <form action="" method="post">
                <div class="row">
                    <div class="offset-md-2 col-10 col-md-6">
                        <div class="form-group row">
                        <label for="cur_password" class="col-form-label col-6"><b>Current Password:</b></label>
                        <div class="col-6">
                            <input type="password" name="cur_password" id="cur_password" class="form-control border border-top-0 border-left-0 border-right-0" minlength="6" maxlength="15" required>
                        </div>  
                        </div>
                    </div>
                    <div class="col-2">
                        <i id="cur_password_notifier" class="fas fa-times text-danger d-none"></i>
                    </div>
                    <div class="offset-md-2 col-10 col-md-6">
                        <div class="form-group row">
                        <label for="new_password" class="col-form-label col-6"><b>New Password:</b></label>
                        <div class="col-6">
                            <input type="password" name="new_password" id="new_password" class="form-control border border-top-0 border-left-0 border-right-0" minlength="6" maxlength="15" required>
                        </div>
                        </div>
                    </div>
                    <div class="col-2 mt-2">
                        <i class="fas fa-eye fa-lg" id="togglepassword"></i>
                    </div> 
                    <div class="offset-md-2 col-10 col-md-6">
                        <div class="form-group row">
                        <label for="cnew_password" class="col-form-label col-6"><b>Confirm New Password:</b></label>
                        <div class="col-6">
                            <input type="password" name="cnew_password" id="cnew_password" class="form-control border border-top-0 border-left-0 border-right-0" minlength="6" maxlength="15" required>
                        </div>  
                        </div>
                    </div>
                    <div class="col-2 mt-2">
                        <i class="fas fa-check text-success d-none" id="confirm_password_notifier"></i>
                    </div>
                </div>

            <div class="row mt-4">
                <div class="offset-md-2 col-5 col-md-3">
                    <a href="javascript:history.go(-1)" class="btn btn-success w-100">Back</a>
                </div>
                <div class="col-5 col-md-3">
                    <div class="form-group">
                      <input type="submit" name="submitchangedpassword" id="submitchangedpassword" class="btn btn-primary btn-md w-100" value="Submit" disabled>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

        </div>
        <!-- content end -->
    </div>
    <!-- page content -->
</div>
<!-- page wrapper end -->

<?php include("footer.php"); ?>
<?php
    if($showinformation==1)
        echo '<script>
                $("#information").html(\''.$message.'\');
                $("#information-modal").modal("show");
            </script>';
?>

<script>
    var isCurrentPasswordVerified=0;

    $("#togglepassword").on("click",
        function()
        {
            type=$("#new_password").attr("type");

            if(type=="password")
            {
                $("#new_password").attr("type","text");
                $("#togglepassword").removeClass("fas fa-eye");
                $("#togglepassword").addClass("fas fa-eye-slash");
            }
            else 
            if(type=="text")
            {
                $("#new_password").attr("type","password");
                $("#togglepassword").removeClass("fas fa-eye-slash");
                $("#togglepassword").addClass("fas fa-eye");
            }
        });

    $("#cur_password").keyup(
        function()
        {
            password=$("#cur_password").val();

            if(password!="")
            {
                var tobesend = 'password='+password;

                $.ajax({
                    type: 'POST',
                    url: 'verifyCurrentPasswordHelper.php',
                    data: tobesend,
                    dataType: 'json',
                    success: function(response)
                    {   
                        if(response.status == 1)
                        {
                            isCurrentPasswordVerified=1;

                            if($("#cur_password_notifier").hasClass("d-none"))
                                $("#cur_password_notifier").removeClass("d-none");

                            if($("#cur_password_notifier").hasClass("fas fa-times text-danger"))
                                $("#cur_password_notifier").removeClass("fas fa-times text-danger");

                            $("#cur_password_notifier").addClass("fas fa-check text-success");

                            password=$("#new_password").val();
                            cpassword=$("#cnew_password").val();

                            if(password==cpassword && isCurrentPasswordVerified==1)
                                $("#submitchangedpassword").attr("disabled",false);
                        }
                        else
                        {
                            isCurrentPasswordVerified=0;

                            if($("#cur_password_notifier").hasClass("d-none"))
                                $("#cur_password_notifier").removeClass("d-none");

                            if($("#cur_password_notifier").hasClass("fas fa-check text-success"))
                                $("#cur_password_notifier").removeClass("fas fa-check text-success");

                            $("#cur_password_notifier").addClass("fas fa-times text-danger");

                            password=$("#new_password").val();
                            cpassword=$("#cnew_password").val();

                            if(isCurrentPasswordVerified==0 || password!=cpassword)
                                $("#submitchangedpassword").attr("disabled",true);
                        }
                    }
                });
            }
            else
            {
                $("#cur_password_notifier").addClass("d-none");
                $("#submitchangedpassword").attr("disabled",true);
            }
        });

      $("#cnew_password").keyup(
        function()  
        {
          password=$("#new_password").val();
          cpassword=$("#cnew_password").val();
        
          if(password==cpassword)
          {
              if($("#confirm_password_notifier").hasClass("d-none"))
                $("#confirm_password_notifier").removeClass("d-none");

              $("#confirm_password_notifier").removeClass("fas fa-times text-danger");
              $("#confirm_password_notifier").addClass("fas fa-check text-success");

              if(isCurrentPasswordVerified==1)
                $("#submitchangedpassword").attr("disabled",false);
          }
          else
          {
            if($("#confirm_password_notifier").hasClass("d-none"))
                $("#confirm_password_notifier").removeClass("d-none");

            $("#confirm_password_notifier").removeClass("fas fa-check text-success");
            $("#confirm_password_notifier").addClass("fas fa-times text-danger");
            $("#submitchangedpassword").attr("disabled",true);
          }
        });

      $("#new_password").keyup(
        function()  
        {
          password=$("#new_password").val();
          cpassword=$("#cnew_password").val();
        
          if(password==cpassword)
          {
              if($("#confirm_password_notifier").hasClass("d-none"))
                $("#confirm_password_notifier").removeClass("d-none");

              $("#confirm_password_notifier").removeClass("fas fa-times text-danger");
              $("#confirm_password_notifier").addClass("fas fa-check text-success");

              if(isCurrentPasswordVerified==1)
                $("#submitchangedpassword").attr("disabled",false);
          }
          else
          {
            if($("#confirm_password_notifier").hasClass("d-none"))
                $("#confirm_password_notifier").removeClass("d-none");

            $("#confirm_password_notifier").removeClass("fas fa-check text-success");
            $("#confirm_password_notifier").addClass("fas fa-times text-danger");
            $("#submitchangedpassword").attr("disabled",true);
          }
        });
</script>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

  </body>
</html>
