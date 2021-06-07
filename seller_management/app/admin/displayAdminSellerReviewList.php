<?php
require_once("../../config/config.php");
require_once("../../config/" . ENV . "_config.php");
?>

<?php
if (!(isset($_SESSION['current_user'])))
  redirect('login.php');
?>
<?php include("navigation.php"); ?>
<?php

$access = premissionScreen('SELLER_REVIEWS', $_SESSION['current_user']);

$global = $access['global'];
$input = $access['input'];
$button = $access['button'];

if ($global != 0) {
?>

  <?php
  $showinformation = 0;
  $message = "";
  ?>

  <!-- Connecting with RestApi-->

  <?php
  function time_elapsed_string($datetime,  $full = false)
  {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
      'y' => 'year',
      'm' => 'month',
      'w' => 'week',
      'd' => 'day',
      'h' => 'hour',
      'i' => 'minute',
      's' => 'second',
    );
    foreach ($string as $k => &$v) {
      if ($diff->$k) {
        $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
      } else {
        unset($string[$k]);
      }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
  }

  $data = array();
  if (isset($_POST['seller_id'])) {
    $data['seller_id'] = $_POST['seller_id'];
  } else if (isset($_POST['Rating'])) {
    $data['rating'] = $_POST['Rating'];
  }
  $url = DOMAIN . '/rest/admin/RatingReviewAdminRest.php';
  $output = getRestApiResponse($url, $data);

  $record = '';

  if (isset($output['getreviewdetails']) && $output['getreviewdetails']['response_code'] == 200) {
    $record .= <<< record
    <input type="hidden" value="<?php echo DOMAIN; ?>" id="getDOMAIN">
    <div class="container-fluid pt-3">
      <div class="row">
      <div class="col">
      <form action="displayAdminSellerReviewList.php" method="post">
          
    
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Seller Id" name="seller_id" style="border: medium solid black;margin-top:16px;">
            <div class="input-group-append">
              <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;x">
                <i class="fa fa-search"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
      <div class="col">
      <form action="displayAdminSellerReviewList.php" method="post">
          
    
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Rating" name="Rating" style="border: medium solid black;margin-top:16px;">
        <div class="input-group-append">
          <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;x">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
    </form>
      </div>
      </div>
    </div>
         
        <div class="mt-3">
        <table class="table table-hover table-responsive-lg table-sm table-bordered table-fixed text-center" 
        data-toggle="table"  
                    data-pagination-h-align="center"
                    data-pagination-detail-h-align="right"
                    data-side-pagination = "client"
                    data-page-size= "10"
                    data-pagination="true">

        <thead style="background-color : #36404e ; color : white;">
      <tr >
        <th>Seller Id</th>
        <th>Review Title</th>
        <th>Review</th>
        <th>Rating</th>
        <th>Review Date Time</th>
      </tr>
    </thead>
    <tbody>
record;
    for ($i = 0; $i < $output['getreviewdetails']['rows']; $i++) {
      $review_50 = substr($output['getreviewdetails'][$i]['review'], 0, 50);
      $time_ago = time_elapsed_string($output['getreviewdetails'][$i]['creation_date_time']);
      $record .= <<< record
                    <tr>
                        <td>{$output['getreviewdetails'][$i]['seller_id']}</td>
                        <td>{$output['getreviewdetails'][$i]['review_title']}</td>
                        <td>$review_50</td>
record;
      if ($output['getreviewdetails'][$i]['rating'] == 1) {
        $record .= <<< record
             <td><i class="fa fa-star" style="color:yellow" aria-hidden="true"></i></td>
record;
      } else if ($output['getreviewdetails'][$i]['rating'] == 2) {
        $record .= <<< record
             <td><i class="fa fa-star" style="color:yellow" aria-hidden="true"></i><i class="fa fa-star" style="color:yellow" aria-hidden="true"></i></td>
record;
      } else if ($output['getreviewdetails'][$i]['rating'] == 3) {
        $record .= <<< record
             <td><i class="fa fa-star" style="color:yellow" aria-hidden="true"></i><i class="fa fa-star" style="color:yellow" aria-hidden="true"></i><i class="fa fa-star" style="color:yellow" aria-hidden="true"></i></td>
record;
      } else if ($output['getreviewdetails'][$i]['rating'] == 4) {
        $record .= <<< record
             <td><i class="fa fa-star" style="color:yellow" aria-hidden="true"></i><i class="fa fa-star" style="color:yellow" aria-hidden="true"></i><i class="fa fa-star" style="color:yellow" aria-hidden="true"></i><i class="fa fa-star" style="color:yellow" aria-hidden="true"></i></td>
record;
      } else if ($output['getreviewdetails'][$i]['rating'] == 5) {
        $record .= <<< record
             <td><i class="fa fa-star" style="color:yellow" aria-hidden="true"></i><i class="fa fa-star" style="color:yellow" aria-hidden="true"></i><i class="fa fa-star" style="color:yellow" aria-hidden="true"></i><i class="fa fa-star" style="color:yellow" aria-hidden="true"></i><i class="fa fa-star" style="color:yellow" aria-hidden="true"></i></td>
record;
      } else if ($output['getreviewdetails'][$i]['rating'] == 0) {
        $record .= <<< record
             <td></td>
record;
      }
      $record .= <<< record
                        <td>{$time_ago}</td>
                    </tr>      
record;
    }

    echo $record;
  } else {
    if (isset($output['getreviewdetails']) && $output['getreviewdetails']['response_code'] == 405) {
      $record .= <<< record
              <h3 class="text-center pt-5" style="color:red;">{$output['getreviewdetails']['response_desc']}</h3>
record;
    }
    echo $record;
  }
  ?>
  </tbody>
  </table>

  </div>
















  <script>
    $(document).ready(() => {
      if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
      }
    });
  </script>
  </body>

  </html>
<?php
} else {
  echo '<h2 class = "pt-5" style="text-align:center;color:red;margin-top:4rem;">Permission Denied!</h2>';
}
?>
<?php include("footer.php"); ?>