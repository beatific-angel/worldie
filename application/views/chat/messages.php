<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>
<style>
    .messageBox{
        width: 80%;
        padding: 5px;
        margin-bottom: 2px;
        background-color: #f5f5f5;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
        display: inline-block;
        vertical-align: middle;
    }
    .muname{
        display: block;
    }
    .mdelete-div{
        width: 20%;
        display: inline;
        vertical-align: middle;
    }
    .mdelete-icon{
        vertical-align: middle;
        display: inline-block;
    }
    .mtime{
        font-weight: bolder;
        font-size: 10px;
    }
    .preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 99999;
        display: flex;
        flex-flow: row nowrap;
        justify-content: center;
        align-items: center;
        background-color: rgba(255,255,255,0.5);
    }
    .preloader img{max-width:200px;}
</style>
<!-- page content -->
<?php $base_url_front = $this->config->item('base_url_front'); ?>
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>User Chat</h3>
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
                        <div class="row x_title">
                            <div class="col-md-7"><h2>Messages Information</h2></div>
                            <div class="col-md-5">
                                <div class="col-md-8">
                                    <input type="text" id="search_val" class="form-control">
                                    <input type="hidden" id="uchat_id" value="<?php echo $chatId; ?>">
                                </div>
                                <div class="col-md-4">
                                    <a href="javascript:void(0);" id="search" class="btn btn-warning">Search</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="msg">
                            <?php if(isset($messages) && $messages) : ?>
                                <div class="row">                                   
                                    <?php
                                        $users = array();
                                        $lastChatUserId;
                                        foreach($messages as $item) : 
                                            if(!isset($users['first'])){
                                                $users['first'] = $item->user_id;
                                            } else if(!isset($users['second'])) {
                                                $users['second'] = $item->user_id;
                                            }
                                            if(empty($lastChatUserId)) :
                                    ?>
                                                <div class="col-md-12 <?php echo $item->user_id == $users['first'] ? '' : 'text-right' ?>"  style="margin-bottom: 15px;">
                                                    <strong class="muname"><?php echo ucwords($item->uname); ?></strong>
                                                    <div class="messageBox">
                                                        <div class="row">
                                                            <div class="col-md-12"><span style="word-break: break-all;"><?php echo $item->msg; ?></span></div>
                                                            <div class="col-md-12 text-right mtime"><?php echo $item->mtime; ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="mdelete-div">
                                                        <a href="javascript:void(0)" class="btn btn-danger mdelete-icon delete_msg" data-id="<?php echo $item->id; ?>" data-chat_id="<?php echo $item->chat_id; ?>"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                    <div class="clearfix"></div>
                                            <?php $lastChatUserId = $item->user_id;
                                                elseif($lastChatUserId == $item->user_id):  ?>
                                                    <?php if($item->user_id == $users['first']) : ?>
                                                        <div class="messageBox">
                                                            <div class="row">
                                                                <div class="col-md-12"><span style="word-break: break-all;"><?php echo $item->msg; ?></span></div>
                                                                <div class="col-md-12 mtime <?php echo $item->user_id == $users['first'] ? 'text-right' : 'text-left' ?>"><?php echo $item->mtime; ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="mdelete-div">
                                                            <a href="javascript:void(0)" class="btn btn-danger mdelete-icon delete_msg" data-id="<?php echo $item->id; ?>" data-chat_id="<?php echo $item->chat_id; ?>"><i class="fa fa-trash"></i></a>
                                                        </div>
                                                    <?php else : ?>
                                                        <div class="mdelete-div">
                                                            <a href="javascript:void(0)" class="btn btn-danger mdelete-icon delete_msg" data-id="<?php echo $item->id; ?>" data-chat_id="<?php echo $item->chat_id; ?>"><i class="fa fa-trash"></i></a>
                                                        </div>
                                                        <div class="messageBox">
                                                            <div class="row">
                                                                <div class="col-md-12"><span style="word-break: break-all;"><?php echo $item->msg; ?></span></div>
                                                                <div class="col-md-12 mtime <?php echo $item->user_id == $users['first'] ? 'text-right' : 'text-left' ?>"><?php echo $item->mtime; ?></div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="clearfix"></div>
                                            <?php  elseif($lastChatUserId != $item->user_id): ?>
                                                </div>
                                                <div class="col-md-12 <?php echo $item->user_id == $users['first'] ? '' : 'text-right' ?>"  style="margin-bottom: 15px;">
                                                    <strong class="muname"><?php echo ucwords($item->uname); ?></strong><br/>
                                                    <?php if($item->user_id == $users['first']) : ?>
                                                        <div class="messageBox">
                                                            <div class="row">
                                                                <div class="col-md-12"><span style="word-break: break-all;"><?php echo $item->msg; ?></span></div>
                                                                <div class="col-md-12 mtime <?php echo $item->user_id == $users['first'] ? 'text-right' : 'text-left' ?>"><?php echo $item->mtime; ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="mdelete-div">
                                                            <a href="javascript:void(0)" class="btn btn-danger mdelete-icon delete_msg" data-id="<?php echo $item->id; ?>" data-chat_id="<?php echo $item->chat_id; ?>"><i class="fa fa-trash"></i></a>
                                                        </div>
                                                    <?php else : ?>
                                                        <div class="mdelete-div">
                                                            <a href="javascript:void(0)" class="btn btn-danger mdelete-icon delete_msg" data-id="<?php echo $item->id; ?>" data-chat_id="<?php echo $item->chat_id; ?>"><i class="fa fa-trash"></i></a>
                                                        </div>
                                                        <div class="messageBox">
                                                            <div class="row">
                                                                <div class="col-md-12"><span style="word-break: break-all;"><?php echo $item->msg; ?></span></div>
                                                                <div class="col-md-12 mtime <?php echo $item->user_id == $users['first'] ? 'text-right' : 'text-left' ?>"><?php echo $item->mtime; ?></div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="clearfix"></div>   
                                            <?php $lastChatUserId = $item->user_id;
                                            endif; ?>
                                    <?php endforeach;?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                </div>        
            </div>
    </div>
