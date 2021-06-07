<?php include("../../resources/config.php"); ?>

<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php
  if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
  {
?>

  	<div class="bs-example4" data-example-id="contextual-table">
  	<div class="row">
  		<div class="col-md-4 col-xs-6"><span><big><b>Frequently Asked Questions</b></big></span></div>
  		<div class="col-md-8 col-xs-6 text-right">
  		 <a class="" href="admin_insert_faq.php"><button class="btn-primary btn">Add FAQ</button></a>
  		</div>
  	</div>
     <div class="row">
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
    <table class="table">
      <thead> 
        <tr>
          <th>S.NO.</th>
          <th>Question</th>
          <th>Answer</th>
          <th class="text-center" colspan="2">Action</th>
        </tr>
      </thead>
      <tbody>
<?php 
		$query=query("select * from faqs order by faq_id desc");
		confirm($query);
    $i=1;
		while($row=fetch_array($query))
		{
			$cat=<<< cat
        <tr class="active">
          <td>{$i}</td>
          <td> {$row['question']}</td>
          <td> {$row['answer']}</td>
          <td>
            <a class="btn btn-info" href="admin_update_faq.php?id={$row['faq_id']}">Update</a>
          </td>
          <td>
            <form action="admin_delete_faq.php" method="post">
                <input type="hidden" name="id" value="{$row['faq_id']}">
                <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
                <input type="submit" class="btn btn-danger" name="delete_faq" value="Delete">
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