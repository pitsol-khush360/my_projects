<?php include("../../resources/config.php"); ?>
<?php 
	if(isset($_POST['delete_question'])&&isset($_POST['qstid'])&&isset($_POST['qtid'])&&isset($_POST['qid']))
	{
		$qstid=$_POST['qstid'];
		$qtid=$_POST['qtid'];
		$qid=$_POST['qid'];

		// selecting question image and removing from server directory
	   	$query1=query("SELECT question_img FROM question WHERE qid = '".$qid."' and qtid='".$qtid."' and qstid='".$qstid."'");
		confirm($query1);

		if(mysqli_num_rows($query1)!=0)
		{
			$row=fetch_array($query1);

			if(!empty($row['question_img']) && $row['question_img']!="") 
				unlink(ADMINQUESTION_UPLOAD.DS.$row['question_img']);
		}

		// deleting data from question
		$query2=query("DELETE FROM question WHERE qid = '".$qid."' and qtid='".$qtid."' and qstid='".$qstid."'");		
		confirm($query2);

		redirect("admin_question_bank.php?qtid={$qtid}&qstid={$qstid}");
	}
	else
	{
		redirect("admin_question_topic.php");
	}
?>