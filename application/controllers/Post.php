<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Controller {
    /**
	 * __construct()
	 * User __construct
	*/
    public function __construct(){
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
	public function index(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
        
		$this->data['title']   = 'All Post';
		$this->load->view('all-content/post_list', $this->data);	
	}

	public function post_list(){
        if(!$_SERVER['HTTP_REFERER']) redirect(base_url());

        $this->post->column_order  = array('a.id');
        $this->post->column_search = array('b.first_name', 'b.last_name', 'a.content');

        $res = $this->post->postList();
        $data = array();
        $no = $_POST['start'];
            
        foreach ($res as $r){
            $no++;
            $row = array();
            $row[] = $no.'.';
            $row[] = ($r->data_f_name.' '.$r->data_l_name);
            $row[] = $r->data_content;
            $row[] = date('Y-m-d', strtotime($r->data_created_at));
            $row[] = ($r->status == 1) ? '<span class="btn btn-success btn-xs reported_item_status" current_status="'.$r->status.'" item_id="'.$r->data_item_id.'" item_type="Post" type="button">Active</span>' : '<span class="btn btn-warning btn-xs reported_item_status" current_status="'.$r->status.'" item_id="'.$r->data_item_id.'" item_type="Post" type="button">In-Active</span>';
        
            $row[] = '<span class="btn btn-warning btn-xs view_reported" type="button" item_id="'.$r->data_item_id.'" item_type="Post">View</span>

                    <span style="display:none;" class="btn btn-info btn-xs" type="button" item_id="'.$r->data_item_id.'" item_type="Post">Edit</span>

					<span class="btn btn-danger btn-xs delete_post" title="Delete Post" id="delete_post_'.$r->data_item_id.'" data-id="'.$r->data_item_id.'">Delete</span>

					<span class="btn btn-info btn-xs" title="Comment View" id="comment_view'.$r->data_item_id.'" onclick="commentViewByPostId('.$r->data_item_id.')" > Comments</span>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($res),
            "recordsFiltered" => $this->post->count_post_list(),
            "data" => $data,
        );
        echo json_encode($output);
    }

	public function group_post(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
        
		$this->data['title']   = 'All Group Post';
		$this->load->view('all-content/group_post_list', $this->data);	
	}

	public function page_post(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
        
		$this->data['title']   = 'All Page Post';
		$this->load->view('all-content/page_post_list', $this->data);	
	}

	public function page_post_list(){
        if(!$_SERVER['HTTP_REFERER']) redirect(base_url());

        $this->post->column_order  = array('gp.id');
        $this->post->column_search =  array('b.first_name', 'b.last_name', 'gp.content', 'g.name');

        $res = $this->post->pagePostList();

        $data = array();
        $no = $_POST['start'];
            
        foreach ($res as $r){
            $no++;
            $row = array();
            $row[] = $no.'.';
            $row[] = ($r->data_f_name.' '.$r->data_l_name);
            $row[] = ($r->data_page_name);
            $row[] = ($r->data_category==1) ? 'Post' : (($r->data_category==2) ? 'Timeline' : (($r->data_category==3) ? 'Page' : (($r->data_category==4) ? 'Album' : 'NA')));
            $row[] = ($r->data_content);
            $row[] = date('Y-m-d', strtotime($r->data_created_at));
            $row[] = ($r->status == 1) ? '<span class="btn btn-success btn-xs reported_item_status" current_status="'.$r->status.'" item_id="'.$r->data_item_id.'" item_type="Page_Post" type="button">Active</span>' : '<span class="btn btn-warning btn-xs reported_item_status" current_status="'.$r->status.'" item_id="'.$r->data_item_id.'" item_type="Page_Post" type="button">In-Active</span>';
        
            $row[] = '<span class="btn btn-warning btn-xs view_reported" type="button" item_id="'.$r->data_item_id.'" item_type="Page_Post">View</span>

                    <span style="display:none;" class="btn btn-info btn-xs" type="button" item_id="'.$r->data_item_id.'" item_type="Page_Post">Edit</span>

					<span class="btn btn-danger btn-xs delete_page_post" title="Delete Post" id="delete_page_post_'.$r->data_item_id.'" data-id="'.$r->data_item_id.'">Delete</span>

					<span style="display:none;" class="btn btn-info btn-xs" title="Comment View" id="view_page_post_comments_'.$r->data_item_id.'" onclick="pagePostCommentsViewById('.$r->data_item_id.')" > Comments</span>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($res),
            "recordsFiltered" => $this->post->count_page_post_list(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function group_post_list(){
        if(!$_SERVER['HTTP_REFERER']) redirect(base_url());

        $this->post->column_order  = array('gp.id');
        $this->post->column_search =  array('b.first_name', 'b.last_name', 'gp.content', 'g.name');

        $res = $this->post->groupPostList();

        $data = array();
        $no = $_POST['start'];
            
        foreach ($res as $r){
            $no++;
            $row = array();
            $row[] = $no.'.';
            $row[] = ($r->data_f_name.' '.$r->data_l_name);
            $row[] = ($r->data_group_name);
            $row[] = ($r->data_category==1) ? 'Post' : (($r->data_category==2) ? 'Timeline' : (($r->data_category==3) ? 'Page' : (($r->data_category==4) ? 'Album' : 'NA')));
            $row[] = ($r->data_content);
            $row[] = date('Y-m-d', strtotime($r->data_created_at));
            $row[] = ($r->status == 1) ? '<span class="btn btn-success btn-xs reported_item_status" current_status="'.$r->status.'" item_id="'.$r->data_item_id.'" item_type="Group_Post" type="button">Active</span>' : '<span class="btn btn-warning btn-xs reported_item_status" current_status="'.$r->status.'" item_id="'.$r->data_item_id.'" item_type="Group_Post" type="button">In-Active</span>';
        
            $row[] = '<span class="btn btn-warning btn-xs view_reported" type="button" item_id="'.$r->data_item_id.'" item_type="Group_Post">View</span>

                    <span style="display:none;" class="btn btn-info btn-xs" type="button" item_id="'.$r->data_item_id.'" item_type="Post">Edit</span>

					<span class="btn btn-danger btn-xs delete_group_post" title="Delete Post" id="delete_group_post_'.$r->data_item_id.'" data-id="'.$r->data_item_id.'">Delete</span>

					<span class="btn btn-info btn-xs" title="Comment View" id="view_group_post_comments_'.$r->data_item_id.'" onclick="groupPostCommentsViewById('.$r->data_item_id.')" > Comments</span>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($res),
            "recordsFiltered" => $this->post->count_group_post_list(),
            "data" => $data,
        );
        echo json_encode($output);
    }
	 
	public function postDeleted(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
			$check = $this->post->checkUserPostOwner($this->input->post('reportedcontent_id'));
			if($check){
				$result = $this->post->deletePostContent($this->input->post('reportedcontent_id'),boolval($this->input->post('reported_post')));

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

	public function groupPostDeleted(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
			$result = $this->post->deleteGroupPostContent($this->input->post('reportedcontent_id'),boolval($this->input->post('reported_post')));

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
		}
	}

	public function pagePostDeleted(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
			$result = $this->post->deletePagePostContent($this->input->post('reportedcontent_id'),boolval($this->input->post('reported_post')));

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
		}
	}
	/**
	 * deletePostComment()
	 * User Delete his Post Comment
	*/
	public function deletePostComment(){
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


	public function ssDeleteComment(){
		if (!$this->ion_auth->logged_in()){
			redirect('default_controller');
		} else{
			// $userdata = $this->session->userdata();
			if ($this->input->post()){
				$result = $this->post->ssDeleteComment($this->input->post());
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

	public function commentView(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		$post_id = $_GET['post_id'];
		$this->data['title']   = 'All Post Comments';
		$this->data['post'] = $this->post->getPostById($post_id);
		$this->load->view('all-content/post_comment_list', $this->data);	
	}

	public function groupPostCommentView(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		$groupPost_id = $_GET['groupPost_id'];
		$this->data['title']   = 'All Post Comments';
		$this->data['groupPost'] = $this->post->getGroupPostCommentById($groupPost_id);
		// echo "<pre>";print_r($this->data['groupPost']);die;
		$this->load->view('all-content/group_post_comment_list', $this->data);	
	}

	public function ssGroupPostComment_delete(){
		if (!$this->ion_auth->logged_in()){
			redirect('default_controller');
		} else{
			// $userdata = $this->session->userdata();
			if ($this->input->post()){
				$result = $this->post->ssGroupPostComment_delete($this->input->post());
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