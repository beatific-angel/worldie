<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>
	  <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
                <div class="title_left">
					<h3><?php $type_string = str_replace('_',' ',$type); echo ucwords($type_string); ?></h3>
                </div>
			    <div class="title_right">
					<div class="col-md-7 col-sm-7 col-xs-12 form-group pull-right">
					    <div class="input-group">
								<a href="<?php echo base_url();?>category/create_category?type=<?php echo $type; ?>" class="btn btn-round btn-primary" type="button">Create  Category</a>
								<a href="<?php echo base_url();?>category/manage_category?type=<?php echo $type; ?>" class="btn btn-round btn-primary" type="button">Manage  Category</a>
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
                    <h2><?php $type_string = str_replace('_',' ',$type); echo ucwords($type_string); ?> Category List</small></h2>
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
								<th>Slug</th>
								<th>Description</th>
								<th>Status</th>
								<th>Created date</th>
								<th>Action</th>
							</tr>
                        </thead>
                        <tbody>
						
						<?php if(count($results) > 0){
							     foreach ($results as $result){ ?>
							<tr>
							    <td>
								    <th><input type="checkbox" id="check-all" class="flat"></th>
							    </td>
							    <td><?php echo $result->name;?></td>
							    <td><?php echo $result->slug;?></td> 
							    <td><?php echo $result->description;?></td>
							    <td class="event_status" current_status="<?php echo $result->status;?>" cat_id="<?php echo $result->id;?>" cat_type="<?php echo $type; ?>">
									<?php if($result->status == 1){
											echo '<span class="btn btn-success btn-xs " type="button">Active</span>';
										}else{
											echo '<span class="btn btn-warning btn-xs " status="0" type="button">In-Active</span>';
										}
									?>
								</td>
							    <td><?php echo date('Y-m-d', strtotime($result->created_at));?></td>
								<td>
									<div class="btn-group">
										<a href="<?php echo base_url();?>category/edit_category?type=<?php echo $type; ?>&id=<?php echo $result->id;?>" class="btn btn-warning" title="Edit Category"><i class="fa fa-edit"></i></a>
										<!--<a href="<?php echo base_url();?>category/delete_category?type=<?php echo $type; ?>&id=<?php echo $result->id;?>" class="btn btn-danger" title="Delete Category"><i class="fa fa-trash"></i></a>-->
										<a href="javascript:;" data_url="<?php echo base_url();?>category/delete_category?type=<?php echo $type; ?>&id=<?php echo $result->id;?>" class="btn btn-danger delete_category_item" title="Delete Category"><i class="fa fa-trash"></i></a>
									</div>
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
<script>
$(document).ready(function(){
	$(document).on('click', '.delete_category_item', function(){
		var redirect_url = $(this).attr('data_url');
		$.confirm({
		    title: 'Do you Want to delete the category!',
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