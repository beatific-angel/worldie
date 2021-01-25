<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>
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
                    <h2>Site Users</small></h2>
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
								<th>
									<th><input type="checkbox" id="check-all" class="flat"></th>
								</th>
								<th>Name</th>
								<th>Email</th>
								<th>Phone</th>
								<th>Created date</th>
								<th>Status</th>
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
							    <td><?php echo $user->phone;?></td>
							    <td><?php echo date('Y-m-d', strtotime($user->created_at));?></td>
								<td class="user_status" status="<?php echo $user->status;?>" user_id="<?php echo $user->id;?>">
									<?php if($user->status == 1){
											echo '<span class="btn btn-success btn-xs change_status" status="1" type="button">Active</span>';
										}else{
											echo '<span class="btn btn-warning btn-xs change_status" status="0" type="button">In-Active</span>';
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
	});
	
	

</script>
<?php $this->load->view('elements/footer'); ?>
