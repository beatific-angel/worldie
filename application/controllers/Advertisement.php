<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Advertisement extends Base_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	function __construct()
	{
		parent::__construct();
		$this->load->model('advertisement_model');
		$this->load->library('ion_auth');
	}
	
	public function index(){
		$this->advertisement_list();
	}
	
	public function advertisement_list()
	{
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		
		$type = $this->input->get('type', TRUE);
		
		$result = $this->advertisement_model->getAdvertisementData();
		$this->data['title']   = 'Advertisement';
		$this->data['results'] = $result;
		$this->load->view('advertisement/advertisement_list', $this->data);
	}
	
	public function delete_Advertisement(){
		$id   = $this->input->get('id', TRUE);
		
		if (!$this->ion_auth->logged_in())
        {
            redirect('users/logout', 'refresh');
        }
		
		if(!$this->ion_auth->has_permission('delete_Advertisement-advertisement')){
			$this->session->set_flashdata('success', "You Don't Have Permission For This Operation.................!");
			redirect('advertisement/advertisement_list', 'refresh');
		}
		if ($id != '')
        {
			$result = $this->advertisement_model->DeleteAdvertisement($id);
            if($result){
				$this->session->set_flashdata('success', "Advertisement Deleted Successfully.................!");
				redirect('advertisement/advertisement_list', 'refresh');
			}else{
				$this->session->set_flashdata('error', "Advertisement Could not be Deleted Pls. Try Again..!");
				redirect('advertisement/advertisement_list', 'refresh');
			}
        }
	}
	
	public function delete_Advertisement_new(){
		$id   = $this->input->post('reportedcontent_id');
		
		if (!$this->ion_auth->logged_in()){
            redirect('users/logout', 'refresh');
        }
		
		if(!$this->ion_auth->has_permission('delete_Advertisement-advertisement')){
			$msg['type']  = 'error';
			$msg['text']  = "You Don't Have Permission For This Operation ....!";
			die(json_encode($msg));
		}
		if ($id != ''){
			$result = $this->advertisement_model->DeleteAdvertisement($id);
            if($result){
				$msg['type']  = 'success';
				$msg['text']  = "Advertisement Deleted Successfully ....!";
				die(json_encode($msg));
			}else{
				$msg['type']  = 'error';
				$msg['text']  = "Advertisement Could not be Deleted Pls. Try Again..!";
				die(json_encode($msg));
			}
        }
	}
	
	public function deactivate()
	{ 
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('change_status-advertisement')){
			
			$msg['type'] = 'error';
			$msg['text'] = "You Don't Have Permission to Perform the Operation";
			$msg = json_encode($msg);
			echo $msg; exit;
		}
		if ($this->input->post())
        {
			$result = $this->advertisement_model->UpdateStatus($this->input->post());
            if($result){
				$msg['type'] = 'success';
				$msg['text'] = "Advertisement Status Updated Successfully.................!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}else{
				$msg['type'] = 'error';
				$msg['text'] = "Advertisement Could not be Updated Pls. Try Again..!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}
        }
	}

	public function advertisement_view(){
		$adv_id  = $this->input->get('adv_id', TRUE); 
		
		$result = $this->advertisement_model->getAdvertisementById($adv_id);
		//echo"<pre>";
		//print_r($result);die;
		$this->data['adv_id']   = $adv_id;
		$this->data['results']   = $result[0];
		$this->load->view('advertisement/advertisement_view', $this->data);
		
	}
	
	public function activate()
	{ 
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('change_status-advertisement')){
			
			$msg['type'] = 'error';
			$msg['text'] = "You Don't Have Permission to Perform the Operation";
			$msg = json_encode($msg);
			echo $msg; exit;
		}
		if ($this->input->post())
        {
			$result = $this->advertisement_model->UpdateStatus($this->input->post());
            if($result){
				$msg['type'] = 'success';
				$msg['text'] = "Advertisement Status Updated Successfully.................!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}else{
				$msg['type'] = 'error';
				$msg['text'] = "Advertisement Could not be Updated Pls. Try Again..!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}
        }
	}
	
}
