
<?php 
if(isset($_SESSION['user_name']))
{
    $query=query("select * from users where username like '".$_SESSION['user_name']."'");
    confirm($query);
    $row=fetch_array($query);
    $img_path=image_path_profile($row['profile_picture']);
    $userid=$row['userid'];

?>
<div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div style="position: relative;">
            <div class="navbar-header" >
                <button type="button" class="navbar-toggle pull-left" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
               <a class="navbar-brand" href="index.php">Home</a>               
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="shop.php">Shop</a>
                    </li>
                    <li>
                        <a href="login.php">Login</a>
                    </li>
                    <li>
                        <a href="signup.php">Sign Up</a>
                    </li>
                    <!--<li>
                        <a href="admin">Admin</a>
                    </li>-->
                     <li>
                        <a href="checkout.php">Checkout</a>
                    </li>
                    <li>
                        <a href="contact.php">Contact</a>
                    </li>
                    <li>
                        <input type="text" name="search" id="search" style="margin-left:50px;font-size:20px;width:250px;">
                        <i class="glyphicon glyphicon-search" style="color:grey;font-size:20px;margin-top:10px;"></i>
                    </li>
                </ul>
            </div>

            <!-- /.navbar-collapse -->
            <a href="checkout.php"  style="color:white;font-size:20px; position: absolute;top:10px;right:100px;"><i class="glyphicon glyphicon-shopping-cart"></i>
                <span class="badge">
                <?php 
                    if(isset($_SESSION['totalproducts']))
                    {
                        echo $_SESSION['totalproducts'];
                    }
                    else
                    {
                        echo "0";
                    } 
                ?>
                </span>
            </a> 
            <?php
                        if(isset($_SESSION['user_name']))
                        {
                    ?>
                    
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="text-decoration:none;color:white;position: absolute;top:0px;right:15px;">
                        <img src="../resources/<?php echo $img_path; ?>" style="width:50px;height:50px;border-radius:30px;">
                        <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><p class="text-info text-center" style="font-size:20px;">Hello, <?php echo $_SESSION['user_name']; ?></p>
                            </li>
                            <li class="list-group-item list-style-none text-center"><a href="users/index.php?orders&id=<?php echo $userid; ?>">Your Orders</a></li>
                            <li class="list-group-item list-style-none text-center"><a href="users/index.php?user_profile&name=<?php echo $_SESSION['user_name']; ?>">Your Profile</a></li>
                            <li class="list-group-item list-style-none text-center"><a href="saved_items.php?userid=<?php echo $userid; ?>">Saved Items</a></li>
                        </ul>
                    <?php
                        }   // ending of if
                    ?>
            </div>   
</div>
        <!-- /.container -->

<?php
    }
    else
    {
?>
<div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div style="position: relative;">
            <div class="navbar-header" >
                <button type="button" class="navbar-toggle pull-left" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
               <a class="navbar-brand" href="index.php">Home</a>               
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="shop.php">Shop</a>
                    </li>
                    <li>
                        <a href="login.php">Login</a>
                    </li>
                    <li>
                        <a href="signup.php">Sign Up</a>
                    </li>
                    <li>
                        <a href="admin">Admin</a>
                    </li>
                     <li>
                        <a href="checkout.php">Checkout</a>
                    </li>
                    <li>
                        <a href="contact.php">Contact</a>
                    </li>
                    <li>
                        <a href="users">Users</a>
                    </li>
                </ul>
            </div>

            <!-- Search Bar -->
            <input type="text" name="search" id="search" style="position:absolute;top:10px;right:190px;font-size:20px;width:250px;">
            <i class="glyphicon glyphicon-search" style="color:grey;font-size:20px;position:absolute;top:15px;right:160px;"></i>

            <!-- /.navbar-collapse -->
            <a href="checkout.php"  style="color:white;font-size:20px; position: absolute;top:10px;right:100px;"><i class="glyphicon glyphicon-shopping-cart"></i>
                <span class="badge">
                <?php 
                    if(isset($_SESSION['totalproducts']))
                    {
                        echo $_SESSION['totalproducts'];
                    }
                    else
                    {
                        echo "0";
                    } 
                ?>
                </span>
            </a> 
            </div>   
</div> <!-- /.container -->
<?php
    } // end of else
?>