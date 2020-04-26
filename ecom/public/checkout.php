<?php require_once("../resources/config.php"); ?>
<?php include(TEMPLATE_FRONT.DS."header.php"); ?>


<!-- Page Content -->
<div class="container">
<!-- /.row -->
<div class="row">
    <h3 class="text-danger bg-danger text-center"><?php displaymessage(); ?> </h3>
      <h1>Checkout</h1>

<form action="order_status_save.php" method="post">
  <input type="hidden" name="cmd" value="_cart">
  <input type="hidden" name="business" value="business_1@ecom.com">
    <table class="table table-striped">
        <thead>
          <tr>
           <th>Product</th>
           <th>Price</th>
           <th>Quantity</th>
           <th>Sub-total</th>
     
          </tr>
        </thead>
        <tbody>
           <?php cart(); ?>
        </tbody>
    </table>
    <input type="hidden" name="upload" value="1"> <!-- Paypal requires Form Based Integration -->
    <input type="hidden" name="currency_code" value="INR">
    <input type="hidden" name="country" value="IN">
    <button class="btn-info btn-lg" type="submit">Buy Now</button><!--<?php //showpaypalbutton(); ?>-->
</form>



<!--  ***********CART TOTALS*************-->
            
<div class="col-xs-4 pull-right ">
<h2>Cart Totals</h2>

<table class="table table-bordered" cellspacing="0">

<tr class="cart-subtotal">
<th>Items:</th>
<td><span class="amount">
    <?php 
    if(isset($_SESSION['totalproducts']))
        echo $_SESSION['totalproducts'];
    else
        echo 0;
    ?>
</span></td>
</tr>
<tr class="shipping">
<th>Shipping and Handling</th>
<td>Free Shipping</td>
</tr>

<tr class="order-total">
<th>Order Total</th>
<td><strong><span class="amount">
    <?php 
    if(isset($_SESSION['bill']))
        echo $_SESSION['bill'];
    else
        echo 0;
    ?>
</span></strong> </td>
</tr>


</tbody>

</table>

</div><!-- CART TOTALS-->


 </div><!--Main Content-->


<?php include(TEMPLATE_FRONT.DS."footer.php");  ?>