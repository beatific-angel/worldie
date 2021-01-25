<div class="modal bd-example-modal-lg" id="importmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" data-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
    	<div class="modal-header">
            <a href="javascript:void(0);" data-dismiss="modal" class="post-modal-close close new_post_model">×</a>
            <h4 class="modal-title"><i class="fa fa-cloud-upload"></i> Import csv-text files where swear and bad keywords listed in</h4>
        </div>
        <div class="modal-body" id="post_form_area">
			<div class="popup-content">
				<div class="modaldesign postModal_design">
                    <?php $attributes = array('name' => 'importform', 'id' => 'importform', 'method'=>'post', 'enctype'=>'multipart/form-data');?>
					<?php echo form_open("monitor/importfile", $attributes);?>    
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" style = "text-align:right" for="b_color">File<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type = "file" id="blockedwords_file" name="blockedwords_file" accept=".txt, .csv">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" style = "text-align:right" for="b_color">Warning<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <span>Please, import files that includs only swear and bad words and also documented in standard format</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-sm-offset-3 col-xs-offset-3">                                
                                <button class="btn btn-info btn-sm" type = "submit">Import</button>                  
                            </div>
                        </div>
					</form>
				</div>
			</div>
		</div>
    </div>
  </div>
</div>
<script>
</script>
