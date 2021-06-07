<?php include("../../resources/config.php"); ?>
<?php 
	if(isset($_POST['delete_section_question']) && isset($_POST['ccid'])&&isset($_POST['scid'])&&isset($_POST['seid'])&&isset($_POST['sec_cnt'])&&isset($_POST['tid'])&& isset($_POST['qid']))
	{
		$qid=$_POST['qid'];
		$tid=$_POST['tid'];
		$ccid=$_POST['ccid'];
		$scid=$_POST['scid'];
		$seid=$_POST['seid'];
		$sec_cnt=$_POST['sec_cnt'];

		// deleting data from question_bank

		$query=query("SET foreign_key_checks = 0");
		confirm($query);

		$query=query("DELETE FROM question_bank WHERE seid='".$seid."' and qid='".$qid."'");		
		confirm($query);

		$query=query("SET foreign_key_checks = 1");
		confirm($query);

		redirect("admin_question.php?ccid={$ccid}&scid={$scid}&tid={$tid}&sec_cnt={$sec_cnt}&seid={$seid}");
	}
	else
	{
		redirect("admin_category.php");
	}
?>