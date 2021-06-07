
<?php
    if(isset($_SESSION['username']) && isset($_SESSION['ulid']))
    {
?>

<!-- Team -->
<section id="team" class="pb-5">
    <div class="container-fluid">
        <h5 class="section-title h1">User Comments</h5>
        <div class="row" id="comments_body">
            <?php
                $query=query("select * from user_comments where comment_status='Y' order by ucid desc limit 0,6");
                confirm($query);

                if(mysqli_num_rows($query)!=0)
                {
                    while($row=fetch_array($query))
                    {
                        $qu=query("select * from user_personal where ulid='".$row['ulid']."'");
                        confirm($qu);
                        $ru=fetch_array($qu);
                        $img_path=image_path_profile($ru['profile_picture']);

                        $com=$row['user_comment'];
                        $c=strlen($com);

                        if($c>=20)
                        {
                            $sub=substr($com,0,30);
                            $sub=$sub."...";
                        }
                        else
                            $sub=$com;

                    $comment=<<<comment
                    <!-- Team member -->
            <div class="col-lg-4 col-xs-12 col-sm-6 col-md-4">
                <div class="image-flip" ontouchstart="this.classList.toggle('hover');">
                    <div class="mainflip">
                        <div class="frontside">
                            <div class="card">
                                <div class="card-body text-center">
                                    <p><img class=" img-fluid" src="../../resources/{$img_path}" alt="card image"></p>
                                    <h4 class="card-title">{$ru['name']} Says</h4>
                                    <p class="card-text">{$sub}</p>
                                    <a href="index.php?add_usercomment" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="backside">
                            <div class="card">
                                <div class="card-body text-center mt-4">
                                    <h4 class="card-title">{$ru['name']} Says</h4>
                                    <p class="card-text">{$row['user_comment']}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./Team member -->
comment;
                    echo $comment;
                    }
                }
            ?>
        </div>
        <div class="row">
            <div class="col-12 text-center">
            <?php 
              $qp=query("select * from user_comments where comment_status='Y'");
              confirm($qp);

              if(mysqli_num_rows($qp)!=0)
              {
                $p=intval(mysqli_num_rows($qp));

                $n=intval($p/6);
                $rem=intval($p%6);

                $pages=$n;

                if($rem!=0)
                  $pages+=1;

                $i=1;
                $start=0;
                $end=6;
                // Pagination
                echo "<div class='row'>
                        <div class='col-md-12 text-center'>
                          <ul class='pagination'>
                            <li id='prev'><span style='cursor:pointer;'>&lt;</span></li>
                            <li class='active comment_page' start='{$start}' end='{$end}' id='button$i'><span style='cursor:pointer;'>$i</span></li>";
                            $start+=6;

                for($i=2;$i<=$pages;$i++)
                {
                  echo "<li class='comment_page' start='{$start}' end='{$end}' id='button$i'><span style='cursor:pointer;'>$i</span></li>";
                  $start+=6;
                }
                echo "<li id='next'><span style='cursor:pointer;'>&gt;</span></li>";
                echo '</ul>
                        </div>
                          </div>';
              }
              
            ?>
            </div>
        </div>
    </div>
</section>

<?php
    }
    else
        redirect("..".DS."signin.php");
?>

<script>
  $(".comment_page").on("click",
    function()
    {
      start=$(this).attr("start");
      end=$(this).attr("end");

      $(".comment_page").removeClass("active");
      $(this).addClass("active");
      
      $.get("change_usercomments.php?start="+start+"&end="+end,
        function(data,status)
        {
          $("#comments_body").html(data);
        });
    });

    pageNumber=5;
    totalPages=<?php echo $pages; ?>;

    $(".comment_page").css("display","none");           
    $("#prev").css("display","inline-block");
    $("#button1").css("display","inline-block");
    $("#button2").css("display","inline-block");
    $("#button3").css("display","inline-block");
    $("#button4").css("display","inline-block");
    $("#button5").css("display","inline-block");
    $("#next").css("display","inline-block");

   $("#prev").click(function(){
      if(pageNumber==5)
        return 0;

       $(".comment_page").css("display","none");
         pageNumber-=5;
         $("#button"+(pageNumber-4)).css("display","inline-block");
         $("#button"+(pageNumber-3)).css("display","inline-block");
         $("#button"+(pageNumber-2)).css("display","inline-block");
         $("#button"+(pageNumber-1)).css("display","inline-block");
         $("#button"+pageNumber).css("display","inline-block");
      });

      $("#next").click(function(){
        if(pageNumber>=totalPages)
          return 0;
          
         $(".comment_page").css("display","none");
         $("#button"+(pageNumber+1)).css("display","inline-block");
         $("#button"+(pageNumber+2)).css("display","inline-block");
         $("#button"+(pageNumber+3)).css("display","inline-block");
         $("#button"+(pageNumber+4)).css("display","inline-block");
         $("#button"+(pageNumber+5)).css("display","inline-block");
         pageNumber+=5;
      });
</script>