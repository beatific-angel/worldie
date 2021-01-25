<?php //die('comming to controller');
defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends CI_Controller {

	/**
	 * __construct()
	 * User __construct
	 */

    public function __construct(){
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->helper(array('url','language'));
		$this->lang->load('auth');
		$this->load->model('media_model','media');
	}

	public function index(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
        
		$this->data['title']   = 'All Media Video';
		$this->load->view('all-content/media_list', $this->data);	
	}

	public function media_list(){
        if(!$_SERVER['HTTP_REFERER']) redirect(base_url());

        $res = $this->media->mediaList();
        $data = array();
        $no = $_POST['start'];
            
        foreach ($res as $r){
            $no++;
            $row = array();
            $row[] = $no.'.';
            $row[] = ($r->data_f_name.' '.$r->data_l_name);
            $row[] = ($r->data_title);
            $row[] = date('Y-m-d', strtotime($r->data_created_at));

            $row[] = ($r->status == 1) ? '<span class="btn btn-success btn-xs reported_item_status" current_status="'.$r->status.'" item_id="'.$r->data_item_id.'" item_type="Media_video" type="button">Active</span>' : '<span class="btn btn-warning btn-xs reported_item_status" current_status="'.$r->status.'" item_id="'.$r->data_item_id.'" item_type="Media_video" type="button">In-Active</span>';
        
            $row[] = '<span class="btn btn-warning btn-xs view_media_video" type="button" item_id="'.$r->data_item_id.'" item_type="Media_video">View</span>

                    <span style="display:none;" class="btn btn-info btn-xs" type="button" item_id="'.$r->data_item_id.'" item_type="Post">Edit</span>

					<span class="btn btn-danger btn-xs delete_media_video" title="Delete Video" id="delete_mediavideo_'.$r->data_item_id.'" data-id="'.$r->data_item_id.'">Delete</span>

					<span class="btn btn-info btn-xs" title="Comment View" id="view_video_comments_'.$r->data_item_id.'" onClick="videoCommentsViewById('.$r->data_item_id.')"> Comments</span>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($res),
            "recordsFiltered" => $this->media->count_media_list(),
            "data" => $data,
        );
        echo json_encode($output);
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
	    	if($this->media->deleteVideo($this->input->post('reportedcontent_id'),boolval($this->input->post('reported_post')))){
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

	public function videoCommentsView(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		$video_id = $_GET['video_id'];
		$this->data['title']   = 'All Video Comments';

		$this->data['video'] = $this->media->getSingleVideoDetails($video_id);
		$this->load->view('all-content/video_comment_list', $this->data);	
	}

	public function ssDeleteComment(){
		if (!$this->ion_auth->logged_in()){
			redirect('default_controller');
		} else{
			// $userdata = $this->session->userdata();
			if ($this->input->post()){
				$result = $this->media->ssDeleteComment($this->input->post());
				if($result == 'deleted'){
					$msg['type'] = 'success';
					$msg['text'] = 'Comment Deleted Successfully';
					$returndata[]=$msg;
					echo json_encode($returndata);
				}else{
					$msg['type'] = 'error';
					$msg['text'] = 'There Is Some Error. Pls. Try Again Later.';
					$returndata[]=$msg;
					echo json_encode($returndata);
				}		
			}
		}
	}	
}