<?php include("../../resources/config.php"); ?>

<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php
  if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
  {
?>
  	<div class="bs-example4" data-example-id="contextual-table">
  	<div class="row">
  		<div class="col-md-4 col-xs-6"><span><big><b>Result</b></big></span></div>
  	</div>
    <div class="row">
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
    <table class="table">
      <thead> 
        <tr>
          <th>S.No.</th>
          <th>Exam</th>
          <th>Exam Start Time</th>
          <th>Exam End Time</th>
          <th>Result</th>
        </tr>
      </thead>
      <tbody>
<?php 
		$query=query("select * from exam");
      confirm($query);
      $i=1;
      while ($row=fetch_array($query)) 
      {
          $query1=query("select * from result where eid='{$row['eid']}'");
          confirm($query1);
          if(mysqli_num_rows($query1)>0)
          {
            $st=date('d F Y h:i A',strtotime($row['start_time']));
            $et=date('d F Y h:i A',strtotime($row['end_time']));

        			$cat=<<< cat
                <tr class="active">
                  <td> {$i}</td>
                  <td> {$row['title']}</td>
                  <td> {$st}</td>
                  <td> {$et}</td>
                  <td>
                  	   <a href="admin_result.php?eid={$row['eid']}">result</a>
                  </td>
                </tr>
cat;
        		print $cat;
            $i++;

          }

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