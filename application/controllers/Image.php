<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Image extends CI_Controller {


    /**
	 * __construct()
	 * Image __construct
	 */

    public function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->helper(array('url','language'));
		$this->lang->load('auth');
		$this->load->model('imagecomment_model');
	}
	
	/**
	 * deleteImageComment()
	 * User Delete his Image Comment
	 */
	 
	public function deleteImageComment()
	{
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){

		if($this->imagecomment_model->deleteImageComment($this->input->post())){
				$msg['type']  = 'success';
				$msg['text']  = "Content Deleted Succsfully....!";
				echo json_encode($msg);
			}else{
				$msg['type']  = 'error';
				$msg['text']  = "Content Could Not Be Deleted. Pls. Try Again ....!";
				echo json_encode($msg);
			}
			//echo $this->load->view('elements/response_event_delete_comment', $this->data, TRUE);
		}else{
				$msg['type']  = 'error';
				$msg['text']  = "This Event Comment Doesnt Belong to You. You Cannot Delete it ....!";
				echo json_encode($msg);
			}		
	}

	
	
}
