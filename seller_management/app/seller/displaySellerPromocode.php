<?php include("navigation.php");  ?>

<?php
  if(!(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2))
    redirect("login.php");
?>

<?php
if(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2)
{
	$showinformation=0;
	$message="";

	$sid=$_SESSION['user_id'];

	if(isset($_POST['save_promocode']) && $_POST['save_promocode']=="savepromocode")
	{
		$data['sid']=$_SESSION['user_id'];
		$data['promo_code']=$_POST['promocode'];
	    $data['valid_till']=$_POST['valid_till'];
	    $data['discount_type'] = $_POST['discounttype'];

	    if($_POST['discount_value'] == "")
	      $data['discount_value'] = 0;

	    $data['discount_value'] = $_POST['discount_value'];
	    $data['minimum_order_amount'] = $_POST['minimum_order_amount'];


		$url=DOMAIN.'/rest/seller/createSellerPromoRest.php';
		$output=getRestApiResponse($url,$data);

		if(isset($output['promos']) && $output['promos']['response_code']==200)
		{
			$showinformation=1;
			$message='<p class="text-success">Promocode added successfully</p>';
		}
		else
		if(isset($output['promos']) && $output['promos']['response_code']==405)
		{
			$showinformation=1;
			$message='<p class="text-danger">This promocode is already exist for this product</p>';
		}
		else
		{
			$showinformation=1;
			$message='<p class="text-danger">Unable to perform this operation</p>';
		}
	}
	else
	if(isset($_POST['edit_promocode']) && $_POST['edit_promocode']=="editpromocode")
	{
		$data['pid']=$_POST['pid'];
		$data['sid']=$_POST['sid'];
		$data['promo_code']=$_POST['old_promocode'];
		$data['new_promo_code']=$_POST['promocode'];
		$data['valid_till']=$_POST['valid_till'];
	  	$data['discount_type'] = $_POST['discounttype'];
    	$data['discount_value'] = $_POST['discount_value'];
    	$data['minimum_order_amount'] = $_POST['minimum_order_amount'];

		$url=DOMAIN.'/rest/seller/updateSellerPromoRest.php';
		$output=getRestApiResponse($url,$data);

		if(isset($output['promos']) && $output['promos']['response_code']==200)
		{
			$showinformation=1;
			$message='<p class="text-success">Promocode updated successfully</p>';
		}
		else
		if(isset($output['promos']) && $output['promos']['response_code']==405)
		{
			$showinformation=1;
			$message='<p class="text-danger">This promocode is already exist for this product</p>';
		}
		else
		{
			$showinformation=1;
			$message='<p class="text-danger">Unable to perform this operation</p>';
		}
	}
	else
	if(isset($_POST['delete_promocode']))
	{
		$data['pid']=$_POST['pid'];
		$data['pname']=$_POST['promo_code'];
		$url=DOMAIN.'/rest/seller/deleteSellerPromoRest.php';
		$output=getRestApiResponse($url,$data);

		if(isset($output['promos']) && $output['promos']['response_code']==200)
		{
			$showinformation=1;
			$message='<p class="text-success">Promocode deleted successfully</p>';
		}
		else
		if(isset($output['promos']) && $output['promos']['response_code']==405)
		{
			$showinformation=1;
			$message='<p class="text-danger">'.$output['promos']['response_desc'].'</p>';
		}
		else
		{
			$showinformation=1;
			$message='<p class="text-danger">Unable to perform this operation</p>';
		}
	}
?>

<div class="container-fluid">
  <h4 class="text-center pb-3">Promo Codes</h4>
	<div class="row border border-dark rounded">
		<div class="col-12 ">
		<?php
			if(!isset($_POST['edit_promocode_form']))
			{
		?>
		<form method="post" action="" id="save-promocode-form">
		  	<div class="row mt-5">
		  		<div class="col-12 col-md-6">
		    		<input type="hidden" name="sid" value="<?php echo $sid; ?>">
		    		<div class="form-group row">
			      		<label for="promocode" class="form-check-label col-5 col-md-4 col-lg-3"><b>Promo Code:</b></label>
			      		<div class="col-7 col-md-8 col-lg-9">
			      			<input type="text" name="promocode" id="promocode" class="form-control border border-left-0 border-top-0 border-right-0" required oninput="this.value = this.value.toUpperCase()">
			      		</div>
			    	</div>
		   		</div>

		   		<div class="col-12 col-md-6">
		    		<div class="form-group row">
			      		<label for="valid_till" class="form-check-label col-5 col-md-4 col-lg-3"><b>Valid Till:</b></label>
			      		<div class="col-7 col-md-8 col-lg-9">
			      			<input type="date" class="form-control border border-left-0 border-top-0 border-right-0" name="valid_till" id="valid_till" min="<?php echo date("Y-m-d"); ?>" required>
		    			</div>
		    			<div class="col-12 mt-1">
		    				<p class="text-info mb-4" >*(Promo code won't be valid after this date)</p>
		    			</div>
			    	</div>
		   		</div>

		   		<div class="col-12 col-md-6">
		    		<div class="form-group row">
						<label for="discounttype" class="form-check-label col-5 col-md-5 col-lg-3"><b>Discount Type:</b></label>
						<div class="col-7 col-md-7 col-lg-4">
		              		<select name="discounttype" id="discounttype" class="form-control border border-left-0 border-top-0 border-right-0">
			              		<option value="Percentage">Percentage</option>
			              		<option value="Flat">Flat</option>
		              		</select>
		              	</div>
		              	<div class="col-10 col-md-10 col-lg-4">
                			<input type="number" class="form-control border border-left-0 border-top-0 border-right-0 text-right" name="discount_value" id="discount_value">
            			</div>
                		<div class="col-2 col-md-2 col-lg-1 mt-2">
                    		<span class="fa-lg text-secondary" id="per">&#37;</span>
                		</div>
		   			</div>
		   		</div>

		        <div class="col-12 col-md-6">
		        	<div class="row">
		      			<label for="valid_till" class="form-check-label col-5 col-md-8 col-lg-6"><b>Minimum Order Amount(in <i class="fas fa-rupee-sign"></i>&nbsp;):</b>
		      			</label>
		      			<div class="col-7 col-md-4 col-lg-6">
		         			<input type="number" name="minimum_order_amount" value="" min="1" class="form-control border border-left-0 border-top-0 border-right-0 text-right" id="minimum_order_amount">
		         		</div>
		         		<div class="col-12 mt-1">
		         			<p class=" mb-4 text-info">*(Promocode will be applied for orders above this amount)</p>
		         		</div>
		         	</div>
		        </div>
		  	</div>

			<div class="row">
				<div class="col-12">
				    <div class="form-group text-right">
				      <input type="hidden" name="save_promocode" value="savepromocode">
				      <button type="button" class="btn btn-primary btn-md text-center enable-save-edit-confirmation" id="for-save-promocode-form">Add Promocode</button>
				    </div>
				</div>
			</div>
		</form>
		<?php
			}
			else
			{
				$data1['sid']=$_SESSION['user_id'];
				$data1['pid']=$_POST['pid'];

				$url=DOMAIN.'/rest/seller/getSellerPromosRest.php';
				$output=getRestApiResponse($url,$data1);
				
				if(isset($output['promos']) && count($output['promos'])>2)
				{
    		?>
    		<form method="post" action="" id="edit-promocode-form">
    		  	<div class="row mt-5">
    		  		<div class="col-12 col-md-6">
    		    		<input type="hidden" name="sid" value="<?php echo $sid; ?>">
    		    		<input type="hidden" name="pid" value="<?php echo $output['promos'][0]['id']; ?>">
    		    		<input type="hidden" name="old_promocode" value="<?php echo $output['promos'][0]['promo_code']; ?>">

    		    		<div class="form-group row">
				      		<label for="promocode" class="form-check-label col-5 col-md-4 col-lg-3"><b>Promo Code:</b></label>
				      		<div class="col-7 col-md-8 col-lg-9">
				      			<input type="text" name="promocode" id="promocode" class="form-control border border-left-0 border-top-0 border-right-0" value="<?php echo $output['promos'][0]['promo_code']; ?>" required oninput="this.value = this.value.toUpperCase()">
				      		</div>
				    	</div>
    		   		</div>

    		   		<div class="col-12 col-md-6">
    		   			<div class="form-group row">
				      		<label for="valid_till" class="form-check-label col-5 col-md-4 col-lg-3"><b>Valid Till:</b></label>
				      		<?php
	    			        	$old=$output['promos'][0]['expiry_date'];
	    						$informat = date('Y-m-d',strtotime($old));
	    			        ?>
				      		<div class="col-7 col-md-8 col-lg-9">
				      			<input type="date" class="form-control border border-left-0 border-top-0 border-right-0" name="valid_till" id="valid_till" min="<?php echo date("Y-m-d"); ?>" value="<?php echo $informat; ?>" required>
			    			</div>
			    			<div class="col-12 mt-1">
			    				<p class="text-info mb-4" >*(Promo code won't be valid after this date)</p>
			    			</div>
				    	</div>
    		   		</div>


	              	<div class="col-12 col-md-6">
	              		<div class="form-group row">
							<label for="discounttype" class="form-check-label col-5 col-md-5 col-lg-3"><b>Discount Type:</b></label>
							<div class="col-7 col-md-7 col-lg-4">
			              		<select name="discounttype" id="discounttype" class="form-control border border-left-0 border-top-0 border-right-0">
				              		<?php
					                    if($output['promos'][0]['discount_type']=="Percentage")
					                      echo '<option value="Percentage">Percentage</option><option value="Flat">Flat</option>';
					                    else
					                      echo '<option value="Flat">Flat</option><option value="Percentage">Percentage</option>';
				                     ?>
			              		</select>
			              	</div>
			              	<div class="col-10 col-md-10 col-lg-4">
	                			<input type="number" class="form-control border border-left-0 border-top-0 border-right-0 text-right" name="discount_value" id="discount_value" value="<?php echo $output['promos'][0]['discount_value'] ?>">
	            			</div>
	                		<div class="col-2 col-md-2 col-lg-1 mt-2">
	                			<?php
					                if($output['promos'][0]['discount_type']=="Percentage")
	                    				echo '<span class="fa-lg text-secondary" id="per">&#37;</span>';
	                    			else
	                    				echo '<span class="fa-lg text-secondary d-none" id="per">&#37;</span>';
	                    		?>
	                		</div>
			   			</div>
	    		   	</div>

	              	<div class="col-12 col-md-6">
		              	<div class="row">
			      			<label for="valid_till" class="form-check-label col-5 col-md-8 col-lg-6"><b>Minimum Order Amount(in <i class="fas fa-rupee-sign"></i>&nbsp;):</b>
			      			</label>
			      			<div class="col-7 col-md-4 col-lg-6">
			         			<input type="number" name="minimum_order_amount" value="<?php echo $output['promos'][0]['minimum_order_amount'] ?>" min="1" class="form-control border border-left-0 border-top-0 border-right-0 text-right" id="minimum_order_amount">
			         		</div>
			         		<div class="col-12 mt-1">
			         			<p class=" mb-4 text-info">*(Promocode will be applied for orders above this amount)</p>
			         		</div>
			         	</div>
	    			</div>
	    		</div>

					<div class="row">
						<div class="col-12">
						    <div class="form-group text-right">
						      <input type="hidden" name="edit_promocode" value="editpromocode">
						      <button class="btn btn-primary btn-md text-center enable-save-edit-confirmation" id="for-edit-promocode-form">Update Promocode</button>
						    </div>
						</div>
					</div>
    		</form>
		<?php
				}
			}
		?>
	</div>
	</div>
<br>
  <hr>
  <br>

<?php
	// Setting array to pass on end point
	$data['sid']=$sid;

	$data['start']=0;

	if(isset($_SESSION['pages']))
		$data['end']=$_SESSION['pages'];
	else
		$data['end']=10;

	$url=DOMAIN.'/rest/seller/getSellerPromosRest.php';

	$output=getRestApiResponse($url,$data);


	if(isset($output['promos']) && count($output['promos'])>2)
	{
?>
	<div class="row mt-1 mt-md-3">
		<div class="col-12 col-md-6">
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
		<div class="col-12 offset-md-2 col-md-4 mt-3 mt-md-0">
			<div class="input-group">
		    	<input type="text" class="form-control" placeholder="Search By Promocode" id="searchfield">
			    <div class="input-group-append">
			      <button class="btn btn-secondary" name="search" type="button" id="search">
			        <i class="fa fa-search"></i>
			      </button>
			    </div>
		  	</div>
		</div>
	</div>

	<div class="row mt-4">
		<div class="col-12 table-responsive">
			<table class="table table-hover table-bordered text-center pl-0" width="100%">
				<thead>
					<tr>
						<th>Promo Code</th>
						<th>Expiry Date</th>
						<th>Minimum Order Amount</th>
						<th>Discount Type</th>
            			<th> Discount Value</th>
						<th>Status</th>
            			<th>Is Active</th>
            			<th>Promo Applied On</th>
						<th colspan="2">Action</th>
					</tr>
				</thead>
				<tbody id="promo_body">
				<?php
					if(isset($output['promos']['rows']) && $output['promos']['rows']!=0)
					{
              			for($i=0;$i<$output['promos']['rows'];$i++)
      					{
							$current=date("Y-m-d");
							$status="";
							if(new DateTime($output['promos'][$i]['expiry_date']) >= new DateTime($current))
								$status="<p class='text-success'>Active</p>";
							else
								$status="<p class='text-danger'>Expired</p>";


							$record="";
							$record=<<< record
							<tr>
								<td>{$output['promos'][$i]['promo_code']}</td>
								<td>{$output['promos'][$i]['expiry_date']}</td>
								<td><i class="fa fa-inr"></i>&nbsp; {$output['promos'][$i]['minimum_order_amount']}</td>
								<td>{$output['promos'][$i]['discount_type']}</td>
								<td>{$output['promos'][$i]['discount_value']}</td>
              					<td>{$status}</td>

record;
								if($output['promos'][$i]['is_active'] == "Yes")
								{
									$record.=<<< record
									<td>
										<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" checked class="setstock" value="{$output['promos'][$i]['id']}">
								    </td>
record;
								}
								else
								{
									$record.=<<< record
									<td>
										<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" class="setstock" value="{$output['promos'][$i]['id']}">
								     </td>
record;
								}

                  			$record.=<<< record
                  			<td>{$output['promos'][$i]['promos_applied']} Products</td>
							<td>
								<form action="displaySellerPromocode.php" method="post">
									<input type="hidden" name="pid" value="{$output['promos'][$i]['id']}">
									<button type="submit" name="edit_promocode_form" class="btn btn-primary">Edit</button>
								</form>
							</td>
							<td>
								<button type="button" class="btn btn-danger enabledeletepopup" pid="{$output['promos'][$i]['id']}" pname="{$output['promos'][$i]['promo_code']}"><i class="fa fa-trash"></i></button>
							</td>
							</tr>
record;
								echo $record;
              			}
					}
				?>
<script>
	$(".enabledeletepopup").on("click",
		function(){
			pid=$(this).attr('pid');
			pname=$(this).attr('pname');
			$("#setpid").val(pid);
			$("#setpname").val(pname);
			$("#delete-confirmation-modal").modal('show');
	});

	$('.setstock').change(
    	function(){

	      value="No";
	      pid=$(this).val();

	      if($(this).prop('checked'))
	       value="Yes";
	      else
	       value="No";

	   	  var tobesend = 'pid='+pid+'&value='+value;

	   		$.ajax({
	            type: 'POST',
	            url: 'setSellerPromoInStockHelper.php',
	            data: tobesend,
	            dataType: 'json',
	            success: function(response)
	            {
	                if(response.status == 1)
	                {
	                    $("#information").html("<p class=\"text-success\">Promocode status updated</p>");
                        $("#information-modal").modal("show");
	                }
	                else
	                {
	                    $("#information").html("<p class=\"text-danger\">Unable to update promocode status!</p>");
                        $("#information-modal").modal("show");
	                }
	            }
		    });
    });
</script>
				</tbody>
			</table>
		</div>
	</div>

	<?php
		// Setting Pagination
		// $sql="select * from promocodes where seller_id='".$sid."'";
		// setupPagination($sql);

      	$qp=query("select * from promocodes where seller_id='".$sid."'");
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
    ?>

    <?php
	}		// if for table
	?>

<!-- Modal for confirmation -->
<div class="modal fade" id="delete-confirmation-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-exclamation-triangle text-danger"></i>&nbsp;Delete Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Do you really want to delete this promocode ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         <form action="displaySellerPromocode.php" method="post">
         	<input type="hidden" name="pid" value="" id="setpid">
         	<input type="hidden" name="promo_code" value="" id="setpname">
        	<button type="submit" name="delete_promocode" class="btn btn-danger text-white" id="yesdelete">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
	if($showinformation==1)
		echo '<script>
				$("#information").html(\''.$message.'\');
				$("#information-modal").modal("show");
			</script>';
?>
</div>

<?php
}
else
	echo "Login First";
?>

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

      $(".page").removeClass("active");
      $(this).addClass("active");

      $.get("changeSellerPromocodeList.php?start="+start+"&end="+end,
        function(data,status)
        {
          $("#promo_body").html(data);
        });
    });

    pageNumber=5;
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
      if(pageNumber==5)
        return 0;

   		$(".page").css("display","none");
    	pageNumber-=5;
     	$("#button"+(pageNumber-4)).css("display","inline-block");
     	$("#button"+(pageNumber-3)).css("display","inline-block");
     	$("#button"+(pageNumber-2)).css("display","inline-block");
     	$("#button"+(pageNumber-1)).css("display","inline-block");
     	$("#button"+pageNumber).css("display","inline-block");
    });

    $("#next").click(function(){
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
			promo=$("#searchfield").val();

			$.get("searchPromo.php?search="+promo,
				function(data,status)
				{
					$("#promo_body").html(data);
				});
		});
