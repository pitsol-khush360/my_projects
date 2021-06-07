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

$access = premissionScreen('SELLER_PROFILE', $_SESSION['current_user']);

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
    if (isset($_POST['seller_id'])) {
        $data['seller_id'] = $_POST['seller_id'];
    } else if (isset($_POST['kyc_application_status'])) {
        $data['kyc_application_status'] = $_POST['kyc_application_status'];
    } else if (isset($_POST['kyc_completed'])) {
        $data['kyc_completed'] = $_POST['kyc_completed'];
    } else if (isset($_POST['accept_online_payments'])) {
        if ($_POST['accept_online_payments'] == "Enabled") {
            $data['accept_online_payments'] = "1";
        } else {
            $data['accept_online_payments'] = "0";
        }
    } else if (isset($_POST['gst_verified'])) {
        $data['gst_verified'] = $_POST['gst_verified'];
    } else if (isset($_POST['logistics_integrated'])) {
        $data['logistics_integrated'] = $_POST['logistics_integrated'];
    } else if (isset($_POST['seller_alternate_numbe'])) {
        $data['seller_alternate_numbe'] = $_POST['seller_alternate_numbe'];
    }

    $url = DOMAIN . '/rest/admin/GetUserlistScreenSellerRest.php';
    $output = getRestApiResponse($url, $data);

    $record = '';

    if (isset($output['getsellerdetails']) && $output['getsellerdetails']['response_code'] == 200) {
        $record .= <<< record
                    <div class="container-fluid pt-4" >
                    <div class="row">
                        <div class="col">
                            <form action="displaySellerProfileList.php" method="POST">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Seller Id" name="seller_id" style="border: medium solid black;margin-top:16px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
            
                        <div class="col">
                            <form action="displaySellerProfileList.php" method="POST">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="KYC Application Status" name="kyc_application_status" style="border: medium solid black;margin-top:16px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
            
                        <div class="col">
                            <form action="displaySellerProfileList.php" method="POST">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="KYC Completed" name="kyc_completed" style="border: medium solid black;margin-top:16px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
            
                        <div class="col">
                            <form action="displaySellerProfileList.php" method="POST">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Online Payments" name="accept_online_payments" style="border: medium solid black;margin-top:16px;">
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
            
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-3">
                            <form action="displaySellerProfileList.php" method="POST">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="GST Verified" name="gst_verified" style="border: medium solid black;margin-top:16px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
            
                        <div class="col-3">
                            <form action="displaySellerProfileList.php" method="POST">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Logistics Integrated" name="logistics_integrated" style="border: medium solid black;margin-top:16px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
            
                        <div class="col-3">
                            <form action="displaySellerProfileList.php" method="POST">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Contact Number" name="seller_alternate_numbe" style="border: medium solid black;margin-top:16px;">
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
            
            
               
            
                <div class="pt-3">
                    <table class="table table-hover table-responsive-lg table-sm table-bordered table-fixed text-center"
                    data-toggle="table"  
                    data-pagination-h-align="center"
                    data-pagination-detail-h-align="right"
                    data-side-pagination = "client"
                    data-page-size= "10"
                    data-pagination="true">
            
                        <thead style='background-color : #36404e;color : white' id="top2">
                            <tr>
                                <th>Seller Id</th>
                                <th>Business Name</th>
                                <th>KYC Application Status</th>
                                <th>KYC Completed</th>
                                <th>Online Payments</th>
                                <th>GST Verified</th>
                                <th>Logistics Integrated</th>
                                <th>Contact Number</th>
                                <th></th>
                                <th>View</th>
                            </tr>
                        </thead>
            
                        <tbody>
record;
        for ($i = 0; $i < $output['getsellerdetails']['rows']; $i++) {

            $record .= <<< record
                    <tr>
                        <td>{$output['getsellerdetails'][$i]['seller_id']}</td>
                        <td>{$output['getsellerdetails'][$i]['seller_business_name']}</td>
                        <td>{$output['getsellerdetails'][$i]['kyc_application_status']}</td>
record;
            if ($output['getsellerdetails'][$i]['kyc_completed'] == 1) {
                $record .= <<< record
                        <td>Yes</td>
record;
            }
            if ($output['getsellerdetails'][$i]['kyc_completed'] == 0) {
                $record .= <<< record
                        <td>No</td>
record;
            }
            if ($output['getsellerdetails'][$i]['accept_online_payments'] == 1) {
                $record .= <<< record
                        <td>Enabled</td>
record;
            }
            if ($output['getsellerdetails'][$i]['accept_online_payments'] == 0) {
                $record .= <<< record
                        <td>Disabled</td>
record;
            }
            $record .= <<< record
                        <td>{$output['getsellerdetails'][$i]['gst_verified']}</td>
                        <td>{$output['getsellerdetails'][$i]['logistics_integrated']}</td>                        
                        <td>{$output['getsellerdetails'][$i]['seller_alternate_number']}</td>
record;

            $record .= <<< record
    <td><input type = "button" value ="⇲" data-toggle="modal"  data-target="#popupModal" id = "{$output['getsellerdetails'][$i]['seller_id']}" onclick="popupFun(this.id)"></td>
    <td><form action = "displaySellerProfileListnewPage.php" method = "POST"><input type = "hidden" name = "sellerIDnewpage" value = "{$output['getsellerdetails'][$i]['seller_id']}"><button type = "submit" class = "btn btn-primary">View</button></form></td>
</tr>      
record;
        }

        echo $record;
    } else {
        if (isset($output['getsellerdetails']) && $output['getsellerdetails']['response_code'] == 405) {

            $record .= <<< record
              <h3 class="pt-5" style = "text-align:center; color:red">{$output['getsellerdetails']['response_desc']}</h3>
record;
        }
        echo $record;
    }
    ?>
    </tbody>
    </table>



    </div>






    <!-- View Detail Modal-->
    <div class="modal fade" id="popupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delivery Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="displaySellerProfielList.php" method="POST">
                        <div class="form-group">
                            <label>Delivery Charge</label>
                            <input id="viewdeleverycharges" readonly type="text" style="background-color : rgba(128, 128, 128, 0.555);float : right; border : 1px solid #549eff; margin-left : 5px">
                        </div>

                        <div class="form-group">
                            <label> Free for Orders</label>
                            <input id="viewffo" readonly type="text" style="background-color : rgba(128, 128, 128, 0.555);float : right; border : 1px solid #549eff; margin-left : 5px ">
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="margin: auto;">
                    <button type="submit" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!--End of View Detail Modal-->







    <script>
        function popupFun(sellerid) {
            console.log(sellerid);
            var domain = $("#getDOMAIN").val();
            $.post(domain + '/rest/admin/SellerDeliveryChargeOnlyRest.php?seller_id=' + sellerid + '&key=<?php echo md5(VALIDATION_KEY);?>',
                function(data, status) {
                    console.log(data);
                    $("#viewdeleverycharges").val(data["getdeliverychargesdetails"][0]["delivery_charge"]);
                    $("#viewffo").val(data["getdeliverychargesdetails"][0]["delivery_free_above"]);
                });
        }
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