<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>
<style>
.preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 99999;
        display: flex;
        flex-flow: row nowrap;
        justify-content: center;
        align-items: center;
        background-color: rgba(255,255,255,0.5);
}
.preloader img{max-width:200px;}
</style>

	  <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
                <div class="title_left">
					<h3>Site Users</h3>
                </div>
            </div>

            <div class="clearfix"></div>
			<!--Display Any Error or Success Message-->
            <?php 
			    if($this->session->flashdata('success_val')){
					$msg['msg'] = $this->session->flashdata('success_val');
					$this->load->view('flash_messages/success', $msg);
			    }else if($this->session->flashdata('error_val')){
					$msg['msg'] = $this->session->flashdata('error_val');
					$this->load->view('flash_messages/error', $msg);
				} 
			?>
			<div class="ajax_msg"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Site Users</small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
                        <thead>
							<tr>
								<th>
									<th><input type="checkbox" id="check-all" class="flat"></th>
								</th>
								<th>Name</th>
								<th>Email</th>
								<!-- <th>Phone</th> -->
                                                                <th>IP Address</th>
								<th>Created date</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
                        </thead>
                        <tbody>
						<?php foreach ($users as $user){ ?>
							<tr>
							    <td>
								    <th><input type="checkbox" id="check-all" class="flat"></th>
							    </td>
							    <td><?php echo $user->first_name.' '.$user->last_name;?></td>
							    <td><?php echo $user->email;?></td>
							    <!-- <td><?php //echo $user->phone;?></td> -->
                                                            <td><?php echo $user->ip_address;?></td>
							    <td><?php echo date('Y-m-d', strtotime($user->created_at));?></td>
								<td class="user_status" status="<?php echo $user->status;?>" user_id="<?php echo $user->id;?>">
									<?php
									if($edit_status){
									if($user->status == 1){
											echo '<span class="btn btn-success btn-xs change_status" status="1" type="button">Active</span>';
										}else{
											echo '<span class="btn btn-warning btn-xs change_status" status="0" type="button">In-Active</span>';
										}
									}
									?>
								</td>
								<td>
									<?php
									if($edit_status){
                                                                            echo '<a href="'.base_url().'users/edit_site_user/'.$user->id.'" class="btn btn-primary btn-xs" title="Edit Site User"><i class="fa fa-edit"></i></a>';
                                                                        }
                                                                        if($delete_status){
                                                                            echo '<a href="javascript:void(0);" class="btn btn-danger btn-xs delete" data-id="'.$user->id.'" title="Delete Site User"><i class="fa fa-trash"></i></a>';
                                                                        }
                                                                        if($edit_status){
                                                                            if($user->is_block){
                                                                                echo '<a href="javascript:void(0);" class="unblock_ip" data-ip="'.$user->ip_address.'" title="Block IP"><span class="btn btn-warning btn-xs" type="button">UnBlock IP</span></a>';
                                                                            } else {
                                                                                echo '<a href="javascript:void(0);" class="block_ip" data-ip="'.$user->ip_address.'" title="Block IP"><span class="btn btn-danger btn-xs" type="button">Block IP</span></a>';
                                                                            }
									}
									?>
								</td>
							</tr>
						<?php } ?>	
                        </tbody>
                    </table>
                  </div>
                </div>
              </div>        
			</div>
          </div>
        </div>
          <section class="preloader">
                <div class="spinner">
                      <span class="spinner-rotate"></span>
                      <img src="<?php echo base_url();?>assets/images/loader.gif">
                </div>
            </section>
        <!-- /page content -->
<script type="text/javascript">
$(document).ready(function() {
        $(".preloader").hide();
        $(document).ajaxStart(function() {
            $(".preloader").show();
        });
        $( document ).ajaxStop(function() {
            $(".preloader").hide();
        });
	<?php if($edit_status){?>
		$('.user_status').click(function(e){
		    var new_status;
			var new_html;
			var request_url;
			var current_status = $(this).attr('status');
			var user_id = $(this).attr('user_id');
			if(current_status == 1){
				new_status = 0
				new_html   = '<span class="btn btn-warning btn-xs change_status" status="0" type="button">In-Active</span>';
				request_url = '<?php echo base_url();?>/users/deactivate/'+user_id;
				$(this).attr("status", new_status);
			}else{
				new_status = 1
				new_html   = '<span class="btn btn-success btn-xs change_status" status="1" type="button">Active</span>';
				request_url = '<?php echo base_url();?>/users/activate/'+user_id;
				$(this).attr("status", new_status);
			}
			$(this).html(new_html);
			$.ajax({
				url: request_url,
                type: "POST",
                data: {id : user_id, confirm:'yes'},
				success:function(response){
					var val = JSON.parse(response);
						var msg;
						if(val.type == 'error'){
							msg = '<div id="ajax-message" class="alert alert-danger alert-dismissible fade in" role="alert">'+'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+'<span aria-hidden="true">×</span></button>'+'<strong>'+val.text+'</strong> </div>';
						}else{
							msg = '<div id="ajax-message" class="alert alert-success alert-dismissible fade in" role="alert">'+'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+'<span aria-hidden="true">×</span></button>'+'<strong>'+val.text+'</strong> </div>';
						}
						$('.ajax_msg').html(msg);
						setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
				}
			});
		});

                $(document).delegate('.block_ip','click',function(){
                    var ip = $(this).data('ip');
                    request_url = '<?php echo base_url();?>users/blockIP';
                    $.confirm({
                        title: 'Do you Want to block this IP!',
                        //content: 'Simple confirm!',
                        buttons: {
                            confirm: function () {
                                $.ajax({
                                    url: request_url,
                                    type: "POST",
                                    data: {ip:ip},
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

                $(document).delegate('.unblock_ip','click',function(){
                    var ip = $(this).data('ip');
                    request_url = '<?php echo base_url();?>users/unblockIP';
                    $.confirm({
                        title: 'Do you Want to unblock this IP!',
                        //content: 'Simple confirm!',
                        buttons: {
                            confirm: function () {
                                $.ajax({
                                    url: request_url,
                                    type: "POST",
                                    data: {ip:ip},
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
	<?php } ?>
        <?php if($delete_status){ ?>
                $(document).delegate('.delete','click',function(){
                    var id = $(this).data('id');
                    request_url = '<?php echo base_url();?>users/deleteUser';
                    $.confirm({
                        title: 'Do you Want to delete this User!',
                        //content: 'Simple confirm!',
                        buttons: {
                            confirm: function () {
                                $.ajax({
                                    url: request_url,
                                    type: "POST",
                                    data: {id:id, is_delete_user : '1'},
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
        <?php } ?>    
	});
</script>
<?php $this->load->view('elements/footer'); ?>
