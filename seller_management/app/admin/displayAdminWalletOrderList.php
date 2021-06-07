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

$access = premissionScreen('WALLET_ORDERS', $_SESSION['current_user']);

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

 <head>
     <style>
         table.table-bordered>tbody>tr>td {
             border: 1px solid black;
         }

         .modal-body {
             overflow-x: auto;
         }

         .modal-lg {
             max-width: 90% !important;
         }
     </style>
 </head>

 <body>
 <input type="hidden" value="<?php echo DOMAIN; ?>" id="getDOMAIN">
    
             <!-- Connecting with RestApi-->

             <?php

                $data = array();
                if (isset($_POST['seller_id'])) {
                    $data['seller_id'] = $_POST['seller_id'];
                } else if (isset($_POST['order_id'])) {
                    $data['order_id'] = $_POST['order_id'];
                } else if (isset($_POST['payment_reference'])) {
                    $data['payment_reference'] = $_POST['payment_reference'];
                } else if (isset($_POST['order_status'])) {
                    $data['order_status'] = $_POST['order_status'];
                } 
                $url = DOMAIN . '/rest/admin/GetWalletOrderlistScreenRest.php';
                $output = getRestApiResponse($url, $data);

                $record = '';

                if (isset($output['getwalletdetails']) && $output['getwalletdetails']['response_code'] == 200) {
                    $record .= <<< record
                    <div class="container-fluid pt-4">
                    <div class="row">
                        <div class="col">
                            <form action="displayAdminWalletOrderList.php" method="post">
                                <div class="input-group" style="float:right;margin-right:20px">
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
                            <form action="displayAdminWalletOrderList.php" method="post">
                                <div class="input-group" style="float:right;margin-right:20px">
                                    <input type="text" class="form-control" placeholder="Order Id" name="order_id" style="border: medium solid black;margin-top:16px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
           
                        <div class="col">
                            <form action="displayAdminWalletOrderList.php" method="post">
                                <div class="input-group" style="float:right;margin-right:20px">
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
                            <form action="displayAdminWalletOrderList.php" method="post">
                                <div class="input-group" style="float:right;margin-right:20px">
                                    <input type="text" class="form-control" placeholder="Status" name="order_status" style="border: medium solid black;margin-top:16px;">
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
                
                
                <table class="mt-4 table table-hover table-responsive-lg table-sm table-bordered table-fixed text-center"
                data-toggle="table"  
                    data-pagination-h-align="center"
                    data-pagination-detail-h-align="right"
                    data-side-pagination = "client"
                    data-page-size= "10"
                    data-pagination="true">
           
                    <thead style='background-color : #36404e;color : white'>
                        <tr>
                            <th>Seller Id</th>
                            <th>Order Id</th>
                            <th>Mobile</th>
                            <th>Opening Balance</th>
                            <th>Closing Balance</th>
                            <th>Gateway Reference</th>
                            <th>Status</th>
                            <th>Gateway Response</th>
                            <th>Txn Date Time</th>
                            <th>View Cash Movements</th>
                        </tr>
                    </thead>
           
                    <tbody>
record;
                    for ($i = 0; $i < $output['getwalletdetails']['rows']; $i++) {

                        $record .= <<< record
                    <tr>
                        <td>{$output['getwalletdetails'][$i]['seller_id']}</td>
                        <td>{$output['getwalletdetails'][$i]['order_id']}</td>
                        <td class="text-right">{$output['getwalletdetails'][$i]['amount']}</td>
                        <td class="text-right">{$output['getwalletdetails'][$i]['wallet_opening_balance']}</td>                        
                        <td class="text-right">{$output['getwalletdetails'][$i]['wallet_closing_balance']}</td>
                        <td>{$output['getwalletdetails'][$i]['payment_reference']}</td>
                        <td>{$output['getwalletdetails'][$i]['order_status']}</td>
                        <td>{$output['getwalletdetails'][$i]['gateway_response_status']}</td>
                        <td>{$output['getwalletdetails'][$i]['created_date_time']}</td>                        
                        <td><button type = "submit" data-toggle = "modal" data-target="#ViewCashMovementsModal" onclick = "setId(this.id)" id = "{$output['getwalletdetails'][$i]['order_id']}" data-toggle="modal" data-target="#apModal" class = "btn btn-primary apbtn">View Cash Movements</button></td>
                    </tr>      
record;
                    }

                    echo $record;
                } else {
                    if (isset($output['getwalletdetails']) && $output['getwalletdetails']['response_code'] == 405) {
                        $record .= <<< record
              <h3 class="text-center pt-5" style = "color:red;">{$output['getwalletdetails']['response_desc']}</h3>
record;
                    }
                    echo $record;
                }
                ?>
         </tbody>
     </table>










     <!-- View Detail Modal-->
     <div class="modal fade" id="ViewCashMovementsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="exampleModalLabel">View Cash Movements</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
                 <div class="modal-body">
                     <h3 class="text-danger text-center" id="ifnorecordfound"></h3>
                     <table class="mt-4 table table-hover table-responsive-lg table-sm table-bordered table-fixed text-center">

                         <thead style='background-color : #36404e;color : white'>
                             <tr id="modaltablehead">
                                
                             </tr>
                         </thead>

                         <tbody id="modaltable">
                             <!-- Connecting with RestApi-->


                         </tbody>
                     </table>
                    
                 </div>
                 <div class="modal-footer">
                 <div class="modal-footer" style="margin: auto;">
                         <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                     </div>
                 </div>

             </div>
         </div>
     </div>
     <!--End of View Detail Modal-->






     

     <script>
         function setId(orderid) {
             console.log(orderid);
             var domain = $("#getDOMAIN").val();
             $.post(domain + '/rest/admin/GetWalletOrderCashMovementsRest.php?order_id=' + orderid + '&key=<?php echo md5(VALIDATION_KEY);?>',
                 function(data, status) {
                     console.log(data);
                     if (data['getwalletcashmovementdetails']['response_code'] == 200) {
                        $("#ifnorecordfound").text("");
                         $("#modaltablehead").append(" <th>Cash Movement Id</th><th>Entry Side</th><th>Opening Balance</th><th>Transaction Amount</th><th>Currency</th><th>D/C</th><th>Closing Balance</th><th>Movement Type</th><th>Gateway Reference</th><th>Status</th><th>Movement Description</th><th>Settled Amount</th><th>Created Date Time</th>");
                         for ($i = 0; $i < data['getwalletcashmovementdetails']['rows']; $i++) {
                             var drcr = (data['getwalletcashmovementdetails'][$i]['dr_cr_Indicator'] == 'D') ? 'Debit' : 'Credit';
                             var movementtype;
                             switch (data['getwalletcashmovementdetails'][$i]['Movement_type']) {
                                 case '1':
                                     movementtype = "NET_AMOUNT";
                                     break;
                                 case '2':
                                     movementtype = "DELIVERY_CHARGES";
                                     break;
                                 case '3':
                                     movementtype = "PLATFORM_FEES";
                                     break;
                                 case '4':
                                     movementtype = "GATEWAY_CHARGES";
                                     break;
                                case '5':
                                     movementtype = "Wallet Recharge";
                                     break;
                             }
                             var movementstatus;
                             switch (data['getwalletcashmovementdetails'][$i]['movement_status']) {
                                 case '1':
                                     movementstatus = "Generated";
                                     break;
                                 case '2':
                                     movementstatus = "Posted";
                                     break;
                                 case '3':
                                     movementstatus = "Refund Pending";
                                     break;
                                 case '4':
                                     movementstatus = "Ready for Settlement";
                                     break;
                                 case '5':
                                     movementstatus = "Settled";
                                     break;
                                 case '6':
                                     movementstatus = "Returned";
                                     break;
                                 

                             }
                             $("#modaltable").append("<tr><td>" + data['getwalletcashmovementdetails'][$i]['cash_movement_id'] + "</td><td>" + data['getwalletcashmovementdetails'][$i]['entry_side'] + "</td><td>" + data['getwalletcashmovementdetails'][$i]['opening_balance'] + "</td><td>" + data['getwalletcashmovementdetails'][$i]['amount'] + "</td><td>" + data['getwalletcashmovementdetails'][$i]['amount_currency'] + "</td><td>" + drcr + "</td><td>" + data['getwalletcashmovementdetails'][$i]['closing_balance'] + "</td><td>" + movementtype + "</td><td>" + data['getwalletcashmovementdetails'][$i]['payment_reference'] + "</td><td>" + movementstatus + "</td><td>" + data['getwalletcashmovementdetails'][$i]['movement_description'] + "</td><td>" + data['getwalletcashmovementdetails'][$i]['settled_amount'] + "</td><td>" + data['getwalletcashmovementdetails'][$i]['created_date_time'] + "</td></tr> ");
                         }
                     }if (data['getwalletcashmovementdetails']['response_code'] == 405) {
                            $("#ifnorecordfound").text("No Records Found");
                        }

                 });


         }
         $('#ViewCashMovementsModal').on('hidden.bs.modal', function() {
             console.log("Modal Hide");
             $("#modaltable").empty();
             $("#modaltablehead").empty();

         });
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
    echo '<h2 class="pt-5" style="text-align:center;color:red;margin-top:4rem;">Permission Denied!</h2>';
}
?>
<?php include("footer.php"); ?>
