<?php include("validateUserMultipleLogin.php"); ?>

<?php

if(!isset($_SESSION['username']) && !isset($_SESSION['ulid']) && !isset($_SESSION['user_token']))
{
    redirect("..".DS."signin.php");
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>AbhyasClasses-User Panel</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } 
    </script>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
    <!-- Custom CSS -->
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <!-- Graph CSS -->
    <!-- <link href="css/lines.css" rel='stylesheet' type='text/css' /> -->
    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="css/aboutus.css" rel="stylesheet"> <!-- manually created aboutus.css -->


    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"> <!-- online fontawesome cdn -->

    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    <!----webfonts--->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900' rel='stylesheet' type='text/css'>
    <!---//webfonts--->  
    <!-- Nav CSS -->
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/mystyles.css?<?php echo time(); ?>" rel="stylesheet">
    <link href="css/faqs.css" rel="stylesheet"> <!-- manually created aboutus.css -->
    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/metisMenu.min.js"></script>
    <script src="js/custom.js"></script>
    <!-- Graph JavaScript -->
    <script src="js/d3.v3.js"></script>
    <script src="js/rickshaw.js"></script>

    <?php
    // unsetting pdf data if recent exam is given and pdf data is not cleared;
    if(isset($_SESSION['pdf_data']))
        unset($_SESSION['pdf_data']);

    if(isset($_POST['exam_choice_selected']))
    	exam_set_preference();

	if(isset($_SESSION['username']))
	{
		$query_user=query("select * from user_login where username like '".$_SESSION['username']."'");
		confirm($query_user);
		$row_user=fetch_array($query_user);

        if(!isset($_SESSION['ulid']))
            $_SESSION['ulid']=$row_user['userid'];

		$qup=query("select * from user_personal where ulid='".$row_user['userid']."'");
		confirm($qup);
		$rup=fetch_array($qup);

		if($rup['profile_picture']!="")
		{
			$img_path=image_path_profile($rup['profile_picture']);
		}
		else
			$img_path=image_path_profile("defaultpic.jpg");
		
		$query=query("select * from user_test_preference where ulid='".$_SESSION['ulid']."'");
		confirm($query);
		$row=fetch_array($query);

		if(mysqli_num_rows($query)==0)
		{
?>
<script type="text/javascript">
	$(document).ready(function(){
        $('#choiceModal').modal('show');
	});
</script>
<?php
		}
	}
?>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
/*.mySlides {display:none;}*/
        .brandtext
        {
            color:white;
            font-size:20px;
            margin-left:15px;
        }

        #nav-logo
        {
            width:40px;
            height:40px;
        }
</style>
</head>

<body style="background-color:black;">
<!-- Choice Preference Modal -->
<div id="choiceModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Choose Your Preference</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 text-center text-danger">
                        <p style="color:red;">This popup will appear every time untill you do not set your preference. So please set your preference. You can also update your preference in "Your Preference" Section</p>
                    </div>
                </div>
                <hr>
            	<?php preference_selection(); ?>
            </div>
        </div>
    </div>
</div>

<div id="wrapper">
     <!-- Navigation -->
     <?php include(TEMPLATE_USERS.DS."top_nav.php"); ?>

    <div id="page-wrapper" style="margin-top:40px;">
        <?php
            if($_SERVER['REQUEST_URI']=="/exam_portal/public/users/index.php" || $_SERVER['REQUEST_URI']=="/exam_portal/public/users/" || isset($_GET['home']))
                include(TEMPLATE_USERS.DS."user_content.php");   
            else
            if(isset($_GET['faq']))
            	include(TEMPLATE_USERS.DS."faq.php"); 
            else
            if(isset($_GET['profile']))
            	include("user_profile.php"); 
            else
                if(isset($_GET['show_practice']))
                    include(TEMPLATE_USERS.DS."show_practice.php");
            else
                if(isset($_GET['show_exams']))
                    include(TEMPLATE_USERS.DS."show_exams.php");
            else
                if(isset($_GET['aboutus']))
                    include(TEMPLATE_USERS.DS."aboutus.php");
            else
                if(isset($_GET['test_preference']))
                    include(TEMPLATE_USERS.DS."test_preference.php");
            else
                if(isset($_GET['add_usercomment']))
                    include(TEMPLATE_USERS.DS."add_usercomment.php");
            else
                if(isset($_GET['usercomments']))
                    include(TEMPLATE_USERS.DS."user_comments.php");
            else
                if(isset($_GET['exam_syllabus']))
                    include(TEMPLATE_USERS.DS."exam_syllabus.php");
            else
                include(TEMPLATE_USERS.DS."user_content.php");
        ?>
        <?php
    include(TEMPLATE_USERS.DS."footer.php");
?>
    </div> <!-- page-wrapper closed -->
</div>
    <!-- /#wrapper -->
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
