<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>
<style type="text/css">
    
.comment-thread {
    background: white;
    padding: 10px;
    border: 1px solid #eeeeee;
    border-radius: 4px;
    float: left;
    width: 100%;
    margin-left: 0%;
    margin-top: 10px;
    margin-bottom: 10px;
}

.comment-thread ul {
    float: left;
    width: 100%;
}

.comment-thread li {
    list-style: none;
    float: left;
    width: 100%;
}

.comment-thread section {
    float: left;
    width: 100%;
}


.comment-thread .dp-image {
    float: left;
    width: 40px;
    height: 40px;
}


.comment-thread .dp-image img {
    border: 1px solid silver;
    border-radius: 100px;
    width: 100%;
    background-position: top center !important;
    background-size: cover !important;
}

.comment-thread ul {
    float: left;
    width: 100%;
}

.comment-thread li li {
    margin-top: 10px;
    padding-top: 10px;
    width: calc(100% - 50px);
    float: right;
    border: 1px solid #eeeeee;
    border-radius: 4px;
    padding: 10px;
}

.comment-thread .right-content {
    width: calc(100% - 50px);
    float: right;
    /* padding-right: 115px; */
    position: relative;
}

.comment-thread .right-content .actions {
    position: absolute;
    right: 0;
    opacity: 100;
    top: -5px;
    -webkit-transition: all .5s ease-in-out;
    -moz-transition: all .5s ease-in-out;
    -o-transition: all .5s ease-in-out;
    transition: all .5s ease-in-out;
}

.comment-thread .right-content .actions a {
    float: left;
}

.comment-thread .right-content .actions a img {
    width: 30px;
    margin-left: 8px;
}
</style>
<?php  $base_url_front = $this->config->item('base_url_front'); ?>

	  <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
                <div class="title_left">
					<h3><?php echo $title; ?></h3>
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
                    <h2><?= $title; ?></h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php echo generateCommentHtml($post[0]->comment, $post[0]->id, 'posts', $post[0]->total_comment_count, 20); ?>
                  </div>
                </div>
              </div>
			</div>
          </div>
        </div>
<?php $this->load->view('elements/footer'); ?>
<script type="text/javascript">
    function showHideReplyArea(item_id, type){
        $('.'+type+'_append_reply_div_'+item_id).toggle();
        $('.'+type+'_show_hide_reply_div_button_'+item_id).toggleClass('active_arrow');
    }
    // common delete comment function
    function deleteComment(item_id, element_id, type, content_type, parent_comment,e){
        //showAjaxProcessing();
        // $(e).parents("ul.my_comment")[0].remove();return false
        $.ajax({
            url : base_url+"post/ssDeleteComment",
            type:'POST',
            data: {'id':item_id, 'element_id': element_id, 'type': type},
            success:function(response){
                //removeAjaxProcessing();
                var obj = $.parseJSON(response);
                if(obj[0].type == 'success'){
                    alert('comment delete successfully');
                    // location.reload(true);
                    $(e).parents("ul.my_comment")[0].remove();return false
                    toastr.success(obj[0].text, {timeOut: 2000});
                }else{
                    alert('comment delete failed');
                    // location.reload(true);
                    toastr.error(obj[0].text, {timeOut: 2000});
                }
                getCommentCount(element_id, type);
            }
        })
    }
</script>