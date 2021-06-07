<?php 
	require_once("../config/config.php"); 
	require_once("../config/".ENV."_config.php");
?>

<?php
  function getTotalCartPrice()
  {
    $price=0;

    if(isset($_SESSION[$_REQUEST['s']]['cartdetails']) && count($_SESSION[$_REQUEST['s']]['cartdetails'])>0)
    {
      foreach($_SESSION[$_REQUEST['s']]['cartdetails'] as $key => $value)
      {
        $price+=($value['price'] * $value['quantity']);
      }
    }
    return $price;
  }

  function isAdded($id)
  {
    $added=0;

    if(isset($_SESSION[$_REQUEST['s']]['cartdetails']) && count($_SESSION[$_REQUEST['s']]['cartdetails'])>0)
    {
      foreach($_SESSION[$_REQUEST['s']]['cartdetails'] as $key => $value)
      {
        if(intval($value['product_id'])==intval($id))
        {
          $added=1;
          break;
        }
      }
    }
    return $added;
  }

  function getIndex($id)
  {
    $index=-1;

    if(isset($_SESSION[$_REQUEST['s']]['cartdetails']) && count($_SESSION[$_REQUEST['s']]['cartdetails'])>0)
    {
      foreach($_SESSION[$_REQUEST['s']]['cartdetails'] as $key => $value)
      {
        if(intval($value['product_id'])==intval($id))
        {
          $index=$key;
          break;
        }
      }
    }
    return $index;
  }
?>

<?php
	if(isset($_POST['s']))
	{
		$response_code=0;

		if(isset($_POST['product_id']) && isset($_POST['catalogue_id']) && isset($_POST['price']) && isset($_POST['quantity']) && isset($_POST['product_name']) && isset($_POST['seller_id']) && isset($_POST['product_image']) && isset($_POST['tax_percent']))
		{
			if(isset($_POST['product_id']) && $_POST['product_id']!="")
	    	{
	  		  $added=isAdded($_POST['product_id']);
		      if($added==0)
		      {
		        $_SESSION[$_REQUEST['s']]['cartdetails'][] = array(
		          'product_id'      => $_POST['product_id'],
		          'catalogue_id'    => $_POST['catalogue_id'],
		          'price'           => $_POST['price'],
		          'quantity'        => $_POST['quantity'],
		          'product_name'    => $_POST['product_name'],
		          'seller_id'       => $_POST['seller_id'],
		          'product_image'   => $_POST['product_image'],
		          'tax_percent'     => $_POST['tax_percent']
		        );

		        $response_code=1;
		      }
		      else
		      if($added==1)
		      {
		        $index=getIndex($_POST['product_id']);
		        if($index!=-1)
		        {
		          $_SESSION[$_REQUEST['s']]['cartdetails'][$index]['quantity']=intval($_SESSION[$_REQUEST['s']]['cartdetails'][$index]['quantity'])+1;
		        }

		        $response_code=2;
		      }
	    	}
		}
		$response['status']=$response_code;
		$response['cart_item_price']="<div class='col-12'>
			                          <span class='bp-nv-cartcount'>".count($_SESSION[$_REQUEST['s']]['cartdetails'])." Items Added</span>
			                        </div>
			                        <div class='col-12'>
			                          <span class='bp-nv-cartcount'><i class='fas fa-rupee-sign'></i> ".getTotalCartPrice()."</span>
			                        </div>";
		echo json_encode($response);
	}
?>