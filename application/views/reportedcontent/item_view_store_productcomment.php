<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//echo"<pre>";print_r($results);die;
?>
<!-- page content -->
	<div class="right_col" role="main">
		<div class="" >
				<div style="background:#eee;" class="page-title">
					<center><h3 style=" padding-bottom:10px;">Store Product Comment</h3></center>
				</div>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_content">
								<div class="row">
									<div class="col-md-12">
										<p style="font-size:15px;"><?php echo $results->comment;?></p>
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