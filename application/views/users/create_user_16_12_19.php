<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
 <?php $this->load->view('elements/header'); ?>
   <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Create User</h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Create New Admin User</h2>
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
				  <div id="infoMessage" class='alert-danger'><?php //echo $message; 
			    if($this->session->flashdata('success_val')){
					$msg['msg'] = $this->session->flashdata('success_val');
					$this->load->view('flash_messages/success', $msg);
			    }else if($this->session->flashdata('error_val')){
					$msg['msg'] = $this->session->flashdata('error_val');
					$this->load->view('flash_messages/error', $msg);
				}
			?></div>
                  <div class="x_content">
                    <br />
					<?php $attributes = array('name' => 'create_user', 'class' => 'form-horizontal form-label-left', 'id' => 'create_user');?>
					<?php echo form_open("users/create_user", $attributes);?>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">First Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						    <?php $first_name = array('type'  => 'text', 'name' => 'first_name', 'id' => 'first_name', 'class' => 'form-control col-md-7 col-xs-12');?>
				            <?php echo form_input($first_name);?>
                        </div>
                      </div>
					  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Last Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						    <?php $last_name = array('type'  => 'text', 'name' => 'last_name', 'id' => 'last_name', 'class' => 'form-control col-md-7 col-xs-12');?>
				            <?php echo form_input($last_name);?>
                        </div>
                      </div>
					   <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Username<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						    <?php $username = array('type'  => 'text', 'name' => 'identity', 'id' => 'identity', 'class' => 'form-control col-md-7 col-xs-12');?>
				            <?php echo form_input($username);?>
                        </div>
                      </div>
					  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Phone<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						    <?php $phone = array('type'  => 'text', 'name' => 'phone', 'id' => 'phone', 'class' => 'form-control col-md-7 col-xs-12');?>
				            <?php echo form_input($phone);?>
                        </div>
                      </div>
					  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Email<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						    <?php $email = array('type'  => 'text', 'name' => 'email', 'id' => 'email', 'class' => 'form-control col-md-7 col-xs-12');?>
				            <?php echo form_input($email);?>
                        </div>
                      </div>
					   <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">User Role<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						    <?php $options = array('2'  => 'Admin', '3' => 'Editor', '4' => 'Moderators');?>
				            <?php echo  form_dropdown('role_id', $options, '', 'class="form-control col-md-7 col-xs-12"');?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">New Password<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <?php $new_pass = array('type'  => 'password', 'name' => 'password', 'id' => 'password', 'class' => 'form-control col-md-7 col-xs-12');?>
				            <?php echo form_input($new_pass);?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Repeat New Password</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						    <?php $re_new_pass = array('type'  => 'password', 'name' => 'password_confirm', 'id' => 'password_confirm', 'class' => 'form-control col-md-7 col-xs-12');?>
				            <?php echo form_input($re_new_pass);?>
                        </div>
                      </div>
					  <div id="result">
					  </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="button" id="save_user" class="btn btn-success">Create User</button>
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
		
		$('#password').keyup(function() {
			var strength = checkStrength($('#password').val());
			var msg_class;
			var msg_test;
			if(strength == "Too short"){
				msg_class = 'warning';
				msg_test = 'Your Password is samll pls enter passowrd more then 8 words';
			}else if(strength == "Weak"){
				msg_class = 'info';
				msg_test = 'Your Password Strength is week';
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
		
		$('#password_confirm').keyup(function(){
			var msg_class;
			var msg_test;
			var password = $('#password').val();
			if(password != ''){
				var re_pass = $('#password_confirm').val();
				if(re_pass != ''){
					if(re_pass != password){
						msg_class = 'warning';
						msg_test = 'Please enter the same password as you enterd password';
					}else{
						msg_class = 'success';
						msg_test = 'Both Password are same';
					}
				}
			}else{
				msg_class = 'warning';
				msg_test = 'Please Enter the Password first';
			}
			
			var msg = '<div class="alert alert-'+msg_class+' alert-dismissible fade in col-md-offset-3 col-md-6" role="alert">'+
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
					'<span aria-hidden="true">×</span></button>'+
                    '<strong>'+msg_test+'</strong> </div>';
			$('#result').html(msg);
		})
		
		$('#save_user').click(function(e){
			e.preventDefault();
			var first_name = $('#first_name').val();
			var last_name  = $('#last_name').val();
			var identity   = $('#identity').val();
			var phone      = $('#phone').val();
			var password   = $('#password').val();
			var password_confirm    = $('#password_confirm').val();
			
			var error = false; 
			
			if(first_name == ''){
				$('#first_name').parent().addClass( "bad" );
				error = true;
			}else{
				$('#first_name').parent().removeClass( "bad" );
				error = false;
			}
			if(last_name == ''){
				$('#last_name').parent().addClass( "bad" );
				error = true;
			}else{
				$('#last_name').parent().removeClass( "bad" );
				error = false;
			}
			if(identity == ''){
				$('#identity').parent().addClass( "bad" );
				error = true;
			}else if(!isValidEmailAddress(identity)){
				$('#identity').parent().addClass( "bad" );
				error = true;
			}else{
				$('#identity').parent().removeClass( "bad" );
				error = false;
			}
			if(phone == ''){
				$('#phone').parent().addClass( "bad" );
				error = true;
			}else if(!isValidatePhoneNumber(phone)){
				$('#phone').parent().addClass( "bad" );
				error = true;
			}else{
				$('#phone').parent().removeClass( "bad" );
				error = false;
			}
			if(password == ''){
				$('#password').parent().addClass( "bad" );
				error = true;
			}else{
				$('#password').parent().removeClass( "bad" );
				error = false;
			}
			if(password_confirm == ''){
				$('#password_confirm').parent().addClass( "bad" );
				error = true;
			}else if(password != password_confirm){
				$('#password_confirm').parent().addClass( "bad" );
				error = true;
			}else{
				$('#password_confirm').parent().removeClass( "bad" );
				error = false;
			}
			
			if(error == false){
				//alert(error);
				$("#create_user").submit();
			}
		});
		
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
		
		function isValidEmailAddress(emailAddress) {
			var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
			return pattern.test(emailAddress);
		}

		function isValidatePhoneNumber(number) {
			var filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
			return filter.test(number);
		}

</script>
		