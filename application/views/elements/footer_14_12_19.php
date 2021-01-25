<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- footer content -->
        <footer>
          <div class="pull-right">
			<p>Copyright &copy; <?php echo date("Y"); ?> Worldlie. All rights reserved.</p>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    
    <!-- Bootstrap -->
    <script src="<?php echo base_url();?>assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url();?>assets/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="<?php echo base_url();?>assets/vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="<?php echo base_url();?>assets/vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="<?php echo base_url();?>assets/vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="<?php echo base_url();?>assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="<?php echo base_url();?>assets/vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="<?php echo base_url();?>assets/vendors/skycons/skycons.js"></script>
	 <!-- Datatables -->
    <script src="<?php echo base_url();?>assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url();?>assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
	<script src="<?php echo base_url();?>assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo base_url();?>assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="<?php echo base_url();?>assets/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
	
	<!-- Facebox jQuery -->
    <script src="<?php echo base_url();?>assets/facebox/facebox.js"></script>
    <!-- Flot -->
    <!-- <script src="<?php echo base_url();?>assets/vendors/Flot/jquery.flot.js"></script>
    <script src="<?php echo base_url();?>assets/vendors/Flot/jquery.flot.pie.js"></script>
    <script src="<?php echo base_url();?>assets/vendors/Flot/jquery.flot.time.js"></script>
    <script src="<?php echo base_url();?>assets/vendors/Flot/jquery.flot.stack.js"></script>
    <script src="<?php echo base_url();?>assets/vendors/Flot/jquery.flot.resize.js"></script>-->
    <!-- Flot plugins -->
    <!--<script src="<?php echo base_url();?>assets/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="<?php echo base_url();?>assets/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="<?php echo base_url();?>assets/vendors/flot.curvedlines/curvedLines.js"></script>-->
    <!-- DateJS -->
    <!--<script src="<?php echo base_url();?>assets/vendors/DateJS/build/date.js"></script>-->
    <!-- JQVMap -->
    <!--<script src="<?php echo base_url();?>assets/vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="<?php echo base_url();?>assets/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="<?php echo base_url();?>assets/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>-->
    <!-- bootstrap-daterangepicker -->
   <!-- <script src="<?php echo base_url();?>assets/vendors/moment/min/moment.min.js"></script>
    <script src="<?php echo base_url();?>assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>-->

    <!-- Custom Theme Scripts -->
    <script src="<?php echo base_url();?>assets/build/js/custom.min.js"></script>
	
	<!-- change category status code --->

	<script type="text/javascript">
	$(document).ready(function() {
		$('.reported_item_status').click(function(e){
			var new_status;
			var new_html;
			var request_url;
			var current_status = $(this).attr('current_status');
			var reported_item_id   = $(this).attr('reported_item_id');
			var item_type = $(this).attr('item_type');
			
			if(current_status == 1){
				new_status = 0;
				new_html   = '<span class="btn btn-warning btn-xs" type="button">In-Active</span>';
				request_url = '<?php echo base_url();?>deactivate_reported_item';
				$(this).attr("current_status", new_status);
			}else{
				new_status = 1;
				new_html   = '<span class="btn btn-success btn-xs" type="button">Active</span>';
				request_url = '<?php echo base_url();?>activate_reported_item';
				$(this).attr("current_status", new_status);
			}
			$(this).html(new_html);
			updateStatus(reported_item_id,item_type,new_status,request_url);
		});
		
		<!------------------- view reported item---------------------->
		$('body').on('click', '.view_reported', function () {
		//$('.view_reported').click(function(){
			var item_type = $(this).attr('item_type');
			var item_id   = $(this).attr('item_id');
			if(item_type != '' && item_id != ''){
				$.facebox($.get('<?php echo base_url();?>reported/item_view?item_type='+item_type+'&item_id='+item_id,
      				function(data) { 
					$.facebox(data)
					})
				)
				/*$.ajax({
					url: request_url,
					type: "POST",
					data: {id : cat_id, type:cat_type, new_status:new_status},
					success:function(response){
						var val = JSON.parse(response);
						var msg;
						if(val.type == 'error'){
							msg = '<div id="ajax-message" class="alert alert-danger alert-dismissible fade in" role="alert">'+
						'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
						'<span aria-hidden="true">×</span></button>'+
						'<strong>'+val.text+'</strong> </div>';
						}else{
							msg = '<div id="ajax-message" class="alert alert-success alert-dismissible fade in" role="alert">'+
						'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
						'<span aria-hidden="true">×</span></button>'+
						'<strong>'+val.text+'</strong> </div>';
						}
						$('.ajax_msg').html(msg);
						setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
					}
			    });*/
			}
		});

		<!---Start advertisement change status--->
		$(document).delegate('.adv_status','click',function(e){
			//alert('button clicked');
			var new_status;
			var new_html;
			var request_url;
			var current_status = $(this).attr('current_status');
			var item_id   = $(this).attr('adv_id');
			
			if(current_status == 1){
				new_status = 0;
				new_html   = '<span class="btn btn-warning btn-xs " type="button">In-Active</span>';
				request_url = '<?php echo base_url();?>advertisement/deactivate';
				//alert(request_url);
				$(this).attr("current_status", new_status);
			}else{
				new_status = 1;
				new_html   = '<span class="btn btn-success btn-xs " type="button">Active</span>';
				request_url = '<?php echo base_url();?>advertisement/activate';
				$(this).attr("current_status", new_status);
			}
			$(this).html(new_html);
			updateStatus(item_id,'',new_status,request_url);
			
		});
		<!--END advertisement change status -->

		<!--Start Event Category change status -->
		$(document).delegate('.event_status','click',function(e){
			//alert('button clicked');
			var new_status;
			var new_html;
			var request_url;
			var current_status = $(this).attr('current_status');
			var item_id   = $(this).attr('cat_id');
			var item_type   = $(this).attr('cat_type');
			
			if(current_status == 1){
				new_status = 0;
				new_html   = '<span class="btn btn-warning btn-xs " type="button">In-Active</span>';
				request_url = '<?php echo base_url();?>category/deactivate';
				
				$(this).attr("current_status", new_status);
			}else{
				new_status = 1;
				new_html   = '<span class="btn btn-success btn-xs " type="button">Active</span>';
				request_url = '<?php echo base_url();?>category/activate';
				$(this).attr("current_status", new_status);
			}
			$(this).html(new_html);
			updateStatus(item_id,item_type,new_status,request_url);
			
		});
		<!--END Event Category  change status-->

		<!--Start Post Comment  change status-->
		$(document).on('.event_status','click',function(e){
			//alert('button clicked');
			var new_status;
			var new_html;
			var request_url;
			var current_status = $(this).attr('current_status');
			var item_id   = $(this).attr('cat_id');
			var item_type   = $(this).attr('cat_type');
			
			if(current_status == 1){
				new_status = 0;
				new_html   = '<span class="btn btn-warning btn-xs " type="button">In-Active</span>';
				request_url = '<?php echo base_url();?>category/deactivate';
				//alert(request_url);
				$(this).attr("current_status", new_status);
			}else{
				new_status = 1;
				new_html   = '<span class="btn btn-success btn-xs " type="button">Active</span>';
				request_url = '<?php echo base_url();?>category/activate';
				$(this).attr("current_status", new_status);
			}
			$(this).html(new_html);

			updateStatus(item_id,item_type,new_status,request_url);
			
		});
		<!--END Post Comment change status-->

		$('.view_advertisement').click(function(){
			var adv_id = $(this).attr('adv_id');
			if(adv_id != ''){
				$.facebox($.get('<?php echo base_url();?>advertisement/advertisement_view?adv_id='+adv_id,
      				function(data) { 
					$.facebox(data)
					})
				)
			}
		});

        /*Common function for update status*/
		function updateStatus(item_id='',item_type='',new_status='',request_url=''){
				$.ajax({
				url: request_url,
                type: "POST",
                data: {id : item_id, type:item_type, new_status:new_status},
				success:function(response){
                 var val = JSON.parse(response);
                
					if(val.type == 'error'){
						msg = '<div id="ajax-message" class="alert alert-danger alert-dismissible fade in" role="alert">'+
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
					'<span aria-hidden="true">×</span></button>'+
                    '<strong>'+val.text+'</strong> </div>';
					}else{
						msg = '<div id="ajax-message" class="alert alert-success alert-dismissible fade in" role="alert">'+
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
					'<span aria-hidden="true">×</span></button>'+
                    '<strong>'+val.text+'</strong> </div>';
					}
					$('.ajax_msg').html(msg);
					setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
				}
		});
		}
	});
	

</script>
  </body>
</html>