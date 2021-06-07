<?php include("../../resources/config.php"); ?>
<?php
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
	if(isset($_SESSION['admin_username']) && isset($_GET['start']) && isset($_GET['end']))
	{
		$start=$_GET['start'];
		$end=$_GET['end'];

		$query=query("SELECT ul.*, up.* FROM user_login ul 
                  LEFT JOIN user_personal up ON ul.userid = up.ulid order by ul.userid DESC LIMIT ".$start.",".$end);
      confirm($query);

    	$cat="";
    	$i=1;
    while($row=fetch_array($query))
    {
      $profile_path=image_path_profile($row['profile_picture']);

      $cat.=<<< cat
        <tr class="active">
          <td> {$i}</td>
          <td><img src="../../resources/{$profile_path}" width="30" height="30"></td>
          <td>{$row['username']}</td>
          <td>{$row['password']}</td>
          <td style="word-break:break-all;"> {$row['name']}</td>
          <td>{$row['mobile']}</td>
          <td>{$row['email']}</td>
          <td>{$row['district']}<br>{$row['state']}<br>{$row['country']}</td>
cat;

  if($row['blocked']==1)
  {
      $cat.=<<< cat
      <td><p class="text-danger">Blocked</p></td>
      <td>
        <form action="blocked.php" method="post">
            <input type="hidden" name="userid" value="{$row['userid']}">
            <input type="hidden" name="action" value="unblock">
            <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
            <input type="submit" class="btn btn-warning" name="block_unblock_user" value="Unblock">
        </form>
      </td>
cat;
   }
    else 
    {
      $cat.=<<<cat
      <td><p class="text-success">Active</p></td>
      <td>
        <form action="blocked.php" method="post">
            <input type="hidden" name="userid" value="{$row['userid']}">
            <input type="hidden" name="action" value="block">
            <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
            <input type="submit" class="btn btn-danger" name="block_unblock_user" value="Block">
        </form>
      </td>
cat;
    }

    $cat.=<<<cat
    <td>
        <a class="btn btn-info" href="admin_update_users.php?ulid={$row['userid']}&upid={$row['upid']}">Update</a>
    </td>
    </tr>
cat;
    $i++;
    }
        echo $cat;
	}
}
?>