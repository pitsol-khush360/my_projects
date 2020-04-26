<?php
 		require_once("../../config.php");

 		if(isset($_GET['id']))
 		{
 			$query=query("delete from users where userid=".escape_string($_GET['id']));
 			confirm($query);
 			setmessage("User Deleted.");
 			redirect("../../../public/admin/index.php?users");
 		}
 		else
 		{
 			redirect("../../../public/admin/index.php?users");
 		}
?> 