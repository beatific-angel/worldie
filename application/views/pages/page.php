<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/nicEdit.js"></script>
<script type="text/javascript">
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>
<div class="right_col" role="main" style="min-height: 617px;">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3><?= $title ?></h3>
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
						<h2>Pages</h2>
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
					<div id="infoMessage" class="alert-danger"></div>
					<div class="x_content" style="color: #333; font-family:Arial, Helvetica, sans-serif;">
						<br>
						<form action="<?php echo base_url();?>pages/update" method="post">
							<input type="hidden" name="page_name" value="<?= $title ?>">

							<div class="col-md-12 col-sm-12 col-xs-12 form-group ">
								<textarea style="width: 100%;" rows="10" cols="100" name="page_content"><?php echo $content; ?></textarea>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12 form-group ">
						 <input type="radio" name="status" value="1" checked> Active<br>
                         <input type="radio" name="status" value="0"> Inactive<br>
                         </div>
						<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right"  style="text-align:right;">
							<div class="input-group" style="display: inline-block;">
								<input type="submit" class="btn btn-round btn-primary"name="submit" value="update">
								
							</div>
						</div>

					</form>

				</div>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('elements/footer'); ?>