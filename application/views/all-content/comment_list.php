<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>
<link href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css" rel="stylesheet">
<style>
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
                    <h2>All Comment List</h2>
                    
                    <div class="clearfix"></div>
                    <div class="form-group col-md-3">
                        <label style="margin-top:20px;">Module</label>
                        <select class="form-control" id="module">
                            <option value="ALL">ALL</option>
                            <option value="Event">Event</option>
                            <option value="Group">Group</option>
                            <option value="Group Post">Group Post</option>
                            <option value="Media Video">Media Video</option>
                            <option value="Page Post">Page Post</option>
                            <option value="Post">Post</option>
                            <option value="Store Product">Store Product</option>
                            <option value="Wall Art">Wall Art</option>
                        </select>
                    </div>
                  </div>
                  <div class="x_content">
                      <a href='javascript:void(0);' title="Delete all" class="btn btn-danger deleteall">Delete Selected</a>
                      <a href='javascript:void(0);' title="Block IP Selected" class="btn btn-warning blockAll">Block IP Selected</a>
                      <a href='javascript:void(0);' title="Unblock IP Selected" class="btn btn-warning unblockAll">Unblock IP Selected</a>
                      <a href='javascript:void(0);' title="Deactive User Selected" class="btn btn-primary deactiveAll">In Active User Selected</a>
                      <a href='javascript:void(0);' title="Active User Selected" class="btn btn-success activeAll">Active User Selected</a>
                    <table id="datatable-post" class="table-striped table-bordered bulk_action display nowrap" style="width:100%">
                        <thead>
							<tr>
							    <th><input type="checkbox" id="selectall"/></th>
								<th>SI.No.</th>
                                <th>Module</th>
                                <th>Comment Author</th>
                                <th>Comment</th>
                                <th>Type</th>
                                <th>Created date</th>
                                <th>In Reply To</th>
                                <th>In Reply To Author</th>
                                <th>Original Author</th>
								<th>Original</th>
								<th>Status</th>
								<th>Action</th>
								
							</tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                  </div>
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
<?php $this->load->view('elements/footer'); ?>
<!--<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>-->
<!--<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>-->
<!--<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>-->
<script type="text/javascript">
$(function() {
    $(".preloader").hide();
    $(document).ajaxStart(function() {
        $(".preloader").show();
    });
    $( document ).ajaxStop(function() {
        $(".preloader").hide();
    });
table = $('#datatable-post').DataTable({
        language: {
            searchPlaceholder: "Search Records"
        },
        responsive : true,
       
        // "processing": true,
        // "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo base_url('comments/comment_list')?>",
            "type": "POST",
            "data": function ( d ) {
              return $.extend( {}, d, {
                "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash();?>",
                "module" : $('#module').val(),
              } );
            },
            "dataType": "json",
                "dataSrc": function (jsonData) {
                    return jsonData.data;
                }
            },
        "columnDefs": [
            {
                "targets": [1,2,3,4,5,7,8,9],
                "orderable": false
            },
            {
                 "targets": 8,
                "className": 'text-right'
            },
            // {
            //     "className": 'control',
            //     "orderable": false,
            //     "targets":   -1
            // }
        ],
    });
    // $('#datatable-post').css( 'display', 'table' );
 
table.responsive.recalc();
table.on('search.dt', function() {
                    table.columns.adjust();
                    table.responsive.recalc();
 });

$(document).on('change', '#module', function(){
    table.ajax.reload();
    // table.draw();
    // table.columns.adjust().responsive.recalc();
});


// $(document).on('click', '.view_comment_detail', function(){
//     alert('ssdfsdf');
// });

/*Delete Post Section*/
$(document).delegate('.delete_comment','click',function(){
    var id=$(this).attr('data-id');
    var thisobj = $('#delete_comment_'+id);
    var type = $(this).data('type');
    request_url = '<?php echo base_url();?>comments/deleteComment';
    $.confirm({
        title: 'Do you Want to delete the Comment!',
        //content: 'Simple confirm!',
        buttons: {
            confirm: function () {
                $.ajax({
                    url: request_url,
                    type: "POST",
                    data: {reportedcontent_id:id,reported_post:0,type:type},
                    success:function(response){
                        var val = JSON.parse(response);
                        var msg;
                        if(val.type == 'error'){
                            msg = '<div id="ajax-message" class="alert alert-danger alert-dismissible fade in" role="alert">'+
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                        '<span aria-hidden="true">×</span></button>'+
                        '<strong>'+val.text+'</strong> </div>';
                        }else{
                            msg = '<div id="ajax-message" class="alert alert-success alert-dismissible fade in" role="alert">'+
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                        '<span aria-hidden="true">×</span></button>'+
                        '<strong>'+val.text+'</strong> </div>';
                        }
                        // table.ajax.reload();
                        table.row($(thisobj).parents('tr')).remove().draw(false);
                        $('.ajax_msg').html(msg);   
                        setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
                    }
                });
            },
            cancel: function () {     
            }
        }
    });
});

$('body').on('click', '.view_page', function () {
        var item_id   = $(this).attr('item_id');
        if(item_id != ''){
            $.facebox($.get('<?php echo base_url();?>groups/item_view?item_id='+item_id,
            function(data) { 
                    $.facebox(data)
                    })
            )
        }
});

// add multiple select / deselect functionality
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
                                table.ajax.reload();
                                table.columns.adjust();
                                table.responsive.recalc();
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
                                table.ajax.reload();
                                table.columns.adjust();
                                table.responsive.recalc();
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
                                table.ajax.reload();
                                table.columns.adjust();
                                table.responsive.recalc();
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
                                table.ajax.reload();
                                table.columns.adjust();
                                table.responsive.recalc();
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
                                table.ajax.reload();
                                table.columns.adjust();
                                table.responsive.recalc();
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

    $(document).delegate('.block_ip','click',function(){
        var ip = $(this).data('ip');
        request_url = '<?php echo base_url();?>users/blockIP';
        $.confirm({
            title: 'Do you Want to block this IP!',
            //content: 'Simple confirm!',
            buttons: {
                confirm: function () {
                    $.ajax({
                        url: request_url,
                        type: "POST",
                        data: {ip:ip},
                        success:function(response){
                            table.ajax.reload();
                            table.columns.adjust();
                            table.responsive.recalc();
                        }   	
                    });
                },
                cancel: function () {

                }
            }
        });
    });

    $(document).delegate('.unblock_ip','click',function(){
        var ip = $(this).data('ip');
        request_url = '<?php echo base_url();?>users/unblockIP';
        $.confirm({
            title: 'Do you Want to unblock this IP!',
            //content: 'Simple confirm!',
            buttons: {
                confirm: function () {
                    $.ajax({
                        url: request_url,
                        type: "POST",
                        data: {ip:ip},
                        success:function(response){
                            table.ajax.reload();
                            table.columns.adjust();
                            table.responsive.recalc();
                        }   	
                    });
                },
                cancel: function () {

                }
            }
        });
    });

    $(document).on('click', '.user_status', function(e){
    // $('.user_status').click(function(e){
	    var new_status;
		var new_html;
		var request_url;
		var current_status = $(this).attr('status');
		var user_id = $(this).attr('user_id');
		if(current_status == 1){
			new_status = 0
			new_html   = '<span class="btn btn-warning btn-xs change_status" status="0" type="button">In-Active User</span>';
			request_url = '<?php echo base_url();?>/users/deactivate/'+user_id;
			$(this).attr("status", new_status);
		}else{
			new_status = 1
			new_html   = '<span class="btn btn-success btn-xs change_status" status="1" type="button">Active User</span>';
			request_url = '<?php echo base_url();?>/users/activate/'+user_id;
			$(this).attr("status", new_status);
		}
		$(this).html(new_html);
		$.ajax({
			url: request_url,
            type: "POST",
            data: {id : user_id, confirm:'yes'},
			success:function(response){
				var val = JSON.parse(response);
					var msg;
					if(val.type == 'error'){
						msg = '<div id="ajax-message" class="alert alert-danger alert-dismissible fade in" role="alert">'+'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+'<span aria-hidden="true">×</span></button>'+'<strong>'+val.text+'</strong> </div>';
					}else{
						msg = '<div id="ajax-message" class="alert alert-success alert-dismissible fade in" role="alert">'+'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+'<span aria-hidden="true">×</span></button>'+'<strong>'+val.text+'</strong> </div>';
					}
					$('.ajax_msg').html(msg);
					setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
			}
		});
	});
/*$(document).delegate('.reported_item_status','click',function(e){
    var new_status;
    var new_html;
    var request_url;
    var current_status = $(this).attr('current_status');
    var item_id   = $(this).attr('item_id');
    var item_type = $(this).attr('item_type');
    
    if(current_status == 1){
        new_status  = 0;
        new_html    = 'In-Active';
        new_class   = 'btn-warning';
        old_class   = 'btn-success';
        request_url = '<?php echo base_url();?>common/update_status';
        $(this).attr("current_status", new_status);
    }else{
        new_status  = 1;
        new_html    = 'Active';
        new_class   = 'btn-success';
        old_class   = 'btn-warning';
        request_url = '<?php echo base_url();?>common/update_status';
        $(this).attr("current_status", new_status);
    }

    $(this).html(new_html);
    $(this).removeClass(old_class);
    $(this).addClass(new_class);
    
    $.ajax({
        url: request_url,
        type: "POST",
        data: {id : item_id, type:item_type, new_status:new_status},
        success:function(response){
         var val = JSON.parse(response);
        
            if(val.type == 'error'){
                msg = '<div id="ajax-message" class="alert alert-danger alert-dismissible fade in" role="alert">'+
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
            '<span aria-hidden="true">×</span></button>'+
            '<strong>'+val.text+'</strong> </div>';
            }else{
                msg = '<div id="ajax-message" class="alert alert-success alert-dismissible fade in" role="alert">'+
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
            '<span aria-hidden="true">×</span></button>'+
            '<strong>'+val.text+'</strong> </div>';
            }
            $('.ajax_msg').html(msg);
            setTimeout(function(){$('#ajax-message').fadeOut();}, 2000);
        }
    });
  });*/
})

function groupPostCommentsViewById(groupPostId){
    base_url = '<?= base_url(); ?>';
    window.location.href = base_url + 'post/groupPostCommentView?groupPost_id='+groupPostId;
}
</script>