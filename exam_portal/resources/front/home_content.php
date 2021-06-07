<?php
	$current_date=date("Y-m-d H:i:s");   // gives the date in specified format.

	$qo=query("select * from offers where offer_text!=\"\" and offer_type!=\"\" and ('".$current_date."' between start_date and end_date) order by offer_id desc limit 0,5");
	confirm($qo);

	$total=mysqli_num_rows($qo);

	if(0)//$total!=0)
	{
?>
<section style="margin-top:1rem;">
	<!-- offer carousel -->
			<div class="carousel-fluid">
				<div id="offer_carousel1" class="carousel slide" data-ride="carousel">
  					<ol class="carousel-indicators">
					  	<?php
							$i=0;
							echo "<li data-target='#offer_carousel1' data-slide-to='$i' class='active'></li>";
							for($i=1;$i<$total;$i++)
							{
								$indicators=<<<indicators
								<li data-target='#offer_carousel1' data-slide-to='$i' class='active'></li>
indicators;
								echo $indicators;
							}
					  	?>
  					</ol>
  					<div class="carousel-inner">
				    	<?php
				    		$count=1;
				    		while($row=fetch_array($qo))
				    		{
				    			// showing only first offer as active for only $count=1
				    			if($row['offer_image']!="")
				    				$img_path=image_path_offer($row['offer_image']);
				    			else
				    				$img_path=image_path_offer("defaultoffer.jpg");

				    			if($count==1)
				    			{
				    				echo "<div class='carousel-item active'>";
				    				echo " <img class='d-block' src='../resources/{$img_path}' style='width:100%;height:25rem;' alt='Offer'>";
				    				echo "</div>";
				    				$count=0;
				    			}
				    			else
				    			{
				    				echo "<div class='carousel-item'>";
				    				echo " <img class='d-block' src='../resources/{$img_path}' style='width:100%;height:25rem;' alt='Offer'>";
				    				echo "</div>";
				    			}
				    		}
				    	?>
  					</div>
					  <a class="carousel-control-prev" href="#offer_carousel1" role="button" data-slide="prev">
					    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
					    <span class="sr-only">Previous</span>
					  </a>
					  <a class="carousel-control-next" href="#offer_carousel1" role="button" data-slide="next">
					    <span class="carousel-control-next-icon" aria-hidden="true"></span>
					    <span class="sr-only">Next</span>
					  </a>
				</div>
			</div>
			<!-- offer carousel ends -->
</section>
<?php
	}
?>

<section>
	<div class="jumbotron jumbotron-fluid">
	  <div class="container">
	    <h2 class="mt-3">अभ्यास क्लासेज़</h2>
	    <p class="lead mt-5">
			 सरकारी एंड करियर फाउंडेशन की तैयारी के लिए एक प्रमुख कोचिंग संस्थान।  संस्थान प्रतियोगी परीक्षाओं की तैयारी के लिए अच्छी तरह से माना जाता है और साल-दर-साल सर्वोत्तम परिणाम देता है।  अभ्यास में, हम अपनी सफलता के लिए छात्रों में ज्ञान और अवधारणाओं की एक मजबूत नींव बनाने पर ध्यान केंद्रित करते हैं और प्रतियोगी परीक्षाओं की शिक्षा की तैयारी के लिए एक उत्कृष्ट मंच प्रदान करते हैं।  सर्वोत्तम शैक्षणिक सहायता और व्यक्तिगत देखभाल जो हम छात्रों को प्रदान करते हैं, उन्हें उनके कैरियर के लक्ष्यों और उद्देश्यों को पूरा करने में मदद करता है।  दृढ़ संकल्प, ईमानदारी, प्रामाणिकता, अखंडता, भक्ति, मानवतावाद, समग्र शिक्षा, सामाजिक नैतिकता और समाज और पर्यावरण के लिए चिंता के मुख्य मूल्य सभी हमारे अकादमिक कार्यक्रमों  में बारीकी से जुड़े हुए हैं।  हमारे उच्च योग्य और सबसे अनुभवी संकाय छात्र की पूर्ण सफलता के लिए समर्पित और प्रतिबद्ध हैं और अपने सामाजिक, सांस्कृतिक, शैक्षणिक और सर्वांगीण विकास में योगदान देने के लिए सहायक परिवेश प्रदान करते हैं।

			 हमारे छात्रों के लिए, हम मूल्य-आधारित कैरियर शिक्षा, प्रचुर संसाधन और व्यक्तिगत ध्यान प्रदान करते हैं।  माता-पिता के लिए, हम बच्चों में नैतिक और जिम्मेदार कैरियर नेतृत्व का पोषण करने की जिम्मेदारी लेते है।
	    </p>
	  </div>
	</div>
</section>

<!-- About Us Section -->
<!-- A Section is already in aboutus.php -->
<?php include(TEMPLATE_FRONT.DS."aboutus.php"); ?>

<!-- contact Us Section -->
<div class="container-fluid">
<section>	
      		<p class="text-danger text-center" style="font-size:20px;"><?php displaymessage(); ?></p>
			<form class="form" method="post">
				<div class="row">
					<h3 id="contactus_login_box_h3">Contact Us</h3>
				</div>
				<div class="row">
				<div class="col-md-6 col-xs-12">
					<div id="contactus_textbox">
						<i class="fa fa-user" id="contactus_textbox_i" aria-hidden="true"></i>
						<input type="text" id="contactus_textbox_input" placeholder="Your Name" name="contact_user" required>
					</div>
				</div>
				<div class="col-md-6 col-xs-5">
					<div id="contactus_textbox">
						<i class="fa fa-envelope" id="contactus_textbox_i" aria-hidden="true"></i>
						<input type="email" id="contactus_textbox_input" placeholder="Email" name="contact_email">
					</div>
				</div>
				<div class="col-md-6 col-xs-5">
					<div id="contactus_textbox">
						<i class="fa fa-mobile" id="contactus_textbox_i" aria-hidden="true"></i>
						<input type="number" id="contactus_textbox_input" placeholder="Mobile Number" name="contact_mobile" pattern="[5-9]{1}[0-9]{9}">
					</div>
				</div>
				<div class="col-md-6 col-xs-5">
					<div id="contactus_textbox">
						<i class="fa fa-comment" id="contactus_textbox_i" aria-hidden="true"></i>
						<input type="text" id="contactus_textbox_input" placeholder="Your Query" name="contact_query" required>
					</div>
				</div>
				</div>
				<!-- hidden input field for handling csrf --> 
        		<input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
        		<div class="row">
        			<div class="col-xs-12 col-md-12 text-center">
						<button id="contactus_btn" type="submit" name="post_query">Post Query</button>
					</div>
				</div>
			</form>
    	</div>
</div>
</section>
</div>
<!-- Contact Us Section Ends -->