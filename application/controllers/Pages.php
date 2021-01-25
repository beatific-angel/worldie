<?php //die('comming to controller');
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

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

		$this->load->database();


		
	}

	/**
	 * deletevideo()
	 * delete Video From Media Channel
	 */

	public function page($name = null){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(page_exists($name)){
			$data['title'] = $name;
			$this->db->select('page_content');
			$this->db->from('settings');
			$this->db->where('page_name',$name);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				$row = $query->row_array();
				$page_content = $row['page_content'];
			}
			$data['content'] = $page_content;
			$this->load->view('pages/page', $data);
		}
		else{
            show_error('Direct access is not allowed');
		}
		
		
	
	}

	public function update(){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		
		if ($this->input->post()) {
			$page_name = $this->input->post('page_name');
			
			if (page_exists($page_name)) {
			$page_content = $this->input->post('page_content');
			$status = $this->input->post('status');
			// echo $page_name;
			// echo $page_content;
			// echo $status;
			// die();
			$this->db->where('page_name', $page_name);
			$query = $this->db->get('settings');
			$result = $query->row();
			if (count($result)>0) {
				$this->db->set('page_content',$page_content);
				$this->db->set('status',$status);
				$this->db->where('id', $result->id);
				$this->db->update('settings');
			}
			else{
				$array = array(
					'page_name' => $page_name,
					'page_content' => $page_content,
					'status' =>  $status,
					);

				$this->db->set($array);
				$this->db->insert('settings');
			}
				
			}
		

		}
		$this->session->set_flashdata('success', 'page updated successfully');
		
		 redirect('/pages/'.$page_name, 'refresh');
	}


	



	

}
