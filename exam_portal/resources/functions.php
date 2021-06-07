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
	// handling csrf (cross site request forgery)
	if (!isset($_SESSION['csrf_name']))
	{
		$_SESSION['csrf_name']=md5(rand());         // md5() is used for changing value in hash value.
		$_SESSION['csrf_value']=md5(rand());
	}
	if(count($_POST)!=0)
	{
		// checking for other post request, not for payment response.
		if(!isset($_POST['txnStatus']))
		{
			if(!isset($_POST[$_SESSION['csrf_name']]))
				exit;

			if($_POST[$_SESSION['csrf_name']]!=$_SESSION['csrf_value'])
			{
				exit;
			}
		}

		unset($_POST[$_SESSION['csrf_name']]);
		$_SESSION['csrf_name']=md5(rand());         // md5() is used for changing value in hash value.
		$_SESSION['csrf_value']=md5(rand());
	}
?>

<?php

foreach ($_POST as $key => $value) 
{
	// if(gettype($_POST[$key])!="array")
	// {
	// 	$_POST[$key]=htmlentities($_POST[$key]);
	// 	$_POST[$key]=addslashes($_POST[$key]);
	// }
	// else
	// {	
	// 	foreach ($_POST[$key] as $k => $v) 
	// 	{
	// 		$_POST[$key][$k]=htmlentities($_POST[$key][$k]);
	// 		$_POST[$key][$k]=addslashes($_POST[$key][$k]);
	// 	}
	// }

	// after 2d array logic came
	if(gettype($_POST[$key])!="array")
	{
		$_POST[$key]=htmlentities($_POST[$key]);
		$_POST[$key]=addslashes($_POST[$key]);
	}
	else 						// if array
	{	
		foreach ($_POST[$key] as $k => $v) 
		{
			if(is_array($_POST[$key][$k]))
			{
				foreach ($v as $s => $t)
				{
					$_POST[$key][$k][$s]=htmlentities($_POST[$key][$k][$s]);
					$_POST[$key][$k][$s]=addslashes($_POST[$key][$k][$s]);
				}
			}
			else
			{
				foreach ($_POST[$key] as $k => $v) 
				{
					$_POST[$key][$k]=htmlentities($_POST[$key][$k]);
					$_POST[$key][$k]=addslashes($_POST[$key][$k]);
				}
			}
		}
	}
}

foreach ($_GET as $key => $value) 
{
	if(gettype($_GET[$key])!="array")
	{
		$_GET[$key]=htmlentities($_GET[$key]);
		$_GET[$key]=addslashes($_GET[$key]);
	}
	else
	{	
		foreach ($_GET[$key] as $k => $v) 
		{
			$_GET[$key][$k]=htmlentities($_GET[$key][$k]);
			$_GET[$key][$k]=addslashes($_GET[$key][$k]);
		}
	}
}


