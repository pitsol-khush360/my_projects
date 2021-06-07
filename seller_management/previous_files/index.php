<?php include("../config/config.php"); ?>
<?php include("../config/".ENV."_config.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
  <title><?php echo APP; ?> - Customer Page</title>
  <link rel="stylesheet" href="../public/font-awesome/css/fontawesome.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="../public/font-awesome/css/all.min.css" rel="stylesheet" type="text/css">
    
  <link rel="stylesheet" href="../public/css/bootstrap/bootstrap.min.css">
  <script src="../public/js/jquery.min.js"></script>
  <script src="../public/js/bootstrap.min.js"></script>

  <link type="text/css" href="<?php echo DOMAIN; ?>/public/css/buyer-panel.css?<?php echo time(); ?>" rel="stylesheet">
  <link href="../public/css/mystyles.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php
  // if(isset($_REQUEST['payment-attempt']) && $_REQUEST['payment-attempt']==1)
  // {
  //   if(isset($_SESSION['cartdetails']))
  //     unset($_SESSION['cartdetails']);

  //   if(isset($_SESSION['sellerdomain_delivery_charge']))
  //     unset($_SESSION['sellerdomain_delivery_charge']);

  //   if(isset($_SESSION['sellerdomain_delivery_free_above']))
  //     unset($_SESSION['sellerdomain_delivery_free_above']);

  //   if(isset($_SESSION['sellerdomain_user_id']))
  //     unset($_SESSION['sellerdomain_user_id']);

  //   if(isset($_SESSION['sellerdomain_kyc_completed']))
  //     unset($_SESSION['sellerdomain_kyc_completed']);

  //   if(isset($_SESSION['sellerdomain_accept_online_payments']))
  //     unset($_SESSION['sellerdomain_accept_online_payments']);

  //   if(isset($_SESSION['sellerdomain_accept_cod_payments']))
  //     unset($_SESSION['sellerdomain_accept_cod_payments']);
  // }

  function getTotalCartPrice()
  {
    $price=0;

    if(isset($_SESSION[$_REQUEST['s']]['cartdetails']) && count($_SESSION[$_REQUEST['s']]['cartdetails'])>0)
    {
      foreach($_SESSION[$_REQUEST['s']]['cartdetails'] as $key => $value)
      {
        $price+=($value['price'] * $value['quantity']);
      }
    }
    return $price;
  }

  function isAdded($id)
  {
    $added=0;

    if(isset($_SESSION[$_REQUEST['s']]['cartdetails']) && count($_SESSION[$_REQUEST['s']]['cartdetails'])>0)
    {
      foreach($_SESSION[$_REQUEST['s']]['cartdetails'] as $key => $value)
      {
        if(intval($value['product_id'])==intval($id))
        {
          $added=1;
          break;
        }
      }
    }
    return $added;
  }

  function getIndex($id)
  {
    $index=-1;

    if(isset($_SESSION[$_REQUEST['s']]['cartdetails']) && count($_SESSION[$_REQUEST['s']]['cartdetails'])>0)
    {
      foreach($_SESSION[$_REQUEST['s']]['cartdetails'] as $key => $value)
      {
        if(intval($value['product_id'])==intval($id))
        {
          $index=$key;
          break;
        }
      }
    }
    return $index;
  }

  if(isset($_POST['add_to_cart']))
  {
    if(isset($_POST['product_id']) && $_POST['product_id']!="")
    {
      $added=isAdded($_POST['product_id']);
      if($added==0)
      {
        $_SESSION[$_REQUEST['s']]['cartdetails'][] = array(
          'product_id'      => $_POST['product_id'],
          'catalogue_id'    => $_POST['catalogue_id'],
          'price'           =>$_POST['price'],
          'quantity'        => $_POST['quantity'],
          'product_name'    => $_POST['product_name'],
          'seller_id'       => $_POST['seller_id'],
          'product_image'   => $_POST['product_image'],
          'tax_percent'     => $_POST['tax_percent']
        );
      }
      else
      if($added==1)
      {
        $index=getIndex($_POST['product_id']);
        if($index!=-1)
        {
          $_SESSION[$_REQUEST['s']]['cartdetails'][$index]['quantity']=intval($_SESSION[$_REQUEST['s']]['cartdetails'][$index]['quantity'])+1;
        }
      }
    }
  }
?>

<?php
  $data=array();

  if(isset($_REQUEST['s']))
  {
    $data['username']=$_REQUEST['s'];
  }

  if(isset($_REQUEST['cid']))
  {
    $data['cid']=$_REQUEST['cid'];
  }

  if(isset($_REQUEST['pid']))
  {
    $data['pid']=$_REQUEST['pid'];
  }
  
  $url=DOMAIN.'/rest/seller/getCatalogueProductRest.php';
  $output=getRestApiResponse($url,$data);

  if(isset($output['getproducts']) && $output['getproducts']['response_code']==200)
  {
    if(isset($output['getproducts']['rows']) && $output['getproducts']['rows']!=0)
    {
      if(isset($_REQUEST['s']))
        $_SESSION[$_REQUEST['s']]['sellerdomain']=$_REQUEST['s'];

      $_SESSION[$_REQUEST['s']]['sellerdomain_delivery_charge']=$output['getproducts'][0]['delivery_charge'];
      $_SESSION[$_REQUEST['s']]['sellerdomain_delivery_free_above']=$output['getproducts'][0]['delivery_free_above'];
      $_SESSION[$_REQUEST['s']]['sellerdomain_user_id']=$output['getproducts']['user_id'];
      $_SESSION[$_REQUEST['s']]['sellerdomain_kyc_completed']=$output['getproducts']['kyc_completed'];
      $_SESSION[$_REQUEST['s']]['sellerdomain_accept_online_payments']=$output['getproducts']['accept_online_payments'];
      $_SESSION[$_REQUEST['s']]['sellerdomain_accept_cod_payments']=$output['getproducts']['accept_cod_payments'];

      if(!isset($_SESSION[$_REQUEST['s']]['cartdetails']))
      {
        $_SESSION[$_REQUEST['s']]['cartdetails']=array();
      }
 ?>

<div class="modal fade" id="img-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <i class="fas fa-gallery fa-2x text-warning"></i> Image View
        <button type="button" class="close mr-2" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="" id="image-view" class="w-100">
      </div>
    </div>
  </div>
</div>

<nav class="navbar navbar-light fixed-bottom pl-5 bg-primary">
  <a class="navbar-brand text-white" href="<?php echo DOMAIN; ?>/app/?s=<?php if(isset($_SESSION[$_REQUEST['s']]['sellerdomain'])) echo $_SESSION[$_REQUEST['s']]['sellerdomain']; ?>">
    <img src="https://i.imgur.com/xdbHo4E.png" class="d-inline-block align-top bp-nv-img" alt="" loading="lazy">&nbsp;
    <div class="bp-nv-appname">Home</div>
  </a>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <!-- <a href="cartdetails.php" class="btn btn-sm bg-transparent border-0 text-white"> -->
        <?php 
          if(isset($_SESSION[$_REQUEST['s']]['cartdetails']) && count($_SESSION[$_REQUEST['s']]['cartdetails'])>0)
          {
            echo "<div class='row text-white'>
                    <div class='offset-1'></div>
                    <div class='col-4'>
                      <div class='row'>
                        <div class='col-12'>
                          <span class='bp-nv-cartcount'>".count($_SESSION[$_REQUEST['s']]['cartdetails'])." Items Added</span>
                        </div>
                        <div class='col-12'>
                          <span class='bp-nv-cartcount'><i class='fas fa-rupee-sign'></i> ".getTotalCartPrice()."</span>
                        </div>
                      </div>
                    </div>
                    <div class='col-6 text-right'>
                      <a href='cartdetails.php?s=".$_REQUEST['s']."' class='btn btn-warning bp-nv-shopping-basket mt-2'>Proceed To Checkout <i class='fas fa-shopping-basket'></i></a>
                    </div>
                  </div>";
          }
          else
            echo "<span class='bp-nv-cartcount text-white'>0 Items Added</span>";
        ?>
      <!-- <i class="fas fa-shopping-basket bp-nv-shopping-basket"></i> -->
      <!-- </a> -->
    </li>
  </ul>
</nav>

<div class="container-fluid mb-5">

  <div class="row bp-bname-bcontact">
    <div class="col-6">
      <p><b><i class="fas fa-phone-alt fa-lg"></i></b> - <?php echo $output['getproducts']['mobile']; ?></p>
    </div>
    <div class="col-6 text-right">
      <p><b>Seller</b> - <?php echo $output['getproducts']['business_name']; ?></p>
    </div>
  </div>
  <hr>

  <div class="row">
    <div class="col-12 col-md-4">
      <div class="input-group">
        <input type="text" class="form-control">
        <div class="input-group-append">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-4">

          <div class="input-group">
            <input type="text" class="form-control">
            <div class="input-group-append">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
          </div>

    </div>
  </div>

  <div id="home-product-container">
<?php
  $catalogue_array = array();

  for($i=0; $i < $output['getproducts']['rows']; $i++) 
  {
    $catalogue_array[$i] = $output['getproducts'][(string)$i]['cataloguename'];
  }
  $unique_cat_array = array();
  $unique_cat_array = array_unique($catalogue_array);
  $unique_cat_array = array_values($unique_cat_array);


  for($k=0;$k<count($unique_cat_array);$k++) 
  {
?>
  <div class="row">
    <div class="col-12 mt-2 mb-5">
    <div class="mt-1 <?php echo $unique_cat_array[$k]; ?>">
      <i class="fas fa-minus-square close <?php  echo $unique_cat_array[$k].'_collapse';  ?> collapse-icon"></i>
      <p class="Catalogue_name <?php echo $unique_cat_array[$k].'_name';  ?>" ><?php  echo $unique_cat_array[$k];  ?></p>
       <div class="row <?php echo $unique_cat_array[$k].'_row';  ?>">
          <?php
          for ($i=0; $i <$output['getproducts']['rows']; $i++) 
          {
             $catalogue_name = $output['getproducts'][(string)$i]['cataloguename'];

             if($output['getproducts'][(string)$i]['cataloguename'] == $unique_cat_array[$k] )
             {
                $product_image = $output['getproducts'][(string)$i]['productimage'];
                $product_name = $output['getproducts'][(string)$i]['product_name'];
                $product_id = $output['getproducts'][(string)$i]['product_id'];
                $catalogue_id = $output['getproducts'][(string)$i]['catalogue_id'];
                $product_price = $output['getproducts'][(string)$i]['product_price'];
                $product_category = $output['getproducts'][(string)$i]['product_category'];
                $product_sub_category = $output['getproducts'][(string)$i]['product_sub_category'];
                $tax_percent=$output['getproducts'][(string)$i]['tax_percent'];
                $product_price_currency = $output['getproducts'][(string)$i]['product_price_currency'];
                $product_offer_price = $output['getproducts'][(string)$i]['product_offer_price'];
                $cart_price=0;

                if($product_price!=$product_offer_price)
                  $cart_price=$product_offer_price;
                else
                  $cart_price=$product_price;

                $product_discount_type = $output['getproducts'][(string)$i]['discount_type'];
                $product_discount = $output['getproducts'][(string)$i]['discount'];
          ?>

          <div class="productt-card">
            <?php
              if($product_discount_type=="Flat")
                echo '<div class="badge ml-2">Flat <i class="fas fa-rupee-sign"></i>&nbsp;'.$product_discount.' Off</div>';
              else
              if($product_discount_type=="Percentage")
                echo '<div class="badge ml-2">'.$product_discount.' <i class="fas fa-percent"></i> Off</div>';
              else
                echo '<div class="badge ml-2">HOT</div>';
            ?>
            <div class="product-tumb">
              <img src="..<?php echo $product_image ; ?>" alt="" class="product-image-showclose">
            </div>
            <div class="product-details">
              <p><?php echo $product_name;  ?></p>
              <div class="product-bottom-details">
                <div class="product-price">
                  <?php 
                    if($product_price_currency=="INR")
                      echo '<i class="fas fa-rupee-sign"></i> ';
                    if($product_price!=$product_offer_price)
                      echo '<s>'.$product_price.'</s> '.$product_offer_price;
                    else
                      echo $product_price;
                  ?>
                </div>
                <div class="product-links">
                  <form class="" action="" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <input type="hidden" name="catalogue_id" value="<?php echo $catalogue_id; ?>">
                    <input type="hidden" name="price" value="<?php echo $cart_price; ?>">
                    <input type="hidden" name="quantity" value="<?php echo "1"; ?>">
                    <input type="hidden" name="product_name" value="<?php echo $product_name; ?>">
                    <input type="hidden" name="seller_id" value="<?php echo $output['getproducts']['user_id']; ?>">
                    <input type="hidden" name="product_image" value="<?php echo $product_image; ?>">
                    <input type="hidden" name="tax_percent" value="<?php echo $tax_percent; ?>">
                    <button type="submit" name="add_to_cart" class="btn btn-primary">Add to <i class="fa fa-shopping-cart"></i></button>
                  </form>
                </div>
              </div>
            </div>
          </div>
  <?php
          }
        }
  ?>
</div>
</div>

<script type="text/javascript">

  $(".<?php echo $unique_cat_array[$k].'_collapse'; ?>").click(
    function() 
    {
      $(".<?php echo $unique_cat_array[$k].'_row'; ?>").slideToggle().toggleClass('active');
      
      if($(".<?php echo $unique_cat_array[$k]; ?>").hasClass('active')) 
        $('.<?php echo $unique_cat_array[$k].'_collapse'; ?>').text('Expand');
      else 
        $('.close').text('');

      if($('.<?php echo $unique_cat_array[$k].'_collapse'; ?>').hasClass('fas fa-minus-square'))
      {
        $('.<?php echo $unique_cat_array[$k].'_collapse'; ?>').removeClass('fas fa-minus-square');
        $('.<?php echo $unique_cat_array[$k].'_collapse'; ?>').addClass('fas fa-plus-square');
      }
      else
      if($('.<?php echo $unique_cat_array[$k].'_collapse'; ?>').hasClass('fas fa-plus-square'))
      {
        $('.<?php echo $unique_cat_array[$k].'_collapse'; ?>').removeClass('fas fa-plus-square');
        $('.<?php echo $unique_cat_array[$k].'_collapse'; ?>').addClass('fas fa-minus-square');
      }
  });

  $(".<?php echo $unique_cat_array[$k].'_name'; ?>").click(
    function(){
      $(".<?php echo $unique_cat_array[$k].'_collapse'; ?>").trigger('click');
  });

</script>

    </div>

  </div>

<script>
  $(".product-image-showclose").on("click", 
    function(){
     $('#image-view').attr('src',$(this).attr('src'));
     $('#img-modal').modal('show');
  });
</script>

<?php
    }
?>

</div>

<?php
  }
}
else
{
?>
<div class="container mb-3">
  <div class="row text-center mt-5">
    <div class="col-12 text-danger mt-5">
      <?php
        if(isset($output['getproducts']) && $output['getproducts']['response_code']==404)
          echo '<h4>Seller does not have any products to sell!</h4>';
        else
        if(isset($output['getproducts']) && $output['getproducts']['response_code']==405)
          echo '<h4>'.$output['getproducts']['response_desc'].'</h4>';
        else
          echo '<h4>Seller domain not found!</h4>';
      ?>
    </div>
  </div>
</div>
<?php
}
?>

</div>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

</body>
</html>