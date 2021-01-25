<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$logged_userid = $this->session->userdata('user_id');
$base_url_front = $this->config->item('base_url_front'); 
?>
<?php $this->load->view('elements/header'); ?>
<style type="text/css">
 .fur_comment {
    float: left;
    width: 90%;
    margin: 0 0 0 30px;
    padding: 10px 0 10px 0;
    border-bottom: 1px solid #dddddd;
}
.main_cross {
    float: left;
    width: 100%;
}
.fur_comment_img {
    float: left;
    height: 30px;
    margin-right: 10px;
    width: 30px;
}
.fur_comment_img img {
    width: 100%;
    border-radius: 100px;
    border: 1px solid #dfdfdf;
}
.fur_text {
    float: left;
    width: calc(100% - 130px);
}
.fur_text h2 {
    float: left;
    margin: 0 0 5px 0;
    width: 100%;
    font-size: 16px;
    color: #00b68d;
    font-weight: bold;
}
.main_cross:hover .cross_inner {
    display: block;
}
img {
    object-fit: cover;
}
.fur_comment_inner {
    float: left;
    padding-top: 3px;
    border-top: 1px solid #dddddd;
    margin: 3px 0 3px 0;
    width: 100%;
    margin-left: 10px;
}
.fur_comment_img {
    float: left;
    height: 30px;
    margin-right: 10px;
    width: 30px;
}
.fur_comment_img img {
    width: 100%;
    border-radius: 100px;
    border: 1px solid #dfdfdf;
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
                    <div class="comment_frame" id="comment_frame_0">
                        <div class="main_comment_div_0">
                            <?php if(count($groupPost) > 0){ 
                                foreach($groupPost as $comment){
                                    ?>
                                    <div class="fur_comment">
                                        <div class="main_cross">
                                            <div class="fur_comment_img">
                                                <?php 
                                                $comment_user_image = '';
                                                if($comment->cu_thumb_image != ''){
                                                    $comment_user_image = $comment->cu_thumb_image;
                                                }elseif($comment->cu_image != ''){
                                                    $comment_user_image = $comment->cu_image;
                                                }else{
                                                    if($comment->cu_gender == 1){
                                                        $comment_user_image = 'assets/images/male.jpg';
                                                    }else{
                                                        $comment_user_image = 'assets/images/female.jpg';
                                                    }
                                                }
                                                ?>
                                                <a href="<?php echo $base_url_front; ?>profile/<?php echo $comment->c_user_id?>">
                                                    <img src="<?php echo $base_url_front.''.$comment_user_image;?>" alt="img" />
                                                </a>
                                            </div>
                                            <div class="fur_text">
                                                <a href="<?php echo $base_url_front; ?>profile/<?php echo $comment->c_user_id?>">
                                                    <h2><?php echo $comment->cu_f_name.' '.$comment->cu_l_name?></h2>
                                                </a>
                                                <div class="edit_input">
                                                    <p id="current_comment_text_<?php echo $comment->main_comment_id;?>"><?php echo $comment->comment_text;?></p>
                                                    <?php if(isset($logged_userid)){ ?>
                                                        <div class="edit_comment" id="edit_comment_div_<?php echo $comment->main_comment_id;?>" style="display:none">
                                                            <input name="input" type="text" name="save_comment_text_<?php echo $comment->main_comment_id;?>" id="save_comment_text_<?php echo $comment->main_comment_id;?>" value="<?php echo $comment->comment_text;?>">
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                
                                                <?php if(isset($logged_userid)){ ?>
                                                    <div id="comment_reply_form_<?php echo $comment->main_comment_id;?>" style="display:none;">
                                                        <div class="inner_com_new">
                                                            <input type="text" placeholder="Add Reply here ..." name="reply_cmnt_text" id="cmnt_reply_text_<?php echo $comment->main_comment_id;?>">
                                                            
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>

                                            <span class="cross_inner cross_group">
                                                <img border="0" alt="img" src="<?php echo $base_url_front;?>assets/images/cancel_icon.png" onclick="deleteGroupPostComment(<?php echo $comment->main_comment_id;?>, this);">
                                            </span>
                                        </div>
                                        <?php if(count($comment->comment_reply) ==  0){?>
                                            <div id="append_reply_div_<?php echo $comment->main_comment_id;?>"></div>
                                        <?php } ?>
                                        <?php if(count($comment->comment_reply) > 0){ 
                                            foreach($comment->comment_reply as $reply){
                                                ?>
                                                <div id="append_reply_div_<?php echo $comment->main_comment_id;?>">
                                                    <div class="fur_comment_inner">
                                                        <div class="fur_comment_img">
                                                            <?php 
                                                            $reply_user_image = '';
                                                            if($reply->cu_thumb_image != ''){
                                                                $reply_user_image = $reply->cu_thumb_image;
                                                            }elseif($reply->cu_image != ''){
                                                                $reply_user_image = $reply->cu_image;
                                                            }else{
                                                                if($reply->cu_gender == 1){
                                                                    $reply_user_image = 'assets/images/male.jpg';
                                                                }else{
                                                                    $reply_user_image = 'assets/images/female.jpg';
                                                                }
                                                            }
                                                            ?>
                                                            <a href="<?php echo $base_url_front; ?>profile/<?php echo $reply->c_user_id?>">
                                                                <img src="<?php echo $base_url_front.''.$reply_user_image;?>" alt="img" />
                                                            </a>
                                                        </div>
                                                        <div class="fur_text">
                                                            <a href="<?php echo $reply_user_image; ?>profile/<?php echo $reply->c_user_id?>">
                                                                <h2><?php echo $reply->cu_f_name.' '.$reply->cu_l_name?></h2>
                                                            </a>
                                                            <div class="edit_input">
                                                                <p id="current_comment_text_<?php echo $reply->main_comment_id;?>"><?php echo $reply->comment_text;?></p>
                                                            </div>
                                                        </div>
                                                        <span class="cross_inner2 cross_group">
                                                            <img border="0" alt="img" src="<?php echo $base_url_front; ;?>assets/images/cancel_icon.png" onclick="deleteGroupPostComment(<?php echo $reply->main_comment_id;?>, this);">
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php } }?>
                                        </div>
                                    <?php } }?>
                                </div>
                            </div>
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
    function deleteGroupPostComment(comment_id,e){
        //showAjaxProcessing();
        // $(e).parents("ul.my_comment")[0].remove();return false
        $.ajax({
            url : base_url+"post/ssGroupPostComment_delete",
            type:'POST',
            data: {'comment_id':comment_id},
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