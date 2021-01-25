<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>
<?php $this->load->view('monitor/importmodal'); ?>
      <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Blocked Texts</h3>
                </div>
                <div class="title_right">
                    <div class="col-md-12 col-sm-12 col-xs-12 form-group pull-right">
                        <div class="input-group" style="float:right">
                                <a href="<?php echo base_url();?>monitor/addblock" class="btn btn-round btn-primary" type="button">Add blocking text</a>
                                <a onclick = "import_text();" class="btn btn-round btn-primary" type="button">Import</a>
                        </div>
                    </div>
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
                    <h2>Blocked Texts</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table id="datatable-block" class="table table-striped table-bordered bulk_action">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Text</th>
                                <th>Color</th>
                                <th>Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i=1;
                        foreach ($blocked_text_res as $item) {
                            ?>
                            <tr>
                                <td class="center"><?php echo $i; ?>.</td>
                                <td><?php echo $item->text;?></td>
                                <td><?php echo $item->color;?></td>
                                <td><?php echo $item->reason;?></td>                                
                                <td class="center">
                                    <?php
                                    echo ($edit_status) ? '<a class="btn btn-info btn-sm" href="'.base_url().'monitor/editblock/'.$item->id.'">Edit</a>&nbsp;' : '&nbsp;';
                                    echo ($delete_status) ? '<a onclick="return confirm(\'Are you sure\')" class="btn btn-danger btn-sm" href="'.base_url().'monitor/delblock/'.$item->id.'">Delete</a>&nbsp;' : '&nbsp;';
                                    ?>
                                </td>
                            </tr>
                                <?php
                                $i++;
                        }
                        ?>
                    </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
<?php $this->load->view('elements/footer'); ?>

<script>
    $('#datatable-block').DataTable({
        order: [[ 0, 'asc' ]],
        columnDefs: [
            { width: "300px", targets: 3 },
            { width: "200px", targets: 4 },
        ]
    });
    function import_text() {
      $('#importmodal').modal('show');      
    }
</script>