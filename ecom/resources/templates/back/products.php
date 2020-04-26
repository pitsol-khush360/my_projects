             <div class="row">

<h1 class="page-header">
  <?php displaymessage(); ?>
   All Products

</h1>
<table class="table table-hover table-bordered">


    <thead>

      <tr>
           <th>Id</th>
           <th>Title</th>
           <th>Product Image</th>
           <th>Category</th>
           <th>Price</th>
           <th>Quantity</th>
           <th colspan="2">Action</th>
      </tr>
    </thead>
    <tbody>

      <tr>
            <?php show_products_in_admin(); ?>
        </tr>
      


  </tbody>
</table>
         </div>
