<?php 
		require_once("../../config.php");
		if(isset($_GET['id']))
		{
			$query=query("delete from orders where order_id=".escape_string($_GET['id']));
			confirm($query);
			setmessage("Order number ".$_GET['id']." deleted.");
			redirect("../../../public/admin/index.php?orders");
		}
?>