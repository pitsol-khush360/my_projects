<?php include("validateUserMultipleLogin.php"); ?>

<?php
	if(isset($_GET['ccid']) && isset($_GET['scid']) && isset($_GET['plan']))
	{
		$amount=0;

		$query=query("select * from subcourse_plans where ccid='".$_GET['ccid']."' and scid='".$_GET['scid']."' and plan_type='".$_GET['plan']."'");
		confirm($query);

		if(mysqli_num_rows($query)!=0)
		{
			$row=fetch_array($query);
			$amount=$row['amount'];
		}
		
		echo $amount;
	}
?>