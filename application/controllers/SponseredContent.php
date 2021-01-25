<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SponseredContent extends Base_Controller {

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
		$this->load->model('sponseredcontent_model');
		$this->load->library('ion_auth');
	} 
	
	public function index()
	{
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('index-sponseredcontent')){
			$this->session->set_flashdata('error', "You Don't Have Permission to Perform the Operation");
			redirect('users/dashboard', 'refresh');
		}
		$result = $this->sponseredcontent_model->getSponseredContent();
		
		$this->data['title']   = 'Reported Content';
		$this->data['results'] = $result;
		$this->load->view('sponseredcontent/index', $this->data);
	}
	
	public function sponsered_item()
	{
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('sponsered_item-sponseredcontent')){
			$this->session->set_flashdata('error', "You Don't Have Permission to Perform the Operation");
			redirect('users/dashboard', 'refresh');
		}
		
		$item = $this->input->get('item', TRUE);
		$result = $this->sponseredcontent_model->getSponseredItems($item);
		
		$this->data['title']   = 'Reported Content';
		$this->data['item']    = $item;
		$this->data['results'] = $result;
		$this->load->view('sponseredcontent/sponsered_item', $this->data);
	}
	
	public function delete_content(){
		
		$type = $this->input->get('type', TRUE); 
		$serviceid = $this->uri->segment(3);
		$data['servicedata'] = $this->county_model->getservicedata($serviceid);
		
		$this->load->view('category/show', $data);
		
	}
	
	public function deactivate()
	{ 
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('change_status-category')){
			
			$msg['type'] = 'error';
			$msg['text'] = "You Don't Have Permission to Perform the Operation";
			$msg = json_encode($msg);
			echo $msg; exit;
		}
		if ($this->input->post())
        {
			$result = $this->category_model->UpdateStatus($this->input->post());
            if($result){
				$msg['type'] = 'success';
				$msg['text'] = "Category Status Updated Successfully.................!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}else{
				$msg['type'] = 'error';
				$msg['text'] = "Category Could not be Updated Pls. Try Again..!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}
        }
	}
	
	public function activate()
	{ 
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('change_status-category')){
			
			$msg['type'] = 'error';
			$msg['text'] = "You Don't Have Permission to Perform the Operation";
			$msg = json_encode($msg);
			echo $msg; exit;
		}
		if ($this->input->post())
        {
			$result = $this->category_model->UpdateStatus($this->input->post());
            if($result){
				$msg['type'] = 'success';
				$msg['text'] = "Category Status Updated Successfully.................!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}else{
				$msg['type'] = 'error';
				$msg['text'] = "Category Could not be Updated Pls. Try Again..!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}
        }
	}
	
}
