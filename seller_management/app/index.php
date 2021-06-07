<?php include("../config/config.php"); ?>
<?php include("../config/".ENV."_config.php"); ?>

<?php 
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
    $link = "https"; 
else
    $link = "http"; 
  
// Here append the common URL characters. 
$link .= "://"; 
  
// Append the host(domain name, ip) to the URL. 
$link .= $_SERVER['HTTP_HOST']; 
  
// Append the requested resource location to the URL 
$link .= $_SERVER['REQUEST_URI']; 
 
?>

<?php
  $data=array();

  if(isset($_GET['s']))
  {
    $data['username']=$_REQUEST['s'];
  }

  
  $url=DOMAIN.'/rest/seller/getCatalogueProductRest.php';
  $output=getRestApiResponse($url,$data);
  ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--<title><?php echo APP; ?> - Customer Panel</title>-->
  
    <meta name="description" content="Whatsapp Business Simplified"/>
  <meta property="og:title" content="<?php echo $output['getproducts']['business_name'];?> - Customer Page"/>
  <meta property="og:url" content="<?php echo DOMAIN;?>" />
  <meta property="og:site_name" content="<?php echo DOMAIN;?>" />
  <meta property="og:image" content="<?php echo DOMAIN;?>/images/home/logo.png">
  <link rel="shortcut icon" type="image/jpg" href="<?php echo DOMAIN;?>/images/home/logo.png"/>
  
  <link rel="stylesheet" href="../public/font-awesome/css/fontawesome.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="../public/font-awesome/css/all.min.css" rel="stylesheet" type="text/css">
    
  <link rel="stylesheet" href="../public/css/bootstrap/bootstrap.min.css">
  <script src="../public/js/jquery.min.js"></script>
  <script src="../public/js/bootstrap.min.js"></script>

  <link type="text/css" href="<?php echo DOMAIN; ?>/public/css/buyer-panel.css?<?php echo time(); ?>" rel="stylesheet">
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

  function getUniqueCollections($output)
  {
    $unique_cat_array = array();

    if(isset($output) && isset($output['getproducts']['rows']) && $output['getproducts']['rows']!=0) 
    {
      $catalogue_array = array();

      for($i=0; $i < $output['getproducts']['rows']; $i++) 
      {
        $catalogue_array[$i] = $output['getproducts'][(string)$i]['cataloguename'];
      }
      
      $unique_cat_array = array_unique($catalogue_array);
      $unique_cat_array = array_values($unique_cat_array);
    }
    return $unique_cat_array;
  }

  function getSellerCollections($output)
  {
    $cat_options="";
    $cat_options.="<option value='ALL'>ALL</option>";

    $unique_cat_array = array();

    if(isset($output) && isset($output['getproducts']['rows']) && $output['getproducts']['rows']!=0) 
    {
      $unique_cat_array = getUniqueCollections($output);

      foreach ($unique_cat_array as $key => $value) 
      {
        if($value!="" && !is_null($value))
          $cat_options.="<option value='".$value."'>".$value."</option>";
      }
    }
    echo $cat_options;
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
  <div class="modal-dialog modal-dialog-centered modal-lg">
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

<div class="modal fade" id="information-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <i class="fas fa-bell fa-2x text-warning"></i>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="information">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<nav class="navbar navbar-light fixed-bottom pl-2 pl-md-5 bg-primary">
  <a class="navbar-brand text-white" href="<?php echo DOMAIN; ?>/app/?s=<?php if(isset($_SESSION[$_REQUEST['s']]['sellerdomain'])) echo $_SESSION[$_REQUEST['s']]['sellerdomain']; ?>">
    <img src="https://i.imgur.com/xdbHo4E.png" class="d-inline-block align-top bp-nv-img" alt="" loading="lazy">&nbsp;
    <div class="bp-nv-appname">Home</div>
  </a>

  <?php
    $mobile_for_orders="";

    $customer_prev_address=array();
    if(isset($_COOKIE['bts_customer_address']))
    {
      $customer_prev_address=unserialize($_COOKIE['bts_customer_address']);
    }
    
    if(isset($customer_prev_address) && isset($customer_prev_address['customer_address']['customer_mobile']) && $customer_prev_address['customer_address']['customer_mobile']!="")
      $mobile_for_orders=$customer_prev_address['customer_address']['customer_mobile'];
    else
      $mobile_for_orders="";
  ?>

  <ul class="navbar-nav ml-sm-auto ml-lg-2 mr-sm-1 mr-lg-auto">
    <li class="nav-item ml-2 mt-1">
      <?php
        if($mobile_for_orders!="")
        {
      ?>
      <a class="nav-link text-white" href="displayCustomerOrders.php?s=<?php echo $_REQUEST['s']; ?>&mobile=<?php echo $mobile_for_orders; ?>">
        <span class="bp-my-order-link"><i class="fas fa-shopping-basket"></i>&nbsp;&nbsp;My Orders</span>
      </a>
      <?php
        }
        else
        {
      ?>
        <button class="nav-link text-white bg-transparent border-0" disabled title="You don't have any order yet!"><span class="bp-my-order-link"><i class="fas fa-shopping-basket"></i>&nbsp;&nbsp;My Orders</span>
        </button>
      <?php
        }
      ?>
    </li>
  </ul>
  <ul class="navbar-nav ml-lg-auto">
    <li class="nav-item">
        <?php 
          if(isset($_SESSION[$_REQUEST['s']]['cartdetails']) && count($_SESSION[$_REQUEST['s']]['cartdetails'])>0)
          {
            echo "<div class='row text-white'>
                    <div class='offset-lg-1'></div>
                    <div class='col-5 col-lg-4'>
                      <div class='row' id='cart-item-price'>
                        <div class='col-12'>
                          <span class='bp-nv-cartcount'>".count($_SESSION[$_REQUEST['s']]['cartdetails'])." Items Added</span>
                        </div>
                        <div class='col-12'>
                          <span class='bp-nv-cartcount'><i class='fas fa-rupee-sign'></i> ".getTotalCartPrice()."</span>
                        </div>
                      </div>
                    </div>
                    <div class='col-7 col-lg-7'>
                      <a href='displayCustomerCart.php?s=".$_REQUEST['s']."' class='btn btn-warning bp-nv-shopping-basket mt-2'>Proceed To Checkout <i class='fas fa-shopping-basket'></i></a>
                    </div>
                  </div>";
          }
          else
          {
            echo "<div class='row text-white'>
                    <div class='offset-lg-1'></div>
                    <div class='col-5 col-lg-4'>
                      <div class='row pl-2 pl-md-0' id='cart-item-price'>
                        <div class='col-12'>
                          <span class='bp-nv-cartcount'>0 Items Added</span>
                        </div>
                        <div class='col-12'>
                          <span class='bp-nv-cartcount'><i class='fas fa-rupee-sign'></i> ".getTotalCartPrice()."</span>
                        </div>
                      </div>
                    </div>
                    <div class='col-7 col-lg-7 text-md-right'>
                      <a href='displayCustomerCart.php?s=".$_REQUEST['s']."' class='btn btn-warning bp-nv-shopping-basket mt-2'>Proceed To Checkout <i class='fas fa-shopping-basket'></i></a>
                    </div>
                  </div>";
          }
        ?>
    </li>
  </ul>
</nav>

<div class="container-fluid mb-5">

  <div class="row bp-bname-bcontact">
    <div class="col-6">
      <p><b><i class="fas fa-phone-alt fa-lg"></i></b> - <?php echo $output['getproducts']['mobile']; ?></p>
    </div>
    <?php 
     echo '<script> document.title = "'.ucwords($output['getproducts']['business_name']).'- Customer Page" </script>';
      
    ?>
    <div class="col-6 text-right">
      <p><b>Seller</b> - <?php echo $output['getproducts']['business_name']; ?></p>
    </div>
  </div>
  <hr>

  <div class="row">
    <div class="offset-md-5 col-12 col-md-3">
      <select class="form-control bp-filters" id="search-by-collection">
        <?php getSellerCollections($output); ?>
      </select>
    </div>

    <div class="col-12 col-md-4 mt-2 mt-md-0">

          <div class="input-group">
            <input type="text" class="form-control bp-filters" placeholder="Search By Product Name" id="search-by-product-field">
            <div class="input-group-append">
              <span class="input-group-text bg-secondary text-white border-dark" id="search-by-product"><i class="fas fa-search"></i></span>
            </div>
          </div>

    </div>
  </div>

  <div id="home-product-container">
<?php

  $unique_cat_array = array();
  $unique_cat_array = getUniqueCollections($output);

  for($k=0;$k<count($unique_cat_array);$k++) 
  {
?>
  <div class="row">
    <div class="col-12 mt-3 mt-lg-2 mb-5 pl-lg-4">
    <div class="mt-1 <?php echo $unique_cat_array[$k]; ?>">
      <i class="fas fa-minus-square close <?php  echo $unique_cat_array[$k].'_collapse';  ?> collapse-icon"></i>
      <p class="Catalogue_name <?php echo $unique_cat_array[$k].'_name';  ?>" ><?php  echo $unique_cat_array[$k];  ?></p>
       <div class="row mt-3 mt-lg-2 <?php echo $unique_cat_array[$k].'_row';  ?>">
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
                <div class="row w-100">
                  <div class="col-12 col-md-8 col-lg-9">
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
                  </div>
                  <div class="col-12 col-md-4 col-lg-3">
                    <div class="product-links">
                      <!-- <form class="" action="" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="catalogue_id" value="<?php echo $catalogue_id; ?>">
                        <input type="hidden" name="price" value="<?php echo $cart_price; ?>">
                        <input type="hidden" name="quantity" value="<?php echo "1"; ?>">
                        <input type="hidden" name="product_name" value="<?php echo $product_name; ?>">
                        <input type="hidden" name="seller_id" value="<?php echo $output['getproducts']['user_id']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $product_image; ?>">
                        <input type="hidden" name="tax_percent" value="<?php echo $tax_percent; ?>">
                        <button type="submit" name="add_to_cart" class="btn btn-primary mt-3 mt-md-0">Add to <i class="fas fa-shopping-cart"></i></button>
                      </form> -->
                      <button class="btn btn-primary mt-3 mt-md-0 add-to-cart-btn" product_id="<?php echo $product_id; ?>" catalogue_id="<?php echo $catalogue_id; ?>" price="<?php echo $cart_price; ?>" quantity="1" product_name="<?php echo $product_name; ?>" seller_id="<?php echo $output['getproducts']['user_id']; ?>" product_image="<?php echo $product_image; ?>" tax_percent="<?php echo $tax_percent; ?>" s="<?php echo $_REQUEST['s']; ?>">Add to <i class="fas fa-shopping-cart"></i></button>
                    </div>
                  </div>
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

<?php
    }
?>

<script>
  $(".product-image-showclose").on("click", 
    function(){
     $('#image-view').attr('src',$(this).attr('src'));
     $('#img-modal').modal('show');
  });
</script>

<script>
  $(".add-to-cart-btn").on("click",
    function()
    {
      product_id=$(this).attr("product_id");
      catalogue_id=$(this).attr("catalogue_id");
      price=$(this).attr("price");
      quantity=$(this).attr("quantity");
      product_name=$(this).attr("product_name");
      seller_id=$(this).attr("seller_id");
      product_image=$(this).attr("product_image");
      tax_percent=$(this).attr("tax_percent");
      seller=$(this).attr("s");

      var tobesend = 'product_id='+product_id+'&catalogue_id='+catalogue_id+'&price='+price+'&quantity='+quantity+'&product_name='+product_name+'&seller_id='+seller_id+'&product_image='+product_image+'&tax_percent='+tax_percent+'&s='+seller;

      $.ajax({
        type: 'POST',
        url: 'cartHelper.php',
        data: tobesend,
        dataType: 'json',
        success: function(response)
        {   
          if(response.status == 1)
          {
            $('#information').html("<p class='text-success'>Product added to your cart</p>");
            $("#cart-item-price").html(response.cart_item_price);
            $('#information-modal').modal('show');
          }
          else
          if(response.status == 2)
          {
            $('#information').html("<p class='text-success'>This product is already exist in your cart. You increased its quantity.</p>");
            $("#cart-item-price").html(response.cart_item_price);
            $('#information-modal').modal('show');
          }
          else
          {
            $('#information').html("<p class='text-danger'>Unable to add this product to your cart!</p>");
            $('#information-modal').modal('show');
          }
        }
      });
    });
</script>

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
  $('#search-by-product-field').keypress(
    function(event){
      if(event.which==13)
      {
          $('#search-by-product').click();
      }
  });

  $("#search-by-product").on("click",
    function()
    {
      pname=$("#search-by-product-field").val();

      if(pname!="")
      {
        cname=$("#search-by-collection").val();
        var tobesend = 's=<?php echo $_REQUEST['s']; ?>&pname='+pname+'&cname='+cname;

        $.ajax({
          type: 'POST',
          url: 'filterSearchHelper.php',
          data: tobesend,
          dataType: 'json',
          success: function(response)
          {  
            if(response.status == 1)
            {
              $("#home-product-container").html(response.searchdata);
            }
            else
            {
              $('#information').html("<p class='text-danger'>No record found for your search!</p>");
              $('#information-modal').modal('show');
            }
          }
        });

      }
    });

  $("#search-by-collection").change(
    function(){
      cname=$(this).val(); 

      if(cname!="")
      {
        pname="";
        pname=$("#search-by-product-field").val();

        var tobesend = 's=<?php echo $_REQUEST['s']; ?>&cname='+cname+'&pname='+pname;

        $.ajax({
          type: 'POST',
          url: 'filterSearchHelper.php',
          data: tobesend,
          dataType: 'json',
          success: function(response)
          {  
            if(response.status == 1)
            {
              $("#home-product-container").html(response.searchdata);
            }
            else
            {
              $('#information').html("<p class='text-danger'>No record found for your search combination!</p>");
              $('#information-modal').modal('show');
            }
          }
        });

      }

  });

</script>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

</body>
</html>