<?php require_once("../resources/config.php"); ?>
<?php include(TEMPLATE_FRONT.DS."header.php"); ?>

<div class="container">
<div class="row">
	<div class="col-lg-12 col-md-6">
                    <?php
                    getproducts();
                    ?>
    </div>
</div>
</div>
<?php //include(TEMPLATE_FRONT.DS."pagination.php"); ?>
<?php include(TEMPLATE_FRONT.DS."footer.php");  ?>