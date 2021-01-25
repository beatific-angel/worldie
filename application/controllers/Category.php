<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends Base_Controller {

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
		$this->load->model('category_model');
		$this->load->library('ion_auth');
	} 
	
	public function category_items()
	{
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		
		$type = $this->input->get('type', TRUE);
		$result = $this->category_model->getCategoryData($type);
		if($type == 'meet_date' || $type){
			$name = explode('_', $type);
			$name =  join(' ', $name);
			$this->data['title']   = ucfirst($name).' '.'Category';
		}else{
			$this->data['title']   = ucfirst($type).' '.'Category';
		}

		$this->data['type']    = $type;
		$this->data['results'] = $result;
		$this->load->view('category/category_item', $this->data);
	}
	
	public function manage_category()
	{	
		
		$type = $this->input->get('type', TRUE);
		$result = $this->category_model->getCategoryDataByOrder($type);
		
		if($type == 'meet_date' || $type){
			$name = explode('_', $type);
			$name =  join(' ', $name);
			$this->data['title']   = ucfirst($name).' '.'Category';
		}else{
			$this->data['title']   = ucfirst($type).' '.'Category';
		}
		$this->data['type']    = $type;
		$this->data['results'] = $result;
		$this->data['title'] ="Manage Category";
		$this->load->view('category/manage_category',$this->data); 
	}
	
	public function save_category_order()
	{	
		$type    = $this->input->post('type', TRUE);
		$results = $this->category_model->saveCategoryOrderData($this->input->post());
		$result = $this->category_model->getCategoryData($type);
		
		if($type == 'meet_date' || $type){
			$name = explode('_', $type);
			$name =  join(' ', $name);
			$this->data['title']   = ucfirst($name).' '.'Category';
		}else{
			$this->data['title']   = ucfirst($type).' '.'Category';
		}
		$this->session->set_flashdata('success', "Category Order Saved Successfully.................!");
	}
	
	/*public function shop_category()
	{
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('shop_category-category')){
			$this->session->set_flashdata('error', "You Don't Have Permission to Perform the Operation");
			redirect('users/dashboard', 'refresh');
		}
		$result = $this->category_model->getShopCategory();
		
		$this->data['title']   = 'Shop Category';
		$this->data['results'] = $result;
		$this->load->view('category/shop_category', $this->data);
	}
	
	public function art_category()
	{
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('art_category-category')){
			$this->session->set_flashdata('error', "You Don't Have Permission to Perform the Operation");
			redirect('users/dashboard', 'refresh');
		}
		$result = $this->category_model->getArtCategory();
		
		$this->data['title']   = 'Art Category';
		$this->data['results'] = $result;
		$this->load->view('category/art_category', $this->data);
	}
	
	public function event_category()
	{
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('event_category-category')){
			$this->session->set_flashdata('error', "You Don't Have Permission to Perform the Operation");
			redirect('users/dashboard', 'refresh');
		}
		$result = $this->category_model->getEventCategory();
		
		$this->data['title']   = 'Event Category';
		$this->data['results'] = $result;
		$this->load->view('category/event_category', $this->data);
	}
	
	public function media_category()
	{
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('media_category-category')){
			$this->session->set_flashdata('error', "You Don't Have Permission to Perform the Operation");
			redirect('users/dashboard', 'refresh');
		}
		$result = $this->category_model->getMediaCategory();
		
		$this->data['title']   = 'Media Category';
		$this->data['results'] = $result;
		$this->load->view('category/media_category', $this->data);
	}
	
	public function meet_date_category()
	{
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('media_category-category')){
			$this->session->set_flashdata('error', "You Don't Have Permission to Perform the Operation");
			redirect('users/dashboard', 'refresh');
		}
		$result = $this->category_model->getMeetDateCategory();
		
		$this->data['title']   = 'Meet Date Category';
		$this->data['results'] = $result;
		$this->load->view('category/meet_date_category', $this->data);
	}*/
	
	public function create_category(){
		
		if (!$this->ion_auth->logged_in())
        {
            redirect('users/logout', 'refresh');
        }
        
		if ($this->input->post())
        {
			$result = $this->category_model->CreateCategory($this->input->post());
            if($result){
				$this->session->set_flashdata('success', 'Category Created Successfully.................!');
				redirect('category/category_items?type='.$this->input->post('type'), 'refresh');
			}else{
				$this->session->set_flashdata('error', 'Category Could not be created Pls. Try Again..!');
				redirect('category/create_category?type='.$this->input->post('type'), 'refresh');
			}
           
        }
		
		$type = $this->input->get('type', TRUE);
		$this->data['type'] = $type;
		$this->data['title']   = 'Create' .ucfirst($type). 'Category';
		$this->load->view('category/create_category', $this->data);
		
	}
	
	public function edit_category(){
		
		if (!$this->ion_auth->logged_in())
        {
            redirect('users/logout', 'refresh');
        }
        
		if ($this->input->post())
        {
			$result = $this->category_model->UpdateCategory($this->input->post());
            if($result){
				$this->session->set_flashdata('success', 'Category Updated Successfully.................!');
				redirect('category/category_items?type='.$this->input->post('type'), 'refresh');
			}else{
				$this->session->set_flashdata('error', 'Category Could not be Updated Pls. Try Again..!');
				redirect('category/edit_category?type='.$this->input->post('type').'&id='.$this->input->post('id'), 'refresh');
			}
           
        }
		$type = $this->input->get('type', TRUE); 
		$id = $this->input->get('id', TRUE); 
		$this->data['results'] = $this->category_model->getCategoryById($id, $type);
		$this->data['type'] = $type;
		$this->data['title']   = 'Create' .ucfirst($type). 'Category';
		
		$this->load->view('category/edit_category', $this->data);
		
	}
	
	public function delete_category(){
		$type = $this->input->get('type', TRUE); 
		$id   = $this->input->get('id', TRUE);
		
		if (!$this->ion_auth->logged_in())
        {
            redirect('users/logout', 'refresh');
        }
		
		if(!$this->ion_auth->has_permission('delete_category-category')){
			$this->session->set_flashdata('success', "You Don't Have Permission For This Operation.................!");
			redirect('category/category_items?type='.$type, 'refresh');
		}
		if ($type != '' && $id != '')
        {
			$result = $this->category_model->DeleteCategory($type, $id);
            if($result){
				$this->session->set_flashdata('success', "Category Deleted Successfully.................!");
				redirect('category/category_items?type='.$type, 'refresh');
			}else{
				$this->session->set_flashdata('error', "Category Could not be Deleted Pls. Try Again..!");
				redirect('category/category_items?type='.$type, 'refresh');
			}
        }
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
