<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
 <?php $this->load->view('elements/header'); ?>
   <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <!-- <h3>Create User</h3> -->
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Update Admin User</h2>
                    <div class="clearfix"></div>
                  </div>
				  <div id="infoMessage">
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
					<?php $attributes = array('name' => 'edit_user', 'class' => 'form-horizontal form-label-left', 'id' => 'edit_user');?>
					<?php echo form_open(uri_string(), $attributes);?>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">First Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						    <?php $first_name = array('type'  => 'text', 'name' => 'first_name', 'id' => 'first_name', 'class' => 'form-control col-md-7 col-xs-12', 'value' => $user->first_name);?>
				            <?php echo form_input($first_name);?>
                        </div>
                      </div>
					  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Last Name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						    <?php $last_name = array('type'  => 'text', 'name' => 'last_name', 'id' => 'last_name', 'class' => 'form-control col-md-7 col-xs-12', 'value' => $user->last_name);?>
				            <?php echo form_input($last_name);?>
                        </div>
                      </div>
					  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Phone<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						    <?php $phone = array('type'  => 'text', 'name' => 'phone', 'id' => 'phone', 'class' => 'form-control col-md-7 col-xs-12', 'value' => $user->phone);?>
				            <?php echo form_input($phone);?>
                        </div>
                      </div>
					  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Email<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
						    <?php $email = array('type'  => 'text', 'name' => 'email', 'id' => 'email', 'class' => 'form-control col-md-7 col-xs-12','value' => $user->email);?>
				            <?php echo form_input($email);?>
                        </div>
                      </div>
					   <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">User Role<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        	<select name="role_id" id="role_id" class="form-control col-md-7 col-xs-12" required="">
                        		<option value="">Select Role</option>
                        		<?php
                        		foreach ($roles as $role) {
                        			?>
                        			<option <?php echo ($user->role_id==$role->id) ? 'selected' : ''; ?> value="<?php echo $role->id; ?>"><?php echo $role->name; ?></option>
                        			<?php
                        		}
                        		?>
                        	</select>
                        </div>
                      </div>
					  <?php echo form_hidden('id', $user->id);?>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="button" id="save_user" class="btn btn-success">Update Admin User</button>
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
		$('#save_user').click(function(e){
			e.preventDefault();
			var first_name = $('#first_name').val();
			var last_name  = $('#last_name').val();
			var email      = $('#email').val();
			var phone      = $('#phone').val();
			var role_id    = $('#role_id').val();
			
			var error = false; 
			
			if(role_id == ''){
				$('#role_id').parent().addClass( "bad" );
				error = true;
				return false;
			}else{
				$('#role_id').parent().removeClass( "bad" );
				error = false;
			}
			if(first_name == ''){
				$('#first_name').parent().addClass( "bad" );
				error = true;
				return false;
			}else{
				$('#first_name').parent().removeClass( "bad" );
				error = false;
			}
			if(last_name == ''){
				$('#last_name').parent().addClass( "bad" );
				error = true;
				return false;
			}else{
				$('#last_name').parent().removeClass( "bad" );
				error = false;
			}
			
			if(phone == ''){
				$('#phone').parent().addClass( "bad" );
				error = true;
				return false;
			}else if(!isValidatePhoneNumber(phone)){
				$('#phone').parent().addClass( "bad" );
				error = true;
				return false;
			}else{
				$('#phone').parent().removeClass( "bad" );
				error = false;
			}

			if(!isValidEmailAddress(email)){
                $('#email').parent().addClass( "bad" );
				error = true;
				return false;
			}
			else{
                $('#email').parent().removeClass( "bad" );
				error = false;
			}

			if(error == false){
				$("#edit_user").submit();
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
		