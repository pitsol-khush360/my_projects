<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php 
    if(isset($_GET['tid'])&&isset($_GET['seid'])&& isset($_GET['ccid']) && isset($_GET['scid']) && isset($_GET['sec_cnt'])) 
    {

      $tid=$_GET['tid'];
      $seid=$_GET['seid'];
      $ccid=$_GET['ccid'];
      $scid=$_GET['scid'];
      $sec_cnt=$_GET['sec_cnt'];

      $query=query("select * from question_bank where seid ='".$seid."';");
      confirm($query);
?>

	<div class="bs-example4" data-example-id="contextual-table">
    <div class="row">
      <div class="col-12">
        <a href="admin_section.php?ccid=<?php echo $ccid; ?>&scid=<?php echo $scid; ?>&tid=<?php echo $tid; ?>&sec_cnt=<?php echo $sec_cnt; ?>" class="btn btn-info btn-sm">Back To Sub-Category Sections</a>
      </div>
    </div>
  	<div class="row">
  		<div class="col-md-4 col-xs-6"><span><big><b>Questions</b></big></span></div>
  		<div class="col-md-8 col-xs-6 text-right">
  		 <a href="admin_insert_question.php?ccid=<?php echo $ccid; ?>&scid=<?php echo $scid; ?>&tid=<?php echo $tid; ?>&sec_cnt=<?php echo $sec_cnt; ?>&seid=<?php echo $seid; ?>">
          <button class="btn-primary btn">
            Add questions
          </button>
        </a>
  		</div>
  	</div>
     <div class="row">
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
    <table class="table" style="table-layout:fixed;">
      <thead> 
        <tr>
          <th style="width:60px;">S.No.</th>
          <th style="width:80%;">Question</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
<?php 
    $i=1;
		while($row=fetch_array($query))
		{
      $query1=query("select * from question where qid ='".$row['qids']."';");
      confirm($query1);  

      if(mysqli_num_rows($query1)!=0)
      {
        $row1=fetch_array($query1);
        $question=html_entity_decode($row1['question']);
  			$cat=<<< cat
          <tr class="active">
            <td style="width:60px;">{$i}</td>
            <td style="width:80%;word-break:break-word;">{$question}</td>
            <td>
              <form action="admin_delete_question.php" method="post">
                  <input type="hidden" name="tid" value="{$tid}">
                  <input type="hidden" name="sec_cnt" value="{$sec_cnt}">
                  <input type="hidden" name="ccid" value="{$ccid}">
                  <input type="hidden" name="scid" value="{$scid}">
                  <input type="hidden" name="seid" value="{$row['seid']}">
                  <input type="hidden" name="qid" value="{$row['qid']}">
                  <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
                  <input type="submit" class="btn btn-danger" name="delete_section_question" value="Delete" onClick="return confirm('This Question can be a part of your section added to exam, If so then this question will appear in exam. Do you really want to delete this question ?')">
              </form>
            </td>
          </tr>
cat;
      $i++;
  		print $cat;
      }
		}

?>
      </tbody>
    </table>
   </div>
 </div>
</div>
<?php }
      else
      {
         redirect("admin_category.php");
      }
?>
<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 
      