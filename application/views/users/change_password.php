<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
 <?php $this->load->view('elements/header'); ?>
   <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Update Password</h3>
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
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Update Login Admin Password</h2>
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
                    <br />
					<?php $attributes = array('name' => 'update_password', 'class' => 'form-horizontal form-label-left', 'id' => 'update_password');?>
					<?php echo form_open("users/change_password", $attributes);?>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Old Password<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						    <?php $old_pass = array('type'  => 'password', 'name' => 'old', 'id' => 'old', 'class' => 'form-control col-md-7 col-xs-12');?>
				            <?php echo form_input($old_pass);?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">New Password<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <?php $new_pass = array('type'  => 'password', 'name' => 'new', 'id' => 'new', 'class' => 'form-control col-md-7 col-xs-12');?>
				            <?php echo form_input($new_pass);?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Repeat New Password</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						    <?php $re_new_pass = array('type'  => 'password', 'name' => 'new_confirm', 'id' => 'new_confirm', 'class' => 'form-control col-md-7 col-xs-12');?>
				            <?php echo form_input($re_new_pass);?>
                        </div>
                      </div>
					  <div id="result">
					  </div>
					  <?php echo form_input($user_id);?>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="button" id="submit1" class="btn btn-success">Change</button>
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
		var error = false;
		$('#new').keyup(function() {
			var strength = checkStrength($('#new').val());
			var msg_class;
			var msg_test;
			if(strength == "Too short"){
				msg_class = 'warning';
				msg_test = 'Your Password is small please enter password more then 8 characters';
			}else if(strength == "Weak"){
				msg_class = 'info';
				msg_test = 'Your Password Strength is weak';
			}else if(strength == "Good"){
				msg_class = 'success';
				msg_test = 'Your Password Strength is Good';
			}else if(strength == "Strong"){
				msg_class = 'success';
				msg_test = 'Your Password Strength is Strong';
			}
			var msg = '<div class="alert alert-'+msg_class+' alert-dismissible fade in col-md-offset-3 col-md-6" role="alert">'+
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
					'<span aria-hidden="true">×</span></button>'+
                    '<strong>'+msg_test+'</strong> </div>';
			$('#result').html(msg);		
		})
		
		$('#new_confirm').keyup(function(){
			var msg_class;
			var msg_test;
			var new_pass = $('#new').val();
			if(new_pass != ''){
				var re_pass = $('#new_confirm').val();
				if(re_pass != ''){
					if(re_pass != new_pass){
						msg_class = 'info';
						msg_test = 'Please enter the same password as you enterd new password';
						error = true;
					}else{
						msg_class = 'success';
						msg_test = 'Both Password are same';
						error = false;
					}
				}
			}else{
				msg_class = 'warning';
				msg_test = 'Please Enter the New Password first';
				error = true;
			}
			
			var msg = '<div class="alert alert-'+msg_class+' alert-dismissible fade in col-md-offset-3 col-md-6" role="alert">'+
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
					'<span aria-hidden="true">×</span></button>'+
                    '<strong>'+msg_test+'</strong> </div>';
			$('#result').html(msg);
		})
		
		$('#submit1').click(function(e){
			
			e.preventDefault();
			
			var old = $('#old').val();
			var new_pass = $('#new').val();
			var re_pass = $('#new_confirm').val();
			if(old == ''){
				$('#old').parent().addClass( "bad" );
				error = true;
			}else{
				$('#old').parent().removeClass( "bad" );
				error = false;
			}
			if(new_pass == ''){
				$('#new').parent().addClass( "bad" );
				error = true;
			}else{
				$('#new').parent().removeClass( "bad" );
				error = false;
			}
			if(re_pass == ''){
				$('#new_confirm').parent().addClass( "bad" );
				error = true;
			}else{
				$('#new_confirm').parent().removeClass( "bad" );
				error = false;
			}
			
			if(error == false){
				$("#update_password").submit();
			}
		});
		function checkStrength(password) {
			var strength = 0
			if (password.length < 6) {
				$('#result').removeClass()
				$('#result').addClass('short')
				return 'Too short'
			}
			if (password.length > 7) strength += 1
			// If password contains both lower and uppercase characters, increase strength value.
			if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1
			// If it has numbers and characters, increase strength value.
			if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1
			// If it has one special character, increase strength value.
			if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
			// If it has two special characters, increase strength value.
			if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
			// Calculated strength value, we can return messages
			// If value is less than 2
			if (strength < 2) {
			$('#result').removeClass()
				$('#result').addClass('weak')
				return 'Weak'
			} else if (strength == 2) {
				$('#result').removeClass()
				$('#result').addClass('good')
				return 'Good'
			} else {
				$('#result').removeClass()
				$('#result').addClass('strong')
				return 'Strong'
			}
		}
	});
</script>
		