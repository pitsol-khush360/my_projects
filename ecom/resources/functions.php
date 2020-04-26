<?php
	// mail libraries should be included in top of php page.
	/*Use PHPMailer API*/
	use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    date_default_timezone_set("Asia/Kolkata");
?>

<?php
foreach ($_POST as $key => $value) {
	$_POST[$key]=htmlentities($_POST[$key]);
	$_POST[$key]=addslashes($_POST[$key]);
}
foreach ($_GET as $key => $value) {
	$_GET[$key]=htmlentities($_GET[$key]);
	$_GET[$key]=addslashes($_GET[$key]);
}
// helper functions 
// for redirection
function redirect($location)
 {
 	header("location:$location");
 } 

 // for query execution
 function query($sql)
 {
 	global $connection;
 	return mysqli_query($connection,$sql);
 }

 // for error display and terminate further execution
 function confirm($result)
 {
 	global $connection;
 	if(!$result)
 	{
 		die("Query Failed".mysqli_error($connection));
 	}
 }

 // prevent from sql injections
 function escape_string($string)
 {
 	global $connection;
 	$string=htmlentities($string);
 	return $string;
 }

 // return a row from result set
 function fetch_array($result)
 {
 	return mysqli_fetch_array($result);
 }

 function getproducts()
 {
 	if(isset($_GET['start']) && isset($_GET['end']))
 	{
 		$start=$_GET['start'];
 		$end=$_GET['end'];
 	}
 	else
 	{
 		$start=0;
 		$end=6;
 	}

 	// For limit on records. $end -> no. of records. $start -> Starting index.
 	$query=query("select * from products limit $start,$end");
 	confirm($query);

 	if(isset($_SESSION['user_name']))
 	{
 			$query_user=query("select * from users where username like '".escape_string($_SESSION['user_name'])."'");
 			confirm($query_user);
 			$row_user=fetch_array($query_user);

 			$userid=$row_user['userid'];
 	}

 	while($row=fetch_array($query))
 	{
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

 	if(isset($_SESSION['user_name']))
 	{
 			$productid=$row['product_id'];

 			$query_saved_items=query("select * from saved_items where user_id={$userid} and product_id={$productid}");
 			confirm($query_saved_items);
 			$row_saved_items=fetch_array($query_saved_items);

 			if(mysqli_num_rows($query_saved_items)!=0)
 			{
 				// using heredoc <<< 
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
 			else
 			{
 				// using heredoc <<< 
 		$products = <<< any
 		<div class="col-sm-4 col-lg-4 col-md-4">
                        <div class="thumbnail">
                        <a href="item.php?id={$row['product_id']}">
                            <img src="../resources/{$image_path}" alt="" style="width:100%;height:170px;">
                        </a>
                            <div class="caption">
                                <h4 class="pull-right"><span id="{$row['product_id']}" class="glyphicon glyphicon-heart heart_color_grey"></span></h4>
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
 		
        }
        else
        {
        		$products = <<< any
 				<div class="col-sm-4 col-lg-4 col-md-4">
                        <div class="thumbnail">
                        <a href="item.php?id={$row['product_id']}">
                            <img src="../resources/{$image_path}" alt="" style="width:100%;height:170px;">
                        </a>
                            <div class="caption">
                                <h4 class="pull-right">
                                <a href="login.php" style="text-decoration:none;">
                                <span class="glyphicon glyphicon-heart"></span></a></h4>
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
 	}
 }

 function getcategories()
 {
 	$query=query("select * from categories");
 	confirm($query);

        	while($row=fetch_array($query))
        	{
        		$cat = <<< heredoc
            			<a href="category.php?id={$row['cat_id']} & cattitle={$row['cat_title']}" class='list-group-item'>{$row['cat_title']}</a>
heredoc;
            			echo $cat;
        	}
 }

 function getproductsbycategory($arg)
 {
 	if($arg=="count")
 	{
 		$query=query("select * from products where product_category_id=".escape_string($_GET['id']));
 		confirm($query);
 		$row=fetch_array($query);
 		echo mysqli_num_rows($query);
 	}
 	else 
 	if($arg=="items")
 	{
 		if(isset($_GET['start']) && isset($_GET['end']))
 		{
 			$start=$_GET['start'];
 			$end=$_GET['end'];
 		}
 		else
 		{
 			$start=0;
 			$end=6;
 		}

 		$query=query("select * from products where product_category_id=".escape_string($_GET['id'])."limit $start,$end");
 		confirm($query);
 		while($row=fetch_array($query))
 		{	
 			$des=$row['product_description'];
 			$c=strlen($des);

 			if($c>=20)
 			{
 				$sub=substr($des,0,30);
 				$sub=$sub."...";
 			}
 			else
 				$sub=$des;

 		// using heredoc <<< 
 		$product_image=image_path_products($row['product_image']);
 		$products = <<< any
 		<div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img class="img-responsive" src="../resources/{$product_image}" alt="" style="height:150px;width:100%;">
                    <div class="caption">
                       	<h3>{$row['product_title']} &nbsp;&nbsp;&nbsp;&nbsp; &#8377;{$row['product_price']}</h3>
                        <p>$sub</p>
                        <p>
                            <a href="cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a><a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
        </div>
any;
                    print $products;
 	}
 	}
 }

function user_login()
{
	if(isset($_POST['submit']))
	{
		if($_POST['password']!="" && $_POST['username']!="")
		{
		$username=$_POST['username'];
		$username=trim($username);            // trim() removes whitespaces and other 											  //predefined symbols if passed in argument.
		$password=$_POST['password'];
		$password=trim($password);

		// Retrieving from users table to check for username and password.(non-admin user)
		$query_user=query("select * from users where username like '".escape_string($username)."' and password like '".escape_string($password)."'");
 		confirm($query_user);
 		$row=fetch_array($query_user);

 		// Retrieving from admin table to check for username and password.
 		$query_admin=query("select * from admin where admin_name like '".escape_string($username)."' and admin_password like '".escape_string($password)."'");
 		confirm($query_admin);

 		if(mysqli_num_rows($query_admin)!=0)
 		{
 			$_SESSION['admin_name']=$username;
 			redirect("admin");
 		}
 		else
 		if(mysqli_num_rows($query_user)==1 && $row['user_info']=='Y')
 		{
 			//echo "Non-admin User";
 			$_SESSION['user_name']=$username;
 			redirect("users");
 		}
 		else
 		if(mysqli_num_rows($query_user)==1 && $row['user_info']=='N')
 		{
 			redirect("signup_complete.php?name=$username");
 		}
 		else
 		{
 			setmessage("Invalid login credentials...");
 			redirect("login.php");
 		}
 		}
 		else
 			if($_POST['password']=="" && $_POST['username']!="")
 			{
 				setmessage("Password Must Be Filled!");
 				redirect("login.php");
 			}
 		else
 			if($_POST['password']!="" && $_POST['username']=="")
 			{
 				setmessage("Username Must Be Filled!");
 				redirect("login.php");
 			}
 		else
 			if($_POST['password']=="" && $_POST['username']=="")
 			{
 				setmessage("Username and password Must Be Filled!");
 				redirect("login.php");
 			}
	}
}

function user_signup()
{
	if(isset($_POST['signup_submit']))
	{
	if($_POST['password']!="" && $_POST['username']!="")
	{
		$username=$_POST['username'];
		$username=trim($username);
		$password=$_POST['password'];
		$password=trim($password);

 		// $check_user,mysqli_num_rows($check) For Checking if username already exists In table // users.

 		$check_user=query("select * from users where username like '".escape_string($username)."'");
 		confirm($check_user);

 		if(mysqli_num_rows($check_user)!=0)  // If Already Exists.
 		{
 			setmessage("Username Already Taken!");
 			redirect("signup.php");
 		}
 		else
 		if(mysqli_num_rows($check_user)==0)
 		{
 			$query=query("insert into users (username,password) values('$username','$password')");
 			confirm($query);
 			redirect("login.php");
 			//exit;            // Ends the script or remaining code will not execute.
 		}
 	}
 	else
 	if($_POST['password']=="" && $_POST['username']!="")
 	{
 			setmessage("Password Must Be Filled!");
 			redirect("signup.php");
 	}
 	else
 		if($_POST['password']!="" && $_POST['username']=="")
 		{
 			setmessage("Username Must Be Filled!");
 			redirect("signup.php");
 		}
 	else
 		if($_POST['password']=="" && $_POST['username']=="")
 		{
 			setmessage("Username and password Must Be Filled!");
 			redirect("signup.php");
 		}
	}	

}

function user_signup_complete()
{
	if(isset($_POST['user_signup_complete']))
	{
		$username=escape_string($_POST['username']);
		$password=escape_string($_POST['password']);
		$email=escape_string($_POST['email']);
		$firstname=escape_string($_POST['firstname']);
		$lastname=escape_string($_POST['lastname']);
		$mobile=escape_string($_POST['mobile_number']);
		$address=escape_string($_POST['address']);
		$profile=escape_string($_FILES['profile_picture']['name']);
		$profile_tmp=escape_string($_FILES['profile_picture']['tmp_name']);

		if(empty($profile))
		{
			$profile=$_POST['hidden_profile_picture'];
		}
		else
			move_uploaded_file($_FILES['profile_picture']['tmp_name'],UPLOAD_DIRECTORY_PROFILE.DS.$profile);

			$query="update users set ";
			$query.="username='{$username}', ";
			$query.="firstname='{$firstname}', ";
			$query.="lastname='{$lastname}', ";
			$query.="password='{$password}', ";
			$query.="email='{$email}', ";
			$query.="address='{$address}', ";
			$query.="mobile_number={$mobile}, ";
			$query.="profile_picture='{$profile}',";
			$query.="user_info='Y' ";
			$query.=" where username='".escape_string($_POST['username'])."'";

			$query=query($query);
			confirm($query);
			$_SESSION['user_name']=$username;
			redirect("users/user_profile.php?name={$username}");
	}
}

function setmessage($message)
{
	if(!empty($message))
	$_SESSION['message']=$message;
}

function displaymessage()
{
	if(isset($_SESSION['message']))
	{
		echo $_SESSION['message'];
		unset($_SESSION['message']);
	}
}

function send_message()
{
	if(isset($_POST['sendmessage']))
		{	
			$to="khushalrankawat360@gmail.com";
			$from_name=$_POST['your_name'];
			$email=$_POST['email'];
			$subject=$_POST['subject'];
			$message=$_POST['message'];

			/*Construct email*/
			$mail = new PHPMailer;
		    //To Enable SMTP debugging uncomment following line
		    //$mail->SMTPDebug = 3;
		    //Set PHPMailer to use SMTP. On live server comment the below line
		    $mail->isSMTP();
		    //Set SMTP host name
		    $mail->Host = "smtp.gmail.com";
		    //Set this to true if SMTP host requires authentication to send email
		    $mail->SMTPAuth = true;
		    //Provide username and password of gmail
		    $mail->Username = "ecomapp1234@gmail.com";
		    $mail->Password = "Tar@1234";

		    //If SMTP requires TLS encryption then set it
		    $mail->SMTPSecure = "tls";
		    
		    //Set TCP port to connect to
		    $mail->Port = 587;
		    
		    //Senders email		    
		    $mail->From = "ecomshoppe@gmail.com";

		    //Senders name
		    $mail->FromName = "Ecom Shoppe";
		    
		    //This is the recievers address
		    $mail->addAddress($to);
		    
		    //Set it to true to send the HTML content
		    $mail->isHTML(true);
		    
		    //Specify the message subject
		    $mail->Subject = $subject;
		    
		    //Specify the message content
		    $mail->Body = "You have recieved the message from {$from_name} reply him on {$email}<br> The message recievd is as follows <br><i>".$message."</i>";
		    // $mail->AltBody = "This is the plain text version of the email content";

		    if(!$mail->send())
		    {  
		    	echo "<span class='text-danger'>Failed to send the message</span>".$mail->ErrorInfo;
		    }
		    else
		    {
		       	echo "<span class='text-success'>Message has been sent successfully</span>";
		       	$sql="insert into user_query (query_subject,query_message,query_email) values('$subject','$message','$email');";
				$query=query($sql);
				confirm($query);
		    }
		}
}

function pagination()
{
	$query=query("select count(product_id) from products");
 	confirm($query);
 	$row=fetch_array($query);
 	$take=$row['count(product_id)'];

 	//print_r($row);      for display result of $row

 	// Making Arrangment For 6 records in a page.
 		$quotent=intval($take/6);    // returns integer value or typecast (int)($take/6);
 		$rem=(int)($take%6);

 		if($rem==0)
 			$c=$quotent+$rem;     // Total Pages.
 		else
 			$c=$quotent+1;		// Total Pages.

 		$set=0;               // For
 		$end=6;

 		if(empty($_SESSION['prev']) && empty($_SESSION['next']))
 		{

 			$prevPage=1;
 			$nextPage=2;
 			$_SESSION['prev']=0;
 			$_SESSION['next']=6;
 			//$prev=0; // for page=1 $prev and $next will not set so give error.
 			//$next=6;
 		}
 		else
 		{
 			if($_GET['page']==1)
 			{
 				$prevPage=1;
 				$nextPage=2;
 				$_SESSION['prev']=0;
 				$prev=$_SESSION['prev'];
 				$_SESSION['next']=6;
 				$next=$_SESSION['next'];
 			}
 			else
 			{
 				$prevPage=$_GET['page']-1;

 				if($_GET['page']==$c)
 					$nextPage=$c;
 				else
 					$nextPage=$_GET['page']+1;

 				$_SESSION['prev']=$_GET['start']-6;
 				$prev=$_SESSION['prev'];

 				if($_GET['page']==$c)
 				{
 					$_SESSION['next']=$_GET['start'];
 					$next=$_SESSION['next'];
 				}
 				else
 				{
 					$_SESSION['next']=$_GET['start']+6;
 					$next=$_SESSION['next'];
 				}
 			}
 		}

	// For number pagination.
	$s=1;
		
 	while($s<=$c)
 	{
 		if($s==1)
 		{	
 			// For Previous <li> in pagination.
 			$page=<<< page
			<ul class="pagination" style="padding-right:80px;">
			<li>
			<a href="shop.php?prev={$_SESSION['prev']}&page=$prevPage&start=$prev&end=6"><span><i class="glyphicon glyphicon-menu-left"></i><i class="glyphicon glyphicon-menu-left"></i> Previous</span></a>
			</li>
			</ul>
page;
				echo $page;

 			if($_GET['page']==$s)
 			{
 				$page=<<< page
 				<ul class="pagination">
				<li class="active"><a href="shop.php?page=$s&start=$set&end=6">$s</a></li>
				</ul>
page;
				echo $page;
 			}
 			else
 			{
 				$page=<<< page
 				<ul class="pagination">
				<li><a href="shop.php?page=$s&start=$set&end=6">$s</a></li>
				</ul>
page;
				echo $page;
 			}
 			if($s==$c)          // If last page then on clicking next go to same page
			{
			// For next <li> in pagination.
 			$page=<<< page
				<ul class="pagination" style="padding-left:80px;">
				<li>
			<a href="shop.php?next={$_SESSION['next']}&page=$nextPage&start=$next&end=6"><span>Next <i class="glyphicon glyphicon-menu-right"></i><i class="glyphicon glyphicon-menu-right"></i></span></a>
				</li>
				</ul>
page;
			echo $page;
			}
			
			$set=$set+$end;
 		}
 		else
 		{
 			if($_GET['page']==$s)
 			{
 				$page=<<< page
 				<ul class="pagination">
				<li class="active"><a href="shop.php?page=$s&start=$set&end=6" id="lists">$s</a></li>
				</ul>
page;
				echo $page;
 			}
 			else
 			{
 				$page=<<< page
 				<ul class="pagination">
				<li><a href="shop.php?page=$s&start=$set&end=6" id="lists">$s</a></li>
				</ul>
page;
				echo $page;
 			}
 			

			if($s==$c)          // If last page then on clicking next go to same page
			{
			// For next <li> in pagination.
 			$page=<<< page
				<ul class="pagination" style="padding-left:80px;">
				<li>
			<a href="shop.php?next={$_SESSION['next']}&page=$nextPage&start=$next&end=6"><span>Next <i class="glyphicon glyphicon-menu-right"></i><i class="glyphicon glyphicon-menu-right"></i></span></a>
				</li>
				</ul>
page;
			echo $page;
			}
			$set+=$end;
		}
 		$s++;
 	}
}

function showpaypalbutton()
{
	if(isset($_SESSION['totalproducts']) && $_SESSION['totalproducts']>0)
	{
		$paypal=<<< button
		<input type="image" name="upload" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" alt="Paypal - The Safer , easier way to pay online">
button;
		echo $paypal;
	}
}

function last_id()
{
	global $connection;
	$id=mysqli_insert_id($connection);
	return $id;
}

function display_order()
{
	$query=query("select * from orders");
	confirm($query);

	while($row=fetch_array($query))
	{
		$order=<<< ORDER
		<tr>
			<td>{$row['order_id']}</td>
			<td>{$row['userid']}</td>
			<td>{$row['order_amount']}</td>
			<td>{$row['order_transaction']}</td>
			<td>{$row['order_currency']}</td>
			<td>{$row['payment_status']} &nbsp;
				<a href="../../resources/templates/back/order_status_edit.php?id={$row['order_id']}"><span class="glyphicon glyphicon-refresh" style="color:blue;"></span>
				</a>
			</td>
			<td>
				<a href="../../resources/templates/back/order_delete.php?id={$row['order_id']}" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
			</td>
		</tr>
ORDER;
		echo $order;
	}
}

function show_product_category_name($product_category_id)
{
	$query=query("select cat_title from categories where cat_id=$product_category_id");
	confirm($query);

	$row=mysqli_fetch_assoc($query);
	$category_title=$row['cat_title'];
	return $category_title;
}

function image_path_products($path)
{
	return "uploads".DS.$path;
}

function image_path_profile($path)
{
	return "profile_upload".DS.$path;
}

function show_products_in_admin()
{
	$query=query("select * from products");
	confirm($query);
while($row=fetch_array($query))
{
	$product_category_title=show_product_category_name($row['product_category_id']);
	$image_path=image_path_products($row['product_image']);
	$products = <<< delimeter
				<tr>
					<td>{$row['product_id']}</td>
					<td>{$row['product_title']}</td>
					<td>
						<a href="index.php?edit_product&id={$row['product_id']}">
						<img src="../../resources/{$image_path}" style="width:50px;height:50px;" alt=""></td></a>
					</td>
					<td>{$product_category_title}</td>
					<td>{$row['product_price']}</td>
					<td>{$row['product_quantity']}</td>
					<td><a href="../../resources/templates/back/product_delete.php?id={$row['product_id']}" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
					</td>
					<td>
						<a href="index.php?edit_product&id={$row['product_id']}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
					</td>
				</tr>
delimeter;
				print $products;
}
}

function add_product()
{
	if(isset($_POST['publish']))
	{
		if(isset($_POST['product_title']) && isset($_POST['product_price']) && isset($_POST['product_quantity']) && isset($_POST['product_category_id']) && isset($_POST['product_description']) && isset($_POST['product_short_description'])
			)
		{
			$product_title=escape_string($_POST['product_title']);
			$product_price=escape_string($_POST['product_price']);
			$product_quantity=escape_string($_POST['product_quantity']);
			$product_category_id=escape_string($_POST['product_category_id']);
			$product_description=escape_string($_POST['product_description']);
			$product_short_description=escape_string($_POST['product_short_description']);
			$product_image=escape_string($_FILES['product_image']['name']);
			$product_image_tmp=escape_string($_FILES['product_image']['tmp_name']);

			if(!empty($product_image))
				move_uploaded_file($_FILES['product_image']['tmp_name'],UPLOAD_DIRECTORY_PRODUCTS.$product_image);

			$query=query("insert into products (product_title,product_category_id,product_price,product_description,product_short_description,product_image,product_quantity) values('{$product_title}',{$product_category_id},{$product_price},'{$product_description}','{$product_short_description}','{$product_image}',{$product_quantity})");
			confirm($query);
			setmessage("New Product With Id ".last_id()." Added.");
			redirect("index.php?products");
		}
		else
		{
			header("refresh:0;url=index.php?add_product");

			echo '<script type="text/javascript">
					alert("All Fields Must Be Filled.");
				  </script>';
		}
	}
}

function get_categories_in_add_product()
{
	$query=query("SELECT * from categories");
	confirm($query);
	while($row=mysqli_fetch_assoc($query))
	{
		$category_options=<<< any
		<option value="{$row['cat_id']}">{$row['cat_title']}</option>
any;
		echo $category_options;
	}
}

function update_product()
{
	if(isset($_POST['update_product']))
	{
		$product_title=escape_string($_POST['product_title']);
		$product_price=escape_string($_POST['product_price']);
		$product_quantity=escape_string($_POST['product_quantity']);
		$product_category_id=escape_string($_POST['product_category_id']);
		$product_description=escape_string($_POST['product_description']);
		$product_short_description=escape_string($_POST['product_short_description']);
		$product_image=escape_string($_FILES['product_image']['name']);
		$product_image_tmp=escape_string($_FILES['product_image']['tmp_name']);

		if(empty($product_image))
		{
			$product_image=$_POST['hidden_product_image'];
		}
		else
			move_uploaded_file($_FILES['product_image']['tmp_name'],UPLOAD_DIRECTORY_PRODUCTS.$product_image);

		if(empty($product_category_id))
			$product_category_id=$_POST['hidden_product_category_id'];

			$query="update products set ";
			$query.="product_title='{$product_title}', ";
			$query.="product_category_id={$product_category_id}, ";
			$query.="product_description='{$product_description}', ";
			$query.="product_short_description='{$product_short_description}', ";
			$query.="product_price={$product_price}, ";
			$query.="product_image='{$product_image}', ";
			$query.="product_quantity={$product_quantity} ";
			$query.=" where product_id=".escape_string($_GET['id']);

			$query=query($query);
			confirm($query);
			setmessage("Product With Id ".last_id()." Updated");
			redirect("index.php?products");
	}
}

function show_categories_in_admin()
{
	$query=query("select * from categories");
	confirm($query);

	while($row=fetch_array($query))
	{
		$categories = <<< delimeter
				<tr>
					<td>{$row['cat_id']}</td>
					<td>{$row['cat_title']}</td>
					<td><a href="../../resources/templates/back/category_delete.php?id={$row['cat_id']}" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
					</td>
					<td>
						<a href="index.php?edit_category&id={$row['cat_id']}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
					</td>
				</tr>
delimeter;
				print $categories;
	}
}

function add_category()
{
	if(isset($_POST['add_category']))
	{
		$category_title=escape_string($_POST['cat_title']);

		$query=query("insert into categories (cat_title) values('{$category_title}')");
		confirm($query);
		setmessage("New Category With Id ".last_id()." Added.");
		redirect("index.php?categories");
	}
}

function update_category()
{
	if(isset($_POST['update_category']))
	{
		$category_title=escape_string($_POST['cat_title']);
		
			$query=query("update categories set cat_title='{$category_title}' where cat_id=".escape_string($_GET['id']));
			confirm($query);
			setmessage("Category With Id ".last_id()." Updated");
			redirect("index.php?categories");
	}
}

function show_users()
{
	$query=query("select * from users");
	confirm($query);
	while($row=fetch_array($query))
	{
		$userid=$row['userid'];
		$username=$row['username'];
		$password=$row['password'];
		$email=$row['email'];
		$firstname=$row['firstname'];
		$lastname=$row['lastname'];
		$address=$row['address'];
		$mobile=$row['mobile_number'];
		$profile=$row['profile_picture'];
		$image_path=image_path_profile($profile);

		$users=<<< users
			<tr>
				<td>$userid</td>
				<td>$username</td>
				<td>$password</td>
				<td>$email</td>
				<td>$firstname</td>
				<td>$lastname</td>
				<td>$address</td>
				<td>$mobile</td>
				<td><img src="../../resources/{$image_path}" style="height:60px;width:60px;"></td>
				<td><a href="../../resources/templates/back/user_delete.php?id={$row['userid']}" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
				</td>
				<td><a href="index.php?edit_user&id={$row['userid']}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
				</td>
			</tr>
users;
			echo $users;
	}
}

function update_user()
{
	if(isset($_POST['update_user']))
	{
		$username=escape_string($_POST['username']);
		$password=escape_string($_POST['password']);
		$email=escape_string($_POST['email']);
		$firstname=escape_string($_POST['firstname']);
		$lastname=escape_string($_POST['lastname']);
		$mobile=escape_string($_POST['mobile_number']);
		$address=escape_string($_POST['address']);
		$profile=escape_string($_FILES['profile_picture']['name']);
		$profile_tmp=escape_string($_FILES['profile_picture']['tmp_name']);

		if(empty($profile))
		{
			$profile=$_POST['hidden_profile_picture'];
		}
		else
			move_uploaded_file($_FILES['profile_picture']['tmp_name'],UPLOAD_DIRECTORY_PROFILE.$profile);

			$query="update users set ";
			$query.="username='{$username}', ";
			$query.="firstname='{$firstname}', ";
			$query.="lastname='{$lastname}', ";
			$query.="password='{$password}', ";
			$query.="email='{$email}', ";
			$query.="address='{$address}', ";
			$query.="mobile_number={$mobile}, ";
			$query.="profile_picture='{$profile}'";
			$query.=" where userid=".escape_string($_GET['id']);

			$query=query($query);
			confirm($query);
			setmessage("User With Id ".last_id()." Updated");
			redirect("index.php?users");
	}
}

function display_order_for_user()
{
	$query_order=query("select * from orders where userid=".$_GET['id']);
	confirm($query_order);

	while($row=fetch_array($query_order))
	{
		$query_report=query("select * from report where order_id=".$row['order_id']);
		confirm($query_report);
		$report=fetch_array($query_report);

		$order=<<< ORDER
		<tr>
			<td><button class="btn btn-primary" data-toggle="collapse" data-target="#m{$row['order_id']}">{$row['order_id']} <span class="caret"></span></button>
			</td>
			<td>{$row['order_amount']}</td>
			<td>{$row['order_transaction']}</td>
			<td>{$row['order_currency']}</td>
			<td>{$row['payment_status']}</td>
			<td>
				<!--<a href="../../resources/templates/users/order_delete.php?id={$row['order_id']}" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a>-->
			</td>
		</tr>


		<div class="collapse" id="m{$row['order_id']}" style="background-color:rgb(30,40,30);color:white;">
    		<div class="row">
    			<div class="col-lg-12">
          				<h4 class="text-center">Order {$row['order_id']} Report</h4>
          				<hr>
        				<div class="row text-center" style="height:5px;">
        					<div class="col-lg-2">Report Id</div>
          					<div class="col-lg-2">Product Id</div>
          					<div class="col-lg-2">Product Title</div>
          					<div class="col-lg-2">Product Quantity</div>
          					<div class="col-lg-2">Product Price</div>
          				</div>
          				<hr>
          				<div class="row text-center">
          					<div class="col-lg-2">{$report['report_id']}</div>
          					<div class="col-lg-2">{$report['product_id']}</div>
          					<div class="col-lg-2">{$report['product_title']}</div>
          					<div class="col-lg-2">{$report['product_quantity']}</div>
       						<div class="col-lg-2">{$report['product_price']}</div>
          					</div>
      					</div>
      			</div>
  			</div>
  		</div>


ORDER;
		echo $order;
	}
}

function get_username_of_review($id)
{
	$query=query("SELECT * from users where userid={$id}");
	confirm($query);
	$row=fetch_array($query);
	$username=$row['username'];
	return $username;
}

function get_days_minutes_or_hours($now,$your_date)
{
	$datediff=$now-$your_date;
	if(floor($datediff)<=60)
		return "$datediff seconds ago";
	else
		if(floor($datediff)<3600)
			return round($datediff/60)." minutes ago";
	else
		if(floor($datediff)<86400)
			return round($datediff/3600)." hours ago";
	else
	if(floor($datediff)>=86400)
			return round(($datediff/86400))." days ago";
}

function show_reviews()
{
	$query=query("select * from reviews where product_id=".$_GET['id']." order by review_id desc limit 0,4");
	confirm($query);

	while($row=fetch_array($query))
	{	
		$now=time();                         // Present time
		$your_date=$row['review_time'];
		$diff_time=get_days_minutes_or_hours($now,$your_date);

		$username=get_username_of_review($row['userid']);

		$stars=$row['review_star'];
		$i=1;
		for($i=1;$i<=5;$i++)
		{
			echo '<span class="glyphicon glyphicon-star"';
			if($i<=$stars)
			{
				echo ' style="color:yellow;"';
			}
			echo '></span>';
		}

        $review=<<< review
        		{$username}
        		<span class="pull-right">{$diff_time}</span>
                <p>{$row['review_comment']}</p>
                <hr>
review;
			echo $review;          
	}
}

function add_review()
{
	if(isset($_POST['review_submit']))
	{
		if($_POST['rating']!="" && $_POST['user_id']!="" && $_POST['product_id']!="" && $_POST['review_comment']!="")
		{
			$rating=$_POST['rating'];
			$userid=$_POST['user_id'];
			$productid=$_POST['product_id'];
			$comment=escape_string($_POST['review_comment']);
			$now=time();
			$query=query("insert into reviews (userid,product_id,review_comment,review_star,review_time) values($userid,$productid,'$comment',$rating,'$now')");
			confirm($query);


			// redirect("item.php?id=$productid");
			header("refresh:0;url=item.php?id=$productid");

			echo '<script type="text/javascript">
					alert("Your Review Posted successfully.");
				  </script>';
		}
		else
		{
			echo '<script type="text/javascript">
					alert("All Review Field Must Be Filled!");
				  </script>';
		}
	}	
}

function display_reviews_for_user()
{
	$query=query("select * from reviews where userid=".$_GET['id']);
	confirm($query);

	while($row=fetch_array($query))
	{
		$review_time=microtime($row['review_time']);
		$review=<<< reviews
				<tr>
					<td>{$row['review_id']}</td>
					<td>{$row['product_id']}</td>
					<td>{$row['review_comment']}</td>
					<td>{$row['review_star']}&nbsp;<span class="glyphicon glyphicon-star" style="color:brown;"></span>
					</td>
					<td>{$review_time}</td>
				</tr>
reviews;
				echo $review;
	}
}

function average_rating()
{
	$query=query("select avg(review_star) as avg_rating from reviews where product_id=".$_GET['id']);
	confirm($query);
	$row=fetch_array($query);

	$stars=round($row['avg_rating']);
	$i=1;
	for($i=1;$i<=5;$i++)
	{
		echo '<span class="glyphicon glyphicon-star"';
		if($i<=$stars)
		{
			echo ' style="color:yellow;"';
		}
		echo '></span>';
	}
	echo "&nbsp;",$stars,".0 Stars";
}

function count_for_admin_panels($arg)
{
	if($arg=="categories")
		$sql="select count(cat_id) as num from categories";
	else
		if($arg=="products")
		$sql="select count(product_id) as num from products";
	else
		if($arg=="orders")
		$sql="select count(order_id) as num from orders";

	$query=query($sql);
	confirm($query);

	$row=fetch_array($query);
	$value=intval($row['num']);
	echo $value;
}

function count_for_user_panel($arg,$id)
{
	if($arg=="orders")
		$sql="select count(order_id) as num from orders where userid={$id}";

	$query=query($sql);
	confirm($query);

	$row=fetch_array($query);
	$value=intval($row['num']);
	echo $value;
}

function logged_in_userid($name)
{
	$query=query("select * from users where username like '".escape_string($_SESSION['user_name'])."'");
	confirm($query);
	$row=fetch_array($query);
	return $row['userid'];
}
?>