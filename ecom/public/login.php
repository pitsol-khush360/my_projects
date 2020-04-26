<?php require_once("../resources/config.php");  ?>
<?php include(TEMPLATE_FRONT.DS."header.php");  ?>

    <!-- Page Content -->
    <div class="container">

      <header>
            <h1 class="text-center">Login</h1>
            <h3 class="text-center text-danger bg-danger"><?php displaymessage(); ?></h3>
        <div class="col-sm-4 col-sm-offset-5">   
                 
            <form class="form" action="" method="post" enctype="multipart/form-data">
                <?php 
                    user_login();
                ?> 
                <div class="form-group"><label for="username">
                    username<input type="text" name="username" id="username" class="form-control"></label>
                </div>
                 <div class="form-group"><label for="password">
                    Password<input type="password" name="password" id="password" class="form-control"></label>
                </div>

                <div class="form-group">
                  <input type="submit" name="submit" class="btn btn-primary" >
                </div>
            </form>
            <p><a href="signup.php" style="text-decoration:none;">If You don't have any account.Please,<span style="font-size:20px;color:green;">sign up!</span></a></p>
        </div>  


    </header>


        </div>

    </div>
    <!-- /.container -->

<?php include(TEMPLATE_FRONT.DS."footer.php");  ?>