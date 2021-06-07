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

$access = premissionScreen('BASKET_ORDERS', $_SESSION['current_user']);

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
        if (isset($_POST['basket_order_id'])) {
            $data['basket_order_id'] = $_POST['basket_order_id'];
        } else if (isset($_POST['order_type'])) {
            $data['order_type'] = $_POST['order_type'];
        } else if (isset($_POST['order_date'])) {
            $data['order_date'] = $_POST['order_date'];
        } else if (isset($_POST['seller_id'])) {
            $data['seller_id'] = $_POST['seller_id'];
        } else if (isset($_POST['order_status'])) {
            $data['order_status'] = $_POST['order_status'];
        } else if (isset($_POST['payment_reference'])) {
            $data['payment_reference'] = $_POST['payment_reference'];
        }
        $url = DOMAIN . '/rest/admin/GetAdminOrderListRest.php';
        $output = getRestApiResponse($url, $data);

        $record = '';

        if (isset($output['getorderdetails']) && $output['getorderdetails']['response_code'] == 200) {
            $record .= <<< record
                    <div class="container-fluid pt-4 pb-1" id="top">
                    <div class="row">
                        <div class="col">
                            <form action="displayAdminBasketOrdersList.php" method="post">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Order Id" name="basket_order_id" style="border: medium solid black;margin-top:16px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
        
                        <div class="col">
                            <form action="displayAdminBasketOrdersList.php" method="post">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Order Type" name="order_type" style="border: medium solid black;margin-top:16px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
        
                        <div class="col">
                            <form action="displayAdminBasketOrdersList.php" method="post">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Order Date" name="order_date" style="border: medium solid black;margin-top:16px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
        
                         
        
        
                    </div>
                    <div class="row">
                     
    
                    
    
                     
    
                    <div class="col">
                        <form action="displayAdminBasketOrdersList.php" method="post">
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
                        <form action="displayAdminBasketOrdersList.php" method="post">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Order Status" name="order_status" style="border: medium solid black;margin-top:16px;">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
    
                    <div class="col">
                        <form action="displayAdminBasketOrdersList.php" method="post">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Payment Reference" name="payment_reference" style="border: medium solid black;margin-top:16px;">
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
                            <th>Order Id</th>
                            <th>Order Type</th>
                            <th>Net Amount</th>
                            <th><span style="visibility:hidden">---</span>Order Date<span style="visibility:hidden">---</span></th>
                            <th>Seller Id</th>
                            <th>Gateway Status</th>
                            <th>Order Status</th>
                            <th>Payment Reference</th>
                            <th>Created Date Time</th>
                            <th>View Cash Movements</th>
                            <th>View Order Details</th>
                        </tr>
                    </thead>
        
                    <tbody>
record;
            for ($i = 0; $i < $output['getorderdetails']['rows']; $i++) {

                $record .= <<< record
                    <tr>
                        <td>{$output['getorderdetails'][$i]['basket_order_id']}</td>
                        <td>{$output['getorderdetails'][$i]['order_type']}</td>
                        <td class="text-right">{$output['getorderdetails'][$i]['net_amount']}</td>
                        <td>{$output['getorderdetails'][$i]['order_date']}</td>                        
                        <td>{$output['getorderdetails'][$i]['seller_id']}</td>
                        <td>{$output['getorderdetails'][$i]['payment_gateway_status']}</td>
                        <td>{$output['getorderdetails'][$i]['order_status']}</td>
                        <td>{$output['getorderdetails'][$i]['payment_reference']}</td>
                        <td>{$output['getorderdetails'][$i]['created_datetime']}</td>
                        <td><button type = "submit" data-toggle = "modal" data-target="#ViewCashMovementsModal" id = "{$output['getorderdetails'][$i]['basket_order_id']}" onclick = "setId(this.id)"  class = "btn btn-primary">View Cash Movements</button></td>
                        <td><button type = "submit" data-toggle = "modal" data-target="#ViewOrderDetailsModal" onclick = "setviewId(this.id)" id = "{$output['getorderdetails'][$i]['basket_order_id']}" class = "btn btn-primary apbtn">View Order Details</button></td>
                    </tr>      
record;
            }

            echo $record;
        } else {
            if (isset($output['getorderdetails']) && $output['getorderdetails']['response_code'] == 405) {
                $record .= <<< record
              <h3 class="pt-5" style="color:red; text-align : center">{$output['getorderdetails']['response_desc']}</h3>
record;
            }
            echo $record;
        }
        ?>
        </tbody>
        </table>










        <!-- View Cash Movement Modal-->
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
                        <h4 id="ifnorecordfound" class="text-danger" style="text-align: center;"></h4>
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

                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--End of View Cash Movement Modal-->


        <!-- View Order Details Modal-->
        <div class="modal fade" id="ViewOrderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">View Order Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h4 class="text-danger text-center" id="ifnorecordfoundview"></h4>
                        <table class="mt-4 table table-hover table-responsive-lg table-sm table-bordered table-fixed text-center">

                            <thead style='background-color : #36404e;color : white'>
                                <tr id="viewmodaltablehead">

                                </tr>
                            </thead>

                            <tbody id="viewmodaltable">
                                <!-- Connecting with RestApi-->


                            </tbody>
                        </table>
                         
                        
                    </div>
                    <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                </div>
            </div>
        </div>
        <!--End of View Order Details Modal-->







        <script>
            $(document).ready(() => {
                if (window.history.replaceState) {
                    window.history.replaceState(null, null, window.location.href);
                }
            });

            function setId(orderid) {
                console.log(orderid);
                var domain = $("#getDOMAIN").val();
                $.post(domain + '/rest/admin/GetBasketOrderCashMovementsRest.php?basket_order_id=' + orderid,
                    function(data, status) {

                        if (data['getordercashmovementdetails']['response_code'] == 200) {
                            $("#ifnorecordfound").text("");
                            $("#modaltablehead").append("<th>Cash Movement Id</th><th>Entry Side</th><th>Opening Balance</th><th>Transaction Amount</th><th>Currency</th><th>D/C</th><th>Closing Balance</th><th>Movement Type</th><th>Gateway Reference</th><th>Status</th><th>Movement Description</th><th>Settled Amount</th><th>Created Date Time</th>");
                            for ($i = 0; $i < data['getordercashmovementdetails']['rows']; $i++) {
                                var drcr = (data['getordercashmovementdetails'][$i]['dr_cr_Indicator'] == 'D') ? 'Debit' : 'Credit';
                                var movementtype;
                                switch (data['getordercashmovementdetails'][$i]['Movement_type']) {
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
                                        movementtype = "WALLET_RECHARGE";
                                        break;
                                }
                                var movementstatus;
                                switch (data['getordercashmovementdetails'][$i]['movement_status']) {
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
                                $("#modaltable").append("<tr><td>" + data['getordercashmovementdetails'][$i]['cash_movement_id'] + "</td><td>" + data['getordercashmovementdetails'][$i]['entry_side'] + "</td><td class='text-right'>" + data['getordercashmovementdetails'][$i]['opening_balance'] + "</td><td class='text-right'>" + data['getordercashmovementdetails'][$i]['amount'] + "</td><td>" + data['getordercashmovementdetails'][$i]['amount_currency'] + "</td><td>" + drcr + "</td><td class='text-right'>" + data['getordercashmovementdetails'][$i]['closing_balance'] + "</td><td>" + movementtype + "</td><td>" + data['getordercashmovementdetails'][$i]['payment_reference'] + "</td><td>" + movementstatus + "</td><td>" + data['getordercashmovementdetails'][$i]['movement_description'] + "</td><td>" + data['getordercashmovementdetails'][$i]['settled_amount'] + "</td><td>" + data['getordercashmovementdetails'][$i]['created_date_time'] + "</td></tr> ");
                            }
                        }
                        if (data['getordercashmovementdetails']['response_code'] == 405) {
                            $("#ifnorecordfound").text("No Records Found");
                        }

                    });


            }

            function setviewId(orderid) {
                console.log(orderid);
                var domain = $("#getDOMAIN").val();
                $.post(domain + '/rest/admin/BuyerOrderDetailsAdminRest.php?basket_order_id=' + orderid,
                    function(data, status) {
                        console.log(data);
                        if (data['getorderdetails']['response_code'] == 200) {
                            $("#ifnorecordfoundview").text("");
                            $("#viewmodaltablehead").append("<th>Order Id</th><th>Quantity</th><th>Order Amount</th><th>Order Date</th><th>Seller Id</th><th>Product Id</th><th>Collection Id</th>");
                            for ($i = 0; $i < data['getorderdetails']['rows']; $i++) {

                                $("#viewmodaltable").append("<tr><td>" + data['getorderdetails'][$i]['order_id'] + "</td><td>" + data['getorderdetails'][$i]['order_quantity'] + "</td><td  class='text-right'>" + data['getorderdetails'][$i]['order_amount_total'] + "</td><td>" + data['getorderdetails'][$i]['order_date'] + "</td><td>" + data['getorderdetails'][$i]['seller_id'] + "</td><td>" + data['getorderdetails'][$i]['product_id'] + "</td><td>" + data['getorderdetails'][$i]['catalogue_id'] + "</td></tr> ");
                            }
                        }
                        if (data['getorderdetails']['response_code'] == 405) {
                            $("#ifnorecordfoundview").text("No Records Found");
                        }

                    });


            }
            $('#ViewOrderDetailsModal').on('hidden.bs.modal', function() {

                $("#viewmodaltable").empty();
                $("#viewmodaltablehead").empty();

            });

            $('#ViewCashMovementsModal').on('hidden.bs.modal', function() {

                $("#modaltable").empty();
                $("#modaltablehead").empty();

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