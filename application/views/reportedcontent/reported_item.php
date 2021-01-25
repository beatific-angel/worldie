<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>
	  <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
                <div class="title_left">
					<h3><?php echo str_replace('_', ' ', $item);?> Reported Items</h3>
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
                    <h2>Reported Items</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
                        <thead>
							<tr>
								
								<th><input type="checkbox" id="check-all" class="flat"></th>
								<th>Type</th>
								<th>Content Title</th>
								<th>Reported By</th>
								<th>Reason</th>
								<th>Reported On</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
                        </thead>
                        <tbody> 
						<?php if(count($results) > 0){
							     foreach ($results as $result){  ?>
							<tr>
							    <td>
								    <input type="checkbox" id="check-all" class="flat">
							    </td>
							    <td><?php echo str_replace('_', ' ', $item);?></td>
							    <td>
									<?php   if($item == 'Post'){
												echo $result->content;
											}elseif($item == 'Event'){
												echo $result->short_description;
											}elseif($item == 'Post_Comment' || $item == 'Event_Comment'){
												echo $result->comment;
											}elseif($item == 'User_Profile'){
												echo "Reported User Profile";
											}elseif($item == 'Image_comment'){
												echo "Image";
											} elseif($item == 'Media_video'){
												echo $result->title;
											}else if($item == 'Media_video_comment'){
												echo $result->comment;
											}else if($item == 'Media_Channel'){
												echo $result->title;
											}else if($item == 'Art_Wall'){
												echo $result->title;
											}else if($item == 'Wall_Art'){
												echo $result->title;
											}else if($item == 'Wall_Art_Comment'){
												echo $result->comment;
											}else if($item == 'Advertisement'){
												echo $result->description;
											}else if($item == 'Product'){
												echo $result->name;
											}else if($item == 'Store'){
												echo $result->name;
											}else if($item == 'Group'){
												echo $result->name;
											}else if($item == 'Page'){
												echo $result->name;
											}

									?>
								</td>
							    <td><?php echo $result->first_name.' '.$result->last_name;?></td>
								<td>
									<?php 
									    if($result->report_reason == 'other'){
											echo $result->report_description;
										}else{
											echo $result->report_reason;
										}
										
									?>
								</td>
								<td><?php echo date('Y-m-d', strtotime($result->created_at));?></td>
							    <td class="reported_item_status" current_status="<?php echo $result->status;?>" reported_item_id="<?php echo $result->id;?>" item_type="<?php echo $item;?>">
									<?php if($result->status == 1){
											echo '<span class="btn btn-success btn-xs" type="button">Active</span>';
										}else{
											echo '<span class="btn btn-warning btn-xs" type="button">In-Active</span>';
										}
									?>
								</td>
							    <td>
							    	<?php if($item=="User_Profile"){ ?>
							    		<span class="btn btn-warning btn-xs view_user_profile" type="button" item_id="<?php echo $result->item_id;?>" item_type="<?php echo $item;?>">view</span>
							    		<span class="btn btn-danger btn-xs send_warning" type="button" item_id="<?php echo $result->item_id;?>" item_type="<?php echo $item;?>">Send Warning</span>
							    	<?php }else{ ?>
										
										<?php if($item=="Post"){?>
											<span class="btn btn-warning btn-xs view_reported" type="button" item_id="<?php echo $result->item_id;?>" item_type="<?php echo $item;?>">view</span>

											<span class="btn btn-danger delete_post" title="Delete Reported Post" id="delete_post_<?php echo $result->id;?>"
											data-id="<?php echo $result->id;?>"><i class="fa fa-trash"></i></span>

										<?php } else if($item=="Event"){?>
											<span class="btn btn-warning btn-xs view_reported_event" type="button" item_id="<?php echo $result->item_id;?>" item_type="<?php echo $item;?>">view</span>
											<span class="btn btn-danger delete_event" title="Delete Reported Event" id="delete_event_<?php echo $result->id;?>" data-id="<?php echo $result->id;?>"><i class="fa fa-trash"></i></span>

										<?php } else if($item=="Post_Comment"){?>
											<span class="btn btn-danger delete_postcomment" title="Delete Reported Event" id="delete_postcomment_<?php echo $result->id;?>" data-id="<?php echo $result->id;?>"><i class="fa fa-trash"></i></span>

										<?php }else if($item=="Event_Comment"){?>
											<span class="btn btn-danger delete_eventcomment" title="Delete Reported Event" id="delete_eventcomment_<?php echo $result->id;?>" data-id="<?php echo $result->id;?>"><i class="fa fa-trash"></i></span>

										<?php }else if($item=="Media_video"){?>
											<span class="btn btn-warning btn-xs view_media_video" type="button" item_id="<?php echo $result->item_id;?>" item_type="<?php echo $item;?>">view</span>
											
											<span class="btn btn-danger delete_media_video" title="Delete Media Video" id="delete_mediavideo_<?php echo $result->id;?>" data-id="<?php echo $result->id;?>"><i class="fa fa-trash"></i></span>

										<?php }else if($item=="Media_video_comment"){?>
											<span class="btn btn-danger delete_mediavideo_comment" title="Delete Media Video Commment" id="delete_mediavideocomment_<?php echo $result->id;?>" data-id="<?php echo $result->id;?>"><i class="fa fa-trash"></i></span>
										<?php }else if($item=="Media_Channel"){ ?>	
											<span class="btn btn-warning btn-xs view_media_channel" type="button" item_id="<?php echo $result->item_id;?>" item_type="<?php echo $item;?>">view</span>
										<?php }else if($item=="Art_Wall"){ ?>	
											<span class="btn btn-warning btn-xs view_art_wall" type="button" item_id="<?php echo $result->item_id;?>" item_type="<?php echo $item;?>">view</span>
										<?php }else if($item == 'Wall_Art'){ ?>	
											<span class="btn btn-warning btn-xs view_wall_art" type="button" item_id="<?php echo $result->item_id;?>" item_type="<?php echo $item;?>">view</span>
											<span class="btn btn-danger delete_wall_art" title="Delete Wall Art" id="delete_comment_<?php echo $result->id;?>" data-id="<?php echo $result->id;?>"><i class="fa fa-trash"></i></span>
										<?php }else if($item=="Wall_Art_Comment"){ ?>
											<span class="btn btn-danger delete_wallart_comment" id="delete_comment_<?php echo $result->id;?>"title="Delete Wall Art Commment" data-id="<?php echo $result->id;?>"><i class="fa fa-trash"></i></span>	
									    <?php }else if($item=="Advertisement"){?>
											<span class="btn btn-danger delete_advertisement" id="delete_advt_<?php echo $result->id;?>"title="Delete Advertisement" data-id="<?php echo $result->id;?>"><i class="fa fa-trash"></i></span>
									    <?php }else if($item=="Product"){?>
										    <span class="btn btn-danger delete_product" id="delete_store_product_<?php echo $result->id;?>"title="Delete Product" data-id="<?php echo $result->id;?>"><i class="fa fa-trash"></i></span>
									    <?php }else if($item=="Store"){?>
											 <span class="btn btn-danger delete_store" id="delete_store_<?php echo $result->id;?>"title="Delete Store" data-id="<?php echo $result->id;?>"><i class="fa fa-trash"></i></span>
									    <?php }else if($item=="Group"){?>
										     <span class="btn btn-danger delete_group" id="delete_group_<?php echo $result->id;?>"title="Delete Group" data-id="<?php echo $result->id;?>"><i class="fa fa-trash"></i></span>	
									     <?php }else if($item=="Page"){ ?>
                                             <span class="btn btn-danger delete_page" id="delete_page_<?php echo $result->id;?>"title="Delete Page" data-id="<?php echo $result->id;?>"><i class="fa fa-trash"></i></span>	
										<?php }else {?>
											<span class="btn btn-danger delete_imagecomment" title="Delete Reported Event" id="delete_imagecomment_<?php echo $result->id;?>" data-id="<?php echo $result->id;?>"><i class="fa fa-trash"></i></span>
										<?php
										 }
										?>
									<?php } ?>
									<?php if($result->isReviewed==0){?>
										<span class="btn btn-warning btn-xs review_reported" type="button" item_id="<?php echo $result->item_id;?>" item_type="<?php echo $item;?>" report_id="<?php echo $result->report_id;?>" user_id="<?php echo $result->user_id;?>" id="<?php echo $result->id;?>">Review</span>
									<?php } else{ ?>
										<span class="btn btn-warning btn-xs review_not_allowed" type="button" item_id="<?php echo $result->item_id;?>" item_type="<?php echo $item;?>" report_id="<?php echo $result->report_id;?>"  user_id="<?php echo $result->user_id;?>" id="<?php echo $result->id;?>">Reviewed</span>
									<?php } ?>
								</td>
							</tr>
						<?php } }else{?>	
						<tr><td colspan="7"><center>No Data is avaiable............!</center></td></tr>
						<?php }?>	
                        </tbody>
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
		var table = $('#datatable-checkbox').DataTable(); 
		// send warning To reported Profile User
		$('.send_warning').click(function(){
			var item_id=$(this).attr('item_id');
			var item_type=$(this).attr('item_type');
			request_url = '<?php echo base_url();?>reported/sendWarningToUser';
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

        /*Delete Group Selection */
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
