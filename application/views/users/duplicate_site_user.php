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
</style>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Duplicate Site Users</h3>
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
                                        <th>IP Address</th>
                                        <th>Role</th> 
                                        <th>Created date</th>
<!--                                        <th>Post</th>
                                        <th>Group Post</th>
                                        <th>Page Post</th>
                                        <th>Media Video</th>
                                        <th>Event</th>
                                        <th>Wall Art</th>-->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user){ ?>
                                        <tr>
                                            <td><?php echo $user->first_name.' '.$user->last_name;?></td>
                                            <td><?php echo $user->ip_address;?></td>
                                            <td><?php echo $user->role;?></td> 
                                            <td><?php echo date('Y-m-d', strtotime($user->created_at));?></td>
<!--                                            <td><a href="javascript:void(0);" class="btn btn-info btn-xs post" data-id="<?php echo $user->id ?>">View</a></td>
                                            <td><a href="javascript:void(0);" class="btn btn-info btn-xs gpost" data-id="<?php echo $user->id ?>">View</a></td>
                                            <td><a href="javascript:void(0);" class="btn btn-info btn-xs ppost" data-id="<?php echo $user->id ?>">View</a></td>
                                            <td><a href="javascript:void(0);" class="btn btn-info btn-xs mpost" data-id="<?php echo $user->id ?>">View</a></td>
                                            <td><a href="javascript:void(0);" class="btn btn-info btn-xs epost" data-id="<?php echo $user->id ?>">View</a></td>
                                            <td><a href="javascript:void(0);" class="btn btn-info btn-xs wpost" data-id="<?php echo $user->id ?>">View</a></td>-->
                                            <td>
                                                <?php
//                                                    if($edit_status){
                                                        if($user->is_block){
                                                            echo '<a href="javascript:void(0);" class="unblock_ip" data-ip="'.$user->ip_address.'" title="Block IP"><span class="btn btn-warning btn-xs" type="button">UnBlock IP</span></a>';
                                                        } else {
                                                            echo '<a href="javascript:void(0);" class="block_ip" data-ip="'.$user->ip_address.'" title="Block IP"><span class="btn btn-danger btn-xs" type="button">Block IP</span></a>';
                                                        }
//                                                    }
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
<!-- /page content -->
<script type="text/javascript">
$(document).ready(function() {
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
    });
</script>
<?php $this->load->view('elements/footer'); ?>
