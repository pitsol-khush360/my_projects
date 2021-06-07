 
<?php include("navigation.php"); ?>

<?php
  if(!(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2))
    redirect("login.php");
?>

<?php 
if(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2)
{
	if(isset($_POST['cancelticket']) || isset($_POST['closeticket']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['ticket_id']=$_POST['tid'];
		$data['status']=$_POST['status'];

		$url=DOMAIN.'/rest/seller/UpdateTicketStatusRest.php';
		$output=getRestApiResponse($url,$data);
		
		if(isset($output['updateticket']) && $output['updateticket']['response_code']==200)
		{
			if(isset($_POST['cancelticket']))
				echo '<script>alert("Ticket cancelled successfully");</script>';
			else
			if(isset($_POST['closeticket']))
				echo '<script>alert("Ticket closed successfully");</script>';
		}
		else
			echo '<script>alert("Unable to perform this operation");</script>';
	}

	if(isset($_POST['raise']) || isset($_POST['reopenticket']))
	{
		if(trim($_POST['des'])!="" && !is_null($_POST['des']) && trim($_POST['sub'])!="" && !is_null($_POST['sub']))
		{
			$data['user_id']=$_SESSION['user_id'];
			$data['description']=escape_string(trim($_POST['des']));

			$url="";

			if(isset($_POST['raise']))
			{
				$data['subject']=escape_string(trim($_POST['sub']));
				$url=DOMAIN.'/rest/seller/createTicketRest.php';
			}
			else
			if(isset($_POST['reopenticket']))
			{
				$data['ticket_id']=$_POST['tid'];
				$url=DOMAIN.'/rest/seller/ReopenTicketRest.php';
			}

			$output=getRestApiResponse($url,$data);
			
			if(isset($output['createticket']) && $output['createticket']['response_code']==200)
				echo '<script>alert("Ticket created successfully");</script>';
			else
			if(isset($output['updateticket']) && $output['updateticket']['response_code']==200)
				echo '<script>alert("Ticket reopened successfully");</script>';
			else
				echo '<script>alert("Unable to perform this operation");</script>';
		}
		else
			echo '<script>alert("Subject and description field must not be blank!");</script>';
	}

	if(isset($_POST['cancelticketscreen']) || isset($_POST['viewticket']) || isset($_POST['viewresolvedticket']) || isset($_POST['reopenticketscreen']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['ticket_id']=$_POST['tid'];

		$url=DOMAIN.'/rest/seller/viewTicketRest.php';
		$output=getRestApiResponse($url,$data);

		if(isset($output['viewticket']) && count($output['viewticket'])>2)
		{
?>

<div class="container">
	<div class="row mt-5">
		<div class="col-12 mt-1">
      		<b>Ticket Id:</b>
      		<span><?php echo $output['viewticket'][0]['ticket_id']; ?></span>
      	</div>
      	<div class="col-12 mt-1">
		    <b>Status:</b>
		    <?php 
		    	if($output['viewticket'][0]['status']==1)
		    		echo "<span class='text-success'>Open</span>";
		    	else
		    	if($output['viewticket'][0]['status']==2)
		    		print "<p class='text-success'>Resolved</p>";
		    	else
		    	if($output['viewticket'][0]['status']==3)
		    		echo "<span class='text-danger'>Cancelled</span>";
		    	else
		    	if($output['viewticket'][0]['status']==4)
		    		echo "<span class='text-info'>Reopened</span>";
		    	else
		    	if($output['viewticket'][0]['status']==5)
		    		echo "<span class='text-danger'>Closed</span>";
		    ?>
      	</div>
      	<div class="col-12 mt-3">
      		<b>Subject:</b>
      		<p><?php echo $output['viewticket'][0]['subject']; ?></p>
      	</div>
      	<div class="col-12 mt-3">
      		<?php 
      			if(isset($_POST['reopenticketscreen']))
      			{
      				echo "<form action='' method='post'>
      						<div class='form-group'>
						      <label for='des'><b>Reopen Remarks:</b></label>
		      					<textarea name='des' id='des' class='form-control' rows='3' cols='15' required></textarea>
							</div>
      						<div class='form-group'>
						      <input type='hidden' name='tid' value='".$output['viewticket'][0]['ticket_id']."'>
						      <input type='hidden' name='sub' value='test'>
						      <input type='submit' name='reopenticket' class='btn btn-primary btn-md' value='Reopen Ticket'>
							</div>
      						</form>";
      			}
      			else
      			{
      				echo "<p><b>Description:</b></p>
      						<textarea class='form-control' rows='3' cols='15' readonly>".$output['viewticket'][0]['description']."</textarea>";
      			}
      		?>
    	</div>
    	<?php
    		if($output['viewticket'][0]['status']==5 || $output['viewticket'][0]['status']==4)
    		{
    			echo '<div class="col-12 mt-4">
      					<p><b>Resolution Remarks:</b></p>
      					<textarea class="form-control" rows="3" cols="15" readonly>'.$output["viewticket"][0]["resolution_remarks"].'</textarea></div></div>';
    		}

    		if($output['viewticket'][0]['status']==4)
    		{
    			echo "<div class='row mt-4'>
      					<div class='col-6'>
      						<div class='row'>
      							<div class='col-6'>
      								<b>Happy?</b>
      							</div>
      							<div class='col-6'>
      								<form action='' method='post'>
								    <div class='form-group'>
								      <input type='hidden' name='tid' value='".$output["viewticket"][0]["ticket_id"]."'>
								      <input type='hidden' name='status' value='5'>
								      <input type='submit' name='closeticket' class='btn btn-primary btn-md' value='Close Ticket' onclick=\"return confirm('Do you really want to close this ticket?')\">
								    </div>
									</form>
      							</div>
      						</div>
      					</div>
      			</div>";
    		}

    		if(isset($_POST['viewresolvedticket']))
    		{
    			echo "<div class='col-12 mt-4'>
      					<p><b>Resolution Remarks:</b></p>
      					<textarea class=form-control rows='3' cols='15' readonly>".$output["viewticket"][0]["resolution_remarks"]."</textarea></div></div>

      				<div class='row mt-4'>
      					<div class='col-6'>
      						<div class='row'>
      							<div class='col-6'>
      								<b>Not Happy?</b>
      							</div>
      							<div class='col-6'>
      								<form action='' method='post'>
								    <div class='form-group'>
								      <input type='hidden' name='tid' value='".$output['viewticket'][0]['ticket_id']."'>
								      <input type='submit' name='reopenticketscreen' class='btn btn-primary btn-md' value='Reopen Ticket'>
								    </div>
									</form>
      							</div>
      							<div class='col-6'>
      								<b>Happy?</b>
      							</div>
      							<div class='col-6'>
      								<form action='' method='post'>
								    <div class='form-group'>
								      <input type='hidden' name='tid' value='".$output["viewticket"][0]["ticket_id"]."'>
								      <input type='hidden' name='status' value='5'>
								      <input type='submit' name='closeticket' class='btn btn-primary btn-md' value='Close Ticket' onclick=\"return confirm('Do you really want to close this ticket?')\">
								    </div>
									</form>
      							</div>
      						</div>
      					</div>
      				</div>";
    		}
    	?>
    </div>

    <div class="row mt-3">
    	<?php
    		if(isset($_POST['cancelticketscreen']))
    		{
    	?>
		<div class="col-6 text-right">
			<form action="" method="post">
		    <div class="form-group">
		      <input type="hidden" name="tid" value="<?php echo $output['viewticket'][0]['ticket_id']; ?>">
		      <input type="hidden" name="status" value="3">
		      <input type="submit" name="cancelticket" class="btn btn-primary btn-md" value="Cancel Ticket" onclick="return confirm('Do you really want to cancel this ticket?')">
		    </div>
			</form>
		</div>	
		<?php
			}
		?>
		<div class="col-6 text-left">
		    <a href="displayContactUsForSeller.php">
		      <button class="btn btn-success btn-md">Back</button>
		    </a>
		</div>
	</div>
</div>

<?php
		}
	}
	else
	{
		$data1['user_id']=$_SESSION['user_id'];
		$data1['start']=0;

		if(isset($_SESSION['pages']))
			$data1['end']=$_SESSION['pages'];
		else
			$data1['end']=10;

		$url=DOMAIN.'/rest/seller/ticketListScreenRest.php';
		$output=getRestApiResponse($url,$data1);
		
		if(isset($output['ticketlist']) && count($output['ticketlist'])>2)
		{
?>
<div class="container">
	<div class="row">	
		<div class="col-12">
		<form action="" method="post">
		  	<div class="row mt-5">
		  		<div class="col-md-6 col-sm-12">
		    		<div class="form-group">
		      		<label for="sub"><b>Subject:</b></label>
		      		<input type="text" name="sub" id="sub" class="form-control" maxlength="100" required>
		    		</div>
		   		</div>
		   	</div>
		   	<div class="row">
		  		<div class="col-md-6 col-sm-12">
		    		<div class="form-group">
		      		<label for="des"><b>Description:</b></label>
		      		<textarea name="des" id="des" class="form-control" rows="3" cols="15" required></textarea>
		    		</div>
		   		</div>
		  	</div>

			<div class="row">
				<div class="col-12">
				    <div class="form-group text-left">
				      <input type="submit" name="raise" class="btn btn-primary btn-md" value="Submit" onclick="return confirm('Do you really want to create this ticket?')">
				    </div>
				</div>
			</div>
		</form>
		</div>
	</div>

	<?php
		if(isset($output['ticketlist']['rows']) && $output['ticketlist']['rows']!=0)
		{
	?>
	<div class="row mt-3">
		<div class="col-5 col-md-6">
			Show
			<select id="pages">
				<option><?php if(isset($_SESSION['pages']))
								echo $_SESSION['pages']; ?>
				</option>
				<option value="1">1</option>
				<option value="10">10</option>
				<option value="25">25</option>
				<option value="50">50</option>
				<option value="100">100</option>
			</select>
			Entries
		</div>
		<div class="col-7 offset-md-2 col-md-4">
			<div class="input-group">
		    	<input type="text" class="form-control" placeholder="Search By Ticket Id" id="searchfield">
			    <div class="input-group-append">
			      <button class="btn btn-secondary" type="button" id="search">
			        <i class="fa fa-search"></i>
			      </button>
			    </div>
		  	</div>
		</div>
	</div>

	<div class="row mt-4">
		<div class="col-12 table-responsive">
			<table class="table table-hover table-bordered text-center">
				<thead>
					<tr>
						<th>Ticket Id</th>
						<th>Subject</th>
						<th>Status</th>
						<th colspan="2">Action</th>
					</tr>
				</thead>
				<tbody id="ticket_body">
					<?php 
							for($i=0;$i<$output['ticketlist']['rows'];$i++)
							{
								$record="";
								$record.=<<< record
								<tr>
									<td>{$output['ticketlist'][$i]['ticket_id']}</td>
									<td>{$output['ticketlist'][$i]['subject']}</td>
record;

								if($output['ticketlist'][$i]['status']==1)
								{
									$record.=<<< record
									<td><p class="text-success">Open</p></td>
									<td>
										<form action="" method="post">
											<input type="hidden" name="tid" value="{$output['ticketlist'][$i]['ticket_id']}">
											<button type="submit" name="viewticket" class="btn btn-info">View</button>
										</form>
									</td>
									<td>
										<form action="" method="post">
											<input type="hidden" name="tid" value="{$output['ticketlist'][$i]['ticket_id']}">
											<button type="submit" name="cancelticketscreen" class="btn btn-danger">Cancel Ticket</button>
										</form>
									</td>
record;
								}
								else
								if($output['ticketlist'][$i]['status']==2)
								{
									$record.=<<< record
									<td><p class="text-success">Resolved</p></td>
									<td>
										<form action="" method="post">
											<input type="hidden" name="tid" value="{$output['ticketlist'][$i]['ticket_id']}">
											<button type="submit" name="viewresolvedticket" class="btn btn-info">View</button>
										</form>
									</td>
									<td></td>
record;
								}
								else
								if($output['ticketlist'][$i]['status']==3)
								{
									$record.=<<< record
									<td><p class="text-danger">Cancelled</p></td>
									<td>
										<form action="" method="post">
											<input type="hidden" name="tid" value="{$output['ticketlist'][$i]['ticket_id']}">
											<button type="submit" name="viewticket" class="btn btn-info">View</button>
										</form>
									</td>
									<td></td>
record;
								}
								else
								if($output['ticketlist'][$i]['status']==4)
								{
									$record.=<<< record
									<td><p class="text-info">Reopened</p></td>
									<td>
										<form action="" method="post">
											<input type="hidden" name="tid" value="{$output['ticketlist'][$i]['ticket_id']}">
											<button type="submit" name="viewticket" class="btn btn-info">View</button>
										</form>
									</td>
									<td></td>
record;
								}
								else
								if($output['ticketlist'][$i]['status']==5)
								{
									$record.=<<< record
									<td><p class="text-danger">Closed</p></td>
									<td>
										<form action="" method="post">
											<input type="hidden" name="tid" value="{$output['ticketlist'][$i]['ticket_id']}">
											<button type="submit" name="viewticket" class="btn btn-info">View</button>
										</form>
									</td>
									<td></td>
record;
								}
								echo $record;
							}
					?>
				</tbody>
			</table>
		</div>
	</div>
	<?php 
		// Setting Pagination
		// $sql="select * from promocodes where seller_id='".$sid."'";
		// setupPagination($sql);

      	$qp=query("select * from tickets where seller_id='".$_SESSION['user_id']."'");
        confirm($qp);

        if(mysqli_num_rows($qp)!=0)
        {
            $p=intval(mysqli_num_rows($qp));

            if(isset($_SESSION['pages']))
            	$n=intval($p/$_SESSION['pages']);
            else
            	$n=intval($p/10);

            if(isset($_SESSION['pages']))
            	$rem=intval($p%$_SESSION['pages']);
            else
            	$rem=intval($p%10);

            $pages=$n;

            if($rem!=0)
              $pages+=1;

            $i=1;
            $start=0;

            if(isset($_SESSION['pages']))
            	$end=$_SESSION['pages'];
            else
            	$end=10;
            // Pagination
            echo "<div class='row mt-2'>
                    <div class='col-md-12'>
                      <ul class='pagination justify-content-center'>
                      <li id='prev' class='page-item'><span style='cursor:pointer;' class='page-link'><i class='fa fa-backward'></i></span></li>
                      <li class='page-item active page' start='$start' end='$end' id='button$i'><span style='cursor:pointer;' class='page-link'>$i</span></li>";

            if(isset($_SESSION['pages']))
            	$start+=$_SESSION['pages'];
            else
            	$start+=10;

            for($i=2;$i<=$pages;$i++)
            {
              echo "<li class='page-item page' start='$start' end='$end' id='button$i'><span style='cursor:pointer;' class='page-link'>$i</span></li>";

              if(isset($_SESSION['pages']))
              	$start+=$_SESSION['pages'];
              else
              	$start+=10;
            }
            echo "<li id='next' class='page-item'><span style='cursor:pointer;' class='page-link'><i class='fa fa-forward'></i></span></li>";
            echo '</ul>
                    </div>
                      </div>';
        }
    	}
    ?>

<?php
		}		// if for table
	}
?>

</div>

        </div>
        <!-- content end -->
    </div>
    <!-- page content -->
</div>
<!-- page wrapper end -->

<?php include("footer.php"); ?>

<script>
  $(".page").on("click",
    function()
    {
      start=$(this).attr("start");
      end=$(this).attr("end");

      // alert(start);
      // alert(end);

      $(".page").removeClass("active");
      $(this).addClass("active");
      
      $.get("changeTicketList.php?start="+start+"&end="+end,
        function(data,status)
        {
          $("#ticket_body").html(data);
        });
    });

    pageNumber=5;     // for tracing five pages at a perticular time.
    totalPages=<?php echo $pages; ?>;

    $(".page").css("display","none");           
    $("#prev").css("display","inline-block");
    $("#button1").css("display","inline-block");
    $("#button2").css("display","inline-block");
    $("#button3").css("display","inline-block");
    $("#button4").css("display","inline-block");
    $("#button5").css("display","inline-block");
    $("#next").css("display","inline-block");

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
	$('#searchfield').keypress(
		function(event){
        if(event.which==13)
        {
            $('#search').click();
        }
    });
    
	$("#search").on("click",
		function()
		{
			ticket_id=$("#searchfield").val();

			$.get("searchTicket.php?ticketid="+ticket_id,
				function(data,status)
				{
					$("#ticket_body").html(data);
				});
		});
</script>

<script>
	$("#pages").change(function()
	{
		pages=$(this).val();
		$.get("../recordperpage.php?pages="+pages,
			function(data,status)
			{

			});
		//location.reload(true);
		location.href="displayContactUsForSeller.php";
	});
</script>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

<?php
}
?>

</div>
    <!-- Main Col END -->
</div>
<!-- body-row END -->
</body>
</html>