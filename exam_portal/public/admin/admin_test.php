<?php include("../../resources/config.php"); ?>

<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php 
    if(isset($_GET['ccid']) && isset($_GET['scid'])) 
    {
      $scid=$_GET['scid'];
      $ccid=$_GET['ccid'];

      $query1=query("select * from sub_category where scid='".$scid."';");
      confirm($query1);
      $row1=fetch_array($query1);

      $query=query("select * from test where scid='".$scid."' ");
      confirm($query);
?>


  	<div class="bs-example4" data-example-id="contextual-table">
      <div class="row">
      <div class="col-12">
        <a href="admin_subcategory.php?id=<?php echo $ccid; ?>" class="btn btn-info btn-sm">Back To Sub-Categories</a>
      </div>
    </div>
  	<div class="row">
  		<div class="col-md-4 col-xs-6"><span><big><b>Number of Section</b></big></span></div>
  		<div class="col-md-8 col-xs-6 text-right">
      <?php if (mysqli_num_rows($query)==0) {?>
  		 <a class="" href="admin_insert_test.php?ccid=<?php echo $ccid; ?>&scid=<?php echo $scid; ?>"><button class="btn-primary btn">Add Number of Section </button></a>
      <?php } ?>
  		</div>
  	</div>
    <div class="row">
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
    <table class="table">
      <thead> 
        <tr>
          <th>S.No</th>
          <th>Sub-Category Name</th>
          <th>No. of section</th>
          <th>Sections</th>
          <th colspan="2" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>  
<?php 

    $cnt=1;
		while($row=fetch_array($query))
		{
			$cat=<<< cat
        <tr class="active">
          <td> {$cnt}</td>
          <td> {$row1['sub_category_name']}</td>
           <td> {$row['no_of_section']}</td>
          <td>
          	   <a href="admin_section.php?ccid={$ccid}&scid={$scid}&tid={$row['tid']}&sec_cnt={$row['no_of_section']}">Section</a>
          </td>
          <td><a class="btn btn-info" href="admin_update_test.php?tid={$row['tid']}&ccid={$ccid}&scid={$scid}">Update</a></td>
          <td>
            <form action="admin_delete_test.php" method="post">
                <input type="hidden" name="tid" value="{$row['tid']}">
                <input type="hidden" name="ccid" value="{$ccid}">
                <input type="hidden" name="scid" value="{$scid}">
                <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
                <input type="submit" class="btn btn-danger" name="delete_test" value="Delete" onClick="return confirm('If you delete these total sections from sub-category, then all your Section, Question Bank related to this total sections will be deleted. do you really want to delete total sections from this sub-category ?')">
            </form>
          </td>
        </tr>
cat;
    $cnt++;
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
    {
       redirect("admin_category.php");
    }
?>

<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 