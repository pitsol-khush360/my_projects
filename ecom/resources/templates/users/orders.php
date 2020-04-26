<div class="col-md-12">
<div class="row">
<h1 class="page-header">
   Your Orders
</h1>
<h3><?php displaymessage(); ?> </h3>
</div>

<div class="row">
<table class="table table-hover table-bordered">
    <thead>

      <tr>
           <th>Order Id</th>
           <th>Amount</th>
           <th>Transaction</th>
           <th>Currency</th>
           <th>Status</th>
      </tr>
    </thead>
    <tbody>
        <?php display_order_for_user(); ?>
    </tbody>
</table>
</div>
</div>