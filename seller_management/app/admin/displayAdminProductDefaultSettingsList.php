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

    $access = premissionScreen('PRODUCT_DEFAULT', $_SESSION['current_user']);

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



     <!-- Connecting with RestApi-->

     <?php

        $data = array();
        if (isset($_POST['seller_id'])) {

            $data['seller_id'] = $_POST['seller_id'];
        }

        $url = DOMAIN . '/rest/admin/AdminProductDefaultsListRest.php';
        $output = getRestApiResponse($url, $data);

        $record = '';

        if (isset($output['getdefaultproductdetails']) && $output['getdefaultproductdetails']['response_code'] == 200) {

            $record .= <<< record
                        <div class="container-fluid pt-3">
     <input type="hidden" value="<?php echo DOMAIN; ?>" id="getDOMAIN">
     <form action="displayAdminProductDefaultSettingsList.php" method="post">
          
         <div class="input-group" style="width: 25%;">
             <input type="text" class="form-control" placeholder="Seller Id" name="seller_id" style="border: medium solid black;margin-top:16px;">
             <div class="input-group-append">
                 <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                     <i class="fa fa-search"></i>
                 </button>
             </div>
         </div>
     </form>
     </div>
     <div class="pt-3">
         <table class="table table-hover table-responsive-lg table-sm table-bordered table-fixed text-center"
         data-toggle="table"  
         data-pagination-h-align="center"
         data-pagination-detail-h-align="right"
         data-side-pagination = "client"
         data-page-size= "10"
         data-pagination="true">

             <thead style='background-color : #36404e;color : white'>
                 <tr>
                     <th>Seller Id</th>
                     <th>Product Category</th>
                     <th>Discount Type</th>
                     <th>Discount Value</th>
                     <th>Tax Type</th>
                     <th>Tax Percent</th>
                     <th>Warranty Type</th>
                     <th>Warranty Duration</th>
                     <th>Warranty Days</th>
                 </tr>
             </thead>

             <tbody>
record;
            for ($i = 0; $i < $output['getdefaultproductdetails']['rows']; $i++) {

                $record .= <<< record
                    <tr >
                        <td >{$output['getdefaultproductdetails'][$i]['seller_id']}</td>
                        <td>{$output['getdefaultproductdetails'][$i]['product_category']}</td>
                        <td>{$output['getdefaultproductdetails'][$i]['discount_type']}</td>
                        <td>{$output['getdefaultproductdetails'][$i]['discount_percent']}</td>
                        <td>{$output['getdefaultproductdetails'][$i]['tax_type']}</td>                        
                        <td>{$output['getdefaultproductdetails'][$i]['tax_percent']}</td>
                        <td>{$output['getdefaultproductdetails'][$i]['warrant_type']}</td>
                        <td>{$output['getdefaultproductdetails'][$i]['warrant_duration']}</td>                        
                        <td>{$output['getdefaultproductdetails'][$i]['warranty_days_mon_yr']}</td>
                    </tr>      
record;
            }

            echo $record;
        } else {
            if (isset($output['getdefaultproductdetails']) && $output['getdefaultproductdetails']['response_code'] == 405) {
                $record .= <<< record
              <h3 class="text-center pt-5" style ="color : red;">{$output['getdefaultproductdetails']['response_desc']}</h3>
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
