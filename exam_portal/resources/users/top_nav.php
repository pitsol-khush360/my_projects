        <nav class="top1 navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom:0;background-color:#282828;">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="../index.php">
                    <div>
                        <img src="../../resources/app_images/logo.jpg" id="nav-logo"><span class="ml-2 brandtext"><?php echo APP; ?></span>
                    </div>
                </a>
            </div>
            <!-- /.navbar-header -->
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="../../resources/<?php echo $img_path; ?>" style="width:3rem;height:3rem;border-radius:50%;margin-bottom:5px;">
                    </a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-menu-header text-center">
                            <strong>
                            	<?php
                            		if(isset($_SESSION['username']))
                            			echo "Welcome ".$_SESSION['username'];
                            	?>
                            </strong>
                        </li>
                        <li class="m_2"><a href="index.php?profile"><i class="fa fa-user"></i> Profile</a></li>
                        <li class="m_2"><a href="..<?php echo DS; ?>forgotpassword.php"><i class="fa fa-key"></i>Change Password</a></li>
                        <li class="m_2"><a href="logout.php"><i class="fa fa-lock"></i> Logout</a></li>  
                    </ul>
                </li>
            </ul>
            <!-- <form class="navbar-form navbar-right">
              <input type="text" class="form-control" value="Search..." onfocus="this.value = '';" onblur="if(this.value ==''){this.value='Search...';}" style="border:1px solid <?php //echo $_SESSION['theme_color']; ?> !important;">
            </form> -->
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="index.php?home"><i class="fa fa-dashboard fa-fw nav_icon" style="font-size:20px;"></i>Home</a>
                        </li>
                        <li>
                            <a href="index.php?profile"><i class="fa fa-user fa-fw nav_icon" style="font-size:20px;"></i>Profile</a>
                        </li>
                        <li>
                            <a href="index.php?test_preference"><i class="fa fa-list-alt fa-fw nav_icon" style="font-size:20px;"></i>Your Preferences</a>
                        </li>
                        <!-- <li>
                            <a href="#"><i class="fa fa-file-o fa-fw nav_icon" style="font-size:20px;"></i>Test Series<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="graphs.html">Link1</a>
                                </li>
                                <li>
                                    <a href="typography.html">Link2</a>
                                </li>
                            </ul>
                            /.nav-second-level 
                        </li> -->
                        <li>
                            <a href="choose_course_pay.php?select_payment_type"><i class="fa fa-rupee fa-fw nav_icon" style="font-size:20px;"></i>Buy Courses</a> 
                        </li>
                        <li>
                            <a href="index.php?show_exams"><i class="fa fa-book fa-fw nav_icon" style="font-size:20px;"></i>Exams</a> 
                        </li>
                        <li>
                            <a href="index.php?exam_syllabus"><i class="fa fa-book fa-fw nav_icon" style="font-size:20px;"></i>Exam Syllabus</a> 
                        </li>
                        <li>
                            <a href="index.php?show_practice"><i class="fa fa-table fa-fw nav_icon" style="font-size:20px;"></i>Practice
                            </a>
                        </li>
                        <li>
                            <a href="index.php?faq"><i class="fa fa-question fa-fw nav_icon" style="font-size:20px;"></i>FAQ's</a>
                        </li>
                        <li>
                            <a href="index.php?usercomments"><i class="fa fa-comments fa-fw nav_icon" style="font-size:20px;color:white;"></i>User Comments</a>
                        </li>
                        <li>
                            <a href="index.php?aboutus"><i class="fa fa-users fa-fw nav_icon" style="font-size:20px;color:white;"></i>About Us</a>
                        </li>
                        <li>
                            <a href="logout.php"><i class="fa fa-sign-out fa-fw nav_icon" style="font-size:20px;color:white;"></i>Logout</a>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>