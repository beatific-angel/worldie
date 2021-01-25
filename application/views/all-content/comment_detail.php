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
                      <input type="checkbox" id="selectall" /><label for="selectall">&nbsp;&nbsp;CheckAll</label>
                      <a href='javascript:void(0);' title="Delete all" class="btn btn-danger deleteall">Delete Selected</a>
                      <a href='javascript:void(0);' title="Block IP Selected" class="btn btn-warning blockAll">Block IP Selected</a>
                      <a href='javascript:void(0);' title="Unblock IP Selected" class="btn btn-warning unblockAll">Unblock IP Selected</a>
                      <a href='javascript:void(0);' title="Deactive User Selected" class="btn btn-primary deactiveAll">In Active User Selected</a>
                      <a href='javascript:void(0);' title="Active User Selected" class="btn btn-success activeAll">Active User Selected</a>
                    <?php echo generateCommentHtml($detail['comment'], $id, $ctype, 0, 20, '1'); ?>
                  </div>
                </div>
              </div>
			</div>
          </div>
        </div>
<?php $this->load->view('elements/footer'); ?>
<script type="text/javascript">
$(function() {
    $(document).on('click', '#selectall', function(){
// 	$("#selectall").click(function () {
		  //$('.comments').attr('checked', this.checked);
		  $('.comments').prop('checked', this.checked);
	});

    // if all checkbox are selected, check the selectall checkbox
	// and viceversa
	$(document).on('click', '.comments', function(){
		if($(".comments").length == $(".comments:checked").length) {
			$("#selectall").prop("checked", true);
		} else {
			$("#selectall").prop("checked", false);
		}

	});

    $(document).on('click', '.deleteall', function(){
        var val = [];
        $('.comments:checked').each(function(i) {
            val[i] = $(this).val();
        });

        if(val == ''){
            alert('select comment to delete');
        } else {
            request_url = '<?php echo base_url();?>comments/deleteAllComment';
            $.confirm({
                title: 'Do you Want to delete All Comments!',
                //content: 'Simple confirm!',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            url: request_url,
                            type: "POST",
                            data: {"id": val},
                            success:function(response){
                                // var val = JSON.parse(response);
                                var msg = '<div id="ajax-message" class="alert alert-success alert-dismissible fade in" role="alert">'+
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                                    '<span aria-hidden="true">×</span></button>'+
                                    '<strong>Comments Deleted Successfully.</strong> </div>';
                                $('.ajax_msg').html(msg);
                                location.reload();
                            }
                        });
                    },
                    cancel: function () {     
                    }
                }
            });
        }
        console.log(val);
    });

    $(document).on('click', '.blockAll', function(){
        var val = [];
        $('.comments:checked').each(function(i) {
            val[i] = $(this).data('ip');
        });

        if(val == ''){
            alert('select comment to block ip');
        } else {
            request_url = '<?php echo base_url();?>comments/blockAllIP';
            $.confirm({
                title: 'Do you Want to Block All IP!',
                //content: 'Simple confirm!',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            url: request_url,
                            type: "POST",
                            data: {"id": val},
                            success:function(response){
                                // var val = JSON.parse(response);
                                var msg = '<div id="ajax-message" class="alert alert-success alert-dismissible fade in" role="alert">'+
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                                    '<span aria-hidden="true">×</span></button>'+
                                    '<strong>IP Addresses Blocked Successfully.</strong> </div>';
                                $('.ajax_msg').html(msg);
                                location.reload();
                            }
                        });
                    },
                    cancel: function () {     
                    }
                }
            });
        }
        console.log(val);
    });

    $(document).on('click', '.unblockAll', function(){
        var val = [];
        $('.comments:checked').each(function(i) {
            val[i] = $(this).data('ip');
        });

        if(val == ''){
            alert('select comment to unblock ip');
        } else {
            request_url = '<?php echo base_url();?>comments/unblockAllIP';
            $.confirm({
                title: 'Do you Want to Unblock All IP!',
                //content: 'Simple confirm!',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            url: request_url,
                            type: "POST",
                            data: {"id": val},
                            success:function(response){
                                // var val = JSON.parse(response);
                                var msg = '<div id="ajax-message" class="alert alert-success alert-dismissible fade in" role="alert">'+
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                                    '<span aria-hidden="true">×</span></button>'+
                                    '<strong>IP Addresses Unblocked Successfully.</strong> </div>';
                                $('.ajax_msg').html(msg);
                                location.reload();
                            }
                        });
                    },
                    cancel: function () {     
                    }
                }
            });
        }
        console.log(val);
    });

    $(document).on('click', '.deactiveAll', function(){
        var val = [];
        $('.comments:checked').each(function(i) {
            val[i] = $(this).data('user_id');
        });

        if(val == ''){
            alert('select comment to inactive users');
        } else {
            request_url = '<?php echo base_url();?>comments/deactiveUsers';
            $.confirm({
                title: 'Do you Want to In-active Uses!',
                //content: 'Simple confirm!',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            url: request_url,
                            type: "POST",
                            data: {"id": val},
                            success:function(response){
                                // var val = JSON.parse(response);
                                var msg = '<div id="ajax-message" class="alert alert-success alert-dismissible fade in" role="alert">'+
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                                    '<span aria-hidden="true">×</span></button>'+
                                    '<strong>Users In-activated Successfully.</strong> </div>';
                                $('.ajax_msg').html(msg);
                                location.reload();
                            }
                        });
                    },
                    cancel: function () {     
                    }
                }
            });
        }
        console.log(val);
    });

    $(document).on('click', '.activeAll', function(){
        var val = [];
        $('.comments:checked').each(function(i) {
            val[i] = $(this).data('user_id');
        });

        if(val == ''){
            alert('select comment to active users');
        } else {
            request_url = '<?php echo base_url();?>comments/activeUsers';
            $.confirm({
                title: 'Do you Want to Active Uses!',
                //content: 'Simple confirm!',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            url: request_url,
                            type: "POST",
                            data: {"id": val},
                            success:function(response){
                                // var val = JSON.parse(response);
                                var msg = '<div id="ajax-message" class="alert alert-success alert-dismissible fade in" role="alert">'+
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                                    '<span aria-hidden="true">×</span></button>'+
                                    '<strong>Users Activated Successfully.</strong> </div>';
                                $('.ajax_msg').html(msg);
                                location.reload();
                            }
                        });
                    },
                    cancel: function () {     
                    }
                }
            });
        }
        console.log(val);
    });
});
    function showHideReplyArea(item_id, type){
        $('.'+type+'_append_reply_div_'+item_id).toggle();
        $('.'+type+'_show_hide_reply_div_button_'+item_id).toggleClass('active_arrow');
    }
    // common delete comment function
    function deleteComment(item_id, element_id, type, content_type, parent_comment,e){
        //showAjaxProcessing();
        // $(e).parents("ul.my_comment")[0].remove();return false
        $.confirm({
            title: 'Do you Want to delete the Comment!',
            //content: 'Simple confirm!',
            buttons: {
                confirm: function () {
                    $.ajax({
                        url : base_url+"comments/ssDeleteComment",
                        type:'POST',
                        data: {'id':item_id, 'element_id': element_id, 'type': type},
                        success:function(response){
                            console.log(response);
                            //removeAjaxProcessing();
                            var obj = $.parseJSON(response);
                            console.log(obj);
                            if(obj.type == 'success'){
                                alert('comment delete successfully');
                                // location.reload(true);
                                $(e).parents("ul.my_comment")[0].remove();return false
                                toastr.success(obj.text, {timeOut: 2000});
                            }else{
                                alert('comment delete failed');
                                // location.reload(true);
                                toastr.error(obj.text, {timeOut: 2000});
                            }
                            getCommentCount(element_id, type);
                        }
                    });
                },
                cancel: function () {     
                }
            }
        });
    }
</script>