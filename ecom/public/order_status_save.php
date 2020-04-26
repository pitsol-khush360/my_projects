<?php require_once("../resources/config.php"); ?>

<!DOCTYPE html>
<html>
<head>
	<title>Order Confirmation Page</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

</body>
</html>

<?php
	if(isset($_SESSION['totalproducts']) && isset($_SESSION['user_name']) && $_SESSION['totalproducts']!=0)
	{
?>

<div class="container">
	<div class="row">
		<table class="table">
			<thead>
			<tr>
				<th>Product Title</th>
           		<th>Amount</th>
           		<th>Quantity</th>
           		<th>Sub-Total</th>
      		</tr>
    	</thead>
    	<tbody>
        <?php

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
					$product = <<< delimeter
					<tr>
					<td>{$row['product_title']}</td>
					<td>{$row['product_price']}</td>
					<td>$value</td>
					<td>$sub</td>
delimeter;
					echo $product;
				}
			}
		}
	}

		$amount=$_SESSION['bill'];
		$cc="rupee";
		$status='Y';
		$userid=logged_in_userid($_SESSION['user_name']);
		$transaction="Successful";
		$query_order=query("insert into orders(userid,order_amount,order_transaction,payment_status,order_currency) values($userid,$amount,'$transaction','$status','$cc')");
		confirm($query_order);
         ?>
    	</tbody>
		</table>
	</div>
	<div class="row">
		<div class="text-center">
			<p class="text-success" style="font-size:20px;">Thank You! For Using Our Services</p>
			<p class="text-info" style="font-size:20px;">Your Payment of <?php echo $_SESSION['bill']; ?>
								is Successful.
			</p>
		</div>
	</div>
	<div class="row">
		<a href="index.php" style="text-decoration:none;color:white;">
			<button class="text-center btn btn-success btn-lg">Continue Shopping</button>
		</a>
	</div>
</div>
<?php
		foreach($_SESSION as $key => $value)
		{
			if($key!="user_name" && $key!="admin_name")
			{
				unset($_SESSION[$key]);
			}
		}
	}	
	else
	{
		if(!isset($_SESSION['user_name']))
		{
			echo '<div class="container">
					<div class="row">
						<p class="text-danger text-center text-lg" style="font-size:20px;">Sorry,You Have To Login For Access This Feature.</p>
					</div>
					<div class="row">
						<a href="login.php" style="text-decoration:none;">
						<button class="btn btn-success btn-md">Login</button></a><br>
						<a href="signup.php" style="text-decoration:none;">
						<p>Don\'t Have Any Account,&nbsp;<button class="btn btn-info btn-md">Sign Up</button></p></a>
					</div>
				  </div>';
		}
		else
			if($_SESSION['totalproducts']==0)
			{
				echo '<div class="container">
						<div class="row">
							<p class="text-danger text-center" style="font-size:20px;">Sorry Your Cart Is Empty</p>
						</div>
					 </div>';
			}
		else
		{
		echo '<div class="container">
				<div class="row">
					<p class="text-danger text-center text-lg" style="font-size:20px;">Sorry! You Don\'t Have Access Permission...</p>
				</div>
				<div class="row">
				<a href="index.php" style="text-decoration:none;color:white;">
				<button class="text-center btn btn-success btn-lg">Continue Shopping</button></a>
				</div>
			  </div>';
		}
	}
?>