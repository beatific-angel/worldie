<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php echo base_url();?>assets/images/logo/favicon.ico" type="image/x-icon">
   <title><?php echo $title;?></title>
    <!-- Bootstrap -->
    <link href="<?php echo base_url();?>assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url();?>assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url();?>assets/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php echo base_url();?>assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
	
    <!-- bootstrap-progressbar -->
    <link href="<?php echo base_url();?>assets/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="<?php echo base_url();?>assets/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="<?php echo base_url();?>assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?php echo base_url();?>assets/build/css/custom.min.css" rel="stylesheet">
	 <!-- Facebox Style -->
    <link href="<?php echo base_url();?>assets/facebox/facebox.css" rel="stylesheet">
     <!-- confirm dialog Style -->
    <link href="<?php echo base_url();?>assets/jquery-confirm/dist/jquery-confirm.min.css" rel="stylesheet">
	<!-- jQuery -->
    <script src="<?php echo base_url();?>assets/vendors/jquery/dist/jquery.min.js"></script>
    <!-- confirm dialog jQuery -->
    <script src="<?php echo base_url();?>assets/jquery-confirm/dist/jquery-confirm.min.js"></script>
	<script>
    var base_url = '<?php echo base_url();?>';
  </script>
  </head>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 1;">
              <a href="<?php echo base_url();?>users/dashboard" class="site_title"><img src="<?php echo base_url();?>assets/images/logo/logo.png" alt="..." height="50px;"width="100px;" style="margin-left:55px;"></a>
            </div>
            <div class="clearfix"></div>
            <!-- menu profile quick info -->
            <!--<div class="profile clearfix">
              <div class="profile_pic">
                 <img src="<?php echo base_url();?>assets/images/img.jpg" alt="..." class="img-circle profile_img">
              </div>
			   
              <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo user_inforamtion('name');?></h2>
              </div>
            </div>-->
            <!-- /menu profile quick info -->

            <br />
            
            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>Sections</h3>
                <ul class="nav side-menu">
                    <li><a><i class="fa fa-users"></i> Users <span class="fa fa-chevron-down"></span></a>
          						<ul class="nav child_menu">
          							<?php if(has_permission('admin_user-users')){?> 
          								<li><a href="<?php echo base_url();?>users/admin_user">Admin Users</a></li>
          							<?php }?>
          							<?php if(has_permission('site_user-users')){?>
          								<li><a href="<?php echo base_url();?>users/site_user">Site Users</a></li>
          							<?php }?>	
          						</ul>
                    </li>
                    <li><a><i class="fa fa-edit"></i> Categories <span class="fa fa-chevron-down"></span></a>
          						<ul class="nav child_menu">
          							<?php if(has_permission('event_category-category')){?>
          								<li><a href="<?php echo base_url();?>category/category_items?type=event">Event Category</a></li>
          							<?php }?>
									<?php if(has_permission('store_category-category')){ ?>
          								<li><a href="<?php echo base_url(); ?>category/category_items?type=store">Store Category</a></li>
          							<?php }?>
          							<?php if(has_permission('shop_category-category')){ ?>
          								<li><a href="<?php echo base_url(); ?>category/category_items?type=shop">Shop Product Category</a></li>
          							<?php }?>
									
          							<?php if(has_permission('art_category-category')){?>
          								<li><a href="<?php echo base_url(); ?>category/category_items?type=art">Art Gallery Category</a></li>
          							<?php }?>
									<?php if(has_permission('media_category-category')){?>
										<li><a href="<?php echo base_url(); ?>category/category_items?type=media">Media Gallery Category</a></li>
									<?php }?>
									<?php if(has_permission('media_channel_category-category')){?>
									  <li><a href="<?php echo base_url(); ?>category/category_items?type=media_channel">Media Channel Category</a></li>
									<?php }?>
									<?php if(has_permission('art_category-category')){?>
									  <li><a href="<?php echo base_url(); ?>category/category_items?type=art">Art Category</a></li>
									<?php }?>
									<?php if(has_permission('art_wall_category-category')){?>
									  <li><a href="<?php echo base_url(); ?>category/category_items?type=art_wall">Art Wall Category</a></li>
									<?php }?>
          							<?php /*if(has_permission('meet_date_category-category')){?>
          								<li><a href="<?php echo base_url(); ?>category/category_items?type=meet_date">Meet Date Category</a></li>
          							<?php }*/?>
          						</ul>
                    </li>
                     <li><a><i class="fa fa-users"></i> Settings <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <?php if(has_permission('admin_user-users')){?> 
                          <li><a href="<?php echo base_url();?>pages/terms">terms and condition</a></li>
                        <?php }?>
                         
                      </ul>
                    </li>
          					<?php if(has_permission('index-reportedcontent')){?>
          					<li>
          						<a href="<?php echo base_url();?>reported/index">
          							<i class="fa fa-times-circle-o"></i>Reported Content
          						</a>
                    </li>
          					<?php }?>
					<?php /*if(has_permission('index-sponseredcontent')){?>
					<li>
						<a href="/sponseredcontent/index">
							<i class="fa fa-link"></i>Sponsered Content
						</a>
                    </li>
					<?php }*/?>
                <li>
                  <a href="<?php echo base_url();?>advertisement/advertisement_list">
                  <i class="fa fa-times-circle-o"></i>Advertisements
                  </a>
                </li>

              <!-- <li><a><i class="fa fa-edit"></i> Media Channel<span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
              
                <li><a href="<?php echo base_url();?>mediachannel/media_channel_items">Media Channel</a></li>
                <!--<li><a href="<?php echo base_url();?>mediachannel/media_channel_category_items">Media Channel Category</a></li>

                </ul>
              </li>-->
              </div>
            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="<?php echo base_url();?>users/logout">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
			<div class="nav_menu">
				<nav>
				  <div class="nav toggle">
					<a id="menu_toggle"><i class="fa fa-bars"></i></a>
				  </div>
					<ul class="nav navbar-nav navbar-right">
						<li class="">
						  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						   
							<img src="<?php echo base_url();?>assets/images/<?php echo user_inforamtion('gender');?>" alt=""><?php echo user_inforamtion('name');?>
							<span class=" fa fa-angle-down"></span>
						  </a>
						  <ul class="dropdown-menu dropdown-usermenu pull-right">
							<li>
								<a href="<?php echo base_url();?>users/change_password">
									<span class="fa-passwd-reset fa-stack pull-right">
									  <i class="fa fa-undo fa-stack-2x"></i>
									  <i class="fa fa-lock fa-stack-1x"></i>
									</span> Reset Password
								</a>
							</li>
							<li>
								<a href="<?php echo base_url();?>users/logout">
									<i class="fa fa-sign-out pull-right"></i> Log Out
								</a>
							</li>
						  </ul>
						</li>
					</ul>
				</nav>
			</div>
        </div>
        <!-- /top navigation -->