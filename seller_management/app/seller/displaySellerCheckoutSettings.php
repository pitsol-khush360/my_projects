<?php include("navigation.php"); ?>

<?php
  if(!(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2))
    redirect("login.php");
?>

<?php

$showinformation=0;
$message="";

if(isset($_POST['submitdeliverychargesetting']))
{
    if($_POST['charge']!="" && $_POST['freeabove']!="")
    {
        $data['user_id']=$_SESSION['user_id'];
        $data['delivery_charge']=$_POST['charge'];
        $data['delivery_free_above']=$_POST['freeabove'];

        $url=DOMAIN.'/rest/seller/updateSellerDeliveryChargeRest.php';
        $output=getRestApiResponse($url,$data);
        
        if(isset($output['updatedeliverycharge']) && $output['updatedeliverycharge']['response_code']==200)
        {
            $showinformation=1;
            $message='<p class="text-success">Delivery charge settings updated successfully</p>';
        }
        else
        {
            $showinformation=1;
            $message='<p class="text-danger">Unable to perform this operation</p>';
        }
    }
    else
    {
        $showinformation=1;
        $message='<p class="text-danger">Charge Fields Must Not Be Blank!</p>';
    }
}
?>

<div class="container-fluid">

<?php
    $data['user_id']=$_SESSION['user_id'];
    $url=DOMAIN.'/rest/seller/getSellerDeliveryChargeRest.php';
    $output=getRestApiResponse($url,$data);

    $deliverycharge=0;
    $deliveryfreeabove=0;
    $acceptcodpayments=0;
    $acceptonlinepayments=0;
    $logisticsintegrated="No";
    $notificationemail=1;
    $notificationsms=0;
    $notificationwhatsapp=0;
    $kyc_completed=0;
    $bank_account_verified="No";

    if(isset($output['getdeliverycharge']) && count($output['getdeliverycharge'])>2)
    {
        $deliverycharge=$output['getdeliverycharge']['delivery_charge'];
        $deliveryfreeabove=$output['getdeliverycharge']['delivery_free_above'];
        $acceptcodpayments=$output['getdeliverycharge']['accept_cod_payments'];
        $acceptonlinepayments=$output['getdeliverycharge']['accept_online_payments'];
        $logisticsintegrated=$output['getdeliverycharge']['logistics_integrated'];
        $notificationemail=$output['getdeliverycharge']['notification_email'];
        $notificationsms=$output['getdeliverycharge']['notification_sms'];
        $notificationwhatsapp=$output['getdeliverycharge']['notification_whatsapp'];
        $kyc_completed=$output['getdeliverycharge']['kyc_completed'];
        $bank_account_verified=$output['getdeliverycharge']['bank_account_verified'];
    }
