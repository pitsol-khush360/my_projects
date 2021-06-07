<?php include("../../resources/config.php"); ?>
<?php 
	if(isset($_POST['delete_section']) && isset($_POST['ccid'])&&isset($_POST['scid'])&&isset($_POST['seid'])&&isset($_POST['sec_cnt'])&&isset($_POST['tid']))
	{
		$tid=$_POST['tid'];
		$ccid=$_POST['ccid'];
		$scid=$_POST['scid'];
		$seid=$_POST['seid'];
		$sec_cnt=$_POST['sec_cnt'];

		/*
			Dependency Flow :
			______________________

			section (seid) -> question_bank (seid)

		*/

		$query=query("SET foreign_key_checks = 0");
		confirm($query);

		$query=query("DELETE s, qb FROM section s
			LEFT JOIN question_bank qb ON (s.seid = qb.seid)
			WHERE s.seid = '".$seid."'");
		confirm($query);

		$query=query("SET foreign_key_checks = 1");
		confirm($query);		

		redirect("admin_section.php?ccid=".$ccid."&scid=".$scid."&tid=".$tid."&sec_cnt=".$sec_cnt);
	}
	else
	{
		redirect("admin_category.php");
	}
?>