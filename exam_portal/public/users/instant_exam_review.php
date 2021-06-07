<?php include("validateUserMultipleLogin.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo APP; ?> - Exam Review</title>
</head>

<body>
<?php
	if(isset($_SESSION['username']) && isset($_SESSION['ulid']) && isset($_SESSION['pdf_data']))
	{
		echo html_entity_decode($_SESSION['pdf_data']);
	}
	else
		redirect("index.php?home");
?>
</body>
</html>