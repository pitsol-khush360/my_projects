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

$access = premissionScreen('COLLECTION_LIBRARY', $_SESSION['current_user']);

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



    <div class="container-fluid pt-3">
        <?php
        if ($button != "disabled") {
        ?>
            <button class='btn mt-3 mb-3' style='float : right;border: 2px solid blue; color : blue' data-toggle="modal" data-target="#exampleModal">Add Collection</button>
        <?php
        } else {
        ?>
            <button class='btn btn-secondary mt-3 mb-3' style='float : right;' disabled>Add Collection</button>
        <?php
        }
        ?>

    </div>
    <input type="hidden" value="<?php echo DOMAIN; ?>" id="getDOMAIN">

    <!-- Connecting with RestApi-->

    <?php

    $data = array();
    if (isset($_POST['searchfieldId'])) {
        $data['collection_id'] = $_POST['searchfieldId'];
    }
    if (isset($_POST['searchfieldName'])) {
        $data['collection_name'] = $_POST['searchfieldName'];
    }

    $url = DOMAIN . '/rest/admin/CollectionLibraryListAdminRest.php';
    $output = getRestApiResponse($url, $data);

    $record = '';

    if (isset($output['getcollectiondetails']) && $output['getcollectiondetails']['response_code'] == 200) {
        if ($output['getcollectiondetails']['rows'] == 0) {
            echo '<script>$("#top").css("visibility", "hidden");</script>';
        }
        $record .= <<< record
                        <form action="displayAdminCollectionLibraryList.php" method="post" id="top">
         <div class="input-group w-50">
             <input type="text" class="form-control" placeholder="Collection Id" name="searchfieldId" style="border: medium solid black;margin-top:16px;">
             <div class="input-group-append">
                 <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                     <i class="fa fa-search"></i>
                 </button>
             </div>
             &nbsp; &nbsp;
             <input type="text" class="form-control" placeholder="Collection Name" name="searchfieldName" style="border: medium solid black;margin-top:16px;">
             <div class="input-group-append">
                 <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                     <i class="fa fa-search"></i>
                 </button>
             </div>
         </div>
         
     </form>

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
                     <th>Collection Id</th>
                     <th>Collection Name</th>
                     <th>Collection Image</th>
                     <th>Modify</th>
                     <th>Show Products</th>
                 </tr>
             </thead>

             <tbody>
record;
        for ($i = 0; $i < $output['getcollectiondetails']['rows']; $i++) {
            $imgpath = SELLER_TO_ROOT . $output['getcollectiondetails'][$i]['image_name'];
            $record .= <<< record
                    <tr>
                        <td>{$output['getcollectiondetails'][$i]['collection_id']}</td>
                        <td>{$output['getcollectiondetails'][$i]['collection_name']}</td>
                        <td><div class="text-center"><img src = "$imgpath" class="list-image"></div></td>
record;
            if ($button != 'disabled') {
                $record .= <<< record
                                <td><button type="submit" data-toggle="modal" onclick="modifyFun(this.id)" id = "{$output['getcollectiondetails'][$i]['collection_id']} + {$output['getcollectiondetails'][$i]['image_name']} + {$output['getcollectiondetails'][$i]['collection_name']}" data-target = "#modifyCollectionModal" class="btn btn-success">Modify</button></td>
record;
            } else {
                $record .= <<< record
                                <td><button type="submit" disabled class="btn btn-secondary">Modify</button></td>
record;
            }
            $record .= <<< record
                        <td><form action = "displayAdminCollectionLibraryListnewpage.php" method = "POST"><input type = "hidden" name = "collectionidnewpage" value = "{$output['getcollectiondetails'][$i]['collection_id']}"><input type = "hidden" name = "collectionnamenewpage" value = "{$output['getcollectiondetails'][$i]['collection_name']}"><button type = "submit" class = "btn btn-primary">Show Products</button></form></td>
                    </tr>      
record;
        }

        echo $record;
    } else {
        if (isset($output['getcollectiondetails']) && $output['getcollectiondetails']['response_code'] == 405) {
            
            $record .= <<< record
                  <h3 class = "text-center pt-5" style = "color : red;">{$output['getcollectiondetails']['response_desc']}</h3>
record;
        }
        echo $record;
    }
    ?>
    </tbody>
    </table>



    </div>


    <!--Add Collection Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Collection Library</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>



                <div class="modal-body">
                    <form action='displayAdminCollectionLibraryList.php' method='POST' enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Collection Name</label>
                            <input type="text" id="collection_name" required name="collection_name" style="float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
                        </div>

                        <div class="form-group">
                            <label>Upload Image</label>
                            <input type="file" id="collection_image" required name="collection_image" class="border border-top-0 border-left-0 border-right-0" style="float:right" title="Upload Collection Image" style="overflow:hidden;" <?php echo $input; ?>>
                        </div>




                        <hr>
                        <div class=" text-center">
                            <button type="submit" class="btn btn-primary" id="upload" style="width : 35%" name="upload">Save</button>
                            <!-- &nbsp;&nbsp; <button type="button" class="btn btn-danger" style="width : 35%" data-dismiss="modal">Cancel</button> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--End of add Collection Modal-->


    <!-- Modify Collection Modal-->
    <div class="modal fade" id="modifyCollectionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modify Collection Library</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="displayAdminCollectionLibraryList.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Collection Id</label>
                            <input id="viewCollectionId" name="viewCollectionId" readonly type="text" style="background-color : rgba(128, 128, 128, 0.555);float : right; border : 1px solid #549eff; margin-left : 5px">
                        </div>

                        <div class="form-group">
                            <label>Collection Name</label>
                            <input id="setCollectionName" required name="setCollectionName" type="text" style="float : right; border : 1px solid #549eff; margin-left : 5px " <?php echo $input; ?>>
                            <input id="setCollectionImage" name="setCollectionImage" type="hidden" style="float : right; border : 1px solid #549eff; margin-left : 5px " <?php echo $input; ?>>

                        </div>

                        <div class="form-group">
                            <label>Collection Image</label>
                            <input type="file" id="collectionImageUpdate" name="collectionImageUpdate" class="border border-top-0 border-left-0 border-right-0" title="Update Collection Image" style="overflow:hidden;float:right" <?php echo $input; ?>>
                        </div>
                        <!--Delete Confirm Modal-->
                        <div class="modal" id="confirmDeleteModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
                            <div class="modal-dialog modal-dialog-centered " role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title">&nbsp;&nbsp;<strong>Confirm ?
                                            </strong></h3>

                                    </div>

                                    <div class="modal-footer">
                                        <input type="button" class="btn btn-success w-50 dltbtn" value='Yes'>
                                        <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End of Delete Confirm Modal -->


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="update" name="update" style="width : 35%">Update</button>


                    <button class="btn btn-primary" type="button" style="width : 35%" data-toggle="modal" data-target="#confirmDeleteModal" id='dltbtn'>Delete</button>
                    <button type="button" class="btn btn-danger" style="width : 35%" data-dismiss="modal">Cancel</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!--End of Modify Collection Modal-->

    <?php

    $data = array();
    if (isset($_POST['upload']) && isset($_POST['collection_name'])) {

        $data['collection_name'] = $_POST['collection_name'];

        $image = $_FILES['collection_image']['tmp_name'];

        if (empty($image) || $image == "")
            $data['imagestatus'] = 0;
        else {
            $data['imagestatus'] = 1;
            $image = file_get_contents($image);
            $image = base64_encode($image);
            $data['image'] = $image;
        }

        $url = DOMAIN . '/rest/admin/CollectionLibraryAddAdminRest.php';
        $output = getRestApiResponse($url, $data);

        // If status code is 200 then successful.
        if (isset($output['createcollection']) && $output['createcollection']['response_code'] == 200) {

            $showinformation = 1;
            $message = '<p class="text-success">Collection Added successfully</p>';
        } else {
            $showinformation = 1;

            $message = '<p class="text-success">' . $output['createcollection']['response_desc'] . '</p>';
        }
    }

    ?>



    <?php

    $data = array();
    if (isset($_POST['update'])) {

        if (isset($_POST['setCollectionName']) && isset($_POST['viewCollectionId'])) {
            $data['collection_name'] = $_POST['setCollectionName'];
            $data['collection_id'] = $_POST['viewCollectionId'];
            $data['image_name'] = $_POST['setCollectionImage'];
            $image = $_FILES['collectionImageUpdate']['tmp_name'];

            if (empty($image) || $image == "")
                $data['imagestatus'] = '0';
            else {
                $data['imagestatus'] = '1';
                $image = file_get_contents($image);
                $image = base64_encode($image);
                $data['image'] = $image;
            }

            $url = DOMAIN . '/rest/admin/CollectionLibraryModifyAdminRest.php';
            $output = getRestApiResponse($url, $data);


            // If status code is 200 then successful.
            if (isset($output['updatecollection']) && $output['updatecollection']['response_code'] == 200) {
                $showinformation = 1;
                $message = '<p class="text-success">Collection updated successfully</p>';
            } else {
                $showinformation = 1;
                $message = '<p class="text-success">' . $output['updatecollection']['response_desc'] . '</p>';
            }
        }
    }
    ?>



    
    <script>
        function modifyFun(collectionData) {
            var temp = new Array();
            temp = collectionData.split("+");
            $("#viewCollectionId").val(temp[0].trim());
            $("#setCollectionImage").val(temp[1].trim());
            $("#setCollectionName").val(temp[2].trim());
        }

        $('.dltbtn').click(function() {
            var domain = $("#getDOMAIN").val();
            var collection_id = $("#viewCollectionId").val();
            $.post(domain + '/rest/admin/CollectionLibraryDeleteAdminRest.php?collection_id=' + collection_id + '&key=<?php echo md5(VALIDATION_KEY);?>',
                function(data, status) {
                    $("#confirmDeleteModal").click();
                    $("#modifyCollectionModal").click();
                    if (data['deletecollection']['response_code'] == 200) {
                        $("#response").modal("show");
                        $("#restext").text("Deletion Successful");
                        $("#resdesc").text("");
                    } else {
                        $("#response").modal("show");
                        $("#restext").text("Deletion Unsuccessful");
                        $("#resdesc").text("ERROR : " + data['deletecollection']['response_desc']);
                    }
                });



        });
    </script>
    <?php

    if ($showinformation == 1)
        echo '<script>
				$("#information").html(\'' . $message . '\');
                $("#information-modal").modal("show");
               
            </script>';
    echo '<script>
            if ( window.history.replaceState ) {
              window.history.replaceState( null, null, window.location.href );
            }
            
            </script>';

    ?>
    </body>

    </html>

<?php
} else {
    echo '<h2 class = "pt-5" style="text-align:center;color:red;margin-top:4rem;">Permission Denied!</h2>';
}
?>
<?php include("footer.php"); ?>
