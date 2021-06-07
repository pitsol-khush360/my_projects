<?php include("../../resources/config.php"); ?>
<?php 
	if(isset($_POST['delete_topic']) && isset($_POST['qtid']))
	{
		$qtid=$_POST['qtid'];

		/*
			Dependency Flow :
			______________________

			question_topic (qtid) -> question_sub_topic(qstid)
			question_sub_topic(qstid) -> question (qstid)

		*/

		$query=query("SET foreign_key_checks = 0");
		confirm($query);

		$query=query("DELETE qt, qst, q FROM question_topic qt
			LEFT JOIN question_sub_topic qst ON (qt.qtid = qst.qtid)
			LEFT JOIN question q ON (qst.qstid = q.qstid)
			WHERE qt.qtid = '".$qtid."'");
		confirm($query);

		$query=query("SET foreign_key_checks = 1");
		confirm($query);		

		redirect("admin_question_topic.php");
	}
	else
	{
		redirect("admin_question_topic.php");
	}
?>