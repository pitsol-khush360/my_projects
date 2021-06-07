<div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Hello, <?php echo $_SESSION['user_name']; ?></a>
            </div>
            <!-- Top Menu Items -->
            <div class="nav navbar-right top-nav" style="margin-top:15px;">
                <a href="logout.php" style="text-decoration:none;color:white;margin-right:20px;"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
            </div>