// for redirection
function redirect($location)
{
	header("refresh:0;url=$location");
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

function image_path_profile($path)
{
	// we can't access path like c:\xampp\... because we have to pass path from file so 
	// we will not use constant USERPROFILE_UPLOAD
	return "userprofile_upload".DS.$path;
}

function user_signin()
{
	if($_POST['password']!="" && $_POST['username']!="")
	{
		if(strlen(trim($_POST['password']))>=6 && strlen(trim($_POST['password']))<=15)
		{
			$username=escape_string(trim($_POST['username']));
			$password=escape_string(trim($_POST['password']));

			$query=query("select * from user_login where username like '".$username."' and password like '".$password."'");
			confirm($query);

			$row=fetch_array($query);

			if(mysqli_num_rows($query)!=0 && $row['blocked']!=1)
			{
				$_SESSION['username']=$username;
				$_SESSION['ulid']=$row['userid'];

				$token=getToken(10);
				
				$_SESSION['user_token']=$token;

				$q_ut=query("update user_login set login_token='".$token."' where userid='".$row['userid']."'");
				confirm($q_ut);
				redirect("users/index.php?home");
			}
			else
			if(mysqli_num_rows($query)!=0 && $row['blocked']==1)
			{
				setmessage("<p class='text-danger text-center'>Sorry! Your Account Is Disabled.Please Contact Admin For more information.</p>");
			}
			else
			{
				setmessage("<p class='text-danger text-center'>Invalid Login Credentials !</p>");
				// redirect("signin.php");
			}
		}
		else
			setmessage("<p class='text-danger text-center'>Password Length Must Be Between 4 To 15</p>");
	}
	else
	if($_POST['password']=="" && $_POST['username']!="")
	{
		setmessage("<p class='text-danger text-center'>Password Must Be Filled !</p>");
		// redirect("signin.php");
	}
	else
	if($_POST['password']!="" && $_POST['username']=="")
	{
		setmessage("<p class='text-danger text-center'>Username Must Be Filled !</p>");
		// redirect("signin.php");
	}
	else
	if($_POST['password']=="" && $_POST['username']=="")
	{
		setmessage("<p class='text-danger text-center'>Username and password Must Be Filled !</p>");
		// redirect("signin.php");
	}
}

function getToken($length)
{
    $token="";
    $tokenCharStream = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $tokenCharStream.= "abcdefghijklmnopqrstuvwxyz";
    $tokenCharStream.= "0123456789";
    $max=strlen($tokenCharStream);

    for($i=0; $i < $length; $i++) 
    {
        $token.=$tokenCharStream[rand(0,$max-1)];
    }

    return $token;
}

function user_signup()
{
	if(!empty($_POST['password']) && !empty($_POST['username']) && !empty($_POST['mobile']))
	{
		if(strlen(trim($_POST['password']))>=6 && strlen(trim($_POST['password']))<=15)
		{
			$username=trim($_POST['username']);
			$password=trim($_POST['password']);
			$mobile=trim($_POST['mobile']);

 			// $check_user,mysqli_num_rows($check) For Checking if username already exists In table // users.

 			$check_user=query("select * from user_login where username like '".escape_string($username)."'");
 			confirm($check_user);

 			$check_mobile=query("select * from user_personal where mobile='".escape_string($mobile)."'");
 			confirm($check_mobile);

 			if(mysqli_num_rows($check_user)!=0)  // If Already Exists.
 			{
 				setmessage("<p class='text-danger text-center'>Username Already Taken!</p>");
 			}
 			else
 			if(mysqli_num_rows($check_mobile)!=0)	
 			{
 				setmessage("<p class='text-danger text-center'>Entered Mobile Number Is Already Registered!</p>");
 			}
 			if(mysqli_num_rows($check_user)==0 && mysqli_num_rows($check_mobile)==0)
 			{
 				$query=query("insert into user_login (username,password) values('$username','$password')");
 				confirm($query);

 				$q1=query("select * from user_login where username='".$username."' and password='".$password."'");
 				confirm($q1);
 				$r1=fetch_array($q1);


 				// $recentid=last_insert_id();
 				/*
 					mysql_insert_id() returns
					The ID generated for an AUTO_INCREMENT column by the previous query on success, 0 if the previous query does not generate an AUTO_INCREMENT value, or FALSE if no MySQL connection was established.but it only works when column(field) type is not BIGINT.if bigint then use Object Oriented Approach.
 				*/
 				if(mysqli_num_rows($q1)!=0)
 				{
 					$qp=query("insert into user_personal (ulid,mobile) values('".$r1['userid']."','".$mobile."')");
 					confirm($qp);
 				}

 				redirect("signin.php?verifyaccount");
 			}
		}
		else
			setmessage("<p class='text-danger text-center'>Password Length Must Be Between 4 To 15</p>");
	}
	else
	if(empty($_POST['password']) && !empty($_POST['username']) && !empty($_POST['mobile']))
	{
		setmessage("<p class='text-danger text-center'>Password Must Be Filled !</p>");
	}
	else
	if(!empty($_POST['password']) && empty($_POST['username']) && !empty($_POST['mobile']))
	{
		setmessage("<p class='text-danger text-center'>Username Must Be Filled !</p>");
	}
	else
	if(!empty($_POST['password']) && !empty($_POST['username']) && empty($_POST['mobile']))
	{
		setmessage("<p class='text-danger text-center'>Mobile Number Must Be Entered !</p>");
	}
	else
	if(empty($_POST['password']) && empty($_POST['username']) && empty($_POST['mobile']))
	{
		setmessage("<p class='text-danger text-center'>All Fields Must Not Be Blank !</p>");
	}
}

function user_signup_complete()
{
	if(isset($_POST['user_signup_complete']))
	{
	if(empty($_FILES['profile_picture']['name']) || $_FILES['profile_picture']['type']=="image/jpg" || $_FILES['profile_picture']['type']=="image/jpeg" || $_FILES['profile_picture']['type']=="image/png")
	{
		$username=trim(escape_string($_POST['username']));
		$password=trim(escape_string($_POST['password']));
		$email=trim(escape_string($_POST['email']));
		$name=trim(escape_string($_POST['firstname']));
		$district=trim(escape_string($_POST['district']));
		$state=trim(escape_string($_POST['state']));
		$country=trim(escape_string($_POST['country']));
		$mobile=trim(escape_string($_POST['mobile_number']));
		$address=trim(escape_string($_POST['address']));
		$profile=escape_string($_FILES['profile_picture']['name']);
		$profile_tmp=escape_string($_FILES['profile_picture']['tmp_name']);
		$ulid=$_POST['ulid'];

		if(empty($profile))
		{
			$profile=$_POST['hidden_profile_picture'];
		}
		else
		{
			// renaming image so that duplicate name images can't be stored
			$profile=date("Ymd").'up'.$ulid.'img'.$profile;
			move_uploaded_file($_FILES['profile_picture']['tmp_name'],USERPROFILE_UPLOAD.DS.$profile);
		}

		$see_new_user=query("select * from user_personal where ulid='".$_POST['ulid']."'");
		confirm($see_new_user);
		$row_see_new_user=fetch_array($see_new_user);

		if(mysqli_num_rows($see_new_user)==0)
		{
			// if user not found then insert
			$query=query("insert into user_personal (ulid,name,email,mobile,address,country,state,district,profile_picture) values('$ulid','$name','$email','$mobile','$address','$country','$state','$district','$profile')");
			confirm($query);
			redirect("users/index.php?profile");
		}
		else
		{
			// if user found then only update.

			// removing old image before soring new image name in table
			if($row_see_new_user['profile_picture']!="" && $row_see_new_user['profile_picture']!=$profile && $row_see_new_user['profile_picture']!="defaultpic.jpg")
				unlink(USERPROFILE_UPLOAD.DS.$row_see_new_user['profile_picture']);

			$query="update user_personal set ";
			$query.="name='{$name}', ";
			$query.="district='{$district}', ";
			$query.="state='{$state}', ";
			$query.="country='{$country}', ";
			$query.="email='{$email}', ";
			$query.="address='{$address}', ";
			$query.="mobile='{$mobile}', ";
			$query.="profile_picture='{$profile}'";
			$query.=" where ulid='".$_POST['ulid']."'";

			$query=query($query);
			confirm($query);
			$_SESSION['username']=$username; // new user so set session['username'];
			redirect("users/index.php?profile");
		}
	}
	else
		setmessage("Please Upload Valid Profile Image Type");
	}
}

function send_otp()
{
	if(isset($_POST['reset_password']))
	{	
		if((!empty($_POST['email']) && !empty($_POST['new_password']) && ($_POST['new_password']==$_POST['confirm_password'])) || (!empty($_POST['mobile']) && !empty($_POST['new_password']) && ($_POST['new_password']==$_POST['confirm_password'])))
		{
			$query=query("select * from user_personal where email like '".$_POST['email']."'");
			confirm($query);
			$row=fetch_array($query);

			if(mysqli_num_rows($query)!=0)
			{
				// mt_rand() and rand() are used to generate random number in php
				// eg: mt_rand(100,1000) returns number between 100 to 1000
				$otp=mt_rand(10000,1000000);
				$to=$_POST['email'];
				$from_name="AbhyasClasses";
				$subject="OTP For changing password";
				$message="Dear User OTP For Changing Your Password Is ".$otp." Do Not Share It With Anyone.";

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
		    	$mail->Body = "You Have Recieved The Message From {$from_name}<br><i>".$message."</i>";
		    	// $mail->AltBody = "This is the plain text version of the email content";

		    	if(!$mail->send())
		    	{  	
		    		setmessage("Failed to send the message ".$mail->ErrorInfo."");
		    	}
		    	else
		    	{
		       		$_SESSION['otp']=$otp;
		       		$_SESSION['ulid']=$row['ulid'];
		       		$_SESSION['new_password']=trim($_POST['new_password']);
					redirect("verifyotp.php?otpsent");
		    	}
			}
			else
				setmessage("<p class='text-danger' style='font-size:25px;'>Invalid Email.Update Your Current Email</p>");
		}
		else
		if($_POST['new_password']!=$_POST['confirm_password'])
		{
			setmessage("<p class='text-center text-danger' style='font-size:25px;'>New Password And Confirm Password Must Be Same</p>");
		}
		else
		{
			setmessage("<p class='text-center text-danger' style='font-size:25px;'>At Least one field between email and mobile must be filled and new password must be filled.</p>");
		}
	}
}

function update_password($password,$ulid)
{
	if(trim($password)!="")
	{
		$password=trim($password);
		$query=query("update user_login set password='".$password."' where userid='".$ulid."'");
		confirm($query);
	
		unset($_SESSION['otp']);
		unset($_SESSION['new_password']);

		setmessage("<p class='text-success text-center' style='font-size:25px;'>Congratulation,Your Password Has Been Updated Successfully</p>");

		redirect("verifyotp.php?passwordchanged");
	}
}

function totalquestions($section)
{
	if(isset($_SESSION['qids']))
	{
		$a=$_SESSION['qids'];
		$count=0;

		foreach($a as $v)
		{
			if($v!="" && !is_null($v))
				$count++;
		}
		return $count;
	}
}

function show_questions_grid($section)
{
	$i=1;                        // for question numbering.

	if(isset($_SESSION['qids']))
		$temp=$_SESSION['qids'];

	$r='<div class="row">';

	foreach($temp as $value) 
	{
		if($value!="" && !is_null($value))
		{
			$question=<<< que
			<button class="gridquestionbutton" id="{$value}" value="{$i}">
				{$i}
			</button>&nbsp;
que;
			$r.=$question;	

			$i++;
		}
	}
	$r.='</div>';

	return $r;
}

function preference_selection()
{	
	$qc=query("select * from course_category");
	confirm($qc);

	echo '<form class="form" method="post" action="index.php?home">
			<div class="card">';

	while($rc=fetch_array($qc))
	{
		$qsc=query("select * from sub_category where ccid='".$rc['ccid']."'");
		confirm($qsc);

		echo "<div class='card-header' style='margin-top:15px;'><b>{$rc['category_name']}</b></div>
				<div class='card-body'>
					<ul class='list-inline'>";

		while($rsc=fetch_array($qsc))
		{
			$list=<<< list
			<li>
				<label>
					<input type="checkbox" name="c[{$rsc['scid']}]" value="{$rsc['scid']}">&nbsp;{$rsc['sub_category_name']}
				</label>
			</li>
list;
			echo $list;
		}

		echo '</ul>
				</div>';
	}

	echo "</div>
				<input type=\"hidden\" name=\"ulid\" value=\"{$_SESSION['ulid']}\">
				<input type=\"hidden\" name=\"{$_SESSION["csrf_name"]}\" value=\"{$_SESSION["csrf_value"]}\">
					<button type=\"submit\" class=\"btn btn-primary\" name=\"exam_choice_selected\" style='margin-top:15px;'>Apply Selection</button>
						</form>";
}

function exam_set_preference()
{
	if(isset($_POST['exam_choice_selected']))
	{
		$qp=query("select * from user_test_preference where ulid='".$_SESSION['ulid']."'");
		confirm($qp);

		// Making String from selected ids
		$checkboxes=$_POST['c'];
		$con="";
		foreach($checkboxes as $key => $value) 
		{
			$con.=$value."#";
		}

		if(mysqli_num_rows($qp)==0)
		{
				$query=query("insert into user_test_preference (ulid,scid) values('{$_SESSION['ulid']}','{$con}')");
				confirm($query);
		}
		else
		{

			$query=query("update user_test_preference set scid='".$con."' where ulid='".$_SESSION['ulid']."'");
			confirm($query);
		}
		redirect("index.php?home");
	}
}

// For Setting Preference For Practice
function practice_set_preference()
{
	if(isset($_POST['practice_choice_selected']))
	{
		$qp=query("select * from user_practice_preference where ulid='".$_SESSION['ulid']."'");
		confirm($qp);

		// Making String From Selected qstid
		$checkboxes=$_POST['c'];
		$con="";
		foreach($checkboxes as $key => $value) 
		{
			$con.=$value."#";
		}

		if(mysqli_num_rows($qp)==0)
		{
			$query=query("insert into user_practice_preference (ulid,qstid) values('{$_SESSION['ulid']}','{$con}')");
			confirm($query);
		}
		else
		{
			$qu=query("update user_practice_preference set qstid='".$con."' where ulid='".$_SESSION['ulid']."'");
			confirm($qu);
		}
		redirect("index.php?home");
	}
}

function totalvisitors()
{
	$query=query("select visitor_count from new_visitors");
	confirm($query);
	$row=fetch_array($query);
	echo $row['visitor_count'];
}

function totalusers()
{
	$query=query("select count(userid) from user_login");
	confirm($query);
	$row=fetch_array($query);
	echo intval($row['count(userid)']);
}

// function show_cources_for_payment()
// {
// 	if(isset($_SESSION['ulid']))
// 	{
// 		$query=query("select * from user_test_preference where ulid='".$_SESSION['ulid']."'");
// 		confirm($query);

// 		$cources="";
// 		$str="";
// 		$count=0;

// 		while($row=fetch_array($query))
// 		{
// 			$q_sc=query("select * from sub_category where scid='".$row['scid']."'");
// 			confirm($q_sc);

// 			while($r_sc=fetch_array($q_sc))
// 			{
// 				$q_cc=query("select * from course_category where ccid='".$r_sc['ccid']."'");
// 				confirm($q_cc);

// 				while($r_cc=fetch_array($q_cc))
// 				{
// 					$temp=explode(",",$str);

// 					foreach($temp as $v)
// 					{
// 						if($v!="" && $v!="undefined")
// 						{
// 							if($r_cc['category_name']==$v)
// 								$count++;
// 						}
// 					}

// 					if($count==0)
// 					{
// 						$cources.=<<< cources
// 						<option value="{$r_cc['category_name']}" c_id="{$r_cc['ccid']}">{$r_cc['category_name']}</option>
// cources;
// 						$str.=$r_cc['category_name'].",";
// 					}
// 					else
// 					{
// 						$count=0;
// 					}
// 				}
// 			}
// 		}
// 		echo $cources;
// 	}
// }

function image_path_offer($path)
{
	return "useroffer_upload".DS.$path;
}

function image_path_question($path)
{
	return "adminquestion_upload".DS.$path;
}

function resolve_user_query()
{
	if($_POST['rqmode']=="email")
	{
		if(!empty($_POST['rquery']))
		{
				$to=$_POST['email'];
				$from_name="AbhyasClasses";
				$subject="On Behalf Of Your Query";
				$message=$_POST['rquery'];

				/*Construct email*/
				$mail = new PHPMailer;
		    	//To Enable SMTP debugging uncomment following line
		    	// $mail->SMTPDebug = 3;
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
		    	$mail->Port = 587;         // 25 587 465
		    
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
		    	$mail->Body = "You Have Recieved The Message From {$from_name}<br><i>".$message."</i>";
		    	// $mail->AltBody = "This is the plain text version of the email content";

		    	if(!$mail->send())
		    	{  	
		    		setmessage("Failed to send the message ".$mail->ErrorInfo."");
		    	}
		    	else
		    	{
		    		$qu=query("update user_query set uq_status=1 where uq_id='".$_POST['uqid']."'");
		    		confirm($query);
					redirect("admin_user_query.php");
		    	}	
		}
		else
			setmessage("<p class='text-center text-danger'>Please Fill Query Field</p>");
	}
	else
	if(isset($_POST['rqmode']) && $_POST['rqmode']=="mobile")
	{
		
	}
}
?>