<?php include("validateUserMultipleLogin.php"); ?>
<?php
	if(isset($_SESSION['username']) && isset($_GET['start']) && isset($_GET['end']))
	{
		$start=$_GET['start'];
		$end=$_GET['end'];

		$query=query("select * from user_comments where comment_status='Y' order by ucid desc limit $start,$end");
    	confirm($query);

    	$comment="";
    	while($row=fetch_array($query))
        {
            $qu=query("select * from user_personal where ulid='".$row['ulid']."'");
                        confirm($qu);
                        $ru=fetch_array($qu);
                        $img_path=image_path_profile($ru['profile_picture']);

                        $com=$row['user_comment'];
                        $c=strlen($com);

                        if($c>=20)
                        {
                            $sub=substr($com,0,30);
                            $sub=$sub."...";
                        }
                        else
                            $sub=$com;

                    $comment.=<<<comment
                    <!-- Team member -->
            <div class="col-lg-4 col-xs-12 col-sm-6 col-md-4">
                <div class="image-flip" ontouchstart="this.classList.toggle('hover');">
                    <div class="mainflip">
                        <div class="frontside">
                            <div class="card">
                                <div class="card-body text-center">
                                    <p><img class=" img-fluid" src="../../resources/{$img_path}" alt="card image"></p>
                                    <h4 class="card-title">{$ru['name']} Says</h4>
                                    <p class="card-text">{$sub}</p>
                                    <a href="index.php?add_usercomment" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="backside">
                            <div class="card">
                                <div class="card-body text-center mt-4">
                                    <h4 class="card-title">{$ru['name']} Says</h4>
                                    <p class="card-text">{$row['user_comment']}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./Team member -->
comment;
        }
        echo $comment;
	}
?>