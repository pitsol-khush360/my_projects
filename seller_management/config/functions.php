<?php
// setting default timezone
date_default_timezone_set("Asia/Kolkata");

// helper functions

// for redirection
function redirect($location)
{
	header("location:$location");
} 

// for query execution
function query($sql)
{
	global $connection;
	return $connection->query($sql);
}

function confirm($query)
{
	global $connection;
	if(!$query)
	{
		return false;
	}
	return true;
}

function commit()
{
	global $connection;
	$connection->commit();
}
function rollback()
{
	global $connection;
	$connection->rollback();
}
function error()
{
  global $connection;
  return $connection->error;
}
function close()
{
	global $connection;
	$connection->close();
}
// prevent from sql injections
function escape_string($string)
{
	global $connection;
	$string=htmlentities($string);
	return $string;
}

// return a row from result set
function fetch_array($result)
{
	return mysqli_fetch_assoc($result);
}

function admin_deactive_seller()
{
	if(isset($_POST['uid']))
	{
		$query=query("update users set status='0' where id='".$_POST['uid']."' and role_id='v'");
		confirm($query);
		echo '<script>alert("Seller Deactivated Successfully")</script>';
	}
}

function getRestApiResponse($url,$data)
{
  $data['key']=md5(VALIDATION_KEY);

	$defaults = array(
	CURLOPT_URL => $url,
	CURLOPT_POST => true,
	CURLOPT_POSTFIELDS => $data,
	);

	$client=curl_init();
	curl_setopt_array($client,$defaults);
	curl_setopt($client,CURLOPT_RETURNTRANSFER,true);

	$output=curl_exec($client);
	curl_close($client);			// To close curl.

	return json_decode($output,JSON_FORCE_OBJECT);
}

function getCountries()
{
  echo "<option value='101'>India</option>";
}

function getStates($sid)
{
  $fun_st=array();
  $fun_st['countrycode']=101;
  $url=DOMAIN.'/rest/seller/getAllstatesRest.php';

  $row=getRestApiResponse($url,$fun_st);

  if(isset($row['getallstates']) && $row['getallstates']['response_code']==200 && $row['getallstates']['rows']!=0)
  {
    $states="";

    if($sid!="")
    {
      for($i=0;$i<$row['getallstates']['rows'];$i++)
      {
        if($row['getallstates'][$i]['id']==$sid)
        {
          $states.=<<< state
          <option value="{$row['getallstates'][$i]['id']}" selected>{$row['getallstates'][$i]['name']}</option>
state;
        }
        else
        {
          $states.=<<< state
          <option value="{$row['getallstates'][$i]['id']}">{$row['getallstates'][$i]['name']}</option>
state;
        }
      }
    }
    else
    {
      $states.=<<< state
      <option value="">--Select State--</option>
state;
      
      for($i=0;$i<$row['getallstates']['rows'];$i++)
      {
        $states.=<<< state
        <option value="{$row['getallstates'][$i]['id']}">{$row['getallstates'][$i]['name']}</option>
state;
      }
    }
    echo $states;
  }
}

function getCatalogues($cid)
{
  $coll=array();
  $coll['user_id']=$_SESSION['user_id'];
  $coll['all']="ALL";
  $url=DOMAIN.'/rest/seller/getCatalogueListScreenRest.php';

  $row=getRestApiResponse($url,$coll);

  if(isset($row['getcatalogue']) && $row['getcatalogue']['response_code']==200 && $row['getcatalogue']['rows']!=0)
  {
    $cats="";

    if($cid!="")
    {
      for($i=0;$i<$row['getcatalogue']['rows'];$i++)
      {
        if($row['getcatalogue'][$i]['catalogue_id']==$cid)
        {
          $cats.=<<< cat
          <option value="{$row['getcatalogue'][$i]['catalogue_id']}" selected>{$row['getcatalogue'][$i]['catalogue_Name']}</option>
cat;
        }
        else
        {
          $cats.=<<< cat
          <option value="{$row['getcatalogue'][$i]['catalogue_id']}">{$row['getcatalogue'][$i]['catalogue_Name']}</option>
cat;
        }
      }
    }
    else
    {
      for($i=0;$i<$row['getcatalogue']['rows'];$i++)
      {
        $cats.=<<< cat
        <option value="{$row['getcatalogue'][$i]['catalogue_id']}">{$row['getcatalogue'][$i]['catalogue_Name']}</option>
cat;
      }
    }
    echo $cats;
  }
}

