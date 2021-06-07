<?php 
	require_once("config/config.php"); 
	require_once("config/" . ENV . "_config.php");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?php echo DOMAIN; ?> - Home Page</title>

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

	  <div id="homeSlider" class="carousel slide mt-5" data-ride="carousel">
	    <ol class="carousel-indicators">
	      <li data-target="#homeSlider" data-slide-to="0" class="active"></li>
	      <li data-target="#homeSlider" data-slide-to="1"></li>
	      <li data-target="#homeSlider" data-slide-to="2"></li>
	    </ol>
	    <div class="carousel-inner">

	      <div class="carousel-item active" style="z-index:1;">
	        <div class="container-fluid pl-1 pr-1 pl-md-5 pr-md-5" style="background-color:#0063B2;">
				<div class="row">
					<div class="col-12 col-md-8">

						<div class="jumbotron text-white" style="background-color:#0063B2;">
						  <h2>Welcome to Bluetoise Technologies</h2>
						  <hr class="my-4">
						  <h6>Create your online store in just 2 min and start selling at no cost</h6>
						  <p class="lead mt-5">
						    
						  </p>
						</div>

					</div>
					<div class="col-12 col-md-4">
						<img src="<?php echo DOMAIN; ?>/images/home/person.png" class="w-100" style="height:400px;">
					</div>
				</div>
			</div>
	      </div>

	      <div class="carousel-item" style="z-index:1;">
	        <div class="container-fluid pl-1 pr-1 pl-md-5 pr-md-5" style="background-color:#0063B2;">
				<div class="row">
					<div class="col-12 col-md-8">

						<div class="jumbotron text-white" style="background-color:#0063B2;">
						  <h2>Welcome to Bluetoise Technologies</h2>
						  <hr class="my-4">
						  <h6>Share your products with customers on whatspp and facebook</h6>
						  <p class="lead mt-5">
						    
						  </p>
						</div>

					</div>
				</div>
			</div>
	      </div>

	      <div class="carousel-item" style="z-index:1;">
	        <div class="container-fluid pl-1 pr-1 pl-md-5 pr-md-5" style="background-color:#0063B2;">
				<div class="row">
					<div class="col-12 col-md-8">

						<div class="jumbotron text-white" style="background-color:#0063B2;">
						  <h2>Welcome to Bluetoise Technologies</h2>
						  <hr class="my-4">
						  <h6>
						  		<p>Easy to create catalogue and products</p>
								<p>Integrated with payment gatways</p>
								<p>Ship your products pan India with our logicistic parterns at very low cost (comming soon)</p>
						  </h6>
						  <p class="lead mt-5">
						    
						  </p>
						</div>

					</div>
				</div>
			</div>
	      </div>
	    </div>
	    <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
	      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
	      <span class="sr-only">Previous</span>
	    </a>
	    <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
	      <span class="carousel-control-next-icon" aria-hidden="true"></span>
	      <span class="sr-only">Next</span>
	    </a>
	  </div>
	</div>

	<div class="container-fluid pl-0 pr-0">
		<div class="row pl-0 pr-0 mt-3" id="cards-row">
			<div class="col-12 col-md-3 p-0">

				<div class="card">
		            <p class="card-title">Profile</p>
		            <p class="card-text">Click to see or edit your profile page.</p>
		      	</div>

			</div>
			<div class="col-12 col-md-3 p-0">

				<div class="card">
		            <p class="card-title">About Us</p>
		            <p class="card-text">Our About Us Information</p>
		      	</div>

			</div>
			<div class="col-12 col-md-3 p-0">

				<div class="card">
		            <p class="card-title">Contact Us</p>
		            <p class="card-text">To Contact Us</p>
		      	</div>

			</div>
			<div class="col-12 col-md-3 p-0">

				<div class="card">
		            <p class="card-title">Other Info</p>
		            <p class="card-text">Other type of information</p>
		      	</div>

			</div>
		</div>
	</div>

	<!-- Footer -->
	<?php include("footer.php"); ?>
</body>
</html>