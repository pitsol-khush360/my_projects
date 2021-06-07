<?php
    if(isset($_SESSION['username']) && isset($_SESSION['ulid']))
    {
?>

<div class="container">
	<div class="row">

		<!-- Form For Set Exam Preference -->
		<div class="col-12 col-sm-8 col-md-9 col-lg-6" style="margin-top:5rem;">
		<?php

			if(isset($_POST['exam_choice_selected']))
				exam_set_preference();
			else
			if(isset($_POST['practice_choice_selected']))
				practice_set_preference();

		if(isset($_GET['test_preference']))
		{
			$qutp=query("select * from user_test_preference where ulid='".$_SESSION['ulid']."'");
			confirm($qutp);
			$rutp=fetch_array($qutp);

			$previous="";

			if(mysqli_num_rows($qutp)!=0)
				$previous=$rutp['scid'];   // String containing scids

			$qc=query("select * from course_category");
			confirm($qc);

			echo "<div class='panel panel-primary' style='max-height:100vh;overflow-y:scroll;'>
					<div class='panel-heading'><p class='text-center' style='font-size:1.1rem;'>Set Your Exam Preferences</p>
					</div>";

			echo "<div class='panel-body'><form class=\"form\" method=\"post\" action=\"index.php?test_preference\">
				<div class=\"card\">";

			while($rc=fetch_array($qc))
			{
				$qsc=query("select * from sub_category where ccid='".$rc['ccid']."'");
				confirm($qsc);

				echo "<div class='card-header' style='margin-top:15px;'><b>{$rc['category_name']}</b></div>
						<div class='card-body'>
							<ul class='list-inline'>";

				while($rsc=fetch_array($qsc))
				{
					$flag=0;         // for checking , if a scid is in or not in $previous.
					// strpos() returns false if occurance of 2nd parameter not found.
					// if(strpos($previous,$rsc['scid'])!==false)

					$temp=explode("#",$previous);

					foreach($temp as $v)
					{
						if($v!="" && !is_null($v))
						{
							if($rsc['scid']==$v)
								$flag++;
						}
					}
					
					if($flag!=0) 
					// show checked mark if previously choised.
					{
						$list=<<< list
						<li>
						<label>
							<input type="checkbox" name="c[{$rsc['scid']}]" value="{$rsc['scid']}" checked>&nbsp;{$rsc['sub_category_name']}
						</label>
						</li>
list;
						echo $list;
					}
					else
					{
						$list=<<< list
						<li>
						<label>
							<input type="checkbox" name="c[{$rsc['scid']}]" value="{$rsc['scid']}">&nbsp;{$rsc['sub_category_name']}
						</label>
						</li>
list;
						echo $list;
					}
				}

				echo '</ul>
					</div>';
			}

			echo "</div>
				<input type=\"hidden\" name=\"ulid\" value=\"{$_SESSION['ulid']}\">
				<input type=\"hidden\" name=\"{$_SESSION["csrf_name"]}\" value=\"{$_SESSION["csrf_value"]}\">
					<button type=\"submit\" class=\"btn btn-info\" name=\"exam_choice_selected\" style='margin-top:15px;'>Apply Selection</button>
						</form>
							</div>
						</div>";
		}
		else
			echo "<p class='text-center text-danger'>Sorry! Permission Denied...</p>";
		?>
		</div>

		<!-- Form For Set Practice Preference -->
		<div class="col-12 col-sm-8 col-md-9 col-lg-5" style="margin-top:5rem;">
			<?php
			if(isset($_GET['test_preference']))
			{
				$quptp=query("select * from user_practice_preference where ulid='".$_SESSION['ulid']."'");
				confirm($quptp);

				$previous="";

				while($ruptp=fetch_array($quptp))
				{
					$previous=$ruptp['qstid'];
				}

				$qc1=query("select * from question_topic");
				confirm($qc1);

				echo "<div class='panel panel-primary' style='max-height:100vh;overflow-y:scroll;'>
					<div class='panel-heading'><p class='text-center' style='font-size:1.1rem;'>Set Your Practice Preferences</p>
					</div>";
				echo "<div class='panel-body'><form class=\"form\" method=\"post\" action=\"index.php?test_preference\">
					<div class=\"card\">";

				while($rc1=fetch_array($qc1))
				{
					$qsc1=query("select * from question_sub_topic where qtid='".$rc1['qtid']."'");
					confirm($qsc1);

					echo "<div class='card-header' style='margin-top:15px;'><b>{$rc1['question_topic']}</b></div>
							<div class='card-body'>
								<ul class='list-inline'>";

					while($rsc1=fetch_array($qsc1))
					{
						$flag=0;         // for checking , if a scid is in or not in $previous.
						// strpos() returns false if occurance of 2nd parameter not found.
						// if(strpos($previous,$rsc['scid'])!==false)

						$temp=explode("#",$previous);

						foreach($temp as $v)
						{
							if($v!="")
							{
								if($rsc1['qstid']==$v)
									$flag++;
							}
						}
						
						if($flag!=0) 
						// show checked mark if previously choised.
						{
							$list=<<< list
							<li>
							<label>
								<input type="checkbox" name="c[{$rsc1['qstid']}]" value="{$rsc1['qstid']}" checked>&nbsp;{$rsc1['question_sub_topic']}
							</label>
							</li>
list;
							echo $list;
						}
						else
						{
							$list=<<< list
							<li>
							<label>
								<input type="checkbox" name="c[{$rsc1['qstid']}]" value="{$rsc1['qstid']}">&nbsp;{$rsc1['question_sub_topic']}
							</label>
							</li>
list;
							echo $list;
						}
					}

					echo '</ul>
						</div>';
				}

				echo "</div>
					<input type=\"hidden\" name=\"ulid\" value=\"{$_SESSION['ulid']}\">
					<input type=\"hidden\" name=\"{$_SESSION["csrf_name"]}\" value=\"{$_SESSION["csrf_value"]}\">
					<button type=\"submit\" class=\"btn btn-info\" name=\"practice_choice_selected\" style='margin-top:15px;'>Apply Selection</button>
							</form>
								</div>
							</div>";
			}
			else
				echo "<p class='text-center text-danger'>Sorry! Permission Denied...</p>";
			?>
		</div>
	</div>
</div>

<?php
    }
    else
        redirect("..".DS."signin.php");
?>