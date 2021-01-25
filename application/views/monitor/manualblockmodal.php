<div class="modal bd-example-modal-lg" id="postModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" data-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
    	<div class="modal-header">
            <a href="javascript:void(0);" data-dismiss="modal" class="post-modal-close close new_post_model">×</a>
            <h4 class="modal-title"><i class="fa fa-plus"></i> Block post manually by selected reason.</h4>
        </div>
        <div class="modal-body" id="post_form_area">
			<div class="popup-content">
				<div class="modaldesign postModal_design">
					<form>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" style = "text-align:right" for="b_color">Reason<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="b_color" id="b_reason" class="form-control col-md-7 col-xs-12" required="">
                                    <?php
                                    foreach ($manual_block_reason as $reason) {
                                    ?>
                                        <option value="<?php echo $reason['id']; ?>"><?php echo $reason['value']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-sm-offset-3 col-xs-offset-3">
                                <input type = "hidden" id = "hiddenblockhref">
                                <a class="btn btn-info btn-sm" id="blockhref">Block</a>                  
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
    $('#blockhref').click(function(e){
        if (!confirm("Do you want to block really?")) {
            e.preventDefault();
            return false;			
        }
        $reasonid = $('#b_reason').val();
        var current_href = $('#hiddenblockhref').val();
        var newhref = current_href + "?reasonid=" + $reasonid;
        $('#blockhref').attr("href", newhref);
    });
</script>
