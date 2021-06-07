<?php
require_once("../../config/config.php");
require_once("../../config/" . ENV . "_config.php");
?>

<?php
if (!(isset($_SESSION['current_user'])))
    redirect('login.php');
?>
<?php include("navigation.php"); ?>
<?php

//$access = premissionScreen('CHANGE_PASSWORD', $_SESSION['current_user']);

//$global = $access['global'];
//$input = $access['input'];
//$button = $access['button'];

//if ($global != 0) {
?>



    <?php
    $showinformation = 0;
    $message = "";
    ?>
    <!Doctype html>
    <html>

    <head>
        <style>

        </style>
    </head>

    <body>
        <input type="hidden" value="<?php echo DOMAIN; ?>" id="getDOMAIN">
        <div class="jumbotron">
            <div class="container text-center shadow p-3 mb-5 bg-white rounded">
                <h4 style="margin-bottom: 50px;">Change Your Password</h4>
                <form action="displayAdminChangePassword" method="post">
                    <div class="row">
                        <div class="offset-md-2 col-10 col-md-6">
                            <div class="form-group row">
                                <label for="cur_password" class="col-form-label col-6"><b>Current Password:</b></label>
                                <div class="col-6">
                                    <input type="password" id="cur_password" class="form-control border border-top-0 border-left-0 border-right-0" minlength="6" maxlength="15" required>
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
                    </div>
                    <!--Change Confirm Modal-->
                    <div class="modal" id="confirmChangeModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
                        <div class="modal-dialog modal-dialog-centered " role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title">&nbsp;&nbsp;<strong>Change Confirmation
                                        </strong></h3>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-footer">
                                    <input type="button" class="btn btn-success w-50" id="submitchangedpassword" value='Yes'>
                                    <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Change Confirm Modal -->
                    <div class="row mt-4 justify-content-center">

                        <div class="col-5 col-md-3">
                            <div class="form-group">
                                <input type="button" class="btn btn-primary btn-md w-100 " data-toggle="modal" data-target="#confirmChangeModal" value="Submit">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>




        <script>
            $(document).ready(() => {
                if (window.history.replaceState) {
                    window.history.replaceState(null, null, window.location.href);
                }
            });
            $("#togglepassword").on("click",
                function() {
                    type = $("#new_password").attr("type");

                    if (type == "password") {
                        $("#new_password").attr("type", "text");
                        $("#togglepassword").removeClass("fas fa-eye");
                        $("#togglepassword").addClass("fas fa-eye-slash");
                    } else
                    if (type == "text") {
                        $("#new_password").attr("type", "password");
                        $("#togglepassword").removeClass("fas fa-eye-slash");
                        $("#togglepassword").addClass("fas fa-eye");
                    }
                });

            $("#submitchangedpassword").click(() => {
                let adminID = "<?php echo $_SESSION['current_user']; ?>";
                let currentPass = $("#cur_passwrod").val();
                let newPass = $("#new_passwrod").val();
                let cnewPass = $("#cnew_password").val();
                var domain = $("#getDOMAIN").val();
                if (newPass != cnewPass) {
                    $.post(domain + '/rest/admin/UpdateAdminUserPasswordRest.php?admin_id=' + adminID + '&cur_password=' + currentPass + '&new_password=' + newPass + '&key=<?php echo md5(VALIDATION_KEY);?>',
                        function(data, status) {

                            $("#confirmChangeModal").click();
                            if (data['updatepassword']['response_code'] == 200) {
                                $("#response").modal("show");
                                $("#restext").text("Password Changed Successfully");
                                $("#resdesc").text("");
                            } else {
                                $("#response").modal("show");
                                $("#restext").text("Operation Unsuccessful");
                                $("#resdesc").text("ERROR : " + data['updatepassword']['response_desc']);
                            }
                        });
                    //location.reload(true);
                } else {
                    $("#confirmChangeModal").click();
                    $("#response").modal("show");
                    $("#restext").text("Operation Unsuccessful");
                    $("#resdesc").text("ERROR : Password Missmatch");
                }
            });
        </script>

    </body>

    </html>
<?php
//} else {
    //echo '<h2 class="pt-5" style="text-align:center;color:red;margin-top:4rem;">Permission Denied!</h2>';
//}
?>
<?php include("footer.php"); ?>