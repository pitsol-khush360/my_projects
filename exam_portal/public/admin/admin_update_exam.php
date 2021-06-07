<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
		if(isset($_GET['eid']))
		{
			$eid=$_GET['eid'];

			if(isset($_POST['submit_update_exam'])) 
			{
				$title=$_POST['title'];
				$ccid=$_POST['category'];
				$scid=$_POST['sub_category'];
				$start_date=$_POST['start_date'];
				$end_date=$_POST['end_date'];

				// Checking if exam is already created

				$q_check=query("select * from exam where scid='".$scid."' and start_time='".$start_date."' and end_time='".$end_date."'");
		        confirm($q_check);
		        
	        	if(mysqli_num_rows($q_check)>0)
	            {
	               echo "<script>alert('you can not insert exam of this subcategory on this date')</script>";
	               redirect("admin_insert_exam.php");
	               exit;
	            }
	            else
	            {
	            	$query=query("UPDATE exam SET title='".$title."',start_time='".$start_date."',end_time='".$end_date."',scid='".$scid ."' where eid='".$eid."'");
					confirm($query);
					redirect("admin_exam.php");
	            }
			}

			$query=query("select * from exam where eid='".$eid."'");
			confirm($query);
			$row=fetch_array($query);

			$title=$row['title'];
?>
<div class="graphs">
	<div class="xs">
		<div class="row">
			<div class="col-12">
				<a href="admin_exam.php" class="btn btn-info">Back To Exams</a>
			</div>
		</div>
		<div class="row text-center">
  			<div class="col-md-12"><span><big><b>Update Exam</b></big></span></div>
	  	</div>
		 <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post" >
								<div class="form-group">
									<label for="title" class="col-sm-2 control-label">Exam Title</label>
									<div class="col-sm-8">
										<input type="text" value="<?php echo $title; ?>" class="form-control1" name="title" id="title" placeholder="Type Exam Title" required>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Write Exam title</p>
									</div>
								</div>
								<div class="form-group">
									<label for="start_date" class="col-sm-2 control-label">Start DateTime</label>
									<div class="col-sm-8">
							            <input type='datetime-local' id="start_date" class="form-control1" name="start_date" placeholder="Enter Start Date" required/>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Pick The Start DateTime</p>
									</div>
								</div>
								<div class="form-group">
									<label for="end_date" class="col-sm-2 control-label">End DateTime</label>
									<div class="col-sm-8">
							            <input type='datetime-local' id="end_date" class="form-control1" name="end_date" placeholder="Enter End Date" required/>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Pick The End DateTime</p>
									</div>
								</div>
								<div class="form-group">
									<label for="category" class="col-sm-2 control-label">category</label>
									<div class="col-sm-8">
										<select name="category" class="form-control1" required>
											<option value="">click to select correct category</option>
									<?php 	$query=query("select * from course_category ");
											confirm($query); 
											while($row=fetch_array($query))
											{?>
											<option value="<?php echo $row['ccid'] ?>"><?php echo $row['category_name'] ?></option>
										<?php } ?>
										</select>
									</div>
									<div class="col-sm-2">
										<p class="help-block">select your course category</p>
									</div>
								</div>
								<div class="form-group">
									<label for="sub_category" class="col-sm-2 control-label">Subcategory</label>
									<div class="col-sm-8">
										<select name="sub_category" class="form-control1" required>
											<option value="">click to select correct category</option>
										</select>
									</div>
									<div class="col-sm-2">
										<p class="help-block">select your sub category</p>
									</div>
								</div>
								<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
								<div class="form-group">
									<div class="col-sm-12 text-center">
										<input type="submit" name="submit_update_exam" class="btn btn_5 btn-lg btn-primary " value="Update">
									</div>
								</div>
							</form>
						</div>
					</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("select[name=category]").change(function(){
			$.get("admin_select_subcategory_exam.php?ccid="+$(this).val(),function(data){$("select[name=sub_category]").html(data)});
		});
	});
</script>
		<?php 
			}
			else
			{
				redirect("admin_exam.php");
				echo "<script>alert('PLZ, Select the exam to be update')</script>";
			}
		}
		else
    		echo '<div class="bs-example4" data-example-id="contextual-table"><div class="row">
	            <div class="col-12 text-center text-danger">
	              <h4 style="margin-top:5em;">You Don\'t Have Permission To Access This Page</h4>
	            </div>
	          </div></div>';
		 ?>

<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 