<?php include("../../resources/config.php"); ?>
<?php 
	if(isset($_POST['delete_sub_topic']) && isset($_POST['qstid'])&&isset($_POST['qtid']))
	{
		$qstid=$_POST['qstid'];
		$qtid=$_POST['qtid'];

		/*
			Dependency Flow :
			______________________

			question_sub_topic(qstid) -> question (qstid)

		*/

		$query=query("SET foreign_key_checks = 0");
		confirm($query);

		$query=query("DELETE qst, q FROM question_sub_topic qst
			LEFT JOIN question q ON (qst.qstid = q.qstid)
			WHERE qst.qstid = '".$qstid."'");
		confirm($query);

		$query=query("SET foreign_key_checks = 1");
		confirm($query);

		redirect("admin_sub_topic.php?qtid={$qtid}");
	}
	else
	{
		redirect("admin_question_topic.php");
	}
?>