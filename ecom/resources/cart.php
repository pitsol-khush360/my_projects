<?php require_once("config.php"); ?>

<?php
if(isset($_GET['add']))
{
	$query=query("select * from products where product_id=".escape_string($_GET['add']));
	confirm($query);

	while($row=fetch_array($query))
	{
		if($row['product_quantity'] != $_SESSION['product_'.$_GET['add']])
		{
			$_SESSION['product_'.$_GET['add']]+=1;
			redirect("../public/checkout.php");
		}
		else
		{
			setmessage("Only ".$row['product_quantity']." pieces of ".$row['product_title']." are Available");
			redirect("../public/checkout.php");
		}
	}
}

if(isset($_GET['remove']))
{
	if($_SESSION['product_'.$_GET['remove']]<1)
	{
		unset($_SESSION['product_'.$_GET['remove']]);
		redirect("../public/checkout.php");
	}
	else
	{
		$_SESSION['product_'.$_GET['remove']]-=1;
		redirect("../public/checkout.php");
	}
}

if(isset($_GET['delete']))
{
	$_SESSION['product_'.$_GET['delete']]=0;
	unset($_SESSION['product_'.$_GET['delete']]);
	redirect("../public/checkout.php");
}

function cart()
{
	// For calculating total bill.
	$bill=0;
	// For calculating total product quantity in cart
	$totalproducts=0;
	$i=1;

	foreach ($_SESSION as $key => $value) 
	{
		if($value>0)
		{
			if(substr($key,0,8)==="product_")
			{
				$id=substr($key,8);
				$query=query("select * from products where product_id=".escape_string($id));
				confirm($query);

				while($row=fetch_array($query))
				{
					$sub=$value*$row['product_price'];
					$product_image=image_path_products($row['product_image']);
					$product = <<< delimeter
					<tr>
					<td>{$row['product_title']}<br>
						<img src="../resources/{$product_image}" style="width:300px;height:150px;">
					</td>
					<td>{$row['product_price']}</td>
					<td>$value</td>
					<td>$sub</td>
					<td><a class="btn btn-warning" href="../resources/cart.php?remove=$id"><span class="glyphicon glyphicon-minus"></span></a>
						<a class="btn btn-success" href="../resources/cart.php?add=$id"><span class="glyphicon glyphicon-plus"></span></a>
						<a class="btn btn-danger" href="../resources/cart.php?delete=$id"><span class="glyphicon glyphicon-remove"></span></a>
					</td>
					</tr>
					<input type="hidden" name="item_name_{$i}" value="{$row['product_title']}">
					<input type="hidden" name="item_number_{$i}" value="{$row['product_id']}">
					<input type="hidden" name="amount_{$i}" value="{$row['product_price']}">
					<input type="hidden" name="quantity_{$i}" value="{$value}">
delimeter;
					echo $product;
					$bill+=$sub;
					$totalproducts+=$value;
					$i++;
				}
			}
		}
	}

	$_SESSION['bill']=$bill;
	$_SESSION['totalproducts']=$totalproducts;
}

function process_transactions()
{
	$amount=$_GET['amt'];
	$cc=$_GET['cc'];
	$transaction=$_GET['tx'];
	$status=$_GET['st'];
	$query_order=query("insert into orders(order_amount,order_transaction,order_status,order_currency) values($amount,'$transaction','$status','$cc')");
	confirm($query_order);

	$order_id=last_id();
	$bill=0;
	$totalproducts=0;

	foreach ($_SESSION as $key => $value) 
	{
		if($value>0)
		{
			if(substr($key,0,8)==="product_")
			{
				$id=substr($key,8);
				$query_select_product=query("select * from products where Product_id=".escape_string($id));
				confirm($query_select_product);

				while($row=fetch_array($query_select_product))
				{
					// $sub=$value*$row['product_price'];
					// $bill+=$sub;
					// $totalproducts+=$value;
					$product_price=$row['product_price'];
					$product_title=$row['product_title'];
					$query_report=query("insert into report(order_id,product_id,product_title,product_quantity,product_price) values({$order_id},{$id},'{$product_title}',{$value},{$product_price})");
					confirm($query_report);
				}
			}
		}
	}
}
?>