function setupPagination($sql)
{
	$query=query($sql);
    confirm($query);

    if(mysqli_num_rows($query)!=0)
    {
        $p=intval(mysqli_num_rows($query));

        if(isset($_SESSION['pages']))
        	$n=intval($p/$_SESSION['pages']);
        else
        	$n=intval($p/10);

        if(isset($_SESSION['pages']))
        	$rem=intval($p%$_SESSION['pages']);
        else
        	$rem=intval($p%10);

        $pages=$n;

        if($rem!=0)
          $pages+=1;

        $i=1;
        $start=0;

        if(isset($_SESSION['pages']))
        	$end=$_SESSION['pages'];
        else
        	$end=10;
        // Pagination
        echo "<div class='row'>
                <div class='col-md-12'>
                  <ul class='pagination justify-content-center'>
                  <li id='prev' class='page-item'><span style='cursor:pointer;' class='page-link'><i class='fa fa-backward'></i></span></li>
                  <li class='page-item active page' start='$start' end='$end' id='button$i'><span style='cursor:pointer;' class='page-link'>$i</span></li>";

        if(isset($_SESSION['pages']))
        	$start+=$_SESSION['pages'];
        else
        	$start+=10;

        for($i=2;$i<=$pages;$i++)
        {
          echo "<li class='page-item page' start='$start' end='$end' id='button$i'><span style='cursor:pointer;' class='page-link'>$i</span></li>";

          if(isset($_SESSION['pages']))
          	$start+=$_SESSION['pages'];
          else
          	$start+=10;
        }
        echo "<li id='next' class='page-item'><span style='cursor:pointer;' class='page-link'><i class='fa fa-forward'></i></span></li>";
        echo '</ul>
                </div>
                  </div>';
    }
}

function displayTransactionsForWalletScreen($output)
{
  $tabdata="";
  for($i=0;$i<$output['getwalletbalance']['rows'];$i++)
  {
    $price="";

    if($output['getwalletbalance'][$i]['dr_cr_Indicator']=="C")
      $price="<p class='text-success'><i class='fas fa-plus'></i>&nbsp;<i class='fas fa-rupee-sign'></i>&nbsp;<b>".$output['getwalletbalance'][$i]['amount']."</b></p>";
    else
    if($output['getwalletbalance'][$i]['dr_cr_Indicator']=="D")
      $price="<p class='text-danger'><i class='fas fa-minus'></i>&nbsp;<i class='fas fa-rupee-sign'></i>&nbsp;<b>".$output['getwalletbalance'][$i]['amount']."</b></p>";

    $tabdata.=<<< tabdata
    <div class="row mt-3">
      <div class="col-12">
        <b>Date:</b>&nbsp;{$output['getwalletbalance'][$i]['date']}
      </div>
      <div class="col-12 mt-3">
        <div class="row">
          <div class="col-5 col-md-8">
            {$output['getwalletbalance'][$i]['time']} / {$output['getwalletbalance'][$i]['movement_description']} / Order Id - {$output['getwalletbalance'][$i]['order_id']}
          </div>
          <div class="col-7 col-md-3">
            <div class="row">
              <div class="col-8 col-md-8 text-right mt-3">
                <span>{$price}</span>
              </div>
              <div class="col-4 col-md-4 text-right">
                <form action="" method="post">
                  <input type="hidden" name="mid" value="{$output['getwalletbalance'][$i]['cash_movement_id']}">
                  <input type="submit" name="seetransactiondetails" class="btn btn-primary" value="Details">
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <hr>
tabdata;
  }
  echo $tabdata;
}

