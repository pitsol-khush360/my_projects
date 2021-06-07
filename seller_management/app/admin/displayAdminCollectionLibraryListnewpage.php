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
    <?php
    if (isset($_POST['collectionidnewpage']) && isset($_POST['collectionnamenewpage'])) {

        $_SESSION['collection_idnewpage'] = $_POST['collectionidnewpage'];
        $_SESSION['collection_namenewpage'] = $_POST['collectionnamenewpage'];
    }

    ?>
    <!Doctype html>
    <html>

    <head>
        <style>
            table.table-bordered>tbody>tr>td {
                border: 1px solid black;
            }
        </style>
    </head>

    <body>
        <input type="hidden" value="<?php echo DOMAIN; ?>" id="getDOMAIN">
        <div style="float: right;" class="pt-4">
            <?php
            if ($button != 'disabled') {
            ?>
                <button class="btn" data-toggle="modal" data-target="#addproductModal" style="border: 2px solid blue; color : blue;float:right">Add Product</button>
            <?php
            } else {
            ?>
                <button class="btn btn-secondary" disabled>Add Product</button>
            <?php
            }
            ?>

        </div>
        <div class="container-fluid pt-4">

            <div class="row">
                <div class="col-3">

                    <button class="btn btn-secondary" disabled>Collection : <?php echo $_SESSION['collection_idnewpage']; ?> - <?php echo $_SESSION['collection_namenewpage']; ?></button>
                </div>

                <div class="col-3">
                    <form action="displayAdminCollectionLibraryListnewpage.php" method="POST">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Product Id" name="searchfieldId" style="border: medium solid black;">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-4">
                    <form action="displayAdminCollectionLibraryListnewpage.php" method="POST">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Product Name" name="searchfieldName" style="border: medium solid black;">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <div class="pt-3">
            <table class="table table-hover table-responsive-lg table-sm table-bordered table-fixed text-center" data-toggle="table" data-pagination-h-align="center" data-pagination-detail-h-align="right" data-side-pagination="client" data-page-size="10" data-pagination="true">

                <thead>
                    <tr style="color: blue;">
                        <th>Product Id</th>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Product Image</th>
                        <th>Modify</th>
                        <th>Delete</th>
                    </tr>
                </thead>

                <tbody>
                    <!-- Connecting with RestApi-->

                    <?php

                    $data = array();
                    if (isset($_POST['searchfieldId'])) {
                        $data['product_id'] = $_POST['searchfieldId'];
                        $data['collection_id'] = $_SESSION['collection_idnewpage'];
                    } else if (isset($_POST['searchfieldName'])) {
                        $data['product_name'] = $_POST['searchfieldName'];
                        $data['collection_id'] = $_SESSION['collection_idnewpage'];
                    } else {

                        $data['collection_id'] = $_SESSION['collection_idnewpage'];
                    }

                    $url = DOMAIN . '/rest/admin/ProductLibraryListAdminRest.php';
                    $output = getRestApiResponse($url, $data);

                    $record = '';

                    if (isset($output['getproductdetails']) && $output['getproductdetails']['response_code'] == 200) {
                        for ($i = 0; $i < $output['getproductdetails']['rows']; $i++) {
                            $imgpath = SELLER_TO_ROOT . $output['getproductdetails'][$i]['image_name'];
                            $record .= <<< record
                    <tr>
                        <td >{$output['getproductdetails'][$i]['product_id']}</td>
                        <td >{$output['getproductdetails'][$i]['product_name']}</td>
                        <td >{$output['getproductdetails'][$i]['product_description']}</td>
                        <td ><div class="text-center"><img src = "$imgpath" class="list-image"></div></td>
record;
                            if ($button != 'disabled') {
                                $record .= <<< record
                        <td ><button type = "button" class = "btn" style = "border : 2px solid green;color : green" data-toggle = "modal" data-target = "#modifyProductModal" id = "{$output['getproductdetails'][$i]['product_id']} + {$output['getproductdetails'][$i]['product_name']} + {$output['getproductdetails'][$i]['product_description']} + {$output['getproductdetails'][$i]['image_name']}" onclick="modifyproductId(this.id)"><i class="fas fa-edit"></i></button></td>
                        <td ><button type = "submit" class = "btn" style = "border : 2px solid red;color : red" data-toggle = "modal" data-target = "#deleteRowModal" id = "{$output['getproductdetails'][$i]['product_id']}" onclick="setproductFun(this.id)"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>      
record;
                            } else {
                                $record .= <<< record
    <td ><button type = "button" class = "btn btn-secondary" disabled ><i class="fas fa-edit"></i></button></td>
    <td ><button type = "submit" class = "btn btn-secondary" disabled ><i class="fas fa-trash-alt"></i></button></td>
</tr>      
record;
                            }
                        }

                        echo $record;
                    } else {
                        if (isset($output['getproductdetails']) && $output['getproductdetails']['response_code'] == 405) {
                            $record .= <<< record
              <tr ><td colspan='6'><h3 style = "color : red;border : 0px;">{$output['getproductdetails']['response_desc']}</h3></td></tr>
               
record;
                        }
                        echo $record;
                    }
                    ?>
                </tbody>

            </table>



        </div>


        <!--Delete Modal-->
        <div class="modal" id="deleteRowModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
            <div class="modal-dialog modal-dialog-centered " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">&nbsp;&nbsp;<strong>Confirm ?
                            </strong></h3>

                    </div>

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-success w-50 dlt" value='Yes'>
                        <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Delete Modal -->

        <!--Add Product Modal-->
        <div class="modal fade" id="addproductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Product to Library</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>



                    <div class="modal-body">
                        <form action='displayAdminCollectionLibraryListnewpage.php' method='POST' enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" id="productName" required name="aproductname" style="float : right; border : 1px solid #549eff; margin-left : 5px">
                            </div>

                            <div class="form-group">
                                <label>Product Description</label>
                                <textarea type="text" id="productDescription" name="aproductdescription" style="float : right; border : 1px solid #549eff; margin-left : 5px"></textarea>
                            </div>
                            <hr style="background-color: #ffffff; margin-top : 20px">
                            <div class="form-group">
                                <label>Product Image</label>
                                <input type="file" accept="image/*" required name="aproductimage" id="productImage" class="border border-top-0 border-left-0 border-right-0" style="float:right" title="Upload Collection Image" style="overflow:hidden;">
                            </div>

                            


                            <hr>
                            <div class=" text-center">
                                <button class="btn btn-primary" type="submit" id="saveproduct" name="saveproduct" style="width : 35%">Save</button>
                                &nbsp;&nbsp; <button type="button" class="btn btn-danger" style="width : 35%" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!--End of add User Modal-->


        <!-- Modify Collection Modal-->
        <div class="modal fade" id="modifyProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modify Product In Library</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="displayAdminCollectionLibraryListnewpage.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Product Id</label>
                                <input id="viewProductId" readonly type="text" name="modifyProductId" style="background-color : rgba(128, 128, 128, 0.555);float : right; border : 1px solid #549eff; margin-left : 5px">
                            </div>

                            <div class="form-group">
                                <label>Product Name</label>
                                <input id="setProductName" type="text" required name="modifyProductName" style="float : right; border : 1px solid #549eff; margin-left : 5px ">
                                <input id="setProductImageh" name="setProductImageh" type="hidden" style="float : right; border : 1px solid #549eff; margin-left : 5px ">
                            </div>

                            <div class="form-group">
                                <label>Product Description</label>
                                <textarea style="float: right;height: 25x;" id="setProductDescription" name="modifyProductDescription"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Product Image</label>
                                <input type="file" name="modifyProductImage" id="setProductImage" class="border border-top-0 border-left-0 border-right-0" style="float:right" title="Upload Collection Image" style="overflow:hidden;">
                            </div>
                             

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" style="width : 35%" id="updateProduct" name="updateProduct">Save</button>
                        <button type="button" class="btn btn-danger" style="width : 35%" data-dismiss="modal">Cancel</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!--End of Modify Collection Modal-->
        <form action="displayAdminCollectionLibraryList.php" method="POST" class="text-center">
            <input type="submit" class="btn btn-success" value="Go Back">
        </form>


        <?php

        $data = array();
        if (isset($_POST['saveproduct']) && isset($_POST['aproductname']) && isset($_POST['aproductdescription'])) {

            $data['collection_id'] = $_SESSION['collection_idnewpage'];
            $data['product_name'] = $_POST['aproductname'];
            $data['product_description'] = $_POST['aproductdescription'];


            $image = $_FILES['aproductimage']['tmp_name'];

            if (empty($image) || $image == "")
                $data['imagestatus'] = 0;
            else {
                $data['imagestatus'] = 1;
                $image = file_get_contents($image);
                $image = base64_encode($image);
                $data['image'] = $image;
            }

            $url = DOMAIN . '/rest/admin/ProductLibraryAddAdminRest.php';
            $output = getRestApiResponse($url, $data);
            // If status code is 200 then successful.
            if (isset($output['createproduct']) && $output['createproduct']['response_code'] == 200) {
                $showinformation = 1;
                $message = '<p class="text-success">Product Updated Successfully</p>';
                //header("location: displayAdminCollectionLibraryListnewpage.php");
            } else {
                $showinformation = 1;
                $message = '<p class="text-success">' . $output['createproduct']['response_desc'] . '</p>';
                //header("location: displayAdminCollectionLibraryListnewpage.php");
            }
        }

        ?>


        <?php


        if (isset($_POST['updateProduct'])) {
            $data = array();
            if (isset($_POST['modifyProductId']) && isset($_POST['modifyProductName'])  && isset($_POST['modifyProductDescription'])) {
                $data['product_name'] = $_POST['modifyProductName'];
                $data['product_id'] = $_POST['modifyProductId'];
                $data['image_name'] = $_POST['setProductImageh'];
                $data['product_description'] = $_POST['modifyProductDescription'];


                $image = $_FILES['modifyProductImage']['tmp_name'];

                if (empty($image) || $image == "")
                    $data['imagestatus'] = '0';
                else {
                    $data['imagestatus'] = '1';
                    $image = file_get_contents($image);
                    $image = base64_encode($image);
                    $data['image'] = $image;
                }

                $url = DOMAIN . '/rest/admin/ProductLibraryModifyAdminRest.php';
                $output = getRestApiResponse($url, $data);

                // If status code is 200 then successful.
                if (isset($output['updateproduct']) && $output['updateproduct']['response_code'] == 200) {
                    $showinformation = 1;
                    $message = '<p class="text-success">Product Updated Successfully</p>';
                    //header("location: displayAdminCollectionLibraryListnewpage.php");

                } else {
                    $showinformation = 1;
                    $message = '<p class="text-success">' . $output['updateproduct']['response_desc'] . '</p>';
                    //header("location: displayAdminCollectionLibraryListnewpage.php");
                }
            }
            if (isset($_POST['updateProduct'])) {
                unset($_POST['updateProduct']);
            }
        }

        ?>



        <input type="hidden" id="getproductID">

        <script>
            

            function setproductFun(productid) {
                $("#getproductID").val(productid);
            }

            function modifyproductId(dataForModify) {
                var temp = new Array();
                temp = dataForModify.split("+");
                console.log(temp);
                $("#viewProductId").val(temp[0].trim());
                $("#setProductName").val(temp[1].trim());
                $("#setProductDescription").val(temp[2].trim());
                $("#setProductImageh").val(temp[3].trim());

            }

            


            $(".dlt").click(() => {
                var domain = $("#getDOMAIN").val();

                $.post(domain + '/rest/admin/ProductLibraryDeleteAdminRest.php?product_id=' + $("#getproductID").val() + '&key=<?php echo md5(VALIDATION_KEY);?>',
                    function(data, status) {
                        $("#deleteRowModal").click();
                        if (data['deleteproduct']['response_code'] == 200) {
                            $("#response").modal("show");
                            $("#restext").text("Deletion Successful");
                        } else {
                            $("#response").modal("show");
                            $("#restext").text("Deletion Unsuccessful");
                            $("#resdesc").text("ERROR : " + data['deleteproduct']['response_desc']);
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