<?php include("../../resources/config.php"); ?>

<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php
  if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
  {
      if(isset($_POST['delete_subcourse_plan']) && isset($_POST['id']))
      {
        $delete_plan=query("delete from subcourse_plans where id='".$_POST['id']."'");
        confirm($delete_plan);
      }
?>
  	<div class="bs-example4" data-example-id="contextual-table">
  	<div class="row">
  		<div class="col-md-4 col-xs-6"><span><big><b>Sub-Courses Plan</b></big></span></div>
  		<div class="col-md-8 col-xs-6 text-right">
  		 <a href="admin_insert_subcourse_plan.php"><button class="btn-primary btn">Add Plan For Sub-Course</button></a>
  		</div>
  	</div>
    <div class="row">
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
    <table class="table">
      <thead> 
        <tr>
          <th>S.NO.</th>
          <th>Course</th>
          <th>Sub-Course</th>
          <th>Plan Type</th>
          <th>Amount</th>
          <th>Duration (In Months) (Course Payment remain valid till this duration)</th>
          <th colspan="2" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
<?php 
		$query=query("SELECT scp.*, cc.category_name, sc.sub_category_name FROM subcourse_plans scp 
                  LEFT JOIN course_category cc ON scp.ccid=cc.ccid
                  LEFT JOIN sub_category sc ON scp.scid = sc.scid
                  ");
		confirm($query);

    $sno=1;
		while($row=fetch_array($query))
		{
			$cat=<<< cat
        <tr class="active">
          <td>{$sno}</td>
          <td>{$row['category_name']}</td>
          <td>{$row['sub_category_name']}</td>
          <td>{$row['plan_type']}</td>
          <td>{$row['amount']}</td>
          <td>{$row['duration']}</td>
          <td>
            <a class="btn btn-info" href="admin_update_subcourse_plan.php?scpid={$row['id']}&ccid={$row['ccid']}">Update</a>
          </td>
          <td>
            <form action="" method="post">
                <input type="hidden" name="id" value="{$row['id']}">
                <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
                <input type="submit" class="btn btn-danger" name="delete_subcourse_plan" value="Delete" onClick="return confirm('Do you really want to delete this sub-course plan ?')">
            </form>
          </td>
        </tr>
cat;
		print $cat;
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