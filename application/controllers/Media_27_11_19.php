<?php //die('comming to controller');
defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends CI_Controller {

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

		$this->load->model('media_model','media');
	}

	/**
	 * deletevideo()
	 * delete Video From Media Channel
	 */

	public function deletevideo(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
	    	if($this->media->deleteVideo($this->input->post('reportedcontent_id'))){
				$msg['type']  = 'success';
				$msg['text']  = "Content Deleted Succsfully....!";
				echo json_encode($msg);
			}else{
				$msg['type']  = 'error';
				$msg['text']  = "Content Could Not Be Deleted. Pls. Try Again ....!";
				echo json_encode($msg);
			}
	    }
	}

	

	/**
	 * deleteVideoComment()
	 * Delete Video Comment
	 */

	public function deleteMediaVideoComment(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
			if($this->media->deleteVideoComment($this->input->post('reportedcontent_id'))){
				$msg['type']  = 'success';
				$msg['text']  = "Content Deleted Succsfully....!";
				echo json_encode($msg);
			}else{
				$msg['type']  = 'error';
				$msg['text']  = "Content Could Not Be Deleted. Pls. Try Again ....!";
				echo json_encode($msg);
			}
		}
	}

}
