<?php include("navigation.php"); ?>

<?php
  if(!(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2))
    redirect("login.php");
?>

<?php
$showinformation=0;
$message='';
if(isset($_POST['submitdefaultsetting']))
{
    $data['user_id']=$_SESSION['user_id'];
    $data['product_category']=escape_string(trim($_POST['productcategory']));
    $data['discount_type']=$_POST['discounttype'];
    $data['tax_type']=$_POST['taxtype'];
    $data['warrant_type']=$_POST['warrant_type'];

    if(isset($_POST['discountpercent']))
        $data['discount_percent']=$_POST['discountpercent'];

    if(isset($_POST['taxpercent']))
        $data['tax_percent']=$_POST['taxpercent'];

    if(isset($_POST['warrant_duration']))
        $data['warranty_duration']=$_POST['warrant_duration'];

    if(isset($_POST['warranty_days_mon_yr']))
        $data['warranty_days_mon_yr']=$_POST['warranty_days_mon_yr'];

    $url=DOMAIN.'/rest/seller/updateSellerProductDefaultSettingsRest.php';
    $output=getRestApiResponse($url,$data);
    
    if(isset($output['updateproductdefault']) && $output['updateproductdefault']['response_code']==200)
    {
        $showinformation=1;
        $message='<p class="text-success">Product default settings updated successfully</p>';
    }
    else
    {
        $showinformation=1;
        $message='<p class="text-danger">Unable to update product default settings</p>';
    }
}
?>

