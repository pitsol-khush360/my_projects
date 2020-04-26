<?php require_once("../resources/config.php");  ?>

<?php 
		if(isset($_GET['search_keywords']))
		{
			$keywords=$_GET['search_keywords'];

			$query_cat=query("select * from categories where cat_title like '%".$keywords."%'");
			confirm($query_cat);

			$query_products=query("select * from products where product_title like '%".$keywords."%' or product_short_description like '%".$keywords."%' or product_description like '%".$keywords."%'");
			confirm($query_products);

			while($row=fetch_array($query_cat))
			{
				$results=<<< results
				<li class="list-style-item"><a href="category.php?id={$row['cat_id']} & cattitle={$row['cat_title']}" style="text-decoration:none;"><span class="glyphicon glyphicon-search"></span>&nbsp;{$row['cat_title']}</a></li>
results;
				echo $results;
			}

			while($row1=fetch_array($query_products))
			{
				$results=<<< results
				<li class="list-style-item"><a href="item.php?id={$row1['product_id']}" style="text-decoration:none;"><span class="glyphicon glyphicon-search"></span>&nbsp;{$row1['product_title']}</a></li>
results;
				echo $results;
			}
		}
?>