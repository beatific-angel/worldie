<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>
      <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Role List</h3>
                </div>
                <div class="title_right">
                    <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right">
                        <div class="input-group">
                                <a href="<?php echo base_url();?>role/add" class="btn btn-round btn-primary" type="button">Create Role</a>
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
                    <h2>Role List</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table id="datatable-post" class="table table-striped table-bordered bulk_action">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i=1;
                        foreach ($role_res as $role) {
                            if($role->id!=1 && $role->id!=5){?>
                            <tr>
                                <td class="center"><?php echo $i; ?>.</td>
                                <td><?php echo $role->name;?></td>
                                <td>
                                <?php
                                echo ($role->status==1) ? '<label class="text-success">Active</label>' : '<label class="text-danger">Inactive</label>';
                                ?>
                                </td>
                                <td class="center">
                                    <?php
                                    echo ($edit_status) ? '<a class="btn btn-info btn-sm" href="'.base_url().'role/add/'.$role->id.'">Edit</a>&nbsp;' : '&nbsp;';
                                    echo ($delete_status) ? '<a onclick="return confirm(\'Are you sure\')" class="btn btn-danger btn-sm" href="'.base_url().'role/delete/'.$role->id.'">Delete</a>&nbsp;' : '&nbsp;';
                                    ?>
                                </td>
                            </tr>
                                <?php
                                $i++;
                            }    
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