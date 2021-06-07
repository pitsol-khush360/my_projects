<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php 
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
	if(isset($_POST['submit_offer_update']))
	{	
		$ccid=$_POST['course_id'];
		$amount=$_POST['amount'];
		$offertext=$_POST['offertext'];
		$offertype=$_POST['offertype'];
		$offerimage=$_FILES['offerimage']['name'];
		$hiddenofferimage=$_POST['hidden_offer_image'];
		$start_date=$_POST['start_date'];
		$end_date=$_POST['end_date'];
		$offer_id=$_POST['offer_id'];

		if(!empty($_FILES['offerimage']['name']) && ($_FILES['offerimage']['type']=="image/jpg" || $_FILES['offerimage']['type']=="image/jpeg" || $_FILES['offerimage']['type']=="image/png"))
		{
			if($hiddenofferimage!="" && $hiddenofferimage!="defaultoffer.jpg")
				unlink(USEROFFER_UPLOAD.DS.$hiddenofferimage);

			$offerimage=date("YmdHis").'offerimg'.$offerimage;

			$query=query("UPDATE offers SET ccid='".$ccid."',amount='".$amount."',offer_text='".$offertext."',offer_image='".$offerimage."',offer_type='".$offertype."',start_date='".$start_date."',end_date='".$end_date ."' WHERE offer_id = ".$offer_id.";");
			confirm($query);

			move_uploaded_file($_FILES['offerimage']['tmp_name'],USEROFFER_UPLOAD.DS.$offerimage);
		}
		else
		{
			$offerimage=$_POST['hidden_offer_image'];

			$query=query("UPDATE offers SET ccid='".$ccid."',amount='".$amount."',offer_text='".$offertext."',offer_image='".$offerimage."',offer_type='".$offertype."',start_date='".$start_date."',end_date='".$end_date ."' WHERE offer_id = ".$offer_id.";");
			confirm($query);
		}
		redirect("admin_offer.php");
	}

	if(isset($_GET['offer_id']))
	{
		$id=$_GET['offer_id'];
		$query=query("select * from offers where offer_id='".$id."'");
		confirm($query);
		$row=fetch_array($query);-
		$ccid=$row['ccid'];
		$amount=$row['amount'];
		$offertext=$row['offer_text'];
		$offeroldimage=$row['offer_image'];
		$offerimage=image_path_offer($row['offer_image']);
		$offertype=$row['offer_type'];

		$q_c=query("select * from course_category");
		confirm($q_c);

		$v="";
		while($r_c=fetch_array($q_c))
		{
			$v.=<<< v
			<option value="{$r_c['ccid']}">{$r_c['category_name']}</option>
v;
		}
?>

<div class="graphs">
	<div class="xs">
		<div class="row">
			<div class="col-12">
				<a href="admin_offer.php" class="btn btn-info">Back To Offers</a>
			</div>
		</div>
		<div class="row">
  			<div class="col-md-4 col-xs-6"><span><big><b>Update Offer</b></big></span></div>
	  	</div>
		 <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" method="post" style="margin:5% auto 21% auto;" enctype="multipart/form-data">
								<div class="form-group">
									<label for="offer_id" class="col-sm-2 control-label">Offer ID</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="offer_id" val id="offer_id" value="<?php echo $id; ?>" readonly>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Offer Id</p>
									</div>
								</div>
								<div class="form-group">
									<label for="course_id" class="col-sm-2 control-label">Course Category</label>
									<div class="col-sm-8">
										<select name="course_id" id="course_id" required>
											<?php echo $v; ?>
										</select>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Course Id</p>
									</div>
								</div>
								<div class="form-group">
									<label for="amount" class="col-sm-2 control-label">Amount</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" value="<?php echo $amount ?>"  name="amount" id="amount" val autofocus required>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Amount</p>
									</div>
								</div>
								<div class="form-group">
									<label for="offertext" class="col-sm-2 control-label">Offer Text</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" value="<?php echo $offertext ?>"  name="offertext" id="offertext" placeholder="Write Category" val autofocus>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Offer Text</p>
									</div>
								</div>
								<div class="form-group">
									<label for="offerimage" class="col-sm-2 control-label">Offer Image</label>
									<input type="hidden" name="hidden_offer_image" value="<?php echo $offeroldimage; ?>">
									<img src="<?php echo "../../resources/$offerimage"; ?>" style="height:70px;width:70px;">
									<div class="col-sm-8">
										<input type="file" class="form-control1" name="offerimage" id="offerimage" val autofocus>
									</div>
								</div>
								<div class="form-group">
									<label for="offertype" class="col-sm-2 control-label">Offer Type</label>
									<div class="col-sm-8">
										<select name="offertype" id="offertype" required>
											<option value="course_wise">Course Wise</option>
											<option value="monthly">Monthly</option>
											<option value="yearly">Yearly</option>
										</select>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Offer Type</p>
									</div>
								</div>
								<div class="form-group">
									<label for="start_date" class="col-sm-2 control-label">Start Date</label>
									<div class="col-sm-8">
							            <input type='datetime-local' id="start_date" class="form-control1" name="start_date" placeholder="Enter Start Date" required/>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Pick the Start Date</p>
									</div>
								</div>
								<div class="form-group">
									<label for="end_date" class="col-sm-2 control-label">End Date</label>
									<div class="col-sm-8">
							            <input type='datetime-local' id="end_date" class="form-control1" name="end_date" placeholder="Enter End Date" required/>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Pick the end Date</p>
									</div>
								</div>
								<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
								<div class="form-group">
									<label for="submit_offer_update" class="col-sm-2 control-label"></label>
									<div class="col-sm-8 text-center">
										<input type="submit" name="submit_offer_update" class="btn btn_5 btn-lg btn-primary " id="submit_offer_update" value="Update Offer">
									</div>
								</div>
							</form>
						</div>
					</div>
	</div>
</div>
<?php
	}
	else
	{
		redirect("admin_category.php");
	}
}
else
    echo '<div class="bs-example4" data-example-id="contextual-table"><div class="row">
            <div class="col-12 text-center text-danger">
              <h4 style="margin-top:5em;">You Don\'t Have Permission To Access This Page</h4>
            </div>
          </div></div>';
?>

<script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datetimepicker();
                $('#datetimepicker2').datetimepicker();
            });
</script>

<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 