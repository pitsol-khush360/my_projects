<div id="page-wrapper">
     <div class="container-fluid">

<div class="col-md-12">

<div class="row">
<h1 class="page-header">
   Update Product
</h1>
</div>

<?php 
    if(isset($_GET['id'])) 
    {
        $query=query("select * from products where product_id=".escape_string($_GET['id']));
        confirm($query);
        $row=fetch_array($query); 
        $image_path=image_path_products($row['product_image']);

        update_product();
?>

<form action="" method="post" enctype="multipart/form-data">
<div class="col-md-8">

    <div class="form-group">
      <label for="product_title">Product Title </label>
      <input type="text" name="product_title" id="product_title" class="form-control" value="<?php echo $row['product_title']; ?>">
    </div>


    <div class="form-group">
      <label for="product_short_description">Product Short Description</label>
      <textarea name="product_short_description" id="product_short_description" cols="30" rows="5" class="form-control"><?php echo $row['product_short_description']; ?></textarea>
    </div>

    <div class="form-group">
      <label for="product_description">Product Description</label>
      <textarea name="product_description" id="product_description" cols="30" rows="8" class="form-control"><?php echo $row['product_description']; ?></textarea>
    </div>

    <div class="form-group row">

    <div class="col-xs-3">
      <label for="product_price">Product Price</label>
      <input type="number" name="product_price" id="product_price" class="form-control" size="60" value="<?php echo $row['product_price']; ?>">
    </div>
    </div>
</div><!--Main Content-->


<!-- SIDEBAR-->


<aside id="admin_sidebar" class="col-md-4">
    <div class="form-group">
      <input type="submit" name="draft" class="btn btn-warning btn-lg" value="Draft">
      <input type="submit" name="update_product" class="btn btn-primary btn-lg" value="Update">
    </div>

     <!-- Product Categories-->

    <div class="form-group">
         <label for="product_category">Product Category</label>
          <hr>
        <select name="product_category_id" id="product_category" class="form-control">
          <option value="">Select Category</option>
          <?php get_categories_in_add_product(); ?>
        </select>
        <input type="hidden" name="hidden_product_category_id" value="<?php echo $row['product_category_id']; ?>">
    </div>

    <!-- Product Brands-->
    <div class="form-group">
      <label for="product_quantity">Product Quantity</label>
      <input min="1" type="number" required name="product_quantity" id="product_quantity" class="form-control" value="<?php echo $row['product_quantity']; ?>">
    </div>
<!-- Product Tags -->

    <!-- Product Image -->
    <div class="form-group">
        <label for="product_image">Product Image</label><img src="../../resources/<?php echo $image_path; ?>" style="width:60px;height:60px;">
        <input type="file" name="product_image" id="product_image"><br>
        <input type="hidden" name="hidden_product_image" id="hidden_product_image" value="<?php echo $row['product_image']; ?>"><br>
    </div>
</aside><!--SIDEBAR-->
</form>

<?php
        }
        else
        {
            echo "Sorry , No Id Selected !";
        }
?>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="js/plugins/morris/raphael.min.js"></script>
    <script src="js/plugins/morris/morris.min.js"></script>
    <script src="js/plugins/morris/morris-data.js"></script>

</body>

</html>
