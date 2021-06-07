<?php include("../../resources/config.php"); ?>
<?php
	if(isset($_GET['id']))
	{ 
		//$_SESSION["ccid"]=$ccid=$_GET['id'];
    $ccid=$_GET['id'];
?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>


  	<div class="bs-example4" data-example-id="contextual-table">
      <div class="row">
        <div class="col-12">
          <a href="admin_category.php" class="btn btn-info btn-sm" style="margin-bottom:15px;">Back To Categories</a>
        </div>
      </div>
  	<div class="row">
  		<div class="col-md-4 col-xs-6"><span><big><b>Course Sub-Category</b></big></span></div>
  		<div class="col-md-8 col-xs-6 text-right">
  		 <a class="" href="admin_insert_subcategory.php?cat_id=<?php echo $ccid; ?>"><button class="btn-primary btn">Add Sub-Category</button></a>
  		</div>
  	</div>
    <div class="row">
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
    <table class="table">
      <thead> 
        <tr>
          <th>S.No</th>
          <th>Sub-Category name</th>
          <th>Add Section</th>
          <th colspan="2" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
<?php 
		$query=query("select * from sub_category where ccid='".$ccid."'");
		confirm($query);

    $sno=1;
		while($row=fetch_array($query))
		{
		$cat=<<< cat
        <tr class="active">
          <td>{$sno}</td>
          <td> {$row['sub_category_name']}</td>
          <td> <a href="admin_test.php?ccid={$ccid}&scid={$row['scid']}">Section Count</a></td>
          <td><a class="btn btn-info" href="admin_update_subcategory.php?id={$row['scid']}&ccid={$row['ccid']}">Update</a>
          </td>
          <td>
            <form action="admin_delete_subcategory.php" method="post">
                <input type="hidden" name="id" value="{$row['scid']}">
                <input type="hidden" name="ccid" value="{$row['ccid']}">
                <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
                <input type="submit" class="btn btn-danger" name="delete_sub_category" value="Delete" onClick="return confirm('If you delete this Sub-Category, then all your Exams, Test, Sections related to this sub-category will be deleted. do you really want to delete this Sub-Category ?')">
            </form>
          </td>
        </tr>
cat;
		print $cat;
    $sno++;
		}
	}
	else
	{
		echo "<script>alert('please set the category for the sub-category')</script>";
	   header("refresh:0;url=admin_category.php");
	}
?>
      </tbody>
    </table>
  </div>
</div>
   </div>

<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 