<div class="col-md-12">

<div class="row">
<h1 class="page-header">
   Add Product
<?php add_product(); ?>
</h1>
</div>
               
<form action="" method="post" enctype="multipart/form-data">
<div class="col-md-8">

    <div class="form-group">
      <label for="product_title">Product Title </label>
      <input type="text" name="product_title" id="product_title" class="form-control">
    </div>


    <div class="form-group">
      <label for="product_short_description">Product Short Description</label>
      <textarea name="product_short_description" id="product_short_description" cols="30" rows="5" class="form-control"></textarea>
    </div>

    <div class="form-group">
      <label for="product_description">Product Description</label>
      <textarea name="product_description" id="product_description" cols="30" rows="8" class="form-control"></textarea>
    </div>

    <div class="form-group row">

    <div class="col-xs-3">
      <label for="product_price">Product Price</label>
      <input type="number" name="product_price" id="product_price" class="form-control" size="60">
    </div>
    </div>
</div><!--Main Content-->


<!-- SIDEBAR-->


<aside id="admin_sidebar" class="col-md-4">
    <div class="form-group">
      <input type="submit" name="draft" class="btn btn-warning btn-lg" value="Draft">
      <input type="submit" name="publish" class="btn btn-primary btn-lg" value="Publish">
    </div>

     <!-- Product Categories-->

    <div class="form-group">
         <label for="product_category">Product Category</label>
          <hr>
        <select name="product_category_id" id="product_category" class="form-control">
          <option value="">Select Category</option>
          <?php get_categories_in_add_product(); ?>
        </select>
    </div>

    <!-- Product Brands-->
    <div class="form-group">
      <label for="product_quantity">Product Quantity</label>
      <input min="1" type="number" required name="product_quantity" id="product_quantity" class="form-control">
    </div>
<!-- Product Tags -->

    <!-- Product Image -->
    <div class="form-group">
        <label for="product_image">Product Image</label>
        <input type="file" name="product_image" id="product_image">
    </div>
</aside><!--SIDEBAR-->
</form>
