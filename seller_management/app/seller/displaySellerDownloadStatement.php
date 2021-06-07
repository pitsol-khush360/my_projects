<?php include("navigation.php"); ?>

<?php
  if(!(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2))
    redirect("login.php");
?>

<?php
if(isset($_POST['generate_statement']))
{
    $data['user_id']=$_SESSION['user_id'];

    if(isset($_POST['interval']) && $_POST['interval']=="This Month")
        $data['interval']=1;
    if(isset($_POST['interval']) && $_POST['interval']=="Last 1 Month")
        $data['interval']=2;
    if(isset($_POST['interval']) && $_POST['interval']=="Last 3 Months")
        $data['interval']=3;
    if(isset($_POST['interval']) && $_POST['interval']=="Last 6 Months")
        $data['interval']=4;
    if(isset($_POST['interval']) && $_POST['interval']=="Current Year")
        $data['interval']=5;
    if(isset($_POST['interval']) && $_POST['interval']=="Last 1 Year")
        $data['interval']=6;

    $interval="";
    $description="";

    if(isset($_POST['interval']))
      $description=$_POST['interval'];

    if(isset($_POST['from_date']) && $_POST['from_date']!="" && isset($_POST['to_date']) && $_POST['to_date']!="")
    {
      $data['start']=$_POST['from_date'];
      $data['end']=$_POST['to_date'];
      $data['interval']=7;

      $description=$_POST['from_date']." To ".$_POST['to_date'];
    }

    $interval=$data['interval'];

    $url=DOMAIN.'/rest/seller/getWalletBalanceTransactionsStatementRest.php';
    $output=getRestApiResponse($url,$data);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mt-3">
            <a href="javascript:history.go(-1)">
              <button class="btn btn-success btn-md">Back</button>
            </a>
        </div>
    </div>
<?php
    if(isset($output['getwalletbalance']) && $output['getwalletbalance']['response_code']==200)
    {
?>
    <div class="row mt-2">
        <div class="col-12 text-center mt-4">
              <h4>View Statement</h4>
        </div>
    </div>
    <?php
      if($output['getwalletbalance']['rows']!=0)
      {
        if(isset($_POST['statement_mode']) && $_POST['statement_mode']=="downloadstatement")
        {
          $description="Your ".$description;
          $tabdata=prepareTransactionsDataForDownloadStatement($output,$_SESSION['user_id'],$_SESSION['business_name'],$description);
          // creating new instance of the library
          require_once '../../public/mcpdf/vendor/autoload.php';
          $mpdf = new \Mpdf\Mpdf();
          $mpdf->WriteHTML($tabdata);
          ob_clean();
          $mpdf->Output("transaction_statement.pdf","D");
        }
        else
        if(isset($_POST['statement_mode']) && $_POST['statement_mode']=="getonemail")
        {

          //$data1['attachment']=$pdf;
          $data1['user_id']=$_SESSION['user_id'];
          $data1['statement_mode']="getonemail";
          $data1['interval']=$interval;
          //$data1['email']="klrankawat30@gmail.com";
          //$data1['message']="Your Transactions Statement";

          $url=DOMAIN.'/rest/seller/getWalletBalanceTransactionsStatementRest.php';
          $output=getRestApiResponse($url,$data1);

          if(isset($output['getwalletbalance']) && $output['getwalletbalance']['response_code']==200)
            echo '<div class="row">
                    <div class="col-12 mt-4 text-center text-success">
                      <h5>Your transactions statement mailed successfully</h5>
                    </div>
                  </div>';
          else
            echo '<div class="row">
                    <div class="col-12 mt-4 text-center text-danger">
                      <h5>Unable to mail your transactions statement</h5>
                    </div>
                  </div>';
        }
        else
        if(isset($_POST['statement_mode']) && $_POST['statement_mode']=="getonscreen")
        {
          $tabdata="";

          $tabdata.=<<<tabdata
            <div class="row mt-5 mb-2">
              <div class="col-5 col-md-3 text-center text-dark">
                <h6>Date</h6>
              </div>
              <div class="col-7 col-md-4 text-center text-dark">
                <h6>Description / Order Id</h6>
              </div>
              <div class="col-3 col-md-2 text-center text-dark">
                <h6>Amount</h6>
              </div>
              <div class="col-9 col-md-3 text-center text-dark">
                <h6>Closing Balance</h6>
              </div>
            </div>
tabdata;

          for($i=0;$i<$output['getwalletbalance']['rows'];$i++)
          {
            $price="";

            if($output['getwalletbalance'][$i]['dr_cr_Indicator']=="C")
              $price="<p class='text-success'><i class='fas fa-rupee-sign'></i>&nbsp;<b>".$output['getwalletbalance'][$i]['amount']."</b></p>";
            else
            if($output['getwalletbalance'][$i]['dr_cr_Indicator']=="D")
              $price="<p class='text-danger'><i class='fas fa-rupee-sign'></i>&nbsp;<b>".$output['getwalletbalance'][$i]['amount']."</b></p>";

            $tabdata.=<<< tabdata
            <div class="row mt-3 pb-0">
              <div class="col-12">
                <div class="row">
                  <div class="col-5 col-md-3">
                    {$output['getwalletbalance'][$i]['date']}&nbsp;{$output['getwalletbalance'][$i]['time']}
                  </div>
                  <div class="col-7 col-md-4 text-center">
                    {$output['getwalletbalance'][$i]['movement_description']} / {$output['getwalletbalance'][$i]['order_id']}
                  </div>
                  <div class="col-3 col-md-2 text-right mt-3 mt-md-0">
                    <span>{$price}</span>
                  </div>
                  <div class="col-9 col-md-3 text-right mt-3 mt-md-0">
                    <span>{$output['getwalletbalance'][$i]['closing_balance']}</span>
                  </div>
                </div>
              </div>
            </div>
            <hr>
tabdata;
          }
          echo $tabdata;
        }
      }
      else
      {
    ?>
      <div class="row mt-4">
        <div class="col-12 text-danger text-center">
          <h5>You don't have any transactions in your specified Interval!</h5>
        </div>
      </div>
    <?php
      }
    ?>
</div>
<?php
    }
}
else
{
?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-12 text-center mt-4">
              <h4>Download Statement</h4>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">

            <form action="" method="post">

                <div class="row">
                    <div class="col-6 col-md-4 text-md-right">
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="statement_mode" value="getonscreen" required autocomplete="off">&nbsp;Display on Screen
                          </label>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 text-md-center">
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="statement_mode" value="getonemail" required autocomplete="off">&nbsp;Email Statement
                          </label>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="statement_mode" value="downloadstatement" required autocomplete="off">&nbsp;Download Statement
                          </label>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row mt-5">
                    <div class="col-6 col-md-3">
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input interval_checkbox" name="interval" value="This Month" autocomplete="off">&nbsp;This Month
                          </label>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input interval_checkbox" name="interval" value="Last 1 Month" autocomplete="off">&nbsp;Last 1 Month
                          </label>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input interval_checkbox" name="interval" value="Last 3 Months" autocomplete="off">&nbsp;Last 3 Months
                          </label>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input interval_checkbox" name="interval" value="Last 6 Months" autocomplete="off">&nbsp;Last 6 Months
                          </label>
                        </div>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-6 col-md-3">
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input interval_checkbox" name="interval" value="Current Year" autocomplete="off">&nbsp;Current Year
                          </label>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input interval_checkbox" name="interval" value="Last 1 Year" autocomplete="off">&nbsp;Last 1 Year
                          </label>
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12 col-md-3">
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="custom-date-enabler" autocomplete="off">&nbsp;Custom
                          </label>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label for="from_date" class="col-form-label col-6"><b>Start Date:</b></label>
                                <div class="col-6">
                                    <input type="date" class="form-control" name="from_date" id="from_date" class="form-control border border-left-0 border-top-0 border-right-0" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <div class="row">
                                <label for="to_date" class="col-form-label col-6 text-md-right"><b>End Date:</b></label>
                                <div class="col-6">
                                    <input type="date" class="form-control" name="to_date" id="to_date" class="form-control border border-left-0 border-top-0 border-right-0" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <input type="submit" name="generate_statement" class="btn btn-primary" value="Generate Statement" id="generate_statement" disabled>
                    </div>
                </div> 
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mt-3">
            <a href="javascript:history.go(-1)">
              <button class="btn btn-success btn-md">Back</button>
            </a>
        </div>
    </div>
</div>

<?php
}
?>

        </div>
        <!-- content end -->
    </div>
    <!-- page content -->
</div>
<!-- page wrapper end -->

<?php include("footer.php"); ?>

<script>
  $("#custom-date-enabler").on("click",
      function()
      {
        if(this.checked)
        {
          $(".interval_checkbox").prop("checked",false);
          $(".interval_checkbox").attr("disabled",true);

          $("#from_date").attr("disabled",false);
          $("#from_date").attr("required",true);
          $("#from_date").focus();
          $("#to_date").attr("disabled",false);
          $("#to_date").attr("required",true);

          $("#generate_statement").attr("disabled",false);
        }
        else
        {
          $(".interval_checkbox").attr("disabled",false);

          $("#from_date").attr("disabled",true);
          $("#from_date").attr("required",false);
          $("#to_date").attr("disabled",true);
          $("#to_date").attr("required",false);

          $("#generate_statement").attr("disabled",true);
        }
      });

  $(".interval_checkbox").on("change",
    function(){
      $(".interval_checkbox").not(this).prop("checked",false);
      $("#generate_statement").attr("disabled",false);
  });
</script>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

  </body>
</html>
