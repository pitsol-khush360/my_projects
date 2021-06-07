<?php include("../../resources/config.php"); ?>
<?php
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
	if(isset($_SESSION['admin_username']) && isset($_GET['start']) && isset($_GET['end']) && isset($_GET['eid']))
	{
    $eid=$_GET['eid'];
		$start=$_GET['start'];
		$end=$_GET['end'];

		$query=query("select * from result where eid='{$eid}' order by marks_obtained desc limit $start,$end");
    confirm($query);

    $i=1;
    $cat="";
    while($row=fetch_array($query))
    {
          $q_ul=query("select * from user_login  where userid='{$row['ulid']}' ");
          confirm($q_ul);
          $r_ul=fetch_array($q_ul);

          $q_up=query("select * from user_personal where ulid ='{$r_ul['userid']}'");
          confirm($q_up);
          $r_up=fetch_array($q_up);

      $cat.=<<< cat
        <tr class="active">
          <td> {$i}</td>
          <td> {$r_up['name']}</td>
          <td> {$r_ul['username']}</td>
          <td> {$row['total_questions']}</td>
          <td> {$row['total_attempted']}</td>
          <td> {$row['right_questions']}</td>
          <td> {$row['wrong_questions']}</td>
          <td> {$row['marks_obtained']}</td>
          <td> {$row['total_marks']}</td>
        </tr>
cat;
    $i++;
    }
    echo $cat;
	}
}
?>