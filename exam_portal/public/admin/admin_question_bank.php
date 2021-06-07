<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php 
    if(isset($_GET['qstid'])&&isset($_GET['qtid'])) 
    {
      $qtid=$_GET['qtid'];
      $qstid=$_GET['qstid'];
      $query=query("select * from question where qstid='{$qstid}'");
      confirm($query);
?>

	<div class="bs-example4" data-example-id="contextual-table">
    <div class="row">
      <div class="col-12">
        <a href="admin_sub_topic.php?qtid=<?php echo $qtid; ?>" class="btn btn-info btn-sm">Back To Sub-Topics</a>
      </div>
    </div>
  	<div class="row">
  		<div class="col-md-4 col-xs-6"><span><big><b>Questions</b></big></span></div>
  		<div class="col-md-8 col-xs-6 text-right">
  		 <a href="admin_insert_question_bank.php?qstid=<?php echo $qstid; ?>&qtid=<?php echo $qtid; ?>">
          <button class="btn-primary btn">
            Add questions
          </button>
        </a>
  		</div>
  	</div>
    <div class="row">
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
    <table class="table">
      <thead> 
        <tr> 
          <th>S.No.</th>
          <th>Question</th>
          <th>First Option</th>
          <th>Second Option</th>
          <th>Third Option</th>
          <th>Fourth Option</th>
          <th>Fifth Option</th>
          <th>Qusetion Image</th>
          <th>Answer</th>
          <th>For Practice</th>
          <th>Description</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
<?php 
    $i=1;
		while($row=fetch_array($query))
		{
      $question=html_entity_decode($row['question']);
      $question_op1=html_entity_decode($row['op1']);
      $question_op2=html_entity_decode($row['op2']);
      $question_op3=html_entity_decode($row['op3']);
      $question_op4=html_entity_decode($row['op4']);
      $question_op5="";
      if($row['op5']!="")
        $question_op5=html_entity_decode($row['op5']);

			echo "
        <tr class='active'>
          <td>{$i}</td>
          <td style='width:100px;'> {$question}</td>
          <td style='max-width:150px;'> {$question_op1}</td>
          <td style='max-width:150px;'> {$question_op2}</td>
          <td style='max-width:150px;'> {$question_op3}</td>
          <td style='max-width:150px;'> {$question_op4}</td>
          <td style='max-width:150px;'> {$question_op5}</td>
          <td>";
            if (!empty($row['question_img'])) 
            {
            echo"<img height='80' width='80' src='../../resources/adminquestion_upload/{$row['question_img']}'>";
            }
      echo"
          </td>
          <td> {$row['answer']}</td>";

          if($row['for_practice']==1)
            echo "<td>Yes</td>";
          else
            echo "<td>No</td>";

          echo "<td style='max-width:200px;'>{$row['description']}</td>";

          echo "
          <td>
          <a class='btn btn-info' href='admin_update_question_bank.php?qid={$row['qid']}&qtid={$row['qtid']}&qstid={$row['qstid']}'>Update</a>
          
          <form action='admin_delete_question_bank.php' method='post'>
                <input type='hidden' name='qid' value='{$row['qid']}'>
                <input type='hidden' name='qtid' value='{$row['qtid']}'>
                <input type='hidden' name='qstid' value='{$row['qstid']}'>
                <input type='hidden' name='{$_SESSION['csrf_name']}' value='{$_SESSION['csrf_value']}'>
                <input type='submit' class='btn btn-danger' name='delete_question' value='Delete' onClick=\"return confirm('This Question can be a part of your section or of your exam. do you really want to delete this Question ?')\">
            </form>
          </td>
        </tr>";
    $i++;
		}

?>
      </tbody>
    </table>
    <div class="row">
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
   </div>
<?php }
      else
      {
         redirect("admin_question_topic.php");
      }
?>
<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 