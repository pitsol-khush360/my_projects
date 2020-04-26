<?php require_once("../../resources/config.php"); ?>
<?php include(TEMPLATE_USERS.DS."header.php"); ?>

<?php
        if(!isset($_SESSION['user_name']))
        {
            redirect("..".DS."login.php");
        }
?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Dashboard <small>Statistics Overview</small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Dashboard
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                    <?php
                            if($_SERVER['REQUEST_URI']=="/Php/Projects/E_Commerce_Site/ecom/public/users/index.php" || $_SERVER['REQUEST_URI']=="/Php/Projects/E_Commerce_Site/ecom/public/users/")
                            {
                                include(TEMPLATE_USERS.DS."user_content.php");
                            } 
                            else
                                if(isset($_GET['orders']))
                                {
                                    include_once(TEMPLATE_USERS.DS."orders.php");
                                }
                            else
                                if(isset($_GET['user_profile']))
                                {
                                    include_once("user_profile.php");
                                }
                            else
                                if(isset($_GET['reviews']))
                                {
                                    include_once(TEMPLATE_USERS.DS."reviews.php");
                                }
                    ?>
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

<?php include(TEMPLATE_USERS.DS."footer.php"); ?>