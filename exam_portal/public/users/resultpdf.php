<?php include("validateUserMultipleLogin.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo APP; ?> - PDF</title>
</head>

<body>
<?php
	if(isset($_SESSION['username']) && isset($_SESSION['ulid']) && isset($_SESSION['pdf_data']))
	{
		ob_start();          // starting output buffering.
		
		require_once '../../resources/mcpdf/vendor/autoload.php';
		
		$stylesheet = file_get_contents('css/stylepdf.css');

      	$mpdf = new \Mpdf\Mpdf();
      	$mpdf->WriteHTML($stylesheet, 1);

        $mpdf->SetWatermarkText('AbhyasClasses');
		$mpdf->showWatermarkText = true;
		$mpdf->watermarkTextAlpha = 0.1;

		$mpdf->WriteHTML(html_entity_decode($_SESSION['pdf_data']));

        $mpdf->Output('user00'.$_SESSION['ulid'].'123result.pdf',"D");
	}
	else
		redirect("index.php?home");
?>
</body>
</html>