<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo base_url(); ?>assets/images/logo/favicon.ico" type="image/x-icon">
    <title><?php echo $title;?> | </title>
    <!-- Bootstrap -->
    <link href="<?php echo base_url();?>assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url();?>assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url();?>assets/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="<?php echo base_url();?>assets/vendors/animate.css/animate.min.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?php echo base_url();?>assets/build/css/custom.min.css" rel="stylesheet">
  </head>
  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
			<?php echo form_open("users/login");?>
              <h1>Bots Login</h1>
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
              <div>
				<?php $user_attr = array('type'  => 'text', 'name' => 'identity', 'id' => 'identity', 'class' => 'form-control');?>
				<?php echo form_input($user_attr);?>
              </div>
              <div>
				<?php $pass_attr = array('type'  => 'password', 'name' => 'password', 'id' => 'password', 'class' => 'form-control');?>
				<?php echo form_input($pass_attr);?>
              </div>
              <div>
				<?php echo form_submit('submit', lang('login_submit_btn'), array('class' => 'btn btn-default submit'));?></p>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <div class="clearfix"></div>
                <br />
                <div>
                  <img style="width: 150px;" src="../../assets/images/logo.png">
                  <!-- <h1><i class="fa fa-paw"></i> Worldlie !</h1> -->
                  <p>©2019 All Rights Reserved. Worldie (Social Media Network). Privacy and Terms</p>
                </div>
              </div>
			  <?php echo form_close();?>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>
