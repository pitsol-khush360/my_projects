<?php include("../../resources/config.php"); ?>

<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
?>
  	<div class="bs-example4" data-example-id="contextual-table">
  	<div class="row">
  	<div class="col-md-4 col-xs-6"><span><big><b>User Comment</b></big></span></div>
  	</div>
    <div class="row">
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
    <table class="table">
      <thead> 
        <tr>
          <th>S.No</th>
          <th>Name</th>
          <th>Comment</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
<?php 
		$query=query("select * from user_comments order by ucid desc");
		confirm($query);

    $i=1;
		while($row=fetch_array($query))
		{
      $cat="";

      $query1=query("select * from user_personal where ulid='".$row['ulid']."'");
      confirm($query1);

      $uname="";

      if(mysqli_num_rows($query1)!=0)
      {
        $row1=fetch_array($query1);
        $uname=$row1['name'];
      }

			$cat.=<<<cat
        <tr class="active">
          <td>{$i}</td>
          <td>{$uname}</td>
          <td>{$row['user_comment']}</td>
cat;

        if($row['comment_status']=='N')
        {
          $cat.=<<<cat
          <td><a href="showcomments.php?id={$row['ucid']}&{$row['comment_status']}">Show</a></td>
        </tr>
cat;
        }
        else
        {
          $cat.=<<<cat
          <td><a href="showcomments.php?id={$row['ucid']}&{$row['comment_status']}">Don't Show</a></td>
        </tr>
cat;
        }

        $i++;
		    echo $cat;
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