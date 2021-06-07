<?php include("../../resources/config.php"); ?>

<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>


  	<div class="bs-example4" data-example-id="contextual-table">
  	<div class="row">
  		<div class="col-md-4 col-xs-6"><span><big><b>Topics</b></big></span></div>
  		<div class="col-md-8 col-xs-6 text-right">
  		 <a class="" href="admin_insert_question_topic.php"><button class="btn-primary btn">Add Topic</button></a>
  		</div>
  	</div>
    <div class="row">
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
    <table class="table">
      <thead> 
        <tr>
          <th>S.No.</th>
          <th>Topic</th>
          <th>Sub-Topic</th>
          <th class="text-center" colspan="2">Action</th>
        </tr>
      </thead>
      <tbody>
<?php 
		$query=query("select * from question_topic");
		confirm($query);
    $i=1;
		while($row=fetch_array($query))
		{

			$cat=<<< cat
        <tr class="active">
          <td> {$i}</td>
          <td> {$row['question_topic']}</td>
          <td>
          	   <a href="admin_sub_topic.php?qtid={$row['qtid']}">Sub Topic</a>
          </td>
          <td>
            <a href="admin_update_question_topic.php?qtid={$row['qtid']}" class="btn btn-info">Update</a>
          </td>
          <td>
            <form action="admin_delete_question_topic.php" method="post">
                <input type="hidden" name="qtid" value="{$row['qtid']}">
                <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
                <input type="submit" class="btn btn-danger" name="delete_topic" value="Delete" onClick="return confirm('If you delete this Topic, then all Sub-Topics, Questions related to this Topic will be deleted. do you really want to delete this section ?')">
            </form>
          </td>
        </tr>
cat;
		print $cat;
    $i++;
		}

?>
      </tbody>
    </table>
  </div>
</div>
   </div>

<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 