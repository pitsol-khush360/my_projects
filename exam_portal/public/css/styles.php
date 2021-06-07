<?php
				/* putting all css in echo to access $_SESSION */
echo '
#theme_button
{
	position:fixed;
	top:250px;
	left:0;
	z-index:3000;
}

#login_box
{
	position:relative;
	width:280px;
	top:50%;
	left:50%;
	transform:translate(-50%,-50%);
}

#login_box_h3
{
	float:left;
	font-size:30px;
	border-bottom:4px solid '; 
	/* echo to print color after solid */
	echo $_SESSION['theme_color'],";"; 
	
	/* again echo starts for other css */
	echo 'margin-bottom:50px;
	padding:13px 0;
}
		
.textbox
{
	width:100%;
	overflow:hidden;
	font-size:20px;
	padding:8px 0;
	margin:8px 0;
	border-bottom:1px solid '; 
	echo $_SESSION['theme_color'],";";

	echo '
}

.textbox_i
{
	width:26px;
	float:left;
	text-align:center;
}

.textbox_input
{
	border:none;
	outline:none;
	background:none;
	font-size:18px;
	width:80%;
	float:left;
	margin:0 10px;
}

#btn
{
	width:30%;
	background:none;
	border:1px solid '; 
	echo $_SESSION['theme_color'],";";

	echo '
	padding:5px;
	font-size:18px;
	cursor:pointer;
	margin:12px 0;
}

#footer
{
	position:relative;
	left:0;
	top:25px;
	bottom:25px;
	width:100%;
	text-align:center;
}

#contactus_login_box_h3
{
	margin-left:auto;
	margin-right:auto;
	font-size:30px;
	border-bottom:4px solid '; 
	/* echo to print color after solid */
	echo $_SESSION['theme_color'],";"; 
	
	/* again echo starts for other css */
	echo 'margin-bottom:50px;
	padding:13px 0;
}

#contactus_textbox_i
{
	font-size:20px;
}

#contactus_textbox_input
{
	border:none;
	outline:none;
	background:none;
	font-size:18px;
	width:80%;
	margin:0 10px;
}

#contactus_btn
{
	width:20%;
	background:none;
	border:1px solid '; 
	echo $_SESSION['theme_color'],";";

	echo '
	padding:5px;
	font-size:18px;
	cursor:pointer;
	margin-top:15px;
}

#contactus_textbox
{
	width:80%;
	overflow:hidden;
	font-size:20px;
	padding:8px 0;
	margin-right:auto;
	margin-left:auto;
	border-bottom:1px solid '; 
	echo $_SESSION['theme_color'],";";

	echo '
}
';

?>