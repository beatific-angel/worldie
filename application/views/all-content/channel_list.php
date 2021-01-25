<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>
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
                            <h2>Channel List</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <table id="datatable-post" class="table table-striped table-bordered bulk_action">
                                <thead>
                                    <tr>
                                        <th>SI.No.</th>
                                        <th>User</th>
                                        <th>Category</th>
                                        <th>Channel Name</th>
                                        <th>Content Title</th>
                                        <th>Created date</th>
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
<?php $this->load->view('elements/footer'); ?>
<script type="text/javascript">
$(function() {
    table = $('#datatable-post').DataTable({
        language: {
            searchPlaceholder: "Search Records"
        },
        responsive: true,
//        "processing": true,
//        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo base_url('channels/channel_list')?>",
            "type": "POST",
            "data": function ( d ) {
                return $.extend( {}, d, {
                    "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash();?>",
                });
            },
//            "dataType": "json",
//                "dataSrc": function (jsonData) {
//                    return jsonData.data;
//                }
            },
        "columnDefs": [{
                "targets": [1,2,3,4,5],
                "orderable": false
            }
        ],
    });

    $('body').on('click', '.view_page', function () {
            var item_id   = $(this).attr('item_id');
            if(item_id != ''){
                $.facebox($.get('<?php echo base_url();?>channels/item_view?item_id='+item_id,
                function(data) { 
                        $.facebox(data)
                        })
                )
            }
    });

/*Delete Post Section*/
$(document).delegate('.delete_page','click',function(){
    var id=$(this).attr('data-id');
    var thisobj = $('#delete_post_'+id);
    request_url = '<?php echo base_url();?>channels/deleteChannel';
    $.confirm({
        title: 'Do you Want to delete the channel!',
        //content: 'Simple confirm!',
        buttons: {
            confirm: function () {
                $.ajax({
                    url: request_url,
                    type: "POST",
                    data: {id:id},
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

$(document).delegate('.item_status','click',function(e){
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
  });
})
function wallartCommentsViewById(wallart_id){
    base_url = '<?= base_url(); ?>';
    window.location.href = base_url + 'art/wallartCommentsView?wallart_id='+wallart_id;
}
</script>