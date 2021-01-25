 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
 <?php $this->load->view('elements/header', $title); ?>
 <!-- page content -->
        <div class="right_col" role="main">
		  <br><br><br>
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
				<div id="page-content">
    <!-- Dashboard 2 Header -->
    <div class="content-header">
        <ul class="nav-horizontal text-center">
            <li class="active">
                <a href="javascript:void(0)"><img class="top_menu_img" src="../../assets/images/icons/electric-car.png" /> Pre-Processing</a>
            </li>
            <li>
                <a href="javascript:void(0)"><img class="top_menu_img" src="../../assets/images/icons/futuristic.png" /> Datasets</a>
            </li>
            <li >
                <a href="javascript:void(0)"><img class="top_menu_img" src="../../assets/images/icons/diet.png" />Input</a>
            </li>
            <li>
                <a href="javascript:void(0)"><img class="top_menu_img" src="../../assets/images/icons/house.png" /> Cases & Types</a>
            </li>
            <li>
                <a href="javascript:void(0)"><img class="top_menu_img" src="../../assets/images/icons/dolphin.png" /> AI Engine</a>
            </li>
            <li>
                <a href="javascript:void(0)"><img class="top_menu_img" src="../../assets/images/icons/rocket.png" /> Simulations</a>
            </li>
            <li>
                <a href="javascript:void(0)"><img class="top_menu_img" src="../../assets/images/icons/helicopter.png" /> Actions</a>
            </li>
            <li>
                <a href="javascript:void(0)"><img class="top_menu_img" src="../../assets/images/icons/life-insurance.png" /> Outputs</a>
            </li>
            
            <li>
                <a href="javascript:void(0)"><img class="top_menu_img" src="../../assets/images/icons/superheroe.png" /> Bot Accounts</a>
            </li>
            <li>
                <a href="javascript:void(0)"><img class="top_menu_img" src="../../assets/images/icons/firefighter-car.png" /> APIs</a>
            </li>
            <li >
                <a href="javascript:void(0)"><img class="top_menu_img" src="../../assets/images/icons/lifeboat.png" /> Email Accounts</a>
            </li>

        </ul>
    </div>
    <!-- END Dashboard 2 Header -->

    <!-- Dashboard 2 Content -->
    <div class="row">
    </div>
    <!-- END Dashboard 2 Content -->
</div>

        </div>
<?php $this->load->view('elements/footer'); ?>
		