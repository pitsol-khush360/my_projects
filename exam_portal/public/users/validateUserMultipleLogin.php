<?php include("../../resources/config.php"); ?>

<?php
if(!isset($_SESSION['username']) && !isset($_SESSION['ulid']) && !isset($_SESSION['user_token']))
{
    redirect("..".DS."signin.php");
}
else
{
	$q_cu=query("select * from user_login where userid='".$_SESSION['ulid']."'");
	confirm($q_cu);

	if(mysqli_num_rows($q_cu)!=0)
	{
		$r_cu=fetch_array($q_cu);

		if(!($r_cu['login_token']==$_SESSION['user_token']))
			redirect("..".DS."signin.php");
	}
	else
	{
		redirect("..".DS."signin.php");
	}
}

?>