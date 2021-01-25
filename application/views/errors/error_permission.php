<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
 <?php $this->load->view('./elements/header'); ?>
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3><?php echo $title; ?></h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Not Authorized For this Page.</h2>
                 
                    <div class="clearfix"></div>
                    <div class="error-desc">
                        <br/><a href="<?php echo base_url('users/dashboard') ?>" class="btn btn-primary m-t">Go To Dashboard</a>
                    </div>
                  </div>                
              </div>
            </div>
          </div>
        </div>
<?php $this->load->view('./elements/footer'); ?>