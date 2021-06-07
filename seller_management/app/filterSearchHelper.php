<?php 
	require_once("../config/config.php"); 
	require_once("../config/".ENV."_config.php");
?>

<?php
  function getUniqueCollections($output)
  {
    $unique_cat_array = array();

    if(isset($output) && isset($output['getproducts']['rows']) && $output['getproducts']['rows']!=0) 
    {
      $catalogue_array = array();

      for($i=0; $i < $output['getproducts']['rows']; $i++) 
      {
        $catalogue_array[$i] = $output['getproducts'][(string)$i]['cataloguename'];
      }
      
      $unique_cat_array = array_unique($catalogue_array);
      $unique_cat_array = array_values($unique_cat_array);
    }
    return $unique_cat_array;
  }
?>

<?php 
	if(isset($_POST['cname']) || isset($_POST['pname']))
	{
		$response_code=0;
		$record="";

		$data=array();

		$data['username']=$_POST['s'];

		if((isset($_POST['cname']) && $_POST['cname']!="ALL") && (isset($_POST['pname']) && $_POST['pname']!=""))
		{
			$data['pname']=$_POST['pname'];
			$data['cname']=$_POST['cname'];
		}
		else
		if((isset($_POST['cname']) && $_POST['cname']!="ALL") && (isset($_POST['pname']) && $_POST['pname']==""))
		{
			$data['pname']="";
			$data['cname']=$_POST['cname'];
		}
		else
		if((isset($_POST['cname']) && $_POST['cname']=="ALL") && (isset($_POST['pname']) && $_POST['pname']!=""))
		{
			$data['cname']="";
			$data['pname']=$_POST['pname'];
		}

		$url=DOMAIN.'/rest/seller/getCatalogueProductRest.php';
  		$output=getRestApiResponse($url,$data);

	  	if(isset($output['getproducts']) && $output['getproducts']['response_code']==200)
	  	{
	    	if(isset($output['getproducts']['rows']) && $output['getproducts']['rows']!=0)
	    	{
	    	  $unique_cat_array = array();
			  $unique_cat_array = getUniqueCollections($output);

			  for($k=0;$k<count($unique_cat_array);$k++) 
			  {
			  	// UI Part
			  	$record.='<div class="row">
    						<div class="col-12 mt-3 mt-lg-2 mb-5 pl-lg-4">
    							<div class="mt-1 '.$unique_cat_array[$k].'">
      								<i class="fas fa-minus-square close '.$unique_cat_array[$k].'_collapse collapse-icon"></i>
      								<p class="Catalogue_name '.$unique_cat_array[$k].'_name">'.$unique_cat_array[$k].'</p>
       									<div class="row mt-3 mt-lg-2 '.$unique_cat_array[$k].'_row">';

       									for($i=0; $i <$output['getproducts']['rows']; $i++) 
          								{
								            $catalogue_name = $output['getproducts'][(string)$i]['cataloguename'];

								            if($output['getproducts'][(string)$i]['cataloguename'] == $unique_cat_array[$k] )
								            {
								                $product_image = $output['getproducts'][(string)$i]['productimage'];
								                $product_name = $output['getproducts'][(string)$i]['product_name'];
								                $product_id = $output['getproducts'][(string)$i]['product_id'];
								                $catalogue_id = $output['getproducts'][(string)$i]['catalogue_id'];
								                $product_price = $output['getproducts'][(string)$i]['product_price'];
								                $product_category = $output['getproducts'][(string)$i]['product_category'];
								                $product_sub_category = $output['getproducts'][(string)$i]['product_sub_category'];
								                $tax_percent=$output['getproducts'][(string)$i]['tax_percent'];
								                $product_price_currency = $output['getproducts'][(string)$i]['product_price_currency'];
								                $product_offer_price = $output['getproducts'][(string)$i]['product_offer_price'];
								                $cart_price=0;

								                if($product_price!=$product_offer_price)
								                  $cart_price=$product_offer_price;
								                else
								                  $cart_price=$product_price;

								                $product_discount_type = $output['getproducts'][(string)$i]['discount_type'];
								                $product_discount = $output['getproducts'][(string)$i]['discount'];

								                // UI Part
          										$record.='<div class="productt-card">';

									            if($product_discount_type=="Flat")
									            	$record.='<div class="badge ml-2">Flat <i class="fas fa-rupee-sign"></i>&nbsp;'.$product_discount.' Off</div>';
									            else
									            if($product_discount_type=="Percentage")
									            	$record.='<div class="badge ml-2">'.$product_discount.' <i class="fas fa-percent"></i> Off</div>';
									            else
									            	$record.='<div class="badge ml-2">HOT</div>';

									            $record.='<div class="product-tumb">
									              <img src="..'.$product_image.'" alt="" class="product-image-showclose">
									            </div>
									            <div class="product-details">
									              <p>'.$product_name.'</p>
									              <div class="product-bottom-details">
									                <div class="row w-100">
									                  <div class="col-12 col-md-8 col-lg-9">
									                    <div class="product-price">';

						                        if($product_price_currency=="INR")
						                          $record.='<i class="fas fa-rupee-sign"></i> ';
						                        if($product_price!=$product_offer_price)
						                          $record.='<s>'.$product_price.'</s> '.$product_offer_price;
						                        else
						                          $record.=$product_price;

						                      	// UI Part

							                    $record.='</div>
							                  		   </div>
							                  		<div class="col-12 col-md-4 col-lg-3">
							                    		<div class="product-links">
							                      			<button class="btn btn-primary mt-3 mt-md-0 add-to-cart-btn" product_id="'.$product_id.'" catalogue_id="'.$catalogue_id.'" price="'.$cart_price.'" quantity="1" product_name="'.$product_name.'" seller_id="'.$output['getproducts']['user_id'].'" product_image="'.$product_image.'" tax_percent="'.$tax_percent.'" s="'.$_POST['s'].'">Add to <i class="fas fa-shopping-cart"></i></button>
								                    	</div>
								                  	</div>
									                </div>
									              </div>
									            </div>
									          </div>';
									        }
									    }

				$record.='</div>
						</div>

				<script type="text/javascript">

				  $(".'.$unique_cat_array[$k].'_collapse").click(
				    function() 
				    {
				      $(".'.$unique_cat_array[$k].'_row").slideToggle().toggleClass("active");
				      
				      if($(".'.$unique_cat_array[$k].'").hasClass("active")) 
				        $(".'.$unique_cat_array[$k].'_collapse").text("Expand");
				      else 
				        $(".close").text("");

				      if($(".'.$unique_cat_array[$k].'_collapse").hasClass("fas fa-minus-square"))
				      {
				        $(".'.$unique_cat_array[$k].'_collapse").removeClass("fas fa-minus-square");
				        $(".'.$unique_cat_array[$k].'_collapse").addClass("fas fa-plus-square");
				      }
				      else
				      if($(".'.$unique_cat_array[$k].'_collapse").hasClass("fas fa-plus-square"))
				      {
				        $(".'.$unique_cat_array[$k].'_collapse").removeClass("fas fa-plus-square");
				        $(".'.$unique_cat_array[$k].'_collapse").addClass("fas fa-minus-square");
				      }
				  });

				  $(".'.$unique_cat_array[$k].'_name").click(
				    function(){
				      $(".'.$unique_cat_array[$k].'_collapse").trigger("click");
				  });

				</script>

				    </div>
				  </div>';
			  }

			$record.='<script>
				  $(".product-image-showclose").on("click", 
				    function(){
				     $("#image-view").attr("src",$(this).attr("src"));
				     $("#img-modal").modal("show");
				  });
				</script>

				<script>
				  $(".add-to-cart-btn").on("click",
				    function()
				    {
				      product_id=$(this).attr("product_id");
				      catalogue_id=$(this).attr("catalogue_id");
				      price=$(this).attr("price");
				      quantity=$(this).attr("quantity");
				      product_name=$(this).attr("product_name");
				      seller_id=$(this).attr("seller_id");
				      product_image=$(this).attr("product_image");
				      tax_percent=$(this).attr("tax_percent");
				      seller=$(this).attr("s");

				      var tobesend = "product_id="+product_id+"&catalogue_id="+catalogue_id+"&price="+price+"&quantity="+quantity+"&product_name="+product_name+"&seller_id="+seller_id+"&product_image="+product_image+"&tax_percent="+tax_percent+"&s="+seller;

				      $.ajax({
				        type: "POST",
				        url: "cartHelper.php",
				        data: tobesend,
				        dataType: "json",
				        success: function(response)
				        {   
				          if(response.status == 1)
				          {
				            $("#information").html("<p class=\"text-success\">Product added to your cart</p>");
				            $("#cart-item-price").html(response.cart_item_price);
				            $("#information-modal").modal("show");
				          }
				          else
				          if(response.status == 2)
				          {
				            $("#information").html("<p class=\"text-success\">This product is already exist in your cart. You increased its quantity.</p>");
				            $("#cart-item-price").html(response.cart_item_price);
				            $("#information-modal").modal("show");
				          }
				          else
				          {
				            $("#information").html("<p class=\"text-danger\">Unable to add this product to your cart!</p>");
				            $("#information-modal").modal("show");
				          }
				        }
				      });
				    });
				</script>';

				$response_code=1;
	    	}
	    }

		$response['status']=$response_code;
		$response['searchdata']=$record;
		echo json_encode($response);
	}
?>