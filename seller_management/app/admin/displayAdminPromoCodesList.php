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

  $access = premissionScreen('PROMO_CODES', $_SESSION['current_user']);

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
    }else if (isset($_POST['is_active'])) {
      $data['is_active'] = $_POST['is_active'];
    }else if (isset($_POST['expiry_date'])) {
      $data['expiry_date'] = $_POST['expiry_date'];
    }
    $url = DOMAIN . '/rest/admin/PromoCodesAdminRest.php';
    $output = getRestApiResponse($url, $data);

    $record = '';
    if (isset($output['getpromocodedetails']) && $output['getpromocodedetails']['response_code'] == 200) {
      $record .= <<< record
            <div class="container-fluid pt-3">
   <div class="row">
     <div class="col">
       <form action="displayAdminPromoCodesList.php" method="post">
         <div class="input-group ">
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
       <form action="displayAdminPromoCodesList.php" method="post">
         <div class="input-group ">
           <input type="text" class="form-control" placeholder="Active" name="is_active" style="border: medium solid black;margin-top:16px;">
           <div class="input-group-append">
             <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
               <i class="fa fa-search"></i>
             </button>
           </div>
         </div>
       </form>
     </div>

     <div class="col">
       <form action="displayAdminPromoCodesList.php" method="post">
         <div class="input-group ">
           <input type="text" class="form-control" placeholder="Expiry Date" name="expiry_date" style="border: medium solid black;margin-top:16px;">
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
 
 
 <div class="mt-3">
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
         <th>Promo Code</th>
         <th>Discount Type</th>
         <th>Discount Value</th>
         <th>Minimum Order Value</th>
         <th>Active</th>
         <th>Expiry Date</th>
       </tr>
     </thead>

     <tbody>
record;
      for ($i = 0; $i < $output['getpromocodedetails']['rows']; $i++) {

        $onlydate = date("d-m-Y", strtotime(substr($output['getpromocodedetails'][$i]['expiry_date'], 0, 10)));
        $record .= <<< record
                    <tr>
                        <td>{$output['getpromocodedetails'][$i]['seller_id']}</td>
                        <td>{$output['getpromocodedetails'][$i]['promo_code']}</td>
                        <td>{$output['getpromocodedetails'][$i]['discount_type']}</td>
                        <td>{$output['getpromocodedetails'][$i]['discount_value']}</td>
                        <td>{$output['getpromocodedetails'][$i]['minimum_order_amount']}</td>
                        <td>{$output['getpromocodedetails'][$i]['is_active']}</td>
                        <td> $onlydate</td>
                        
record;
      }

      echo $record;
    } else {
      if (isset($output['getpromocodedetails']) && $output['getpromocodedetails']['response_code'] == 405) {
        $record .= <<< record
              <h3 class="text-center pt-5" style = "color:red">{$output['getpromocodedetails']['response_desc']}</h3>
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