function displayOrdersInDashboard($output)
{
    echo '
    <div class="row mt-4">
      <div class="col-12 table-responsive">
        <table class="table table-hover table-bordered text-center w-auto table-sm">
          <thead>
            <tr>
              <th>Order Id</th>
              <th>Order Type</th>
              <th>Customer Name</th>
              <th>No. Of Items</th>
              <th>Cart Total</th>
              <th>DateTime</th>
              <th>Status</th>
              <th colspan="3">Action</th>
            </tr>
          </thead>
          <tbody id="order_body">';

      for($i=0;$i<10 && $i<$output['getorders']['rows'];$i++)
      {
        $record="";
        $record.=<<< record
        <tr>
        <td>{$output['getorders'][$i]['basket_order_id']}</td>
        <td>{$output['getorders'][$i]['order_type']}</td>
        <td>{$output['getorders'][$i]['customer_name']}</td>
        <td>{$output['getorders'][$i]['total_items']}</td>
        <td>{$output['getorders'][$i]['net_amount']}</td>
        <td>{$output['getorders'][$i]['order_date']}</td>
record;

        if($output['getorders'][$i]['order_status']=="Pending")
        {
          $record.=<<< record
          <td><p class="text text-warning">{$output['getorders'][$i]['order_status']}</p></td>
          <td>
            <form action="displaySellerOrders.php" method="post">
              <input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
              <input type="hidden" name="orderdate" value="{$output['getorders'][$i]['order_date']}">
              <button type="submit" name="orderitems" class="btn btn-primary">View</button>
            </form>
          </td>
          <td>
            <form action="displaySellerOrders.php" method="post">
              <input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
              <input type="hidden" name="order_status" value="Accepted">
              <button type="submit" name="setorderstatus" class="btn btn-primary" onclick="return confirm('Do You Really Want To Accept This Order')">Accept</button>
            </form>
          </td>
          <td>
            <form action="displaySellerOrders.php" method="post">
              <input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
              <input type="hidden" name="order_status" value="Declined">
              <button type="submit" name="setorderstatus" class="btn btn-danger" onclick="return confirm('Do You Really Want To Reject This Order')">Decline</button>
            </form>
          </td>
record;
        }
        else if($output['getorders'][$i]['order_status']=="Accepted")
        {
          $record.=<<< record
          <td><p class="text text-success">{$output['getorders'][$i]['order_status']}</p></td>
          <td>
          <form action="displaySellerOrders.php" method="post">
            <input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
            <input type="hidden" name="orderdate" value="{$output['getorders'][$i]['order_date']}">
            <button type="submit" name="orderitems" class="btn btn-primary">View</button>
          </form>
          </td>
          <td>
            <form action="displaySellerOrders.php" method="post">
              <input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
              <input type="hidden" name="order_status" value="Shipped">
              <button type="submit" name="setorderstatus" class="btn btn-info">Ship</button>
            </form>
          </td>
          <td>
            <form action="displaySellerOrders.php" method="post">
              <input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
              <input type="hidden" name="order_status" value="Declined">
              <button type="submit" name="setorderstatus" class="btn btn-danger" onclick="return confirm('Do You Really Want To Reject This Order')">Decline</button>
            </form>
          </td>
record;
        }
        else if($output['getorders'][$i]['order_status']=="Declined")
        {
          $record.=<<< record
          <td><p class="text text-danger">{$output['getorders'][$i]['order_status']}</p></td>
          <td>
          <form action="displaySellerOrders.php" method="post">
            <input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
            <input type="hidden" name="orderdate" value="{$output['getorders'][$i]['order_date']}">
            <button type="submit" name="orderitems" class="btn btn-primary">View</button>
          </form>
          </td>
          <td></td><td></td>
record;
        }
        else if($output['getorders'][$i]['order_status']=="Shipped")
        {
          $record.=<<< record
          <td><p class="text text-info">{$output['getorders'][$i]['order_status']}</p></td>
          <td>
          <form action="displaySellerOrders.php" method="post">
            <input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
            <input type="hidden" name="orderdate" value="{$output['getorders'][$i]['order_date']}">
            <button type="submit" name="orderitems" class="btn btn-primary">View</button>
          </form>
          </td>
          <td>
            <form action="displaySellerOrders.php" method="post">
              <input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
              <input type="hidden" name="order_status" value="Delivered">
              <button type="submit" name="setorderstatus" class="btn btn-success">Delivered</button>
            </form>
          </td>
          <td>
            <form action="displaySellerOrders.php" method="post">
              <input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
              <input type="hidden" name="order_status" value="Returned">
              <button type="submit" name="setorderstatus" class="btn btn-danger">Returned</button>
            </form>
          </td>
record;
        }
        else if($output['getorders'][$i]['order_status']=="Delivered")
        {
          $record.=<<< record
          <td><p class="text text-success">{$output['getorders'][$i]['order_status']}</p></td>
          <td>
          <form action="displaySellerOrders.php" method="post">
            <input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
            <input type="hidden" name="orderdate" value="{$output['getorders'][$i]['order_date']}">
            <button type="submit" name="orderitems" class="btn btn-primary">View</button>
          </form>
          </td>
          <td></td><td></td>
record;
        }
        else
        {
          $record.=<<< record
          <td><p class="text text-danger">{$output['getorders'][$i]['order_status']}</p></td>
          <td>
          <form action="displaySellerOrders.php" method="post">
            <input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
            <input type="hidden" name="orderdate" value="{$output['getorders'][$i]['order_date']}">
            <button type="submit" name="orderitems" class="btn btn-primary">View</button>
          </form>
          </td>
          <td></td>
record;
        }

        $record.=<<< record
      </tr>
record;
        echo $record;
      }

    echo '
          </tbody>
        </table>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12 text-center">
        <a href="displaySellerOrders.php?orderstatus='.$output["getorders"][0]["order_status"].'" class="btn btn-primary mb-2">Show All</a>
      </div>
    </div>';
}

