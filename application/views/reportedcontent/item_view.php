<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//echo"<pre>";
//print_r($results);die;
?>
<?php if($results->post_type == 4){ ?>
	<style>
	* {box-sizing:border-box}
	body {font-family: Verdana,sans-serif;margin:0}
	.mySlides {display:none;text-align: center;}
    
	/* Slideshow container */
	.slideshow-container {
	  max-width: 1000px;
	  position: relative;
	  margin: auto;
	}

	/* Next & previous buttons */
	.prev, .next {
	  cursor: pointer;
	  position: absolute;
	  top: 50%;
	  width: auto;
	  padding: 16px;
	  margin-top: -22px;
	  color: #dfdfdf;
	  font-weight: bold;
	  font-size: 18px;
	  transition: 0.6s ease;
	  border-radius: 0 3px 3px 0;
	}

	/* Position the "next button" to the right */
	.next {
	  right: 0;
	  border-radius: 3px 0 0 3px;
	}

	/* On hover, add a black background color with a little bit see-through */
	.prev:hover, .next:hover {
	  background-color: rgba(0,0,0,0.8);
	}

	/* Caption text */
	.text {
	  color: #f2f2f2;
	  font-size: 15px;
	  padding: 8px 12px;
	  position: absolute;
	  bottom: 8px;
	  width: 100%;
	  text-align: center;
	}

	/* Number text (1/3 etc) */
	.numbertext {
	  color: #000000;
	  font-size: 12px;
	  padding: 8px 12px;
	  position: absolute;
	  top: 0;
	}

	/* The dots/bullets/indicators */
	/*.dot {
	  cursor: pointer;
	  height: 15px;
	  width: 15px;
	  margin: 0 2px;
	  background-color: #bbb;
	  border-radius: 50%;
	  display: inline-block;
	  transition: background-color 0.6s ease;
	}*/

	.active, .dot:hover {
	  background-color: #717171;
	}

	/* Fading animation */
	.fade {
	  -webkit-animation-name: fade;
	  -webkit-animation-duration: 1.5s;
	  animation-name: fade;
	  animation-duration: 1.5s;
	}

	@-webkit-keyframes fade {
	  from {opacity: .4} 
	  to {opacity: 1}
	}

	@keyframes fade {
	  from {opacity: .4} 
	  to {opacity: 1}
	}

	/* On smaller screens, decrease text size */
	@media only screen and (max-width: 300px) {
	  .prev, .next,.text {font-size: 11px}
	}
	</style>
 <?php } ?>
<!-- page content -->
	<div class="right_col" role="main">
		<div class="" >
				<div style="background:#eee;" class="page-title">
					<center><h3 style=" padding-bottom:10px;">Reported Content</h3></center>
				</div>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_content">
								<div class="row">
									<div class="col-md-12">
										<p style="font-size:15px;"><?php echo $results->content;?></p>
										<?php   if($results->post_type == 1){
										            if($results->image_id != 0){ ?>
														<image class="img-responsive" src="<?php echo $results->image_url;?>">
										<?php }}elseif($results->post_type == 4){ 
										        if(count($results->album_items) > 0){
										?> 
											<div class="slideshow-container">
												<?php $i =1; foreach($results->album_items as $album){?>
												<div class="mySlides">
												 <div class="numbertext"><?php echo $i;?> / <?php echo count($results->album_items);?></div>
												  <img src="<?php echo $album->image_url;?>" style="width:100%">
												</div>
												<?php $i++; } ?>
												<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
												<a class="next" onclick="plusSlides(1)">&#10095;</a>
												</div>
										<?php } } ?> 
										<br>
										<span style="float:left; font-size:13px;">Date:<?php echo date('Y-m-d', strtotime($results->created_at));?></span>
										<span style="float:right; font-size:13px;">Posted By:<?php echo $results->first_name.' '.$results->last_name;?></span>
									</div>
								</div>
						</div>
					</div>
				</div>
		</div>	
	</div>
	<script>
		var slideIndex = 1;
		showSlides(slideIndex);

		function plusSlides(n) {
		  showSlides(slideIndex += n);
		}

		function currentSlide(n) {
		  showSlides(slideIndex = n);
		}

		function showSlides(n) {
		  var i;
		  var slides = document.getElementsByClassName("mySlides");
		  var dots = document.getElementsByClassName("dot");
		  if (n > slides.length) {slideIndex = 1}    
		  if (n < 1) {slideIndex = slides.length}
		  for (i = 0; i < slides.length; i++) {
			  slides[i].style.display = "none";  
		  }
		  for (i = 0; i < dots.length; i++) {
			  dots[i].className = dots[i].className.replace(" active", "");
		  }
		  slides[slideIndex-1].style.display = "block";  
		  dots[slideIndex-1].className += " active";
		}
	</script>
<!-- /page content -->