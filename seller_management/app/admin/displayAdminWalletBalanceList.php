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

$access = premissionScreen('WALLET_BALANCE', $_SESSION['current_user']);

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

  

 <body>
     <input type="hidden" value="<?php echo DOMAIN; ?>" id="getDOMAIN">
    
                 <!-- Connecting with RestApi-->

                 <?php

                    $data = array();
                    if ( isset($_POST['seller_id'])) {
                        $data['seller_id'] = $_POST['seller_id'];
                    }else if ( isset($_POST['value_date'])) {
                        $data['value_date'] = $_POST['value_date'];
                    }

                    $url = DOMAIN . '/rest/admin/GetWalletBalancelistScreenRest.php';
                    $output = getRestApiResponse($url, $data);

                    $record = '';

                    if (isset($output['getwalletdetails']) && $output['getwalletdetails']['response_code'] == 200) {
                        $record .= <<< record
                        <div class="container-fluid pt-3">
                        <div class="row">
                            <div class="col">
                                <form action="displayAdminWalletBalanceList.php" method="post">
                                    <div class="input-group w-50" >
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
                                <form action="displayAdminWalletBalanceList.php" method="post">
               
                                    <div class="input-group w-50" >
                                        <input type="text" class="form-control" placeholder="Value Date" name="value_date" style="border: medium solid black;margin-top:16px;">
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
                        <table class=" table table-hover table-responsive-lg table-sm table-bordered table-fixed text-center" 
                        data-toggle="table"  
                    data-pagination-h-align="center"
                    data-pagination-detail-h-align="right"
                    data-side-pagination = "client"
                    data-page-size= "10"
                    data-pagination="true">
               
                            <thead style='background-color : #36404e;color : white'>
                                <tr>
                                    <th>Seller Id</th>
                                    <th>Value Date</th>
                                    <th>Opening Balance</th>
                                    <th>Closing Balance</th>
                                    <th>Currency</th>
                                </tr>
                            </thead>
               
                            <tbody>
record;
                        for ($i = 0; $i < $output['getwalletdetails']['rows']; $i++) {

                            $record .= <<< record
                    <tr >
                        <td data-align = "center">{$output['getwalletdetails'][$i]['seller_id']}</td>
                        <td data-align = "center">{$output['getwalletdetails'][$i]['value_date']}</td>
                        <td class = "text-right">{$output['getwalletdetails'][$i]['opening_balance']}</td>
                        <td class = "text-right">{$output['getwalletdetails'][$i]['closing_balance']}</td>
                       
                        <td data-align = "center">{$output['getwalletdetails'][$i]['balance_currency']}</td>
record;
                        }
                    } else {
                        $record .= <<< record
              <h3 class="text-center pt-5" style = "color:red;">{$output['getwalletdetails']['response_desc']}</h3>
record;
                    }
                    echo $record;

                    ?>
             </tbody>
         </table>
     </div>

    
<script>
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
