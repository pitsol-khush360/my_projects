<?php require_once("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."header.php"); ?>

<?php
        if(!isset($_SESSION['admin_name']))
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
                            if($_SERVER['REQUEST_URI']=="/Php/Projects/E_Commerce_Site/ecom/public/admin/index.php" || $_SERVER['REQUEST_URI']=="/Php/Projects/E_Commerce_Site/ecom/public/admin/")
                            {
                                include(TEMPLATE_BACK.DS."admin_content.php");
                            } 
                            else
                                if(isset($_GET['orders']))
                                {
                                    include_once(TEMPLATE_BACK.DS."orders.php");
                                }
                            else
                                if(isset($_GET['products']))
                                {
                                    include_once(TEMPLATE_BACK.DS."products.php");
                                }
                            else
                                if(isset($_GET['add_product']))
                                {
                                    include_once(TEMPLATE_BACK.DS."add_product.php");
                                }
                            else
                                if(isset($_GET['categories']))
                                {
                                    include_once(TEMPLATE_BACK.DS."categories.php");
                                }
                            else
                                if(isset($_GET['users']))
                                {
                                    include_once(TEMPLATE_BACK.DS."users.php");
                                }
                            else
                                if(isset($_GET['edit_product']))
                                {
                                    include_once(TEMPLATE_BACK.DS."edit_product.php");
                                }
                            else
                                if(isset($_GET['edit_category']))
                                {
                                    include_once(TEMPLATE_BACK.DS."edit_category.php");
                                }
                            else
                                if(isset($_GET['edit_user']))
                                {
                                    include_once(TEMPLATE_BACK.DS."edit_user.php");
                                }
                    ?>
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

<?php include(TEMPLATE_BACK.DS."footer.php"); ?>