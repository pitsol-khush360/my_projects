<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php 
    if(isset($_GET['tid']) && isset($_GET['ccid']) && isset($_GET['scid']) && isset($_GET['sec_cnt'])) 
    { 
      $tid=$_GET['tid'];
      $ccid=$_GET['ccid'];
      $scid=$_GET['scid'];
      $sec_cnt=$_GET['sec_cnt'];
      $query=query("select * from section where tid ='".$tid."';");
      confirm($query);
?>

	<div class="bs-example4" data-example-id="contextual-table">
    <div class="row">
      <div class="col-12">
        <a href="admin_test.php?ccid=<?php echo $ccid; ?>&scid=<?php echo $scid; ?>" class="btn btn-info btn-sm">Back To Sub-Category Total Sections</a>
      </div>
    </div>
  	<div class="row">
  		<div class="col-md-4 col-xs-6"><span><big><b>Section</b></big></span></div>
  		<div class="col-md-8 col-xs-6 text-right">
  		 <a href="admin_insert_section.php?ccid=<?php echo $ccid; ?>&scid=<?php echo $scid; ?>&tid=<?php echo $tid; ?>&sec_cnt=<?php echo $sec_cnt; ?>">
          <button class="btn-primary btn">
            Add Section
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
          <th>Name of Section</th>
          <th>Number of Questions</th>
          <th>Section Timing(in minute)</th>
          <th>Positive Marks(per question)</th>
          <th>Negative Marks(per question)</th>
          <th>Maximum Marks</th>
          <th>Add Questions</th>
          <th class="text-center" colspan="2">Action</th>
        </tr>
      </thead>
      <tbody>
<?php 
    $i=1;
		while($row=fetch_array($query))
		{
			$cat=<<< cat
        <tr class="active">
          <td> {$i}</td>
          <td> {$row['section_name']}</td>
          <td> {$row['section_question_no']}</td>
          <td> {$row['section_timing']}</td>
          <td> {$row['pm']}</td>
          <td> {$row['nm']}</td>
          <td> {$row['mm']}</td>
          <td><a href="admin_question.php?ccid={$ccid}&scid={$scid}&tid={$tid}&sec_cnt={$sec_cnt}&seid={$row['seid']}">Question</a></td>
          <td><a class="btn btn-info" href="admin_update_section.php?ccid={$ccid}&scid={$scid}&tid={$tid}&sec_cnt={$sec_cnt}&seid={$row['seid']}">Update</a>
          </td>
          <td>
            <form action="admin_delete_section.php" method="post">
                <input type="hidden" name="tid" value="{$tid}">
                <input type="hidden" name="sec_cnt" value="{$sec_cnt}">
                <input type="hidden" name="ccid" value="{$ccid}">
                <input type="hidden" name="scid" value="{$scid}">
                <input type="hidden" name="seid" value="{$row['seid']}">
                <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
                <input type="submit" class="btn btn-danger" name="delete_section" value="Delete" onClick="return confirm('If you delete this section, then all your added questions related to this section will be removed. do you really want to delete this section ?')">
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
<?php }
      else
      {
        redirect("admin_category.php");
      }
?>
<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 