function prepareTransactionsDataForDownloadStatement($output,$sid,$bname,$interval)
{
  $data="";

  $data.="<b>Seller Id</b> - <span>".$sid."</span><br>";
  $data.="<b>Seller Business Name</b> - <span>".$bname."</span><br>";
  $data.="<b>Description</b> - <span>".$interval." Transactions</span>";

  $data.="<br><br><br>";

  $data.=<<<data
  <table style="border:1px solid grey;border-collapse: collapse;width:100%;">
    <tr>
      <th>Date</th>
      <th>Description</th>
      <th>Order Id</th>
      <th>Amount</th>
      <th>Closing Balance</th>
    </tr>
data;

  for($i=0;$i<$output['getwalletbalance']['rows'];$i++)
  {
    $price="";

    if($output['getwalletbalance'][$i]['dr_cr_Indicator']=="C")
      $price="<p style='color:green;'><i class='fas fa-rupee-sign'></i>&nbsp;<b>".$output['getwalletbalance'][$i]['amount']."</b></p>";
    else
    if($output['getwalletbalance'][$i]['dr_cr_Indicator']=="D")
      $price="<p style='color:red;'><i class='fas fa-rupee-sign'></i>&nbsp;<b>".$output['getwalletbalance'][$i]['amount']."</b></p>";

    $data.=<<< data
    <tr>
      <td style="border:1px solid grey;width:26%;text-align:center;">
        {$output['getwalletbalance'][$i]['date']}&nbsp;&nbsp;{$output['getwalletbalance'][$i]['time']}
      </td>
      <td style="border:1px solid grey;width:24%;">
        {$output['getwalletbalance'][$i]['movement_description']}
      </td>
      <td style="border:1px solid grey;width:26%;text-align:center;">
        {$output['getwalletbalance'][$i]['order_id']}
      </td>
      <td style="border:1px solid grey;text-align:right;width:12%;">
        {$price}
      </td>
      <td style="border:1px solid grey;text-align:right;width:12%;">
        {$output['getwalletbalance'][$i]['closing_balance']}
      </td>
    </tr>
data;
  }
  $data.="</table>";

  return $data;
}

