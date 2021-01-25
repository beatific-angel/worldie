<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends CI_Controller {
    /**
	 * __construct()
	 * Event __construct
	 */

    public function __construct(){
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->helper(array('url','language'));
		$this->lang->load('auth');
		$this->load->model('event_model','events');
	}

	public function index(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
        
		$this->data['title']   = 'All Event';
		$this->load->view('all-content/event_list', $this->data);	
	}

	public function event_list(){
        if(!$_SERVER['HTTP_REFERER']) redirect(base_url());

        $res = $this->events->eventList();
        $data = array();
        $no = $_POST['start'];
            
        foreach ($res as $r){
            $no++;
            $row = array();
            $row[] = $no.'.';
            $row[] = ($r->data_f_name.' '.$r->data_l_name);
            $row[] = ($r->data_planing);
            $row[] = date('Y-m-d', strtotime($r->data_created_at));

            $row[] = ($r->status == 1) ? '<span class="btn btn-success btn-xs reported_item_status" current_status="'.$r->status.'" item_id="'.$r->data_item_id.'" item_type="Event" type="button">Active</span>' : '<span class="btn btn-warning btn-xs reported_item_status" current_status="'.$r->status.'" item_id="'.$r->data_item_id.'" item_type="Event" type="button">In-Active</span>';
        
            $row[] = '<span class="btn btn-warning btn-xs view_reported" type="button" item_id="'.$r->data_item_id.'" item_type="Event">View</span>

                    <span style="display:none;" class="btn btn-info btn-xs" type="button" item_id="'.$r->data_item_id.'" item_type="Event">Edit</span>

					<span class="btn btn-danger btn-xs delete_event" title="Delete Event" id="delete_event_'.$r->data_item_id.'" data-id="'.$r->data_item_id.'">Delete</span>

					<span class="btn btn-info btn-xs" title="Comment View" id="view_event_comments_'.$r->data_item_id.'" onClick="eventCommentsViewById('.$r->data_item_id.')"> Comments</span>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($res),
            "recordsFiltered" => $this->events->count_event_list(),
            "data" => $data,
        );
        echo json_encode($output);
    }
	
	/**
	 * deleteEventComment()
	 * User Delete his Evemt Comment
	 */
	 
	public function deleteEventComment(){
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
				if($this->events->deleteEvent($this->input->post('reportedcontent_id'),boolval($this->input->post('reported_post')))){
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
	public function eventCommentsView(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		$event_id = $_GET['event_id'];
		$this->data['title']   = 'All Events Comments';
		$this->data['event_detail'] = $this->events->get_single_event_details($event_id);
		$this->load->view('all-content/event_comment_list', $this->data);	
	}
}
