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

$access = premissionScreen('SELLER_LIST', $_SESSION['current_user']);

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

  
            <!-- Connecting with RestApi-->

            <?php
            $data = array();
            if (isset($_POST['user_id'])) {
                $data['user_id'] = $_POST['user_id'];
            } else if (isset($_POST['mobile'])) {
                $data['mobile'] = $_POST['mobile'];
            } else if (isset($_POST['username'])) {
                $data['username'] = $_POST['username'];
            } else if (isset($_POST['status'])) {
                if($_POST['status'] == 'Active') {
                    $data['status'] = 'A';
                } else if($_POST['status'] == 'Inactive'){
                    $data['status'] = 'I';
                }
                
            }

            $url = DOMAIN . '/rest/admin/GetUserlistScreenAdminRest.php';
            $output = getRestApiResponse($url, $data);

            $record = '';

            if (isset($output['getuserdetails']) && $output['getuserdetails']['response_code'] == 200) {
               $record .= <<< record
               <div class="container-fluid pt-4 pb-4" id="top">
               <div class="row">
                   <div class="col">
                       <form action="displaySellerLoginList.php" method="post">
                           <div class="input-group">
                               <input type="text" class="form-control" placeholder="User Id" name="user_id" style="border: medium solid black;margin-top:16px;">
                               <div class="input-group-append">
                                   <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                       <i class="fa fa-search"></i>
                                   </button>
                               </div>
                           </div>
                       </form>
                   </div>
       
                   <div class="col">
                       <form action="displaySellerLoginList.php" method="post">
                           <div class="input-group">
                               <input type="text" class="form-control" placeholder="Mobile Numbe" name="mobile" style="border: medium solid black;margin-top:16px;">
                               <div class="input-group-append">
                                   <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                       <i class="fa fa-search"></i>
                                   </button>
                               </div>
                           </div>
                       </form>
                   </div>
       
                   <div class="col">
                       <form action="displaySellerLoginList.php" method="post">
                           <div class="input-group">
                               <input type="text" class="form-control" placeholder="Username" name="username" style="border: medium solid black;margin-top:16px;">
                               <div class="input-group-append">
                                   <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                       <i class="fa fa-search"></i>
                                   </button>
                               </div>
                           </div>
                       </form>
                   </div>
       
                   <div class="col">
                       <form action="displaySellerLoginList.php" method="post">
                           <div class="input-group">
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
       
           
          
           <table class="table table-hover table-responsive-lg table-sm table-bordered table-fixed text-center"
           data-toggle="table"  
                    data-pagination-h-align="center"
                    data-pagination-detail-h-align="right"
                    data-side-pagination = "client"
                    data-page-size= "10"
                    data-pagination="true">
       
               <thead style='background-color : #36404e;color : white'>
                   <tr>
                       <th>User ID</th>
                       <th>Mobile Numbe</th>
                       <th>Username</th>
                       <th>Your Name</th>
                       <th>Status</th>
                       <th>Mobile Verified</th>
                       <th>Accepted T&C</th>
                       <th>Creation Date Time</th>
                       <th>Active/Block</th>
                   </tr>
               </thead>
       
               <tbody>
record;
                for ($i = 0; $i < $output['getuserdetails']['rows']; $i++) {
                    if ($output['getuserdetails'][$i]['status'] == 'A') {

                        $output['getuserdetails'][$i]['status'] = 'Active';
                    } else {
                        $output['getuserdetails'][$i]['status'] = 'Inactive';
                    }

                    $record .= <<< record
                    <tr>
                        <td>{$output['getuserdetails'][$i]['user_id']}</td>
                        <td>{$output['getuserdetails'][$i]['mobile']}</td>
                        <td>{$output['getuserdetails'][$i]['username']}</td>
                        <td>{$output['getuserdetails'][$i]['business_name']}</td>
                       
                        <td>{$output['getuserdetails'][$i]['status']}</td>
                        <td>{$output['getuserdetails'][$i]['mobile_verified']}</td>
                        <td>{$output['getuserdetails'][$i]['accept_terms_and_conditions']}</td>
                        <td>{$output['getuserdetails'][$i]['created_datetime']}</td>
record;
                    if ($button != 'disabled') {
                        if ($output['getuserdetails'][$i]['status'] == "Active") {
                            $record .= <<< record
        <td><input type = "button" data-toggle = "modal" data-target = "#confirmBlockModal" id = "{$output['getuserdetails'][$i]['user_id']}" value = "Block" class = "btn btn-warning" onclick="blockFun(this.id)"></td>
record;
                        }
                        if ($output['getuserdetails'][$i]['status'] == "Inactive") {
                            $record .= <<< record
        <td><input type = "button" data-toggle = "modal" data-target = "#confirmActivateModal" id = "{$output['getuserdetails'][$i]['user_id']}" value = "Activate" class = "btn btn-primary" onclick="activeFun(this.id)"></td>
record;
                        }
                    } else {
                        if ($output['getuserdetails'][$i]['status'] == "Active") {
                            $record .= <<< record
        <td><input type = "button" value = "Block" class = "btn btn-secondary" disabled readonly></td>
record;
                        }
                        if ($output['getuserdetails'][$i]['status'] == "Inactive") {
                            $record .= <<< record
        <td><input type = "button" value = "Activate" class = "btn btn-secondary" disabled readonly></td>
record;
                        }
                    }
                }
                echo $record;
            } else {
                if (isset($output['getuserdetails']) && $output['getuserdetails']['response_code'] == 405) {
                    
                        
                 
                    $record .= <<< record
              <h3 class="pt-5" style="color:red;text-align: center">{$output['getuserdetails']['response_desc']}</h3>
record;
                }
                echo $record;
            }
            ?>
        </tbody>
    </table>


    <!--Block Confirm Modal-->
    <div class="modal" id="confirmBlockModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        <i class="fa fa-pause" style="color:red" aria-hidden="true"></i>&nbsp;&nbsp;
                        <strong>Block Confirmation</strong>
                    </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    Do you really want to block this account?</div>
                <div class="modal-footer">
                    <input type="button" id="blockConfirmbtn" class="btn btn-success w-50 " onclick="blockactiveFun(this.id, 'Block')" value='Yes'>
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
                        <strong>Active Confirmation</strong>
                    </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="hiddenA">
                    Do you really want to active this account?</div>
                <div class="modal-footer">
                    <input type="submit" id="activeConfirmbtn" class="btn btn-success w-50" onclick="blockactiveFun(this.id, 'Activate')" value='Yes'>
                    <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Activate Confirm Modal -->



    
    <script>
        function blockactiveFun(userid, wbtn) {
            var domain = $("#getDOMAIN").val();
            $.post(domain + '/rest/admin/ActiveBlockSellerRest.php?user_id=' + userid + '&key=<?php echo md5(VALIDATION_KEY);?>',
                function(data, status) {
                    $("#confirm" + wbtn + "Modal").click();
                    if (data['updatestatus']['response_code'] == 200) {
                        $("#response").modal("show");
                        $("#restext").text(wbtn + " Successful");
                    } else {
                        $("#response").modal("show");
                        $("#restext").text(wbtn + " Unsuccessful");
                        $("#resdesc").text("ERROR : " + data['updatestatus']['response_desc']);
                    }
                });
            //location.reload(true);

        }

        function blockFun(id) {
            $("#blockConfirmbtn").attr("id", id);
        }

        function activeFun(id) {
            $("#activeConfirmbtn").attr("id", id);
        }
        $(document).ready(() => {
            if ( window.history.replaceState ) {
              window.history.replaceState( null, null, window.location.href );
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