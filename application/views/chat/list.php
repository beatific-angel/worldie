<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>
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
                    <h3>Messages</h3>
                </div>
            </div>

            <div class="clearfix"></div>
			<!--Display Any Error or Success Message-->
            <?php 
                    if($this->session->flashdata('success_val')){
                                $msg['msg'] = $this->session->flashdata('success_val');
                                $this->load->view('flash_messages/success', $msg);
                    }else if($this->session->flashdata('error_val')){
                                $msg['msg'] = $this->session->flashdata('error_val');
                                $this->load->view('flash_messages/error', $msg);
                        } 
            ?>
            <div class="ajax_msg"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Messages</small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
                        <thead>
                            <tr>
                                    <th>User</th>
                                    <th>User 2</th>
                                    <!-- <th>Phone</th> -->
                                    <th>Newest Message</th>
                                    <th>Newest Date</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($chatInfo as $value){ ?>
                                        <?php if($value->ufrom && $value->uto) : ?>
                                    <tr>
                                        <td><?php echo $value->ufrom;?></td>
                                        <td><?php echo $value->uto;?></td>
                                        <!-- <td><?php //echo $user->phone;?></td> -->
                                        <td><?php echo $value->message;?></td>
                                        <td style="white-space: nowrap"><?php echo date('Y-m-d', strtotime($value->message_time));?></td>
                                        <td style="white-space: nowrap"><?php echo date('Y-m-d', strtotime($value->time));?></td>
                                        <td>
                                            <?php
//                                                    if($view_status){
                                                        echo '<a href="'.base_url().'chat/messages/'.$value->id.'" class="btn btn-warning btn-xs" title="View All Messages" target="_blank">View</a>';
                                                        echo '<a href="javascript:void(0);" class="btn btn-danger btn-xs delete_chat" title="Delete Messages" data-id="'.$value->id.'">Delete</a>';
//                                                    }
                                            ?>
                                        </td>
                                    </tr>
                            <?php endif; } ?>	
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

        $(document).delegate('.delete_chat','click',function(){
            var id = $(this).data('id');
            request_url = '<?php echo base_url();?>chat/deleteChat';
            $.confirm({
                title: 'Do you Want to delete this chat!',
                //content: 'Simple confirm!',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            url: request_url,
                            type: "POST",
                            data: {id:id},
                            success:function(response){
                                location.href="<?php echo base_url('chat/index') ?>";
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
