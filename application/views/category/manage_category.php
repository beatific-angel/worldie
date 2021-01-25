 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<style>

#sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
#sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 40px; }
#sortable li span { position: absolute; margin-left: -1.3em; }
#save-reorder{ padding:10px; background:#12a574;color:#fff;}
.wrapper{width:60%; margin: 0 auto;}
</style>
<script>
$(function() {
$( "#sortable" ).sortable();
$( "#sortable" ).disableSelection();
});
</script>
 <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Manage <?php $type_string = str_replace('_',' ',$type); echo ucwords($type_string); ?> Categories</h3>
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
			<div class="ajax_msg"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Manage <?php  echo ucwords($type_string); ?> Categories</h2>
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
                    <div class="wrapper">
						<ul id="sortable">
						<?php //echo $msg['msg']; ?>
						<?php foreach ($results as $result) { ?>
							<li data-id="<?php echo $result->id ;?>" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?php echo $result->name ;?></li>
						<?php  }?>
						</ul>
						<button id="save-reorder">Save Order</button> 
					</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
		<script>
			$(document).ready(function(){
				$(document).on('click','#save-reorder',function(){
					var list = new Array();
					var type = '<?php echo $type ?>';
					$('#sortable').find('.ui-state-default').each(function(){
						 var id=$(this).attr('data-id');	
						 list.push(id);
					});
					var data=JSON.stringify(list);
					$.ajax({
					url: '<?php echo base_url();?>category/save_category_order', // server url
					type: 'POST', //POST or GET 
					data: {type:type,token:'reorder',data:data}, // data to send in ajax format or querystring format
					datatype: 'json',
					success: function() {
						location.href = window.location.href; 
					}
			 
				});
				});
				
			});
	 </script>
<?php $this->load->view('elements/footer'); ?>