<?php
    if($showinformation==1)
        echo '<script>
                $("#information").html(\''.$message.'\');
                $("#information-modal").modal("show");
            </script>';
?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row mt-2">
        <div class="col-12 text-center">
              <h4>Product Default Settings<h4>
        </div>
        <div class="col-12">
            <div class="card">
              <div class="card-header">
                This screen is designed to capture default values which are same for all products.Values are assigned when product is first created. These settings can be overridden at each individual product level also.
              </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <?php
                $data['user_id']=$_SESSION['user_id'];
                $url=DOMAIN.'/rest/seller/getSellerProductDefaultSettingsRest.php';
                $output=getRestApiResponse($url,$data);
                
                $productcategory="";
                $discounttype="";
                $discountpercent=0;
                $taxtype="";
                $taxpercent=0;
                // $freeshipping=0;
                // $returnavailable=0;
                // $cashondelivery=1;
                $warranttype="";
                $warrantduration=0;
                $warrantydaysmonyr="";

                if(isset($output['getproductdefault']) && count($output['getproductdefault'])>2)
                {
                    $productcategory=$output['getproductdefault'][0]['product_category'];
                    $discounttype=$output['getproductdefault'][0]['discount_type'];
                    $discountpercent=$output['getproductdefault'][0]['discount_percent'];
                    $taxtype=$output['getproductdefault'][0]['tax_type'];
                    $taxpercent=$output['getproductdefault'][0]['tax_percent'];
                    // $freeshipping=$output['getproductdefault'][0]['free_shipping'];
                    // $returnavailable=$output['getproductdefault'][0]['return_available'];
                    // $cashondelivery=$output['getproductdefault'][0]['cash_on_delivery'];
                    $warranttype=$output['getproductdefault'][0]['warrant_type'];
                    $warrantduration=$output['getproductdefault'][0]['warrant_duration'];
                    $warrantydaysmonyr=$output['getproductdefault'][0]['warranty_days_mon_yr'];
                }
            ?>
            <form action="" method="post">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group row">
                            <label for="productcategory" class="col-5 col-form-label"><b>Product Category:</b></label>
                            <div class="col-7">
                                <input type="text" name="productcategory" id="productcategory" class="form-control border border-top-0 border-left-0 border-right-0" value="<?php echo $productcategory; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="form-group row">
                        <label for="discounttype" class="col-4 col-form-label"><b>Discount Type:</b></label>
                        <div class="col-4">
                                <select name="discounttype" id="discounttype" class="form-control border border-left-0 border-top-0 border-right-0">
                                    <?php
                                        if($discounttype=="" || $discounttype=="None")
                                            echo '<option value="None">None</option><option value="Percentage">Percentage</option><option value="Flat">Flat</option>'; 
                                        else
                                        if($discounttype=="Percentage")
                                            echo '<option value="Percentage">Percentage</option><option value="Flat">Flat</option><option value="None">None</option>';
                                        else
                                            echo '<option value="Flat">Flat</option><option value="Percentage">Percentage</option><option value="None">None</option>';
                                    ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-8 col-md-6">
                                        <?php
                                            if($discounttype=="Percentage")
                                            {
                                                echo '<input type="number" name="discountpercent" id="discountpercent" class="form-control border border-left-0 border-top-0 border-right-0 text-right" value="'.$discountpercent.'" min="1" max="100">';
                                            }
                                            else
                                            if($discounttype=="Flat")
                                            {
                                                echo '<input type="number" name="discountpercent" id="discountpercent" class="form-control border border-left-0 border-top-0 border-right-0 text-right" value="'.$discountpercent.'" min="1">';
                                            }
                                            else
                                            if($discounttype=="None" || $discounttype=="")
                                            {
                                                echo '<input type="number" name="discountpercent" id="discountpercent" class="form-control border border-left-0 border-top-0 border-right-0 d-none text-right" value="'.$discountpercent.'" min="1" max="100" disabled>';
                                            }
                                        ?>
                                    </div>
                                    <div class="col-4 col-md-6">
                                        <?php
                                            if($discounttype=="Percentage")
                                                echo '<span class="fa-lg text-secondary" id="percentsymbol">&#37;</span>';
                                            else
                                            if($discounttype=="Flat" || $discounttype=="None" || $discounttype=="")
                                                echo '<span class="fa-lg text-secondary d-none" id="percentsymbol">&#37;</span>';
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="form-group row">
                        <label for="taxtype" class="col-4 col-md-4 col-form-label"><b>Tax Type:</b></label>
                        <div class="col-4">
                        <select name="taxtype" id="taxtype" class="form-control border border-left-0 border-right-0 border-top-0">
                            <?php
                                if($taxtype=="" || $taxtype=="None")
                                    echo '<option value="None">None</option><option value="Percentage">Percentage</option><option value="GST">GST</option>'; 
                                else
                                if($taxtype=="Percentage")
                                    echo '<option value="Percentage">Percentage</option><option value="GST">GST</option><option value="None">None</option>';
                                else
                                    echo '<option value="GST">GST</option><option value="Percentage">Percentage</option><option value="None">None</option>';
                            ?>
                        </select>
                        </div>
                        <div class="col-4">
                            <div class="row">
                                <div class="col-8 col-md-6">
                                    <?php
                                        if($taxtype=="Percentage" || $taxtype=="GST")
                                        {
                                            echo '<input type="number" name="taxpercent" id="taxpercent" class="form-control border border-left-0 border-top-0 border-right-0 text-right" value="'.$taxpercent.'" min="1" max="100">';
                                        }
                                        else
                                        if($taxtype=="None" || $taxtype=="")
                                        {
                                            echo '<input type="number" name="taxpercent" id="taxpercent" class="form-control border border-left-0 border-top-0 border-right-0 d-none text-right" value="'.$taxpercent.'" min="1" max="100" disabled>';
                                        }
                                    ?>
                                </div>
                                <div class="col-4 col-md-6">
                                    <?php
                                        if($taxtype=="Percentage" || $taxtype=="GST")
                                            echo '<span class="fa-lg text-secondary" id="percentsymbol1">&#37;</span>';
                                        else
                                        if($taxtype=="None" || $taxtype=="")
                                            echo '<span class="fa-lg text-secondary d-none" id="percentsymbol1">&#37;</span>';
                                    ?>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <hr>
                <!-- <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group row">
                            <label for="free_shipping" class="col-6 col-form-label"><b>Free Shipping:</b></label>
                            <div class="col-6">
                                <?php 
                                    /*if($freeshipping==1)
                                    {
                                        echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" checked id="get_free_shipping">
                                            <input type="hidden" name="free_shipping" id="set_free_shipping" value="1">';
                                    }
                                    else
                                    {
                                        echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" id="get_free_shipping">
                                            <input type="hidden" name="free_shipping" id="set_free_shipping" value="0">';
                                    }*/
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group row">
                            <label for="return_available" class="col-6 col-form-label"><b>Return Available:</b></label>
                            <div class="col-6">
                                <?php 
                                    /*if($returnavailable==1)
                                    {
                                        echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" checked id="get_return_available">
                                            <input type="hidden" name="return_available" id="set_return_available" value="1">';
                                    }
                                    else
                                    {
                                        echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" id="get_return_available">
                                            <input type="hidden" name="return_available" id="set_return_available" value="0">';
                                    }*/
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group row">
                            <label for="cash_on_delivery" class="col-6 col-form-label"><b>Cash On Delivery:</b></label>
                            <div class="col-6">
                                <?php 
                                    /*if($cashondelivery==1)
                                    {
                                        echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" checked id="get_cash_on_delivery">
                                            <input type="hidden" name="cash_on_delivery" id="set_cash_on_delivery" value="1">';
                                    }
                                    else
                                    {
                                        echo '<input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" id="get_cash_on_delivery">
                                            <input type="hidden" name="cash_on_delivery" id="set_cash_on_delivery" value="0">';
                                    }*/
                                ?>
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="row">
                    <div class="col-12 col-md-5">
                        <div class="form-group row">
                            <label for="warrant_type" class="col-6 col-md-5 col-form-label"><b>Warranty Type:</b></label>
                            <div class="col-6">
                                <select name="warrant_type" id="warrant_type" class="form-control border border-left-0 border-right-0 border-top-0">
                                    <?php
                                        if($warranttype=="" || $warranttype=="Not Applicable")
                                            echo '<option value="Not Applicable">Not Applicable</option><option value="Warranty">Warranty</option><option value="Gaurantee">Gaurantee</option>'; 
                                        else
                                        if($warranttype=="Warranty")
                                            echo '<option value="Warranty">Warranty</option><option value="Gaurantee">Gaurantee</option><option value="Not Applicable">Not Applicable</option>';
                                        else
                                            echo '<option value="Gaurantee">Gaurantee</option><option value="Warranty">Warranty</option><option value="Not Applicable">Not Applicable</option>';
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-7">
                        <div class="form-group row">
                            <?php
                                if($warranttype=="Warranty" || $warranttype=="Gaurantee")
                                {
                            ?>
                            <div class="col-12">
                                <div class="form-group row">
                                        <?php 
                                            if($warranttype=="Warranty")
                                                echo "<label for=\"warrant_duration\" class=\"col-5 col-md-4 col-form-label text-md-right\" id=\"warrant_duration_label\"><b>Warranty Duration:</b></label>";
                                            else
                                            if($warranttype=="Gaurantee")
                                                echo "<label for=\"warrant_duration\" class=\"col-5 col-md-4 col-form-label text-md-right\" id=\"warrant_duration_label\"><b>Gaurantee Duration:</b></label>";
                                            else
                                                echo "<label for=\"warrant_duration\" class=\"col-5 col-md-4 col-form-label text-md-right d-none\" id=\"warrant_duration_label\"><b>Warranty Duration:</b></label>";
                                        ?>
                                    <div class="col-3 col-md-4">
                                        <input type="number" name="warrant_duration" id="warrant_duration" class="form-control border border-left-0 border-top-0 border-right-0" value="<?php echo $warrantduration; ?>" min="1">
                                    </div>
                                    <div class="col-4">
                                        <select name="warranty_days_mon_yr" class="form-control border border-left-0 border-top-0 border-right-0" id="warranty_days_mon_yr">
                                            <?php
                                                if($warrantydaysmonyr=="" || $warrantydaysmonyr=="Days")
                                                    echo '<option value="Days">Days</option><option value="Months">Months</option><option value="Years">Years</option>'; 
                                                else
                                                if($warrantydaysmonyr=="Months")
                                                    echo '<option value="Months">Months</option><option value="Days">Days</option><option value="Years">Years</option>';
                                                else
                                                    echo '<option value="Years">Years</option><option value="Months">Months</option><option value="Days">Days</option>';
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                                else if($warranttype=="Not Applicable" || $warranttype=="")
                                {
                            ?>
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="warrant_duration" class="col-5 col-md-4 col-form-label text-md-right d-none" id="warrant_duration_label"><b>Warranty Duration:</b></label>
                                        <div class="col-3 col-md-4">
                                        <input type="number" name="warrant_duration" id="warrant_duration" class="form-control border border-left-0 border-top-0 border-right-0 d-none" value="<?php echo $warrantduration; ?>" min="1" disabled>
                                        </div>
                                        <div class="col-4">
                                            <select name="warranty_days_mon_yr" class="form-control border border-left-0 border-top-0 border-right-0 d-none" id="warranty_days_mon_yr" min="1" disabled>
                                                <?php
                                                    if($warrantydaysmonyr=="" || $warrantydaysmonyr=="Days")
                                                        echo '<option value="Days">Days</option><option value="Months">Months</option><option value="Years">Years</option>'; 
                                                    else
                                                    if($warrantydaysmonyr=="Months")
                                                        echo '<option value="Months">Months</option><option value="Days">Days</option><option value="Years">Years</option>';
                                                    else
                                                        echo '<option value="Years">Years</option><option value="Months">Months</option><option value="Days">Days</option>';
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="offset-md-3 col-6 col-md-3 text-right">
                        <a href="displaySellerDashboard.php" class="btn btn-danger w-100">Cancel</a>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="form-group">
                          <input type="submit" name="submitdefaultsetting" class="btn btn-success btn-md w-100" value="Save Settings">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


        </div>
        <!-- content end -->
    </div>
    <!-- page content -->
</div>
<!-- page wrapper end -->

<?php include("footer.php"); ?>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

<script>
    // $('#get_free_shipping').change(function(){
    //   if($(this).prop('checked'))
    //   {
    //    $('#set_free_shipping').val(1);
    //   }
    //   else
    //   {
    //    $('#set_free_shipping').val(0);
    //   }
    //  });

    // $('#get_return_available').change(function(){
    //   if($(this).prop('checked'))
    //   {
    //    $('#set_return_available').val(1);
    //   }
    //   else
    //   {
    //    $('#set_return_available').val(0);
    //   }
    //  });

    // $('#get_cash_on_delivery').change(function(){
    //   if($(this).prop('checked'))
    //   {
    //    $('#set_cash_on_delivery').val(1);
    //   }
    //   else
    //   {
    //    $('#set_cash_on_delivery').val(0);
    //   }
    //  });
</script>

<script>
    $("#discounttype").change(function()
    {
        type=$(this).val();
        if(type=="None")
        {
            if(!$("#discountpercent").hasClass("d-none"))
            {
                $("#discountpercent").addClass("d-none");
                $("#discountpercent").attr("disabled",true);
            }

            if(!$("#percentsymbol").hasClass("d-none"))
                $("#percentsymbol").addClass("d-none");
        }
        else
        if(type=="Flat")
        {
            if($("#discountpercent").hasClass("d-none"))
            {
                $("#discountpercent").removeClass("d-none");
                $("#discountpercent").attr("disabled",false);
                $("#discountpercent").attr("max",false);
            }

            if(!$("#percentsymbol").hasClass("d-none"))
                $("#percentsymbol").addClass("d-none");
        }
        else
        if(type=="Percentage")
        {
            if($("#discountpercent").hasClass("d-none"))
            {
                $("#discountpercent").removeClass("d-none");
                $("#discountpercent").attr("disabled",false);
                $("#discountpercent").attr("max",true);
            }

            if($("#percentsymbol").hasClass("d-none"))
                $("#percentsymbol").removeClass("d-none");
        }
    });

    $("#taxtype").change(function()
    {
        type=$(this).val();
        if(type=="None")
        {
            if(!$("#taxpercent").hasClass("d-none"))
            {
                $("#taxpercent").attr("disabled",true);
                $("#taxpercent").addClass("d-none");
            }

            if(!$("#percentsymbol1").hasClass("d-none"))
                $("#percentsymbol1").addClass("d-none");
        }
        else
        if(type=="GST")
        {
            if($("#taxpercent").hasClass("d-none"))
            {
                $("#taxpercent").removeClass("d-none");
                $("#taxpercent").attr("disabled",false);
            }

            if($("#percentsymbol1").hasClass("d-none"))
                $("#percentsymbol1").removeClass("d-none");
        }
        else
        if(type=="Percentage")
        {
            if($("#taxpercent").hasClass("d-none"))
            {
                $("#taxpercent").removeClass("d-none");
                $("#taxpercent").attr("disabled",false);
            }

            if($("#percentsymbol1").hasClass("d-none"))
                $("#percentsymbol1").removeClass("d-none");
        }
    });

    $("#warrant_type").change(function()
    {
        type=$(this).val();
        if(type=="Not Applicable")
        {
            if(!$("#warrant_duration_label").hasClass("d-none"))
                $("#warrant_duration_label").addClass("d-none");

            if(!$("#warrant_duration").hasClass("d-none"))
            {
                $("#warrant_duration").attr("disabled",true);
                $("#warrant_duration").addClass("d-none");
            }

            if(!$("#warranty_days_mon_yr").hasClass("d-none"))
            {
                $("#warranty_days_mon_yr").attr("disabled",true);
                $("#warranty_days_mon_yr").addClass("d-none");
            }
        }
        else
        if(type=="Warranty")
        {
            if($("#warrant_duration_label").hasClass("d-none"))
                $("#warrant_duration_label").removeClass("d-none");

            $("#warrant_duration_label").html("<b>Warranty Duration:</b>");

            if($("#warrant_duration").hasClass("d-none"))
            {
                $("#warrant_duration").removeClass("d-none");
                $("#warrant_duration").attr("disabled",false);
            }

            if($("#warranty_days_mon_yr").hasClass("d-none"))
            {
                $("#warranty_days_mon_yr").removeClass("d-none");
                $("#warranty_days_mon_yr").attr("disabled",false);
            }
        }
        else
        if(type=="Gaurantee")
        {
            if($("#warrant_duration_label").hasClass("d-none"))
                $("#warrant_duration_label").removeClass("d-none");

            $("#warrant_duration_label").html("<b>Gaurantee Duration:</b>");
            
            if($("#warrant_duration").hasClass("d-none"))
            {
                $("#warrant_duration").removeClass("d-none");
                $("#warrant_duration").attr("disabled",false);
            }

            if($("#warranty_days_mon_yr").hasClass("d-none"))
            {
                $("#warranty_days_mon_yr").removeClass("d-none");
                $("#warranty_days_mon_yr").attr("disabled",false);
            }
        }
    });
</script>

  </body>
</html>
