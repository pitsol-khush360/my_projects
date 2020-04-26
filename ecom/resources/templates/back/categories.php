
<h3 class="text-success">
    <?php displaymessage(); ?>
</h3>
<h1 class="page-header">
  Product Categories
</h1>

<div class="col-md-4">
    <?php add_category(); ?>
    <form action="" method="post">
        <div class="form-group">
            <label for="cat_title">Title</label>
            <input type="text" name="cat_title" class="form-control" required>
        </div>
        <div class="form-group"> 
            <input type="submit" name="add_category" class="btn btn-primary" value="Add Category">
        </div>      
    </form>
</div>


<div class="col-md-8">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Id</th>
                <th>Title</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>

        <tbody>
            <tr>
            <?php show_categories_in_admin(); ?>
            </tr>
        </tbody>
    </table>
</div>