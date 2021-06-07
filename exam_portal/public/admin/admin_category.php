<?php include("../../resources/config.php"); ?>

<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php
  if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
  {
?>
  	<div class="bs-example4" data-example-id="contextual-table">
  	<div class="row">
  		<div class="col-md-4 col-xs-6"><span><big><b>Course Category</b></big></span></div>
  		<div class="col-md-8 col-xs-6 text-right">
  		 <a class="" href="admin_insert_category.php"><button class="btn-primary btn">Add Category</button></a>
  		</div>
  	</div>
    <div class="row">
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
    <table class="table">
      <thead> 
        <tr>
          <th>S.NO.</th>
          <th>Category name</th>
          <th>Sub-Category</th>
          <th colspan="2" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
<?php 
		$query=query("select * from course_category ");
		confirm($query);

    $sno=1;
		while($row=fetch_array($query))
		{

			$cat=<<< cat
        <tr class="active">
          <td> {$sno}</td>
          <td> {$row['category_name']}</td>
          <td>
          	   <a href="admin_subcategory.php?id={$row['ccid']}">Sub_Category</a>
          </td>
          <td>
            <a class="btn btn-info" href="admin_update_category.php?id={$row['ccid']}">Update</a>
          </td>
          <td>
            <form action="admin_delete_category.php" method="post">
                <input type="hidden" name="id" value="{$row['ccid']}">
                <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
                <input type="submit" class="btn btn-danger" name="delete_category" value="Delete" onClick="return confirm('If you delete this Category, then all your Sub-Categories, Exams, Test, Offers, User amount paid records, Sections related to this Category will be deleted. do you really want to delete this category ?')">
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