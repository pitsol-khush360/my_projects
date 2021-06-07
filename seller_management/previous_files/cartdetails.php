<?php include("../config/config.php"); ?>
<?php include("../config/".ENV."_config.php"); ?>

<!DOCTYPE html>
<html>
  <head>
    <title><?php echo APP; ?> - Customer Cart Checkout</title>

    <link rel="stylesheet" href="../public/font-awesome/css/fontawesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../public/font-awesome/css/all.min.css" rel="stylesheet" type="text/css">  
    <link rel="stylesheet" href="../public/css/bootstrap/bootstrap.min.css">
    <link type="text/css" href="<?php echo DOMAIN; ?>/public/css/buyer-cart.css?<?php echo time(); ?>" rel="stylesheet">
    <script src="../public/js/jquery.min.js"></script>
    <script src="../public/js/bootstrap.min.js"></script>
  </head>
<body>

<?php

if(isset($_REQUEST['s']))
{
  $showinformation=0;
  $message="";

  $promocode_minimum_order_amount=0;
  $promocode_discount_type="";
  $promocode_discount_value=0;
  $promo_code="";

  $sdurl="";

  if(isset($_REQUEST['s']) && isset($_SESSION[$_REQUEST['s']]['sellerdomain']))
    $sdurl=DOMAIN.'/app/cartdetails.php?s='.$_SESSION[$_REQUEST['s']]['sellerdomain'];

  if(isset($_POST['apply_promocode']))
  {
    $data['user_id']=$_SESSION[$_REQUEST['s']]['sellerdomain_user_id'];
    $data['promocode']=$_POST['promocode'];

    $url=DOMAIN.'/rest/seller/getPromocodeDetailsRest.php';
    $output=getRestApiResponse($url,$data);
    
    if(isset($output['getpromocode']) && $output['getpromocode']['response_code']==200)
    {
      $promocode_minimum_order_amount=$output['getpromocode'][0]['minimum_order_amount'];
      $promocode_discount_type=$output['getpromocode'][0]['discount_type'];
      $promocode_discount_value=$output['getpromocode'][0]['discount_value'];
      $promo_code=$output['getpromocode'][0]['promo_code'];
    }
    else
    if(isset($output['getpromocode']) && $output['getpromocode']['response_code']==404)
    {
      $showinformation=1;
      $message='<p class="text-danger">Invalid promocode!</p>';
    }
    else
    if(isset($output['getpromocode']) && $output['getpromocode']['response_code']==405)
    {
      $showinformation=1;
      $message='<p class="text-danger">This promocode is expired!</p>';
    }
  }
  else
  if(isset($_POST['inc_qty_product_in_cart']))
  {
    if(count($_SESSION[$_REQUEST['s']]["cartdetails"])!=0)
    {
      foreach($_SESSION[$_REQUEST['s']]['cartdetails'] as $key => $value)
      {
        if(intval($value['product_id'])==intval($_POST['product_id']))
        {
          $_SESSION[$_REQUEST['s']]['cartdetails'][$key]['quantity']=intval($value['quantity'])+1;
          break;
        }
      }
    }
  }
  else
  if(isset($_POST['dec_qty_product_in_cart']))
  {
    if(count($_SESSION[$_REQUEST['s']]["cartdetails"])!=0)
    {
      foreach($_SESSION[$_REQUEST['s']]['cartdetails'] as $key => $value)
      {
        if(intval($value['product_id'])==intval($_POST['product_id']))
        {
          if(intval($value['quantity'])>1)
          {
            $_SESSION[$_REQUEST['s']]['cartdetails'][$key]['quantity']=intval($value['quantity'])-1;
            break;
          }
          else
          if($value['quantity']==1)
          {
            unset($_SESSION[$_REQUEST['s']]['cartdetails'][$key]);
            break;
          }
        }
      }
    }
  }
  else
  if(isset($_POST["delete_product_from_cart"]))
  {
      if(count($_SESSION[$_REQUEST['s']]["cartdetails"])!=0)
      {
        $index=-1;
        foreach($_SESSION[$_REQUEST['s']]['cartdetails'] as $key => $value)
        {
          if(intval($value['product_id'])==intval($_POST['product_id']))
          {
            $index=$key;
            break;
          }
        }

        if($index!=-1)
          unset($_SESSION[$_REQUEST['s']]['cartdetails'][$index]);
      }
  }
  else
  if(isset($_POST['checkout_form']))
  {
    $ck_subarr=array(
      "customer_name" => escape_string(trim($_POST['customer_name'])),
      "customer_email" => escape_string(trim($_POST['customer_email'])),
      "customer_mobile" => escape_string(trim($_POST['customer_mobile'])),
      "customer_address1" => escape_string(trim($_POST['customer_address1'])),
      "customer_address2" => escape_string(trim($_POST['customer_address2'])),
      "customer_state" => $_POST['state'],
      "customer_city" => escape_string(trim($_POST['city'])),
      "customer_pincode" => escape_string(trim($_POST['pincode']))
      );

    $ck_arr=array("customer_address" => $ck_subarr);

    $value = serialize($ck_arr);
    setcookie("bts_customer_address", $value, time()+365*24*60*60);   // cookie index that will expire after 1 year

    $data=array();

    foreach($_POST['cart_products_array'] as $key => $value) 
    {
      array_push($data,array(
            'user_id' => $_POST['user_id'],
            'catalogue_id' => $value['catalogue_id'], 
            'product_id' => $value['product_id'],
            'order_quantity' => $value['order_quantity'],
            'order_amount_total' => $value['order_amount_total'],
            'product_price' => $value['product_price'],
            'tax_amount' => $value['tax_percent']
      ));
    }

    if(isset($_POST['order_type']))
    {
      if($_POST['order_type']=="online")
        $data['order_type']="Prepaid";
      else
      if($_POST['order_type']=="COD")
        $data['order_type']="COD";
    }

    $data['payment_method']=$_POST['order_type'];
    
    $data['user_id']=$_POST['user_id'];
    $data['customer_name']=$_POST['customer_name'];
    $data['customer_email']=$_POST['customer_email'];
    $data['customer_mobile']=$_POST['customer_mobile'];
    $data['delivery_address1']=$_POST['customer_address1'];
    $data['delivery_address2']=$_POST['customer_address2'];
    $data['country']=101;
    $data['state']=$_POST['state'];
    $data['city']=$_POST['city'];
    $data['pincode']=$_POST['pincode'];

    $data['total_items']=$_POST['total_items'];
    $data['promo_code']=$_POST['promo_code'];
    $data['net_amount']=$_POST['net_amount'];
    $data['delivery_charge']=$_POST['delivery_charge'];
    $data['discount']=$_POST['discount'];

    $data['tax_amount']=0;

    $data['sellerdomain']=$_SESSION[$_REQUEST['s']]['sellerdomain'];

    $data['fieldcount']=20;

    $url =DOMAIN."/rest/seller/cartCheckoutCreateOrdersRest.php";
    $defaults = array(
    CURLOPT_URL => $url,
    CURLOPT_POST => true,
    CURLOPT_BINARYTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => array('data'=>serialize($data)),
    );

    $client=curl_init();
    curl_setopt_array($client,$defaults);
    curl_setopt($client,CURLOPT_RETURNTRANSFER,true);

    $output=curl_exec($client);
    curl_close($client);
    $output = json_decode($output,JSON_FORCE_OBJECT);

    if(isset($output['checkout']) && $output['checkout']['response_code']==200 && isset($output['checkout']['order_type']) && $output['checkout']['order_type']=="Prepaid" && isset($output['checkout']['paymentLink']))
    {
        unset($_SESSION[$_REQUEST['s']]['cartdetails']);
        unset($_SESSION[$_REQUEST['s']]['sellerdomain_delivery_charge']);
        unset($_SESSION[$_REQUEST['s']]['sellerdomain_delivery_free_above']);
        unset($_SESSION[$_REQUEST['s']]['sellerdomain_user_id']);
        unset($_SESSION[$_REQUEST['s']]['sellerdomain_kyc_completed']);
        unset($_SESSION[$_REQUEST['s']]['sellerdomain_accept_online_payments']);
        unset($_SESSION[$_REQUEST['s']]['sellerdomain_accept_cod_payments']);
        header("Location: ".$output['checkout']['paymentLink']);
    }
    else
    if(isset($output['checkout']) && $output['checkout']['response_code']==404 && isset($output['checkout']['order_type']) && $output['checkout']['order_type']=="Prepaid")
    {
      // what to show
    }
    else
    if(isset($output['checkout']) && $output['checkout']['response_code']==200 && isset($output['checkout']['order_type']) && $output['checkout']['order_type']=="COD")
    {
        unset($_SESSION[$_REQUEST['s']]['cartdetails']);
        unset($_SESSION[$_REQUEST['s']]['sellerdomain_delivery_charge']);
        unset($_SESSION[$_REQUEST['s']]['sellerdomain_delivery_free_above']);
        unset($_SESSION[$_REQUEST['s']]['sellerdomain_user_id']);
        unset($_SESSION[$_REQUEST['s']]['sellerdomain_kyc_completed']);
        unset($_SESSION[$_REQUEST['s']]['sellerdomain_accept_online_payments']);
        unset($_SESSION[$_REQUEST['s']]['sellerdomain_accept_cod_payments']);

        redirect("displayCustomerOrderConfirmation.php?orderplaced=1&orderid=".$output['checkout']['orderId']."&sellerdomain=".$_REQUEST['s']);
    }
    else
    {
      $showinformation=1;
      $message='<p class="text-danger">Unable to place your order!</p>';
    }
  }
?>

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

<?php
  if($showinformation==1)
    echo '<script>
        $("#information").html(\''.$message.'\');
        $("#information-modal").modal("show");
      </script>';
?>

<?php
  if(isset($_SESSION[$_REQUEST['s']]['cartdetails']) && count($_SESSION[$_REQUEST['s']]['cartdetails'])!=0)
  {
?>

  <nav class="navbar navbar-light fixed-bottom pl-5 bg-primary">
    <a class="navbar-brand text-white" href="<?php echo DOMAIN; ?>/app/?s=<?php if(isset($_SESSION[$_REQUEST['s']]['sellerdomain'])) echo $_SESSION[$_REQUEST['s']]['sellerdomain']; ?>">
      <img src="https://i.imgur.com/xdbHo4E.png" class="d-inline-block align-top bp-nv-img" alt="" loading="lazy">&nbsp;
      <div class="bp-nv-appname">Home</div>
    </a>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        
      </li>
    </ul>
  </nav>

    <div class="container-fluid mb-5">
      <div class="row mt-3">
        <div class="col-12">
          <a href="<?php echo DOMAIN; ?>/app/?s=<?php if(isset($_SESSION[$_REQUEST['s']]['sellerdomain'])) echo $_SESSION[$_REQUEST['s']]['sellerdomain']; ?>" class="btn btn-success" id="home-btn">Back To Products</a>
        </div>
      </div>
      <!-- Heading -->
      <div class="row mt-5">
        <div class="col-12">
          <h3 class="text-center">Checkout</h3>
        </div>
      </div>

        <div class="row mt-4">
            <div class="bp-cart">

              <div class="row">
                <div class="col-6">
                  <h4><span class="text-muted">Your Cart</span></h4>
                </div>
                <div class="col-6 text-right">
                  <h4><span class="badge badge-secondary badge-pill"><?php echo count($_SESSION[$_REQUEST['s']]['cartdetails']); ?></span></h4>
                </div>
              </div>

              <!-- Cart -->
              <div class="row mt-3">
                <div class="col-12">
                  <ul class="list-group mb-3">
                  <?php
                    $total_indivisual_price=0;
                    $total_price = 0;
                    $delivery_charge=0;
                    $discount=0;
                    $k=0;

                    $cart_products_array=array();

                    foreach($_SESSION[$_REQUEST['s']]['cartdetails'] as $key => $value)
                    {
                  ?>
                    <li class="list-group-item">
                     <div class="row item">
                       <div class="col-4 align-self-center">
                        <img class="img-fluid" src="..<?php echo $value['product_image']; ?>">
                       </div>
                       <div class="offset-1 col-7">
                            <div class="row bp-nml-text">
                              <div class="col-12"><?php echo $value['product_name']; ?></div>
                            </div>
                            <div class="row mt-3 bp-nml-text">
                              <div class="col-12">
                                <div class="row">
                                  <div class="col-2">
                                     <form action="<?php echo $sdurl; ?>" method="post">
                                        <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?> ">
                                        <?php
                                          if(intval($value['quantity'])>1)
                                          {
                                        ?>
                                        <button class="btn btn-warning btn-md qty-buttons text-md-right" type="submit" name="dec_qty_product_in_cart"><i class="fas fa-minus"></i></button>
                                        <?php
                                          }
                                          else
                                          if(intval($value['quantity'])==1)
                                          {
                                        ?>
                                        <button class="btn btn-secondary btn-md qty-buttons text-md-right" type="submit" name="dec_qty_product_in_cart" disabled><i class="fas fa-minus"></i></button>
                                        <?php
                                          }
                                        ?>
                                     </form>
                                   </div>
                                   <div class="col-3">
                                    <div class="border border-secondary text-center mt-2"><?php echo $value['quantity']; ?></div>
                                  </div>
                                  <div class="col-2">
                                     <form action="<?php echo $sdurl; ?>" method="post">
                                        <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?> ">
                                        <button class="btn btn-success btn-sm qty-buttons text-md-left" type="submit" name="inc_qty_product_in_cart"><i class="fas fa-plus"></i></button>
                                     </form>
                                   </div>
                                  <div class="col-5 text-right">
                                     <form action="<?php echo $sdurl; ?>" method="post">
                                        <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?> ">
                                        <button class="btn btn-danger btn-sm qty-buttons" type="submit" name="delete_product_from_cart"><i class="fas fa-trash"></i></button>
                                     </form>
                                   </div>
                                </div>
                              </div>
                            </div>
                           <div class="row mt-3 bp-nml-text">
                            <div class="col-5"><b>Net Price</b></div>
                            <div class="col-7 text-right"><i class="fas fa-rupee-sign text-muted"></i>&nbsp;<?php echo $value['price']; ?>&nbsp;<i class="fa-lg">&times;</i>&nbsp;<?php echo $value['quantity']; ?></div>
                           </div>
                           <div class="row mt-2 bp-nml-text">
                            <div class="col-6"></div>
                            <div class="col-6 text-right"><i class="fas fa-rupee-sign text-muted"></i>&nbsp;<?php echo $value['price'] * $value['quantity'] ; ?></div>
                            </div>
                          <?php 
                            $total_indivisual_price=$value['price'] * $value['quantity'];
                            $total_price += $value['price'] * $value['quantity'];  
                          ?>
                       </div>
                     </div>
                    </li>
                  <?php
                      array_push($cart_products_array,
                        array(  'user_id' => 1, 
                                'catalogue_id' => $value['catalogue_id'], 
                                'product_id' => $value['product_id'],
                                'order_quantity' => $value['quantity'],
                                'order_amount_total' => $total_indivisual_price,
                                'product_price' => $value['price'],
                                'tax_percent' => $value['tax_percent']
                              ));
                    }
                  ?>

                  <?php
                    if($promocode_discount_type=="Flat" || $promocode_discount_type=="Percentage")
                    {
                      if($total_price>=$promocode_minimum_order_amount)
                      {
                        if($promocode_discount_type=="Flat")
                        {
                          $total_price-=$promocode_discount_value;
                          $discount=$promocode_discount_value;
                          echo '<li class="list-group-item bg-light">
                                 <div class="col-12 text-success">
                                    <div class="row bp-nml-text">
                                      <div class="col-6">
                                        <h6 class="my-0 bp-nml-text">Discount</h6>
                                      </div>
                                      <div class="col-6 text-right">
                                        <span><i class="fas fa-minus"></i>&nbsp;&nbsp;<i class="fas fa-rupee-sign"></i>&nbsp;<b>'.$promocode_discount_value.'</b></span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12">
                                  <form action="" method="post">
                                    <button type="submit" name="remove_promocode" class="btn bg-transparent border-0 bp-nml-text">
                                      <p class="mt-2"><span class="badge badge-success badge-pill">'.$promo_code.'</span><sup><span class="badge badge-pill badge-danger ml-0"><i class="fa-2x">&times;</i></span></sup></p>
                                    </button>
                                  </form>
                                 </div>
                               </li>';

                          $message='<p class="text-success">Promocode applied successfully!</p>';
                          echo '<script>
                            $("#information").html(\''.$message.'\');
                            $("#information-modal").modal("show");
                          </script>';
                        }
                        else
                        if($promocode_discount_type=="Percentage")
                        {
                          $per_discount=($total_price/100)*$promocode_discount_value;
                          $per_discount=round($per_discount,2);
                          $discount=$per_discount;
                          $total_price-=$per_discount;

                          // echo '<li class="list-group-item d-flex justify-content-between bg-light">
                          //        <div class="text-success">
                          //          <h6 class="my-0">Discount</h6>
                          //          <span class="badge badge-secondary badge-pill"><small>'.$promo_code.'</small></span>
                          //        </div>
                          //        <span class="text-success"><i class="fas fa-minus"></i>&nbsp;&nbsp;<i class="fas fa-rupee-sign"></i>&nbsp;<b>'.$per_discount.'</b></span>
                          //      </li>';

                          echo '<li class="list-group-item bg-light">
                                 <div class="col-12 text-success">
                                    <div class="row bp-nml-text">
                                      <div class="col-6">
                                        <h6 class="my-0 bp-nml-text">Discount</h6>
                                      </div>
                                      <div class="col-6 text-right">
                                        <span><i class="fas fa-minus"></i>&nbsp;&nbsp;<i class="fas fa-rupee-sign"></i>&nbsp;<b>'.$per_discount.'</b></span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12">
                                  <form action="" method="post">
                                    <button type="submit" name="remove_promocode" class="btn bg-transparent border-0 bp-nml-text">
                                      <p class="mt-2"><span class="badge badge-success badge-pill">'.$promo_code.'</span><sup><span class="badge badge-pill badge-danger ml-0"><i class="fa-2x">&times;</i></span></sup></p>
                                    </button>
                                  </form>
                                 </div>
                               </li>';

                          $message='<p class="text-success">Promocode applied successfully!</p>';
                          echo '<script>
                            $("#information").html(\''.$message.'\');
                            $("#information-modal").modal("show");
                          </script>';
                        }
                      }
                      else
                      {
                        $promo_code="";
                        $message='<p class="text-danger">This promocode is only applicable on order greater than '.$promocode_minimum_order_amount.'</p>';
                        echo '<script>
                          $("#information").html(\''.$message.'\');
                          $("#information-modal").modal("show");
                        </script>';
                      }
                    }
                  ?>
                   <li class="list-group-item bp-nml-text">
                      <div class="row">
                        <div class="col-12">
                          <div class="row">
                            <div class="col-6">
                              <span>Delivery Charge (INR)</span>
                            </div>
                            <div class="col-6 text-right">
                              <strong><i class="fas fa-rupee-sign"></i>&nbsp;
                                <?php
                                  if($total_price>$_SESSION[$_REQUEST['s']]['sellerdomain_delivery_free_above'])
                                  {
                                    $delivery_charge=0;
                                    echo $delivery_charge;
                                  }
                                  else
                                  {
                                    $total_price+=$_SESSION[$_REQUEST['s']]['sellerdomain_delivery_charge'];
                                    $delivery_charge=$_SESSION[$_REQUEST['s']]['sellerdomain_delivery_charge'];
                                    echo $delivery_charge;
                                  }
                                ?>
                              </strong>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 mt-2">
                          <div class="row">
                            <div class="col-6">
                              <span>Total (INR)</span>
                            </div>
                            <div class="col-6 text-right">
                              <strong><i class="fas fa-rupee-sign"></i>&nbsp;<?php echo $total_price; ?></strong>
                            </div>
                          </div>
                        </div>
                      </div>
                   </li>
                  </ul>
                </div>
              </div>

         <!-- Promo code -->
         <?php
          if($promo_code=="")
          {
         ?>
         <div class="row">
          <div class="col-12">
           <form action="" method="post" class="card p-2">
             <div class="input-group">
               <input type="text" name="promocode" class="form-control" placeholder="Promocode" oninput="this.value=this.value.toUpperCase();" required>
               <div class="input-group-append">
                 <input type="submit" name="apply_promocode" class="btn btn-success btn-md waves-effect m-0" value="Apply">
               </div>
             </div>
           </form>
          </div>
         </div>
         <!-- Promo code -->
         <?php
            }
          ?>

       </div>

           <div class="bp-checkoutform">

            <div class="row">
              <div class="col-12 text-right">
                <h4><span class="text-muted">Chekout Details</span></h4>
              </div>
            </div>
             <!--Card-->

            <div class="row mt-3">
              <div class="col-12">
               <div class="card mb-5">

                <?php
                  $customer_prev_address=array();
                  if(isset($_COOKIE['bts_customer_address']))
                  {
                    $customer_prev_address=unserialize($_COOKIE['bts_customer_address']);
                  }
                ?>
                 <!--Card content-->
                  <form class="card-body" method="post" action="<?php echo $sdurl; ?>" autocomplete="on">

                    <div class="row mt-1">
                      <div class="bp-checkoutform-col">
                        <div class="form-group">
                          <label for="yourname">Your Name</label>
                            <div class="input-group">
                             <div class="input-group-prepend">
                               <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                             </div>
                             <input type="text" class="form-control" name="customer_name" placeholder="Your Name"  id="yourname" value="<?php 
                              if(isset($customer_prev_address) && isset($customer_prev_address['customer_address']['customer_name']))
                                echo $customer_prev_address['customer_address']['customer_name'];
                              else
                                echo "";
                             ?>" required>
                            </div>
                        </div>
                      </div>

                      <div class="bp-checkoutform-col">
                        <div class="form-group">
                          <label for="email">Email</label>
                            <div class="input-group">
                             <div class="input-group-prepend">
                               <span class="input-group-text" id="basic-addon2"><i class="fas fa-envelope"></i></span>
                             </div>
                             <input type="email" id="email" class="form-control" name="customer_email" placeholder="xyz@example.com" value="
                             <?php 
                              if(isset($customer_prev_address) && isset($customer_prev_address['customer_address']['customer_email']))
                                echo $customer_prev_address['customer_address']['customer_email'];
                              else
                                echo "";
                             ?>">
                            </div>
                        </div>
                      </div>
                    </div>

                    <div class="row mt-1">
                      <div class="bp-checkoutform-col">
                        <div class="form-group">
                          <label for="mobile">Mobile Number</label>
                          <div class="input-group">
                           <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon3">+91</span>
                           </div>
                           <input type="text" id="mobile" class="form-control" name="customer_mobile" placeholder="Your Mobile Number" 
                           value="<?php 
                            if(isset($customer_prev_address) && isset($customer_prev_address['customer_address']['customer_mobile']))
                                echo $customer_prev_address['customer_address']['customer_mobile'];
                            else
                              echo ""; ?>" required minlength="10" maxlength="10">
                         </div>
                        </div>
                      </div>

                      <div class="bp-checkoutform-col">
                        <div class="form-group">
                          <label for="address1">Address Line 1</label>
                          <div class="input-group">
                           <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon4"><i class="fas fa-home"></i></span>
                           </div>
                           <input type="text" id="address1" class="form-control" name="customer_address1" placeholder="Your Flat/Building No." value="<?php 
                              if(isset($customer_prev_address) && isset($customer_prev_address['customer_address']['customer_address1']))
                                echo $customer_prev_address['customer_address']['customer_address1'];
                              else
                                echo "";
                            ?>" required>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row mt-1">
                      <div class="bp-checkoutform-col">
                        <div class="form-group">
                          <label for="address2">Address Line 2</label>
                          <div class="input-group">
                           <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon5"><i class="fas fa-home"></i></span>
                           </div>
                           <input type="text" id="address2" class="form-control" name="customer_address2" placeholder="Your Area/Locality/Sector" value="<?php 
                              if(isset($customer_prev_address) && isset($customer_prev_address['customer_address']['customer_address2']))
                                echo $customer_prev_address['customer_address']['customer_address2'];
                              else
                                echo "";
                             ?>">
                         </div>
                        </div>
                      </div>

                      <div class="bp-checkoutform-col">
                        <div class="form-group">
                          <label for="state">State</label>
                           <select class="custom-select d-block w-100" name="state" id="state" required>
                              <?php 
                                if(isset($customer_prev_address) && isset($customer_prev_address['customer_address']['customer_state']) && $customer_prev_address['customer_address']['customer_state']!="")
                                  getStates($customer_prev_address['customer_address']['customer_state']);
                                else
                                  getStates("");
                             ?>
                           </select>
                        </div>
                      </div>
                    </div>

                    <div class="row mt-1">
                      <div class="bp-checkoutform-col">
                        <div class="form-group">
                          <label for="city">City</label>
                            <div class="input-group">
                             <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon5"><i class="fas fa-home"></i></span>
                             </div>
                             <input type="text" id="city" class="form-control" name="city" placeholder="Your City" required value="<?php 
                              if(isset($customer_prev_address) && isset($customer_prev_address['customer_address']['customer_city']))
                                echo $customer_prev_address['customer_address']['customer_city'];
                              else
                                echo "";
                             ?>">
                            </div>
                        </div>
                      </div>

                      <div class="bp-checkoutform-col">
                        <div class="form-group">
                          <label for="zip">Zip</label>
                          <input type="text" class="form-control cstm-input" name="pincode" id="zip" placeholder="Pincode" required value="<?php 
                              if(isset($customer_prev_address) && isset($customer_prev_address['customer_address']['customer_pincode']))
                                echo $customer_prev_address['customer_address']['customer_pincode'];
                              else
                                echo "";
                             ?>">
                        </div>
                      </div>
                    </div>

                    <div class="row mt-3">
                      <div class="col-12 mt-1">
                        <div class="form-group">
                          <label for="order_type">Payment Type</label>
                          <div class="row ml-1 mr-1">
                            <?php
                                if(isset($_SESSION[$_REQUEST['s']]['sellerdomain_kyc_completed']) && $_SESSION[$_REQUEST['s']]['sellerdomain_kyc_completed']==1 && isset($_SESSION[$_REQUEST['s']]['sellerdomain_accept_online_payments']) && $_SESSION[$_REQUEST['s']]['sellerdomain_accept_online_payments']==1)
                                {
                            ?>
                            <div class="col-8 mt-3">
                              <div class="form-check-inline">
                                <label class="bp-nml-text">
                                    <div class="row">
                                        <div class="col-2">
                                            <input type="radio" class="form-check-input" name="order_type" value="online" required>
                                        </div>
                                        <div class="col-10">
                                            Pay Now (Online)
                                        </div>
                                  </div>
                                </label>
                              </div>
                            </div>
                            <?php
                              }

                              if(isset($_SESSION[$_REQUEST['s']]['sellerdomain_accept_cod_payments']) && $_SESSION[$_REQUEST['s']]['sellerdomain_accept_cod_payments']==1)
                              {
                            ?>
                            <div class="col-4 mt-3">
                              <div class="form-check-inline">
                                <label class="bp-nml-text">
                                    <div class="row">
                                        <div class="col-5">
                                            <input type="radio" class="form-check-input" name="order_type" value="COD" required>
                                        </div>
                                        <div class="col-7">
                                            COD
                                        </div>
                                  </div>
                                </label>
                              </div>
                            </div>
                            <?php
                              }
                            ?>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-12">
                        <input type="hidden" name="delivery_charge" value="<?php echo $delivery_charge; ?>">
                        <input type="hidden" name="discount" value="<?php echo $discount; ?>">
                        <input type="hidden" name="total_items" value="<?php echo count($_SESSION[$_REQUEST['s']]['cartdetails']); ?>">
                        <input type="hidden" name="net_amount" value="<?php echo $total_price; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $_SESSION[$_REQUEST['s']]['sellerdomain_user_id']; ?>">
                        <input type="hidden" name="promo_code" value="<?php echo $promo_code; ?>">

                        <?php
                          foreach($cart_products_array as $key1 => $value1)
                          {
                            foreach($value1 as $key2 => $value2)
                            {
                              echo '<input type="hidden" name="cart_products_array['.$key1.']['.$key2.']" value="'.$value2.'">';
                            }
                          }
                        ?>
                        <button class="btn btn-primary btn-lg btn-block" name="checkout_form" id="checkout-btn">Checkout</button>
                      </div>
                    </div>
                  </form>
                </div>
                <!--/.Card-->
               </div>
              </div>
            </div>
            <!--Grid column-->
     </div>
     <!--Grid row-->

<script>
  $('input:radio[name=order_type]').click(
    function()
    {
      type=$(this).val();
      
      if(type=="online")
        $("#checkout-btn").text("Proceed To Pay");
      else
      if(type=="COD")
        $("#checkout-btn").text("Checkout");
    });
</script>

   </div>

<?php
  }
  else
  {
?>
  <div class="container">
    <div class="row text-center mt-5">
      <div class="col-12 mt-5">
        <p><i class="fas fa-shopping-cart fa-5x"></i></p>
      </div>
      <div class="col-12 text-info mt-2">
        <h3>Your Cart Is Empty</h3>
      </div>
      <div class="col-12 text-danger mt-5">
        <?php 
          if(isset($_SESSION[$_REQUEST['s']]['sellerdomain']) && $_SESSION[$_REQUEST['s']]['sellerdomain']!="")
          {
        ?>
          <a href="<?php echo DOMAIN; ?>/app/?s=<?php echo $_SESSION[$_REQUEST['s']]['sellerdomain']; ?>" class="btn btn-primary btn-lg">Continue Shopping</a>
        <?php
          }
        ?>
      </div>
    </div>
  </div>
<?php
  }
}
else
{
?>
<div class="container">
  <div class="row text-center mt-5">
    <div class="col-12 mt-5">
      <p><i class="fas fa-exclamation-triangle text-danger fa-5x"></i></p>
    </div>
    <div class="col-12 text-danger mt-2">
      <h4>Invalid Request! Seller Domain Not Found</h4>
    </div>
  </div>
</div>
<?php
}
?>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

 </body>
</html>
