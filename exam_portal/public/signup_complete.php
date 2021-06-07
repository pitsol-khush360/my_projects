<?php require_once("../resources/config.php");  ?>
<?php 
  $title="Update Profile";
  include(TEMPLATE_FRONT.DS."header.php");  
?>

<?php
  if(isset($_SESSION['username']) && isset($_SESSION['ulid']))
  {
?>

<div class="container-fluid" style="margin-top:20px;">

<div class="row">
  <h3>Update Your Details</h3>
</div>

<?php 
    if(isset($_GET['edit'])) 
    {
        $query=query("select * from user_login where username like '".escape_string($_SESSION['username'])."'");
        confirm($query);
        $row=fetch_array($query);

        $q=query("select * from user_personal where ulid='".$row['userid']."'");
        confirm($q);
        $row1=fetch_array($q);
        $img_path=image_path_profile($row1['profile_picture']);

        user_signup_complete();
?>

<h3 class="text-center text-danger"><?php echo displaymessage(); ?></h3>
<form action="" method="post" enctype="multipart/form-data" style="margin-top:20px;">
  <div class="row">
  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    <input type="hidden" name="ulid" value="<?php echo $row['userid']; ?>">
    <div class="form-group">
      <label for="username"><b>Username</b></label>
      <input type="text" name="username" id="username" class="form-control" value="<?php echo $row['username']; ?>" readonly>
    </div>

    <div class="form-group">
      <label for="password"><b>Password</b></label>
      <input type="password" name="password" id="password" class="form-control" value="<?php echo $row['password']; ?>" readonly>
    </div>

    <div class="form-group">
      <label for="email"><b>Email</b></label>
      <input type="email" name="email" id="email" class="form-control" value="<?php echo $row1['email']; ?>">
    </div>

    <div class="form-group">
      <label for="firstname"><b>Name</b></label>
      <input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo $row1['name']; ?>">
    </div>

    <div class="form-group">
      <label for="address"><b>Address</b></label>
      <input type="text" name="address" id="address" class="form-control" value="<?php echo $row1['address']; ?>">
    </div>

    <div class="form-group">
      <label for="mobile_number"><b>Mobile Number</b></label>
      <input type="text" name="mobile_number" id="mobile_number" class="form-control" value="<?php echo $row1['mobile']; ?>" pattern="[5-9]{1}[0-9]{9}" required>
    </div>
  </div>

  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    <div class="form-group">
      <label for="district"><b>District</b></label>
      <input type="text" name="district" id="district" class="form-control" value="<?php echo $row1['district']; ?>">
    </div>

    <div class="form-group">
      <label for="state"><b>State</b></label>
      <input type="text" name="state" id="state" class="form-control" value="<?php echo $row1['state']; ?>">
    </div>

    <div class="form-group">
      <label for="country"><b>Country</b></label>
      <input type="text" name="country" id="country" class="form-control" value="<?php echo $row1['country']; ?>">
    </div>

    <!-- Image -->
    <div class="form-group">
        <label for="profile_picture"><b>Profile Picture</b></label>
        <img src="../resources/<?php echo $img_path; ?>" style="width:60px;height:60px;">&nbsp;&nbsp;<?php echo $row1['profile_picture']; ?><br>
        <input type="file" name="profile_picture" id="profile_picture"><br>
        <input type="hidden" name="hidden_profile_picture" id="hidden_profile_picture" value="<?php echo $row1['profile_picture']; ?>">
    </div>

    <input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
    <div class="form-group text-center">
      <input type="submit" name="user_signup_complete" class="btn btn-success btn-md" value="Procceed">
    </div>
  </div>
</div>
</form>

<div class="row">
  <div class="col-xs-12 col-md-12 text-center mt-3">
    <a href="users<?php echo DS; ?>index.php?profile" class="btn btn-info">Back To Profile</a>
  </div>
</div>

<?php
        }
        else
        {
            echo "Sorry ,Basics Informations Are Not Provided.";
        }
?>
</div><!-- container-fluid ends -->
<?php
  }
  else
    redirect("signin.php");
?>

<?php include(TEMPLATE_USERS.DS."footer.php");  ?>