<?php include("navigation.php"); ?>

<?php
  if(!(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2))
    redirect("login.php");
?>

<?php
$message='';
$showinformation=0;
if(isset($_POST['submitreview']))
{
    if($_POST['rating']!=0)
    {
        if(trim($_POST['review'])!="" && !is_null($_POST['review']) && trim($_POST['reviewtitle'])!="" && !is_null($_POST['reviewtitle']))
        {
            $data['user_id']=$_SESSION['user_id'];
            $data['rating']=$_POST['rating'];
            $data['reviewtitle']=$_POST['reviewtitle'];
            $data['review']=$_POST['review'];

            $url=DOMAIN.'/rest/seller/updateSellerReviewsRest.php';
            $output=getRestApiResponse($url,$data);
            
            if(isset($output['updatereviews']) && $output['updatereviews']['response_code']==200)
            {
                $showinformation=1;
                $message='<p class="text-success">Review & rating posted successfully</p>';
               
            }
            else
            {
                $showinformation=1;
                $message='<p class="text-danger">Unable To Perform This Operation</p>';
                
            }
        }
        else
        {
            $showinformation=1;
            $message='<p class="text-danger">Review title and review field must not be blank!</p>';
             
        }
    }
    else
    {
        $showinformation=1;
        $message='<p class="text-danger">Select at least one star to rate!</p>';
    }
}
?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row mt-4">
        <div class="col-lg-12 text-center">
              <h4>Write Review And Rate Us<h4>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <?php
                $data['user_id']=$_SESSION['user_id'];
                $url=DOMAIN.'/rest/seller/getSellerReviewsRest.php';
                $output=getRestApiResponse($url,$data);

                $reviewtitle="";
                $review="";
                $rating=0;

                if(isset($output['getreviews']) && count($output['getreviews'])>3)
                {
                    $reviewtitle="";
                    $review="";
                    $rating="";

                    if(isset($output['getreviews']['review_title']))
                        $reviewtitle=$output['getreviews']['review_title'];

                    if(isset($output['getreviews']['review']))
                        $review=$output['getreviews']['review'];

                    if(isset($output['getreviews']['rating']))
                        $rating=$output['getreviews']['rating'];
                }
            ?>
            <form action="" method="post">
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="form-group row">
                        <label for="rating" class="col-4 col-md-2 col-form-label"><b>Our Rating:</b></label>
                            <div class="col-6 col-md-3">
                                <div class="mt-2">
                                <?php
                                    if($rating!=0)
                                    {
                                        for($i=1;$i<=5;$i++)
                                        {
                                            if($i<=$rating)
                                            {
                                                echo '<span class="fas fa-star fa-lg rating" id="rate'.$i.'" rate="'.$i.'" style="color:yellow;"></span>';
                                            }
                                            else
                                            {
                                                echo '<span class="far fa-star fa-lg rating text-secondary" id="rate'.$i.'" rate="'.$i.'"></span>';
                                            }
                                        }
                                    }
                                    else
                                    {
                                        for($i=1;$i<=5;$i++)
                                        {
                                            echo '<span class="far fa-star fa-lg rating text-secondary" id="rate'.$i.'" rate="'.$i.'"></span>';
                                        }
                                    }
                                ?>
                                </div>
                                <input type="hidden" name="rating" id="rating" value="<?php echo $rating; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                        <label for="reviewtitle"><b>Review Title:</b></label>
                        <input type="text" name="reviewtitle" id="reviewtitle" class="form-control" value="<?php echo $reviewtitle; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                        <label for="review"><b>Your Review:</b></label>
                        <textarea name="review" id="review" class="form-control" rows="3" cols="25" required><?php echo $review; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 col-md-3 text-right">
                        <a href="displaySellerDashboard.php" class="btn btn-danger">Cancel</a>
                    </div>
                    <div class="col-6 col-md-3 text-left">
                        <div class="form-group">
                          <input type="submit" name="submitreview" class="btn btn-success btn-md" value="Submit">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- All Reviews -->
    <div class="row mt-5">
        <!-- Average Rating -->
        <?php
            if(isset($output['getreviews']) && count($output['getreviews'])>3)
            {
              $totalrating="";
              $totalrating.=<<< totalrating
              <div class="col-8 col-md-6">
                {$output['getreviews']['total_rating']}.0&nbsp;&nbsp;
totalrating;
                if($output['getreviews']['total_rating']!=0)
                {
                    for($i=1;$i<=5;$i++)
                    {
                        if($i<=$output['getreviews']['total_rating'])
                        {
                            $totalrating.=<<< totalrating
                            <span class="fas fa-star fa-lg" style="color:yellow;"></span>
totalrating;
                        }
                        else
                        {
                            $totalrating.=<<< totalrating
                            <span class="far fa-star fa-lg text-secondary"></span>
totalrating;
                        }
                    }
                }
                else
                {
                    for($i=1;$i<=5;$i++)
                    {
                        $totalrating.=<<< totalrating
                        <span class="far fa-star fa-lg text-secondary"></span>
totalrating;
                    }
                }

                $totalrating.=<<< totalrating
                ({$output['getreviews']['rows']} Reviews)</div>
                <div class="col-4 col-md-6 text-right"><a href="#" class="btn btn-primary">View All</a></div>      
totalrating;

              echo $totalrating;
        ?>
    </div>
    <hr>
    <div class="row mt-4">
        <!-- All Seller Rating And Reviews -->
        <?php
                for($i=0;$i<$output['getreviews']['rows'];$i++)
                {
                    $otherreviews="";
                    $otherreviews.=<<< other
                    <div class="col-12 col-md-8 mt-3">
                        <div class="row">
                            <div class="col-6">
                                <div>{$output['getreviews'][$i]['username']}</div>
                                <div><p>
other;
                    if($output['getreviews'][$i]['rating']!=0)
                    {
                        for($j=1;$j<=5;$j++)
                        {
                            if($j<=$output['getreviews'][$i]['rating'])
                            {
                                $otherreviews.=<<< other
                                <span class="fas fa-star fa-md" style="color:yellow;"></span>
other;
                            }
                            else
                            {
                                $otherreviews.=<<< other
                                <span class="far fa-star fa-md text-secondary"></span>
other;
                            }
                        }
                    }
                    else
                    {
                        for($j=1;$j<=5;$j++)
                        {
                            $otherreviews.=<<< other
                            <span class="far fa-star fa-md text-secondary"></span>
other;
                        }
                    } 
                        
                    $otherreviews.=<<< other
                    </p></div>
                    </div>
                        <div class="col-6">
                            <p class="text-right text-md-center">{$output['getreviews'][$i]['day']}</p>
                        </div>
                        <div class="col-12">
                            <div><b>{$output['getreviews'][$i]['review_title']}</b></div><div><p>{$output['getreviews'][$i]['review']}</p></div>
                        </div>
                    </div>
                </div>
other;
                    echo $otherreviews;
                }
            }
        ?>
    </div>
</div>

        </div>
        <!-- content end -->
    </div>
    <!-- page content -->
</div>
<!-- page wrapper end -->
<?php
    if($showinformation==1)
        echo '<script>
                $("#information").html(\''.$message.'\');
                $("#information-modal").modal("show");
            </script>';
?>
<?php include("footer.php"); ?>

<script>
    $(".rating").on("click",
        function()
        {
            for(var i=1;i<=5;i++)
            {
                $("#rate"+i).removeClass("fas fa-star");
                $("#rate"+i).addClass("far fa-star text-secondary");
            }

            rate=$(this).attr('rate');

            for(var i=1;i<=rate;i++)
            {
                $("#rate"+i).removeClass("far fa-star text-secondary");
                $("#rate"+i).addClass("fas fa-star");
                $("#rate"+i).css("color","yellow");
            }

            $("#rating").val(rate);
        });    
</script>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

  </body>
</html>
