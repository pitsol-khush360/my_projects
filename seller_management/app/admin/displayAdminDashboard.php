<?php
require_once("../../config/config.php");
require_once("../../config/" . ENV . "_config.php");
?>

<?php
if (!(isset($_SESSION['current_user'])))
    redirect('login.php');
?>
 <?php include("navigation.php");?>
<?php

$access = premissionScreen('DASHBOARD', $_SESSION['current_user']);

$global = $access['global'];
$input = $access['input'];
$button = $access['button'];

if ($global != 0) {
?>

   

    <?php
    $showinformation = 0;
    $message = "";
    ?>


<div class="container pt-3">
    <?php
    $data['user_id'] = $_SESSION['current_user'];
    if (isset($_POST['submitdate']))
        $data['date'] = $_POST['searchdate'];

    $url = DOMAIN . '/rest/admin/GetAdminDashBoardCountRest.php';
    $output = getRestApiResponse($url, $data);
    ?>
</div>
<hr>

<div class="row mt-3">
    <div class="col-9 col-md-3">
        <form action="" method="post">
            <div class="input-group">
                <?php
                $old = "";

                if (isset($_POST['submitdate']))
                    $current = $_POST['searchdate'];
                else
                    $current = date("Y-m-d");
                ?>
                <input type="date" name="searchdate" class="form-control" value="<?php echo $current; ?>">
                <div class="input-group-append">
                    <button class="btn btn-secondary" type="submit" name="submitdate">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
<h3 style='text-align : center'><strong>Orders</strong></h3>
<div class="row mt-3 justify-content-center">
    <div class="col-6 col-md-2  mt-3">

        <div class="card shadow bg-white rounded" style=' box-shadow: 5px 5px #d9534f;border-color : #ffffff'>
            <div class="card-body">
                <diV>
                    <span style='display : block; text-align : center; margin-bottom : 10px;color : #d9534f'>
                        <i class="fas fa-question fa-2x"></i>
                    </span>

                    <p style='text-align : center'>Pending Orders</p>

                    <span style='float : right; color : #d9534f'>
                        <!-- <a href="displaySellerOrders.php?orderstatus=Pending" data-toggle="tooltip" title="View Details" data-placement="left">
                            <i class="fa fa-arrow-circle-right"></i>
                        </a> -->
                    </span>
                </div>
                <span style='float : left; color :  #d9534f'>
                    <?php
                    if (isset($output['getdashboard']['pending']))
                        echo $output['getdashboard']['pending'];
                    ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2 mt-3">

        <div class="card shadow bg-white rounded" style=' box-shadow: 5px 5px #28a745;border-color : #ffffff'>
            <div class="card-body">
                <diV>
                    <span style='display : block; text-align : center; margin-bottom : 10px;color : #28a745'>
                        <i class="fas fa-check fa-2x"></i>
                    </span>

                    <p style='text-align : center'>Accepted Orders</p>

                    <span style='float : right; color : #d9534f'>
                        <!-- <a href="displaySellerOrders.php?orderstatus=Accepted" data-toggle="tooltip" title="View Details" data-placement="left">
                            <i class="fa fa-arrow-circle-right"></i>
                        </a> -->
                    </span>
                </div>
                <span style='float : left; color :  #28a745'>
                    <?php
                    if (isset($output['getdashboard']['accepted']))
                        echo $output['getdashboard']['accepted'];
                    ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2 mt-3">

        <div class="card shadow bg-white rounded" style=' box-shadow: 5px 5px #17a2b8;border-color : #ffffff'>
            <div class="card-body">
                <diV>
                    <span style='display : block; text-align : center; margin-bottom : 10px;color : #17a2b8'>
                        <i class="fas fa-gift fa-2x"></i>
                    </span>

                    <p style='text-align : center'>Shipped Orders</p>

                    <span style='float : right; color : #d9534f'>
                        <!-- <a href="displaySellerOrders.php?orderstatus=Shipped" data-toggle="tooltip" title="View Details" data-placement="left">
                            <i class="fa fa-arrow-circle-right"></i>
                        </a> -->
                    </span>
                </div>
                <span style='float : left; color :  #17a2b8'>
                    <?php
                    if (isset($output['getdashboard']['shipped']))
                        echo $output['getdashboard']['shipped'];
                    ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2 mt-3">

        <div class="card shadow bg-white rounded" style=' box-shadow: 5px 5px #28a745;border-color : #ffffff'>
            <div class="card-body">
                <diV>
                    <span style='display : block; text-align : center; margin-bottom : 10px;color : #28a745'>
                        <i class="fas fa-truck fa-2x"></i>
                    </span>

                    <p style='text-align : center'>Delivered Orders</p>

                    <span style='float : right; color : #d9534f'>
                        <!-- <a href="displaySellerOrders.php?orderstatus=Delivered" data-toggle="tooltip" title="View Details" data-placement="left">
                            <i class="fa fa-arrow-circle-right"></i>
                        </a> -->
                    </span>
                </div>
                <span style='float : left; color :  #28a745'>
                    <?php
                    if (isset($output['getdashboard']['delivered']))
                        echo $output['getdashboard']['delivered'];
                    ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2 mt-3">

        <div class="card shadow bg-white rounded" style=' box-shadow: 5px 5px #dc3545;border-color : #ffffff'>
            <div class="card-body">
                <diV>
                    <span style='display : block; text-align : center; margin-bottom : 10px;color : #dc3545'>
                        <i class="fas fa-times fa-2x"></i>
                    </span>

                    <p style='text-align : center'>Cancelled Orders</p>

                    <span style='float : right; color : #d9534f'>
                        <!-- <a href="displaySellerOrders.php?orderstatus=Delivered" data-toggle="tooltip" title="View Details" data-placement="left">
                            <i class="fa fa-arrow-circle-right"></i>
                        </a> -->
                    </span>
                </div>
                <span style='float : left; color :  #dc3545'>
                    <?php
                    if (isset($output['getdashboard']['cancelled']))
                        echo $output['getdashboard']['cancelled'];
                    ?>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-md-2 mt-2 pt-2">
            <h3 class="text-center pb-2"><strong>Revenue</strong></h3>

            <div class="card shadow bg-white rounded" style=' box-shadow: 5px 5px #007bff;border-color : #ffffff'>
                <div class="card-body">
                    <diV>
                        <span style='display : block; text-align : center; margin-bottom : 10px;color : #007bff'>
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </span>

                        <p style='text-align : center'>Total Sales</p>

                        <span style='float : right; color : #d9534f'>
                            <!-- <a href="#" data-toggle="tooltip" title="View Details" data-placement="left">
                            <i class="fa fa-arrow-circle-right"></i>
                        </a> -->
                        </span>
                    </div>
                    <span style='float : left; color :  #007bff'>
                        <?php
                        if (isset($output['getdashboard']['total_sales']))
                            echo $output['getdashboard']['total_sales'];
                        ?>
                    </span>
                </div>
            </div>

        </div>
        <div class="col-md-5 mt-2 pt-2">
        <h3 class="text-center pb-2"><strong>Revenue</strong></h3>
            <div class="row">
                <div class="col">
                    <div class="card shadow bg-white rounded" style=' box-shadow: 5px 5px #ffc107;border-color : #ffffff'>
                        <div class="card-body">
                            <diV>
                                <span style='display : block; text-align : center; margin-bottom : 10px;color : #ffc107'>
                                    <i class="fas fa-layer-group fa-2x"></i>
                                </span>

                                <p style='text-align : center'>Total Collections</p>

                                <span style='float : right; color : #d9534f'>
                                    <!-- <a href="displaySellerCatalogues.php" data-toggle="tooltip" title="View Details" data-placement="left">
                            <i class="fa fa-arrow-circle-right"></i>
                        </a> -->
                                </span>
                            </div>
                            <span style='float : left; color :  #ffc107'>
                                <?php
                                if (isset($output['getdashboard']['catalogues']))
                                    echo $output['getdashboard']['catalogues'];
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow bg-white rounded" style=' box-shadow: 5px 5px #28a745;border-color : #ffffff'>
                        <div class="card-body">
                            <diV>
                                <span style='display : block; text-align : center; margin-bottom : 10px;color : #28a745'>
                                    <i class="fas fa-list-alt fa-2x"></i>
                                </span>

                                <p style='text-align : center'>Total Products</p>

                                <span style='float : right; color : #d9534f'>
                                    <!-- <a href="displaySellerProducts.php" data-toggle="tooltip" title="View Details" data-placement="left">
                            <i class="fa fa-arrow-circle-right"></i>
                        </a> -->
                                </span>
                            </div>
                            <span style='float : left; color :  #28a745'>
                                <?php
                                if (isset($output['getdashboard']['products']))
                                    echo $output['getdashboard']['products'];
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5 mt-2 pt-2">
        <h3 class="text-center pt-2"><strong>Tickets</strong></h3>
            <div class="row">
                <div class="col">
                    <div class="card shadow bg-white rounded" style=' box-shadow: 5px 5px #17a2b8;border-color : #ffffff'>
                        <div class="card-body">
                            <diV>
                                <span style='display : block; text-align : center; margin-bottom : 10px;color : #17a2b8'>
                                    <i class="fas fa-ticket-alt fa-2x"></i>
                                </span>

                                <p style='text-align : center'>Open Tickets</p>

                                <span style='float : right; color : #d9534f'>
                                    <!-- <a href="displayContactUsForSeller.php" data-toggle="tooltip" title="View Details" data-placement="left">
                            <i class="fa fa-arrow-circle-right"></i>
                        </a> -->
                                </span>
                            </div>
                            <span style='float : left; color :  #17a2b8'>
                                <?php
                                if (isset($output['getdashboard']['open']))
                                    echo $output['getdashboard']['open'];
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow bg-white rounded" style=' box-shadow: 5px 5px #17a2b8;border-color : #ffffff'>
                        <div class="card-body">
                            <diV>
                                <span style='display : block; text-align : center; margin-bottom : 10px;color : #17a2b8'>
                                    <i class="fas fa-ticket-alt fa-2x"></i>
                                </span>

                                <p style='text-align : center'>Reopen Tickets</p>

                                <span style='float : right; color : #d9534f'>
                                    <!-- <a href="displayContactUsForSeller.php" data-toggle="tooltip" title="View Details" data-placement="left">
                            <i class="fa fa-arrow-circle-right"></i>
                        </a> -->
                                </span>
                            </div>
                            <span style='float : left; color :  #17a2b8'>
                                <?php
                                if (isset($output['getdashboard']['reopen']))
                                    echo $output['getdashboard']['reopen'];
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







<hr>
<!-- container ends -->

</div>
<!-- content end -->
</div>
<!-- page content -->
</div>
</div>
<!-- page wrapper end -->



<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>


<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
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
