<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Controller {


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
		$this->load->model('post_model','post');
	}
	
	/**
	 * postDeleted()
	 * User Delete his Post
	 */
	 
	public function postDeleted()
	{
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
			$check = $this->post->checkUserPostOwner($this->input->post('reportedcontent_id'));
			if($check){
				$result = $this->post->deletePostContent($this->input->post('reportedcontent_id'));
				$result='saved';
				if($result=='saved'){
					$msg['type']  = 'success';
					$msg['text']  = "Content Deleted Succsfully....!";
					echo json_encode($msg);
				}else{
					$msg['type']  = 'error';
					$msg['text']  = "Content Could Not Be Deleted. Pls. Try Again ....!";
					echo json_encode($msg);
				}
			}else{
				$msg['type']  = 'error';
				$msg['text']  = "This Post Doesnt Belong to You. You Cannot Delete it ....!";
				echo json_encode($msg);
			}		
		}
	}

	/**
	 * deletePostComment()
	 * User Delete his Post Comment
	 */
	
	public function deletePostComment()
	{
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
			if($this->post->deletePostComment($this->input->post())){
				$msg['type']  = 'success';
				$msg['text']  = "Content Deleted Succsfully....!";
				echo json_encode($msg);
			}else{
				$msg['type']  = 'error';
				$msg['text']  = "Content Could Not Be Deleted. Pls. Try Again ....!";
				echo json_encode($msg);
			}
		}else{
			$msg['type']  = 'error';
			$msg['text']  = "This Post Comment Doesnt Belong to You. You Cannot Delete it ....!";
			echo json_encode($msg);
		}		
	}

	
}
