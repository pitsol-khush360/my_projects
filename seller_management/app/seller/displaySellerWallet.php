<?php include("navigation.php"); ?>

<?php

	//setcookie('rebuildsession','',time()-3600);
	//$_COOKIE['rebuildsession']=$_SESSION;


//setcookie('rebuildsession',"",time()-3600);
	//print_r($_COOKIE);

  // if(!isset($_COOKIE['rebuildsession']))
  // {
  // 	setcookie('rebuildsession',$_SESSION);
  // }

  // if(!isset($_SESSION['user_id']))
  // {
  // 	$_SESSION['user_id']=$_COOKIE['rebuildsession']['user_id'];
  // 	$_SESSION['role']=$_COOKIE['rebuildsession']['role'];
  // 	$_SESSION['username']=$_COOKIE['rebuildsession']['username'];
  // 	$_SESSION['business_name']=$_COOKIE['rebuildsession']['business_name'];
  // 	$_SESSION['mobile']=$_COOKIE['rebuildsession']['mobile'];
  // 	$_SESSION['seller_image']=$_COOKIE['rebuildsession']['seller_image'];

  // 	setcookie("rebuildsession","",time()-3600);
  // }

  if(!(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2))
    redirect("login.php");
?>

<?php 
if(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2)
{
	if(isset($_POST['seetransactiondetails']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['movement_id']=$_POST['mid'];

		$url=DOMAIN.'/rest/seller/getTransactionDetailsRest.php';
		$output=getRestApiResponse($url,$data);

		if(isset($output['getwalletbalance']) && count($output['getwalletbalance'])>2)
		{
?>

<div class="container-fluid">
	<div class="row mt-3">
		<div class="col-12 mt-1">
      		<div class="card bg-info text-white text-center">
				<div class="card-header pt-0 pb-0">
					<h5>Transaction Details</h5>
				</div>
			</div>
      	</div>
      	<div class="col-12 mt-5">
		    <div class="row">
				<div class="offset-md-3 col-6 col-md-3">
					Transaction Amount:
				</div>
				<div class="col-6 col-md-3 text-right">
					<?php 
						if($output['getwalletbalance'][0]['dr_cr_Indicator']=="C")
                        	echo "<span class='text-success'><i class='fas fa-rupee-sign'></i>&nbsp;<b>".$output['getwalletbalance'][0]['amount']."</b></span>";
                        else
                        if($output['getwalletbalance'][0]['dr_cr_Indicator']=="D")
                        	echo "<span class='text-danger'><i class='fas fa-rupee-sign'></i>&nbsp;<b>".$output['getwalletbalance'][0]['amount']."</b></span>";
                    ?>
				</div>
				<div class="offset-md-3 col-6 col-md-3">
					Transaction Type:
				</div>
				<div class="col-6 col-md-3 text-right">
					<?php
						if($output['getwalletbalance'][0]['dr_cr_Indicator']=="C")
							echo "Credit";
						else
						if($output['getwalletbalance'][0]['dr_cr_Indicator']=="D")
							echo "Debit";
					?>
				</div>
				<div class="offset-md-3 col-6 col-md-3">
					Transaction ID:
				</div>
				<div class="col-6 col-md-3 text-right">
					<?php echo $output['getwalletbalance'][0]['cash_movement_id']; ?>
				</div>
				<div class="offset-md-3 col-6 col-md-3">
					Opening Balance:
				</div>
				<div class="col-6 col-md-3 text-right">
					<?php echo "<i class='fas fa-rupee-sign'></i>&nbsp;".$output['getwalletbalance'][0]['opening_balance']; ?>
				</div>
				<div class="offset-md-3 col-6 col-md-3">
					Closing Balance:
				</div>
				<div class="col-6 col-md-3 text-right">
					<?php echo "<i class='fas fa-rupee-sign'></i>&nbsp;".$output['getwalletbalance'][0]['closing_balance']; ?>
				</div>
				<div class="offset-md-3 col-6 col-md-3">
					Date Time:
				</div>
				<div class="col-6 col-md-3 text-right">
					<?php echo $output['getwalletbalance'][0]['date']; ?>&nbsp;&nbsp;<?php echo $output['getwalletbalance'][0]['time']; ?>
				</div>
				<div class="offset-md-3 col-6 col-md-3">
					Description:
				</div>
				<div class="col-6 col-md-3 text-right">
					<?php 
						if(!is_null($output['getwalletbalance'][0]['movement_description']))
							echo $output['getwalletbalance'][0]['movement_description'];
						else
							echo "";
					?>
				</div>
				<div class="offset-md-3 col-6 col-md-3">
					Payment Reference:
				</div>
				<div class="col-6 col-md-3 text-right">
					<?php 
						if(!is_null($output['getwalletbalance'][0]['payment_reference']))
							echo $output['getwalletbalance'][0]['payment_reference'];
						else
							echo "";
					?>
				</div>
				<div class="offset-md-3 col-6 col-md-3">
					Order ID:
				</div>
				<div class="col-6 col-md-3 text-right">
					<?php echo $output['getwalletbalance'][0]['order_id']; ?>
				</div>
				<div class="offset-md-3 col-6 col-md-3">
					Status:
				</div>
				<div class="col-6 col-md-3 text-right">
					<?php 
						if($output['getwalletbalance'][0]['movement_status']==1)
							echo "<b>Generated</b>";
						else
						if($output['getwalletbalance'][0]['movement_status']==2)
							echo "<b>Posted</b>";
						else
						if($output['getwalletbalance'][0]['movement_status']==3)
							echo "<b>Refund Pending</b>";
						else
						if($output['getwalletbalance'][0]['movement_status']==4)
							echo "<b>Ready For Settlement</b>";
						else
						if($output['getwalletbalance'][0]['movement_status']==5)
							echo "<b>Settled</b>";
						else
						if($output['getwalletbalance'][0]['movement_status']==6)
							echo "<b>Returned</b>";
						else
						if($output['getwalletbalance'][0]['movement_status']==7)
							echo "<b>Refunded</b>";
						else
					?>
				</div>
			</div>
      	</div>
		<div class="col-12 mt-5">
		    <a href="javascript:history.go(-1)">
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

		$url=DOMAIN.'/rest/seller/getWalletBalanceRest.php';
		$output=getRestApiResponse($url,$data1);
		
		$wallet_balance=0.00;
		$kyc_completed=0;
		$accept_online_payment=0;
		$bank_account_verified="No";

		if(isset($output['getwalletbalance']) && count($output['getwalletbalance'])>2)
		{
			$wallet_balance=round($output['getwalletbalance']['walletbalance'],2);
			$kyc_completed=$output['getwalletbalance']['kyc_completed'];
			$bank_account_verified=$output['getwalletbalance']['bank_account_verified'];
			$accept_online_payment=$output['getwalletbalance']['accept_online_payments'];
		}
?>
<div class="container-fluid">
	<div class="row mt-5">	
		<div class="col-6 mt-4">
			<h6>Wallet Balance:</h6>
		</div>
		<div class="col-6 text-right mt-4">
			<h6><i class="fas fa-rupee-sign"></i>&nbsp;<?php if(isset($wallet_balance)) echo $wallet_balance; ?></h6>
		</div>
	</div>
	<hr>

	<div class="row mt-3">
		<div class="col-4 col-md-2">
			<?php 
				if($kyc_completed==1 && $bank_account_verified=="Yes" && $accept_online_payment==1)
					echo '<a href="displayAddWithdrawWalletMoney.php?addmoney" style="text-decoration:none;">';
				else
					echo '<button class="btn btn-secondary bg-transparent text-dark border-0" disabled>';
			?>
			<div class="row">
				<div class="col-12 text-center">
					<i class="fas fa-coins fa-2x"></i>
				</div>
				<div class="col-12 text-center mt-2">
					<h6>Add Money</h6>
				</div>
			</div>
			<?php 
				if($kyc_completed==1 && $bank_account_verified=="Yes" && $accept_online_payment==1)
					echo '</a>';
				else
					echo '</button>';
			?>
		</div>
		<div class="col-4 col-md-2">
			<?php 
				if($kyc_completed==1 && $bank_account_verified=="Yes" && $accept_online_payment==1)
					echo '<a href="displayAddWithdrawWalletMoney.php?withdrawmoney" style="text-decoration:none;">';
				else
					echo '<button class="btn btn-secondary bg-transparent text-dark border-0" disabled>';
			?>
			<div class="row">
				<div class="col-12 text-center">
					<i class="fas fa-university fa-2x"></i>
				</div>
				<div class="col-12 text-center mt-2">
					<h6>Withdraw Money</h6>
				</div>
			</div>
			<?php 
				if($kyc_completed==1 && $bank_account_verified=="Yes" && $accept_online_payment==1)
					echo '</a>';
				else
					echo '</button>';
			?>
		</div>
		<div class="col-4 col-md-2">
			<?php 
				if($kyc_completed==1 && $bank_account_verified=="Yes" && $accept_online_payment==1)
					echo '<a href="displaySellerDownloadStatement.php" style="text-decoration:none;">';
				else
					echo '<button class="btn btn-secondary bg-transparent text-dark border-0" disabled>';
			?>
			<div class="row">
				<div class="col-12 text-center">
					<i class="fas fa-download fa-2x"></i>
				</div>
				<div class="col-12 text-center mt-2">
					<h6>Download Statement</h6>
				</div>
			</div>
			<?php 
				if($kyc_completed==1 && $bank_account_verified=="Yes" && $accept_online_payment==1)
					echo '</a>';
				else
					echo '</button>';
			?>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col-12 text-danger">
			<h6>* Wallet Operations are only allowed if KYC is completed, Bank Account is Verified & Online Payments are accepted</h6>
		</div>
	</div>

<?php
	$data5['user_id']=$_SESSION['user_id'];

	$url=DOMAIN.'/rest/seller/getWalletBalanceTransactionsRest.php';
	$output=getRestApiResponse($url,$data5);
		
	if(isset($output['getwalletbalance']) && count($output['getwalletbalance'])>2)
	{
?>
	<div class="row mt-5">
		<div class="col-12">
			<div class="card">
				<div class="card-header pt-0 pb-0">
					<h5>Your Transactions</h5>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-3">
		<div class="col-12">
			<!-- Tab list -->
            <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="processed-tab" data-toggle="tab" href="#processed" role="tab" aria-controls="processed" aria-selected="true">Processed</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="false">Pending</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="refunds-tab" data-toggle="tab" href="#refunds" role="tab" aria-controls="refunds" aria-selected="false">Refunds</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="processed" role="tabpanel" aria-labelledby="processed-tab">
                <?php
                    $data2['user_id']=$_SESSION['user_id'];
                    $data2['status']="Processed";

					$url=DOMAIN.'/rest/seller/getWalletBalanceTransactionsRest.php';
					$output=getRestApiResponse($url,$data2);

                    if(isset($output['getwalletbalance']) && $output['getwalletbalance']['rows']!=0)
                        displayTransactionsForWalletScreen($output);
                    else
                        echo "<h5 class='text-center text-danger mt-5'>No Processed Transactions</h5>";
                ?>
              </div>
              <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                <?php
                	$data3['user_id']=$_SESSION['user_id'];
                    $data3['status']="Pending";

					$url=DOMAIN.'/rest/seller/getWalletBalanceTransactionsRest.php';
					$output=getRestApiResponse($url,$data3);

					if(isset($output['getwalletbalance']) && $output['getwalletbalance']['rows']!=0)
                        displayTransactionsForWalletScreen($output);
                    else
                        echo "<h5 class='text-center text-danger mt-5'>No Pending Transactions</h5>";
                ?>
              </div>
              <div class="tab-pane fade" id="refunds" role="tabpanel" aria-labelledby="refunds-tab">
                <?php
                	$data4['user_id']=$_SESSION['user_id'];
                    $data4['status']="Refund";

					$url=DOMAIN.'/rest/seller/getWalletBalanceTransactionsRest.php';
					$output=getRestApiResponse($url,$data4);

					if(isset($output['getwalletbalance']) && $output['getwalletbalance']['rows']!=0)
                        displayTransactionsForWalletScreen($output);
                    else
                        echo "<h5 class='text-center text-danger mt-5'>No Refund Transactions</h5>";
                ?>
              </div>
            </div>
		</div>
	</div>
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