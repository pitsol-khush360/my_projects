
        <div class="col-md-12">
<div class="row">
<h1 class="page-header">
   Your Reviews

</h1>
<h3><?php displaymessage(); ?> </h3>
</div>

<div class="row">
<table class="table table-hover">
    <thead>

      <tr>
           <th>Review Id</th>
           <th>Product Id</th>
           <th>Review Comment</th>
           <th>Review Star</th>
           <th>Review Time</th>
      </tr>
    </thead>
    <tbody>
        <?php display_reviews_for_user(); ?>
    </tbody>
</table>
</div>