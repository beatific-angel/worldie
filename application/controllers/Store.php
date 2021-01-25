<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store extends CI_Controller {


    /**
	 * __construct()
	 * User __construct
	 */

    public function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->helper(array('url','language'));
		$this->lang->load('auth');
		$this->load->model('store_model','store');
	}
	
	/**
	 * deleteProduct()
	 * Delete Store Product
	 */

	public function deleteProduct(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
			$result = $this->store->deleteStoreProduct($this->input->post());
			
			if($result){
				$msg['type']  = 'success';
				$msg['text']  = "Product Deleted Successfully..!";
				echo json_encode($msg);
			}else{
				$msg['type']  = 'error';
				$msg['text']  = "Content Could Not Be Deleted. Pls. Try Again ....!";
				echo json_encode($msg);
			}
		}
	}
	
		/**
	 * deleteStore()
	 * delete Store
	 */

	public function deleteStore(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
		$result = $this->store->deleteStoreAndItsData($this->input->post());
			if($result){
				$msg['type']  = 'success';
				$msg['text']  = "Store Deleted Successfully..!";
				echo json_encode($msg);
			}else{
				$msg['type']  = 'error';
				$msg['text']  = "Content Could Not Be Deleted. Pls. Try Again ....!";
				echo json_encode($msg);
			}
		}
	}
	


	
	
}
