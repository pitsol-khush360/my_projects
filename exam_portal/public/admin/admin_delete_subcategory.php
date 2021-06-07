<?php include("../../resources/config.php"); ?>

<?php
	if(isset($_POST['id']) && isset($_POST['ccid']))
	{
		$scid=$_POST['id'];

		/*
			Dependency Flow :
			______________________

			sub_category (scid) -> exam (eid)
			exam(eid) -> exam_questions (eid)
			exam(eid) -> user_exam_paid (eid)

			sub_category (scid) -> test(scid)
			test(tid) -> section (tid)
			section (seid) -> question_bank (seid)

		*/

		$query=query("SET foreign_key_checks = 0");
		confirm($query);

		$query=query("DELETE sc, e, eq, uep, t, s, qb FROM sub_category sc
			LEFT JOIN exam e ON (sc.scid = e.scid)
			LEFT JOIN exam_questions eq ON (e.eid = eq.eid)
			LEFT JOIN user_exam_paid uep ON (e.eid = uep.eid)
			LEFT JOIN test t ON (sc.scid = t.scid)
			LEFT JOIN section s ON (t.tid = s.tid)
			LEFT JOIN question_bank qb ON (s.seid = qb.seid)
			WHERE sc.scid = '".$scid."'");
		confirm($query);

		$query=query("SET foreign_key_checks = 1");
		confirm($query);

		redirect("admin_subcategory.php?id={$_POST['ccid']}");
	}
	else
	{
		redirect("admin_category.php");
	}
?>