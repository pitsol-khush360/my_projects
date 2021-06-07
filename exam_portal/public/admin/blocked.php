<?php include("../../resources/config.php"); ?>
<?php 
	if(isset($_POST['block_unblock_user']) && isset($_POST['action'])&&isset($_POST['userid'])) 
	{
		$action=$_POST['action'];
		$uid=$_POST['userid'];

		$query="";

		if($action=="unblock")
			$query=query("UPDATE user_login SET blocked = '0' WHERE userid='".$uid."'");
		else
		if($action=="block")
			$query=query("UPDATE user_login SET blocked ='1' WHERE userid='".$uid."'");
			
		confirm($query);
		redirect("admin_users.php?users");
	}
	else
	{
		redirect("admin_users.php?users");
	}
?>