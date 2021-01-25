<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>
	  <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
                <div class="title_left">
					<h3>Reported Items List</h3>
                </div>
            </div>

            <div class="clearfix"></div>
			<!--Display Any Error or Success Message-->
            <?php 
			    if($this->session->flashdata('success')){
					$msg['msg'] = $this->session->flashdata('success');
					$this->load->view('flash_messages/success', $msg);
			    }else if($this->session->flashdata('error')){
					$msg['msg'] = $this->session->flashdata('error');
					$this->load->view('flash_messages/error', $msg);
				} 
			?>
			<div class="ajax_msg"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                    	<select class="form-control" id="content_type">
                    		<option value="">Select Type</option>
                    		<option value="1">Post</option>
                    		<option value="2">Event</option>
                    		<option value="3">Post Comment</option>
                    		<option value="4">Image Comment</option>
                    		<option value="5">Event Comment</option>
                    		<option value="6">User</option>
                    		<option value="7">Media Video</option>
                    		<option value="8">Media Video Comment</option>
                    		<option value="9">Media Channel</option>
                    		<option value="10">Art Wall</option>
                    		<option value="11">Wall Art</option>
                    		<option value="12">Wall Art Comment</option>
                    		<option value="13">Advertisement</option>
                    		<option value="14">Product</option>
                    		<option value="15">Store</option>
                    		<option value="16">Group</option>
                                <option value="17">Page</option>
                    	</select>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                    	<select class="form-control" id="content_reason">
                    		<option value="">Select Reason</option>
                    		<option value="Nudity/Nude">Nudity/Nude</option>
                    		<option value="harassment">Harassment</option>
                    		<option value="criminal activity">Criminal Activity</option>
                    		<option value="scam/fraud">Scam/Fraud</option>
                    		<option value="spam">Spam</option>
                    		<option value="copyright">Copyright</option>
                    		<option value="other">Other</option>
                    	</select>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                    	<button type="button" id="filter" class="btn btn-sm btn-primary">Filter</button>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table id="datatable-list" class="table table-striped table-bordered bulk_action">
                    	<thead>
							<tr>
								<th>S.No.</th>
								<th>Type</th>
								<th>Content Title</th>
								<th>Reported By</th>
								<th>Reason</th>
								<th>Reported On</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
                        </thead>
                    </table>
                  </div>
                </div>
              </div>

              
			</div>
          </div>
        </div>
        <!-- /page content -->
