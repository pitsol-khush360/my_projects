<?php include("../../resources/config.php"); ?>
<?php
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
	if(isset($_SESSION['admin_username']) && isset($_GET['start']) && isset($_GET['end']))
	{
		$start=$_GET['start'];
		$end=$_GET['end'];

		$query=query("select * from user_query order by uq_id desc limit $start,$end");
    confirm($query);

    $i=1;
    $cat="";
    while($row=fetch_array($query))
    {
      $cat.=<<< cat
        <tr class="active">
          <td> {$i}</td>
          <td style="word-break:break-all;"> {$row['uq_name']}</td>
          <td> {$row['uq_email']}</td>
          <td> {$row['uq_mobile']}</td>
          <td> {$row['uq_query']}</td>
cat;
          if($row['uq_status']==0)
          {
            $cat.=<<< cat
            <td><a href="user_query_fullfill.php?uqid={$row['uq_id']}&email={$row['uq_email']}&mobile={$row['uq_mobile']}">Pending</a></td>
cat;
          }
          else
          {
            $cat.=<<< cat
            <td><a href="user_query_fullfill.php?uqid={$row['uq_id']}&email={$row['uq_email']}&mobile={$row['uq_mobile']}">FullFilled</a></td>
cat;
          }
    $i++;
    }
    echo $cat;
	}
}
?>