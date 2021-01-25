<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
 <?php $this->load->view('elements/header'); ?>
 <style type="text/css">
     table .form-control{
        width: 20px;
        box-shadow: none;
     }
 </style>
   <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Update Role</h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Update Role</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div id="infoMessage" class='alert-danger'>
                <?php
                if($this->session->flashdata('success_val')){
                    $msg['msg'] = $this->session->flashdata('success_val');
                    $this->load->view('flash_messages/success', $msg);
                }else if($this->session->flashdata('error_val')){
                    $msg['msg'] = $this->session->flashdata('error_val');
                    $this->load->view('flash_messages/error', $msg);
                }
            ?></div>
                  <div class="x_content">
                    <br />
                    <?php $attributes = array('name' => 'create_role', 'class' => 'form-horizontal form-label-left', 'id' => 'create_role');?>
                    <?php echo form_open("", $attributes);?>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                        <label class="control-label" for="first-name">Role Name<span class="required">*</span>
                        </label>
                        <div>
                            <input type="text" class="form-control" name="role_name" value="<?php echo $role_edit->name;?>" required="">
                        </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                        <label class="control-label" for="first-name">Role Status<span class="required">*</span>
                        </label>
                        <div class="">
                            <select class="form-control" name="status">
                                <option <?php echo ($role_edit->status==1) ? 'selected' : ''; ?> value="1">Active</option>
                                <option <?php echo ($role_edit->status==0) ? 'selected' : ''; ?> value="0">Inactive</option>
                            </select>
                        </div>
                       </div>
                      </div>
                      
                      <hr>
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Tab Name</th>
                                        <th>All</th>
                                        <th>
                                            <div class="i-checks">
                                                <label>
                                                    View
                                                    <input class="form-control" type="checkbox" name="all_view" id="all_view" value="" onclick="checkedAllCheckboxVertical('all_view', 'view_')" >
                                                </label>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="i-checks">
                                                <label>
                                                    Add
                                                    <input class="form-control" type="checkbox" name="all_add" id="all_add" value="" onclick="checkedAllCheckboxVertical('all_add', 'add_')" >
                                                </label>
                                            </div>
                                        </th>            
                                        <th>
                                            <div class="i-checks">
                                            <label>
                                                Edit
                                                <input class="form-control" type="checkbox" name="all_edit" id="all_edit" value="" onclick="checkedAllCheckboxVertical('all_edit', 'edit_')" >
                                            </label>
                                        </div>
                                    </th>           
                                        <th>
                                            <div class="i-checks">
                                                <label>
                                                    Delete
                                                    <input class="form-control" type="checkbox" name="all_delete" id="all_delete" value="" onclick="checkedAllCheckboxVertical('all_delete', 'delete_')" >
                                                </label>
                                            </div>
                                        </th>  
                                    </tr>
                                    </thead>
                                    <tbody>
                                            <?php
                                                if(!empty($list)){
                                                    foreach($list as $res){
                                                        $per_res = $this->common->getRowDetails('tbl_role_permission',array('role_id'=>$role_edit->id,'tab_id'=>$res->id));
                                                        ?>
                                                        <tr class="tab_list_<?php echo $res->id; ?>">        
                                                            <td><strong><?php echo $res->tab_name;?></strong></td>
                                                            <td>
                                                            <div class="i-checks">
                                                                <label>
                                                                 <input class="form-control" type="checkbox" name="all_<?php echo $res->id; ?>" id="all_<?php echo $res->id; ?>" value="1" onclick="checkedAllCheckbox(<?php echo $res->id; ?>)">
                                                                </label>
                                                             </div>
                                                            </td>
                                                            <td>
                                                                <div class="i-checks">
                                                                    <label>
                                                                     <input <?php echo (!empty($per_res) && $per_res->view_status==1) ? 'checked' : ''; ?> class="form-control" type="checkbox" name="view_<?php echo $res->id; ?>" id="view_<?php echo $res->id; ?>" value="1">
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                            <div class="i-checks">
                                                                <label>
                                                                    <input <?php echo (!empty($per_res) && $per_res->add_status==1) ? 'checked' : ''; ?> class="form-control" type="checkbox" name="add_<?php echo $res->id; ?>" id="add_<?php echo $res->id; ?>" value="1">
                                                                </label>
                                                            </div>
                                                            </td>
                                                            <td>
                                                                <div class="i-checks">
                                                                <label>
                                                                    <input <?php echo (!empty($per_res) && $per_res->edit_status==1) ? 'checked' : ''; ?> class="form-control" type="checkbox" name="edit_<?php echo $res->id; ?>" id="edit_<?php echo $res->id; ?>" value="1">
                                                                </label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="i-checks">
                                                                <label>
                                                                    <input <?php echo (!empty($per_res) && $per_res->delete_status==1) ? 'checked' : ''; ?> class="form-control" type="checkbox" name="delete_<?php echo $res->id; ?>" id="delete_<?php echo $res->id; ?>" value="1">
                                                                </label>
                                                                </div>
                                                            </td>
                                                        </tr> 
                                                        <?php
                                                    }
                                                }
                                            ?> 
                                    </tbody>
                                </table>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="submit" name="submit" id="save_user" class="btn btn-success">Update Role</button>
                        </div>
                      </div>
                    <?php echo form_close();?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- /page content -->
<?php $this->load->view('elements/footer'); ?>
 <!-- jQuery -->
    
<script type="text/javascript">
    function checkedAllCheckbox(tab_id){
        if(document.getElementById('all_'+tab_id).checked){
            $("#view_"+tab_id).iCheck('check');
            $("#add_"+tab_id).iCheck('check');
            $("#edit_"+tab_id).iCheck('check');
            $("#delete_"+tab_id).iCheck('check');
        }
        else{
            $("#view_"+tab_id).iCheck('uncheck');
            $("#add_"+tab_id).iCheck('uncheck');
            $("#edit_"+tab_id).iCheck('uncheck');
            $("#delete_"+tab_id).iCheck('uncheck');
        }

    }

    function checkedAllCheckboxVertical(main_tab, sub_tab){
        if(document.getElementById(main_tab).checked){
            <?php
            foreach ($list as $t_l){
                ?>
                $("#"+sub_tab+"<?php echo $t_l->id; ?>").iCheck('check');
                <?php
            }
            ?>
        }
        else{
            <?php
            foreach ($list as $t_l){
                ?>
                $("#"+sub_tab+"<?php echo $t_l->id; ?>").iCheck('uncheck');
                <?php
            }
            ?>
        }
    }
</script>
