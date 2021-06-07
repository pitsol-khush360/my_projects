<?php include("../../resources/config.php"); ?>

<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
?>
  	<div class="bs-example4" data-example-id="contextual-table">
  	<div class="row text-center" style="margin:1rem auto 3rem auto;">
      <div class="col-md-12 col-xs-12"><span><big><b>Users</b></big></span></div>
    </div>
    <div class="row">
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
    <table class="table table-hover">
      <thead> 
        <tr>
          <th>S.No.</th>
          <th>Profile</th>
          <th>Username</th>
          <th>Password</th>
          <th>Name</th>
          <th>Mobile</th>
          <th>Email</th>
          <th>District,State,Country</th>
          <th>Status</th>
          <th>Change Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="users_body">
<?php 
    // for search bar
    if(isset($_POST['search']) && trim($_POST['search'])!="") 
    {
      $search=$_POST['search'];
      $query=query("SELECT ul.*, up.* FROM user_login ul 
                  LEFT JOIN user_personal up ON ul.userid = up.ulid 
                  WHERE (up.name like '%".$search."%' OR up.email like '%".$search."%' OR up.mobile like '%".$search."%')");
      confirm($query);
    }
    else
    {
      $query=query("SELECT ul.*, up.* FROM user_login ul 
                  LEFT JOIN user_personal up ON ul.userid = up.ulid order by ul.userid DESC LIMIT 0,10");
      confirm($query);
    }
    $i=1;
    $cat="";
		while($row=fetch_array($query))
		{
      $profile_path=image_path_profile($row['profile_picture']);

  		$cat.=<<< cat
        <tr class="active">
          <td> {$i}</td>
          <td><img src="../../resources/{$profile_path}" width="30" height="30"></td>
          <td>{$row['username']}</td>
          <td>{$row['password']}</td>
          <td style="word-break:break-all;"> {$row['name']}</td>
          <td>{$row['mobile']}</td>
          <td>{$row['email']}</td>
          <td>{$row['district']}<br>{$row['state']}<br>{$row['country']}</td>
cat;

  if($row['blocked']==1)
  {
      $cat.=<<< cat
      <td><p class="text-danger">Blocked</p></td>
      <td>
        <form action="blocked.php" method="post">
            <input type="hidden" name="userid" value="{$row['userid']}">
            <input type="hidden" name="action" value="unblock">
            <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
            <input type="submit" class="btn btn-warning" name="block_unblock_user" value="Unblock">
        </form>
      </td>
cat;
   }
    else 
    {
      $cat.=<<<cat
      <td><p class="text-success">Active</p></td>
      <td>
        <form action="blocked.php" method="post">
            <input type="hidden" name="userid" value="{$row['userid']}">
            <input type="hidden" name="action" value="block">
            <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
            <input type="submit" class="btn btn-danger" name="block_unblock_user" value="Block">
        </form>
      </td>
cat;
    }

    $cat.=<<<cat
    <td>
        <a class="btn btn-info" href="admin_update_users.php?ulid={$row['userid']}&upid={$row['upid']}">Update</a>
    </td>
    </tr>
cat;
    $i++;
		}

    echo $cat;

?>
      </tbody>
    </table>
  </div>
</div>
  <!-- Pagination Logic -->
    <?php 
              $qp=query("select * from user_personal");
              confirm($qp);

              if(mysqli_num_rows($qp)!=0)
              {
                $p=intval(mysqli_num_rows($qp));

                $n=intval($p/10);
                $rem=intval($p%10);

                $pages=$n;

                if($rem!=0)
                  $pages+=1;

                $i=1;
                $start=0;
                $end=10;
                // Pagination
                echo "<div class='row'>
                        <div class='col-md-12 text-center'>
                          <ul class='pagination'>
                          <li id='prev'><span style='cursor:pointer;'>&lt;</span></li>
                            <li class='active page' start='$start' end='$end' id='button$i'><span style='cursor:pointer;'>$i</span></li>";
                            $start+=10;

                for($i=2;$i<=$pages;$i++)
                {
                  echo "<li class='page' start='$start' end='$end' id='button$i'><span style='cursor:pointer;'>$i</span></li>";
                  $start+=10;
                }
                echo "<li id='next'><span style='cursor:pointer;'>&gt;</span></li>";
                echo '</ul>
                        </div>
                          </div>';
              }
              
            ?>
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
</div>
   </div>

<script>
  $(".page").on("click",
    function()
    {
      start=$(this).attr("start");
      end=$(this).attr("end");

      $(".page").removeClass("active");   // remove active class from current page.
      $(this).addClass("active");         // add active class to clicked page.
      
      $.get("change_userstable.php?start="+start+"&end="+end,
        function(data,status)
        {
          $("#users_body").html(data);
        });
    });

    pageNumber=5;     // for tracing pages 5 at a perticular time.
    totalPages=<?php echo $pages; ?>;

    // In Starting setting all pages display to none and show page 1 to 5
    $(".page").css("display","none");           
    $("#prev").css("display","inline-block");
    $("#button1").css("display","inline-block");
    $("#button2").css("display","inline-block");
    $("#button3").css("display","inline-block");
    $("#button4").css("display","inline-block");
    $("#button5").css("display","inline-block");
    $("#next").css("display","inline-block");

    // working when click on prev
   $("#prev").click(function(){
      if(pageNumber==5) // means no page before page 1 show return will end the script.
        return 0;

       $(".page").css("display","none"); // again all pages to hide but showing previous five pages
         pageNumber-=5;
         $("#button"+(pageNumber-4)).css("display","inline-block");
         $("#button"+(pageNumber-3)).css("display","inline-block");
         $("#button"+(pageNumber-2)).css("display","inline-block");
         $("#button"+(pageNumber-1)).css("display","inline-block");
         $("#button"+pageNumber).css("display","inline-block");
      });

    // working when click on next
      $("#next").click(function(){
        //if(pageNumber><?php //echo floor($pages/10)/*-1*/; ?>) // means no page after page ceil($pages/5)-1 to show. return will end the script. devide by 5 because we are showing 5 records in one page
        if(pageNumber>=totalPages)
          return 0;
          
         $(".page").css("display","none");
         $("#button"+(pageNumber+1)).css("display","inline-block");
         $("#button"+(pageNumber+2)).css("display","inline-block");
         $("#button"+(pageNumber+3)).css("display","inline-block");
         $("#button"+(pageNumber+4)).css("display","inline-block");
         $("#button"+(pageNumber+5)).css("display","inline-block");
         pageNumber+=5;
      });
</script>

<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 