<?php 
        if(isset($_SESSION['user_name']))
        {
            $query=query("select userid from users where username like '".escape_string($_SESSION['user_name'])."'");
            confirm($query);
            $row=fetch_array($query);
            $userid=$row['userid'];
        }
?>

<div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li class="active">
                        <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="../index.php"><i class="fa fa-fw fa-home"></i> Home</a>
                    </li>
                    <li>
                        <a href="index.php?user_profile&name=<?php echo $_SESSION['user_name']; ?>"><i class="fa fa-fw fa-bar-chart-o"></i> User Profile</a>
                    </li>
                    <li>
                        <a href="index.php?orders&id=<?php echo $userid; ?>"><i class="fa fa-fw fa-dashboard"></i> Orders</a>
                    </li>
                    <li>
                        <a href="index.php?reviews&id=<?php echo $userid; ?>"><i class="fa fa-fw fa-dashboard"></i> Reviews</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->