</div>
    <section class="preloader">
        <div class="spinner">
              <span class="spinner-rotate"></span>
              <img src="<?php echo base_url();?>assets/images/loader.gif">
        </div>
    </section>
<!-- /page content -->
<script type="text/javascript">
$(document).ready(function(){
    $(".preloader").hide();
    $(document).ajaxStart(function() {
        $(".preloader").show();
    });
    $( document ).ajaxStop(function() {
        $(".preloader").hide();
    });

    $(document).delegate('.delete_msg','click',function(){
        var id = $(this).data('id');
        var chat_id = $(this).data('chat_id');
        request_url = '<?php echo base_url();?>chat/deleteMessage';
        $.confirm({
            title: 'Do you Want to delete this message!',
            //content: 'Simple confirm!',
            buttons: {
                confirm: function () {
                    $.ajax({
                        url: request_url,
                        type: "POST",
                        data: {id:id, chat_id : chat_id},
                        success:function(response){
                            location.reload();
                        }   	
                    });
                },
                cancel: function () {

                }
            }
        });
    });

    $(document).on('click', '#search', function(){
        var search = $('#search_val').val();
        var chatId = $('#uchat_id').val();
        request_url = '<?php echo base_url();?>chat/searchChat';
//        if(search != '' && search != null) {
            $.ajax({
                url: request_url,
                type: "POST",
                data: {search:search, chatId : chatId},
                success:function(response){
                    var json = $.parseJSON(response);
                    $('#msg').html(json.html);
//                    location.reload();
                }
            });
//            location.href = "<?php echo base_url('chat/messages') ?>"+'/'+chatId+'?search='+search;
//        }
    });
    $(document).on('keypress', '#search_val',function(e) {
        if (e.keyCode === 13) {
            var search = $('#search_val').val();
            var chatId = $('#uchat_id').val();
            request_url = '<?php echo base_url();?>chat/searchChat';
    //        if(search != '' && search != null) {
            $.ajax({
                url: request_url,
                type: "POST",
                data: {search:search, chatId : chatId},
                success:function(response){
                    var json = $.parseJSON(response);
                    $('#msg').html(json.html);
//                    location.reload();
                }
            });
        }
    });
});
</script>
<?php $this->load->view('elements/footer'); ?>
