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

$access = premissionScreen('ADMIN_USER_LIST', $_SESSION['current_user']);
$global = $access['global'];
$input = $access['input'];
$button = $access['button'];

if ($global != 0) {
?>



    <?php
    $showinformation = 0;
    $message = "";
    ?>

    <!Doctype html>
    <html>
    <input type="hidden" value="<?php echo DOMAIN; ?>" id="getDOMAIN">
    <div class="col pt-4" style="float:right; width : 20%">
        <?php
        if ($button != "disabled") {
        ?>
            <button class='btn btn-primary mt-3 mb-3 w-100' style='float : right;' data-toggle="modal" data-target="#exampleModal">Add Admin</button>
        <?php
        } else {
        ?>
            <button class='btn btn-secondary mt-3 mb-3 w-100' style='float : right;' disabled>Add Admin</button>
        <?php
        }
        ?>
    </div>
    <!-- Connecting with RestApi-->

    <?php
    $data = array();

    if (isset($_POST['admin_id'])) {
        $data['admin_id'] = $_POST['admin_id'];
    } else
             if (isset($_POST['full_name'])) {
        $data['full_name'] = $_POST['full_name'];
    } else
            if (isset($_POST['role'])) {
        $data['role'] = $_POST['role'];
    } else
            if (isset($_POST['status'])) {
        $data['status'] = $_POST['status'];
    }
    
    $url = DOMAIN . '/rest/admin/GetAdminUserDetailsRest.php';
    $output = getRestApiResponse($url, $data);

    $record = '';

    if (isset($output['getadmindetails']) && $output['getadmindetails']['response_code'] == 200) {

        $record .= <<< record
               <div class="container-fluid pt-4 pb-2" >
               <div class="row" >
                   <div class="col">
                       <form action="displayAdminUserList.php" method="post">
                           <div class="input-group " style="float:right;margin-right:20px">
                               <input type="text" class="form-control" placeholder="Admin Id" name="admin_id" style="border: medium solid black;margin-top:16px;">
                               <div class="input-group-append">
                                   <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                       <i class="fa fa-search"></i>
                                   </button>
                               </div>
                           </div>
                       </form>
                   </div>
                   <div class="col">
                       <form action="displayAdminUserList.php" method="post">
                           <div class="input-group " style="float:right;margin-right:20px">
                               <input type="text" class="form-control" placeholder="Full Name" name="full_name" style="border: medium solid black;margin-top:16px;">
                               <div class="input-group-append">
                                   <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                       <i class="fa fa-search"></i>
                                   </button>
                               </div>
                           </div>
                       </form>
                   </div>
                   <div class="col">
                       <form action="displayAdminUserList.php" method="post">
                           <div class="input-group " style="float:right;margin-right:20px">
                               <input type="text" class="form-control" placeholder="Role" name="role" style="border: medium solid black;margin-top:16px;">
                               <div class="input-group-append">
                                   <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                       <i class="fa fa-search"></i>
                                   </button>
                               </div>
                           </div>
                       </form>
                   </div>
                   <div class="col">
                       <form action="displayAdminUserList.php" method="post">
                           <div class="input-group " style="float:right;margin-right:20px">
                               <input type="text" class="form-control" placeholder="Status" name="status" style="border: medium solid black;margin-top:16px;">
                               <div class="input-group-append">
                                   <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                       <i class="fa fa-search"></i>
                                   </button>
                               </div>
                           </div>
                       </form>
                   </div>
                  
               </div>
           </div>
       
       
       
       
          
       
           <table class="table table-hover table-responsive-lg table-sm table-bordered text-center">
       
               <thead style='background-color : #36404e;color : white'>
                   <tr>
                       <th>Admin ID</th>
                       <th>Full Name</th>
                       <th>Role</th>
                       <th>Status</th>
                       <th>Last Modified By</th>
                       <th>Change Status</th>
                       <th>Modify</th>
                       <th>Assign Permission</th>
                   </tr>
               </thead>
       
               <tbody>
       
record;

        for ($i = 0; $i < $output['getadmindetails']['rows']; $i++) {
            $record .= <<< record
                    <tr>
                        <td>{$output['getadmindetails'][$i]['userid']}</td>
                        <td>{$output['getadmindetails'][$i]['full_name']}</td>
                        <td>{$output['getadmindetails'][$i]['role']}</td>
                        <td>{$output['getadmindetails'][$i]['status']}</td>
                        <td>{$output['getadmindetails'][$i]['last_modified_by']}</td>
record;

            if ($button != "disabled") {
                if ($output['getadmindetails'][$i]['status'] == "Active") {
                    $record .= <<< record
                            <td><div><input type = "button" data-toggle = "modal" data-target = "#confirmSuspendModal" id = "{$output['getadmindetails'][$i]['userid']}" value = "Suspend" class = "btn btn-warning" onclick="suspendFun(this.id)"></td>
record;
                }

                if ($output['getadmindetails'][$i]['status'] == "Suspended") {
                    $record .= <<< record
                            <td><div><input type = "button" data-toggle = "modal" data-target = "#confirmActivateModal" id = "{$output['getadmindetails'][$i]['userid']}" value = "Activate" class = "btn btn-primary" onclick="activeFun(this.id)"></td>
record;
                }

                if ($output['getadmindetails'][$i]['status'] == "Captured") {
                    $record .= <<< record
                            <td><div><button type = "button" data-toggle="modal" data-target="#authModal" id ="{$output['getadmindetails'][$i]['userid']}" class = "btn btn-success authbtn">Authorize</button></td>
record;
                }

                if ($output['getadmindetails'][$i]['status'] == "Suspended") {
                    $record .= <<< record
                            <td><div><button type = "button" data-toggle="modal" data-target="#rowModal" id = "{$output['getadmindetails'][$i]['userid']}" class = "btn btn-success mdfbtn">Modify</button></td>
                            <td><div><button type = "button" id = "{$output['getadmindetails'][$i]['userid']}" data-toggle="modal" data-target="#apModal" class = "btn btn-secondary apbtn" disabled>Assign Permission</button></td>
                        </tr>      
record;
                } else {
                    $record .= <<< record
                            <td><div><button type = "button" data-toggle="modal" data-target="#rowModal" id = "{$output['getadmindetails'][$i]['userid']}" class = "btn btn-success mdfbtn">Modify</button></td>
                            <td><div><button type = "button" id = "{$output['getadmindetails'][$i]['userid']}" data-toggle="modal" data-target="#apModal" class = "btn btn-primary apbtn">Assign Permission</button></td>
                        </tr>      
record;
                }
            } else {
                if ($output['getadmindetails'][$i]['status'] == "Active") {
                    $record .= <<< record
                            <td><div><input type = "button" value = "Suspend" class = "btn btn-secondary" disabled ></div></td>
record;
                }

                if ($output['getadmindetails'][$i]['status'] == "Suspended") {
                    $record .= <<< record
                            <td><input type = "button"   value = "Activate" class = "btn btn-secondary" disabled></td>
record;
                }

                if ($output['getadmindetails'][$i]['status'] == "Captured") {
                    $record .= <<< record
                            <td><div><button type = "button" class = "btn btn-secondary" disabled>Authorize</button></div></td>
record;
                }

                if ($output['getadmindetails'][$i]['status'] == "Suspended") {
                    $record .= <<< record
                            <td><button type = "button" class = "btn btn-secondary" disabled>Modify</button></td>
                            <td><button type = "button" class = "btn btn-secondary" disabled>Assign Permission</button></td>
                        </tr>      
record;
                } else {
                    $record .= <<< record
                            <td><button type = "button" class = "btn btn-secondary" disabled>Modify</button></td>
                            <td><button type = "button" class = "btn btn-secondary" disabled>Assign Permission</button></td>
                        </tr>      
record;
                }
            }
        }

        echo $record;
    } else {
        if (isset($output['getadmindetails']) && $output['getadmindetails']['response_code'] == 405) {
            $record .= <<< record
                    <h3 class = "text-center pt-5" style = "color : red;">{$output['getadmindetails']['response_desc']}</h3>
record;
        }
        echo $record;
    }
    ?>
    </tbody>
    </table>



    <!--Add User Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Admin User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <?php
                $data = array();
                if (isset($_POST['admin_idadd']) && isset($_POST['fullnameadd']) && isset($_POST['roleadd']) && isset($_POST['passadd']) && isset($_POST['pass_confirmadd'])) {
                    $data['admin_id'] = $_POST['admin_idadd'];
                    $data['full_name'] = $_POST['fullnameadd'];
                    $data['role'] = $_POST['roleadd'];
                    $data['password'] = $_POST['passadd'];
                    $data['confirm_password'] = $_POST['pass_confirmadd'];
                    $data['current_user'] = $_SESSION['current_user'];

                    if ($data['password'] == $data['confirm_password']) {
                        $url = DOMAIN . '/rest/admin/CreateAdminUserRest.php';
                        $output = getRestApiResponse($url, $data);
                        if ($output['getcreateadmindetails']['response_code'] == 200) {
                            $showinformation = 1;
                            $message = '<p class="text-success">Added Successful</p>';
                        } else {
                            $showinformation = 1;
                            $message = '<p class="text-danger">' . $output['getcreateadmindetails']['response_desc'] . '</p>';
                        }
                    } else {
                        $showinformation = 1;
                        $message = '<p class="text-danger">' . "Password Mismatch" . '</p>';
                    }
                }
                ?>

                <div class="modal-body">
                    <form action='displayAdminUserList.php' method='POST'>
                        <div class="form-group">
                            <label>Admin Id</label>
                            <input type="text" name="admin_idadd" style="float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
                        </div>

                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="fullnameadd" style="float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" name="roleadd" style="float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="passadd" style="float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
                        </div>

                        <div class="form-group">
                            <label>Re-Type Password</label>
                            <input type="password" name="pass_confirmadd" style="float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
                        </div>

                        <!--Confirm Modal-->
                        <div class="modal" id="confirmAddModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
                            <div class="modal-dialog modal-dialog-centered " role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title"><i class="fa fa-user-plus" style="color:green" aria-hidden="true"></i>&nbsp;&nbsp;<strong>Save Confirmation
                                            </strong></h3>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>

                                    </div>
                                    <div class="modal-body"></div>
                                    <div class="modal-footer">
                                        <input type="submit" class="btn btn-success w-50" value='Yes'>
                                        <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End of Confirm Modal -->

                    </form>
                    <hr>
                    <div class=" text-center">
                        <button class="btn btn-primary" style="width : 35%" data-toggle="modal" data-target="#confirmAddModal">Save</button>
                        <button type="button" class="btn btn-danger" style="width : 35%" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--End of add User Modal-->


    <!--Authorize Modal-->
    <div class="modal fade" id="authModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Authorize Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action='displayAdminUserList.php' method='POST'>
                        <div class="form-group">
                            <label>Admin Id</label>
                            <input type="text" id="authAdminId" disabled name="user_id" style="float : right; border : 1px solid #549eff;background-color:#cfcfcf; " <?php echo $input; ?>>
                        </div>

                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" id="authFullName" disabled name="fullname" style="float : right; border : 1px solid #549eff;background-color:#cfcfcf; " <?php echo $input; ?>>
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" id="authRole" disabled name="role" style="float : right; border : 1px solid #549eff;background-color:#cfcfcf; " <?php echo $input; ?>>
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" id="authPass" disabled name="pass" style="float : right; border : 1px solid #549eff;background-color:#cfcfcf; " <?php echo $input; ?>>
                        </div>

                        <div class="form-group">
                            <label>Re-Type Password</label>
                            <input type="password" id="authConfirmPass" disabled name="pass_confirm" style="float : right; border : 1px solid #549eff;background-color:#cfcfcf; " <?php echo $input; ?>>
                        </div>

                        <!--Authorize Admin Modal-->
                        <div class="modal" id="confirmAuthModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
                            <div class="modal-dialog modal-dialog-centered " role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title"><i class="fa fa-check-circle" style="color:green" aria-hidden="true"></i>&nbsp;&nbsp;<strong>Authorize Admin
                                            </strong></h3>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body"></div>
                                    <div class="modal-footer">
                                        <input type="button" class="btn btn-success w-50 yesauth" value='Yes'>
                                        <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End of Authorize Modal -->

                    </form>
                    <hr>
                    <div class=" text-center">
                        <button class="btn btn-primary" style="width : 35%" data-toggle="modal" data-target="#confirmAuthModal">Authorize</button>
                        <button type="button" class="btn btn-danger" style="width : 35%" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--End of Authorize Modal-->




    <!-- View Detail Modal-->
    <div class="modal fade" id="rowModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modify Admin User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="displayAdminUserList.php" method="POST">
                        <div class="form-group">
                            <label>Admin Id</label>
                            <input id="viewAdminId" type="text" name="admin_id" style="background-color : rgba(128, 128, 128, 0.555);float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
                        </div>

                        <div class="form-group">
                            <label>Full Name</label>
                            <input id="setFullName" type="text" name="fullname" style="float : right; border : 1px solid #549eff; margin-left : 5px " <?php echo $input; ?>>
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <input id="setRole" type="text" name="role" style="float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
                        </div>



                        <div class="form-group" id="passForm">
                            <label>Current Password</label>

                            <input id="setPass" type="password" name="currentpass" style="float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
                            <i class="fas fa-eye fa-lg" id="togglepassword" style="float:right; margin-top : 8px"></i>
                        </div>



                        <div class="form-group" id="passForm">
                            <label>New Password</label>
                            <input id="setnewPass" type="password" name="newpass" style="float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
                        </div>
                        <div class="form-group" id="confirmpassForm">
                            <label>Re-Type Password</label>
                            <input id="setConfirmPass" type="password" name="confirm_pass" style="float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
                        </div>


                        <!--Update Confirm Modal-->
                        <div class="modal" id="confirmUpdateModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
                            <div class="modal-dialog modal-dialog-centered " role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title"><i class="fas fa-sync-alt" style="color:green" aria-hidden="true"></i>&nbsp;&nbsp;<strong>Update Confirmation
                                            </strong></h3>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body"></div>
                                    <div class="modal-footer">
                                        <input type="button" class="btn btn-success w-50 uptbtn" value='Yes'>
                                        <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End of Update Confirm Modal -->


                        <!--Delete Confirm Modal-->
                        <div class="modal" id="confirmDeleteModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
                            <div class="modal-dialog modal-dialog-centered " role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title"><i class="fa fa-trash" style="color:red" aria-hidden="true"></i>&nbsp;&nbsp;<strong>Delete Confirmation
                                            </strong></h3>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body"></div>
                                    <div class="modal-footer">
                                        <input type="button" class="btn btn-success w-50 dltbtn" value='Yes'>
                                        <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End of Delete Confirm Modal -->

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" style="width : 35%" data-toggle="modal" data-target="#confirmUpdateModal">Update</button>


                    <button class="btn btn-primary" style="width : 35%" data-toggle="modal" data-target="#confirmDeleteModal" id='dltbtn'>Delete</button>
                    <button type="button" class="btn btn-danger" style="width : 35%" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!--End of View Detail Modal-->

    <!--Suspend Confirm Modal-->
    <div class="modal" id="confirmSuspendModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        <i class="fa fa-pause" style="color:red" aria-hidden="true"></i>&nbsp;&nbsp;
                        <strong>Suspend Confirmation</strong>
                    </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <input type="button" id="suspendConfirmbtn" class="btn btn-success w-50 " onclick="suspendactiveFun(this.id, 'Suspend')" value='Yes'>
                    <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Suspend Confirm Modal -->

    <!--Activate Confirm Modal-->
    <div class="modal" id="confirmActivateModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        <i class="fa fa-thumbs-up" style="color:green" aria-hidden="true"></i>&nbsp;&nbsp;
                        <strong>Activate Confirmation</strong>
                    </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="hiddenA">
                </div>
                <div class="modal-footer">
                    <input type="button" id="activeConfirmbtn" class="btn btn-success w-50" onclick="suspendactiveFun(this.id, 'Activate')" value='Yes'>
                    <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Activate Confirm Modal -->

    <!-- Assign Permission Modal-->
    <div class="modal fade bd-example-modal-lg" id="apModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg mw-100 w-75 mx-auto" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <p id="apModal-title"></p>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-5">
                                <table class="table table-hover table-responsive table-sm table-bordered" id="sourceT">
                                    <tbody id="leftT">

                                    </tbody>
                                </table>
                            </div>
                            <div class="col-2 text-center">
                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-secondary rounded-circle" id="assign-to-userpermission"><i class="fas fa-arrow-right"></i></button>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <button type="button" class="btn btn-secondary rounded-circle" id="remove-from-userpermission"><i class="fas fa-arrow-left"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <table class="table table-hover table-responsive table-sm table-bordered" id="destT">
                                    <tbody id="rightT">

                                    </tbody>
                                </table>
                            </div>
                            <div class="offset-2 col-4 text-center mt-2">
                                <button type="button" id="save-assigned-permissions" class="btn btn-primary w-100" data-dismiss="modal">Save</button>
                            </div>
                            <div class="col-4 text-center mt-2">
                                <button type="button" class="btn btn-secondary w-100" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--End of Assign Permission Modal-->
    <?php
    if ($showinformation == 1)
        echo '<script>
				$("#information").html(\'' . $message . '\');
				$("#information-modal").modal("show");
			</script>';
    ?>


    <script>
        $('.mdfbtn').click(
            function() {

                var admin_id = $(this).attr('id');
                console.log(admin_id);
                var domain = $("#getDOMAIN").val();
                $.post(domain + '/rest/admin/GetAdminUserDetailsRest.php?admin_id=' + admin_id + '&key=<?php echo md5(VALIDATION_KEY);?>',
                    function(data, status) {
                        console.log(data);
                        $("#viewAdminId").prop('disabled', true);
                        $("#viewAdminId").val(data['getadmindetails'][0]['userid']);
                        $("#setFullName").val(data['getadmindetails'][0]['full_name']);
                        $("#setRole").val(data['getadmindetails'][0]['role']);
                        $("#setPass").val(data['getadmindetails'][0]['password']);
                    });
            });
        $("#togglepassword").on("click",
            function() {
                type = $("#setPass").attr("type");

                if (type == "password") {
                    $("#setPass").attr("type", "text");
                    $("#togglepassword").removeClass("fas fa-eye");
                    $("#togglepassword").addClass("fas fa-eye-slash");
                } else
                if (type == "text") {
                    $("#setPass").attr("type", "password");
                    $("#togglepassword").removeClass("fas fa-eye-slash");
                    $("#togglepassword").addClass("fas fa-eye");
                }
            });
        $('.uptbtn').click(function() {

            var domain = $("#getDOMAIN").val();


            var admin_id = $('#viewAdminId').val();
            var full_name = $('#setFullName').val();
            var role = $('#setRole').val();
            var pass = $('#setnewPass').val();
            var confirmPass = $('#setConfirmPass').val();

            var data = 'admin_id=' + admin_id + '&full_name=' + full_name + '&role=' + role + '&password=' + pass + '&confirm_password=' + confirmPass + '&key=<?php echo md5(VALIDATION_KEY);?>';
            if (pass == confirmPass) {
                $.post(domain + '/rest/admin/UpdateAdminUserDetailsRest.php?' + data,
                    function(data, status) {
                        $("#confirmUpdateModal").click();
                        $("#rowModal").click();
                        if (data['updateuserdetails']['response_code'] == 200) {
                            $("#response").modal("show");
                            $("#restext").text("Updation Successful");
                            $("#resdesc").text("");
                        } else {
                            $("#response").modal("show");
                            $("#restext").text("Updation Unsuccessful");
                            $("#resdesc").text("ERROR :" + data['updateuserdetails']['response_desc']);
                        }
                    });


            } else {
                $("#confirmUpdateModal").click();
                $("#rowModal").click();
                $("#response").modal("show");
                $("#restext").text("Updation Unsuccessful");
                $("#resdesc").text("ERROR : Password Missmatch");
            }


        });

        $('.dltbtn').click(function() {
            var domain = $("#getDOMAIN").val();
            var admin_id = $("#viewAdminId").val();
            $.post(domain + '/rest/admin/DeleteAdminUserRest.php?admin_id=' + admin_id + '&key=<?php echo md5(VALIDATION_KEY);?>',
                function(data, status) {
                    $("#confirmDeleteModal").click();
                    $("#rowModal").click();
                    if (data['deleteadmindetails']['response_code'] == 200) {
                        $("#response").modal("show");
                        $("#restext").text("Deletion Successful");
                        $("#resdesc").text("");
                    } else {
                        $("#response").modal("show");
                        $("#restext").text("Deletion Unsuccessful");
                        $("#resdesc").text("ERROR : " + data['deleteadmindetails']['response_desc']);
                    }
                });

        });
    </script>

    <script>
        var user_roles = [];
        var roleid_to_be_moved = "";
        var rolename_to_be_moved = "";
        var admin_id = "";

        $(".apbtn").click(function() {
            user_roles = [];
            roleid_to_be_moved = "";
            rolename_to_be_moved = "";
            admin_id = "";

            admin_id = $(this).attr("id");

            $("#apModal-title").text("Assign permissions to " + admin_id);

            var domain = $("#getDOMAIN").val();

            $.post(domain + '/rest/admin/FetchUserAccessPermissionsRest.php?admin_id=' + admin_id + '&key=<?php echo md5(VALIDATION_KEY);?>',
                function(data, status) {

                    for (var i = 0; i < data['permissiondetails']['availablepermission'].length; i++) {
                        $("#leftT").append("<tr role=\"" + data['permissiondetails']['availablepermission'][i]["ROLE_NAME"] + "\" roleid=\"" + data['permissiondetails']['availablepermission'][i]["id"] + "\"><td>" + data['permissiondetails']['availablepermission'][i]["ROLE_NAME"] + "</td></tr>");
                    }
                    for (var i = 0; i < data['permissiondetails']['givenpermission'].length; i++) {
                        $("#rightT").append("<tr role=\"" + data['permissiondetails']['givenpermission'][i]["ROLE_NAME"] + "\" roleid=\"" + data['permissiondetails']['givenpermission'][i]["id"] + "\"><td>" + data['permissiondetails']['givenpermission'][i]["ROLE_NAME"] + "</td></tr>");

                        user_roles.push(data['permissiondetails']['givenpermission'][i]["id"]);
                    }
                });
        });

        $("#sourceT").on("click", "tr", function() {
            roleid_to_be_moved = $(this).attr("roleid");
            rolename_to_be_moved = $(this).attr("role");
            $("#sourceT tr").find("td").css("border", "1px solid black");
            $("#destT tr").find("td").css("border", "1px solid black");
            $(this).find("td").css("border", "2px solid red");
        });

        $("#assign-to-userpermission").on("click",
            function() {
                if (roleid_to_be_moved != "" && rolename_to_be_moved != "") {
                    if ($.inArray(roleid_to_be_moved, user_roles) > -1) {
                        return;
                    } else {
                        user_roles.push(roleid_to_be_moved);
                        $("#rightT").append("<tr role=\"" + rolename_to_be_moved + "\" roleid=\"" + roleid_to_be_moved + "\"><td>" + rolename_to_be_moved + "</td></tr>");
                        $("#leftT tr[roleid=" + roleid_to_be_moved + "]").remove();
                    }
                } else
                    return;
            });

        $("#destT").on("click", "tr", function() {
            roleid_to_be_moved = $(this).attr("roleid");
            rolename_to_be_moved = $(this).attr("role");
            $("#destT tr").find("td").css("border", "1px solid black");
            $("#sourceT tr").find("td").css("border", "1px solid black");
            $(this).find("td").css("border", "2px solid red");
        });

        $("#remove-from-userpermission").on("click",
            function() {
                if (roleid_to_be_moved != "" && rolename_to_be_moved != "") {
                    if ($.inArray(roleid_to_be_moved, user_roles) > -1) {
                        user_roles.splice($.inArray(roleid_to_be_moved, user_roles), 1);
                        $("#leftT").append("<tr role=\"" + rolename_to_be_moved + "\" roleid=\"" + roleid_to_be_moved + "\"><td>" + rolename_to_be_moved + "</td></tr>");
                        $("#rightT tr[roleid=" + roleid_to_be_moved + "]").remove();
                    } else {}
                } else
                    return;
            });

        $("#save-assigned-permissions").on("click",
            () => {
                if (user_roles.length != 0) {
                    console.log(admin_id);
                    console.log(user_roles.length);
                    console.log(user_roles);

                    var tobesend = 'admin_id=' + admin_id + '&permissions_count=' + user_roles.length + '&permissions=' + user_roles;
                    //datatobesend={ admin_id : admin_id, permissions_count : user_roles.length, permissions : user_roles };
                    //alert(data.permissions);

                    $.ajax({
                        type: 'POST',
                        url: 'assignPermissionsToUserHelper.php',
                        data: tobesend,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 1) {
                                $('#information').html("<p class='text-success'>Permissions assigned successfully</p>");
                                $('#information-modal').modal('show');
                            } else {

                                $('#information').html("<p class='text-danger'>Unable to assign permissions</p>");
                                $('#information-modal').modal('show');
                            }
                        }
                    });
                } else
                    return;
            });
    </script>

    <script>
        $(".authbtn").click(function() {
            var admin_id = $(this).attr('id');
            console.log(admin_id);
            var domain = $("#getDOMAIN").val();
            $.post(domain + '/rest/admin/GetAdminUserDetailsRest.php?admin_id=' + admin_id + "&current_user=" + "<?php echo $_SESSION['current_user'] ?>" + '&key=<?php echo md5(VALIDATION_KEY);?>',
                function(data, status) {
                    $("#authAdminId").prop('disabled', true);
                    $("#authAdminId").val(data['getadmindetails'][0]['userid']);
                    $("#authFullName").val(data['getadmindetails'][0]['full_name']);
                    $("#authRole").val(data['getadmindetails'][0]['role']);
                });

        });


        $(".yesauth").click(() => {
            var admin_id = $("#authAdminId").val();
            var domain = $("#getDOMAIN").val();
            $.post(domain + '/rest/admin/AuthorizedAdminUserRest.php?admin_id=' + admin_id + "&current_user=" + "<?php echo $_SESSION['current_user'] ?>" + '&key=<?php echo md5(VALIDATION_KEY);?>',
                function(data, status) {
                    console.log(data);
                    $("#confirmAuthModal").click();
                    $("#authModal").click();


                    if (data['getauthorizeuserdetails']['response_code'] == 200) {
                        $("#response").modal("show");
                        $("#restext").text("Authorize Successful");
                        $("#resdesc").text("");
                    } else {
                        $("#response").modal("show");
                        $("#restext").text("Authorize Unsuccessful");
                        $("#resdesc").text("ERROR : " + data['getauthorizeuserdetails']['response_desc']);
                    }
                });
        });






        function suspendactiveFun(adminid, wbtn) {
            var domain = $("#getDOMAIN").val();
            $.post(domain + '/rest/admin/ChangeAdminUserStatustRest.php?admin_id=' + adminid + '&key=<?php echo md5(VALIDATION_KEY);?>',
                function(data, status) {
                    $("#confirm" + wbtn + "Modal").click();

                    if (data['getchangeadminstatusdetails']['response_code'] == 200) {
                        $("#response").modal("show");
                        $("#restext").text(wbtn + " Successful");
                        $("#resdesc").text("");
                    } else {
                        $("#response").modal("show");
                        $("#restext").text(wbtn + " Unsuccessful");
                        $("#resdesc").text("ERROR : " + data['getchangeadminstatusdetails']['response_desc']);
                    }
                });
            //location.reload(true);
        }

        function suspendFun(id) {
            $("#suspendConfirmbtn").attr("id", id);
        }

        function activeFun(id) {
            $("#activeConfirmbtn").attr("id", id);
        }

        $('#apModal').on('hidden.bs.modal', function() {

            $("#leftT").empty();
            $("#rightT").empty();

        });
        $(document).ready(() => {
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        });
    </script>


    </body>

    </html>
<?php
} else {
    echo '<h2 class = "pt-5" style="text-align:center;color:red;margin-top:4rem;">Permission Denied!</h2>';
}
?>
<?php include("footer.php"); ?>
