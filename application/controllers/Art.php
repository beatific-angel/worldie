<?php //die('comming to controller');
defined('BASEPATH') OR exit('No direct script access allowed');

class Art extends CI_Controller {

	/**
	 * __construct()
	 * User __construct
	 */

    public function __construct(){
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->helper(array('url','language'));
		$this->lang->load('auth');
		$this->load->model('art_model','art');
		$this->load->model('common_model','common');
	}

	public function index(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
        
		$this->data['title']   = 'Wall Art';
		$this->load->view('all-content/wall_art_list', $this->data);	
	}

	public function wall_art_list(){
        if(!$_SERVER['HTTP_REFERER']) redirect(base_url());

        $res = $this->art->wallArtList();
        //die;
        $data = array();
        $no = $_POST['start'];
            
        foreach ($res as $r){
        	$wall_res = $this->common->getTableValue('art_wall', 'id', $r->wall_id);
            $no++;
            $row = array();
            $row[] = $no.'.';
            $row[] = ($r->data_f_name.' '.$r->data_l_name);
            $row[] = ($wall_res->name);
            $row[] = ($r->data_title);
            $row[] = date('Y-m-d', strtotime($r->data_created_at));
            $row[] = ($r->status == 1) ? '<span class="btn btn-success btn-xs item_status" current_status="'.$r->status.'" item_id="'.$r->data_item_id.'" item_type="Wall_Art" type="button">Active</span>' : '<span class="btn btn-warning btn-xs item_status" current_status="'.$r->status.'" item_id="'.$r->data_item_id.'" item_type="Wall_Art" type="button">In-Active</span>';
        
            $row[] = '<span class="btn btn-warning btn-xs view_reported" type="button" item_id="'.$r->data_item_id.'" item_type="Wall_Art">View</span>

                     <span style="display:none;" class="btn btn-info btn-xs" type="button" item_id="'.$r->data_item_id.'" item_type="Wall_Art">Edit</span>

					 <span class="btn btn-danger btn-xs delete_wall_art" title="Delete Post" id="delete_post_'.$r->data_item_id.'" data-id="'.$r->data_item_id.'">Delete</span>

					 <span class="btn btn-info btn-xs" title="Comment View" id="view_wallart_comments_'.$r->data_item_id.'" onClick="wallartCommentsViewById('.$r->data_item_id.')"> Comments</span>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($res),
            "recordsFiltered" => $this->art->count_wall_art_list(),
            "data" => $data,
        );
        echo json_encode($output);
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
			$result = $this->art->deleteArtWallArts($this->input->post(),boolval($this->input->post('reported_post')));
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
	
	public function wallartCommentsView(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		$wallart_id = $_GET['wallart_id'];
		$this->data['title']   = 'All Wall Art Comments';
		$this->data['art']             = $this->art->getSingleArtDetails($wallart_id);
		$this->load->view('all-content/wallart_comment_list', $this->data);	
	}
}