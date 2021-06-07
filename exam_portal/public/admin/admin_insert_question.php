<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php
	if(isset($_GET['tid'])&&isset($_GET['seid'])&& isset($_GET['ccid']) && isset($_GET['scid']) && isset($_GET['sec_cnt'])) 
	{
		$tid=$_GET['tid'];
		$seid=$_GET['seid'];
		$ccid=$_GET['ccid'];
		$scid=$_GET['scid'];
		$sec_cnt=$_GET['sec_cnt'];

		if(isset($_POST['submit'])) 
		{
			unset($_POST['submit']);
			foreach ($_POST as $key => $value) 
			{
				$query=query("insert into question_bank(seid,qids) values('".$seid."','".$key."')");
				confirm($query);
				redirect("admin_question.php?ccid={$ccid}&scid={$scid}&tid={$tid}&sec_cnt={$sec_cnt}&seid={$seid}");
			}
		}	
?>
				<div class="container">
					<div class="row">
						<div class="col-12">
							<a href="admin_question.php?ccid=<?php echo $ccid; ?>&scid=<?php echo $scid; ?>&tid=<?php echo $tid; ?>&sec_cnt=<?php echo $sec_cnt; ?>&seid=<?php echo $seid; ?>" class="btn btn-info btn-sm">Back To Section Questions</a>
						</div>
					</div>
					<div class="row" style="margin-bottom:20px; margin-top:20px; ">
  						<div class="col-md-12  text-center"><span><big><b>Add Questions</b></big></span></div>
	  				</div>
					<div class="row">	
					<div class="col-md-4"></div>	
					<div class="col-md-4">	
					<span>Select Topic:</span>	
					<select name="qt" class="form-control1" style="border:1px solid black !important; margin-bottom:10px; margin-top:10px; " required autofocus>
                          <?php 
                          	$query=query("select * from question_topic");
                          	confirm($query);
                          	$count=1;
                          	$f_qtid=0;
                          	while($row=fetch_array($query)) 
                          	{
                          		if($count==1)
                          		{
                          			$f_qtid=$row['qtid']; 
                          		}
                          	?>

                          	<option value="<?php echo $row['qtid']; ?>" > <?php echo $row['question_topic']; ?> </option>

                          	<?php	
                          	$count++;
                          	}
                           ?>
                      </select>
					</div>
					<div class="col-md-4"></div>	
                    </div>
                      <div class="row">	
					  <div class="col-md-4"></div>	
					  <div class="col-md-4">
					  <span>Select Sub Topic:</span>	
                      <select name="qst" id="qst" class="form-control1"  style="border:1px solid black !important; margin-bottom:10px;" required>
                      	 <?php 
                          	$query=query("select * from question_sub_topic where qtid='{$f_qtid}'");
                          	confirm($query);
                          	$count=1;
                          	$f_qstid=0;
                          	while($row=fetch_array($query)) 
                          	{
                          		if($count==1)
                          		{
                          			$f_qstid=$row['qstid']; 
                          		}
                          	?>

                          	<option value="<?php echo $row['qstid']; ?>" > <?php echo $row['question_sub_topic'];?> </option>

                          	<?php	
                          	$count++;
                          	}
                           ?>
                      </select>
                      </div>
					  <div class="col-md-4"></div>
					</div>
                 </div>

			<form method="post" style="overflow-x:auto;">
                    <input type="submit" class="btn btn-primary btn-lg" name="submit" style="margin-left: 10px;">
					<div <?php echo"id='{$f_qtid}_{$f_qstid}'";?> class='question_set'>	
						<table class="table">
					      <thead>
					        <tr>
					          <th>S.No.</th>
					          <th>Question</th>
					          <th>Action</th>
					        </tr>
					      </thead>
					      <tbody>
						 <?php 
						 $i=1;
                          	$query=query("SELECT * from question where qtid='{$f_qtid}' and qstid='{$f_qstid}'");
                          	confirm($query);
                          	while ($row=fetch_array($query)) 
                          	{
                          	?>
                          	<tr>
                          		<td style="width:60px;"><?php echo $i; ?></td>
								<td style="width:80%;word-break:break-word;"><?php echo html_entity_decode($row['question']); ?></td>
								<td><input type="checkbox" name="<?php echo $row['qid']; ?>"></td>
							</tr>
                          <?php
                          	$i++;	
                          	}
                          ?>	
                       </tbody>
                   	 </table>
                     </div>
                     <input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
			</form>


		<!-- scripts of ajax for fecthing data-->
		<script type="text/javascript">
			$(document).ready(function(){
				$("select[name=qt]").change(function()
				{
					 $.get("ajax_question_sub_topic.php?qtid="+$(this).val(),function(data,status)
   					 {

    					  $("#qst").html(data);
    					  $.get("ajax_question_bank.php?qtid="+$("select[name=qt]").val()+"&qstid="+$("select[name=qst]").val(),function(data,status)
		   					 {
		   					 	$("div[class='question_set']").css("display","none");
		   					 	if ($("#"+$("select[name=qt]").val()+"_"+$("select[name=qst]").val()).length==0) {	
		    					  $("form").append(data);
		   					 	}
		   					 	else
		   					 	{
		   					 		$("#"+$("select[name=qt]").val()+"_"+$("select[name=qst]").val()).css("display","block");
		   					 	}
		   					 });
   					 });

				});
				$("select[name=qst]").change(function()
				{
					 $.get("ajax_question_bank.php?qtid="+$("select[name=qt]").val()+"&qstid="+$(this).val(),function(data,status)
   					 {
    					   $.get("ajax_question_bank.php?qtid="+$("select[name=qt]").val()+"&qstid="+$("select[name=qst]").val(),function(data,status)
		   					 {
		   					 	$("div[class='question_set']").css("display","none");
		   					 	if ($("#"+$("select[name=qt]").val()+"_"+$("select[name=qst]").val()).length==0) {	
		    					  $("form").append(data);
		   					 	}
		   					 	else
		   					 	{
		   					 		$("#"+$("select[name=qt]").val()+"_"+$("select[name=qst]").val()).css("display","block");
		   					 	}
		   					 });
   					 });
				});
			});
		</script>
<?php  
	} 
	else
	{
		redirect("admin_category.php");
	}
?>
<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 





