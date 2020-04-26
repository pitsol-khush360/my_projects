<?php require_once("../../config.php"); ?>

<?php
	session_start();
	if(isset($_SESSION['admin_name']) && isset($_GET['id']))
	{
		$query=query("select * from orders where order_id='".$_GET['id']."'");
		confirm($query);
		$row=fetch_array($query);

		if($row['payment_status']=='N')
		{
			$query_edit=query("update orders set payment_status='Y' where order_id='".$_GET['id']."'");
			confirm($query_edit);
			redirect("../../../public/admin/index.php?orders");
		}
		else
		{
			$query_edit=query("update orders set payment_status='N' where order_id='".$_GET['id']."'");
			confirm($query_edit);
			redirect("../../../public/admin/index.php?orders");
		}
	}
	else
		echo "<p class='text-danger text-center text-lg'>Sorry! You Don't Have Access Permission...</p>";
?>