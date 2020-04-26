<?php require_once("../resources/config.php"); ?>
<?php include(TEMPLATE_FRONT.DS."header.php"); ?>

<?php
	if(isset($_GET['tx']))
	{
		process_transactions();
		session_destroy();
	}
	else
	{
		redirect("index.php");
	}
?>

<!-- Page content -->
<div class="container">
	<h1 class="text-center">Thank You</h1>
</div>

<?php include(TEMPLATE_FRONT.DS."footer.php"); ?>