function getMaskedString($str,$char,$startindex,$endindex)
{
  $data=substr_replace($str,str_repeat($char,$endindex),$startindex,$endindex);
  return $data;
}

function readDataFromFile($path)
{
  $file=fopen($path,"r") or die("Unable to open file!");
  
  while(!feof($file)) 
  {
    echo fgets($file)."<br>";
  }
  fclose($file);
}

function imageupload($data,$imgname)
{
  $bin = base64_decode($data);

  // Load GD resource from binary data
  $im = imageCreateFromString($bin);

  // Make sure that the GD library was able to load the image
  // This is important, because you should not miss corrupted or unsupported images
  if (!$im) 
  {
    return "Invalid";
  }

  // Specify the location where you want to save the image
  $img_file = $imgname;
  $width = imagesx($im);
  $height = imagesy($im);
  $percent = 0.5;
  $newwidth = $width * $percent;
  $newheight = $height * $percent;

  $thumb = imagecreatetruecolor($newwidth, $newheight);

  // Resize
  imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);


  imagejpeg($thumb, $img_file, 60);
  return "Success";
}

//SEND MESSAGE
function sendMessage($mobile,$text)
{
  $api_key = KEY;
  $contacts = $mobile;
  $from = SENDER;
  $sms_text = urlencode($text);

  //Submit to server

  $ch = curl_init();
  curl_setopt($ch,CURLOPT_URL, SMS_URL);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "key=".$api_key."&campaign=0&routeid=7&type=text&contacts=".$contacts."&senderid=".$from."&msg=".$sms_text);
  $response = curl_exec($ch);
  curl_close($ch);
  return $response;
}

function premissionScreen($screen,$admin_id)
{
  $global=1;
  $data=array();
  $data['screen'] = $screen;
  $data['admin_id'] = $admin_id;
  $button='';
  $input='';
  $url = DOMAIN.'/rest/admin/ValidateUserAccessPermissionRest.php';
  $output = getRestApiResponse($url, $data);

  if(isset($output['givenpermissiondetails'][$screen.'_READ_AND_WRITE']) && $output['givenpermissiondetails'][$screen.'_READ_AND_WRITE']==true)
  {
    $global=1;
    $button='';
    $input='';
  }
  else if(isset($output['givenpermissiondetails'][$screen.'_READ_ONLY']) && $output['givenpermissiondetails'][$screen.'_READ_ONLY']==true)
  {
    $button='disabled';
    $input='readonly';
    $global=1;
  }
  else if(isset($output['givenpermissiondetails']['ADMIN_APPLICATION_READ_AND_WRITE']) && $output['givenpermissiondetails']['ADMIN_APPLICATION_READ_AND_WRITE']==true)
  {
    $global=1;
    $button='';
    $input='';
  }
  else if(isset($output['givenpermissiondetails']['ADMIN_APPLICATION_READ_ONLY']) && $output['givenpermissiondetails']['ADMIN_APPLICATION_READ_ONLY']==true)
  {
    $button='disabled';
    $input='readonly';
    $global=1;
  }
  else
  {
    $global=0;
  }
  $data=array();
  $data['global'] = $global;
  $data['button'] = $button;
  $data['input'] = $input;
  return $data;
}

function getTransactionDescription($interval)
{
  $description="";

  switch($interval) 
  {
    case 1:
      $description="Your This Month";
      break;
    case 2:
      $description="Your Last 1 Month";
      break;
    case 3:
      $description="Your Last 3 Months";
      break;
    case 4:
      $description="Your Last 6 Months";
      break;
    case 5:
      $description="Your Current Year";
      break;
    case 6:
      $description="Your Last 1 Year";
      break;
    default:
      $description=$interval;
  }

  return $description;
}
?>