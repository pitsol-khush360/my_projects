<?php include("../../resources/config.php"); ?>

<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php 
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
        if(isset($_GET['eid'])) 
        {
          $eid= $_GET['eid'];
          $exam_name="";

          $query_e=query("select title from exam where eid='{$eid}'");
          confirm($query_e);

          if(mysqli_num_rows($query_e)!=0)
          {
            $r_e=fetch_array($query_e);
            $exam_name=$r_e['title'];
          }
?>

  	<div class="bs-example4" data-example-id="contextual-table">
  	<div class="row">
  		<div class="col-12 col-md-6"><span><big><b>" <?php echo $exam_name; ?> " Result</b></big></span></div>
      <div class="col-12 col-md-6 text-right">
        <form action="admin_result_download.php" method="post">
            <input type="hidden" name="eid" value="<?php echo $eid; ?>">
            <input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
            <input type="submit" class="btn btn-warning" name="result_download" value="Download Result PDF">
        </form>
      </div>
  	</div>
    <div class="row">
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
    <table class="table">
      <thead> 
        <tr>
          <th>S.No</th>
          <th>Name</th>
          <th>Username</th>
          <th>Total Question </th>
          <th>Total Attempted</th>
          <th>Right</th>
          <th>Wrong</th>
          <th>Marks Obtained</th>
          <th>Total Marks</th>
        </tr>
      </thead>
      <tbody id="result_body">
<?php 
		$query=query("select * from result where eid='{$eid}' order by marks_obtained desc");
		confirm($query);

    $i=1;

		while($row=fetch_array($query))
		{
      $name="";
      $username="";

      $q_ul=query("select * from user_login  where userid='{$row['ulid']}' ");
      confirm($q_ul);

      if(mysqli_num_rows($q_ul)!=0)
      {
        $r_ul=fetch_array($q_ul);
        $username=$r_ul['username'];
      }

      $q_up=query("select * from user_personal where ulid ='{$r_ul['userid']}'");
      confirm($q_up);

      if(mysqli_num_rows($q_ul)!=0)
      {
        $r_up=fetch_array($q_up);
        $name=$r_up['name'];
      }

			$cat=<<< cat
        <tr class="active">
          <td> {$i}</td>
          <td> {$name}</td>
          <td> {$username}</td>
          <td> {$row['total_questions']}</td>
          <td> {$row['total_attempted']}</td>
          <td> {$row['right_questions']}</td>
          <td> {$row['wrong_questions']}</td>
          <td> {$row['marks_obtained']}</td>
          <td> {$row['total_marks']}</td>
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

    <div class="row">
      <div class="col-12 text-center">
        <?php 
              // $qp=query("select * from result where eid='{$eid}'");
              // confirm($qp);

              // if(mysqli_num_rows($qp)!=0)
              // {
              //   $p=intval(mysqli_num_rows($qp));

              //   $n=intval($p/10);
              //   $rem=intval($p%10);

              //   $pages=$n;

              //   if($rem!=0)
              //     $pages+=1;

              //   $i=1;
              //   $start=0;
              //   $end=10;
              //   // Pagination
              //   echo "<div class='row'>
              //           <div class='col-md-12 text-center'>
              //             <ul class='pagination'>
              //               <li class='active page' start='$start' end='$end'><span style='cursor:pointer;'>$i</span></li>";
              //               $start+=10;

              //   for($i=2;$i<=$pages;$i++)
              //   {
              //     echo "<li class='page' start='$start' end='$end'><span style='cursor:pointer;'>$i</span></li>";
              //     $start+=10;
              //   }

              //   echo '</ul>
              //           </div>
              //             </div>';
              // }
              
            ?>
      </div>
    </div>
   </div>
<?php 
}
else
{
  redirect("admin_result_exam.php");
} 
}
else
    echo '<div class="bs-example4" data-example-id="contextual-table"><div class="row">
            <div class="col-12 text-center text-danger">
              <h4 style="margin-top:5em;">You Don\'t Have Permission To Access This Page</h4>
            </div>
          </div></div>';
?>

<script>
  // $(".page").on("click",
  //   function()
  //   {
  //     start=$(this).attr("start");
  //     end=$(this).attr("end");
  //     eid=<?php //echo $_GET['eid']; ?>;

  //     $(".page").removeClass("active");   // remove active class from current page.
  //     $(this).addClass("active");         // add active class to clicked page.
      
  //     $.get("change_resulttable.php?start="+start+"&end="+end+"&eid="+eid,
  //       function(data,status)
  //       {
  //         $("#result_body").html(data);
  //       });
  //   });
</script>

<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 