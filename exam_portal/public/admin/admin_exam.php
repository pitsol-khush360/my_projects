<?php include("../../resources/config.php"); ?>

<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php
  if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
  {
?>

  	<div class="bs-example4" data-example-id="contextual-table">
  	<div class="row">
  		<div class="col-md-4 col-xs-6"><span><big><b>Exams</b></big></span></div>
  		<div class="col-md-8 col-xs-6 text-right">
  		 <a href="admin_insert_exam.php"><button class="btn-primary btn">Create Exam</button></a>
  		</div>
  	</div>
    <div class="row">
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
    <table class="table">
      <thead> 
        <tr>
          <th>S.No</th>
          <th>Title</th>
          <th>Sub-Category</th>
          <th>Start Timing</th>
          <th>End Timing</th>
          <th>Question in Exam</th>
          <th>Add section & Question</th>
          <th colspan="2" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
<?php 
		$query=query("select * from exam ");
		confirm($query);
    $i=1;
		while($row=fetch_array($query))
		{
      $q_sub=query("select * from sub_category where scid='".$row['scid']."'");
      confirm($q_sub);
      $row1=fetch_array($q_sub);


			$cat=<<< cat
        <tr class="active">
          <td> {$i}</td>
          <td> {$row['title']}</td>
          <td> {$row1['sub_category_name']}</td>
          <td>{$row['start_time']}</td>
          <td>{$row['end_time']}</td>
          <td><a href="admin_show_exam_question.php?eid={$row['eid']}&scid={$row['scid']}">Question of exam</a></td>
          <td><a href="admin_insert_exam_question.php?eid={$row['eid']}&scid={$row['scid']}">Add Section and Question</a>
          </td>
          <td>
              <a class="btn btn-info" href="admin_update_exam.php?eid={$row['eid']}">update</a>
          </td>
          <td>
            <form action="admin_delete_exam.php" method="post">
                <input type="hidden" name="eid" value="{$row['eid']}">
                <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
                <input type="submit" class="btn btn-danger" name="delete_exam" value="Delete" onClick="return confirm('If you delete this Exam, then all your added questions related to this exam, exam set will be removed. do you really want to delete this exam ?')">
            </form>
          </td>
        </tr>
cat;
    $i++;
		print $cat;
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