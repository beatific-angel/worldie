<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
	.img-responsive{	
	max-height: 500px !important;
    margin: 0 auto 0 !important;
	}
</style>
<!-- page content -->
	<div class="right_col" role="main">
		<div class="" >
				<div style="background:#eee;" class="page-title">
					<center><h3 style=" padding-bottom:10px;">Content</h3></center>
				</div>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_content">
								<div class="row">
									<div class="col-md-12">
										<p style="font-size:15px;"><?php echo $results->content;?></p>
										<span style="float:left; font-size:13px;;font-weight: bold;">Date:<?php echo date('Y-m-d', strtotime($results->created_at));?></span>
										<span style="float:right; font-size:13px;;font-weight: bold;">Posted By:<?php echo $results->first_name.' '.$results->last_name;?></span>
									</div>
								</div>
						</div>
					</div>
				</div>
		</div>	
	</div>