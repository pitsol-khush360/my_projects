<?php include("../resources/config.php"); ?>
<?php
	if(isset($_GET['increment']))
	{
		if(!isset($_SESSION['current_visitor']))
		{
			$_SESSION['current_visitor']="true";
        	$inc=query("update new_visitors set visitor_count=visitor_count+1");
        	confirm($inc);
        }
	}
?>