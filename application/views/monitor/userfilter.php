<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>
<?php $this->load->view('monitor/manualblockmodal'); ?>
      <!-- page content -->
        <style>
            .btn-c_orange{
                background-color: #FF5722;
                border-color: #FF5722;
                color: #fff;
            }              
            .btn-c_orange:hover{
                background-color: #f74209;
                border-color: #f74209;
                color: #fff;
            }            
            .c_span{
                color: #73879C !important;
            }
        </style>
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>User's Blocked Post</h3>
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
                    <h2>Flagged Users Detail</small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table id="datatable-filter" class="table table-striped table-bordered bulk_action">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>User</th>
                                <th>Content</th>
                                <th>IP Address</th>
                                <th>Location</th>
                                <th>Reason</th>
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
        table = $('#datatable-filter').DataTable({
        language: {
            searchPlaceholder: "Search Records"
        },
        responsive: true,
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo base_url('monitor/user_filter_list')?>",
            "type": "POST",
            "data": function ( d ) {
              return $.extend( {}, d, {
                "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash();?>",
                "userid": "<?php echo $userid ?>"
              } );
            },
            "dataType": "json",
            "dataSrc": function (jsonData) {
                return jsonData.data;
            },
            "complete": function (res) {
                $('.mblock').click(function(e){
                    var href = e.target.href;
                    $('#postModal').modal('show');
                    $('#hiddenblockhref').val(href);        
                    e.preventDefault();                    
                    return false;			
                    
                }); 
            
                $('.munblock').click(function(e){
                    if (!confirm("Do you want to unblock post really?")) {
                        e.preventDefault();
                        return false;			
                    }
                });

                $('.mdeactivate').click(function(e){
                    if (!confirm("Do you want to deactivate user really?")) {
                        e.preventDefault();
                        return false;			
                    }
                });
                
                $('.mdelete').click(function(e){
                    if (!confirm("Do you want to delete user really?")) {
                        e.preventDefault();
                        return false;			
                    }
                });

                $('.mblockip').click(function(e){
                    if (!confirm("Do you want to block ip really?")) {
                        e.preventDefault();
                        return false;			
                    }
                });
            }
        },
            
        "columnDefs": [
            {"orderable": false,},
            { "width": "400px", "targets": 2 },
        ],
        
        });                        
        
    });

</script>