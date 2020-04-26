<?php require_once("../resources/config.php");  ?>
<?php include(TEMPLATE_FRONT.DS."header.php");  ?>

    <!-- Page Content -->
    <div class="container">

        <!-- Jumbotron Header -->
        <header class="hero-spacer">
            <h1><?php echo $_GET['cattitle']; ?></h1>
            <p class="text-success" style="font-size:18px;"><?php getproductsbycategory("count"); ?>&nbsp;Products Found
            </p>
        </header>

        <hr>

        <!-- Title -->
        <div class="row">
            <div class="col-lg-12">
                <h3>Latest Products</h3>
            </div>
        </div>
        <!-- /.row -->

        <!-- Page Features -->
        <div class="row text-center">

            <?php getproductsbycategory("items"); ?>

        </div>
        <!-- /.row -->
<?php include(TEMPLATE_FRONT.DS."footer.php");  ?>