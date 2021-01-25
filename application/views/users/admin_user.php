<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>
	  <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
                <div class="title_left">
					<h3>Admin Users</h3>
                </div>
			    <div class="title_right">
					<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right">
					    <div class="input-group">
								<a href="<?php echo base_url();?>users/create_user" class="btn btn-round btn-primary" type="button">Create User</a>
					    </div>
					</div>
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
                    <h2>Admin Users</small></h2>
                    
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
								<th>Role</th>
								<th>Email</th>
								<!-- <th>Phone</th> -->
								<th>Status</th>
								<th>Created date</th>
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
							    <td>
							    	<?php
							    	$role = $this->common_model->getRowDetails('tbl_role',array('id'=>$user->role_id));
							    	echo !empty($role) ? $role->name : '';
							    	?>
							    </td>
							    <td><?php echo $user->email;?></td>
							    <!-- <td><?php //echo $user->phone;?></td> -->
							    <td class="user_status" status="<?php echo $user->status;?>" user_id="<?php echo $user->id;?>">
							    	<?php
									if($user->role_id!=1){?>
									<?php if($user->status == 1){
											echo '<span class="btn btn-success btn-xs change_status" status="1" type="button">Active</span>';
										}else{
											echo '<span class="btn btn-warning btn-xs change_status" status="0" type="button">In-Active</span>';
										}
									}
									?>
								</td>
							    <td><?php echo date('Y-m-d', strtotime($user->created_at));?></td>
								<td>
									<?php
									if($user->role_id!=1){?>
									<div class="btn-group">
										<a href="<?php echo base_url();?>users/edit_user/<?php echo $user->id;?>" class="btn btn-warning" title="Edit User"><i class="fa fa-edit"></i></a>
										<a href="javascript:;" data_url="<?php echo base_url();?>users/delete_user/<?php echo $user->id;?>" class="btn btn-danger delete_admin_user" title="Delete User"><i class="fa fa-trash"></i></a>
									</div>
									<?php } ?>
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
        <!-- /page content -->
<script type="text/javascript">
	$(document).ready(function() {
		
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

		// delete admin user

		$(document).on('click', '.delete_admin_user', function(){
			var redirect_url = $(this).attr('data_url');
			$.confirm({
			    title: 'Do you Want to delete the Admin User!',
			    //content: 'Simple confirm!',
			    buttons: {
			        confirm: function () {
			            location.href = redirect_url;
			        },
			        cancel: function () {
			            
			        }
			    }
			});
		});
		 
	});
	 

</script>		
<?php $this->load->view('elements/footer'); ?>