</script>

<script>
	$("#discounttype").change(function()
	{
		type=$(this).val();

		if(type=="Flat")
		{
			if(!$("#per").hasClass("d-none"))
				$("#per").addClass("d-none");
		}
		else
		if(type=="Percentage")
		{
			if($("#per").hasClass("d-none"))
				$("#per").removeClass("d-none");
		}
	});
</script>

<script>
	$(".enable-save-edit-confirmation").on("click",
		function(event)
		{
			event.preventDefault();
			id=$(this).attr("id");

			if(id=="for-save-promocode-form")
			{
				$("#save-edit-confirmation-modal-title").html("Save Confirmation");
				$("#save-edit-confirmation-modal-body").html("Do you really want to save this promocode?");
				$("#yes-save-edit").attr("fortype","for-save-promocode-form");
			}
			else
			{
				$("#save-edit-confirmation-modal-title").html("Update Confirmation");
				$("#save-edit-confirmation-modal-body").html("Do you really want to update this promocode?");
				$("#yes-save-edit").attr("fortype","for-edit-promocode-form");
			}
			$("#save-edit-confirmation-modal").modal('show');
		});

	$("#yes-save-edit").on("click",function()
	{
		formtype=$("#yes-save-edit").attr("fortype");

		if($("#promocode").val()!="" && $("#valid_till").val()!="" && $("#discounttype").val()!="" && $("#discount_value").val()!="" && $("#minimum_order_amount").val()!="")
		{
			if($("#discounttype").val()=="Percentage" && ($("#discount_value").val()>=1 && $("#discount_value").val()<=100))
			{
				if(formtype=="for-save-promocode-form")
					$("#save-promocode-form").submit();
				else
				if(formtype=="for-edit-promocode-form")
					$("#edit-promocode-form").submit();
			}
			else
			if($("#discounttype").val()=="Flat" && $("#discount_value").val()>=1)
			{
				if(formtype=="for-save-promocode-form")
					$("#save-promocode-form").submit();
				else
				if(formtype=="for-edit-promocode-form")
					$("#edit-promocode-form").submit();
			}
			else
			{
				$("#save-edit-confirmation-modal").modal('hide');
				$("#discount_value").focus();
			}
		}
		else
		{
			$("#save-edit-confirmation-modal").modal('hide');

			if($("#promocode").val()=="")
				$("#promocode").focus();
			else
			if($("#valid_till").val()=="")
				$("#valid_till").focus();
			else
			if($("#discounttype").val()=="")
				$("#discounttype").focus();
			else
			if($("#discount_value").val()=="")
				$("#discount_value").focus();
			else
			if($("#minimum_order_amount").val()=="")
				$("#minimum_order_amount").focus();
		}
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
		location.href="displaySellerPromocode.php";
	});
</script>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

</body>
</html>
