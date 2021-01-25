<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Role extends CI_Controller{
	public $role_id; public $user_id;
	public function __construct(){
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->helper(array('url','language'));
		$this->lang->load('auth');
		$this->load->model('common_model','common');
		$user_data = $this->session->userdata();
		$this->role_id = $user_data['role_id'];
		$this->user_id = $user_data['user_id'];
		$this->data['title'] = 'Admin Role';
	}
	
	protected $validation_rules = array(
        'roleAdd' => array(
            array(
                'field' => 'role_name',
                'label' => 'Role name',
                'rules' => 'trim|required|xss_clean|html_escape'
            )
        ),
		'roleUpdate' => array(
            array(
                'field' => 'role_name',
                'label' => 'Role name',
                'rules' => 'trim|required|xss_clean|html_escape'
            )
        )
    );
	
	
	/* Details */
	public function index(){
		$this->data['view_status']       = checkPermission($this->role_id,'view_status');
		if($this->data['view_status']){
            $this->data['add_status']    = checkPermission($this->role_id,'add_status');
			$this->data['edit_status']   = checkPermission($this->role_id,'edit_status');
			$this->data['delete_status'] = checkPermission($this->role_id,'delete_status');

			$this->data['role_res'] = $this->common->getAllRole();
			$this->load->view('role/list',$this->data);
		}
		else{	
			redirect(base_url().'errors');
		}
    }

    /* Add and Update */
	public function add(){
		$role_id = abs(intval($this->uri->segment(3)));

		$this->data['list'] = $this->common->getParentTab();

		if($role_id && $role_id!=1 && $role_id!=5){
			if(checkPermission($this->role_id,'edit_status')){
				if (isset($_POST['submit'])){
					try{
						$this->form_validation->set_rules($this->validation_rules['roleUpdate']);
						if(!$this->form_validation->run()){
			            	throw new Exception(validation_errors());
			            }

			            $post['name']         = $this->input->post('role_name');
			            $post['status']       = $this->input->post('status');
						$post['updated_by']   = $this->user_id;
						$post['updated_date'] = date('Y-m-d H:i:s');

						$update = $this->common->updateData('tbl_role', $post, array('id'=>$role_id));

						if(!$update){
			            	throw new Exception('Failed to update!');
			            }

						foreach($this->data['list'] as $res){
							$post_per['role_id'] = $role_id;
							$post_per['tab_id']  = $res->id;
							$post_per['view_status']   = ($this->input->post('view_'.$res->id)) ? $this->input->post('view_'.$res->id) : 0;
							$post_per['add_status']    = ($this->input->post('add_'.$res->id)) ? $this->input->post('add_'.$res->id) : 0;
							$post_per['edit_status']   = ($this->input->post('edit_'.$res->id)) ? $this->input->post('edit_'.$res->id) : 0;
							$post_per['delete_status'] = ($this->input->post('delete_'.$res->id)) ? $this->input->post('delete_'.$res->id) : 0;
							
							$exist_per = $this->common->getRowDetails('tbl_role_permission', array('role_id'=>$role_id,'tab_id'=>$res->id));

							if(!empty($exist_per)){
								$this->common->updateData('tbl_role_permission',$post_per,array('id'=>$exist_per->id));
							}
							else{
								$this->common->addData('tbl_role_permission',$post_per);
							}	
						}

						$msg = 'Role updated successfully!!';
						$url = 'role';
						redirectWithMsgSuccess($msg,$url);
			        }
			        catch(Exception $e){
			            $msg = $e->getMessage();
			            $url = 'role/add/'.$role_id;
			            redirectWithMsgFailed($msg,$url);
			        }		
				}

				$this->data['role_edit'] = $this->common->getRowDetails('tbl_role',array('id'=>$role_id));
				if(!empty($this->data['role_edit'])){
					$this->load->view('role/update',$this->data);
				}
				else{
					redirect(base_url().'role');
				}
			}
			else{
				redirect(base_url().'errors');
			}
		}
		else{
			if(checkPermission($this->role_id,'add_status')){
				if (isset($_POST['submit'])){
					try{
						$this->form_validation->set_rules($this->validation_rules['roleAdd']);
						if(!$this->form_validation->run()){
			            	throw new Exception(validation_errors());
			            }

			            $post['name']     = $this->input->post('role_name');
			            $post['status']   = $this->input->post('status');
			            $post['added_by'] = $this->user_id;
						$post['created_date'] = date('Y-m-d H:i:s');
						$role_id = $this->common->addData('tbl_role',$post);

						if(!$role_id){
			            	throw new Exception('Failed to add!');
			            }

			            foreach ($this->data['list'] as $res){
							$post_permission['role_id'] = $role_id;
							$post_permission['tab_id']  = $res->id;
							$post_permission['view_status']   = ($this->input->post('view_'.$res->id)) ? $this->input->post('view_'.$res->id) : 0;
							$post_permission['add_status']    = ($this->input->post('add_'.$res->id)) ? $this->input->post('add_'.$res->id) : 0;
							$post_permission['edit_status']   = ($this->input->post('edit_'.$res->id)) ? $this->input->post('edit_'.$res->id) : 0;
							$post_permission['delete_status'] = ($this->input->post('delete_'.$res->id)) ? $this->input->post('delete_'.$res->id) : 0;

							$this->common->addData('tbl_role_permission',$post_permission);
						}
						$msg = 'Role added successfully!!';
						$url = 'role';
						redirectWithMsgSuccess($msg,$url);
					}
					catch(Exception $e){
			            $msg = $e->getMessage();
			            $url = 'role/add';
			            redirectWithMsgFailed($msg,$url);
			        }			
				}
				$this->load->view('role/add',$this->data);
			}
			else{
				redirect(base_url().'errors');
			}
		}
	}
	
	/* Delete */
	public function delete(){
		if(checkPermission($this->role_id,'delete_status')){
			$role_id = abs(intval($this->uri->segment(3)));

			try{
            if(!$role_id || $role_id==1 || $role_id==5){
            	throw new Exception("Something went wrong!");
            }

            #chk if role is assign to user
            $chk_role = $this->common->getRowDetails('users',array('role_id'=>$role_id));

            if(!empty($chk_role)){
            	throw new Exception("Failed to delete! You need to change user role where it is assign.");
            }

            $res = $this->common->deleteData('tbl_role',array('id'=>$role_id));

            if(!$res){
            	throw new Exception("Failed to delete! You need to change user role where it is assign.");
            }

            #Now delete role permission
            $res = $this->common->deleteData('tbl_role_permission',array('role_id'=>$role_id));

            $msg = 'Role remove successfully...!';
            $url = 'role';
            redirectWithMsgSuccess($msg,$url);
        }
        catch(Exception $e){
            $msg = $e->getMessage();
            $url = 'role';
            redirectWithMsgFailed($msg,$url);
         }	
		}
		else{
			redirect(base_url().'errors');
		}		
	}
}
?>