<?php $this->load->view('elements/footer'); ?>
<script type="text/javascript">
	$(document).ready(function(){
	var table = $('#datatable-list').DataTable({
        language: {
            searchPlaceholder: "Search Records"
        },
        responsive: true,
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo base_url('reportedList/reported_list')?>",
            "type": "POST",
            "data": function ( d ) {
              return $.extend( {}, d, {
              	"content_type"  : $('#content_type').val(),
                "content_reason": $('#content_reason').val(),
                "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash();?>",
              } );
            },
            "dataType": "json",
                "dataSrc": function (jsonData) {
                    return jsonData.data;
                }
            },
        "columnDefs": [{
                "targets": [1,2,3,4,5,6,7],
                "orderable": false
            }
        ],
        });

	$(document).on("click", "#filter", function(event) {
        table.ajax.reload();
    });

		// send warning To reported Profile User
		$('.send_warning').click(function(){
			var item_id   = $(this).attr('item_id');
			var item_type = $(this).attr('item_type');
			request_url   = '<?php echo base_url();?>reported/sendWarningToUser';
			$.ajax({
				url: request_url,
                type: "POST",
                data: {item_id:item_id,item_type:item_type},
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
			});
		})

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

$(document).delegate('.reported_item_status','click',function(e){
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
		$(this).parent('td').html(new_html);
		updateStatus(reported_item_id,item_type,new_status,request_url);
	});
		
	$(document).delegate('.review_reported','click',function(){
		var $this=$(this);
		var user_id=$(this).attr('user_id');
		var report_id=$(this).attr('report_id');
		var id=$(this).attr('id');
		var item_id=$(this).attr('item_id');
		var item_type=$(this).attr('item_type');
		request_url = '<?php echo base_url();?>reported/sendReviewNotification';
		$.ajax({
			url: request_url,
            type: "POST",
            data: {user_id : user_id, report_id:report_id, item_id:item_id,item_type:item_type,id:id},
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
				$this.html("");
				$this.html("Reviewed");
				$this.removeClass('review_reported');
				$this.addClass('review_not_allowed');		
				setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
			}
		});
	});		

	/*Delete Post Section*/
	$(document).delegate('.delete_post','click',function(){
		var id=$(this).attr('data-id');
		var thisobj = $('#delete_post_'+id);
		request_url = '<?php echo base_url();?>post/postDeleted';
		$.confirm({
		    title: 'Do you Want to delete the Post!',
		    //content: 'Simple confirm!',
		    buttons: {
		        confirm: function () {
					$.ajax({
						url: request_url,
		                type: "POST",
		                data: {reportedcontent_id:id,reported_post:1},
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
							table.row($(thisobj).parents('tr')).remove().draw(false);
							$('.ajax_msg').html(msg);	
							setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
						}
					});
				},
		        cancel: function () {
		            
		        }
		    }
		});
	});	

        $(document).delegate('.delete_group','click',function(){
		var id=$(this).attr('data-id');
		var thisobj = $('#delete_group_'+id);
		request_url = '<?php echo base_url();?>groups/deleteGroup';
		$.confirm({
		    title: 'Do you Want to delete the Group!',
		    //content: 'Simple confirm!',
		    buttons: {
		        confirm: function () {
					$.ajax({
						url: request_url,
		                type: "POST",
		                data: {id:id,reported_post:1},
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
							table.row($(thisobj).parents('tr')).remove().draw(false);
							$('.ajax_msg').html(msg);	
							setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
						}
					});
				},
		        cancel: function () {
		            
		        }
		    }
		});
	});

	/*Delete Event Section*/
	$(document).delegate('.delete_event','click',function(){
		var id=$(this).attr('data-id');
		var thisobj = $('#delete_event_'+id);
		request_url = '<?php echo base_url();?>events/event_delete';
		$.confirm({
		    title: 'Do you Want to delete the Event!',
		    //content: 'Simple confirm!',
		    buttons: {
		        confirm: function () {
					$.ajax({
						url: request_url,
		                type: "POST",
		                data: {reportedcontent_id:id,reported_post:1},
						success:function(response){
							var val = JSON.parse(response);
							var msg;
							console.log(val);
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
							table.row($(thisobj).parents('tr')).remove().draw(false);
							$('.ajax_msg').html(msg);	
							setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
						}
					});
				},
		        cancel: function () {
		            
		        }
		    }
		});
	});		

	/*Delete Post Comment Section*/
	$(document).delegate('.delete_postcomment','click',function(){
		var id=$(this).attr('data-id');
		var thisobj = $('#delete_postcomment_'+id);
		request_url = '<?php echo base_url();?>post/deletePostComment';
		$.confirm({
		    title: 'Do you Want to delete the Post Comment!',
		    //content: 'Simple confirm!',
		    buttons: {
		        confirm: function () {
					$.ajax({
						url: request_url,
		                type: "POST",
		                data: {reportedcontent_id:id},
						success:function(response){
							var val = JSON.parse(response);
							var msg;
							console.log(val);
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
							table.row($(thisobj).parents('tr')).remove().draw(false);
							$('.ajax_msg').html(msg);	
							setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
						}
					});
				},
		        cancel: function () {
		            
		        }
		    }
		});
	});		

	/*Delete Event Comment Section*/
	$(document).delegate('.delete_eventcomment','click',function(){
		var id=$(this).attr('data-id');
		var thisobj = $('#delete_eventcomment_'+id);
		request_url = '<?php echo base_url();?>events/deleteEventComment';
		$.confirm({
		    title: 'Do you Want to delete the Event Comment!',
		    //content: 'Simple confirm!',
		    buttons: {
		        confirm: function () {
					$.ajax({
						url: request_url,
		                type: "POST",
		                data: {reportedcontent_id:id},
						success:function(response){
							var val = JSON.parse(response);
							var msg;
							console.log(val);
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
							table.row($(thisobj).parents('tr')).remove().draw(false);
							$('.ajax_msg').html(msg);	
							setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
						}
					});
				},
		        cancel: function () {
		            
		        }
		    }
		});
	});	

