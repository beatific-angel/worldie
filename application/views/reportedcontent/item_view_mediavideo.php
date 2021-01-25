<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="<?php echo base_url();?>assets/vendors/video-player/video-js.css" rel="stylesheet">
<!-- If you'd like to support IE8 -->
<script src="<?php echo base_url();?>assets/vendors/video-player/videojs-ie8.min.js"></script>
<script src="<?php echo base_url();?>assets/vendors/video-player/video.js"></script>
<style>
    .my-video-dimensions{ width:100%;}
    .video-js .vjs-big-play-button{ left:42%; top:40%;}
</style>
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
										<div class="row">
											<video id="my-video" class="video-js" controls preload="auto" width="640" height="264" data-setup="{}">
	                                            <source src="<?php echo web_url().$results->video_location;?>" type="video/<?php echo $results->video_ext;?>">
	                                            Your browser does not support the video tag.
	                                        </video>
										</div>
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
<!-- /page content -->