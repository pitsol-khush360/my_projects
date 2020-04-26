<?php
 		require_once("../../config.php");

 		if(isset($_GET['id']))
 		{
 			$query=query("delete from products where product_id=".escape_string($_GET['id']));
 			confirm($query);
 			setmessage("Product Number ".$_GET['id']." deleted.");
 			redirect("../../../public/admin/index.php?products");
 		}
 		else
 		{
 			redirect("../../../public/admin/index.php?products");
 		}
?>