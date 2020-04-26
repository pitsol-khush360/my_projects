<?php require_once("../resources/config.php");  ?>
<?php include(TEMPLATE_FRONT.DS."header.php");  ?>

<div class="container">
	<div class="row">
<?php
	$query_saved=query("select * from saved_items where user_id=".$_GET['userid']);
	confirm($query_saved);

	while($row_saved=fetch_array($query_saved))
	{
		$query_product=query("select * from products where product_id=".$row_saved['product_id']);
		confirm($query_product);

		$row=fetch_array($query_product);

		$des=$row['product_short_description'];
 		$c=strlen($des);

 		if($c>=20)
 		{
 			$sub=substr($des,0,30);
 			$sub=$sub."...";
 		}
 		else
 			$sub=$des;

		

		$image_path=image_path_products($row['product_image']);

		$products = <<< any
 		<div class="col-sm-4 col-lg-4 col-md-4">
                        <div class="thumbnail">
                        <a href="item.php?id={$row['product_id']}">
                            <img src="../resources/{$image_path}" alt="" style="width:100%;height:170px;">
                        </a>
                            <div class="caption">
                                <h4 class="pull-right"><span id="{$row['product_id']}" class="glyphicon glyphicon-heart heart_color_red"></span></h4>
                                <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                                </h4>
                                <p>&#8377;{$row['product_price']}</p>
                                <p>$sub</p>
                                <a class="btn btn-primary" target="" href="../resources/cart.php?add={$row['product_id']}">Add To Cart</a>
                            </div>
                        </div>
                    </div>
any;
                    print $products;
	}
?>
	</div>
</div>

<script type="text/javascript">
    $("span").on("click",
            function()
            {
                // if($(this).attr('style').color=='grey')
                // if($(this).css('color')=='grey')

                if($(this).hasClass("heart_color_grey"))
                {
                    $(this).removeClass("heart_color_grey");
                    $(this).addClass("heart_color_red");
                }
                else
                    if($(this).hasClass("heart_color_red"))
                    {
                        $(this).removeClass("heart_color_red")
                        $(this).addClass("heart_color_grey");
                    }

                productid=$(this).attr("id");

                //alert($productid+" "+<?php //echo $userid; ?>);

                $.get("update_saved_items.php?userid="+<?php echo $userid; ?>+"&saveitemid="+productid,
                    function(data,message)
                    {
                        window.location.assign(window.location.href);
                    });
            }
        );
</script>