/* Delete Image comment */ 
	$(document).delegate('.delete_imagecomment','click',function(){
		var id=$(this).attr('data-id');
		var thisobj = $('#delete_imagecomment_'+id);
		request_url = '<?php echo base_url();?>image/deleteImageComment';
		$.confirm({
		    title: 'Do you Want to delete the Image Comment!',
		    //content: 'Simple confirm!',
		    buttons: {
		        confirm: function () {
					$.ajax({
						url: request_url,
			            type: "POST",
			            data: {reportedcontent_id:id},
						success:function(response){
							var val = JSON.parse(response);
							var msg;
							console.log(val);
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
							table.row($(thisobj).parents('tr')).remove().draw(false);
							$('.ajax_msg').html(msg);	
							setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
						}
					});
				},
		        cancel: function () {
		            
		        }
		    }
		});
	});	

	/* Delete media video Comment section */ 

	$(document).delegate('.delete_mediavideo_comment','click',function(){
		var id=$(this).attr('data-id');
		var thisobj = $('#delete_imagecomment_'+id);
		request_url = '<?php echo base_url();?>media/deleteMediaVideoComment';
		$.confirm({
		    title: 'Do you Want to delete the Media Video Comment!',
		    //content: 'Simple confirm!',
		    buttons: {
		        confirm: function () {
					$.ajax({
						url: request_url,
			            type: "POST",
			            data: {reportedcontent_id:id},
						success:function(response){
							var val = JSON.parse(response);
							var msg;
							console.log(val);
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
							table.row($(thisobj).parents('tr')).remove().draw(false);
							$('.ajax_msg').html(msg);	
							setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
						}
					});
				},
		        cancel: function () {
		            
		        }
		    }
		});
	});	

	/* Delete media video section */ 

	$(document).delegate('.delete_media_video','click',function(){
		var id      = $(this).attr('data-id');
		var thisobj = $('#delete_mediavideo_'+id);
		request_url = '<?php echo base_url();?>media/deletevideo';
		$.confirm({
		    title: 'Do you Want to delete the Media Video!',
		    //content: 'Simple confirm!',
		    buttons: {
		        confirm: function () {
					$.ajax({
						url: request_url,
			            type: "POST",
			            data: {reportedcontent_id:id,reported_post:1},
						success:function(response){
							var val = JSON.parse(response);
							var msg;
							console.log(val);
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
							table.row($(thisobj).parents('tr')).remove().draw(false);
							$('.ajax_msg').html(msg);	
							setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
						}
					});
				},
		        cancel: function () {
		            
		        }
		    }
		});
	});	

	/* Delete Wall Art */ 

	$(document).delegate('.delete_wall_art','click',function(){
		var id=$(this).attr('data-id');
		var thisobj = $('#delete_comment_'+id);
		request_url = '<?php echo base_url();?>art/deleteWallArt';
		$.confirm({
		    title: 'Do you Want to delete the Wall Art!',
		    //content: 'Simple confirm!',
		    buttons: {
		        confirm: function () {
					$.ajax({
						url: request_url,
			            type: "POST",
			            data: {reportedcontent_id:id,reported_post:1},
						success:function(response){
							var val = JSON.parse(response);
							var msg;
							console.log(val);
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
							table.row($(thisobj).parents('tr')).remove().draw(false);
							$('.ajax_msg').html(msg);	
							setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
						}
					});
				},
		        cancel: function () {
		            
		        }
		    }
		});
	});

	/* Delete Wall Art Comment*/ 

	$(document).delegate('.delete_wallart_comment','click',function(){
		var id=$(this).attr('data-id');
		var thisobj = $('#delete_comment_'+id);
		request_url = '<?php echo base_url();?>art/deleteArtComment';
		$.confirm({
		    title: 'Do you Want to delete the Wall Art Comment!',
		    //content: 'Simple confirm!',
		    buttons: {
		        confirm: function () {
					$.ajax({
						url: request_url,
			            type: "POST",
			            data: {reportedcontent_id:id},
						success:function(response){
							var val = JSON.parse(response);
							var msg;
							console.log(val);
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
							table.row($(thisobj).parents('tr')).remove().draw(false);
							$('.ajax_msg').html(msg);	
							setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
						}
					});
				},
		        cancel: function () {
		            
		        }
		    }
		});
	});	
	
	/* Delete Advertisement*/ 

	$(document).delegate('.delete_advertisement','click',function(){
		var id=$(this).attr('data-id');
		var thisobj = $('#delete_advt_'+id);
		request_url = '<?php echo base_url();?>advertisement/delete_Advertisement_new';
		$.confirm({
		    title: 'Do you Want to delete the Advertisement!',
		    //content: 'Simple confirm!',
		    buttons: {
		        confirm: function () {
					$.ajax({
						url: request_url,
			            type: "POST",
			            data: {reportedcontent_id:id},
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
							table.row($(thisobj).parents('tr')).remove().draw(false);
							$('.ajax_msg').html(msg);	
							setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
						}
					});
				},
		        cancel: function () {
		            
		        }
		    }
		});
	});	
	/*Delete Page Selection */
        $(document).delegate('.delete_page','click',function(){
		var id=$(this).attr('data-id');
		var thisobj = $('#delete_page_'+id);
		request_url = '<?php echo base_url();?>allpages/deletePage';
		$.confirm({
		    title: 'Do you Want to delete the Page!',
		    //content: 'Simple confirm!',
		    buttons: {
		        confirm: function () {
					$.ajax({
						url: request_url,
		                type: "POST",
		                data: {id:id,reported_post:1},
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
							table.row($(thisobj).parents('tr')).remove().draw(false);
							$('.ajax_msg').html(msg);	
							setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
						}
					});
				},
		        cancel: function () {
		            
		        }
		    }
		});
	});
	/* Delete Store*/ 

	$(document).delegate('.delete_product','click',function(){
		var id=$(this).attr('data-id');
		var thisobj = $('#delete_store_product_'+id);
		request_url = '<?php echo base_url();?>store/deleteProduct';
		$.confirm({
		    title: 'Do you Want to delete the Product!',
		    //content: 'Simple confirm!',
		    buttons: {
		        confirm: function () {
					$.ajax({
						url: request_url,
			            type: "POST",
			            data: {reportedcontent_id:id},
						success:function(response){
							var val = JSON.parse(response);
							var msg;
							console.log(val);
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
							table.row($(thisobj).parents('tr')).remove().draw(false);
							$('.ajax_msg').html(msg);	
							setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
						}
					});
				},
		        cancel: function () {
		            
		        }
		    }
		});
	});	
	
		/* Delete Store*/ 

	$(document).delegate('.delete_store','click',function(){
		var id=$(this).attr('data-id');
		var thisobj = $('#delete_store_'+id);
		request_url = '<?php echo base_url();?>store/deleteStore';
		$.confirm({
		    title: 'Do you Want to delete the Store!',
		    //content: 'Simple confirm!',
		    buttons: {
		        confirm: function () {
					$.ajax({
						url: request_url,
			            type: "POST",
			            data: {reportedcontent_id:id},
						success:function(response){
							var val = JSON.parse(response);
							var msg;
							console.log(val);
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
							table.row($(thisobj).parents('tr')).remove().draw(false);
							$('.ajax_msg').html(msg);	
							setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
						}
					});
				},
		        cancel: function () {
		            
		        }
		    }
		});
	});	
	
	
	$('.view_user_profile').click(function(){
		var item_id = $(this).attr('item_id');
		var url = '<?php echo user_profile();?>';
        window.open(url+item_id, '_blank');
	});

	$('.view_media_channel').click(function(){
		var item_id = $(this).attr('item_id');
		var url = '<?php echo media_channel_url();?>';
        window.open(url+item_id, '_blank');
	});
	
	$('.view_art_wall').click(function(){
		var item_id = $(this).attr('item_id');
		var url = '<?php echo user_art_wall();?>';
        window.open(url+item_id, '_blank');
	});

	$('.view_wall_art').click(function(){
		var item_id = $(this).attr('item_id');
		var url = '<?php echo user_wall_art();?>';
        window.open(url+item_id, '_blank');
	})

	$('.view_media_video').click(function(){
		var item_id = $(this).attr('item_id');
		var url = '<?php echo user_media_video();?>';
        window.open(url+item_id, '_blank');
	})

	$('.view_reported_event').click(function(){
		var item_id = $(this).attr('item_id');
		var url = '<?php echo view_event_detail();?>';
        window.open(url+item_id, '_blank');
	})

	});			
</script>
<style>
.review_not_allowed{cursor:not-allowed;}
</style>
