<?php
    if(isset($_SESSION['username']) && isset($_SESSION['ulid']))
    {
?>

<div class="container">
<div class="row">
	<div class="col-xs-1 col-md-1"></div>
	<div class="col-xs-10 col-md-10">
<?php
if(isset($_GET['practice']) && !isset($_POST['mode']))
{
	echo '<div class="row" style="margin-top:60px;">
			<h3 class="text-center">Select Practice Mode</h3>
		</div>';

	echo "<div class='row' style='margin-top:60px;' style='text-align:center; !important'>
			<form action='index.php?practice' method='post'>
				<input type='hidden' name='{$_SESSION["csrf_name"]}' value='{$_SESSION["csrf_value"]}'>
				<input type='radio' name='mode' value='by_section' id='m1'>&nbsp;<label for='m1' class='text-center' style='font-size:1.2rem;'>Practice By Only Sections</label><br>
				<input type='radio' name='mode' value='by_topic' id='m2'>&nbsp;<label for='m2' class='text-center' style='font-size:1.2rem;'>Practice By Only Topics</label><br>
				<button type='submit' class='btn btn-info' name='select_practice' style='margin-top:20px;'>Set Practice Mode</button>
		 	</form>
		 </div>";
}
else
if(isset($_GET['practice']) && isset($_POST['mode']) && $_POST['mode']=="by_section")
{
	echo '<div class="row" style="margin-top:60px;">
			<h3 class="text-center">Sections</h3>
		</div>';

	$query_sc=query("select * from user_test_preference where ulid='".$_SESSION['ulid']."'");
	confirm($query_sc);

	while($r_sc=fetch_array($query_sc))
	{
		$query_tid=query("select * from test where scid='".$r_sc['scid']."'");
		confirm($query_tid);

		while($r_tid=fetch_array($query_tid))
		{
			$query_section=query("select * from section where tid='".$r_tid['tid']."'");
			confirm($query_section);

			while($r_section=fetch_array($query_section))
			{
				$section=<<< section
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				<a href="index.php?show_practice&sections&seid={$r_section['seid']}" style="text-decoration:none;">
					<div class="panel panel-success">
						<div class="panel panel-heading"><p>{$r_section['section_name']}</p></div>
  						<div class="panel-body">
  							<div class="row">
  								<div class="col-md-12 col-xs-12">
  									Total Questions : {$r_section['section_question_no']}
  								</div>
  								<div class="col-md-12 col-xs-12">
  									Section Timing : {$r_section['section_timing']}&nbsp minutes
  								</div>
  							</div>
						</div>
					</div>
				</a>
				</div>
section;
				echo $section;
			}
		}
	}
}
else
if(isset($_GET['practice']) && isset($_POST['mode']) && $_POST['mode']=="by_topic")
{
	echo '<div class="row" style="margin-top:60px;">
			<h3 class="text-center">Sections</h3>
		</div>';

	$query_sc=query("select * from user_test_preference where ulid='".$_SESSION['ulid']."'");
	confirm($query_sc);

	while($r_sc=fetch_array($query_sc))
	{
		$query_tid=query("select * from test where scid='".$r_sc['scid']."'");
		confirm($query_tid);

		while($r_tid=fetch_array($query_tid))
		{
			$query_section=query("select * from section where tid='".$r_tid['tid']."'");
			confirm($query_section);

			while($r_section=fetch_array($query_section))
			{
				$query_t=query("select * from topic where seid='".$r_section['seid']."'");
				confirm($query_t);
				$t_topics=mysqli_num_rows($query_t);

				$section=<<< section
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				<a href="index.php?show_practice&topics&seid={$r_section['seid']}" style="text-decoration:none;">
					<div class="panel panel-success">
						<div class="panel panel-heading"><p>{$r_section['section_name']}</p></div>
  						<div class="panel-body">
  							<div class="row">
  								<div class="col-md-12 col-xs-12">
  									Total Topics : {$t_topics}
  								</div>
  								<div class="col-md-12 col-xs-12">
  									Section Timing : {$r_section['section_timing']}&nbsp;minutes
  								</div>
  							</div>
						</div>
					</div>
				</a>
				</div>
section;
				echo $section;
			}
		}
	}
}
?>
			</div>
		<div class="col-xs-1 col-md-1"></div>
	</div> <!-- row ends -->
</div>

<?php
    }
    else
        redirect("..".DS."signin.php");
?>