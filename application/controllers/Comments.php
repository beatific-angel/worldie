<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comments extends CI_Controller {
    /**
	 * __construct()
	 * User __construct
	*/
    public function __construct(){
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->helper(array('url','language'));
		$this->lang->load('auth');
		$this->load->model('Comment_model','comment');
	}
	/**
	 * postDeleted()
	 * User Delete his Post
	*/
	public function index(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
        
		$this->data['title']   = 'All Comments';
		$this->load->view('all-content/comment_list', $this->data);	
	}

	public function comment_list(){
        if(!$_SERVER['HTTP_REFERER']) redirect(base_url());
        $postData = $this->input->post();
        $module = $postData['module'];
        $res = $this->comment->getAllComments($module);
        $data = array();
        $no = 0;
            
        foreach ($res as $resData){
            foreach($resData as $r) {
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' name='comments[]' class='comments' value='".$r->type."-".$r->data_item_id."' data-ip='".$r->ip_address."' data-user_id='".$r->data_userid."'>";
            $row[] = $no.'.';
            
            $row[] = $r->type ? str_replace('_', ' ', $r->type) : '';
            $row[] = ($r->data_f_name.' '.$r->data_l_name);
            $row[] = $r->ocomment;
            $row[] = ($r->comment_id == 0) ? '' : 'reply';
            $row[] = '<span class="text-nowrap">'.date('Y-m-d', strtotime($r->data_created_at)).'</span>';
            $class = $r->type == 'Group' ? 'view_page' : 'view_reported';
            
            $row[] = $r->rcomment ? $r->rcomment : '  ';
            $row[] = $r->rf_name ? ($r->rf_name.' '.$r->rl_name) : '  ';
            $row[] = $r->original_fname ? ($r->original_fname.' '.$r->original_lname) : '  ';
            $row[] = $r->module;
            $row[] = ($r->status == 1) ? '<span class="btn btn-success btn-xs reported_item_status" current_status="'.$r->status.'" item_id="'.$r->data_item_id.'" item_type="Post" type="button">Active</span>' : '<span class="btn btn-warning btn-xs reported_item_status" current_status="'.$r->status.'" item_id="'.$r->data_item_id.'" item_type="Post" type="button">In-Active</span>';
            $html = '<a class="btn btn-warning btn-xs view_comment_detail" item_id="'.$r->data_item_id.'" item_type="'.$r->comment_type.'" href="'.base_url().'comments/viewCommentDetail?type='.$r->type.'&id='.$r->module_id.'" target="_blank">View</a>

					<span class="btn btn-danger btn-xs delete_comment" title="Delete Comment" id="delete_comment_'.$r->data_item_id.'" data-type="'.$r->comment_type.'" data-id="'.$r->data_item_id.'">Delete</span>

					<span class="btn btn-primary btn-xs '.$class.'" type="button" item_id="'.$r->module_id.'" item_type="'.$r->type.'">View Post</span>';
			if($r->is_block){
                $html .= '<a href="javascript:void(0);" class="unblock_ip" data-ip="'.$r->ip_address.'" title="Block IP"><span class="btn btn-warning btn-xs" type="button">UnBlock IP</span></a>';
            } else {
                $html .= '<a href="javascript:void(0);" class="block_ip" data-ip="'.$r->ip_address.'" title="Block IP"><span class="btn btn-danger btn-xs" type="button">Block IP</span></a>';
            }
            $html .= '<a class="user_status" status="'.$r->user_status.'" user_id="'.$r->data_userid.'">';
            if($r->user_status == 1){
			    $html .= '<span class="btn btn-success btn-xs change_status" status="1" type="button">Active User</span>';
			}else{
				$html .= '<span class="btn btn-warning btn-xs change_status" status="0" type="button">In-Active User</span>';
		    }
            $html .= '</a>';
			$row[] = $html;
           
            $data[] = $row;
        }
        }

        $output = array(
            "recordsTotal" => count($res),
            "recordsFiltered" => count($data),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function deleteComment(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
		    $id = $this->input->post('reportedcontent_id');
		    $type = $this->input->post('type');
			if($this->comment->deleteComment($id, $type)){
				$msg['type']  = 'success';
				$msg['text']  = "Comment Deleted Successfully....!";
				echo json_encode($msg);
			}else{
				$msg['type']  = 'error';
				$msg['text']  = "Comment Could Not Be Deleted. Pls. Try Again ....!";
				echo json_encode($msg);
			}
			//echo $this->load->view('elements/response_event_delete_comment', $this->data, TRUE);
		}else{
			$msg['type']  = 'error';
			$msg['text']  = "This Comment Doesnt Belong to You. You Cannot Delete it ....!";
			echo json_encode($msg);
		}		
	}

    public function deleteAllComment(){
        $postData = $this->input->post();
        $cArray = $postData['id'];
        $this->comment->deleteAllComment($cArray);
    }
    
    public function blockAllIP(){
        $postData = $this->input->post();
        $cArray = $postData['id'];
        $this->comment->blockAllIP($cArray);
    }

    public function unblockAllIP(){
        $postData = $this->input->post();
        $cArray = $postData['id'];
        $this->comment->unblockAllIP($cArray);
    }

    public function deactiveUsers(){
        $postData = $this->input->post();
        $cArray = $postData['id'];
        if($cArray){
            foreach($cArray as $id){
                $id = (int) $id;
                $this->ion_auth->deactivate($id);
            }
        }
    }

    public function activeUsers(){
        $postData = $this->input->post();
        $cArray = $postData['id'];
        if($cArray){
            foreach($cArray as $id){
                $id = (int) $id;
                $this->ion_auth->activate($id);
            }
        }
    }

    public function viewCommentDetail(){
        $type = $_GET['type'];
        $id = $_GET['id'];
        if($type != '' && $id != ''){
            $this->config->load('user_permission', TRUE);
		    $ctype = $this->config->item('content_type', 'user_permission');
            switch ($type) {
                case 'Event':
                    $this->load->model('event_model','events');
                    $this->data['detail'] = $this->events->get_single_event_details($id);
                    $this->data['title'] = 'Event Comments';
                    $this->data['ctype'] = $this->getKey($ctype, $type);
                    $this->data['id'] = $id;
                    break;
                case 'Group':
                    $this->data['detail'] = $this->comment->getGroupById($id);
                    $this->data['title'] = 'Group Comments';
                    $this->data['ctype'] = $this->getKey($ctype, $type);
                    $this->data['id'] = $id;
                    break;
                case 'Group_Post':
                    $this->data['detail'] = $this->comment->getGroupPostById($id);
                    $this->data['title'] = 'Group Post Comments';
                    $this->data['ctype'] = $this->getKey($ctype, $type);
                    $this->data['id'] = $id;
                    break;
                case 'Media_video':
                    $this->load->model('media_model','media');
                    $this->data['title']   = 'All Media Video Comments';
		            $res = $this->media->getSingleVideoDetails($id);
		            $this->data['detail'] = (array)$res;
		          //  print_r($this->data['detail']);exit;
		            $this->data['ctype'] = $this->getKey($ctype, $type);
		            $this->data['id'] = $id;
		            break;
                case 'Page_Post':
                    $this->data['detail'] = $this->comment->getPagePostById($id);
                    $this->data['title'] = 'Page Post Comments';
                    $this->data['ctype'] = $this->getKey($ctype, $type);
                    $this->data['id'] = $id;
                    break;
                case 'Post':
                    $this->data['detail'] = $this->comment->getPostById($id);
                    $this->data['title'] = 'Post Comments';
                    $this->data['ctype'] = $this->getKey($ctype, $type);
                    $this->data['id'] = $id;
                    break;
                case 'Store_Product':
                    $this->data['detail'] = $this->comment->getStoreProductById($id);
                    $this->data['title'] = 'Store Product Comments';
                    $this->data['ctype'] = $this->getKey($ctype, $type);
                    $this->data['id'] = $id;
                    break;
                case 'Wall_Art':
                    $this->data['detail'] = $this->comment->getWallArtById($id);
                    $this->data['title'] = 'Wall Art Comments';
                    $this->data['ctype'] = $this->getKey($ctype, $type);
                    $this->data['id'] = $id;
                    break;
            }

            $this->load->view('all-content/comment_detail', $this->data);
        }
    }
    
    function getKey($array, $val){
        foreach($array as $ak => $av){
            if($av == $val){
                return $ak;
            }
        }
    }

    public function ssDeleteComment(){
        $postData = $this->input->post();
        $element_id = $postData['element_id'];
        $id = $postData['id'];
        $type = $postData['type'];
        if($this->comment->deleteCommentPerticular($element_id, $type, $id)){
			$msg['type']  = 'success';
			$msg['text']  = "Comment Deleted Succsfully....!";
			echo json_encode($msg);
		}else{
			$msg['type']  = 'error';
			$msg['text']  = "Content Could Not Be Deleted. Pls. Try Again ....!";
			echo json_encode($msg);
		}
    }
}