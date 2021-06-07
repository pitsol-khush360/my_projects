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

$access = premissionScreen('CASH_MOVEMENTS', $_SESSION['current_user']);

$global = $access['global'];
$input = $access['input'];
$button = $access['button'];

if ($global != false) {
?>

    <?php
    $showinformation = 0;
    $message = "";
    ?>
    <!Doctype html>
    <html>

    <head>

    </head>
    <input type="hidden" value="<?php echo DOMAIN; ?>" id="getDOMAIN">

    <!-- Connecting with RestApi-->

    <?php

    $data = array();
    if (isset($_POST['cash_movement_id'])) {

        $data['cash_movement_id'] = $_POST['cash_movement_id'];
    } else if (isset($_POST['entry_side'])) {

        $data['entry_side'] = $_POST['entry_side'];
    } else if (isset($_POST['movement_type'])) {
        switch ($_POST['movement_type']) {
            case 'NET_AMOUNT':
                $data['Movement_type'] = '1';
                break;
            case 'DELIVERY_CHARGES':
                $data['Movement_type'] = '2';
                break;
            case 'PLATFORM_FEES':
                $data['Movement_type'] = '3';
                break;
            case 'GATEWAY_CHARGES':
                $data['Movement_type'] = '4';
                break;
        }
    } else if (isset($_POST['payment_reference'])) {

        $data['payment_reference'] = $_POST['payment_reference'];
    } else if (isset($_POST['movement_status'])) {
        switch ($_POST['movement_status']) {
            case 'Generated':
                $data['movement_status'] = '1';
                break;
            case 'Posted':
                $data['movement_status'] = '2';
                break;
            case 'Refund Pending':
                $data['movement_status'] = '3';
                break;
            case 'Ready for Settlement':
                $data['movement_status'] = '4';
                break;
            case 'Settled':
                $data['movement_status'] = '5';
                break;
            case 'Returned':
                $data['movement_status'] = '6';
                break;
        }
    } else if (isset($_POST['order_id'])) {
        $data['order_id'] = $_POST['order_id'];
    }

    $url = DOMAIN . '/rest/admin/AdminCashMovementListRest.php';
    $output = getRestApiResponse($url, $data);

    $record = '';

    if (isset($output['getcashmovement']) && $output['getcashmovement']['response_code'] == 200) {
        $record .= <<< record
                        <div class="container-fluid pt-4" >
                        <div class="row">
                            <div class="col">
                                <form action="displayAdminCashMovementsList.php" method="post">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cash Movement Id" name="cash_movement_id" style="border: medium solid black;margin-top:16px;">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
            
                            <div class="col">
                                <form action="displayAdminCashMovementsList.php" method="post">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Entry Side" name="entry_side" style="border: medium solid black;margin-top:16px;">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
            
                            <div class="col">
                                <form action="displayAdminCashMovementsList.php" method="post">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Movement Type" name="movement_type" style="border: medium solid black;margin-top:16px;">
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

                            <div class="container-fluid pb-1" >
                            <div class="row">
                            <div class="col">
                                <form action="displayAdminCashMovementsList.php" method="post">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Gateway Reference" name="payment_reference" style="border: medium solid black;margin-top:16px;">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
            
                            <div class="col">
                                <form action="displayAdminCashMovementsList.php" method="post">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Status" name="movement_status" style="border: medium solid black;margin-top:16px;">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col">
                                <form action="displayAdminCashMovementsList.php" method="post">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Order Id" name="order_id" style="border: medium solid black;margin-top:16px;">
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
                     <table class="table table-hover table-responsive-lg table-sm table-bordered text-center"
                     data-toggle="table"  
                    data-pagination-h-align="center"
                    data-pagination-detail-h-align="right"
                    data-side-pagination = "client"
                    data-page-size= "10"
                    data-pagination="true">
            
                         <thead style='background-color : #36404e;color : white'>
                             <tr>
                                 <th>Cash Movement Id</th>
                                 <th>Order Id</th>
                                 <th>Entry Side</th>
                                 <th class="text-right">Opening Balance</th>
                                 <th class="text-right">Transaction Amount</th>
                                 <th>Currency</th>
                                 <th>D/C</th>
                                 <th class="text-right">Closing Balance</th>
                                 <th>Movement Type</th>
                                 <th>Gateway Reference</th>
                                 <th>Status</th>
                                 <th>Movement Description</th>
                                 <th>Settled Amount</th>
                                 <th>Creation Date Time</th>
                             </tr>
                         </thead>
            
                         <tbody>
record;
        for ($i = 0; $i < $output['getcashmovement']['rows']; $i++) {
            $drcr = ($output['getcashmovement'][$i]['dr_cr_Indicator'] == 'D') ? 'Debit' : 'Credit';
            $movementtype;
            switch ($output['getcashmovement'][$i]['Movement_type']) {
                case '1':
                    $movementtype = "NET_AMOUNT";
                    break;
                case '2':
                    $movementtype = "DELIVERY_CHARGES";
                    break;
                case '3':
                    $movementtype = "PLATFORM_FEES";
                    break;
                case '4':
                    $movementtype = "GATEWAY_CHARGES";
                    break;
                case '5':
                    $movementtype = "WALLET_RECHARGE";
                    break;
            }
            $movementstatus;
            switch ($output['getcashmovement'][$i]['movement_status']) {
                case '1':
                    $movementstatus = "Generated";
                    break;
                case '2':
                    $movementstatus = "Posted";
                    break;
                case '3':
                    $movementstatus = "Refund Pending";
                    break;
                case '4':
                    $movementstatus = "Ready for Settlement";
                    break;
                case '5':
                    $movementstatus = "Settled";
                    break;
                case '6':
                    $movementstatus = "Returned";
                    break;
            }
            $record .= <<< record
                    <tr>
                        <td>{$output['getcashmovement'][$i]['cash_movement_id']}</td>
                        <td>{$output['getcashmovement'][$i]['order_id']}</td>
                        <td>{$output['getcashmovement'][$i]['entry_side']}</td>
                        <td class = "text-right">{$output['getcashmovement'][$i]['opening_balance']}</td>
                        <td class = "text-right">{$output['getcashmovement'][$i]['amount']}</td>
                        <td>{$output['getcashmovement'][$i]['amount_currency']}</td>
                        <td>{$drcr}</td>
                        <td class = "text-right">{$output['getcashmovement'][$i]['closing_balance']}</td>
                        <td>{$movementtype}</td>
                        <td>{$output['getcashmovement'][$i]['payment_reference']}</td>
                        <td>{$movementstatus}</td>
                        <td>{$output['getcashmovement'][$i]['movement_description']}</td>
                        <td>{$output['getcashmovement'][$i]['settled_amount']}</td>
                        <td>{$output['getcashmovement'][$i]['created_date_time']}</td>
                    </tr>      
record;
        }

        echo $record;
    } else {
        if (isset($output['getcashmovement']) && $output['getcashmovement']['response_code'] == 405) {
            $record .= <<< record
              <h3 class = "text-center pt-5" style = "color : red">{$output['getcashmovement']['response_desc']}</h3>
record;
        }
        echo $record;
    }
    ?>
    </tbody>
    </table>
    </div>



    <script>
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