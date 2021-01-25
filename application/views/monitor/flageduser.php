<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>

	  <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
                <div class="title_left">
					<h3>Flagged Users</h3>
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
                    <h2>Flagged Users</small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table id="datatable-fuser" class="table table-striped table-bordered bulk_action">
                        <thead>
							<tr>
                                <th>S.No.</th>
								<th>Name</th>
								<th>Email</th>
                                <th>IP Address</th>
                                <th>Created date</th>
                                <th>count of blocked posts</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
                        </thead>
                        <tbody>
                        <?php
                        $i=1;
                         foreach ($users as $user){ ?>
							<tr>
                                <td class="center"><?php echo $i; ?>.</td>
							    <td><?php echo $user->first_name.' '.$user->last_name;?></td>
							    <td><?php echo $user->email;?></td>
                                <td><?php echo $user->ip_address;?></td>
                                <td><?php echo date('Y-m-d', strtotime($user->created_at));?></td>
                                <td><?php echo $user->count;?></td>
								<td class="user_status" status="<?php echo $user->status;?>" user_id="<?php echo $user->id;?>">
									<?php
									if($user->status == 1){
											echo '<span class="btn btn-success btn-xs " status="1" type="button">Active</span>';
										}else{
											echo '<span class="btn btn-warning btn-xs " status="0" type="button">In-Active</span>';
                                        }
                                    if($user->is_block){
											echo '<span class="btn btn-warning btn-xs " status="1" type="button">IP Blocked</span>';
										}else{
											echo '<span class="btn btn-success btn-xs " status="0" type="button">IP Unblocked</span>';
										}
									?>
								</td>
								<td>
									<?php                                        
                                        echo '<a href="'.base_url().'monitor/userdetail/'.$user->id.'" class="btn btn-success btn-xs mdetail" title="See detail"><i class="fa fa fa-mail-forward"></i></a>';
                                        echo '<a href="'.base_url().'monitor/deleteuser/'.$user->id.'" class="btn btn-danger btn-xs mdelete" title="Delete User"><i class="fa fa-trash"></i></a>';
                                        if($user->status){
                                            echo '<a href="'.base_url().'monitor/deactivateuser/'.$user->id.'" class="btn btn-danger btn-xs mdeactivate" title="Deactivate User"><i class="fa fa-lock"></i></a>';
                                        } else {
                                            echo '<a href="'.base_url().'monitor/activateuser/'.$user->id.'" class="btn btn-warning btn-xs mactivate" title="Activate User"><i class="fa fa-unlock"></i></a>';
                                        }
                                        if($user->is_block){
                                            echo '<a href="'.base_url().'monitor/unblockip/'.$user->id.'" class="btn btn-warning btn-xs munblockip" title="Unblock ip"><i class="fa fa-times"></i></a>';
                                        } else {
                                            echo '<a href="'.base_url().'monitor/blockip/'.$user->id.'" class="btn btn-danger btn-xs mblockip" title="Block ip"><i class="fa fa-times"></i></a>';
                                        }
                                        
									?>
								</td>
							</tr>
						<?php $i++;} ?>	
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
    $('#datatable-fuser').DataTable({
        order: [[ 0, 'asc' ]],        
        initComplete: function(settings, json) {
            $('.mdeactivate').click(function(e){
                    if (!confirm("Do you want to deactivate user really?")) {
                        e.preventDefault();
                        return false;			
                    }
                });
                
                $('.mdelete').click(function(e){
                    if (!confirm("Do you want to delete user really?")) {
                        e.preventDefault();
                        return false;			
                    }
                });

                $('.mblockip').click(function(e){
                    if (!confirm("Do you want to block ip really?")) {
                        e.preventDefault();
                        return false;			
                    }
                });

                $('.munblockip').click(function(e){
                    if (!confirm("Do you want to unblock ip really?")) {
                        e.preventDefault();
                        return false;			
                    }
                });
                $('.mactivate').click(function(e){
                    if (!confirm("Do you want to activate user really?")) {
                        e.preventDefault();
                        return false;			
                    }
                });
                
                
        }
    });
});
</script>
<?php $this->load->view('elements/footer'); ?>
