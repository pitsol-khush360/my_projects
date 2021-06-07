<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php 	
		if(isset($_POST['submit_insert_offer']))
		{
			$ccid=$_POST['course_id'];
			$amount=$_POST['amount'];
			$offertext=$_POST['offertext'];
			$offertype=$_POST['offertype'];
			$start_date=$_POST['start_date'];
			$end_date=$_POST['end_date'];
			$offerimage=$_FILES['offerimage']['name'];

			if(!empty($_FILES['offerimage']['name']) && ($_FILES['offerimage']['type']=="image/jpg" || $_FILES['offerimage']['type']=="image/jpeg" || $_FILES['offerimage']['type']=="image/png")) 
			{
				$offerimage=date("YmdHis").'offerimg'.$offerimage;

				$query=query("insert into offers (ccid,amount,offer_text,offer_image,offer_type,start_date,end_date) value('".$ccid."','".$amount."','".$offertext."','".$offerimage."','".$offertype."','".$start_date."','".$end_date."')");
				confirm($query);

				move_uploaded_file($_FILES['offerimage']['tmp_name'],USEROFFER_UPLOAD.DS.$offerimage);
			}
			else
			{
				$query=query("insert into offers (ccid,amount,offer_text,offer_image,offer_type,start_date,end_date) value('".$ccid."','".$amount."','".$offertext."','defaultoffer.jpg','".$offertype."','".$start_date."','".$end_date."')");
				confirm($query);
			}
			redirect("admin_offer.php");
		}

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
  			<div class="col-md-4 col-xs-6"><span><big><b>Insert Offer</b></big></span></div>
	  	</div>
		 <div class="tab-content">
						<div class="tab-pane active" id="horizontal-form">
							<p class="text-danger text-center" style="font-size:20px;"><?php displaymessage(); ?></p>
							<form class="form-horizontal" enctype="multipart/form-data" method="post" style="margin:5% auto 5% auto;">
								<div class="form-group">
									<label for="course_id" class="col-sm-2 control-label">Course Id</label>
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
										<input type="text" class="form-control1" name="amount" id="amount" val autofocus required>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Amount</p>
									</div>
								</div>
								<div class="form-group">
									<label for="offertext" class="col-sm-2 control-label">Offer Text</label>
									<div class="col-sm-8">
										<input type="text" class="form-control1" name="offertext" id="offertext" placeholder="Offer Description" val autofocus>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Offer Text</p>
									</div>
								</div>
								<div class="form-group">
									<label for="offerimage" class="col-sm-2 control-label">Offer Image</label>
									<div class="col-sm-8">
										<input type="file" class="form-control1" name="offerimage" id="offerimage" val autofocus>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Offer Image</p>
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
										<div class='input-group date' id='datetimepicker2'>
							                <input type='datetime-local' id="end_date" class="form-control1" name="end_date" placeholder="Enter End Date" required/>
							            </div>
									</div>
									<div class="col-sm-2">
										<p class="help-block">Pick the end Date</p>
									</div>
								</div>
								<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
								<div class="form-group">
									<label for="submit_insert" class="col-sm-2 control-label"></label>
									<div class="col-sm-8 text-center">
										<input type="submit" name="submit_insert_offer" class="btn btn_5 btn-lg btn-primary " id="submit_insert" value="Add">
									</div>
								</div>
							</form>
						</div>
					</div>
	</div>
</div>

<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 