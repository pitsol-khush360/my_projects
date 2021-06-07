<?php 
    if(!isset($_SESSION['admin_username']) && !isset($_SESSION['admin_role'])) 
    {
        echo '<script>alert("You can not access this without Login")</script>';
        redirect("admin_login.php");
        exit();
    }
?>
<!DOCTYPE HTML>
<html>
<head>
<title>AbhyasClasses-Admin Panel</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="keywords" content="Modern Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>

<!-- Bootstrap Core CSS -->
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<!-- Graph CSS -->
<link href="css/lines.css" rel='stylesheet' type='text/css' />
<link href="css/font-awesome.css" rel="stylesheet"> 

<!-- jQuery -->
<script src="js/jquery.min.js"></script>
<!-- js libraries for datetimepicker() -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

<!----webfonts--->
<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900' rel='stylesheet' type='text/css'>
<!---//webfonts--->  
<!-- Nav CSS -->
<link href="css/custom.css" rel="stylesheet">
<!-- Metis Menu Plugin JavaScript -->
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
<!-- Graph JavaScript -->
<script src="js/d3.v3.js"></script>
<script src="js/rickshaw.js"></script>

<style>
.logoutbutton
{
    color:white;
}

#nav-logo-content
{
    margin-bottom:10px;
}

.brandtext
{
    color:white;
    font-size:23px;
    margin-left:15px;
}

#nav-logo
{
    width:45px;
    height:45px;
}
</style> 

</head>
<body>
<div id="wrapper">
     <!-- Navigation -->
        <nav class="top1 navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom:0;">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <a class="navbar-brand" href="index.php">
                    <div id="nav-logo-content">
                        <img src="../../resources/app_images/logo.jpg" id="nav-logo"><span class="ml-2 brandtext">AbhyasClasses</span>
                    </div>
                </a>
            </div>

            <!-- /.navbar-header -->
            <ul class="nav navbar-nav navbar-right">
			    <li class="dropdown">
	        		<a href="admin_logout.php"><span class="logoutbutton">Logout</span>&nbsp;<i  class="fa fa-sign-out" title="logout"></i></a>
	      		</li>
			</ul>
            <?php if (isset($_GET['users'])) {?>
			<form class="navbar-form navbar-right" method="post"  style="color: white">

            <span>Search User Here</span>
              <input type="text" class="form-control bg-white" name="search" placeholder="Email, Name or Mobile">
              <input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
              <input type="submit" class="form-control" placeholder="search">
            </form>
           <?php } ?>
            
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw nav_icon"></i>Home</a>
                        </li>
                        <li>
                            <a href="admin_manage_admin.php"><i class="fa fa-user nav_icon"></i>Admin</a>
                        </li>
                        <li>
                            <a href="admin_category.php"><i class="fa fa-list nav_icon"></i>Course Category</a>
                        </li>
                        <li>
                            <a href="admin_question_bank.php"><i class="fa fa-list-ul nav_icon"></i>Question Bank</a>
                        </li>
                        <li>
                            <a href="admin_exam.php"><i class="fa fa-book nav_icon"></i>Exam</a>
                        </li>
                        <li>
                            <a href="admin_exam_syllabus.php"><i class="fa fa-book nav_icon"></i>Exam Syllabus</a>
                        </li>
                        <li>
                            <a href="admin_result_exam.php"><i class="fa fa-list-alt nav_icon"></i>Result</a>
                        </li>
                        <li>
                            <a href="admin_users.php?users"><i class="fa fa-user nav_icon"></i>Users Details</a>
                        </li>
                        <li>
                            <a href="admin_users_transactions.php"><i class="fa fa-rupee nav_icon"></i>Users Transactions</a>
                        </li>
                        <li>
                            <a href="admin_subcourse_plans.php"><i class="fa fa-list nav_icon"></i>Course Plans</a>
                        </li>
                        <li>
                            <a href="admin_offer.php"><i class="fa fa-gift nav_icon"></i>Offers</a>
                        </li>
                        <li>
                            <a href="admin_user_query.php"><i class="fa fa-angle-double-down nav_icon"></i>User Queries</a>
                        </li>
                        <li>
                            <a href="admin_user_comment.php"><i class="fa fa-angle-double-down nav_icon"></i>User Comments</a>
                        </li>
                        <li>
                            <a href="admin_faqs.php"><i class="fa fa-question nav_icon"></i>FAQs</a>
                        </li>
                        <hr style="color: gray; width: 90%;">
                        <li>
                            <a href="admin_logout.php"><i class="fa fa-sign-out nav_icon"></i> logout</a>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
        <div id="page-wrapper">