<?php require_once("../resources/config.php");  ?>

<?php
	if(isset($_GET['userid']) && isset($_GET['saveitemid'])) // save item only for logged-in user.
	{
		$query=query("select * from saved_items where user_id=".$_GET['userid']." and product_id=".$_GET['saveitemid']);
		confirm($query);

		if(mysqli_num_rows($query)!=0) // if already saved,unsave product.
		{
			$query_delete=query("delete from saved_items where user_id=".$_GET['userid']." and product_id=".$_GET['saveitemid']);
			confirm($query_delete);
			// echo "Item Successfully Removed from saved items";
			header("location:saved_items.php?userid={$_GET['userid']}");
		}
		else                             // if not saved,save product.
		{
			$userid=$_GET['userid'];
			$itemid=$_GET['saveitemid'];

			$query_insert=query("insert into saved_items (user_id,product_id) values($userid,$itemid)");
			confirm($query);
			//echo "Item Successfully added in saved items";
			// header("refresh:0;url=index.php#$itemid");
		}
	}
?>