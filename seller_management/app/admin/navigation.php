 
<?php
require_once("../../config/config.php");
require_once("../../config/" . ENV . "_config.php");
include("header.php");
?>

<!-- Begin page -->
<div id="wrapper">
    <!-- Top Bar Start -->
    <div class="topbar">
        <!-- LOGO -->
        <div class="topbar-left">
            <a href="index.html" class="logo"><span>NP<span>Admin</span></span><i class="mdi mdi-layers"></i></a>
            <!-- Image logo -->
            <!--<a href="index.html" class="logo">-->
            <!--<span>-->
            <!--<img src="assets/images/logo.png" alt="" height="30">-->
            <!--</span>-->
            <!--<i>-->
            <!--<img src="assets/images/logo_sm.png" alt="" height="28">-->
            <!--</i>-->
            <!--</a> -->
        </div>

         <!-- Button mobile view to collapse sidebar menu -->
         <div class="topbar">
             <!-- LOGO -->
             <div class="topbar-left">
                 <a href="displaySellerDashboard.php" class="logo"><span><?php echo APP; ?></span><i class="fas fa-home"></i></a>
                 <!-- Image logo -->
                 <!--<a href="index.html" class="logo">-->
                 <!--<span>-->
                 <!--<img src="assets/images/logo.png" alt="" height="30">-->
                 <!--</span>-->
                 <!--<i>-->
                 <!--<img src="assets/images/logo_sm.png" alt="" height="28">-->
                 <!--</i>-->
                 <!--</a>-->
             </div>

             <!-- show/hide leftside menu -->

             <div class="navbar navbar-default" style="background-color: #dedede;" role="navigation">
                 <!-- Navbar-left -->
                 <ul class="navbar-nav mr-auto">
                     <li class="nav-item">
                         <button class="button-menu-mobile open-left waves-effect">
                             <i class="fas fa-bars"></i>
                         </button>
                     </li>
                 </ul>

                 <!-- Right Side Of Upper Nav -->
                 <nav class="navbar navbar-expand ">
                 
                 <ul class="navbar-nav ml-auto">
                     <li >
                         <a class="nav-item mr-3 d-none d-md-block text-dark pt-2">Admin Id - <?php if (isset($_SESSION['current_user']))
                                                            echo $_SESSION['current_user']; ?>
                         </a>
                     </li>
                     <li >
                         <a class="nav-item mr-3 d-none d-md-block btn btn-outline-danger btn border-danger" href="displayAdminChangePassword.php"> Change Password</a>
                     </li>
                      
                     <!-- <li class="nav-item">
                            <a class="nav-link">Seller Id - <?php //if(isset($_SESSION['user_id']))
                                                            //echo $_SESSION['user_id']; 
                                                            ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link">Mobile - <?php //if(isset($_SESSION['mobile']))
                                                            //echo $_SESSION['mobile']; 
                                                            ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link">Hi,&nbsp;<?php //if(isset($_SESSION['business_name']))
                                                            //echo $_SESSION['business_name']; 
                                                            ?>
                            </a>
                        </li> -->
                     <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle waves-effect user-link" href="#" id="rightNavOptions" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php
                                        if (isset($_SESSION['seller_image']))
                                            echo SELLER_TO_ROOT . $_SESSION['seller_image']; ?>" width="30" height="30" class="rounded-circle" style="border-radius:40%;">
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="rightNavOptions">
                            <div class="row text-center">
                                <div class="col-12">
                                    <img src="<?php if (isset($_SESSION['seller_image']))
                                                    echo SELLER_TO_ROOT . $_SESSION['seller_image']; ?>" width="120" height="120" class="rounded-circle" style="border-radius:40%;">
                                </div>
                                <div class="col-12 pt-0">
                                    <button type="button" class="btn bg-transparent updateprofile">
                                        <i class="fas fa-camera fa-lg"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="displaySellerProfile.php"><span class="fas fa-user fa-fw mr-2"></span>Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="displaySellerChangePassword.php"><span class="fas fa-key fa-fw mr-2"></span>Change Password</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php"><span class="fas fa-sign-out-alt fa-fw mr-2"></span>Logout</a>
                        </div>
                    </li> -->

                 </ul> <!-- end navbar-right -->
                 </nav>
             </div><!-- end navbar -->
         </div>
     </div>
     <!-- Top Bar End -->


     <!-- ========== Left Sidebar Start ========== -->
     <div class="left side-menu">
         <div class="sidebar-inner slimscrollleft">

             <!--- Sidemenu -->
             <div id="sidebar-menu">
                 <ul>
                     <li>
                         <a href="displayAdminDashboard.php" class="waves-effect"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                         </a>
                     </li>
                     <li class="has_sub">
                         <a href="javascript:void(0);" class="waves-effect"><i class="fas fa-user"></i> <span>User Management</span><span class="menu-arrow"></span></a>
                         <ul class="list-unstyled">
                             <li><a href="displayAdminUserList.php">Admin User</a></li>
                             <li><a href="displayAdminScreenPermissionList.php">Screen Permissions</a></li>
                         </ul>
                     </li>

                     <li class="has_sub">
                         <a href="javascript:void(0);" class="waves-effect"><i class="fas fa-list"></i> <span>Product Management</span><span class="menu-arrow"></span></a>
                         <ul class="list-unstyled">
                             <li><a href="displayAdminCollectionList.php">Collections</a></li>
                             <li><a href="displayAdminCollectionLibraryList.php">Collection Library</a></li>
                             <li><a href="displayAdminProductList.php">Products</a></li>
                             <li><a href="displayAdminProductDefaultSettingsList.php">Product Default Settings</a></li>
                         </ul>
                     </li>
                     <li class="has_sub">
                         <a href="javascript:void(0);" class="waves-effect"><i class="fas fa-list"></i> <span>Order Management</span><span class="menu-arrow"></span></a>
                         <ul class="list-unstyled">
                             <li><a href="displayAdminBasketOrdersList.php">Basket Orders</a></li>
                             <li><a href="displayAdminCashMovementsList.php">Cash Movements</a></li>
                         </ul>
                     </li>

                     <li class="has_sub">
                         <a href="javascript:void(0);" class="waves-effect"><i class="fas fa-calendar"></i><span>Seller Management</span><span class="menu-arrow"></span>
                         </a>
                         <ul class="list-unstyled">
                             <li><a href="displaySellerLoginList.php">Seller Login List</a></li>
                             <li>
                                 <a href="displaySellerProfileList.php">Seller Profile Management</a>
                             </li>
                             <li>
                                 <a href="displayAdminSellerReviewList.php">Seller Reviews</a>
                             </li>
                             <li>
                                 <a href="displayAdminShippingChargesList.php">Shipping Charges</a>
                             </li>
                             <li>
                                 <a href="displayAdminPromoCodesList.php">Promo Codes</a>
                             </li>
                             <li>
                                 <a href="displayAdminTicketList.php">Tickets</a>
                             </li>

                         </ul>
                     </li>
                     <li class="has_sub">
                         <a href="javascript:void(0);" class="waves-effect"><i class="fas fa-wallet"></i><span>Wallet</span><span class="menu-arrow"></span>
                         </a>
                         <ul class="list-unstyled">
                             <li><a href="displayAdminWalletBalanceList.php">Wallet Balance </a></li>
                             <li><a href="displayAdminWalletOrderList.php">Wallet Orders </a></li>




                         </ul>
                     </li>
                     <li class="has_sub">
                         <a href="javascript:void(0);" class="waves-effect"><i class="fas fa-wallet"></i><span>Advanced Settings</span><span class="menu-arrow"></span>
                         </a>
                         <ul class="list-unstyled">

                             <li><a href="displayAdminCommissionChargesList.php">Commission Charges</a></li>
                         </ul>
                     </li>

                     <li class="has_sub">
                         <a href="logout.php" class="waves-effect"><i class="fas fa-sign-out-alt"></i><span>Logout</span>
                         </a>
                     </li>
                 </ul>
             </div>
             <!-- Sidebar -->
             <div class="clearfix"></div>

             <div class="help-box">
                 <h5 class="text-muted m-t-0">For Help ?</h5>
                 <p class=""><span class="text-custom">Email:</span> <br /> uatcode@gmail.com</p>
             </div>

         </div>
         <!-- Sidebar -left -->
     </div> <!-- Left Sidebar End -->

     <!-- Profile Pic Change popup -->
     <div class="modal fade" id="updateprofilemodal" tabindex="-1" role="dialog" aria-labelledby="updateprofileforuser" aria-hidden="true">
         <div class="modal-dialog modal-sm w-75 mx-auto" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="updateprofileforuser">Update Profile Pic</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
                 <div class="modal-body">
                     <form enctype="multipart/form-data" action="" method="post" id="updateprofileform">
                         <div class="row">
                             <div class="col-12">
                                 <input type="file" accept="image/*" name="seller_image" id="seller_image" class="form-control border border-top-0 border-left-0 border-right-0" title="Update Profile Pic" style="overflow: hidden;">
                                 <input type="hidden" name="hidden_seller_image" value="<?php if (isset($_SESSION['seller_image'])) echo $_SESSION['seller_image'];
                                                                                        else echo "/images/sellers/defaultpic.jpg"; ?>">
                             </div>
                             <div class="col-12 text-center mt-3">
                                 <div class="form-group">
                                     <input type="submit" name="update" class="btn btn-primary btn-md" id="imageuploadbutton" value="Upload" disabled>
                                 </div>
                             </div>
                         </div>
                     </form>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                 </div>
             </div>
         </div>
     </div>

     <!-- Popup for information -->
     <div class="modal fade" id="information-modal" tabindex="-1" role="dialog" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <i class="fas fa-bell fa-2x text-warning"></i>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
                 <div class="modal-body" id="information">

                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="<?php ?>">OK</button>
                 </div>
             </div>
         </div>
     </div>



     <!-- Popup for information -->
     <div class="modal fade" id="response" tabindex="-1" role="dialog" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <i class="fas fa-bell fa-2x text-warning"></i>
                     &nbsp;&nbsp;<h4 id="restext"></h4>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
                 <div class="modal-body">
                     <p id="resdesc"></p>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-danger" data-dismiss="modal">OK</button>
                 </div>
             </div>
         </div>
     </div>



     <script>
         $('#information-modal').on('hidden.bs.modal', function() {


             location.reload(true);
         });
         $('#response').on('hidden.bs.modal', function() {



             location.reload(true);
         });
         $(".updateprofile").on("click",
             function() {
                 $("#updateprofileform")[0].reset();
                 $("#imageuploadbutton").attr("disabled", true);
                 $("#updateprofilemodal").modal('show');
             });

         $("#seller_image").change(
             function() {
                 filename = $("#seller_image").val();

                 if (filename)
                     $("#imageuploadbutton").attr("disabled", false);
                 else
                     $("#imageuploadbutton").attr("disabled", true);
             });

         $("#updateprofileform").on("submit",
             function(event) {
                 event.preventDefault();
                 $.ajax({
                     type: 'POST',
                     url: 'editProfilePic.php',
                     data: new FormData(this),
                     dataType: 'json',
                     contentType: false,
                     cache: false,
                     processData: false,
                     success: function(response) {
                         if (response.status == 1) {
                             $('#updateprofileform')[0].reset();
                             $("#updateprofilemodal").modal('hide');
                             window.location.reload();
                         } else
                         if (response.status == 0) {
                             $("#updateprofilemodal").modal('hide');
                             $("#information").html("<p class='text-danger'>Unable to update profile</p>");
                             $("#information-modal").modal("show");
                         }
                     }
                 });
             });
     </script>

     <!-- Original Page Content -->
     <div class="content-page">
         <!-- Start content -->
         <div class="content">

             <!-- close 3 divs on all pages after your page container. for content, content-page, page-wrapper -->