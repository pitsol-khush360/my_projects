<?php include("../../../resources/config.php"); ?>

<?php
//print_r($_POST);
$postdata = $_POST;

// $_POST will have all details of transaction like transaction status, payment mode (card,upi,...), other fields.

// should 'salt' key be passed with form post data or should keep in config.
$msg = '';
$txnStatus='';
$amount=0;

if (isset($postdata ['key'])) {
    $key                =   $postdata['key'];
    // 'salt' key is not coming in response.
    //$salt               =   $postdata['salt'];
    $salt               =   SALT;
    $txnid              =   $postdata['txnid'];
    $amount             =   $postdata['amount'];
    $productInfo        =   $postdata['productinfo'];
    $firstname          =   $postdata['firstname'];
    $email              =   $postdata['email'];
    $udf5               =   $postdata['udf5'];
    $mihpayid           =   $postdata['mihpayid'];
    $status             =   $postdata['status'];
    $resphash           =   $postdata['hash'];

    // custom variables
    $txnStatus          =   $postdata['txnStatus'];
    $payuMoneyId        =   $postdata['payuMoneyId'];
    $mode               =   $postdata['mode'];                  // CC, DC,....
    $phone              =   $postdata['phone'];
    //Calculate response hash to verify 
    $keyString          =   $key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||'.$udf5.'|||||';
    $keyArray           =   explode("|",$keyString);
    $reverseKeyArray    =   array_reverse($keyArray);
    $reverseKeyString   =   implode("|",$reverseKeyArray);
    $CalcHashString     =   strtolower(hash('sha512', $salt.'|'.$status.'|'.$reverseKeyString));
    
    // checking if calculated hash key is equal to original. means valid user payment request. not 3rd party interfarance.
    if ($status == 'success'  && $resphash == $CalcHashString) 
    {
        $msg = "Transaction Successful and Hash Verified...";
        // Now on successful payment we can perform other operations like storing record in db.
        if($txnStatus=="SUCCESS")
        {
            // autoincrement,userid,courseid,course_plan,txnid, paumoneid,amount,firstname,email,mobile,status,mode

            if(isset($_SESSION['payumoney_courseid']) && isset($_SESSION['payumoney_subcourseid']) && isset($_SESSION['payumoney_plan_type']))
            {
                $scid="";

                $pay_time=date("Y-m-d H:i:s");

                if(isset($_SESSION['payumoney_subcourseid']))
                    $scid=$_SESSION['payumoney_subcourseid'];

                if($scid!="")
                {
                    $query_payment=query("insert into user_payments(ulid,ccid,scid,plan_type,txnid,payumoney_id,amount,name,email,mobile,payment_mode,payment_status,payment_time) values('".$_SESSION['ulid']."','".$_SESSION['payumoney_courseid']."','".$scid."','".$_SESSION['payumoney_plan_type']."','".$txnid."','".$payuMoneyId."','".$amount."','".$firstname."','".$email."','".$phone."','".$mode."','".$txnStatus."','".$pay_time."')");
                    confirm($query_payment);
                }

                // unsetting session index after successful payment
                if(isset($_SESSION['payumoney_plan_type']))
                unset($_SESSION['payumoney_plan_type']);

                if(isset($_SESSION['payumoney_courseid']))
                    unset($_SESSION['payumoney_courseid']);

                if(isset($_SESSION['payumoney_subcourseid']))
                    unset($_SESSION['payumoney_subcourseid']);
            }
        }
        else
        {
            $txnStatus="INCOMPLETE";

            if(isset($_SESSION['payumoney_courseid']) && isset($_SESSION['payumoney_subcourseid']) && isset($_SESSION['payumoney_plan_type']))
            {
                $scid="";

                $pay_time=date("Y-m-d H:i:s");

                if(isset($_SESSION['payumoney_subcourseid']))
                    $scid=$_SESSION['payumoney_subcourseid'];

                if($scid!="")
                {
                    $query_payment=query("insert into user_payments(ulid,ccid,scid,plan_type,txnid,payumoney_id,amount,name,email,mobile,payment_mode,payment_status,payment_time) values('".$_SESSION['ulid']."','".$_SESSION['payumoney_courseid']."','".$scid."','".$_SESSION['payumoney_plan_type']."','".$txnid."','".$payuMoneyId."','".$amount."','".$firstname."','".$email."','".$phone."','".$mode."','".$txnStatus."','".$pay_time."')");
                    confirm($query_payment);
                }

                // unsetting session index after successful payment
                if(isset($_SESSION['payumoney_plan_type']))
                unset($_SESSION['payumoney_plan_type']);

                if(isset($_SESSION['payumoney_courseid']))
                    unset($_SESSION['payumoney_courseid']);

                if(isset($_SESSION['payumoney_subcourseid']))
                    unset($_SESSION['payumoney_subcourseid']);
            }
        }
    }
    else 
    {
        //tampered or failed
        $msg = "Payment failed for Hash not verified...";
    } 
}
else
{
    redirect("../index.php?home");
    exit(0);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo APP; ?> - Your Payment Status</title>
<!-- Bootstrap Core CSS -->
<link href="../css/bootstrap.min.css" rel='stylesheet' type='text/css' />
</head>
<style type="text/css">
    .main {
        margin-left:30px;
        font-family:Verdana, Geneva, sans-serif, serif;
    }
    .text {
        float:left;
        width:180px;
    }
    .dv {
        margin-bottom:5px;
    }
</style>
<body>
<div class="main container">
    <div class="row" style="margin-top:5em;">
        <div class="col-md-4"></div>
        <div class="col-12 col-md-5">
            <div>
                <img src="images/payumoney.png" />
            </div>
            
            <!-- <div class="dv">
            <span class="text"><label>Merchant Key:</label></span>
            <span><?php //echo $key; ?></span>
            </div>
            
            <div class="dv">
            <span class="text"><label>Merchant Salt:</label></span>
            <span><?php //echo $salt; ?></span>
            </div> -->
            
            <div class="dv">
            <span class="text"><label>Transaction/Order ID:</label></span>
            <span><?php echo $txnid; ?></span>
            </div>
            
            <div class="dv">
            <span class="text"><label>Amount:</label></span>
            <span><?php echo $amount; ?></span>    
            </div>
            
            <div class="dv">
            <span class="text"><label>Product Info:</label></span>
            <span><?php echo $productInfo; ?></span>
            </div>
            
            <div class="dv">
            <span class="text"><label>First Name:</label></span>
            <span><?php echo $firstname; ?></span>
            </div>
            
            <div class="dv">
            <span class="text"><label>Email ID:</label></span>
            <span><?php echo $email; ?></span>
            </div>
            
            <!-- <div class="dv">
            <span class="text"><label>Mihpayid:</label></span>
            <span><?php //echo $mihpayid; ?></span>
            </div> -->
            
            <!-- <div class="dv">
            <span class="text"><label>Hash:</label></span>
            <span><?php //echo $resphash; ?></span>
            </div> -->
            
            <div class="dv">
            <span class="text"><label>Transaction Status:</label></span>
            <span><?php echo $status; ?></span>
            </div>
            
            <!-- <div class="dv">
            <span class="text"><label>Message:</label></span>
            <span><?php //echo $msg; ?></span>
            </div> -->

            <div class="dv text-info">
                <p style="margin-top:2em;">* Please Keep this <b>Transaction/Order ID</b> for your reference. Contact <b>"Admin"</b> and share this <b>Transaction/Order ID</b> with admin to know your Transaction detail. (Only contact admin if you have any doubt regarding your payment)
                </p>
                <p style="margin-top:2em;">* If the payment is pending then it may take some time to reflect. If money is dedcuted from your account, then contact <b>"Admin"</b> for more details.
                </p>
            </div>

            <div class="dv text-center">
                <a href="../index.php?home" class="btn btn-primary">Back To User Panel</a>
            </div>
        </div>
    </div>
</div>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

</body>
</html>
    