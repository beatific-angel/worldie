<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
 <?php $this->load->view('elements/header'); ?>
   <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Update block text</h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Update block text</h2>
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
				  <div id="infoMessage" class='alert-danger'>
			    <?php
				    if($this->session->flashdata('success_val')){
						$msg['msg'] = $this->session->flashdata('success_val');
						$this->load->view('flash_messages/success', $msg);
				    }else if($this->session->flashdata('error_val')){
						$msg['msg'] = $this->session->flashdata('error_val');
						$this->load->view('flash_messages/error', $msg);
					}
				?>
			</div>
                  <div class="x_content">
                    <br />
					<?php $attributes = array('name' => 'editblockform', 'class' => 'form-horizontal form-label-left', 'id' => 'editblockform');?>
					<?php echo form_open(uri_string(), $attributes);?>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="b_text">Text<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						    <?php $b_text = array('type'  => 'text', 'name' => 'b_text', 'id' => 'b_text', 'class' => 'form-control col-md-7 col-xs-12', 'value' => $block_edit->text);?>
				            <?php echo form_input($b_text);?>
                        </div>
                      </div>
					  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="b_color">Color<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
							<select name="b_color" id="b_color" class="form-control col-md-7 col-xs-12" required="">
                        		<?php
                        		foreach ($block_colors as $color) {
                        		?>
                                    <option <?php echo ($block_edit->color==$color) ? 'selected' : ''; ?> value="<?php echo $color; ?>"><?php echo $color; ?></option>

                        		<?php
                        		}
                        		?>
                        	</select>
						    
                        </div>
                      </div>
					   <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="b_reason">Reason<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						    <?php $b_reason = array('type'  => 'text', 'name' => 'b_reason', 'id' => 'b_reason', 'class' => 'form-control col-md-7 col-xs-12', 'value' => $block_edit->reason);?>
				            <?php echo form_input($b_reason);?>
                        </div>
                      </div>
					  
					  <div id="result">
					  </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="button" id="edit_block_text_btn" class="btn btn-success">Update</button>
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
		$('#edit_block_text_btn').click(function(e){
			e.preventDefault();
			var b_text = $('#b_text').val();
			var b_color  = $('#b_color').val();
			var b_reason   = $('#b_reason').val();
			
			var error = false; 
			
			if(b_text == ''){
				$('#b_text').parent().addClass( "bad" );
				error = true;
			}else{
				$('#b_text').parent().removeClass( "bad" );
				error = false;
			}
			if(b_color == ''){
				$('#b_color').parent().addClass( "bad" );
				error = true;
			}else{
				$('#b_color').parent().removeClass( "bad" );
				error = false;
			}
			if(b_reason == ''){
				$('#b_reason').parent().addClass( "bad" );
				error = true;
			}else{
				$('#b_reason').parent().removeClass( "bad" );
				error = false;
			}			
			
			if(error == false){
				$("#editblockform").submit();
			}
		});
	});
</script>
		