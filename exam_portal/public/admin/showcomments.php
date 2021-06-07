<?php include("../../resources/config.php"); ?>
<?php 
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
	if (isset($_GET['N'])&&isset($_GET['id'])) 
	{
		$query=query("UPDATE user_comments SET comment_status ='Y' WHERE ucid='".$_GET['id']."'");
		confirm($query);
		redirect("admin_user_comment.php");
	}
	else if(isset($_GET['Y'])&&isset($_GET['id']))
	{
		$query=query("UPDATE user_comments SET comment_status ='N' WHERE ucid='".$_GET['id']."'");
		confirm($query);
		redirect("admin_user_comment.php");
	}
	else
	{
		redirect("admin_user_comment.php");
	}
}
?>