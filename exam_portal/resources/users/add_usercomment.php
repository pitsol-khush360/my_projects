<?php 
	if(isset($_SESSION['username']) && isset($_SESSION['ulid']) && isset($_POST['post_comment']))
	{
		if(trim($_POST['user_comment'])!="")
		{
			$comment=trim($_POST['user_comment']);

			$query=query("insert into user_comments(ulid,user_comment) values('".$_SESSION['ulid']."','".$comment."')");
			confirm($query);

			setmessage("<p class='text-center' style='font-size:20px;color:green;'>Your Comment Posted Successfully</p>");
		}
		else
			setmessage("<p class='text-center text-danger' style='font-size:20px;'>Please Enter Comment!</p>");
	}
?>

<?php 
	if(isset($_SESSION['username']) && isset($_SESSION['ulid']))
	{
?>
<div class="container">
	<div class="row" style="margin-top:10rem;">
		<div class="col-12 col-sm-8 col-md-9 col-lg-12"><?php displaymessage(); ?></div>
		<div class="col-lg-2"></div>
		<div class="col-12 col-sm-8 col-md-9 col-lg-8 text-center">
			<h2 class="text-center">Post Comment</h2>
			<form action="index.php?add_usercomment" method="post">
				<label style="font-size:15px;margin-top:20px;">Enter Comment</label><br>
				<textarea name="user_comment" style="width:100%;height:5rem;">
				</textarea><br>
				<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
				<button type="submit" class="btn btn-success" name="post_comment">Post</button>
			</form>
		</div>
		<div class="col-md-2"></div>
	</div>
</div>

<?php
	}
	else
		redirect("..".DS."signin.php");
?>