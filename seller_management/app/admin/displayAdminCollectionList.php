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

    $access = premissionScreen('COLLECTIONS', $_SESSION['current_user']);

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
        if (isset($_POST['searchfield'])) {

            $data['catalogue_seller_id'] = $_POST['searchfield'];
        }

        $url = DOMAIN . '/rest/admin/AdminCollectionListRest.php';
        $output = getRestApiResponse($url, $data);

        $record = '';

        if (isset($output['getcollectiondetails']) && $output['getcollectiondetails']['response_code'] == 200) {
            $record .= <<< record
                        <div class="container-fluid pt-2 pb-2" >
         
                        <form action="displayAdminCollectionList.php" method="post">
               
               
                            <div class="input-group w-50">
                                <input type="text" class="form-control" placeholder="Seller Id" name="searchfield" style="border: medium solid black;margin-top:16px;">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
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
                     <th>Seller Id</th>
                     <th>Collection Id</th>
                     <th>Collection Name</th>
                     <th>Creation DateTime</th>
                     <th>Status</th>
                     <th>Image</th>
                 </tr>
             </thead>

             <tbody>
record;
            for ($i = 0; $i < $output['getcollectiondetails']['rows']; $i++) {

                $imgpath = SELLER_TO_ROOT . $output['getcollectiondetails'][$i]['catalogue_image'];
                $record .= <<< record
                    <tr>
                        <td>{$output['getcollectiondetails'][$i]['catalogue_seller_id']}</td>
                        <td>{$output['getcollectiondetails'][$i]['catalogue_id']}</td>
                        <td>{$output['getcollectiondetails'][$i]['catalogue_Name']}</td>
                        <td>{$output['getcollectiondetails'][$i]['creation_datetime']}</td>
                        <td>{$output['getcollectiondetails'][$i]['catalogue_status']}</td>                        
                        <td><img src="$imgpath" class="img-responsive img-fluid list-image"></td>
                    </tr>      
record;
            }

            echo $record;
        } else {
            if (isset($output['getcollectiondetails']) && $output['getcollectiondetails']['response_code'] == 405) {
                $record .= <<< record
              <h3 class= "text-center pt-5" style = "color:red;">{$output['getcollectiondetails']['response_desc']}</h3>
record;
            }
            echo $record;
        }
        ?>
     </tbody>
     </table>
     



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
        echo '<h2 class="pt-5" style="text-align:center;color:red;margin-top:4rem;">Permission Denied!</h2>';
    }
    ?>
 <?php include("footer.php"); ?>
