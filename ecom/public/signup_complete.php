<?php require_once("../resources/config.php");  ?>
<?php include(TEMPLATE_FRONT.DS."header.php");  ?>

<div class="container-fluid">

<div class="col-md-12">

<div class="row">
<h3 class="page-header">
   Update Your Details
</h3>
</div>

<?php 
    if(isset($_GET['name'])) 
    {
        $query=query("select * from users where username like '".escape_string($_GET['name'])."'");
        confirm($query);
        $row=fetch_array($query);
        $img_path=image_path_profile($row['profile_picture']);

        user_signup_complete();
?>

<form action="" method="post" enctype="multipart/form-data">
<div class="col-md-8">

    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" name="username" id="username" class="form-control" value="<?php echo $row['username']; ?>">
    </div>


    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" class="form-control" value="<?php echo $row['password']; ?>">
    </div>

    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" class="form-control" value="<?php echo $row['email']; ?>">
    </div>

    <div class="form-group">
      <label for="firstname">Firstname</label>
      <input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo $row['firstname']; ?>">
    </div>
</div>


<!-- SIDEBAR-->
<aside id="admin_sidebar" class="col-md-4">

    <div class="form-group">
         <label for="lastname">Lastname</label>
        <input type="text" name="lastname" class="form-control" value="<?php echo $row['lastname']; ?>">
    </div>

    <div class="form-group">
      <label for="address">Address</label>
      <input type="text" name="address" id="address" class="form-control" value="<?php echo $row['address']; ?>">
    </div>

    <div class="form-group">
      <label for="mobile_number">Mobile Number</label>
      <input type="number" name="mobile_number" id="mobile_number" class="form-control" value="<?php echo $row['mobile_number']; ?>">
    </div>

    <!-- Image -->
    <div class="form-group">
        <label for="profile_picture">Profile Picture</label>
        <img src="../resources/<?php echo $img_path; ?>" style="width:60px;height:60px;"><br><?php echo $row['profile_picture']; ?>
        <input type="file" name="profile_picture" id="profile_picture"><br>
        <input type="hidden" name="hidden_profile_picture" id="hidden_profile_picture" value="<?php echo $row['profile_picture']; ?>">
    </div>

    <div class="form-group">
      <input type="submit" name="user_signup_complete" class="btn btn-primary btn-lg" value="Procceed">
    </div>

</aside><!--SIDEBAR-->
</form>

<?php
        }
        else
        {
            echo "Sorry ,Basics Informations Are Not Provided.";
        }
?>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="js/plugins/morris/raphael.min.js"></script>
    <script src="js/plugins/morris/morris.min.js"></script>
    <script src="js/plugins/morris/morris-data.js"></script>

</body>

</html>




<?php include(TEMPLATE_FRONT.DS."footer.php");  ?>