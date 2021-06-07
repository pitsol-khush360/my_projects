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

    $access = premissionScreen('PRODUCTS', $_SESSION['current_user']);

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
        if (isset($_POST['product_seller_id'])) {

            $data['product_seller_id'] = $_POST['product_seller_id'];
        } else if (isset($_POST['product_catalogue_id'])) {
            $data['product_catalogue_id'] = $_POST['product_catalogue_id'];
        } else if (isset($_POST['product_id'])) {
            $data['product_id'] = $_POST['product_id'];
        }

        $url = DOMAIN . '/rest/admin/AdminProductListRest.php';
        $output = getRestApiResponse($url, $data);

        $record = '';

        if (isset($output['getproductdetails']) && $output['getproductdetails']['response_code'] == 200) {
            if ($output['getproductdetails']['rows'] == 0) {
                echo '<script>$("#top").css("visibility", "hidden");</script>';
            }
            $record .= <<< record
                            <div class="container-fluid pt-4 pb-2" id="top">
                            <div class="row">
                                <div class="col">
                                    <form action="displayAdminProductList.php" method="post">
                                        <div class="input-group " style="float:right;margin-right:20px">
                                            <input type="text" class="form-control" placeholder="Seller Id" name="product_seller_id" style="border: medium solid black;margin-top:16px;">
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col">
                                    <form action="displayAdminProductList.php" method="post">
                                        <div class="input-group " style="float:right;margin-right:20px">
                                            <input type="text" class="form-control" placeholder="Collection Id" name="product_catalogue_id" style="border: medium solid black;margin-top:16px;">
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col">
                                    <form action="displayAdminProductList.php" method="post">
                                        <div class="input-group " style="float:right;margin-right:20px">
                                            <input type="text" class="form-control" placeholder="Product Id" name="product_id" style="border: medium solid black;margin-top:16px;">
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
               
                                <thead style='background-color : #36404e;color : white'>
                                    <tr>
                                        <th>Seller Id</th>
                                        <th>Collection Id</th>
                                        <th>Product Id</th>
                                        <th>Product Name</th>
                                        <th>Product Price</th>
                                        <th>Status</th>
                                        <th>Image</th>
                                        <th>Offer Price</th>
                                        <th>Product Inventory</th>
                                    </tr>
                                </thead>
               
                                <tbody>
record;
            for ($i = 0; $i < $output['getproductdetails']['rows']; $i++) {
                $imgpath = SELLER_TO_ROOT . $output['getproductdetails'][$i]['productimage'];
                $inventory;
                if( $output['getproductdetails'][$i]['product_inventory'] == 1 ) {
                    $inventory = "Stock";
                } else {
                    $inventory = "Instock";
                }
                $record .= <<< record
                    <tr>
                        <td >{$output['getproductdetails'][$i]['product_seller_id']}</td>
                        <td >{$output['getproductdetails'][$i]['product_catalogue_id']}</td>
                        <td >{$output['getproductdetails'][$i]['product_id']}</td>
                        <td >{$output['getproductdetails'][$i]['product_name']}</td>
                        <td class="text-right">{$output['getproductdetails'][$i]['product_price']}</td>                        
                        <td >{$output['getproductdetails'][$i]['product_status']}</td>
                        <td ><img class="list-image" src = "$imgpath" /></td>
                        <td class="text-right">{$output['getproductdetails'][$i]['product_offer_price']}</td>                        
                        <td >{$inventory}</td>
                    </tr>      
record;
            }

            echo $record;
        } else {
            if (isset($output['getproductdetails']) && $output['getproductdetails']['response_code'] == 405) {
                $record .= <<< record
                       <h3 class = "text-center pt-5" style = "color : red;">{$output['getproductdetails']['response_desc']}</h3>
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