?>

    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-white bg-secondary">
                    Delivery Charge Settings
                </div>
                <div class="card-body">
                    <div class="row mt-1">
                        <div class="col-12 text-right">
                            <button class="btn btn-primary" id="deliverychargedetails-enabler">Edit</button>
                        </div>
                        <div class="col-12 mt-2">
                            <form action="" method="post">
                                <div class="row mt-3">
                                    <label for="charge" class="col-6 col-md-2 col-form-label"><b>Delivery Charge:</b></label>
                                    <div class="col-6 col-md-2">
                                        <div class="input-group">
                                          <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-rupee-sign"></i></span>
                                          </div>
                                          <input type="number" class="form-control text-right" name="charge" id="charge" value="<?php echo $deliverycharge; ?>" disabled>
                                        </div>
                                    </div>
                                
                                    <label for="freeabove" class="col-6 col-md-6 col-form-label text-md-right mt-2 mt-md-0"><b>Free For Orders Above:</b></label>
                                    <div class="col-6 col-md-2 mt-2 mt-md-0">
                                        <div class="input-group">
                                          <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-rupee-sign"></i></span>
                                          </div>
                                          <input type="number" class="form-control text-right" name="freeabove" id="freeabove" value="<?php echo $deliveryfreeabove; ?>" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-5 d-none" id="deliverychargedetails-formbuttons">
                                    <div class="offset-md-3 col-6 col-md-3 text-right">
                                        <a href="displaySellerCheckoutSettings.php" class="btn btn-danger w-100">Cancel</a>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="form-group">
                                          <input type="submit" name="submitdeliverychargesetting" class="btn btn-success btn-md w-100" value="Save Charge Details">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-12 mt-3 text-danger">
                            <p>*Order amount is the total Cart amount and delivery charges captured here will be added to Order Total</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-white bg-secondary">
                    Payment Method Settings
                </div>
                <div class="card-body">
                    <div class="row mt-1">
                        <div class="col-12 mt-2">
                            <div class="row">
                                <div class="col-2 col-md-1">
                                    <i class="fas fa-rupee-sign fa-2x"></i>
                                </div>
                                <div class="col-7 col-md-5">
                                    <span class="ml-3">Cash On Delivery (COD)</span>
                                </div>
                                <div class="col-3">
                                    <?php
                                        if($acceptonlinepayments==1 && $kyc_completed==1 && $bank_account_verified=="Yes") 
                                        {
                                            if($acceptcodpayments==1)
                                            {
                                                echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" checked id="codpaymentsetting">';
                                            }
                                            else
                                            {
                                                echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" id="codpaymentsetting">';
                                            }
                                        }
                                        else
                                        {
                                            if($acceptcodpayments==1)
                                            {
                                                echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" checked id="codpaymentsetting" disabled>';
                                            }
                                            else
                                            {
                                                echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" id="codpaymentsetting" disabled>';
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-2 col-md-1">
                                    <i class="fas fa-credit-card fa-2x"></i>
                                </div>
                                <div class="col-7 col-md-5">
                                    <span class="ml-3">Accept Online Payments</span>
                                </div>
                                <div class="col-3">
                                    <?php
                                        if($kyc_completed==1 && $bank_account_verified=="Yes") 
                                        {
                                            if($acceptonlinepayments==1)
                                            {
                                                if($acceptcodpayments==1)
                                                {
                                                    echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" checked id="onlinepaymentsetting">';
                                                }
                                                else
                                                {
                                                   echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" checked id="onlinepaymentsetting" disabled>'; 
                                                }
                                            }
                                            else
                                            {
                                                if($acceptcodpayments==1)
                                                {
                                                    echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" id="onlinepaymentsetting">';
                                                }
                                                else
                                                {
                                                   echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" id="onlinepaymentsetting" disabled>'; 
                                                }
                                            }
                                        }
                                        else
                                        {
                                            echo '<span title="Please submit your KYC to enable this option"><input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary"  disabled></span>';
                                        }
                                    ?>
                                </div>
                                <div class="col-12 text-danger mt-3">
                                    <span>* Buyer will not be able to pay online if this field is set to No.</span>
                                </div>
                                <div class="col-12 text-danger">
                                    <span>** Complete Your KYC and Bank Account Formalities to start accepting online payments</span><br>
                                    <span>*** At least one option COD or Online must be enabled.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-white bg-secondary">
                    Customer Notification Settings
                </div>
                <div class="card-body">
                    <div class="row mt-1">
                        <div class="col-12 mt-2">
                            <div class="row">
                                <div class="col-9 col-md-6">
                                    <span class="ml-3">Email Notifications</span>
                                </div>
                                <div class="col-3">
                                    <?php
                                        if($notificationemail==1)
                                        {
                                            echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" checked id="notificationemailsetting">';
                                        }
                                        else
                                        {
                                            echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" id="notificationemailsetting">';
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-9 col-md-6">
                                    <span class="ml-3">SMS Notifications</span>
                                </div>
                                <div class="col-3">
                                    <?php
                                        if($notificationsms==1)
                                        {
                                            echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" checked id="notificationsmssetting">';
                                        }
                                        else
                                        {
                                            echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" id="notificationsmssetting">';
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-9 col-md-6">
                                    <span class="ml-3">Whatsapp Notifications</span>
                                </div>
                                <div class="col-3">
                                    <?php
                                        if($notificationwhatsapp==1)
                                        {
                                            echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" checked id="notificationwhatsappsetting">';
                                        }
                                        else
                                        {
                                            echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" id="notificationwhatsappsetting">';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-danger mt-2">
                            <span>*Enable/Disable these notifcations to send/stop order status messages to customers at diferent stages</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-white bg-secondary">
                    Logistics Integration
                </div>
                <div class="card-body">
                   <div class="row mt-1">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-2 col-md-1">
                                    <i class="fas fa-truck fa-2x"></i>
                                </div>
                                <div class="col-7 col-md-5">
                                    <span class="ml-3">Integrate logistics/shipping</span>
                                </div>
                                <div class="col-3">
                                    <?php
                                    if($kyc_completed==1 && $bank_account_verified=="Yes")
                                    {
                                        if($logisticsintegrated=="Yes")
                                        {
                                            echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" checked id="logisticsintegrated">';
                                        }
                                        else
                                        {
                                            echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" id="logisticsintegrated">';
                                        }
                                    }
                                    else
                                    {
                                        echo '<span title="Please complete your KYC & Bank Account Details to integrate with our Logistics solutions"><input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" disabled></span>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-danger mt-2">
                            <p>**Complete your KYC & Bank Account Details to integrate with our Logistics solutions</p>
                        </div>
                    </div> 
                </div>
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
        <!-- content end -->
    </div>
    <!-- page content -->
</div>
<!-- page wrapper end -->

<?php include("footer.php"); ?>

<script>
    $("#deliverychargedetails-enabler").on("click",
        function()
        {
            $("#deliverychargedetails-enabler").addClass("d-none");
            $("#charge").attr("disabled",false);
            $("#freeabove").attr("disabled",false);
            $("#deliverychargedetails-formbuttons").removeClass("d-none");
            $("#charge").focus();
        });
</script>

<script>
    $('#codpaymentsetting').change(
        function(){
        value=1;

        if($(this).prop('checked'))
            value=1;
        else
            value=0;

        var tobesend = 'codpayment='+value;

        $.ajax({
            type: 'POST',
            url: 'setSellerPaymentSettingHelper.php',
            data: tobesend,
            dataType: 'json',
            success: function(response)
            {   
                if(response.status == 1)
                {
                    $("#information-modal").modal('show');
                    $("#information").html("<p class='text-success'>COD payment setting updated successfully</p>");
                }
                else
                {
                    $("#information-modal").modal('show');
                    $("#information").html("<p class='text-danger'>Unable to update COD payment setting</p>");
                }
            }
        });
    });

    $('#onlinepaymentsetting').change(
        function(){
        value=0;

        if($(this).prop('checked'))
            value=1;
        else
            value=0;

        confirm=false;

        if(value==0)
        {
            $("#information-modal").modal('show');
            $("#information").html("<p class='text-danger'>* Buyer will not be able to pay online if this field is set to No ?</p>");
        }

        var tobesend = 'onlinepayment='+value;

        $.ajax({
            type: 'POST',
            url: 'setSellerPaymentSettingHelper.php',
            data: tobesend,
            dataType: 'json',
            success: function(response)
            {  
                if(value==1)
                { 
                    if(response.status == 1)
                    {
                        $("#information-modal").modal('show');
                        $("#information").html("<p class='text-success'>Online payment setting updated successfully</p>");
                    }
                    else
                    {
                        $("#information-modal").modal('show');
                        $("#information").html("<p class='text-danger'>Unable to update online payment setting</p>");
                    }
                }
            }
        });
    });

    $('#notificationemailsetting').change(
        function(){
        value=1;

        if($(this).prop('checked'))
            value=1;
        else
            value=0;

        var tobesend = 'email='+value;

        $.ajax({
            type: 'POST',
            url: 'setSellerNotificationSettingHelper.php',
            data: tobesend,
            dataType: 'json',
            success: function(response)
            {   
                if(response.status == 1)
                {
                    $("#information-modal").modal('show');
                    $("#information").html("<p class='text-success'>Email notification setting updated successfully</p>");
                }
                else
                {
                    $("#information-modal").modal('show');
                    $("#information").html("<p class='text-danger'>Unable to update email notification setting</p>");
                }
            }
        });
    });

    $('#notificationsmssetting').change(
        function(){
        value=0;

        if($(this).prop('checked'))
            value=1;
        else
            value=0;

        var tobesend = 'sms='+value;

        $.ajax({
            type: 'POST',
            url: 'setSellerNotificationSettingHelper.php',
            data: tobesend,
            dataType: 'json',
            success: function(response)
            {   
                if(response.status == 1)
                {
                    $("#information-modal").modal('show');
                    $("#information").html("<p class='text-success'>SMS notification setting updated successfully</p>");
                }
                else
                {
                    $("#information-modal").modal('show');
                    $("#information").html("<p class='text-danger'>Unable to update SMS notification setting</p>");
                }
            }
        });
    });

    $('#notificationwhatsappsetting').change(
        function(){
        value=0;

        if($(this).prop('checked'))
            value=1;
        else
            value=0;

        var tobesend = 'whatsapp='+value;

        $.ajax({
            type: 'POST',
            url: 'setSellerNotificationSettingHelper.php',
            data: tobesend,
            dataType: 'json',
            success: function(response)
            {   
                if(response.status == 1)
                {
                    $("#information-modal").modal('show');
                    $("#information").html("<p class='text-success'>Whatsapp notification setting updated successfully</p>");
                }
                else
                {
                    $("#information-modal").modal('show');
                    $("#information").html("<p class='text-danger'>Unable to update Whatsapp notification setting</p>");
                }
            }
        });
    });

    $('#logisticsintegrated').change(
        function(){
        value="No";

        if($(this).prop('checked'))
            value="Yes";
        else
            value="No";

        var tobesend = 'logistics='+value;

        $.ajax({
            type: 'POST',
            url: 'setSellerLogisticsIntegratedHelper.php',
            data: tobesend,
            dataType: 'json',
            success: function(response)
            {   
                if(response.status == 1)
                {
                    $("#information-modal").modal('show');
                    $("#information").html("<p class='text-success'>Logistics service updated successfully</p>");
                }
                else
                {
                    $("#information-modal").modal('show');
                    $("#information").html("<p class='text-danger'>Unable to update logistics service</p>");
                }
            }
        });
    });
</script>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

  </body>
</html>
