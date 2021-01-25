<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
 <?php $this->load->view('elements/header'); ?>
   <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Create Category</h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Create New <?php $type_string = str_replace('_',' ',$type); echo ucwords($type_string); ?> Category</h2>
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
                  <div class="x_content">
                    <br />
					<?php $attributes = array('name' => 'create_category', 'class' => 'form-horizontal form-label-left', 'id' => 'create_category');?>
					<?php echo form_open("category/create_category", $attributes);?>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						    <?php $name = array('type'  => 'text', 'name' => 'name', 'id' => 'name', 'class' => 'form-control col-md-7 col-xs-12');?>
				            <?php echo form_input($name);?>
                        </div>
                      </div>
					  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Description<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						    <?php $description = array('type'  => 'text', 'name' => 'description', 'id' => 'description', 'class' => 'form-control col-md-7 col-xs-12');?>
				            <?php echo form_textarea($description);?>
                        </div>
                      </div>
					  <div class="form-group">
						  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Status<span class="required">*</span></label>
						  <div class="col-md-6 col-sm-6 col-xs-12" style="margin-top:8px;">
							Active:
							<input type="radio" class="flat" name="status" id="status" value="1" checked="" required /> Inactive:
							<input type="radio" class="flat" name="status" id="status" value="0" />
						  </div>
					   </div>
					   <?php echo form_hidden('type', $type);?>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="button" id="save_category" class="btn btn-success">Create Category</button>
                        </div>
                      </div>
                    <?php echo form_close();?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
		
        <!-- /page content -->
<?php $this->load->view('elements/footer'); ?>
 <!-- jQuery -->
    
<script type="text/javascript">
	$(document).ready(function() {
		$('#save_category').click(function(e){
			e.preventDefault();
			var name         = $('#name').val();
			var description  = $('#description').val();
			var error = false; 
			
			if(name == ''){
				$('#name').parent().addClass( "bad" );
				error = true;
			}else{
				$('#name').parent().removeClass( "bad" );
				error = false;
			}
			if(description == ''){
				$('#description').parent().addClass( "bad" );
				error = true;
			}else{
				$('#description').parent().removeClass( "bad" );
				error = false;
			}
			
			if(error == false){
				$("#create_category").submit();
			}
		});
	});
</script>
		