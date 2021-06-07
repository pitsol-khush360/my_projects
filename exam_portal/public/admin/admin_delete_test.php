<?php include("../../resources/config.php"); ?>
<?php 
	if(isset($_POST['tid'])&&isset($_POST['scid']))
	{
		$tid=$_POST['tid'];
		$ccid=$_POST['ccid'];
		$scid=$_POST['scid'];

		/*
			Dependency Flow :
			______________________

			test(tid) -> section (tid)
			section (seid) -> question_bank (seid)

		*/

		$query=query("SET foreign_key_checks = 0");
		confirm($query);

		$query=query("DELETE t, s, qb FROM test t
			LEFT JOIN section s ON (t.tid = s.tid)
			LEFT JOIN question_bank qb ON (s.seid = qb.seid)
			WHERE t.tid = '".$tid."'");
		confirm($query);

		$query=query("SET foreign_key_checks = 1");
		confirm($query);

		redirect("admin_test.php?ccid={$ccid}&scid={$scid}");
	}
	else
	{
		redirect("admin_category.php");
	}
?>