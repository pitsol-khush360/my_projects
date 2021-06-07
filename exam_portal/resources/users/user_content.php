
        <div class="graphs">
          <div class="row" style="margin: 3rem 1rem;">
            <div class="col-md-3 widget widget1">
                <div class="r3_counter_box">
                    <i class="pull-left fa fa-question icon-rounded"></i>
                    <div class="stats">
                      <h5><strong>45%</strong></h5>
                      <span>New Quizes</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 widget widget1">
                <div class="r3_counter_box">
                    <i class="pull-left fa fa-users user1 icon-rounded"></i>
                    <div class="stats">
                      <h5><strong><?php totalvisitors(); ?></strong></h5>
                      <span>Total Visitors</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 widget widget1">
                <div class="r3_counter_box">
                    <i class="pull-left fa fa-user user2 icon-rounded"></i>
                    <div class="stats">
                      <h5><strong><?php totalusers(); ?></strong></h5>
                      <span>Total Users</span>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
      </div>
      <?php
          $current_date=date("Y-m-d H:i:s");   // gives the date in timestamp format.
          
          $qo=query("select * from offers where offer_text!=\"\" and offer_type!=\"\" and ('".$current_date."' between start_date and end_date) order by offer_id desc limit 0,5");
            confirm($qo);

          if(mysqli_num_rows($qo)!=0)
          {
      ?>
      <div class="col-md-12 span_3">
        <div class="row" id="user-offer-slider-row">
         <div class="col-12" >
         <div class="w3-content w3-display-container">
          <?php
            $count=1;
            while($row=fetch_array($qo))
            {
              // showing only first offer as active for only $count=1
              if($row['offer_image']!="")
                $img_path=image_path_offer($row['offer_image']);
              else
                $img_path=image_path_offer("defaultoffer.jpg");

              if($count==1)
              {
                echo " <img class='d-block mySlides' src='../../resources/{$img_path}' style='width:100%;height:25rem;' alt='Offer'>";
                $count=0;
              }
              else
              {
                echo " <img class='d-block mySlides' src='../../resources/{$img_path}' style='width:100%;height:25rem;' alt='Offer'>";
              }
            }
          ?>
          <button class="w3-button w3-black w3-display-left" onclick="plusDivs(-1)">&#10094;</button>
          <button class="w3-button w3-black w3-display-right" onclick="plusDivs(1)">&#10095;</button>
        </div>
        </div>
        </div>
      </div>
      <?php
        }
      ?>
      <div class="clearfix"></div>

<?php 
    $query=query("select * from result where ulid='".$_SESSION['ulid']."' order by result_id desc limit 0,5");
    confirm($query);
