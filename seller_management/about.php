<?php 
	require_once("config/config.php"); 
	require_once("config/" . ENV . "_config.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo DOMAIN; ?> - Contact</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="public/font-awesome/css/fontawesome.min.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="public/font-awesome/css/all.min.css" rel="stylesheet" type="text/css">
    
    <link rel="stylesheet" href="public/css/bootstrap/bootstrap.min.css">

    <script src="public/js/jquery.min.js"></script>
    <script src="public/js/bootstrap.min.js"></script>

    <link href="public/css/app-home.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
</head>
<body>

<?php include("nav.php"); ?>

<div class="container-fluid pl-0 pr-0">
	<div id="about-div">
	    <img src="<?php echo DOMAIN; ?>/images/home/aboutus-div.jpg" id="about-div-img"/>
	    <div class="row" id="about-div-caption">
	    	<div class="col-12 text-center">
	    		<h3>About Us</h3>
	    	</div>
	    	<div class="col-12">
	    		<p>Bluetoise is Indis's leading ecommerce platform which helps sellers to sell their products online without making any investement. Our mission is to bring the technology at the doorestep of the sellers and provide them opportunities so that they can increase their footprint and customers
	    		</p>
	    	</div>
		</div>
	</div>
</div>

<div class="container-fluid pl-0 pr-0">
	<div class="row mt-4 pl-0 pr-0 mt-4" id="aboutus-card-row">

		<div class="col-12 col-md-4">
			<div class="aboutus-card">
		  		<img src="<?php echo DOMAIN; ?>/images/home/defaultpic.jpg">
				  <div class="aboutus-card-info">
				  	<div class="row mt-2">
				  		<div class="col-12">
						    <h4><b>Our About Info</b></h4> 
						    <p>This is our about info in which we will enter details for the relative header</p> 
						</div>
					</div>
				  </div>
			</div>
		</div>

		<div class="col-12 col-md-4">
			<div class="aboutus-card">
		  		<img src="<?php echo DOMAIN; ?>/images/home/defaultpic.jpg">
				  <div class="aboutus-card-info">
				  	<div class="row mt-2">
				  		<div class="col-12">
						    <h4><b>Our About Info</b></h4> 
						    <p>This is our about info in which we will enter details for the relative header</p> 
						</div>
					</div>
				  </div>
			</div>
		</div>

		<div class="col-12 col-md-4">
			<div class="aboutus-card">
		  		<img src="<?php echo DOMAIN; ?>/images/home/defaultpic.jpg">
				  <div class="aboutus-card-info">
				  	<div class="row mt-2">
				  		<div class="col-12">
						    <h4><b>Our About Info</b></h4> 
						    <p>This is our about info in which we will enter details for the relative header</p>
						</div> 
					</div>
				  </div>
			</div>
		</div>

	</div>
</div>

<!-- Footer -->
	<?php include("footer.php"); ?>

</body>
</html>