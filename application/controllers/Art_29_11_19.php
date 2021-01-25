<?php //die('comming to controller');
defined('BASEPATH') OR exit('No direct script access allowed');

class Art extends CI_Controller {

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
		$this->load->model('art_model','art');
	}

	/**
	 * deleteArtComment()
	 * Delete Art Comment
	 */

	public function deleteArtComment(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
			$result = $this->art->deleteArtComment($this->input->post());
			if($result){
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
	 * deleteWallArt()
	 * Delete Wall Art
	 */

	public function deleteWallArt(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
			$result = $this->art->deleteArtWallArts($this->input->post());
			if($result){
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
