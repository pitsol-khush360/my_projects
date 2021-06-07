<?php include("../../resources/config.php"); ?>

<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php
  if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
  {
?>
  	<div class="bs-example4" data-example-id="contextual-table">
  	<div class="row">
  		<div class="col-md-4 col-xs-6"><span><big><b>Offers</b></big></span></div>
  		<div class="col-md-8 col-xs-6 text-right">
  		 <a class="" href="admin_insert_offer.php"><button class="btn-primary btn">Add Offers</button></a>
  		</div>
  	</div>
    <div class="row">
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
    <table class="table">
      <thead> 
        <tr>
          <th>S.No.</th>
          <th>ccid</th>
          <th>Course Category</th>
          <th>Amount</th>
          <th>Offer Text</th>
          <th>Offer Type</th>
          <th>Offer Start Date</th>
          <th>Offer End Date</th>
          <th>Offer image</th>
          <th colspan="2" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
<?php 
		$query=query("select * from offers");
		confirm($query);
    $i=1;
		while($row=fetch_array($query))
		{
      $offer_path=image_path_offer($row['offer_image']);

      $category_name="";

      $o_c=query("select category_name from course_category where ccid='".$row['ccid']."'");
      confirm($o_c);

      if(mysqli_num_rows($o_c)!=0)
      {
        $ro_c=fetch_array($o_c);
        $category_name=$ro_c['category_name'];
      }

			$cat=<<< cat
        <tr class="active">

          <td> {$i}</td>

          <td>{$row['ccid']}</td>

          <td>{$category_name}</td>

          <td>{$row['amount']}</td>

          <td>{$row['offer_text']}</td>

          <td>{$row['offer_type']}</td>

          <td>{$row['start_date']}</td>

          <td>{$row['end_date']}</td>
        
          <td><img src="../../resources/{$offer_path}" width="30" height="30"></td>

          <td>
            <a class="btn btn-info" href="admin_update_offer.php?offer_id={$row['offer_id']}">Update</a>
          </td>
          <td>
            <form action="admin_delete_offer.php" method="post">
                <input type="hidden" name="offer_id" value="{$row['offer_id']}">
                <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
                <input type="submit" class="btn btn-danger" name="delete_offer" value="Delete" onClick="return confirm('This offer is applied to some course categories. do you really want to delete this offer ?')">
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
    echo '<div class="bs-example4" data-example-id="contextual-table"><div class="row">
            <div class="col-12 text-center text-danger">
              <h4 style="margin-top:5em;">You Don\'t Have Permission To Access This Page</h4>
            </div>
          </div></div>';
?>

<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 