<?php include("../../resources/config.php"); ?>
<?php 
	if(isset($_POST['delete_exam']) && isset($_POST['eid']))
	{
		$eid=$_POST['eid'];

		// using join
		// inner join will only select common rows between two tables
		// left join selects first table rows addition to the matched row with second table

		// temporary disabling foreign key check, after delete re-enabling foreign key check
		$query=query("SET foreign_key_checks = 0");
		confirm($query);

		$query=query("DELETE e, eq FROM exam e LEFT JOIN exam_questions eq ON e.eid=eq.eid where e.eid='".$eid."'");
		confirm($query);

		$query=query("SET foreign_key_checks = 1");
		confirm($query);

		redirect("admin_exam.php");
	}
	else
	{
		redirect("admin_exam.php?err");
	}
?>