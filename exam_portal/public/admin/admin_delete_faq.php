<?php include("../../resources/config.php"); ?>
<?php 
	if(isset($_POST['delete_faq']) && isset($_POST['id']))
	{
		$fid=$_POST['id'];		

		$query=query("DELETE FROM faqs WHERE faq_id = '".$fid."'");
		confirm($query);

		redirect("admin_faqs.php");
	}
	else
	{
		redirect("admin_faqs.php");
	}
?>