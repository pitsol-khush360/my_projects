
        <div class="col-md-12">
<div class="row">
<h1 class="page-header">
   All Orders

</h1>
<h3><?php displaymessage(); ?> </h3>
</div>

<div class="row">
<table class="table table-hover">
    <thead>

      <tr>
           <th>Order Id</th>
           <th>Userid</th>
           <th>Amount</th>
           <th>Transaction</th>
           <th>Currency</th>
           <th>Payment Status</th>
           <th>Action</th>
      </tr>
    </thead>
    <tbody>
        <?php display_order(); ?>
    </tbody>
</table>
</div>