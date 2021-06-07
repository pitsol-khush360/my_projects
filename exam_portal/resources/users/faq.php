
<?php
    if(isset($_SESSION['username']) && isset($_SESSION['ulid']))
    {
?>

<div class="container">
	<div class="row" style="margin-top:3rem;">
		<div class="col-12 col-sm-8 col-md-9 col-lg-12">
			<h3 class="text-center"><Strong>Frequently Asked Questions</Strong></h3>
		</div>
		<div class="col-12" id="change_faqs" style="margin-top:5rem;">
			<?php
				$query=query("select * from faqs order by faq_id desc limit 0,5");
				confirm($query);

				echo '<div class="row">';
				while($row=fetch_array($query))
				{
					$faq=<<< faq
					<div class="col-12 col-sm-7 col-md-8 col-lg-10 faq_panel_col_margin">
						<div class="card">
							<div class="card-header card1-header">
								<div class="col-12">
									<p><b>Question&nbsp; :</b><br>{$row['question']}</p>
								</div>
							</div>
							<div class="card-body card1-body">
								<div class="col-12">
									<p><b>Answer&nbsp;:</b><br>{$row['answer']}</p>
								</div>
							</div>	
						</div>
					</div>
faq;
					echo $faq;
				}
				echo '</div>';
		?>
		</div>
	</div>

		<!-- Pagination for faqs -->
			<?php 
	              $qp=query("select * from faqs");
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
	                        <div class='col-12 col-sm-7 col-md-9 col-lg-12 text-center'>
	                          <ul class='pagination'>
	                          <li id='prev'><span style='cursor:pointer;'>&lt;</span></li>
	                            <li class='active faqpage' start='{$start}' end='{$end}' id='button$i'><span style='cursor:pointer;'>$i</span></li>";
	                            $start+=5;

	                for($i=2;$i<=$pages;$i++)
	                {
	                  echo "<li class='faqpage' start='{$start}' end='{$end}' id='button$i'><span style='cursor:pointer;'>$i</span></li>";
	                  $start+=5;
	                }
	                echo "<li id='next'><span style='cursor:pointer;'>&gt;</span></li>";
	                echo '</ul>
	                        </div>
	                          </div>';
	              }
            ?>
		<!-- Pagination for faqs ends -->
</div>

<?php
    }
    else
        redirect("..".DS."signin.php");
?>

<script>
  $(".faqpage").on("click",
    function()
    {
      start=$(this).attr("start");
      end=$(this).attr("end");

      $(".faqpage").removeClass("active");   // remove active class from current page.
      $(this).addClass("active");         // add active class to clicked page.
      
      $.get("change_userfaqs.php?start="+start+"&end="+end,
        function(data,status)
        {
          $("#change_faqs").html(data);
        });
    });

    pageNumber=5;     // for tracing pages 5 at a perticular time.
    totalPages=<?php echo $pages; ?>;

    // In Starting setting all pages display to none and show page 1 to 5
    $(".faqpage").css("display","none");           
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

       $(".faqpage").css("display","none"); // again all pages to hide but showing previous five pages
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
          
         $(".faqpage").css("display","none");
         $("#button"+(pageNumber+1)).css("display","inline-block");
         $("#button"+(pageNumber+2)).css("display","inline-block");
         $("#button"+(pageNumber+3)).css("display","inline-block");
         $("#button"+(pageNumber+4)).css("display","inline-block");
         $("#button"+(pageNumber+5)).css("display","inline-block");
         pageNumber+=5;
      });
</script>