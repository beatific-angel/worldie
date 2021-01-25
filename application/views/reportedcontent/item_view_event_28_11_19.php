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
										<p class="event-title"><?php echo $results->event_planning;?></p>
										<p class="event-description"><?php echo $results->long_description;?></p>
										<span class="event_addres_time">
											<span class="event_addres">
												Address:<br>
												Country:<?php echo $results->event_country;?><br>
												City:<?php echo $results->event_city;?><br>
												Street:<?php echo $results->event_street;?><br>
											</span>
											<span class="event_time">
												Timing:<br>
												Start-Time:<?php echo $results->start_date;?><br>
												End-Time:<?php echo $results->end_date;?><br>
											</span>
										</span>
										<?php if($results->sponsered == 1){?>
											<span class="sponsered-event">
											   Sponsered Event
										    </span>
										<?php } ?>
										<?php if($results->image_url != ''){ ?>
											<image class="img-responsive" src="<?php echo $results->image_url;?>">
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