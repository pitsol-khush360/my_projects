<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo APP." - ".$title; ?></title>
	
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- google login api header -->
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="327869480464-0bdmvds7uln87qktpmiafu28nmmd07iu.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <!-- google login api header ends -->
  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- aboutus.php page css -->
    <link rel="stylesheet" href="css/aboutus.css">
  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- <link rel="stylesheet" href="css/styles.css"> -->
    <style type="text/css">
        <?php include("css/styles.php"); ?>
    </style>

    <!-- custom css -->
    <!-- <link rel="stylesheet" href="css/mystyles.css"> -->
    <!--<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>-->

    <style type="text/css">
        @import "https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css";

/*        .body_image
        {
            background:url("images/black_theme.png");
            background-position:center;
            background-repeat:no-repeat; /*if image is small then it will repeate in bg so we setted no-repeat 
            background-size:cover;  covers the whole body
        }*/

        .parallax_section1
        {
            /* The image used */
            background-image: url("images/parallax4.jpg");

            /* Set a specific height */
            height: 350px;

            /* Creating the parallax scrolling effect */
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .parallax_section2
        {
            /* The image used */
            background-image:url("images/parallax2.jpg");

            /* Set a specific height */
            height: 400px;

            /* Creating the parallax scrolling effect */
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .parallax_section3
        {
            /* The image used */
            background-image: url("images/black_theme.png");

            /* Set a specific height */
            height: 150px;

            /* Creating the parallax scrolling effect */
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
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

    <script type="text/javascript">
        function add_visitor()
        {
            $.get("add_visitor.php?increment",function(data,status){});
        }
    </script>
</head>

<body class="body_image" onload="add_visitor()">
<!-- navbar -->
<nav class="navbar navbar-expand-md navbar-dark fixed-top" style="background-color:#282828;">

    <div class="navbar-header">
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-links-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="index.php">
            <div>
                <img src="../resources/app_images/logo.jpg" id="nav-logo"><span class="ml-2 brandtext"><?php echo APP; ?></span>
            </div>
        </a>
    </div>

    <div class="collapse navbar-collapse" id="navbar-links-collapse">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item" style="margin-top:6px;"><a href="index.php" class="nav-link">Home</a></li>
            <li class="nav-item-dropdown" style="margin-top:6px;">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="modal" data-target="#cources">Cources</a>
            </li>
            <?php 
                if(isset($_SESSION['username']) && isset($_SESSION['ulid'])) 
                {
            ?>
                <li class="nav-item" style="margin-top:6px;"><a href="users<?php echo DS; ?>index.php?practice" class="nav-link">Practice</a></li>
            <?php 
                } 
                else
                {
            ?>
                <li class="nav-item" style="margin-top:6px;"><a href="signin.php" class="nav-link">Practice</a></li>
            <?php
                }
            ?>
            <?php
                if(isset($_SESSION['username']))
                {
                    $username=$_SESSION['username'];
                    $query=query("select * from user_login where username like '".$username."'");
                    confirm($query);
                    $row=fetch_array($query);
                    $ulid=$row['userid'];
            ?>
            <li class="nav-item" style="margin-top:6px;"><a class="nav-link" href="users/index.php?home">Users</a></li>
            <?php
                }
                else
                {
            ?>
            <li class="nav-item" style="margin-top:6px;"><a class="nav-link" href="signin.php">Users</a></li>
            <?php } ?>
            <li class="nav-item"><a href="signin.php" class="nav-link"><button class="btn" style="background:none;color:white;border:1px solid <?php echo $_SESSION['theme_color']; ?>;border-radius:30px;font-size:15px;margin-top:2px;">Login</button></a>
            </li>
            <li class="nav-item"><a href="signup.php" class="nav-link"><button class="btn btn-sm" style="background:none;color:white;border:1px solid <?php echo $_SESSION['theme_color']; ?>;border-radius:30px;font-size:15px;margin-top:3px;">Register</button></a>
            </li>
        </ul>
    </div>
</nav>

<div class="modal fade" id="cources" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Our Courses</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <?php
            $qc=query("select * from course_category");
            confirm($qc);

            $const=get_defined_constants(); // because we have to use DS in heredoc. {DS} will not work.
            // now $const is treated as array and we will access $const['DS'];

            if(mysqli_num_rows($qc)!=0)
            {
                echo '<div class="card">';

                while($rc=fetch_array($qc))
                {
                    $qsc=query("select * from sub_category where ccid='".$rc['ccid']."'");
                    confirm($qsc);

                    if(isset($_SESSION['username']) && isset($_SESSION['ulid']))
                        echo "<a href='users{$const['DS']}index.php?test_preference' style='text-decoration:none;color:black'>";
                    else
                        echo "<a href='signin.php' style='text-decoration:none;color:black'>";

                    echo "
                        <div class='card-header'><b>{$rc['category_name']}</b></div>
                        <div class='card-body'>
                            <div class='row'>";

                    while($rsc=fetch_array($qsc))
                    {
                        $list=<<< list
                        <div class='col-6 col-md-3 mt-4'>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <i class="fa fa-star text-secondary"></i>
                                </div>
                                <div class="col-12 text-center">
                                    {$rsc['sub_category_name']}
                                </div>
                            </div>
                        </div>
list;
                        echo $list;
                    }

                    echo '
                        </div>
                        </div></a>';
                }

                echo '</div>';
            }
            else
                echo '<h5 class="text-danger text-center mt-3 mb-3">Courses Not Available</h5>';
        ?>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
