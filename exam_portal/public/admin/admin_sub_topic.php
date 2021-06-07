<?php include("../../resources/config.php"); ?>

<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

    <?php 
        if(isset($_GET['qtid'])) 
        {
          $qtid=$_GET['qtid'];
     ?>
  	<div class="bs-example4" data-example-id="contextual-table">
      <div class="row">
        <div class="col-12">
          <a href="admin_question_topic.php" class="btn btn-info btn-sm">Back To Topics</a>
        </div>
      </div>
  	<div class="row">
  		<div class="col-md-4 col-xs-6"><span><big><b>Sub Topics</b></big></span></div>
  		<div class="col-md-8 col-xs-6 text-right">
  		 <a class="" href="admin_insert_sub_topic.php?qtid=<?php echo $qtid; ?>"><button class="btn-primary btn">Add Sub Topic</button></a>
  		</div>
  	</div>
    <div class="row">
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
    <table class="table">
      <thead> 
        <tr>
          <th>S.No.</th>
          <th>Sub Topic</th>
          <th>Questions</th>
          <th colspan="2" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
<?php 
		$query=query("select * from question_sub_topic where qtid='{$qtid}'
      ");
		confirm($query);
    $i=1;
		while($row=fetch_array($query))
		{

			$cat=<<< cat
        <tr class="active">
          <td> {$i}</td>
          <td> {$row['question_sub_topic']}</td>
          <td>
          	   <a href="admin_question_bank.php?qstid={$row['qstid']}&qtid={$qtid}">Questions</a>
          </td>
          <td><a class="btn btn-info" href="admin_update_sub_topic.php?qstid={$row['qstid']}&qtid={$qtid}">Update</a>
          </td>
          <td>
            <form action="admin_delete_sub_topic.php" method="post">
                <input type="hidden" name="qtid" value="{$qtid}">
                <input type="hidden" name="qstid" value="{$row['qstid']}">
                <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
                <input type="submit" class="btn btn-danger" name="delete_sub_topic" value="Delete" onClick="return confirm('If you delete this Sub-Topic, then all Questions related to this Sub-Topic will be deleted. do you really want to delete this Sub-Topic ?')">
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
<?php 
  }
  else
  {
    redirect("admin_sub_topic.php?id={$qtid}");
  }
 ?>
<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 