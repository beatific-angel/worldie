 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>
<style>
.m_bottom{margin-bottom:20px;}
</style>
 <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Reported Content</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel" style="height:760px;">
                  <div class="x_title">
                    <h2>Reported Content</h2>
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
                    <div class="row">
                      <div class="col-md-12">
					  <pre><?php// print_R($results); ?></pre>
          					    <?php foreach ($results as $key => $value){ ?>
            							<div class="col-md-3 col-sm-6 col-xs-12 m_bottom">
            							  <a href="<?php echo base_url();?>reported/reported_item?item=<?php echo $key;?>"><div class="pricing">
            								<div class="title">
            								  <h2><?php echo str_replace('_', ' ', $key);?></h2>
            								  <h1><?php echo $value;?></h1>
            								</div>
            							  </div></a>
            							</div>
                         <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
<?php $this->load->view('elements/footer'); ?>