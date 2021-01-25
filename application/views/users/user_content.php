<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>
<style>
    .dataTables_scrollHeadInner{
        width: 100% !important;
    }
    .dataTables_scrollHeadInner table{
        width: 100% !important;
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
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>User Content</h3>
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
                            <h2>Duplicate Site Users</small></h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
                                <thead>
                                    <tr>
                                        <th>Name</th> 
                                        <!--<th>Created date</th>-->
                                        <th>Post</th>
                                        <th>Group Post</th>
                                        <th>Page Post</th>
                                        <th>Media Video</th>
                                        <th>Event</th>
                                        <th>Wall Art</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user){ ?>
                                        <tr>
                                            <td><?php echo $user->first_name.' '.$user->last_name;?></td>
                                            <!--<td><?php echo date('Y-m-d', strtotime($user->created_at));?></td>-->
                                            <td><a href="javascript:void(0);" class="btn btn-info btn-xs post" data-id="<?php echo $user->id ?>">View</a></td>
                                            <td><a href="javascript:void(0);" class="btn btn-info btn-xs gpost" data-id="<?php echo $user->id ?>">View</a></td>
                                            <td><a href="javascript:void(0);" class="btn btn-info btn-xs ppost" data-id="<?php echo $user->id ?>">View</a></td>
                                            <td><a href="javascript:void(0);" class="btn btn-info btn-xs mpost" data-id="<?php echo $user->id ?>">View</a></td>
                                            <td><a href="javascript:void(0);" class="btn btn-info btn-xs epost" data-id="<?php echo $user->id ?>">View</a></td>
                                            <td><a href="javascript:void(0);" class="btn btn-info btn-xs wpost" data-id="<?php echo $user->id ?>">View</a></td>
                                            <td>
                                                <?php
//                                                    if($edit_status){
                                                        if($user->is_block){
                                                            echo '<a href="javascript:void(0);" class="unblock_ip" data-ip="'.$user->ip_address.'" title="Block IP"><span class="btn btn-warning btn-xs" type="button">UnBlock IP</span></a>';
                                                        } else {
                                                            echo '<a href="javascript:void(0);" class="block_ip" data-ip="'.$user->ip_address.'" title="Block IP"><span class="btn btn-danger btn-xs" type="button">Block IP</span></a>';
                                                        }
//                                                    }
                                                    if($delete_status){
                                                        echo '<a href="javascript:void(0);" class="btn btn-danger btn-xs delete" data-id="'.$user->id.'" title="Delete Site User">Delete All User Data</a>';
                                                    }
                                                    echo '<a href="'.base_url("users/user_profile/").$user->id.'" class="btn btn-primary btn-xs" target="_blank">User Data</a>';
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>	
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
<input type="hidden" id="user_id" value="">
<div class="example-modal" >
    <div class="modal" id="post_modal">
        <div class="modal-dialog" style="width: 90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: 0px;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Post</h4>
                </div>
                <div class="modal-body">
                    <table id="posttable" class="table table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>Content</th>
                                <th>Created date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<div class="example-modal" >
    <div class="modal" id="gpost_modal">
        <div class="modal-dialog" style="width: 90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: 0px;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Group Post</h4>
                </div>
                <div class="modal-body">
                    <table id="gposttable" class="table table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>Group</th>
                                <th>Type</th>
                                <th>Content</th>
                                <th>Created date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<div class="example-modal" >
    <div class="modal" id="ppost_modal">
        <div class="modal-dialog" style="width: 90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: 0px;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Page Post</h4>
                </div>
                <div class="modal-body">
                    <table id="pposttable" class="table table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>Page</th>
                                <th>Type</th>
                                <th>Content</th>
                                <th>Created date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<div class="example-modal" >
    <div class="modal" id="epost_modal">
        <div class="modal-dialog" style="width: 90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: 0px;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Event Post</h4>
                </div>
                <div class="modal-body">
                    <table id="eposttable" class="table table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>Event Planning</th>
                                <th>Created date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<div class="example-modal" >
    <div class="modal" id="mpost_modal">
        <div class="modal-dialog" style="width: 90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: 0px;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Media Video</h4>
                </div>
                <div class="modal-body">
                    <table id="mposttable" class="table table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>Content Title</th>
                                <th>Created date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<div class="example-modal" >
    <div class="modal" id="wpost_modal">
        <div class="modal-dialog" style="width: 90%">
            <div class="modal-content">
                <div class="wodal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: 0px;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Wall Art</h4>
                </div>
                <div class="modal-body">
                    <table id="wposttable" class="table table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>Wall Name</th>
                                <th>Content Title</th>
                                <th>Created date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<div class="example-modal" >
    <div class="modal" id="fmodal">
        <div class="modal-dialog" style="width: 90%">
            <div class="modal-content">
                <div class="wodal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: 0px;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Reported Content</h4>
                </div>
                <div class="modal-body" id="viewbody">
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<!-- /page content -->
<script type="text/javascript">
$(document).ready(function() {
    $(".preloader").hide();
        $(document).ajaxStart(function() {
            $(".preloader").show();
        });
        $( document ).ajaxStop(function() {
            $(".preloader").hide();
        });
        $(".modal").on('hide.bs.modal', function () {
            $(".preloader").hide();
        });
        $('body').on('click', '.dview_reported', function () {
            //$('.view_reported').click(function(){
                    var item_type = $(this).attr('item_type');
                    var item_id   = $(this).attr('item_id');
                    if(item_type != '' && item_id != ''){
                            $.facebox($.get('<?php echo base_url();?>reported/item_view?item_type='+item_type+'&item_id='+item_id,
                            function(data) { 
                                    $.facebox(data)
                                    })
                            )
                            $(".modal:visible").modal('toggle');
                    }
            });

        $('body').on('click', '.view_media_video', function () {
            var item_id = $(this).attr('item_id');
            var url = '<?php echo user_media_video();?>';
            window.open(url+item_id, '_blank');
        });
        var posttable = $('#posttable').DataTable({
            "processing": true,
            //"serverSide": true,
            "responsive": true,
            "scrollY":        "300px",
            "scrollCollapse": true,
            "paging":         false,
            "ordering": false,
            "ajax" : {  
                url : "<?=  base_url('users/postByUserDatatable');?>",  
                type : "POST",
                data : function(d) {
                    d.user_id = $('#user_id').val();
                }
            },

        });
        $(document).on('click', '.post', function(){
            var id = $(this).data('id');
            $('#user_id').val(id);
            $('#post_modal').modal('toggle');
            posttable.ajax.reload();
        });
        $(document).delegate('.delete_post','click',function(){
            var id=$(this).attr('data-id');
            var thisobj = $('#delete_post_'+id);
            request_url = '<?php echo base_url();?>users/postDeleted';
            $.confirm({
                title: 'Do you Want to delete the Post!',
                //content: 'Simple confirm!',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            url: request_url,
                            type: "POST",
                            data: {reportedcontent_id:id,reported_post:0},
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
                                posttable.ajax.reload();
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

        var gposttable = $('#gposttable').DataTable({
            "processing": true,
            //"serverSide": true,
//            "responsive": true,
            "scrollY":        "300px",
            "scrollCollapse": true,
            "paging":         false,
            "ordering": false,
            "ajax" : {  
                url : "<?=  base_url('users/gpostByUserDatatable');?>",  
                type : "POST",
                data : function(d) {
                    d.user_id = $('#user_id').val();
                }
            },
        });
        $(document).on('click', '.gpost', function(){
            var id = $(this).data('id');
            $('#user_id').val(id);
            $('#gpost_modal').modal('toggle');
            gposttable.ajax.reload();
        });
        $(document).delegate('.delete_group_post','click',function(){
            var id=$(this).attr('data-id');
            var thisobj = $('#delete_post_'+id);
            request_url = '<?php echo base_url();?>users/gpostDeleted';
            $.confirm({
                title: 'Do you Want to delete the Post!',
                //content: 'Simple confirm!',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            url: request_url,
                            type: "POST",
                            data: {reportedcontent_id:id,reported_post:0},
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
                                gposttable.ajax.reload();
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

        $(document).delegate('.delete_page_post','click',function(){
            var id=$(this).attr('data-id');
            var thisobj = $('#delete_post_'+id);
            request_url = '<?php echo base_url();?>users/ppostDeleted';
            $.confirm({
                title: 'Do you Want to delete the Post!',
                //content: 'Simple confirm!',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            url: request_url,
                            type: "POST",
                            data: {reportedcontent_id:id,reported_post:0},
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
                                pposttable.ajax.reload();
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

        $(document).delegate('.delete_media_post','click',function(){
            var id=$(this).attr('data-id');
            var thisobj = $('#delete_post_'+id);
            request_url = '<?php echo base_url();?>users/mpostDeleted';
            $.confirm({
                title: 'Do you Want to delete the Post!',
                //content: 'Simple confirm!',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            url: request_url,
                            type: "POST",
                            data: {reportedcontent_id:id,reported_post:0},
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
                                mposttable.ajax.reload();
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

        $(document).delegate('.delete_wall_post','click',function(){
            var id=$(this).attr('data-id');
            var thisobj = $('#delete_post_'+id);
            request_url = '<?php echo base_url();?>users/wpostDeleted';
            $.confirm({
                title: 'Do you Want to delete the Post!',
                //content: 'Simple confirm!',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            url: request_url,
                            type: "POST",
                            data: {reportedcontent_id:id,reported_post:0},
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
                                wposttable.ajax.reload();
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

        $(document).delegate('.delete_event_post','click',function(){
            var id=$(this).attr('data-id');
            var thisobj = $('#delete_post_'+id);
            request_url = '<?php echo base_url();?>users/epostDeleted';
            $.confirm({
                title: 'Do you Want to delete the Post!',
                //content: 'Simple confirm!',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            url: request_url,
                            type: "POST",
                            data: {reportedcontent_id:id,reported_post:0},
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
                                eposttable.ajax.reload();
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

        var pposttable = $('#pposttable').DataTable({
            "processing": true,
            //"serverSide": true,
//            "responsive": true,
            "scrollY":        "300px",
            "scrollCollapse": true,
            "paging":         false,
            "ordering": false,
            "ajax" : {  
                url : "<?=  base_url('users/ppostByUserDatatable');?>",  
                type : "POST",
                data : function(d) {
                    d.user_id = $('#user_id').val();
                }
            },
        });
        $(document).on('click', '.ppost', function(){
            var id = $(this).data('id');
            $('#user_id').val(id);
            $('#ppost_modal').modal('toggle');
            pposttable.ajax.reload();
        });

        var eposttable = $('#eposttable').DataTable({
            "processing": true,
            //"serverSide": true,
//            "responsive": true,
            "scrollY":        "300px",
            "scrollCollapse": true,
            "paging":         false,
            "ordering": false,
            "ajax" : {  
                url : "<?=  base_url('users/epostByUserDatatable');?>",  
                type : "POST",
                data : function(d) {
                    d.user_id = $('#user_id').val();
                }
            },
        });
        $(document).on('click', '.epost', function(){
            var id = $(this).data('id');
            $('#user_id').val(id);
            $('#epost_modal').modal('toggle');
            eposttable.ajax.reload();
        });

        var mposttable = $('#mposttable').DataTable({
            "processing": true,
            //"serverSide": true,
//            "responsive": true,
            "scrollY":        "300px",
            "scrollCollapse": true,
            "paging":         false,
            "ordering": false,
            "ajax" : {  
                url : "<?=  base_url('users/mpostByUserDatatable');?>",  
                type : "POST",
                data : function(d) {
                    d.user_id = $('#user_id').val();
                }
            },
        });
        $(document).on('click', '.mpost', function(){
            var id = $(this).data('id');
            $('#user_id').val(id);
            $('#mpost_modal').modal('toggle');
            mposttable.ajax.reload();
        });

        var wposttable = $('#wposttable').DataTable({
            "processing": true,
            //"serverSide": true,
//            "responsive": true,
            "scrollY":        "300px",
            "scrollCollapse": true,
            "paging":         false,
            "ordering": false,
            "ajax" : {  
                url : "<?=  base_url('users/wpostByUserDatatable');?>",  
                type : "POST",
                data : function(d) {
                    d.user_id = $('#user_id').val();
                }
            },
        });
        $(document).on('click', '.wpost', function(){
            var id = $(this).data('id');
            $('#user_id').val(id);
            $('#wpost_modal').modal('toggle');
            wposttable.ajax.reload();
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
                                location.reload();
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
                                location.reload();
                            }   	
                        });
                    },
                    cancel: function () {

                    }
                }
            });
        });

        <?php if($delete_status){ ?>
                $(document).delegate('.delete','click',function(){
                    var id = $(this).data('id');
                    request_url = '<?php echo base_url();?>users/deleteUser';
                    $.confirm({
                        title: 'Do you Want to delete All Content of this User!',
                        //content: 'Simple confirm!',
                        buttons: {
                            confirm: function () {
                                $.ajax({
                                    url: request_url,
                                    type: "POST",
                                    data: {id:id, is_delete_user : '0'},
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
        <?php } ?>
    });
</script>
<?php $this->load->view('elements/footer'); ?>
