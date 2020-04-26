<?php require_once("../resources/config.php");  ?>
<?php include(TEMPLATE_FRONT.DS."header.php");  ?>


<?php add_review(); ?>
    <!-- Page Content -->
<div class="container">
       <!-- Side Navigation -->
        <?php include(TEMPLATE_FRONT.DS."side_nav.php"); ?>

<?php 
        $query=query("select * from products where product_id='".escape_string($_GET['id'])."'");
        confirm($query);
        while($row=fetch_array($query))
        {
            $product_image=image_path_products($row['product_image']);
?>

<div class="col-md-9">

<!--Row For Image and Short Description-->
<div class="row">
    <div class="col-md-7">
       <img class="img-responsive" src="<?php echo "../resources/".$product_image; ?>" alt="" style="width:100%;height:250px;">
    </div>

    <div class="col-md-5">
        <div class="thumbnail">
            <div class="caption-full">
            <h4><a href="#"><?php echo $row['product_title']; ?></a> </h4>
            <hr>
            <h4 class=""><?php echo "&#8377;",$row['product_price']; ?></h4>

            <div class="ratings">
     
        <p>
            <?php average_rating(); ?>
        </p>
            </div>
        <p>
        <?php echo $row['product_short_description']; ?>
        </p>
        <br>
    <form action="">
        <div class="form-group">
            <a href="../resources/cart.php?add=<?php echo $row['product_id']; ?>" class="btn btn-primary">Add To Cart</a>
        </div>
    </form>

    </div>
 
</div>

</div>


</div><!--Row For Image and Short Description-->


        <hr>


<!--Row for Tab Panel-->

<div class="row">

<div role="tabpanel">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Description</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Reviews</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">
    <p></p>
    <?php echo $row['product_description']; ?>
    </div>

    <div role="tabpanel" class="tab-pane" id="profile">
        <div class="col-md-6">
            <h3>Reviews</h3>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <?php show_reviews(); ?>
                </div>
            </div>
        </div>

<?php
} // end of while loop.
?>

<?php
        if(isset($_SESSION['user_name']))
        {
            $query=query("select userid from users where username like '".escape_string($_SESSION['user_name'])."'");
            confirm($query);
            $row_user=fetch_array($query);
            $user_id=$row_user['userid'];      // show add review form if user has logged-in.
?>
        <div class="col-md-6">
            <h3>Add A review</h3>
            <hr>
                <form action="" class="form-inline" method="post">
                    <div class="form-group">
                        <label><h4>Your Rating</h4></label><br>
                        <span class="glyphicon glyphicon-star" id="s1"></span>
                        <span class="glyphicon glyphicon-star" id="s2"></span>
                        <span class="glyphicon glyphicon-star" id="s3"></span>
                        <span class="glyphicon glyphicon-star" id="s4"></span>
                        <span class="glyphicon glyphicon-star" id="s5"></span>
                        <input type="hidden" name="rating">
                    </div>
                    <br>
                    <br>
                    <div class="form-group">
                        <label for="comment"><h4>Your Comment</h4></label>
                        <textarea name="review_comment" id="comment" cols="60" rows="10" class="form-control"></textarea>
                    </div>
                    <input type="hidden" name="product_id" value="<?php echo "{$_REQUEST['id']}" ?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <br>
                    <br>
                    <div class="form-group">
                        <input type="submit" name="review_submit" class="btn btn-primary" value="SUBMIT">
                    </div>
                </form>
        </div>
<?php 
        } // end of if statement.
?>
    </div> <!-- tabpane for id profile ends -->
 </div> <!-- tab content ends -->

</div><!-- tabpanel ends -->
</div><!--Row for Tab Panel-->

</div>
</div>
    <!-- /.container -->

<!-- JQuery for setting reviews -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('#s1').mousemove(function(){
            $('#s1').css("color","yellow");
            $('#s2').css("color","black");
            $('#s3').css("color","black");
            $('#s4').css("color","black");
            $('#s5').css("color","black");
            $('input[name=rating]').val(1);
        });
        $('#s2').mousemove(function(){
            $('#s1').css("color","yellow");
            $('#s2').css("color","yellow");
            $('#s3').css("color","black");
            $('#s4').css("color","black");
            $('#s5').css("color","black");
            $('input[name=rating]').val(2);
        });
        $('#s3').mousemove(function(){
            $('#s1').css("color","yellow");
            $('#s2').css("color","yellow");
            $('#s3').css("color","yellow");
            $('#s4').css("color","black");
            $('#s5').css("color","black");
            $('input[name=rating]').val(3);
        });
        $('#s4').mousemove(function(){
            $('#s1').css("color","yellow");
            $('#s2').css("color","yellow");
            $('#s3').css("color","yellow");
            $('#s4').css("color","yellow");
            $('#s5').css("color","black");
            $('input[name=rating]').val(4);
        });
        $('#s5').mousemove(function(){
            $('#s1').css("color","yellow");
            $('#s2').css("color","yellow");
            $('#s3').css("color","yellow");
            $('#s4').css("color","yellow");
            $('#s5').css("color","yellow");
            $('input[name=rating]').val(5);
        });
        $('form').submit(function(){
            if($('input[name=rating]').val()=="")
            {
                alert("Please give rating.");
                return false;
            }
        });
    });
</script>

<?php include(TEMPLATE_FRONT.DS."footer.php");  ?>