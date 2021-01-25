<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//echo"<pre>";
//print_r($results);die;
?>
<style>
event-title {
   color: #000;
   float: left;
   font-size: 18px;
   width: 100%;
}

.event-description {
   float: left;
   font-size: 13px;
   width: 100%;
}

.event_addres_time {
   float: left;
   width: 100%;
}

.event_addres {
   float: left;
   font-size: 15px;
   width: 50%;
}

.event_time {
   float: right;
   font-size: 15px;
   width: 50%;
   text-align:right;
}

.sponsered-event {
   color: green;
   float: left;
   font-size: 14px;
   margin: 10px 0 10px 0;
   width: 100%;
}
</style>
<?php $base_url_front = $this->config->item('base_url_front'); ?>
<!-- page content -->
	<div class="right_col" role="main">
		<div class="" >
				<div style="background:#eee;" class="page-title">
					<center><h3 style=" padding-bottom:10px;">Group Content</h3></center>
				</div>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_content">
								<div class="row">
									<div class="col-md-12">
										<p class="event-title"><?php echo $results->name;?></p>
										<p class="event-description"><?php echo $results->summary;?></p>
										<?php if($results->banner_image != '' && file_exists($this->config->item('parent_folder_name').$results->banner_image)){ ?>
                                                                                        <p class="event-description">Banner Image</p>
											<image class="img-responsive" src="<?php echo $base_url_front.$results->banner_image;?>">
										<?php }  ?>
                                                                                <?php if($results->display_image != '' && file_exists($this->config->item('parent_folder_name').$results->display_image)){ ?>
                                                                                        <p class="event-description">Display Image</p>
											<image class="img-responsive" src="<?php echo $base_url_front.$results->display_image;?>">
										<?php }  ?>
										<br>
										<span style="float:left; font-size:13px;">Date:<?php echo date('Y-m-d', strtotime($results->created_at));?></span>
										<span style="float:right; font-size:13px;">Posted By:<?php echo $results->created_by;?></span>
									</div>
								</div>
						</div>
					</div>
				</div>
		</div>	
	</div>
<!-- /page content -->