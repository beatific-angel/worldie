<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//echo"<pre>";
//print_r($results);die;
?>

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
<?php $base_url_front = $this->config->item('base_url_front'); ?>
<!-- page content -->
	<div class="right_col" role="main">
		<div class="" >
				<div style="background:#eee;" class="page-title">
					<center><h3 style=" padding-bottom:10px;"><?php echo $title ?></h3></center>
				</div>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_content">
								<div class="row">
									<div class="col-md-12">
										
                                                                                <div class="slideshow-container">
                                                                                    <?php if($results) :  ?>
                                                                                        <?php $i =1; foreach($results as $album){?>
                                                                                        <div class="mySlides">
                                                                                            <?php $dtitle = $title == 'Videos' ? 'Video' : 'Image';  ?>
                                                                                            <p class="text-center"><a href="javascript:void(0)" class="btn btn-danger delete" data-id="<?php echo $album->id; ?>" data-title="<?php echo $dtitle; ?>">Delete This <?php echo $dtitle ?></a></p>
                                                                                            <div class="numbertext"><?php echo $i;?> / <?php echo count($results);?></div>
                                                                                                <?php if($title == 'Videos') :  ?>
                                                                                                    <video id="my-video" class="video-js" controls preload="auto" width="640" height="264" data-setup="{}">
                                                                                                        <source src="<?php echo $base_url_front.$album->image_name;?>" type="video/mp4">
                                                                                                        Your browser does not support the video tag.
                                                                                                    </video>
                                                                                                <?php else : ?>
                                                                                                    <img src="<?php echo $base_url_front.$album->image_name;?>" style="width:100%">
                                                                                                <?php endif; ?>
                                                                                        </div>
                                                                                        <?php $i++; } ?>
                                                                                        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                                                                                        <a class="next" onclick="plusSlides(1)">&#10095;</a>
                                                                                    <?php else : ?>
                                                                                        <p>No <?php echo $title; ?> Available</p>
                                                                                    <?php endif;?>
                                                                                </div>
									</div>
								</div>
						</div>
					</div>
				</div>
		</div>	
	</div>
	<script>
                $(document).delegate('.delete','click',function(){
                    var id = $(this).data('id');
                    var title = $(this).data('title');
                    request_url = '<?php echo base_url();?>users/deleteUserImage';
                    $.confirm({
                        title: 'Do you Want to delete this!',
                        //content: 'Simple confirm!',
                        buttons: {
                            confirm: function () {
                                $.ajax({
                                    url: request_url,
                                    type: "POST",
                                    data: {id:id, title : title},
                                    success:function(response){
                                        location.reload();
                                    }   	
                                });
                            },
                            cancel: function () {

                            }
                        }
                    });
                });
		var slideIndex = 1;
		showSlides(slideIndex);

		function plusSlides(n) {
                    stopAllVideos();
		  showSlides(slideIndex += n);
		}

		function currentSlide(n) {
		  showSlides(slideIndex = n);
                  stopAllVideos();
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
                function stopAllVideos(){
                    var videoList = document.getElementsByTagName("video");
                    for (var i = 0; i < videoList.length; i++) {
                        videoList[i].pause();
                    }
                }
	</script>
<!-- /page content -->