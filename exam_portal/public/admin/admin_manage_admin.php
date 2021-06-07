<?php include("../../resources/config.php"); ?>

<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php
  if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
  {

  if(isset($_POST['delete_admin']) && isset($_POST['id']))
  {
    if($_POST['id']!=1 && $_POST['id']!=2)
    {
      $q_d=query("delete from admin_login where admin_id='".$_POST['id']."'");
      confirm($q_d);
    }
    else
      echo '<script>alert("Right Now, You are not allowed to delete this admin")</script>';
  }
?>

  	<div class="bs-example4" data-example-id="contextual-table">
  	<div class="row">
  		<div class="col-12 col-md-6"><span><big><b><?php echo APP; ?> - Admins</b></big></span></div>
  		<div class="col-12 col-md-6 text-right">
  		 <a href="admin_insert_admin.php"><button class="btn-primary btn">Add Admin</button></a>
  		</div>
  	</div>
    <div class="row">
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
    <table class="table">
      <thead> 
        <tr>
          <th>S.NO.</th>
          <th>Name</th>
          <th>Admin Username</th>
          <th>Admin Account</th>
          <th colspan="2" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
<?php 
		$query=query("select * from admin_login");
		confirm($query);

    $sno=1;
		while($row=fetch_array($query))
		{
      $a="";

			$a.=<<< admin
        <tr class="active">
          <td> {$sno}</td>
          <td> {$row['admin_name']}</td>
          <td> {$row['admin_username']}</td>
admin;

      if($row['role']==1)
      {
        $a.=<<< admin
        <td><p class="text-success">Primary</p></td>
admin;
      }
      else
      if($row['role']==2)
      {
        $a.=<<< admin
        <td><p class="text-warning">Secondary</p></td>
admin;
      }

      $a.=<<< admin
      <td>
        <a class="btn btn-info" href="admin_update_admin.php?id={$row['admin_id']}">Update</a>
      </td>
      <td>
        <form action="" method="post">
            <input type="hidden" name="id" value="{$row['admin_id']}">
            <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
            <input type="submit" class="btn btn-danger" name="delete_admin" value="Delete" onClick="return confirm('Do you really want to delete this Admin ?')">
        </form>
      </td>
    </tr>
admin;
		print $a;
    $sno++;
		}

?>
      </tbody>
    </table>
   </div>
 </div>
</div>

<?php
  }
  else
    echo '<div class="bs-example4" data-example-id="contextual-table"><div class="row">
            <div class="col-12 text-center text-danger">
              <h4 style="margin-top:5em;">You Don\'t Have Permission To Access This Page</h4>
            </div>
          </div></div>';
?>

<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 