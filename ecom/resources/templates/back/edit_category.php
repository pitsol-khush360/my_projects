<div id="page-wrapper">
     <div class="container-fluid">

<div class="col-md-12">

<div class="row">
<h1 class="page-header">
  <?php update_category(); ?>
   Update Category
</h1>
</div>

<?php 
    if(isset($_GET['id'])) 
    {
        $query=query("select * from categories where cat_id=".escape_string($_GET['id']));
        confirm($query);
        $row=fetch_array($query); 
?>

<form action="" method="post">
<div class="col-md-8">

    <div class="form-group">
        <label for="cat_id">Category Id</label>
        <input type="text" name="cat_id" id="cat_id" class="form-control" value="<?php echo $row['cat_id']; ?>" readonly> 
    </div>

    <div class="form-group">
      <label for="cat_title">Category Title </label>
      <input type="text" name="cat_title" id="cat_title" class="form-control" value="<?php echo $row['cat_title']; ?>">
    </div>

    <input type="submit" name="draft" class="btn btn-warning btn-lg" value="Draft">
    <input type="submit" name="update_category" class="btn btn-primary btn-lg" value="Update Category">
</div>
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
