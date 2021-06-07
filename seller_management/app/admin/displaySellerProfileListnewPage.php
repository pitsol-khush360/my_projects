<?php
require_once("../../config/config.php");
require_once("../../config/" . ENV . "_config.php");
?>

<?php
if (!(isset($_SESSION['current_user'])))
    redirect('login.php');
?>
<?php include("navigation.php"); ?>
<?php

$access = premissionScreen('SELLER_PROFILE', $_SESSION['current_user']);

$global = $access['global'];
$input = $access['input'];
$button = $access['button'];



if ($global != 0) {
?>

    <?php
    $showinformation = 0;
    $message = "";

    ?>
    <?php
    if (isset($_POST['sellerIDnewpage'])) {
        $_SESSION['sellerIDnewpage'] = $_POST['sellerIDnewpage'];
    }
    ?>
    <!Doctype html>
    <html>

    <head>
        <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
        <style>
            .form-control {
                height: 30px;
                border-radius: 0px;
            }

            .form-control :focus {
                background-color: #00000000;
            }

            .modal-body {
                overflow-x: auto;

            }

            input {
                cursor: default !important;
            }

            .increase {
                max-width: 90% !important;
                max-height: 90% !important;
            }
        </style>
    </head>

    <body>
        <input type="hidden" value="<?php echo DOMAIN; ?>" id="getDOMAIN">
        <hr>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-5 mr-3 pt-2" style="border: 1px solid black;">
                    <form action="displaySellerProfileListnewPage.php" method="POST">
                        <h5>Seller - Basic Details</h5>
                        <div class="form-group row ">
                            <label class="col-sm-6 col-form-label">Seller ID</label>
                            <div class="col-sm-6">
                                <input type="text" id="sellerID" readonly class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label">Login Mobile</label>
                            <div class="col-sm-6">
                                <input type="text" id="mobile" readonly class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label">Username</label>
                            <div class="col-sm-6">
                                <input type="text" id="username" readonly class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label">Business Name</label>
                            <div class="col-sm-6">
                                <input type="text" id="businessname" readonly class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label">Email:</label>
                            <div class="col-sm-6">
                                <input type="text" id="email" readonly class="form-control">
                            </div>
                        </div>



                    </form>
                </div>
                <div class="col-6 pt-2" style="border: 1px solid black;">
                    <form action="displaySellerProfileListnewPage.php" method="POST">
                        <h5>Seller - Address Details</h5>
                        <div class="form-group row ">
                            <label class="col-sm-6 col-form-label">Address Line 1</label>
                            <div class="col-sm-6">
                                <input type="text" id="addressline1" readonly class="form-control">
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label class="col-sm-6 col-form-label">Address Line 2</label>
                            <div class="col-sm-6">
                                <input type="text" id="addressline2" readonly class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group row ">
                                    <label class="col-sm-6 col-form-label">Country</label>
                                    <div class="col-sm-6">
                                        <input type="text" id="country" readonly class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row ">
                                    <label class="col-sm-6 col-form-label">City</label>
                                    <div class="col-sm-6">
                                        <input type="text" id="city" readonly class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group row ">
                                    <label class="col-sm-6 col-form-label">State</label>
                                    <div class="col-sm-6">
                                        <input type="text" id="state" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-sm-6 col-form-label">PIN:</label>
                                    <div class="col-sm-6">
                                        <input type="text" id="pin" readonly class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>




                    </form>
                </div>
            </div>
        </div>

        <hr>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-6 mr-3 pt-2" style="border: 1px solid black;">
                    <form action="displaySellerProfileListnewPage.php" method="POST">
                        <h5>PAN Card Details & KYC Verification</h5>

                        <div class="form-group row ">
                            <label class="col-sm-4 col-form-label">KYC Completed</label>
                            <div class="col-sm-8 text-right" id="kycyesflag" style="cursor:default;">
                                <input type="button" disabled value="Yes" class="btn btn-success">

                            </div>
                            <div class="col-sm-8 text-right" id="kycnoflag" style="cursor:default;">
                                <input type="button" disabled value="No" class='btn btn-warning'>

                            </div>
                        </div>

                        <div class="form-group row ">
                            <label class="col-sm-4 col-form-label">Name (As in PAN)</label>
                            <div class="col-sm-8">
                                <input type="text" id="nameaspan" readonly class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">PAN Number</label>
                            <div class="col-sm-8">
                                <input type="text" id="pannumber" readonly class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">PAN Card Proof</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-8">
                                        <input type="text" id="pancard" readonly class="form-control">
                                    </div>
                                    <div class="col">
                                        <input type="button" data-toggle="modal" data-target="#panproofModal" value="View" class="btn btn-secondary w-100">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Address Proof</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-8">
                                        <input type="text" id="addressproof" readonly class="form-control">
                                    </div>
                                    <div class="col">
                                        <input type="button" data-toggle="modal" data-target="#addressproofModal" value="View" class="btn btn-secondary w-100">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">KYC Verified</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <?php
                                    if ($button != 'disabled') {
                                    ?>
                                        <div class="col-6">
                                            <input type="button" id="papbtn" data-toggle="modal" data-target="#kycapproveModal" value="Approve" class="btn btn-primary w-100 ">
                                        </div>
                                        <div class="col">
                                            <input type="button" data-toggle="modal" data-target="#kycrejectModal" value="Reject" class="btn btn-danger w-100   ">
                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="col-6">
                                            <input type="button" disabled value="Approve" class="btn btn-secondary w-100 ">
                                        </div>
                                        <div class="col">
                                            <input type="button" disabled value="Reject" class="btn btn-secondary w-100   ">
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <p id="msgforno" class="text-danger"></p>
                        </div>



                    </form>
                </div>
                <div class="col-5 pt-2" style="border: 1px solid black;">
                    <form action="displaySellerProfileListnewPage.php" method="POST">
                        <h5>GST Details</h5>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">GST Verification</label>
                            <div class="col-sm-8 text-right">
                                <div class="col-sm-8 text-right" id="gstyesflag">
                                    <input type="button" disabled value="Yes" class="btn btn-success">

                                </div>
                                <div class="col-sm-8 text-right" id="gstnoflag">
                                    <input type="button" disabled value="No" class='btn btn-warning'>

                                </div>

                            </div>
                        </div>

                        <div class="form-group row ">
                            <label class="col-sm-4 col-form-label">GSTIN</label>
                            <div class="col-sm-8">
                                <input type="text" readonly id="gstin" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label class="col-sm-4 col-form-label">GST Certificate</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-8">
                                        <input type="text" readonly id="gstcertificate" class="form-control">
                                    </div>
                                    <div class="col">
                                        <input type="button" data-toggle="modal" data-target="#gstcertificateModal" value="View" class="btn btn-secondary w-100">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row" style="height: 50px;">
                            <label class="col-sm-4 col-form-label">GST Verified</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <?php
                                    if ($button != 'disabled') {
                                    ?>
                                        <div class="col-6">
                                            <input type="button" id="gapbtn" data-toggle="modal" data-target="#gstapproveModal" value="Approve" class="btn btn-primary w-100">
                                        </div>
                                        <div class="col">
                                            <input type="button" data-toggle="modal" data-target="#gstrejectModal" value="Reject" class="btn btn-danger w-100">
                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="col-6">
                                            <input type="button" disabled value="Approve" class="btn btn-secondary w-100">
                                        </div>
                                        <div class="col">
                                            <input type="button" disabled value="Reject" class="btn btn-secondary w-100">
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <hr>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-11 pt-2" style="border: 1px solid black;">
                    <form action="displaySellerProfileListnewPage.php" method="POST">
                        <h5>Bank Account Details</h5>

                        <div class="form-group row w-50">
                            <label class="col-sm-6 col-form-label">Bank Account Verification</label>
                            <div class="col-sm-6 text-right">
                                <div class="col-sm-8 text-right" id="bankyesflag">
                                    <input type="button" disabled value="Yes" class="btn btn-success">

                                </div>
                                <div class="col-sm-8 text-right" id="banknoflag">
                                    <input type="button" disabled value="No" class='btn btn-warning'>

                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group row ">
                                    <label class="col-sm-4 col-form-label">Beneficiary Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly id="beneficiaryname" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">IFSC Code</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly id="ifsccode" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group row ">
                                    <label class="col-sm-4 col-form-label">Account Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly id="accountnumber" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row ">
                                    <label class="col-sm-4 col-form-label">Cancelled Cheque</label>
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-8">
                                                <input type="text" readonly id="Noledcheque" class="form-control">
                                            </div>
                                            <div class="col">
                                                <input type="button" data-toggle="modal" data-target="#NoledchequeModal" value="View" class="btn btn-secondary w-100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row w-50">
                            <label class="col-sm-4 col-form-label">Beneficiary ID</label>
                            <div class="col-sm-8 text-right">
                                <input type="text" id="beneficiaryid" readonly class="form-control">

                            </div>
                        </div>



                        <div class="form-group row w-50">
                            <label class="col-sm-4 col-form-label">Bank A/C Verification</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <?php
                                    if ($button != 'disabled') {

                                    ?>
                                        <div class="col-6">
                                            <input type="button" id="bapbtn" data-toggle="modal" data-target="#bankapproveModal" value="Approve" class="btn btn-primary w-100">
                                        </div>
                                        <div class="col">
                                            <input type="button" data-toggle="modal" data-target="#bankrejectModal" value="Reject" class="btn btn-danger w-100">
                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="col-6">
                                            <input type="button" disabled value="Approve" class="btn btn-primary w-100">
                                        </div>
                                        <div class="col">
                                            <input type="button" disabled value="Reject" class="btn btn-danger w-100">
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>



                    </form>
                </div>

            </div>
        </div>
        <hr>
        <div class="text-center">
            <a href="displaySellerProfileList.php"><button class="btn btn-success"><i class="fa fa-arrow-left" style="color:red" aria-hidden="true"></i>&nbsp;&nbsp;Go Back</button></a>
        </div>

        <!--Confirm Modals-->

        <!--Kyc Approve Modal-->
        <div class="modal" id="kycapproveModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
            <div class="modal-dialog modal-dialog-centered " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title"><i class="fa fa-check-circle" style="color:green" aria-hidden="true"></i>&nbsp;&nbsp;<strong>Confirm
                            </strong></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size : 25px">&times;</span>
                        </button>

                    </div>

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-success w-50  kycapprove" value='Yes'>
                        <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
        <!--End of Kyc Approve Modal-->

        <!--GST Approve Modal-->
        <div class="modal" id="gstapproveModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
            <div class="modal-dialog modal-dialog-centered " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title"><i class="fa fa-check-circle" style="color:green" aria-hidden="true"></i>&nbsp;&nbsp;<strong>Confirm
                            </strong></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size : 25px">&times;</span>
                        </button>

                    </div>

                    <div class="modal-footer">
                        <input type="submit" id="YES" onclick="gstApproveRejectFun(this.id, 'approve')" class="btn btn-success w-50" value='Yes'>
                        <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
        <!--End of GST Approve Modal-->

        <!--Bank Approve Modal-->
        <div class="modal" id="bankapproveModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
            <div class="modal-dialog modal-dialog-centered " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title"><i class="fa fa-check-circle" style="color:green" aria-hidden="true"></i>&nbsp;&nbsp;<strong>Confirm
                            </strong></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size : 25px">&times;</span>
                        </button>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" id="YES" onclick="bankdetailsApproveRejectFun(this.id, 'approve')" class="btn btn-success w-50">Yes</button>
                        <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
        <!--End of Bank Approve Modal-->



        <!--Kyc Reject Modal-->
        <div class="modal" id="kycrejectModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
            <div class="modal-dialog modal-dialog-centered " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title"><i class="fa fa-check-circle" style="color:green" aria-hidden="true"></i>&nbsp;&nbsp;<strong>Confirm
                            </strong></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size : 25px">&times;</span>
                        </button>

                    </div>

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-success w-50  kycreject" value='Yes'>
                        <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
        <!--End of Kyc Reject Modal-->

        <!--GST Reject Modal-->
        <div class="modal" id="gstrejectModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
            <div class="modal-dialog modal-dialog-centered " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title"><i class="fa fa-check-circle" style="color:green" aria-hidden="true"></i>&nbsp;&nbsp;<strong>Confirm
                            </strong></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size : 25px">&times;</span>
                        </button>

                    </div>

                    <div class="modal-footer">
                        <input type="submit" id="NO" onclick="gstApproveRejectFun(this.id, 'reject')" class="btn btn-success w-50" value='Yes'>
                        <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
        <!--End of GST Reject Modal-->

        <!--Bank Reject Modal-->
        <div class="modal" id="bankrejectModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
            <div class="modal-dialog modal-dialog-centered " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title"><i class="fa fa-check-circle" style="color:green" aria-hidden="true"></i>&nbsp;&nbsp;<strong>Confirm
                            </strong></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size : 25px">&times;</span>
                        </button>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" id="NO" onclick="bankdetailsApproveRejectFun(this.id, 'reject')" class="btn btn-success w-50">Yes</button>
                        <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
        <!--End of Bank Reject Modal-->


        <!-- View Pan Card Proof Modal-->
        <div class="modal fade " id="panproofModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color : rgba(0, 0, 0, 0.555);">
            <div class="modal-dialog modal-dialog-centered modal-lg increase" role="document" style="background-color : rgba(0, 0, 0, 0.555);">
                <div class="modal-content" style="background-color : #ffffff00">
                    <div class="modal-header">

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color: white;font-size : 25px">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color : #ffffff00">
                        <img id="pancardimage" class="mx-auto d-block" />
                    </div>
                    <div class="modal-footer" style="margin: auto;" style="background-color : rgba(0, 0, 0, 0.555);">
                        <button class="btn btn-danger" data-dismiss="modal">Go Back</button>
                    </div>
                </div>
            </div>
        </div>
        <!--End of View Pan Card Proof Modal-->

        <!-- View Address Proof Modal-->
        <div class="modal fade" id="addressproofModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color : rgba(0, 0, 0, 0.555);">
            <div class="modal-dialog modal-dialog-centered modal-lg increase" role="document" style="background-color : rgba(0, 0, 0, 0.555);">
                <div class="modal-content" style="background-color : #ffffff00">
                    <div class="modal-header">

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color: white;font-size : 25px">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color : #ffffff00">
                        <img id="addressimage" class="mx-auto d-block" />
                    </div>
                    <div class="modal-footer" style="margin: auto;" style="background-color : rgba(0, 0, 0, 0.555);">
                        <button class="btn btn-danger" data-dismiss="modal">Go Back</button>
                    </div>
                </div>
            </div>
        </div>
        <!--End of View Address Proof Modal-->

        <!-- View GST Certificate Modal-->
        <div class="modal fade" id="gstcertificateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color : rgba(0, 0, 0, 0.555);">
            <div class="modal-dialog modal-dialog-centered modal-lg increase" role="document" style="background-color : rgba(0, 0, 0, 0.555);">
                <div class="modal-content" style="background-color : #ffffff00">
                    <div class="modal-header">

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color: white;font-size : 25px">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color : #ffffff00">
                        <img id="gstcertificateimage" class="mx-auto d-block" />
                    </div>
                    <div class="modal-footer" style="margin: auto;" style="background-color : rgba(0, 0, 0, 0.555);">
                        <button class="btn btn-danger" data-dismiss="modal">Go Back</button>
                    </div>
                </div>
            </div>
        </div>
        <!--End of View GST Certificate Modal-->

        <!-- View Noled Cheque Modal-->
        <div class="modal fade" id="NoledchequeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color : rgba(0, 0, 0, 0.555);">
            <div class="modal-dialog modal-dialog-centered modal-lg increase" role="document" style="background-color : rgba(0, 0, 0, 0.555);">
                <div class="modal-content" style="background-color : #ffffff00">
                    <div class="modal-header">

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color: white;font-size : 25px">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color : #ffffff00">
                        <img id="Noledchequeimage" class="mx-auto d-block" />
                    </div>
                    <div class="modal-footer" style="margin: auto;" style="background-color : rgba(0, 0, 0, 0.555);">
                        <button class="btn btn-danger" data-dismiss="modal">Go Back</button>
                    </div>
                </div>
            </div>
        </div>
        <!--End of View Noled Cheque Modal-->

        <script>
            $(document).ready(() => {

                var domain = $("#getDOMAIN").val();
                $.post(domain + '/rest/admin/GetSellerDetailsAdminRest.php?seller_id=' + "<?php echo $_SESSION['sellerIDnewpage']; ?>" + '&key=<?php echo md5(VALIDATION_KEY);?>',
                    function(data, status) {

                        //Seller - Basic Details    
                        $("#sellerID").val(data["getreviewdetails"]["seller_id"]);
                        $("#mobile").val(data["getreviewdetails"]["seller_alternate_number"]);
                        $("#username").val(data["getreviewdetails"]["username"]);
                        $("#businessname").val(data["getreviewdetails"]["seller_business_name"]);
                        $("#email").val(data["getreviewdetails"]["seller_email"]);


                        //Seller - Address Details
                        $("#addressline1").val(data["getreviewdetails"]["seller_address1"]);
                        $("#addressline2").val(data["getreviewdetails"]["seller_address2"]);
                        $("#country").val(data["getreviewdetails"]["seller_country"]);
                        $("#state").val(data["getreviewdetails"]["seller_state"]);
                        $("#city").val(data["getreviewdetails"]["seller_city"]);
                        $("#pin").val(data["getreviewdetails"]["seller_pin"]);


                        //PAN Card Details & KYC Verifcation
                        $("#nameaspan").val(data["getreviewdetails"]["seller_panname"]);
                        $("#pannumber").val(data["getreviewdetails"]["seller_pannum"]);
                        $("#pancard").val(data["getreviewdetails"]["pan_card_image"]);
                        $("#pancardimage").attr("src", "<?php echo DOMAIN; ?>" + data["getreviewdetails"]["pan_card_image"]);
                        $("#addressimage").attr("src", "<?php echo DOMAIN; ?>" + data["getreviewdetails"]["address_proof_image"]);
                        $("#addressproof").val(data["getreviewdetails"]["address_proof_image"]);

                        if (data["getreviewdetails"]["kyc_application_status"] == "Submitted") {
                            if (data["getreviewdetails"]["gst_verified"] == "No" || data["getreviewdetails"]["bank_account_verified"] == "No") {
                                $("#msgforno").text("*GST and Bank Account is not verified.");
                                $("#papbtn").prop("disabled", true);
                                $("#papbtn").removeClass("btn-primary");
                                $("#papbtn").addClass("btn-secondary");
                            } else {
                                $("#msgforno").text("");
                                $("#papbtn").prop("disabled", false);
                                $("#papbtn").removeClass("btn-secondary");
                                $("#papbtn").addClass("btn-primary");
                            }


                        } else {
                            $("#papbtn").prop("disabled", true);
                            $("#papbtn").removeClass("btn-primary");
                            $("#papbtn").addClass("btn-secondary");
                            if (data["getreviewdetails"]["gst_verified"] == "No" || data["getreviewdetails"]["bank_account_verified"] == "No") {
                                $("#msgforno").text("*GST and Bank Account is not verified.")
                            } else {
                                $("#msgforno").text("");
                            }
                        }


                        if (data["getreviewdetails"]["kyc_completed"] == "1") {
                            document.getElementById('kycyesflag').style.display = "";
                            document.getElementById('kycnoflag').style.display = "none";
                        } else {
                            document.getElementById('kycyesflag').style.display = "none";
                            document.getElementById('kycnoflag').style.display = "";
                        }

                        if (data["getreviewdetails"]["gst_verified"] == "No") {
                            document.getElementById('gstyesflag').style.display = "none";
                            document.getElementById('gstnoflag').style.display = "";
                            $("#gapbtn").prop("disabled", false);
                            $("#gapbtn").removeClass("btn-secondary");
                            $("#gapbtn").addClass("btn-primary");
                        } else {
                            document.getElementById('gstyesflag').style.display = "";
                            document.getElementById('gstnoflag').style.display = "none";
                            $("#gapbtn").prop("disabled", true);
                            $("#gapbtn").removeClass("btn-primary");
                            $("#gapbtn").addClass("btn-secondary");
                        }

                        if (data["getreviewdetails"]["bank_account_verified"] == "No") {
                            document.getElementById('bankyesflag').style.display = "none";
                            document.getElementById('banknoflag').style.display = "";
                            $("#bapbtn").prop("disabled", false);
                            $("#bapbtn").removeClass("btn-secondary");
                            $("#bapbtn").addClass("btn-primary");
                        } else {
                            document.getElementById('bankyesflag').style.display = "";
                            document.getElementById('banknoflag').style.display = "none";
                            $("#bapbtn").prop("disabled", true);
                            $("#bapbtn").removeClass("btn-primary");
                            $("#bapbtn").addClass("btn-secondary");
                        }

                        //GST Details
                        $("#gstin").val(data["getreviewdetails"]["seller_gst"]);
                        $("#gstcertificate").val(data["getreviewdetails"]["gst_certificate_image"]);
                        $("#gstcertificateimage").attr("src", "<?php echo DOMAIN; ?>" + data["getreviewdetails"]["gst_certificate_image"]);


                        //Bank Account Details
                        console.log(data["getreviewdetails"]["beneficiary_name"]);
                        $("#beneficiaryname").val(data["getreviewdetails"]["beneficiary_name"]);
                        $("#accountnumber").val(data["getreviewdetails"]["account_number"]);
                        $("#ifsccode").val(data["getreviewdetails"]["ifsc_code"]);
                        $("#Noledcheque").val(data["getreviewdetails"]["cheque_image"]);
                        $("#Noledchequeimage").attr("src", "<?php echo DOMAIN; ?>" + data["getreviewdetails"]["gst_certificate_image"]);
                        $("#beneficiaryid").val(data["getreviewdetails"]["beneficiary_id"]);





                    });

            });


            $(".kycapprove").click(() => {
                var domain = $("#getDOMAIN").val();
                $.post(domain + '/rest/admin/ApproveSellerKYCRest.php?seller_id=' + "<?php echo $_SESSION['sellerIDnewpage']; ?>" + "&confirm=YES" + '&key=<?php echo md5(VALIDATION_KEY);?>',
                    function(data, status) {
                        $("#kycapproveModal").click();
                        if (data['approveKYC']['response_code'] == 200) {
                            $("#response").modal("show");
                            $("#restext").text("Kyc Approved Successfully");
                            $("#resdesc").text("");
                        } else {
                            $("#response").modal("show");
                            $("#restext").text("Operation Unsuccessful");
                            $("#resdesc").text("ERROR : " + data['approveKYC']['response_desc']);
                        }
                    });
                //location.reload(true);
            });
            $(".kycreject").click(() => {
                var domain = $("#getDOMAIN").val();
                $.post(domain + '/rest/admin/ApproveSellerKYCRest.php?seller_id=' + "<?php echo $_SESSION['sellerIDnewpage']; ?>" + "&confirm=NO" + '&key=<?php echo md5(VALIDATION_KEY);?>',
                    function(data, status) {
                        $("#kycrejectModal").click();
                        if (data['approveKYC']['response_code'] == 200) {
                            $("#response").modal("show");
                            $("#restext").text("Kyc Rejected Successfully");
                            $("#resdesc").text("");
                        } else {
                            $("#response").modal("show");
                            $("#restext").text("Operation Unsuccessful");
                            $("#resdesc").text("ERROR : " + data['approveKYC']['response_desc']);
                        }
                    });
                //location.reload(true);
            });

            function gstApproveRejectFun(flag, wm) {
                var domain = $("#getDOMAIN").val();

                $.post(domain + '/rest/admin/ApproveSellerGSTRest.php?seller_id=' + "<?php echo $_SESSION['sellerIDnewpage']; ?>" + "&confirm=" + flag + '&key=<?php echo md5(VALIDATION_KEY);?>',
                    function(data, status) {
                        $("#gst" + wm + "Modal").click();
                        if (data['approveGST']['response_code'] == 200) {
                            $("#response").modal("show");
                            $("#restext").text("Operation Successful");
                            $("#resdesc").text("");
                        } else {
                            $("#response").modal("show");
                            $("#restext").text("Operation Unsuccessful");
                            $("#resdesc").text("ERROR : " + data['approveGST']['response_desc']);
                        }
                    });
                //location.reload(true);
            }

            function bankdetailsApproveRejectFun(flag, wm) {
                var domain = $("#getDOMAIN").val();

                $.post(domain + '/rest/admin/ApproveSellerBankAccountRest.php?seller_id=' + "<?php echo $_SESSION['sellerIDnewpage']; ?>" + "&confirm=" + flag + '&key=<?php echo md5(VALIDATION_KEY);?>',
                    function(data, status) {

                        $("#bank" + wm + "Modal").click();
                        if (data['approveBamkAccount']['response_code'] == 200) {
                            $("#response").modal("show");
                            $("#restext").text("Operation Successful");
                            $("#resdesc").text("");
                        } else {
                            $("#response").modal("show");
                            $("#restext").text("Operation Unsuccessful");
                            $("#resdesc").text("ERROR : " + data['approveBankAccount']['response_desc']);
                        }
                    });
                //location.reload(true);
            }
            $(document).ready(() => {
                if (window.history.replaceState) {
                    window.history.replaceState(null, null, window.location.href);
                }
            });
        </script>





    </body>

    </html>
<?php
} else {
    echo '<h2 class="pt-5" style="text-align:center;color:red;margin-top:4rem;">Permission Denied!</h2>';
}
?>
<?php include("footer.php"); ?>