?>
    <div class="content_bottom">
     <div class="col-md-12 span_3">
          <div class="bs-example1" data-example-id="contextual-table">
            <h3 class="text-center">Your Completed Exams</h3>
            <div class="col-12" style="overflow-x:auto;">
            <table class="table table-responsive table-hover table-bordered">
              <thead>
                <tr>
                  <th>Exam</th>
                  <th>Set</th>
                  <th>Total Questions</th>
                  <th>Total Attempted</th>
                  <th>Right Answers</th>
                  <th>Wrong Answers</th>
                  <th>Marks Obtained</th>
                  <th>Total Marks</th>
                  <th>Exam Completed Time</th>
                  <th>Rank</th>
                </tr>
              </thead>
              <tbody id="result_body">
                <?php
                  while($row=fetch_array($query))
                  {
                    $ct=date('d F Y h:i A',strtotime($row['completed_time']));

                    // calculating Rank Of User

                    $total_students_appeared=0;
                    $rank=0;

                    $q_gc=query("SELECT count(*) AS totalstudents FROM result WHERE eid='".$row['eid']."'");
                    confirm($q_gc);

                    if(mysqli_num_rows($q_gc)!=0)
                    {
                      $r_gc=fetch_array($q_gc);
                      $total_students_appeared=$r_gc['totalstudents'];
                    }

                    if($total_students_appeared!=0)
                    {
                      $query_rank=query("SELECT Rank FROM 
                        (SELECT @rank:=@rank+1 AS Rank, result.* 
                          FROM result, (SELECT @rank:=0) AS i 
                          WHERE eid='".$row['eid']."' ORDER BY marks_obtained DESC, completed_time ASC) AS allranks 
                          WHERE allranks.ulid='".$_SESSION['ulid']."'");
                      confirm($query_rank);

                      if(mysqli_num_rows($query_rank)!=0)
                      {
                        $row_rank=fetch_array($query_rank);
                        $rank=$row_rank['Rank'];
                      }
                    }

                    $here=<<<here
                    <tr class="active">
                        <td>{$row['exam_title']}</td>
                        <td>{$row['exam_set']}</td>
                        <td>{$row['total_questions']}</td>
                        <td>{$row['total_attempted']}</td>
                        <td>{$row['right_questions']}</td>
                        <td>{$row['wrong_questions']}</td>
                        <td>{$row['marks_obtained']}</td>
                        <td>{$row['total_marks']}</td>
                        <td>{$ct}</td>
                        <td>{$rank} / {$total_students_appeared}</td>
                    </tr>
here;
                    echo $here;
                  }
                ?>
              </tbody>
            </table>
            </div>

            <?php 
              $qp=query("select * from result where ulid='".$_SESSION['ulid']."'");
              confirm($qp);

              if(mysqli_num_rows($qp)!=0)
              {
                $p=intval(mysqli_num_rows($qp));

                $n=intval($p/5);
                $rem=intval($p%5);

                $pages=$n;

                if($rem!=0)
                  $pages+=1;

                $i=1;
                $start=0;
                $end=5;
                // Pagination
                echo "<div class='row'>
                        <div class='col-md-12 text-center'>
                          <ul class='pagination'>
                          <li id='prev'><span style='cursor:pointer;'>&lt;</span></li>
                            <li class='active page' id='button$i' start='{$start}' end='{$end}'><span style='cursor:pointer;'>$i</span></li>";
                            $start+=5;
                for($i=2;$i<=$pages;$i++)
                {
                  echo "<li class='page' id='button$i' start='{$start}' end='{$end}'><span style='cursor:pointer;'>$i</span></li>";
                  $start+=5;
                }
                echo "<li id='next'><span style='cursor:pointer;'>&gt;</span></li>";

                echo '</ul>
                        </div>
                          </div>';
              }
              
            ?>
          </div>
       </div>

        <div class="col-md-12 span_4">
        <div class="col_12">
        <div class="box_1">
           <div class="col-md-4 col_1_of_2 span_1_of_2">
             <a class="tiles_info">
                <div class="tiles-head fb1">
                    <div class="text-center">Facebook</div>
                </div>
                <div class="tiles-body fb2">10</div>
             </a>
           </div>
           <div class="col-md-4 col_1_of_2 span_1_of_2">
              <a class="tiles_info tiles_blue">
                <div class="tiles-head tw1">
                    <div class="text-center">Twitter</div>
                </div>
                <div class="tiles-body tw2">30</div>
              </a>
           </div>
           <div class="col-md-4 col_1_of_2 span_1_of_2">
             <a class="tiles_info" href="index.php?add_usercomment">
                <div class="tiles-head bg-info">
                    <div class="text-center">Add Comment</div>
                </div>
                <div class="tiles-body bg-info">10</div>
             </a>
           </div>
           <div class="clearfix"> </div>
        </div>
        </div>
        </div>
        <div class="clearfix"> </div>
        </div>

<div class="container">
	<div class="row">
    <div class="col-lg-1"></div>
		<div class="col-12 col-sm-3 col-md-3 col-lg-3">
			<a href="index.php?faq" style="text-decoration:none;">
			<div class="panel panel-info">
				<div class="panel panel-heading">FAQ</div>
  				<div class="panel-body">Visit FAQ</div>
			</div>
			</a>
		</div>
		<div class="col-12 col-sm-3 col-md-3 col-lg-3">
			<a href="index.php?aboutus" style="text-decoration:none;">
			<div class="panel panel-success">
				<div class="panel panel-heading">About Us</div>
  				<div class="panel-body">Meet Our Team</div>
			</div>
			</a>
		</div>
		<div class="col-12 col-sm-3 col-md-3 col-lg-3">
			<a href="" style="text-decoration:none;">
			<div class="panel panel-danger">
				<div class="panel panel-heading">Others</div>
  				<div class="panel-body">Visit Others</div>
			</div>
			</a>
		</div>
    <div class="col-md-1 col-lg-1"></div>
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
      
      $.get("change_resulttable.php?start="+start+"&end="+end,
        function(data,status)
        {
          $("#result_body").html(data);
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

<script>
// var slideIndex = 1;
// showDivs(slideIndex);

// function plusDivs(n) {
//   showDivs(slideIndex += n);
// }

// function showDivs(n) {
//   var i;
//   var x = document.getElementsByClassName("mySlides");
//   if (n > x.length) {slideIndex = 1}
//   if (n < 1) {slideIndex = x.length}
//   for (i = 0; i < x.length; i++) {
//     x[i].style.display = "none";  
//   }
//   x[slideIndex-1].style.display = "block";  
//}

var slideIndex = 0;
carousel();

function carousel() {
  var i;
  var x = document.getElementsByClassName("mySlides");
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";
  }
  slideIndex++;
  if (slideIndex > x.length) {slideIndex = 1}
  x[slideIndex-1].style.display = "block";
  setTimeout(carousel, 10000); // Change image every 2 seconds
}

//var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {
  showDivs(slideIndex += n);
}

function showDivs(n) {
  var i;
  var x = document.getElementsByClassName("mySlides");
  if (n > x.length) {slideIndex = 1}
  if (n < 1) {slideIndex = x.length} ;
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";
  }
  x[slideIndex-1].style.display = "block";
}

</script>