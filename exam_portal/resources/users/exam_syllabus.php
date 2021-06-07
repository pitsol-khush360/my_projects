<?php
    if(isset($_SESSION['username']) && isset($_SESSION['ulid']))
    {
?>

<div class="container">
<div class="row" style="margin:5rem 3rem 4rem 0.5rem ;">
	<div class="col-12 col-sm-9 col-md-10 col-lg-12 text-center">
		<h5 style="font-size:30px;">Exam Syllabus</h5>
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-8 col-md-9 col-lg-11">
		<div class="row">
<?php
if(isset($_GET['exam_syllabus']))
{	
	if(isset($_POST['show_exam_syllabus']) && isset($_POST['eid']))
	{
		$q_sy=query("select * from exam_syllabus where eid='".$_POST['eid']."'");
    	confirm($q_sy);

    	if(mysqli_num_rows($q_sy)!=0)
    	{
	      	echo '<div class="row">
	                <div class="col-12">
	                  <a href="index.php?exam_syllabus" class="btn btn-warning" style="margin-left:2em;margin-bottom:2em;">Back To All Exam Syllabus</a>
	                </div>
	              </div>
	              <div class="row">
	              	<div class="col-sm-1 col-md-1 col-lg-1"></div>
	              	<div class="col-12 col-sm-11 col-md-11 col-lg-11">
	                <table class="table table-responsive table-bordered table-hover">
	                  <thead>
	                    <tr>
	                      <th class="text-center">Topic</th><th class="text-center">Sub-Topics</th>
	                    </tr>
	                  </thead>
	                  <tbody>';

		      while($r_sy=fetch_array($q_sy))
		      {
		        $syllabus=<<< syllabus
		        <tr class="active">
		          <td>{$r_sy['topic']}</td>
		          <td>{$r_sy['sub_topics']}</td>
		        </tr>
syllabus;
		        echo $syllabus;
		      }

	      echo '</tbody>
	          </table>
	          	</div>
	          </div>';
	    }
	    else
	    {
	      echo '<div class="row">
	              <div class="col-12">
	                <a href="index.php?exam_syllabus" class="btn btn-warning" style="margin-left:2em;">Back To All Exam Syllabus</a>
	              </div>
	            </div>
	            <div class="row">
	              <div class="col-12 text-center text-danger">
	                <h4>Exam Syllabus Not Available</h4>
	              </div>
	            </div>';
	    }
	}
	else
	{
		$query_sc=query("select * from user_test_preference where ulid='".$_SESSION['ulid']."'");
		confirm($query_sc);
		$r_sc=fetch_array($query_sc);

		$exams_found=0;

		if(mysqli_num_rows($query_sc)!=0 && $r_sc['scid']!="")
		{
			$scids=explode("#",$r_sc['scid']);
			
			foreach($scids as $v)
			{
				if($v!="" && !is_null($v) && $v!=" ")
				{
					$query_exam=query("select * from exam where scid='".$v."'");
					confirm($query_exam);

					if(mysqli_num_rows($query_exam)!=0)
					{
						$exams_found=1;

						while($r_exam=fetch_array($query_exam))
						{
							$start_time=date('d F Y h:i A',strtotime($r_exam['start_time']));
							$end_time=date('d F Y h:i A',strtotime($r_exam['end_time']));

							$exams=<<< exams
								<div class="col-12 col-sm-6 col-md-4 col-lg-4 card-parent-div">
									<div class="card card1">
										<div class="card-header card1-header">
											<div class="row">
												<div class="col-12"> 
													<p>{$r_exam['title']}</p>
												</div>
											</div>
										</div>
				  						<div class="card-body card1-body">
				  							<div class="row">
				  								<div class="col-12">
				  									<div class="row">
				  										<div class="col-12">
				  											<h5>Exam Start Time</h5> 
				  										</div>
				  										<div class="col-12">
				  											<p style="color:grey;">{$start_time}</p>
				  										</div>
				  									</div>
				  								</div>
				  								<div class="col-12">
				  									<div class="row">
				  										<div class="col-12">
				  											<h5>Exam End Time</h5> 
				  										</div>
				  										<div class="col-12">
				  											<p style="color:grey;">{$end_time}</p>
				  										</div>
				  									</div>
				  								</div>
				  							</div>
				  							<div class="row text-center">
				  								<form action="index.php?exam_syllabus" method="post">
				  									<input type="hidden" name="eid" value="{$r_exam['eid']}">
				  									<input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
				  									<button type="submit" name="show_exam_syllabus" class="btn btn-warning card1-btn">Check Syllabus</button>
				  								</form>
				  							</div>
				  						</div>
									</div>
								</div>
exams;
							echo $exams;
						}
					}
				}
			}

			if($exams_found==0)
				echo "<div class='col-12 text-center text-danger'><p style='font-size:20px;'>Exams are not available based on your preference!</p></div>";
		}
		else
		{
			echo "<div class='col-12 text-center text-danger'><p style='font-size:20px;'>Your Exam Preference Is Not Setted ! Or Refresh The Page If You Recently Setted Your Exam Preference.</p></div>";
			echo "<div class='col-12 text-center' style='margin-top:2rem;'><a href='index.php?test_preference'><button class='btn btn-info'>Set Exam Preference Now</button></a></div>";
		}
	}
}
else
	echo "Sorry! You Don't Have Permission";
?>
			</div>
		</div>
	</div> <!-- row ends exam.php?username={$username}&ulid={$ulid}&show_exams&eid={$r_exam['eid']} -->
</div>

<?php
    }
    else
        redirect("..".DS."signin.php");
?>