<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Common extends CI_Controller {
    /**
	 * __construct()
	 * User __construct
	*/
    public function __construct(){
		parent::__construct();
		$this->load->library('ion_auth');
		$this->lang->load('auth');
		$this->load->model('common_model','common');
	}
	
	/**
	 * postDeleted()
	 * User Delete his Post
	*/

	public function index(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
	}

    public function update_status(){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('change_status-allcontents')){	
			$msg['type'] = 'error';
			$msg['text'] = "You Don't Have Permission to Perform the Operation";
			$msg = json_encode($msg);
			echo $msg; exit;
		}
		if ($this->input->post()){
			$result = $this->common->updateStatus($this->input->post());
            if($result){
				$msg['type'] = 'success';
				$msg['text'] = "Content Status Updated Successfully.................!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}else{
				$msg['type'] = 'error';
				$msg['text'] = "Content Status Could not be Updated Pls. Try Again..!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}
        }
	}
}