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

    $access = premissionScreen('SCREEN_PERMISSIONS', $_SESSION['current_user']);

    $global = $access['global'];
    $input = $access['input'];
    $button = $access['button'];

    if ($global != 0) {
    ?>
    
     <input type="hidden" value="<?php echo DOMAIN; ?>" id="getDOMAIN">
     <div class="col pt-4" style="float: right;width: 30%;">
         <?php
            if ($button != "disabled") {
            ?>
             <button class='btn btn-primary mt-3 mb-3 w-50' style='float : right;' data-toggle="modal" data-target="#addPermission">Add</button>
         <?php
            } else {
            ?>
             <button class='btn btn-secondary mt-3 mb-3 w-50' style='float : right;' disabled>Add</button>
         <?php
            }
            ?>
     </div>





     <!-- Connecting with RestApi-->

     <?php

        $data = array();

        if (isset($_POST['searchfieldRoleId'])) {
            $data['id'] = $_POST['searchfieldRoleId'];
        } else if (isset($_POST['searchfieldRoleName'])) {
            $data['ROLE_NAME'] = $_POST['searchfieldRoleName'];
        } 
        $url = DOMAIN . '/rest/admin/GetScreenPermissionsStaticRest.php';
        $output = getRestApiResponse($url, $data);

        $record = '';

        if (isset($output['getpermissiondetails']) && $output['getpermissiondetails']['response_code'] == 200) {

            $record .= <<< record
            <div class="container-fluid pt-4 pb-2">
            <div class="row">
                <div class="col-4" id="top">
                    <form action="displayAdminScreenPermissionList.php" method="post">
   
   
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Role Id" name="searchfieldRoleId" style="border: medium solid black;margin-top:16px;">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-4" id="top">
                    <form action="displayAdminScreenPermissionList.php" method="post">
   
   
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Role Name" name="searchfieldRoleName" style="border: medium solid black;margin-top:16px;">
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
                 <th>Role ID</th>
                 <th>Screen Name</th>
                 <th>Permission</th>
                 <th>Role Name</th>
                 <th>Delete</th>
             </tr>
         </thead>

         <tbody>
record;
            for ($i = 0; $i < $output['getpermissiondetails']['rows']; $i++) {

                $record .= <<< record
                    <tr>
                        <td>{$output['getpermissiondetails'][$i]['id']}</td>
                        <td>{$output['getpermissiondetails'][$i]['screen_name']}</td>
                        <td>{$output['getpermissiondetails'][$i]['permission_name']}</td>
                        <td>{$output['getpermissiondetails'][$i]['ROLE_NAME']}</td>
record;
                if ($button != "disabled") {
                    $record .= <<< record
                <td><button type = "button" data-toggle="modal" data-target="#confirmDelete" id =  "{$output['getpermissiondetails'][$i]['id']}" onclick="setID(this.id)" class = "btn" style = "border : 2px solid red;color : red"><i class="fa fa-trash"></i></button></td>
            </tr>      
record;
                } else {
                    $record .= <<< record
                <td><button type = "button" disabled class = "btn btn-secondary"><i class="fa fa-trash"></i></button></td>
            </tr>      
record;
                }
            }

            echo $record;
        } else {
            if (isset($output['getpermissiondetails']) && $output['getpermissiondetails']['response_code'] == 405) {

                $record .= <<< record
              <h3 class="text-center pt-5" style = "color:red">{$output['getpermissiondetails']['response_desc']}</h3>
record;
            }
            echo $record;
        }
        ?>
     </tbody>
     </table>


     <!--Add Permission Modal-->
     <div class="modal fade" id="addPermission" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="exampleModalLabel">Create Screen Permissions</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
                 <div class="modal-body">
                     <form action='displayAdminScreenPermissionList.php' method='POST'>
                         <div class="form-group">
                             <label>Screen Name</label>
                             <!-- <input type="text" name="screenname" id="screenName" style="float : right; border : 1px solid #549eff; margin-left : 5px"> -->
                             <select id="screenName" placeholder="choose one" style='float:right;border: 1px solid black' <?php echo $input; ?>>
                                 <option value="ADMIN_APPLICATION_">ADMIN_APPLICATION</option>
                                 <option value="DASHBOARD_">DASHBOARD</option>
                                 <option value="ADMIN_USER_LIST_">ADMIN_USER_LIST</option>
                                 <option value="SCREEN_PERMISSIONS_">SCREEN_PERMISSIONS</option>
                                 <option value="COLLECTIONS_">COLLECTIONS</option>
                                 <option value="COLLECTION_LIBRARY_">COLLECTION_LIBRARY</option>
                                 <option value="PRODUCTS_">PRODUCTS</option>
                                 <option value="PRODUCT_DEFAULT_">PRODUCT_DEFAULT</option>
                                 <option value="BASKET_ORDERS_">BASKET_ORDERS</option>
                                 <option value="CASH_MOVEMENTS_">CASH_MOVEMENTS</option>
                                 <option value="SELLER_LIST_">SELLER_LIST</option>
                                 <option value="SELLER_PROFILE_">SELLER_PROFILE</option>
                                 <option value="SELLER_REVIEWS_">SELLER_REVIEWS</option>
                                 <option value="SHIPPING_CHARGES_">SHIPPING_CHARGES</option>
                                 <option value="PROMO_CODES_">PROMO_CODES</option>
                                 <option value="TICKETS_">TICKETS</option>
                                 <option value="WALLET_BALANCE_">WALLET_BALANCE</option>
                                 <option value="WALLET_ORDERS_">WALLET_ORDERS</option>
                                 <option value="COMMISSION_CHARGES_">COMMISSION_CHARGES</option>
                             </select>

                         </div>

                         <div class="form-group">
                             <label>Permission</label>

                             <select name="permission" id="keywordPermission" placeholder="choose one" style='float : right' <?php echo $input; ?>>
                                 <option value="READ_ONLY">READ ONLY</option>
                                 <option value="READ_AND_WRITE">READ AND WRITE </option>

                             </select>
                         </div>

                         <div class="form-group">
                             <label>Role Name</label>
                             <input type="text" readonly id="addRoleName" name="roleName" style="float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>

                         </div>
                         <!--Confirm Modal-->
                         <div class="modal" id="confirmpermissionModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
                             <div class="modal-dialog modal-dialog-centered " role="document">
                                 <div class="modal-content">
                                     <div class="modal-header">
                                         <h3 class="modal-title"><i class="fa fa-check-circle" style="color:green" aria-hidden="true"></i>&nbsp;&nbsp;<strong>Save Confirmation
                                             </strong></h3>
                                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                             <span aria-hidden="true">&times;</span>
                                         </button>

                                     </div>
                                     <div class="modal-footer">
                                         <?php
                                            if ($button != 'disabled') {

                                            ?>
                                             <input type="button" class="btn btn-success addpermissionscreen" value='Yes'>
                                             <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                                         <?php
                                            } else {
                                            ?>
                                             <input type="button" disabled class="btn btn-secondary" value='Yes'>
                                             <button type="button" disabled class="btn btn-secondary">No</button>
                                         <?php
                                            }
                                            ?>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <!-- End of Confirm Modal -->


                         <hr>
                         <div class=" text-center">
                             <button type="button" class="btn btn-primary  " data-toggle="modal" data-target="#confirmpermissionModal">Save</button>
                             <button type="button" class="btn btn-danger  " data-dismiss="modal">Cancel</button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>

     <!--End of add Permission Modal-->




     <input type="hidden" id="getID">
     <!--Delete Confirm Modal-->
     <div class="modal" id="confirmDelete" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
         <div class="modal-dialog modal-dialog-centered " role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h3 class="modal-title"><i class="fa fa-trash" style="color:red" aria-hidden="true"></i>&nbsp;&nbsp;<strong>Confirm ?
                         </strong></h3>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
                 <div class="modal-footer">
                     <input type="submit" class="btn btn-success w-50 dltbtn" value='Yes'>
                     <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                 </div>
             </div>
         </div>
     </div>
     <!-- End of Delete Confirm Modal -->















     <script>
         $(document).ready(() => {
             if (window.history.replaceState) {
                 window.history.replaceState(null, null, window.location.href);
             }
         });
         $(".dltbtn").click(() => {

             var domain = $("#getDOMAIN").val();

             let id = $("#getID").val();

             $.post(domain + '/rest/admin/DeleteScreenPermissionsRest.php?id=' + id + '&key=<?php echo md5(VALIDATION_KEY);?>',
                 function(data, status) {
                     console.log(data);
                     $("#confirmDelete").click();
                     if (data['deletescreenpermission']['response_code'] == 200) {
                         $("#response").modal("show");
                         $("#restext").text("Deletion Successful");
                         $("#resdesc").text("");
                     } else {
                         $("#response").modal("show");
                         $("#restext").text("Deletion Unsuccessful");
                         $("#resdesc").text(data['deletescreenpermission']['response_desc']);
                     }
                 });

         });

         $("#load").click(() => {
             location.reload(true);
         });

         function setID(id) {
             $("#getID").val(id);
         }

         $(".addpermissionscreen").click(() => {
             $("#addPermission").click();
             var domain = $("#getDOMAIN").val();
             let screenName = $('#screenName option:selected').attr("value");
             let screenName1 = $('#screenName option:selected').text();

             let permissionName = $('#keywordPermission option:selected').attr("value");
             let roleName = screenName + permissionName;

             let data = "screen_name=" + screenName1 + "&permission_name=" + permissionName + "&ROLE_NAME=" + roleName + '&key=<?php echo md5(VALIDATION_KEY);?>';

             $.post(domain + '/rest/admin/CreateScreenPermissionStaticRest.php?' + data,
                 function(data, status) {


                     if (data['addpermission']['response_code'] == 200) {

                         $("#confirmpermissionModal").click();
                         $("#response").modal("show");
                         $("#restext").text("Addition Successful");
                     } else {
                         $("#response").modal("show");
                         $("#restext").text("Addition Unsuccessful");
                         $("#resdesc").text(data['addpermission']['response_desc']);
                     }
                 });

         });
     </script>

     </body>

     </html>
 <?php
    } else {
        echo '<h2 class= "pt-5" style="text-align:center;color:red;margin-top:4rem;">Permission Denied!</h2>';
    }
    ?>

 <?php include("footer.php"); ?>