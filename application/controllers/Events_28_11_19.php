<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends CI_Controller {


    /**
	 * __construct()
	 * Event __construct
	 */

    public function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->helper(array('url','language'));
		$this->lang->load('auth');
		$this->load->model('event_model','events');
	}
	
	/**
	 * deleteEventComment()
	 * User Delete his Evemt Comment
	 */
	 
	public function deleteEventComment()
	{
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
			if($this->events->deleteEventComment($this->input->post())){
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

	/**
	 * deleteEvent()
	 * User Delete his Event */
	
	public function event_delete(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		} else{
			if ($this->input->post()){
				if($this->events->deleteEvent($this->input->post('reportedcontent_id'))){
					$msg['type']  = 'success';
					$msg['text']  = "Content Deleted Succsfully....!";
					//$returndata[] = $msg;
					echo json_encode($msg);
				}else{
					$msg['type']  = 'error';
					$msg['text']  = "Content Could Not Be Deleted. Pls. Try Again ....!";
					//$returndata[] = $msg;
					echo json_encode($msg);
				}
			}else{
				$msg['type']  = 'error';
				$msg['text']  = "This Event Doesnt Belong to You. You Cannot Delete it ....!";
				//$returndata[] = $msg;
				echo json_encode($msg);
			}
		}
	}
	
}
