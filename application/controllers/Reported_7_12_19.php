<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reported extends Base_Controller {

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
		$this->load->model('reported_model');
		$this->load->library('ion_auth');
	} 
	
	public function index()
	{	
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('index-reportedcontent')){
			$this->session->set_flashdata('error', "You Don't Have Permission to Perform the Operation");
			redirect('users/dashboard', 'refresh');
		}
		$result = $this->reported_model->getReportedContent();
		$this->data['title']   = 'Reported Content';
		$this->data['results'] = $result;
		$this->load->view('reportedcontent/index', $this->data);
	}
	
	public function reported_item()
	{
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('reported_item-reportedcontent')){
			$this->session->set_flashdata('error', "You Don't Have Permission to Perform the Operation");
			redirect('users/dashboard', 'refresh');
		}
		
	    $item = $this->input->get('item', TRUE);
		$result = $this->reported_model->getReportedItems($item);
		//echo"<pre>";
		//print_r($result);die;
       
		$this->data['title']   = 'Reported Content';
		$this->data['item']    = $item;
		$this->data['results'] = $result;

		$this->load->view('reportedcontent/reported_item', $this->data);
	}
	
	public function item_view(){
		$item_type = $this->input->get('item_type', TRUE); 
		$item_id  = $this->input->get('item_id', TRUE); 

		$result = $this->reported_model->getReportedItemDetails($item_type, $item_id);

		$this->data['item_type'] = $item_type;
		$this->data['item_id']   = $item_id;
		$this->data['results']   = $result;

		if($item_type == 'Post'){
			$this->load->view('reportedcontent/item_view', $this->data);
		}elseif($item_type == 'Event'){
			$this->load->view('reportedcontent/item_view_event', $this->data);
		}elseif($item_type == 'Post_Comment'){
			$this->load->view('reportedcontent/item_view_postcomment', $this->data);
		}elseif($item_type == 'Event_Comment'){
			$this->load->view('reportedcontent/item_view_eventcomment', $this->data);
		}elseif($item_type == 'Image_comment'){
			$this->load->view('reportedcontent/item_view_imagecomment', $this->data);
		} elseif($item_type == 'Media_video'){
            $this->load->view('reportedcontent/item_view_mediavideo', $this->data);
		}
		elseif($item_type == 'Wall_Art'){
            $this->load->view('reportedcontent/item_view_wallart', $this->data);
		}
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
	
	public function deactivateReportedContent()
	{ 
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('change_status-reportedcontent')){
			
			$msg['type'] = 'error';
			$msg['text'] = "You Don't Have Permission to Perform the Operation";
			$msg = json_encode($msg);
			echo $msg; exit;
		}
		if ($this->input->post())
        {
			//echo"<pre>";
			//print_r($this->input->post());die;
			$result = $this->reported_model->updateReportedItemStatus($this->input->post());
            if($result){
				$msg['type'] = 'success';
				$msg['text'] = "Report Content Status Updated Successfully.................!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}else{
				$msg['type'] = 'error';
				$msg['text'] = "Report Content Status Could not be Updated Pls. Try Again..!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}
        }
	}
	
	public function activateReportedContent()
	{ 
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('change_status-reportedcontent')){
			
			$msg['type'] = 'error';
			$msg['text'] = "You Don't Have Permission to Perform the Operation";
			$msg = json_encode($msg);
			echo $msg; exit;
		}
		if ($this->input->post())
        {
			$result = $this->reported_model->updateReportedItemStatus($this->input->post());
            if($result){
				$msg['type'] = 'success';
				$msg['text'] = "Report Content Status Updated Successfully.................!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}else{
				$msg['type'] = 'error';
				$msg['text'] = "Report Content Status Could not be Updated Pls. Try Again..!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}
        }
	}

	public function sendReviewNotification()
	{ 
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('review-reportedcontent')){
			
			$msg['type'] = 'error';
			$msg['text'] = "You Don't Have Permission to Perform the Operation";
			$msg = json_encode($msg);
			echo $msg; exit;
		}
		if ($this->input->post())
        {
			$result = $this->reported_model->sendReviewNotification($this->input->post());
            if($result){
				$msg['type'] = 'success';
				$msg['text'] = "Reviewed Notification Sent Successfully.................!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}else{
				$msg['type'] = 'error';
				$msg['text'] = "Reviewed Notification Could not be Sent Pls. Try Again..!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}
        }
	}
	
	public function sendWarningToUser(){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('review-reportedcontent')){
			
			$msg['type'] = 'error';
			$msg['text'] = "You Don't Have Permission to Perform the Operation";
			$msg = json_encode($msg);
			echo $msg; exit;
		}
		if ($this->input->post())
        {
			$result = $this->reported_model->sendWarningToUser($this->input->post());
            if($result){
				$msg['type'] = 'success';
				$msg['text'] = "Warning Message Sent Successfully.................!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}else{
				$msg['type'] = 'error';
				$msg['text'] = "Warning Message Could not be Sent Pls. Try Again..!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}
        }
	}

}
