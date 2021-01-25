<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Base_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->helper(array('url','language'));
        $this->load->model( array('user_model', 'common_model') );
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
// 		$this->load->model('common_model','common');
		
	}
	
	/**
	 * dashboard()
	 * Admin Dashboard
	 */

	protected $validation_rules = array(
        'siteUserUpdate' => array(
            array(
                'field' => 'first_name',
                'label' => 'First name',
                'rules' => 'trim|required|xss_clean|html_escape'
            ),
            array(
                'field' => 'last_name',
                'label' => 'Last name',
                'rules' => 'trim|required|xss_clean|html_escape'
            ),
            // array(
            //     'field' => 'phone',
            //     'label' => 'Phone',
            //     'rules' => 'trim|required|xss_clean|html_escape'
            // ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|xss_clean|html_escape|valid_email'
            )
        )
    );
	 
	public function dashboard(){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}else{
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			//$this->data['users']   = $this->ion_auth->users()->num_rows();
			$this->data['users_count']   = $this->ion_auth->users()->num_rows();
			$this->data['posts_count']   = $this->common_model->count_post_list();
			$this->data['medias_count']   = $this->common_model->count_media_list();
			$this->data['arts_count']   = $this->common_model->count_art_list();
			$this->data['walls_count']   = $this->common_model->count_wall_list();
			$this->data['event_count']   = $this->common_model->count_events_list();
			$this->data['product_count']   = $this->common_model->count_product_list();
			$this->data['store_count']   = $this->common_model->count_store_list();
			$this->data['groups_count']   = $this->common_model->count_groups_list();
			$this->data['pages_count']   = $this->common_model->count_pages_list();
			$this->data['messages_count']   = $this->common_model->count_messages_list();
			$report_content_arr = [];
			$reportContents = $this->common_model->getTotalReportContentCount();
			if(!empty($reportContents)){
				foreach($reportContents as $rcVal){
					$data = [];
					$data['totalReadReport'] = $this->common_model->getTotalReadReportContent($rcVal->content_type);
					$data['totalReportContent'] = $rcVal;
					$report_content_arr[] = $data;
				}
			}
			// echo "<pre>";print_r($report_content_arr);die;
			$this->data['report_content_stats']   = $report_content_arr;


			// foreach ($this->data['users'] as $k => $user){
			// 	$this->data['users'][$k]->roles = $this->ion_auth->get_users_roles($user->id)->result();
			// }
			$this->data['title'] = 'Dashboard';
			$this->load->view('users/dashboard', $this->data);
		}
	}
	
	/**
	 * login()
	 * Admin User Login
	 */
	
	public function login(){
		if ($this->input->post()){
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)){
				$this->session->set_flashdata('success', $this->ion_auth->messages());
				redirect('users/dashboard', 'refresh');
			}
			else{
				$this->session->set_flashdata('error', $this->ion_auth->errors());
				redirect('users/login', 'refresh'); 
			}
		}
		else{
			$this->data['message'] = $this->session->flashdata('message');
			$this->data['title'] = 'Bots Login';
			$this->load->view('users/login', $this->data);
		}
	}
	
	/**
	 * admin_user()
	 * Show Admin User Listing
	 */
	public function admin_user(){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}else{
			$this->data['users'] = $this->user_model->get_AdminUsers();
			$this->data['title'] = 'Admin Users';
			$this->load->view('users/admin_user', $this->data);
		}
	}
	
	/**
	 * site_user()
	 * Show Admin User Listing
	 */
	public function site_user(){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}else{
			$user_data = $this->session->userdata();
		    $role_id   = $user_data['role_id'];

		    $this->data['view_status']   = checkPermission($role_id,'view_status');
		    if($this->data['view_status']){
		    $this->data['add_status']    = checkPermission($role_id,'add_status');
			$this->data['edit_status']   = checkPermission($role_id,'edit_status');
			$this->data['delete_status'] = checkPermission($role_id,'delete_status');

			$this->data['users'] = $this->user_model->get_SiteUsers();
			$this->data['title'] = 'Site Users';
			$this->load->view('users/site_user', $this->data);
		}
		else{	
			redirect(base_url().'errors');
		}
		}
	}
	/** Khushali : 2020_01_06 3:49 PM **/
	    public function user_content(){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}else{
			$user_data = $this->session->userdata();
                        $role_id   = $user_data['role_id'];

		    $this->data['view_status']   = checkPermission($role_id,'view_status');
		    if($this->data['view_status']){
		    $this->data['add_status']    = checkPermission($role_id,'add_status');
			$this->data['edit_status']   = checkPermission($role_id,'edit_status');
			$this->data['delete_status'] = checkPermission($role_id,'delete_status');

			$this->data['users'] = $this->user_model->get_SiteUsers();
			$this->data['title'] = 'User Content';
			$this->load->view('users/user_content', $this->data);
		}
		else{	
			redirect(base_url().'errors');
		}
		}
	}

        public function duplicate_site_user(){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}else{
			$user_data = $this->session->userdata();
		    $role_id   = $user_data['role_id'];

		    $this->data['view_status']   = checkPermission($role_id,'view_status');
		    if($this->data['view_status']){
		    $this->data['add_status']    = checkPermission($role_id,'add_status');
			$this->data['edit_status']   = checkPermission($role_id,'edit_status');
			$this->data['delete_status'] = checkPermission($role_id,'delete_status');

			$this->data['users'] = $this->user_model->get_DuplicateSiteUsers();
//                        print_r($this->data);exit;
			$this->data['title'] = 'Duplicate Site Users';
			$this->load->view('users/duplicate_site_user', $this->data);
		}
		else{	
			redirect(base_url().'errors');
		}
		}
	}

        public function blockIP(){
            if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
            }
            if ($this->input->post()){
                $postData = $this->input->post();
                $ip = $postData['ip'];
                $user_data = $this->session->userdata();
                $user_id   = $user_data['user_id'];
                $blocked_ip_id = $this->user_model->insert_block_ip(array('ip_address' => $ip, 'created_by' => $user_id));
                if($blocked_ip_id) {
                    $this->session->set_flashdata('success', $ip.' Ip Address Blocked Successfully!');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong. Please Try Again Later.');
                }
            }
        }

        public function unblockIP(){
            if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
            }
            if ($this->input->post()){
                $postData = $this->input->post();
                $ip = $postData['ip'];
                $user_data = $this->session->userdata();
                $user_id   = $user_data['user_id'];
                $this->user_model->delete(array('ip_address' => $ip), 'blocked_ip');
                $this->session->set_flashdata('success', $ip.' Ip Address Unblocked Successfully!');
            }
        }

        public function postByUserDatatable(){
            $postData = $this->input->post();
            $id = isset($postData['user_id']) && $postData['user_id'] ? $postData['user_id'] : 0;
            $postDetail = $this->user_model->get_users_post($id);
            $data = array();
            if($postDetail){
                foreach ($postDetail as $value)
                {
                        $row = array();
                        $row[] = $value->content ? $value->content : '';
                        $row[] = $value->created_at ? '<span style="white-space:nowrap;">'.date('Y-m-d', strtotime($value->created_at)).'</span>' : '';
                        $row[] = $value->status == 1 ? '<span class="btn btn-xs  btn-success">Active</span>' : '<span class="btn btn-xs btn-warning">In-active</span>';
                        $row[] = '<span class="btn btn-warning btn-xs dview_reported" type="button" item_id="'.$value->id.'" item_type="Post">View</span>
                                <span class="btn btn-danger btn-xs delete_post" title="Delete Post" id="delete_post_'.$value->id.'" data-id="'.$value->id.'">Delete</span>';
                        $data[] = $row;
                }
            }

            $output = array(
                    "data" => $data,
                    "recordsTotal" => count($data),
                    "recordsFiltered" => count($data),
            );

            echo json_encode($output);
        }

        public function gpostByUserDatatable(){
            $postData = $this->input->post();
            $id = isset($postData['user_id']) && $postData['user_id'] ? $postData['user_id'] : 0;
            $postDetail = $this->user_model->get_users_gpost($id);
            $data = array();
            if($postDetail){
                $type = array('1'=>'Post', '2'=>'Timeline', '3'=>'Page', '4'=>'Album');
                foreach ($postDetail as $value)
                {
                        $row = array();
                        $row[] = $value->group ? $value->group : '';
                        $row[] = $value->post_type ? $type[$value->post_type] : '';
                        $row[] = $value->content ? $value->content : '';
                        $row[] = $value->created_at ? '<span style="white-space:nowrap;">'.date('Y-m-d', strtotime($value->created_at)).'</span>' : '';
                        $row[] = $value->status == 1 ? '<span class="btn btn-xs  btn-success">Active</span>' : '<span class="btn btn-xs btn-warning">In-active</span>';
                        $row[] = '<span class="btn btn-warning btn-xs dview_reported" type="button" item_id="'.$value->id.'" item_type="Group_Post">View</span>
                                <span class="btn btn-danger btn-xs delete_group_post" title="Delete Post" id="delete_group_post_'.$value->id.'" data-id="'.$value->id.'">Delete</span>';
                        $data[] = $row;
                }
            }

            $output = array(
                    "data" => $data,
                    "recordsTotal" => count($data),
                    "recordsFiltered" => count($data),
            );

            echo json_encode($output);
        }

        public function ppostByUserDatatable(){
            $postData = $this->input->post();
            $id = isset($postData['user_id']) && $postData['user_id'] ? $postData['user_id'] : 0;
            $postDetail = $this->user_model->get_users_ppost($id);
            $data = array();
            if($postDetail){
                $type = array('1'=>'Post', '2'=>'Timeline', '3'=>'Page', '4'=>'Album');
                foreach ($postDetail as $value)
                {
                        $row = array();
                        $row[] = $value->page ? $value->page : '';
                        $row[] = $value->post_type ? $type[$value->post_type] : '';
                        $row[] = $value->content ? $value->content : '';
                        $row[] = $value->created_at ? '<span style="white-space:nowrap;">'.date('Y-m-d', strtotime($value->created_at)).'</span>' : '';
                        $row[] = $value->status == 1 ? '<span class="btn btn-xs  btn-success">Active</span>' : '<span class="btn btn-xs btn-warning">In-active</span>';
                        $row[] = '<span class="btn btn-warning btn-xs dview_reported" type="button" item_id="'.$value->id.'" item_type="Page_Post">View</span>
                                <span class="btn btn-danger btn-xs delete_page_post" title="Delete Post" id="delete_page_post_'.$value->id.'" data-id="'.$value->id.'">Delete</span>';
                        $data[] = $row;
                }
            }

            $output = array(
                    "data" => $data,
                    "recordsTotal" => count($data),
                    "recordsFiltered" => count($data),
            );

            echo json_encode($output);
        }

        public function epostByUserDatatable(){
            $postData = $this->input->post();
            $id = isset($postData['user_id']) && $postData['user_id'] ? $postData['user_id'] : 0;
            $postDetail = $this->user_model->get_users_epost($id);
            $data = array();
            if($postDetail){
                $type = array('1'=>'Post', '2'=>'Timeline', '3'=>'Page', '4'=>'Album');
                foreach ($postDetail as $value)
                {
                        $row = array();
                        $row[] = $value->data_planing ? $value->data_planing : '';
                        $row[] = $value->created_at ? '<span style="white-space:nowrap;">'.date('Y-m-d', strtotime($value->created_at)).'</span>' : '';
                        $row[] = $value->status == 1 ? '<span class="btn btn-xs  btn-success">Active</span>' : '<span class="btn btn-xs btn-warning">In-active</span>';
                        $row[] = '<span class="btn btn-warning btn-xs dview_reported" type="button" item_id="'.$value->id.'" item_type="Event">View</span>
                                <span class="btn btn-danger btn-xs delete_event_post" title="Delete Post" id="delete_event_post_'.$value->id.'" data-id="'.$value->id.'">Delete</span>';
                        $data[] = $row;
                }
            }

            $output = array(
                    "data" => $data,
                    "recordsTotal" => count($data),
                    "recordsFiltered" => count($data),
            );

            echo json_encode($output);
        }

        public function mpostByUserDatatable(){
            $postData = $this->input->post();
            $id = isset($postData['user_id']) && $postData['user_id'] ? $postData['user_id'] : 0;
            $postDetail = $this->user_model->get_users_mpost($id);
            $data = array();
            if($postDetail){
                $type = array('1'=>'Post', '2'=>'Timeline', '3'=>'Page', '4'=>'Album');
                foreach ($postDetail as $value)
                {
                        $row = array();
                        $row[] = $value->title ? $value->title : '';
                        $row[] = $value->created_at ? '<span style="white-space:nowrap;">'.date('Y-m-d', strtotime($value->created_at)).'</span>' : '';
                        $row[] = $value->status == 1 ? '<span class="btn btn-xs  btn-success">Active</span>' : '<span class="btn btn-xs btn-warning">In-active</span>';
                        $row[] = '<span class="btn btn-warning btn-xs view_media_video" type="button" item_id="'.$value->id.'" item_type="Media_video">View</span>
                                <span class="btn btn-danger btn-xs delete_media_post" title="Delete Post" id="delete_media_post_'.$value->id.'" data-id="'.$value->id.'">Delete</span>';
                        $data[] = $row;
                }
            }

            $output = array(
                    "data" => $data,
                    "recordsTotal" => count($data),
                    "recordsFiltered" => count($data),
            );

            echo json_encode($output);
        }

        public function wpostByUserDatatable(){
            $postData = $this->input->post();
            $id = isset($postData['user_id']) && $postData['user_id'] ? $postData['user_id'] : 0;
            $postDetail = $this->user_model->get_users_wpost($id);
            $data = array();
            if($postDetail){
                $type = array('1'=>'Post', '2'=>'Timeline', '3'=>'Page', '4'=>'Album');
                foreach ($postDetail as $value)
                {
                        $row = array();
                        $row[] = $value->name ? $value->name : '';
                        $row[] = $value->title ? $value->title : '';
                        $row[] = $value->created_at ? '<span style="white-space:nowrap;">'.date('Y-m-d', strtotime($value->created_at)).'</span>' : '';
                        $row[] = $value->status == 1 ? '<span class="btn btn-xs  btn-success">Active</span>' : '<span class="btn btn-xs btn-warning">In-active</span>';
                        $row[] = '<span class="btn btn-warning btn-xs dview_reported" type="button" item_id="'.$value->id.'" item_type="Wall_Art">View</span>
                                    <span class="btn btn-danger btn-xs delete_wall_post" title="Delete Post" id="delete_wall_post_'.$value->id.'" data-id="'.$value->id.'">Delete</span>';
                        $data[] = $row;
                }
            }

            $output = array(
                    "data" => $data,
                    "recordsTotal" => count($data),
                    "recordsFiltered" => count($data),
            );

            echo json_encode($output);
        }

        public function postDeleted(){
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
//			$check = $this->user_model->checkUserPostOwner($this->input->post('reportedcontent_id'));
//			if($check){
                                $postId = $this->input->post('reportedcontent_id');
                                $this->user_model->delete(array('id' => $postId), 'reported_content');
                                $this->user_model->delete(array('element_id' => $postId), 'post_comment');
                                $this->user_model->delete(array('post_id' => $postId), 'post_views');
                                $this->user_model->delete(array('post_id' => $postId), 'post_like');
                                $this->user_model->delete(array('post_id' => $postId, 'content_type' => 1), 'wall_shared_post');

                                $postInfo = $this->user_model->getRowById(array('id' => $postId),'post');
                                if($postInfo->image_id != 0){
                                    $imageInfo = $this->user_model->getRowById(array('id' => $postInfo->image_id),'image');
                                    if($imageInfo){
                                        $type = mb_strtolower($imageInfo->type);
                                        $imageName = $this->config->item('parent_folder_name').'assets/member_images/post_image/'.$imageInfo->prefix.'image_'.$imageInfo->id.'.'.$type;
                                        if (file_exists($imageName)){
                                            unlink($imageName);
                                        }
                                    }
                                    $this->user_model->delete(array('id' => $postInfo->image_id),'image');
                                    $this->user_model->delete(array('image_id' => $postInfo->image_id),'image_comment');
                                    $this->user_model->delete(array('image_id' => $postInfo->image_id),'image_like');
                                    $this->user_model->delete(array('image_id' => $postInfo->image_id),'image_views');
                                }
                                if($postInfo->video_id != 0){
                                    $videoInfo = $this->user_model->getRowById(array('id' => $postInfo->video_id),'video');
                                    if($videoInfo){
                                        $type = mb_strtolower($videoInfo->type);
                                        $videoName = 'assets/member_videos/post_video/'.$videoInfo->prefix.'video_'.$videoInfo->id.'.'.$type;
                                        if (file_exists($this->config->item('parent_folder_name').$videoName)){
                                            unlink($this->config->item('parent_folder_name').$videoName);
                                        }
                                    }
                                    $this->user_model->delete(array('id' => $postInfo->video_id),'video');
                                }

                                if($postInfo->album_id != 0){
                                    $dirname = $this->config->item('parent_folder_name').'assets/member_album_items/'.$postInfo->user_id;
                                    array_map('unlink', glob("$dirname/*.*"));
                                    rmdir($dirname);
                                    $this->user_model->delete(array('id' => $postInfo->album_id),'user_albums');
                                    $this->user_model->delete(array('album_id' => $postInfo->album_id),'user_album_items');
                                }
                                $this->user_model->delete(array('id' => $postId), 'post');
//				$result = $this->post_model->deletePostContent($this->input->post('reportedcontent_id'),boolval($this->input->post('reported_post')));

				$result='saved';
				if($result=='saved'){
					$msg['type']  = 'success';
					$msg['text']  = "Post Deleted Succsfully....!";
					echo json_encode($msg);
				}else{
					$msg['type']  = 'error';
					$msg['text']  = "Content Could Not Be Deleted. Pls. Try Again ....!";
					echo json_encode($msg);
				}
//			}
//                        else{
//				$msg['type']  = 'error';
//				$msg['text']  = "This Post Doesnt Belong to You. You Cannot Delete it ....!";
//				echo json_encode($msg);
//			}		
		}
	}

        public function gpostDeleted(){
//                print_r($this->input->post());exit;
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
//			$check = $this->post_model->checkUserPostOwner($this->input->post('reportedcontent_id'));
//			if($check){
                                $postId = $this->input->post('reportedcontent_id');
                                $this->user_model->delete(array('id' => $postId), 'reported_content');
                                $this->user_model->delete(array('post_id' => $postId), 'group_post_comment');
                                $this->user_model->delete(array('post_id' => $postId), 'group_post_views');
                                $this->user_model->delete(array('post_id' => $postId), 'group_post_like');
                                $this->user_model->delete(array('post_id' => $postId, 'content_type' => 14), 'wall_shared_post');

                                $postInfo = $this->user_model->getRowById(array('id' => $postId),'group_posts');

                                if($postInfo->image_id != 0){
                                    $imageInfo = $this->user_model->getRowById(array('id' => $postInfo->image_id),'image');
                                    if($imageInfo){
                                        $type = mb_strtolower($imageInfo->type);
                                        $imageName = $this->config->item('parent_folder_name').'assets/group/post_image/'.$imageInfo->prefix.'image_'.$imageInfo->id.'.'.$type;
                                        if (file_exists($imageName)){
                                            unlink($imageName);
                                        }
                                    }
                                    $this->user_model->delete(array('id' => $postInfo->image_id),'image');
                                    $this->user_model->delete(array('image_id' => $postInfo->image_id),'image_comment');
                                    $this->user_model->delete(array('image_id' => $postInfo->image_id),'image_like');
                                    $this->user_model->delete(array('image_id' => $postInfo->image_id),'image_views');
                                }

                                if($postInfo->video_id != 0){
                                    $videoInfo = $this->user_model->getRowById(array('id' => $postInfo->video_id),'video');
                                    if($videoInfo){
                                        $type = mb_strtolower($videoInfo->type);
                                        $videoName = 'assets/group/post_video/'.$videoInfo->prefix.'video_'.$videoInfo->id.'.'.$type;
                                        if (file_exists($this->config->item('parent_folder_name').$videoName)){
                                            unlink($this->config->item('parent_folder_name').$videoName);
                                        }
                                    }
                                    $this->user_model->delete(array('id' => $postInfo->video_id),'video');
                                }

                                if($postInfo->album_id != 0){
                                    $albumInfo = $this->user_model->getAllRecords('group_album_items', array('album_id' => $postInfo->album_id), 'image_name');

                                    if($albumInfo){
                                        foreach($albumInfo as $item){
                                            if (file_exists($this->config->item('parent_folder_name').$item->image_name)){
                                                unlink($this->config->item('parent_folder_name').$item->image_name);
                                            }
                                        }
                                    }
                                    $this->user_model->delete(array('id' => $postInfo->album_id),'group_albums');
                                    $this->user_model->delete(array('album_id' => $postInfo->album_id),'group_album_items');
                                }
                                $this->user_model->delete(array('id' => $postId), 'group_posts');

				$result='saved';
				if($result=='saved'){
					$msg['type']  = 'success';
					$msg['text']  = "Post Deleted Succsfully....!";
					echo json_encode($msg);
				}else{
					$msg['type']  = 'error';
					$msg['text']  = "Content Could Not Be Deleted. Pls. Try Again ....!";
					echo json_encode($msg);
				}
//			}else{
//				$msg['type']  = 'error';
//				$msg['text']  = "This Post Doesnt Belong to You. You Cannot Delete it ....!";
//				echo json_encode($msg);
//			}		
		}
	}

        public function ppostDeleted(){
//                print_r($this->input->post());exit;
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
//			$check = $this->post_model->checkUserPostOwner($this->input->post('reportedcontent_id'));
//			if($check){
                                $postId = $this->input->post('reportedcontent_id');
                                $this->user_model->delete(array('id' => $postId), 'reported_content');
                                $this->deletepagePostData($postId);
                                $this->user_model->delete(array('post_id' => $postId, 'content_type' => 15), 'wall_shared_post');
                                $postInfo = $this->user_model->getRowById(array('id' => $postId),'page_posts');

                                if($postInfo){
                                    if($postInfo->image_id != 0){
                                        $imageInfo = $this->user_model->getRowById(array('id' => $postInfo->image_id),'image');
                                        if($imageInfo){
                                            $type = mb_strtolower($imageInfo->type);
                                            $imageName = $this->config->item('parent_folder_name').'assets/page/post_image/'.$imageInfo->prefix.'image_'.$imageInfo->id.'.'.$type;
                                            if (file_exists($imageName)){
                                                unlink($imageName);
                                            }
                                        }
                                        $this->user_model->delete(array('id' => $postInfo->image_id),'image');
                                        $this->deleteImageData($postInfo->image_id);
                                    }

                                    if($postInfo->video_id != 0){
                                        $videoInfo = $this->user_model->getRowById(array('id' => $postInfo->video_id),'video');
                                        if($videoInfo){
                                            $type = mb_strtolower($videoInfo->type);
                                            $videoName = 'assets/page/post_video/'.$videoInfo->prefix.'video_'.$videoInfo->id.'.'.$type;
                                            if (file_exists($this->config->item('parent_folder_name').$videoName)){
                                                unlink($this->config->item('parent_folder_name').$videoName);
                                            }
                                        }
                                        $this->user_model->delete(array('id' => $postInfo->video_id),'video');
                                    }

                                    if($postInfo->album_id != 0){
                                        $albumInfo = $this->user_model->getAllRecords('page_album_items', array('album_id' => $postInfo->album_id), 'image_name, item_type, item_id');

                                        if($albumInfo){
                                            foreach($albumInfo as $item){
                                                $path = $this->config->item('parent_folder_name').$item->image_name;
                                                $this->unlinkFile($path);
                                                if($item->item_type == 1){
                                                    $this->user_model->delete(array('id' => $item->item_id),'image');
                                                    $this->deleteImageData($item->item_id);
                                                } else if($item->item_type == 2){
                                                    $this->user_model->delete(array('id' => $item->video_id),'video');
                                                }
                                            }
                                        }
                                        $this->user_model->delete(array('id' => $postInfo->album_id),'page_albums');
                                        $this->user_model->delete(array('album_id' => $postInfo->album_id),'page_album_items');
                                    }
                                }
                                $this->user_model->delete(array('id' => $postId), 'page_posts');

				$result='saved';
				if($result=='saved'){
					$msg['type']  = 'success';
					$msg['text']  = "Page Post Deleted Succsfully....!";
					echo json_encode($msg);
				}else{
					$msg['type']  = 'error';
					$msg['text']  = "Content Could Not Be Deleted. Pls. Try Again ....!";
					echo json_encode($msg);
				}
//			}else{
//				$msg['type']  = 'error';
//				$msg['text']  = "This Post Doesnt Belong to You. You Cannot Delete it ....!";
//				echo json_encode($msg);
//			}		
		}
	}

        public function mpostDeleted(){
//                print_r($this->input->post());exit;
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
//			$check = $this->post_model->checkUserPostOwner($this->input->post('reportedcontent_id'));
//			if($check){
                                $postId = $this->input->post('reportedcontent_id');
                                $this->user_model->delete(array('id' => $postId), 'reported_content');
                                $this->deletemediaVideoData($postId);
                                $this->user_model->delete(array('element_id' => $postId), 'media_video_comment');
                                $this->user_model->delete(array('post_id' => $postId, 'content_type' => 3), 'wall_shared_post');
                                $postInfo = $this->user_model->getRowById(array('id' => $postId),'media_video');

                                if($postInfo){
                                    if($postInfo->video_location){
                                        $path = $this->config->item('parent_folder_name').$postInfo->video_location;
                                        $this->unlinkFile($path);
                                    }

                                    if($postInfo->image_location){
                                        $path = $this->config->item('parent_folder_name').$postInfo->image_location;
                                        $this->unlinkFile($path);
                                    }                                    
                                }
                                $this->user_model->delete(array('id' => $postId), 'media_video');

				$result='saved';
				if($result=='saved'){
					$msg['type']  = 'success';
					$msg['text']  = "Media Post Deleted Succsfully....!";
					echo json_encode($msg);
				}else{
					$msg['type']  = 'error';
					$msg['text']  = "Content Could Not Be Deleted. Pls. Try Again ....!";
					echo json_encode($msg);
				}
//			}else{
//				$msg['type']  = 'error';
//				$msg['text']  = "This Post Doesnt Belong to You. You Cannot Delete it ....!";
//				echo json_encode($msg);
//			}		
		}
	}

        public function epostDeleted(){
//                print_r($this->input->post());exit;
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
//			$check = $this->post_model->checkUserPostOwner($this->input->post('reportedcontent_id'));
//			if($check){
                                $postId = $this->input->post('reportedcontent_id');
                                $this->user_model->delete(array('id' => $postId), 'reported_content');
                                $this->deleteEventData($postId);
                                $this->user_model->delete(array('post_id' => $postId, 'content_type' => 2), 'wall_shared_post');
                                $postInfo = $this->user_model->getRowById(array('id' => $postId),'events');

                                if($postInfo){
                                    $this->user_model->delete(array('id' => $postInfo->image_id),'image');
                                    $this->deleteImageData($postInfo->image_id);
                                    if($postInfo->image_path){
                                        $path = $this->config->item('parent_folder_name').$postInfo->image_path;
                                        $this->unlinkFile($path);
                                    }                                  
                                }
                                $this->user_model->delete(array('id' => $postId), 'events');

				$result='saved';
				if($result=='saved'){
					$msg['type']  = 'success';
					$msg['text']  = "Event Post Deleted Succsfully....!";
					echo json_encode($msg);
				}else{
					$msg['type']  = 'error';
					$msg['text']  = "Content Could Not Be Deleted. Pls. Try Again ....!";
					echo json_encode($msg);
				}
//			}else{
//				$msg['type']  = 'error';
//				$msg['text']  = "This Post Doesnt Belong to You. You Cannot Delete it ....!";
//				echo json_encode($msg);
//			}		
		}
	}

        public function wpostDeleted(){
//                print_r($this->input->post());exit;
		if (!$this->ion_auth->logged_in()){
			redirect('/login', 'refresh');
		}
		if ($this->input->post()){
//			$check = $this->post_model->checkUserPostOwner($this->input->post('reportedcontent_id'));
//			if($check){
                                $postId = $this->input->post('reportedcontent_id');
                                $this->user_model->delete(array('id' => $postId), 'reported_content');
                                $this->deleteWallArtData($postId);
                                $this->user_model->delete(array('element_id' => $postId), 'wall_arts_comment');
                                $this->user_model->delete(array('post_id' => $postId, 'content_type' => 4), 'wall_shared_post');
                                $postInfo = $this->user_model->getRowById(array('id' => $postId),'wall_arts');

                                if($postInfo){
                                    if($postInfo->image_location){
                                        $path = $this->config->item('parent_folder_name').$postInfo->image_location;
                                        $this->unlinkFile($path);
                                    }                                  
                                }
                                $this->user_model->delete(array('id' => $postId), 'wall_arts');

				$result='saved';
				if($result=='saved'){
					$msg['type']  = 'success';
					$msg['text']  = "Wall Art Deleted Succsfully....!";
					echo json_encode($msg);
				}else{
					$msg['type']  = 'error';
					$msg['text']  = "Content Could Not Be Deleted. Pls. Try Again ....!";
					echo json_encode($msg);
				}
//			}else{
//				$msg['type']  = 'error';
//				$msg['text']  = "This Post Doesnt Belong to You. You Cannot Delete it ....!";
//				echo json_encode($msg);
//			}		
		}
	}

        public function deleteUser(){
            if (!$this->ion_auth->logged_in()){
                    redirect('/login', 'refresh');
            } else {
                $postData = $this->input->post();               
                //delete media advertisements of user
                $advertisements = $this->user_model->getAllRecords('advertisement', array('user_id' => $postData['id']), 'image_url');
                if($advertisements){
                    foreach ($advertisements as $item) {
                        if(isset($item->image_url) && $item->image_url){
                            $path = $this->config->item('parent_folder_name').$item->image_url;
                            $this->unlinkFile($path);
                        }
                    }
                }

                //delete media of art_wall of user
                $artwalls = $this->user_model->getAllRecords('art_wall', array('user_id' => $postData['id']), 'banner_image, display_image');
                if($artwalls){
                    foreach ($artwalls as $item) {
                        if(isset($item->banner_image) && $item->banner_image){
                            $path = $this->config->item('parent_folder_name').$item->banner_image;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->display_image) && $item->display_image){
                            $path = $this->config->item('parent_folder_name').$item->display_image;
                            $this->unlinkFile($path);
                        }
                    }
                }

                //delete media of events of user
                $events = $this->user_model->getAllRecords('events', array('user_id' => $postData['id']), 'id, image_id, image_path');
                if($events){
                    foreach ($events as $item) {
                        if(isset($item->image_path) && $item->image_path){
                            $path = $this->config->item('parent_folder_name').$item->image_path;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->image_id) && $item->image_id){
                            $this->user_model->delete(array('id' => $item->image_id),'image');
                            $this->deleteImageData($item->image_id);
                        }
                        if(isset($item->id) && $item->id){
                            $this->user_model->delete(array('element_id' => $item->id),'event_comment');
                            $this->user_model->delete(array('post_id' => $item->id, 'content_type' => 2), 'wall_shared_post');
                            $this->deleteEventData($item->id);
                        }
                    }
                }

                //delete media of groups of user
                $groups = $this->user_model->getAllRecords('groups', array('user_id' => $postData['id']), 'id, banner_image, display_image');
                if($groups){
                    foreach ($groups as $item) {
                        if(isset($item->banner_image) && $item->banner_image){
                            $path = $this->config->item('parent_folder_name').$item->banner_image;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->display_image) && $item->display_image){
                            $path = $this->config->item('parent_folder_name').$item->display_image;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->id) && $item->id){
                            $groupAlbums = $this->user_model->getAllRecords('group_album_items', array('group_id' => $item->id), 'image_name');
                            if($groupAlbums){
                                foreach($groupAlbums as $gitem){
                                    $dirname = $this->config->item('parent_folder_name').$gitem->image_name;
                                    $this->unlinkFile($dirname);
                                }
                            }

                            $groupPosts = $this->user_model->getAllRecords('group_posts', array('user_id_written_on' => $item->id), 'id, image_id, video_id, album_id');
                            if($groupPosts){
                                foreach($groupPosts as $gpitem){
                                    if($gpitem->image_id != 0){
                                        $imageInfo = $this->user_model->getRowById(array('id' => $gpitem->image_id),'image');
                                        if($imageInfo){
                                            $type = mb_strtolower($imageInfo->type);
                                            $imageName = $this->config->item('parent_folder_name').'assets/group/post_image/'.$imageInfo->prefix.'image_'.$imageInfo->id.'.'.$type;
                                            $this->unlinkFile($imageName);
                                        }
                                        $this->user_model->delete(array('id' => $gpitem->image_id),'image');
                                        $this->deleteImageData($gpitem->image_id);
                                    }

                                    if($gpitem->video_id != 0){
                                        $videoInfo = $this->user_model->getRowById(array('id' => $gpitem->video_id),'video');
                                        if($videoInfo){
                                            $type = mb_strtolower($videoInfo->type);
                                            $videoName = 'assets/group/post_video/'.$videoInfo->prefix.'video_'.$videoInfo->id.'.'.$type;
                                            $path = $this->config->item('parent_folder_name').$videoName;
                                            $this->unlinkFile($path);
                                        }
                                        $this->user_model->delete(array('id' => $gpitem->video_id),'video');
                                    }
                                    $this->deletegroupPostData($gpitem->id);
                                    $this->user_model->delete(array('post_id' => $gpitem->id, 'content_type' => 14), 'wall_shared_post');
                                }
                            }
                            $this->user_model->delete(array('user_id_written_on' => $item->id),'group_posts');
                            $this->deleteGroupData($item->id);
                        }
                    }
                }

                //delete media of groupposts of user
                $groupPosts = $this->user_model->getAllRecords('group_posts', array('user_id' => $postData['id']), 'id, image_id, video_id');
                if($groupPosts){
                    foreach ($groupPosts as $item) {
                        $this->deletegroupPostData($item->id);
                        $this->user_model->delete(array('post_id' => $item->id, 'content_type' => 14), 'wall_shared_post');

                        if($item->image_id != 0){
                            $imageInfo = $this->user_model->getRowById(array('id' => $item->image_id),'image');
                            if($imageInfo){
                                $type = mb_strtolower($imageInfo->type);
                                $imageName = $this->config->item('parent_folder_name').'assets/group/post_image/'.$imageInfo->prefix.'image_'.$imageInfo->id.'.'.$type;
                                $this->unlinkFile($imageName);
                            }
                            $this->user_model->delete(array('id' => $item->image_id),'image');
                            $this->deleteImageData($item->image_id);
                        }

                        if($item->video_id != 0){
                            $videoInfo = $this->user_model->getRowById(array('id' => $item->video_id),'video');
                            if($videoInfo){
                                $type = mb_strtolower($videoInfo->type);
                                $videoName = 'assets/group/post_video/'.$videoInfo->prefix.'video_'.$videoInfo->id.'.'.$type;
                                $path = $this->config->item('parent_folder_name').$videoName;
                                $this->unlinkFile($path);
                            }
                            $this->user_model->delete(array('id' => $item->video_id),'video');
                        }
                    }
                }

                //delete media of media_channel of user
                $mediaChannels = $this->user_model->getAllRecords('media_channel', array('user_id' => $postData['id']), 'id, banner_image, display_image');
                if($mediaChannels){
                    foreach ($mediaChannels as $item) {
                        if(isset($item->banner_image) && $item->banner_image){
                            $path = $this->config->item('parent_folder_name').$item->banner_image;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->display_image) && $item->display_image){
                            $path = $this->config->item('parent_folder_name').$item->display_image;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->id) && $item->id){
                            $this->deleteMediaChannelData($item->id);
                        }
                    }
                }

                //delete media of media_video of user
                $mediaVieos = $this->user_model->getAllRecords('media_video', array('user_id' => $postData['id']), 'id, image_location, video_location');
                if($mediaVieos){
                    foreach ($mediaVieos as $item) {
                        if(isset($item->image_location) && $item->image_location){
                            $path = $this->config->item('parent_folder_name').$item->image_location;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->video_location) && $item->video_location){
                            $path = $this->config->item('parent_folder_name').$item->video_location;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->id) && $item->id){
                            $this->user_model->delete(array('element_id' => $item->id),'media_video_comment');
                            $this->user_model->delete(array('element_id' => $item->id), 'media_video_comment');
                            $this->user_model->delete(array('post_id' => $item->id, 'content_type' => 3), 'wall_shared_post');
                            $this->deletemediaVideoData($item->id);
                        }
                    }
                }

                //delete media of page of user
                $pages = $this->user_model->getAllRecords('pages', array('user_id' => $postData['id']), 'id, banner_image, display_image');
                if($pages){
                    foreach ($pages as $item) {
                        if(isset($item->banner_image) && $item->banner_image){
                            $path = $this->config->item('parent_folder_name').$item->banner_image;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->display_image) && $item->display_image){
                            $path = $this->config->item('parent_folder_name').$item->display_image;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->id) && $item->id){
                            $this->deletePagesData($item->id);

                            $pageAlbums = $this->user_model->getAllRecords('page_album_items', array('page_id' => $item->id), 'image_name');
                            if($pageAlbums){
                                foreach($pageAlbums as $gitem){
                                    $dirname = $this->config->item('parent_folder_name').$gitem->image_name;
                                    $this->unlinkFile($dirname);
                                }
                            }

                            $pagePosts = $this->user_model->getAllRecords('page_posts', array('user_id_written_on' => $item->id), 'id, image_id, video_id, album_id');
                            if($pagePosts){
                                foreach($pagePosts as $gpitem){
                                    $this->user_model->delete(array('post_id' => $gpitem->id, 'content_type' => 15), 'wall_shared_post');
                                    if($gpitem->image_id != 0){
                                        $imageInfo = $this->user_model->getRowById(array('id' => $gpitem->image_id),'image');
                                        if($imageInfo){
                                            $type = mb_strtolower($imageInfo->type);
                                            $imageName = $this->config->item('parent_folder_name').'assets/page/post_image/'.$imageInfo->prefix.'image_'.$imageInfo->id.'.'.$type;
                                            $this->unlinkFile($imageName);
                                        }
                                        $this->user_model->delete(array('id' => $gpitem->image_id),'image');
                                        $this->deleteImageData($gpitem->image_id);
                                    }

                                    if($gpitem->video_id != 0){
                                        $videoInfo = $this->user_model->getRowById(array('id' => $gpitem->video_id),'video');
                                        if($videoInfo){
                                            $type = mb_strtolower($videoInfo->type);
                                            $videoName = 'assets/page/post_video/'.$videoInfo->prefix.'video_'.$videoInfo->id.'.'.$type;
                                            $path = $this->config->item('parent_folder_name').$videoName;
                                            $this->unlinkFile($path);
                                        }
                                        $this->user_model->delete(array('id' => $gpitem->video_id),'video');
                                    }
                                    $this->deletepagePostData($gpitem->id);
                                }
                            }
                        }
                    }
                }

                //delete media of page_posts of user
                $pagePosts = $this->user_model->getAllRecords('page_posts', array('user_id' => $postData['id']), 'id, image_id, video_id');
                if($pagePosts){
                    foreach ($pagePosts as $item) {
                        $this->deletepagePostData($item->id);

                        if($item->image_id != 0){
                            $imageInfo = $this->user_model->getRowById(array('id' => $item->image_id),'image');
                            if($imageInfo){
                                $type = mb_strtolower($imageInfo->type);
                                $imageName = $this->config->item('parent_folder_name').'assets/page/post_image/'.$imageInfo->prefix.'image_'.$imageInfo->id.'.'.$type;
                                $this->unlinkFile($imageName);
                            }
                            $this->user_model->delete(array('id' => $item->image_id),'image');
                            $this->deleteImageData($item->image_id);
                        }

                        if($item->video_id != 0){
                            $videoInfo = $this->user_model->getRowById(array('id' => $item->video_id),'video');
                            if($videoInfo){
                                $type = mb_strtolower($videoInfo->type);
                                $videoName = 'assets/page/post_video/'.$videoInfo->prefix.'video_'.$videoInfo->id.'.'.$type;
                                $path = $this->config->item('parent_folder_name').$videoName;
                                $this->unlinkFile($path);
                            }
                            $this->user_model->delete(array('id' => $item->video_id),'video');
                        }
                    }
                }

                //delete media of post of user
                $posts = $this->user_model->getAllRecords('post', array('user_id' => $postData['id']), 'id, image_id, video_id, album_id');
                if($posts){
                    foreach ($posts as $item) {
                        $this->deletePostData($item->id);

                        if($item->image_id != 0){
                            $imageInfo = $this->user_model->getRowById(array('id' => $item->image_id),'image');
                            if($imageInfo){
                                $type = mb_strtolower($imageInfo->type);
                                $imageName = $this->config->item('parent_folder_name').'assets/member_images/post_image/'.$imageInfo->prefix.'image_'.$imageInfo->id.'.'.$type;
                                $this->unlinkFile($imageName);
                            }
                            $this->user_model->delete(array('id' => $item->image_id),'image');
                            $this->deleteImageData($item->image_id);
                        }
                        if($item->video_id != 0){
                            $videoInfo = $this->user_model->getRowById(array('id' => $item->video_id),'video');
                            if($videoInfo){
                                $type = mb_strtolower($videoInfo->type);
                                $videoName = 'assets/member_videos/post_video/'.$videoInfo->prefix.'video_'.$videoInfo->id.'.'.$type;
                                $path = $this->config->item('parent_folder_name').$videoName;
                                $this->unlinkFile($path);
                            }
                            $this->user_model->delete(array('id' => $item->video_id),'video');
                        }

                        if($item->id){
                            $this->user_model->delete(array('post_id' => $item->id, 'content_type' => 1), 'wall_shared_post');
                            $this->user_model->delete(array('element_id' => $item->id), 'post_comment');
                        }
                    }
                }

                //delete media of store of user
                $stores = $this->user_model->getAllRecords('store', array('user_id' => $postData['id']), 'id, slider_image1, slider_image2, slider_image3');
                if($stores){
                    foreach ($stores as $item) {
                        if(isset($item->slider_image1) && $item->slider_image1){
                            $path = $this->config->item('parent_folder_name').$item->slider_image1;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->slider_image1) && $item->slider_image2){
                            $path = $this->config->item('parent_folder_name').$item->slider_image2;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->slider_image3) && $item->slider_image3){
                            $path = $this->config->item('parent_folder_name').$item->slider_image3;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->id) && $item->id){
//                            $this->user_model->delete(array('element_id' => $item->id),'media_video_comment');
                            $this->deleteStoreData($item->id);
                        }
                    }
                }

                //delete media of store_product_images of user
                $storeProductImages = $this->user_model->getAllRecords('store_product_images', array('user_id' => $postData['id']), 'id, image_url');
                if($storeProductImages){
                    foreach ($storeProductImages as $item) {
                        if(isset($item->image_url) && $item->image_url){
                            $path = $this->config->item('parent_folder_name').$item->image_url;
                            $this->unlinkFile($path);
                        }
                    }
                }

                //delete media of users_profile of user
                $profileImages = $this->user_model->getAllRecords('users_profile', array('user_id' => $postData['id']), 'id, image, thumb_image, cover_image_id, cover_crop_image');
                if($profileImages){
                    foreach ($profileImages as $item) {
                        if(isset($item->image) && $item->image){
                            $path = $this->config->item('parent_folder_name').$item->image;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->thumb_image) && $item->thumb_image){
                            $path = $this->config->item('parent_folder_name').$item->thumb_image;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->cover_image_id) && $item->cover_image_id){
                            $path = $this->config->item('parent_folder_name').$item->cover_image_id;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->cover_crop_image) && $item->cover_crop_image){
                            $path = $this->config->item('parent_folder_name').$item->cover_crop_image;
                            $this->unlinkFile($path);
                        }
                    }
                }

                //delete media of user_album_items of user
                $albumImages = $this->user_model->getAllRecords('user_album_items', array('user_id' => $postData['id']), 'id, image_name');
                if($albumImages){
                    foreach ($albumImages as $item) {
                        if(isset($item->image_name) && $item->image_name){
                            $path = $this->config->item('parent_folder_name').$item->image_name;
                            $this->unlinkFile($path);
                        }
                    }
                }

                //delete media of wall_arts of user
                $wallAlbumImages = $this->user_model->getAllRecords('wall_arts', array('user_id' => $postData['id']), 'id, image_location');
                if($wallAlbumImages){
                    foreach ($wallAlbumImages as $item) {
                        if(isset($item->image_location) && $item->image_location){
                            $path = $this->config->item('parent_folder_name').$item->image_location;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->id) && $item->id){
                            $this->user_model->delete(array('element_id' => $item->id), 'wall_arts_comment');
                            $this->deleteWallArtData($item->id);
                        }
                    }
                }

                // delete all table data where user_id column
                $tableNames = array('advertisement', 'advertisement_click', 'advertisement_like', 'art_wall', 'art_wall_favourite', 'art_wall_join', 'art_wall_liked', 'art_wall_views','blocked_user_friend_contact_request',
                                    'donations', 'events', 'events_contact', 'event_comment', 'event_like', 'event_views', 'groups_favourite', 'group_comment', 'group_like', 'group_posts',
                                    'group_post_comment', 'group_post_like', 'group_post_views', 'group_users', 'image_comment', 'image_like', 'image_views', 'media_channel',
                                    'media_channel_join', 'media_channel_subscriber', 'media_channel_views', 'media_favourite_channel', 'media_liked_channel', 'media_user_play_list', 'media_user_play_list_item',
                                    'media_video', 'media_video_comment', 'media_video_like', 'media_video_views', 'meet_dates', 'pages', 'pages_favourite', 'pages_following', 'pages_like', 'pages_users', 'pages_user_play_list',
                                    'pages_user_play_list_item', 'page_posts', 'page_post_comment', 'page_post_like', 'page_post_views', 'page_views', 'post', 'post_comment', 'post_like', 'post_views', 'reported_content',
                                    'store', 'store_favourite', 'store_join', 'store_liked', 'store_products', 'store_product_attributes_options', 'store_product_comment', 'store_product_images', 'store_product_like', 'store_product_views', 'store_views',
                                    'store_wishlist', 'users_chat_msgs', 'users_page', 'users_page_comment', 'users_page_likes', 'users_profile', 'user_albums', 'user_album_items', 'user_contacts', 'user_contact_request', 'user_events_join',
                                    'user_fans', 'user_following', 'user_friends', 'user_media_playlist', 'user_privacy_setting', 'user_profile_invite', 'user_profile_likes', 'user_profile_views', 'wall_arts', 'wall_arts_comment', 'wall_arts_views',
                                    'wall_art_like');
                foreach($tableNames as $key => $value){
                    $this->user_model->delete(array('user_id' => $postData["id"]), $value);
                }

                //delete blocked_user_friend_contact_request data
                $this->user_model->delete(array('blocked_request_user_id' => $postData["id"]), 'blocked_user_friend_contact_request');

                //delete event_invited_users data
                $this->user_model->delete(array('invited_by' => $postData["id"]), 'event_invited_users');
                $this->user_model->delete(array('invited_user' => $postData["id"]), 'event_invited_users');

                //delete group_invites data
                $this->user_model->delete(array('invited_by' => $postData["id"]), 'group_invites');
                $this->user_model->delete(array('invited_user' => $postData["id"]), 'group_invites');

                //delete media_channel_invited data
                $this->user_model->delete(array('invited_by' => $postData["id"]), 'media_channel_invited');
                $this->user_model->delete(array('invited_user' => $postData["id"]), 'media_channel_invited');

                //delete pages_invites data
                $this->user_model->delete(array('invited_by' => $postData["id"]), 'pages_invites');
                $this->user_model->delete(array('invited_user' => $postData["id"]), 'pages_invites');

                //delete store_invited data
                $this->user_model->delete(array('invited_by' => $postData["id"]), 'store_invited');
                $this->user_model->delete(array('invited_user' => $postData["id"]), 'store_invited');

                $this->user_model->delete(array('fan_id' => $postData["id"]), 'user_fans');
                $this->user_model->delete(array('contact_id' => $postData["id"]), 'user_contacts');
                $this->user_model->delete(array('contact_id' => $postData["id"]), 'user_contact_request');
                $this->user_model->delete(array('follow_id' => $postData["id"]), 'user_following');
                $this->user_model->delete(array('friend_id' => $postData["id"]), 'user_friends');
                $this->user_model->delete(array('invited_user_id' => $postData["id"]), 'user_profile_invite');
                $this->user_model->delete(array('liked_by_id' => $postData["id"]), 'user_profile_likes');

                //delete user_notification data
                $this->user_model->delete(array('notification_by' => $postData["id"]), 'user_notification');
                $this->user_model->delete(array('notification_to' => $postData["id"]), 'user_notification');

                //delete wall_shared_post data
                $this->user_model->delete(array('shared_by' => $postData["id"]), 'wall_shared_post');
                $this->user_model->delete(array('shared_on' => $postData["id"]), 'wall_shared_post');

                //delete user_chat data
//                $this->user_model->delete(array('user_one' => $postData["id"]), 'user_chat');
//                  $this->user_model->delete(array('user_two' => $postData["id"]), 'user_chat');

                //delete users
                if($postData['is_delete_user'] == 1){
                    $this->user_model->delete(array('id' => $postData["id"]), 'users');
                }
                $this->session->set_flashdata('success_val', 'User Deleted Successfully!');
            }
        }

        public function deleteImageData($imageId){
            if($imageId){
                $tableNames = array('image_comment', 'image_like', 'image_views');
                foreach($tableNames as $key => $value){
                    $this->user_model->delete(array('image_id' => $imageId), $value);
                }
            }
        }

        public function deleteEventData($eventId){
            if($eventId){
                $tableNames = array('events_contact', 'event_invited_users', 'event_like', 'event_views', 'user_events_join');
                foreach($tableNames as $key => $value){
                    $this->user_model->delete(array('event_id' => $eventId), $value);
                }
            }
        }

        public function deleteGroupData($groupId){
            if($groupId){
                $tableNames = array('groups_favourite', 'group_albums', 'group_album_items', 'group_comment', 'group_invites', 'group_like', 'group_meeting', 'group_rules', 'group_users');
                foreach($tableNames as $key => $value){
                    $this->user_model->delete(array('group_id' => $groupId), $value);
                }
            }
        }

        public function deletegroupPostData($postId){
            if($postId){
                $tableNames = array('group_post_comment', 'group_post_views', 'group_post_like');
                foreach($tableNames as $key => $value){
                    $this->user_model->delete(array('post_id' => $postId), $value);
                }
            }
        }

        public function deleteMediaChannelData($channelId){
            if($channelId){
                $tableNames = array('media_channel_invited', 'media_channel_join', 'media_channel_subscriber', 'media_channel_views', 'media_favourite_channel', 'media_liked_channel');
                foreach($tableNames as $key => $value){
                    $this->user_model->delete(array('channel_id' => $channelId), $value);
                }
            }
        }

        public function deletePagesData($pageId){
            if($pageId){
                $tableNames = array('pages_favourite', 'pages_following', 'pages_invites', 'pages_like', 'pages_users', 'pages_user_play_list', 'page_albums', 'page_album_items', 'page_views');
                foreach($tableNames as $key => $value){
                    $this->user_model->delete(array('page_id' => $pageId), $value);
                }
            }
        }

        public function deletepagePostData($postId){
            if($postId){
                $tableNames = array('page_post_comment', 'page_post_like', 'page_post_views');
                foreach($tableNames as $key => $value){
                    $this->user_model->delete(array('post_id' => $postId), $value);
                }
            }
        }

        public function deletemediaVideoData($videoId){
            if($videoId){
                $tableNames = array('media_video_views', 'media_video_like', 'media_user_play_list_item');
                foreach($tableNames as $key => $value){
                    $this->user_model->delete(array('video_id' => $videoId), $value);
                }
            }
        }

        public function deletePostData($postId) {
            if($postId){
                $tableNames = array('post_like','post_views');
                foreach($tableNames as $key => $value){
                    $this->user_model->delete(array('post_id' => $postId), $value);
                }
            }
        }

        public function deleteStoreData($Id) {
            if($Id){
                $tableNames = array('store_favourite', 'store_invited', 'store_join', 'store_liked', 'store_products', 'store_views');
                foreach($tableNames as $key => $value){
                    $this->user_model->delete(array('store_id' => $Id), $value);
                }
            }
        }

        public function deleteWallArtData($id){
            if($id){
                $tableNames = array('wall_arts_views', 'wall_art_like');
                foreach($tableNames as $key => $value){
                    $this->user_model->delete(array('art_id' => $id), $value);
                }
            }
        }
        public function unlinkFile($path){
            if (file_exists($path)){
                unlink($path);
            }
        }
        public function user_profile($userId = ''){
            if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}else{
                    $user_data = $this->session->userdata();
		    $role_id   = $user_data['role_id'];
                    $this->data['user_id'] = $userId;
		    $this->data['view_status']   = checkPermission($role_id,'view_status');
		    if($this->data['view_status']){
                        $this->data['groups'] = $this->user_model->getFromSql(
                                'SELECT g.id , g.name, gu.id as guId, gu.user_id as userId, g.user_id as createdBy
                                    FROM groups as g
                                    LEFT JOIN group_users as gu ON gu.group_id = g.id
                                    WHERE gu.user_id = "'.$userId.'"
                                ');
                        $this->data['pages'] = $this->user_model->getFromSql(
                                'SELECT p.id , p.name, pu.id as puId, pu.user_id as userId, p.user_id as createdBy
                                    FROM pages as p
                                    LEFT JOIN pages_users as pu ON pu.page_id = p.id
                                    WHERE pu.user_id = "'.$userId.'"
                                ');
                        $this->data['channels'] = $this->user_model->getFromSql(
                                'SELECT m.id , m.channel_name as name, mu.id as muId, mu.user_id as userId, m.user_id as createdBy
                                    FROM media_channel as m
                                    LEFT JOIN media_channel_join as mu ON mu.channel_id = m.id
                                    WHERE mu.user_id = "'.$userId.'" OR m.user_id = "'.$userId.'"
                                ');
                        $this->data['walls'] = $this->user_model->getFromSql(
                                'SELECT a.id , a.name, au.id as auId, au.user_id as userId, a.user_id as createdBy
                                    FROM art_wall as a
                                    LEFT JOIN art_wall_join as au ON au.wall_id = a.id
                                    WHERE a.user_id = "'.$userId.'" OR au.user_id = "'.$userId.'"
                                ');
                        $this->data['stores'] = $this->user_model->getFromSql(
                                'SELECT s.id , s.name, su.id as suId, su.user_id as userId, s.user_id as createdBy
                                    FROM store as s
                                    LEFT JOIN store_join as su ON su.store_id = s.id
                                    WHERE s.user_id = "'.$userId.'" OR su.user_id = "'.$userId.'"
                                ');
                        $this->data['title'] = 'User Profile';
                        $this->load->view('users/user_profile', $this->data);
                    }
		else{	
			redirect(base_url().'errors');
		}
            }
        }

        public function item_view(){
		$item_type = $this->input->get('item_type', TRUE); 
		$item_id   = $this->input->get('item_id', TRUE); 

		$this->data['item_type'] = $item_type;
		$this->data['item_id']   = $item_id;

		if($item_type == 'wall'){
                        $this->data['results'] = $this->user_model->getFromSql(
                                'SELECT a.id , a.name, a.description, a.banner_image, a.display_image, CONCAT(u.first_name, " ", u.last_name) as created_by, a.created_at
                                    FROM art_wall as a
                                    LEFT JOIN users as u ON u.id = a.created_by
                                    WHERE a.id = "'.$item_id.'"
                                ', 'row');
                        
			$this->load->view('users/item_view/item_view_wall', $this->data);
                }
                if($item_type == 'profile_image'){
                        $this->data['results'] = $this->user_model->getFromSql(
                                'SELECT u.id , u.album_id, u.image_name
                                    FROM user_album_items as u
                                    WHERE u.user_id = "'.$item_id.'" AND u.item_type = "3"
                                ');
                        $this->data['title'] = 'Profile Photos';
			$this->load->view('users/item_view/item_view_image', $this->data);
                }
                if($item_type == 'banner_image'){
                        $this->data['results'] = $this->user_model->getFromSql(
                                'SELECT u.id , u.album_id, u.image_name
                                    FROM user_album_items as u
                                    WHERE u.user_id = "'.$item_id.'" AND u.item_type = "4"
                                ');
                        $this->data['title'] = 'Banners';
			$this->load->view('users/item_view/item_view_image', $this->data);
                }
                if($item_type == 'photo'){
                        $this->data['results'] = $this->user_model->getFromSql(
                                'SELECT u.id , u.album_id, u.image_name
                                    FROM user_album_items as u
                                    WHERE u.user_id = "'.$item_id.'" AND u.item_type = "1"
                                ');
                        $this->data['title'] = 'Photos';
			$this->load->view('users/item_view/item_view_image', $this->data);
                }
                if($item_type == 'video'){
                        $this->data['results'] = $this->user_model->getFromSql(
                                'SELECT u.id , u.album_id, u.image_name
                                    FROM user_album_items as u
                                    WHERE u.user_id = "'.$item_id.'" AND u.item_type = "2"
                                ');
                        $this->data['title'] = 'Videos';
			$this->load->view('users/item_view/item_view_image', $this->data);
                }
	}

        public function deleteUserData() {
            $postData = $this->input->post();
            $type = $postData['type'];
            if($type == 'groups'){
                $groups = $postData['groups'];
                if($groups){
                    $tGroups = count($groups);
                    for($i=0; $i<$tGroups; $i++){
                        $gInfo = explode('_', $groups[$i]);
                        $guId = $gInfo[0];
                        $gId = isset($gInfo[1]) ? $gInfo[1] : NULL;
                        $this->user_model->delete(array('id' => $guId, 'user_id' => $postData['userId']), 'group_users');
                        if($gId){
                            $item = $this->user_model->getRowById(array('id' => $gId, 'user_id' => $postData['userId']), 'groups');
                            if(isset($item->banner_image) && $item->banner_image){
                                $path = $this->config->item('parent_folder_name').$item->banner_image;
                                $this->unlinkFile($path);
                            }
                            if(isset($item->display_image) && $item->display_image){
                                $path = $this->config->item('parent_folder_name').$item->display_image;
                                $this->unlinkFile($path);
                            }
                            if(isset($item->id) && $item->id){
                                $groupAlbums = $this->user_model->getAllRecords('group_album_items', array('group_id' => $item->id), 'image_name');
                                if($groupAlbums){
                                    foreach($groupAlbums as $gitem){
                                        $dirname = $this->config->item('parent_folder_name').$gitem->image_name;
                                        $this->unlinkFile($dirname);
                                    }
                                }

                                $groupPosts = $this->user_model->getAllRecords('group_posts', array('user_id_written_on' => $item->id), 'id, image_id, video_id, album_id');
                                if($groupPosts){
                                    foreach($groupPosts as $gpitem){
                                        if($gpitem->image_id != 0){
                                            $imageInfo = $this->user_model->getRowById(array('id' => $gpitem->image_id),'image');
                                            if($imageInfo){
                                                $type = mb_strtolower($imageInfo->type);
                                                $imageName = $this->config->item('parent_folder_name').'assets/group/post_image/'.$imageInfo->prefix.'image_'.$imageInfo->id.'.'.$type;
                                                $this->unlinkFile($imageName);
                                            }
                                            $this->user_model->delete(array('id' => $gpitem->image_id),'image');
                                            $this->deleteImageData($gpitem->image_id);
                                        }

                                        if($gpitem->video_id != 0){
                                            $videoInfo = $this->user_model->getRowById(array('id' => $gpitem->video_id),'video');
                                            if($videoInfo){
                                                $type = mb_strtolower($videoInfo->type);
                                                $videoName = 'assets/group/post_video/'.$videoInfo->prefix.'video_'.$videoInfo->id.'.'.$type;
                                                $path = $this->config->item('parent_folder_name').$videoName;
                                                $this->unlinkFile($path);
                                            }
                                            $this->user_model->delete(array('id' => $gpitem->video_id),'video');
                                        }
                                        $this->deletegroupPostData($gpitem->id);
                                        $this->user_model->delete(array('post_id' => $gpitem->id, 'content_type' => 14), 'wall_shared_post');
                                    }
                                }
                                $this->user_model->delete(array('user_id_written_on' => $item->id),'group_posts');
                                $this->deleteGroupData($item->id);
                                $this->user_model->delete(array('id' => $item->id), 'groups');
                            }
                        }
                    }
                    $this->session->set_flashData('success', 'Groups Deleted Successfully!');
                }
            }
            if($type == 'pages'){
                $pages = $postData['pages'];
                if($pages){
                    $tPages = count($pages);
                    for($i=0; $i<$tPages; $i++){
                        $pInfo = explode('_', $pages[$i]);
                        $puId = $pInfo[0];
                        $pId = isset($pInfo[1]) ? $pInfo[1] : NULL;
                        $this->user_model->delete(array('id' => $puId, 'user_id' => $postData['userId']), 'pages_users');
                        $item = $this->user_model->getRowById(array('id' => $pId, 'user_id' => $postData['userId']), 'pages');
                        if(isset($item->banner_image) && $item->banner_image){
                            $path = $this->config->item('parent_folder_name').$item->banner_image;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->display_image) && $item->display_image){
                            $path = $this->config->item('parent_folder_name').$item->display_image;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->id) && $item->id){
                            $this->deletePagesData($item->id);

                            $pageAlbums = $this->user_model->getAllRecords('page_album_items', array('page_id' => $item->id), 'image_name');
                            if($pageAlbums){
                                foreach($pageAlbums as $gitem){
                                    $dirname = $this->config->item('parent_folder_name').$gitem->image_name;
                                    $this->unlinkFile($dirname);
                                }
                            }

                            $pagePosts = $this->user_model->getAllRecords('page_posts', array('user_id_written_on' => $item->id), 'id, image_id, video_id, album_id');
                            if($pagePosts){
                                foreach($pagePosts as $gpitem){
                                    $this->user_model->delete(array('post_id' => $gpitem->id, 'content_type' => 15), 'wall_shared_post');
                                    if($gpitem->image_id != 0){
                                        $imageInfo = $this->user_model->getRowById(array('id' => $gpitem->image_id),'image');
                                        if($imageInfo){
                                            $type = mb_strtolower($imageInfo->type);
                                            $imageName = $this->config->item('parent_folder_name').'assets/page/post_image/'.$imageInfo->prefix.'image_'.$imageInfo->id.'.'.$type;
                                            $this->unlinkFile($imageName);
                                        }
                                        $this->user_model->delete(array('id' => $gpitem->image_id),'image');
                                        $this->deleteImageData($gpitem->image_id);
                                    }

                                    if($gpitem->video_id != 0){
                                        $videoInfo = $this->user_model->getRowById(array('id' => $gpitem->video_id),'video');
                                        if($videoInfo){
                                            $type = mb_strtolower($videoInfo->type);
                                            $videoName = 'assets/page/post_video/'.$videoInfo->prefix.'video_'.$videoInfo->id.'.'.$type;
                                            $path = $this->config->item('parent_folder_name').$videoName;
                                            $this->unlinkFile($path);
                                        }
                                        $this->user_model->delete(array('id' => $gpitem->video_id),'video');
                                    }
                                    $this->deletepagePostData($gpitem->id);
                                }
                            }
                            $this->user_model->delete(array('id' => $item->id), 'pages');
                        }
                    }
                    $this->session->set_flashData('success', 'Pages Deleted Successfully!');
                }
            }
            if($type == 'channels'){
                $pages = $postData['channels'];
                if($pages){
                    $tPages = count($pages);
                    for($i=0; $i<$tPages; $i++){
                        $pInfo = explode('_', $pages[$i]);
                        $puId = $pInfo[0];
                        $pId = isset($pInfo[1]) ? $pInfo[1] : NULL;
                        $this->user_model->delete(array('id' => $puId, 'user_id' => $postData['userId']), 'media_channel_join');
                        $item = $this->user_model->getRowById(array('id' => $pId, 'user_id' => $postData['userId']), 'media_channel');
                        if(isset($item->banner_image) && $item->banner_image){
                            $path = $this->config->item('parent_folder_name').$item->banner_image;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->display_image) && $item->display_image){
                            $path = $this->config->item('parent_folder_name').$item->display_image;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->id) && $item->id){
                            $this->deleteMediaChannelData($item->id);
                            $this->user_model->delete(array('id' => $item->id), 'media_channel');
                        }
                    }
                    $this->session->set_flashData('success', 'Channels Deleted Successfully!');
                }
            }
            if($type == 'walls'){
                $pages = $postData['walls'];
                if($pages){
                    $tPages = count($pages);
                    for($i=0; $i<$tPages; $i++){
                        $pInfo = explode('_', $pages[$i]);
                        $puId = $pInfo[0];
                        $pId = isset($pInfo[1]) ? $pInfo[1] : NULL;
                        if($puId){
                            $this->user_model->delete(array('id' => $puId, 'user_id' => $postData['userId']), 'art_wall_join');
                        }
                        $item = $this->user_model->getRowById(array('id' => $pId, 'user_id' => $postData['userId']), 'art_wall');
                        if(isset($item->banner_image) && $item->banner_image){
                            $path = $this->config->item('parent_folder_name').$item->banner_image;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->display_image) && $item->display_image){
                            $path = $this->config->item('parent_folder_name').$item->display_image;
                            $this->unlinkFile($path);
                        }
                        if(isset($item->id) && $item->id){
                            $this->user_model->delete(array('id' => $item->id), 'art_wall');
                        }
                    }
                    $this->session->set_flashData('success', 'Walls Deleted Successfully!');
                }
            }
            if($type == 'stores'){
                $pages = $postData['stores'];
                if($pages){
                    $tPages = count($pages);
                    for($i=0; $i<$tPages; $i++){
                        $pInfo = explode('_', $pages[$i]);
                        $puId = $pInfo[0];
                        $pId = isset($pInfo[1]) ? $pInfo[1] : NULL;
                        if($puId){
                            $this->user_model->delete(array('id' => $puId, 'user_id' => $postData['userId']), 'store_join');
                        }
                        $item = $this->user_model->getRowById(array('id' => $pId, 'user_id' => $postData['userId']), 'store');
                        if($item){
                            if(isset($item->slider_image1) && $item->slider_image1){
                                $path = $this->config->item('parent_folder_name').$item->slider_image1;
                                $this->unlinkFile($path);
                            }
                            if(isset($item->slider_image1) && $item->slider_image2){
                                $path = $this->config->item('parent_folder_name').$item->slider_image2;
                                $this->unlinkFile($path);
                            }
                            if(isset($item->slider_image3) && $item->slider_image3){
                                $path = $this->config->item('parent_folder_name').$item->slider_image3;
                                $this->unlinkFile($path);
                            }
                            if(isset($item->id) && $item->id){
    //                            $this->user_model->delete(array('element_id' => $item->id),'media_video_comment');
                                $this->deleteStoreData($item->id);
                                $this->user_model->delete(array('id' => $item->id), 'store');
                            }
                        }
                    }
                    $this->session->set_flashData('success', 'Stores Deleted Successfully!');
                }
            }
            redirect('users/user_profile/'.$postData["userId"], 'refresh');
        }

        public function deleteUserImage(){
            $postData = $this->input->post();
            $userAlbumItemId = $postData['id'];
            $item = $this->user_model->getRowById(array('id' => $userAlbumItemId),'user_album_items');
            if(isset($item->image_name) && $item->image_name){
                $path = $this->config->item('parent_folder_name').$item->image_name;
                $this->unlinkFile($path);
            }
            if(isset($item->item_id) && $item->item_id){
                if($item->item_type == 4){
                    $this->user_model->delete(array('id' => $item->item_id), 'video');
                } else {
                    $this->deleteImageData($item->item_id);
                    $this->user_model->delete(array('id' => $item->item_id), 'image');
                }
            }
            $this->user_model->delete(array('id' => $userAlbumItemId), 'user_album_items');
            $this->session->set_flashData('success', $postData['title'].' Deleted Successfully!');
        }
        /** Khushali : 2020_01_06 3:49 PM **/
	
	/**
	 * logout()
	 * Admin User Login
	 */
	public function logout(){
		$this->data['title'] = "Logout";
		$logout = $this->ion_auth->logout();
		$this->session->set_flashdata('success', $this->ion_auth->messages());
		redirect('users/login', 'refresh');
	}
	
	/**
	 * change_password()
	 * Change Admin User Password
	 */
	public function change_password(){
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('users/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() == false)
		{
			if(!empty(validation_errors())){
				$this->session->set_flashdata('error', validation_errors());
			}
			//$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			/*$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name'    => 'new',
				'id'      => 'new',
				'type'    => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name'    => 'new_confirm',
				'id'      => 'new_confirm',
				'type'    => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);*/
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
			);

			// render
			$this->load->view('users/change_password', $this->data);
		}
		else
		{
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('sucsess', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('error', $this->ion_auth->errors());
				redirect('users/change_password', 'refresh');
			}
		}
	}
        /**
         * 
         */
	// forgot password
	public function forgot_password(){
		// setting validation rules by checking whether identity is username or email
		if($this->config->item('identity', 'ion_auth') != 'email' )
		{
		   $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		}
		else
		{
		   $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() == false)
		{
			$this->data['type'] = $this->config->item('identity','ion_auth');
			// setup the input
			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
			);

			if ( $this->config->item('identity', 'ion_auth') != 'email' ){
				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			// set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->load->view('users/forgot_password', $this->data); 
		}
		else
		{
			$identity_column = $this->config->item('identity','ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

			if(empty($identity)) {

	            		if($this->config->item('identity', 'ion_auth') != 'email')
		            	{
		            		$this->ion_auth->set_error('forgot_password_identity_not_found');
		            	}
		            	else
		            	{
		            	   $this->ion_auth->set_error('forgot_password_email_not_found');
		            	}

		                $this->session->set_flashdata('message', $this->ion_auth->errors());
                		redirect("users/forgot_password", 'refresh');
            		}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				// if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("users/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("users/forgot_password", 'refresh');
			}
		}
	}
        /**
         * 
         */
	// reset password - final step for forgotten password
	public function reset_password($code = NULL){
		if (!$code)
		{
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false)
			{
				// display the form

				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name'    => 'new_confirm',
					'id'      => 'new_confirm',
					'type'    => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				// render
				$this->load->view('users/reset_password', $this->data);
			}
			else
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{

					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						// if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect("users/login", 'refresh');
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('users/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("users/forgot_password", 'refresh');
		}
	}
	
    /**
	 * activate($id)
	 * Activate Admin User
	 * @Params $id int
	 */
	 
	public function activate($id, $code=false){
		if ($code !== false){
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->logged_in()){
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation){
			// redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			$msg['type'] = 'success';
			$msg['text'] = "User Status Updated Successfully.................!";
			$msg = json_encode($msg);
			echo $msg; exit;
			
			//redirect("users/admin_user", 'refresh');
		}
		else{
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("users/forgot_password", 'refresh');
		}
	}
	
	/**
	 * deactivate($id)
	 * Deactivate Admin User
	 * @Params $id int
	 */
	
	public function deactivate($id = NULL){
		if (!$this->ion_auth->logged_in()){
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}

		$id = (int) $id;
		// do we really want to deactivate?
		if ($this->input->post('confirm') == 'yes'){
			// do we have the right userlevel?
			if ($this->ion_auth->logged_in()){
				$result=$this->ion_auth->deactivate($id);
				if($result){
					$msg['type'] = 'success';
					$msg['text'] = "User Status Updated Successfully.................!";
					$msg = json_encode($msg);
					echo $msg; exit;
				}
			}
		}

		// redirect them back to the auth page
		redirect('users/admin_user', 'refresh');
		
	}
	
	/**
	 * create_user()
	 * Create new Admin User
	 */
	
	/*public function create_user(){
        

        if (!$this->ion_auth->logged_in())
        {
            redirect('users/logout', 'refresh');
        }
         
		if(!$this->ion_auth->has_permission('admin_create-users')){
			$this->session->set_flashdata('error', "You Don't Have Permission to Perform the Operation");
			redirect('users/admin_user', 'refresh');
		}



        $tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
        if($identity_column!=='email')
        {
            $this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        }
        else
        {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true)
        {
            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'role_id'    => $this->input->post('role_id'),
                'phone'      => $this->input->post('phone'),
            );
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($identity, $password, $email, $additional_data))
        {
            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message1','<div id="ajax-message" class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>Test</strong> </div>');
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("users/admin_user", 'refresh');
        }
        else
        {
            // display the create user form
            // set the flash data error message if there is one
           // $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
           
			$this->data['title'] = $this->lang->line('create_user_heading');
			
            $this->load->view('users/create_user', $this->data);
        }
    }*/

    public function create_user(){
        if (!$this->ion_auth->logged_in()){
            redirect('users/logout', 'refresh');
        }
         
		if(!$this->ion_auth->has_permission('admin_create-users')){
			$this->session->set_flashdata('error', "You Don't Have Permission to Perform the Operation");
			redirect('users/admin_user', 'refresh');
		}
		
		if ($this->input->post()){
		$tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
        if($identity_column!=='email'){
            $this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        }
        else{
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }

        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true){
            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'role_id'    => $this->input->post('role_id'),
                'phone'      => $this->input->post('phone'),
            );
        }

        if($this->input->post('role_id')==1 || $this->input->post('role_id')==5){
        	$msg = 'Can not assign super admin or members role';
            $url = 'users/create_user';
            redirectWithMsgFailed($msg,$url);
            die();
        }

		if ($this->form_validation->run() == true &&  $this->ion_auth->register($identity, $password, $email, $additional_data)){
				$this->session->set_flashdata('success_val', $this->ion_auth->messages());
				$this->data['message']=$this->ion_auth->messages();
				redirect("users/admin_user", 'refresh');
			}else{
				
				$this->data['message']=(validation_errors() ? validation_errors() : $this->ion_auth->errors());
				$this->session->set_flashdata('error_val', $this->data['message']);
				$this->data['title'] = $this->lang->line('create_user_heading');
				//$this->load->view('users/create_user', $this->data);
				redirect("users/create_user", 'refresh');
			}
		}
		else{
			$this->data['roles']   = $this->common_model->getData('tbl_role' , array('status'=>1,'id !='=>1,'id!= '=>5));
			$this->data['message'] = "";
			$this->data['title']   = $this->lang->line('create_user_heading');		
			$this->load->view('users/create_user', $this->data);
		}
	}
	
	
	/**
	 * edit_user($id)
	 * Edit Admin User
	 * @Input Parameter $id
	 *	 
	 */
	
	public function edit_user($id){
		$this->data['title'] = $this->lang->line('edit_user_heading');

		if (!$this->ion_auth->logged_in()){
			redirect('users/logout', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();

		if($user->role_id==1 || $user->role_id==5){
			redirect('users/admin_user', 'refresh');
		}
		//$roles=$this->ion_auth->roles()->result_array();
		//$currentroles = $this->ion_auth->get_users_roles($id)->result();

		#get role list
		$roles = $this->common_model->getData('tbl_role' , array('status'=>1,'id !='=>1,'id!= '=>5));

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
		//$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required');
		
		if(!empty($this->input->post('password')) && !empty($this->input->post('password_confirm'))){	
			$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
	        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');
		}

		if (isset($_POST) && !empty($_POST)){
			if ($this->form_validation->run() === TRUE){
				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name'  => $this->input->post('last_name'),
					'role_id'    => $this->input->post('role_id'),
					//'phone'      => $this->input->post('phone'),
					'email'      => $this->input->post('email'),
				);

				if(!empty($this->input->post('password')) && !empty($this->input->post('password_confirm'))){
		            $password = $this->input->post('password');
		            $data['password'] = $password;
				}

				#if role id == 1 || 5 (can not be super admin & members)
				if($this->input->post('role_id')==1 || $this->input->post('role_id')==5){
					$msg = 'Can not assign super admin or members role';
		            $url = 'users/admin_user';
		            redirectWithMsgFailed($msg,$url,'error');
		            die();
				}

				#chk email duplication
                $chk = $this->common_model->getRowDetails('users', array('id !='=>$user->id,'email'=>$this->input->post('email')));
                if(!empty($chk)){
	            	$msg = 'Email is already used for another user. Try diffrent email id!';
		            $url = 'users/admin_user';
		            redirectWithMsgFailed($msg,$url,'error');
		            die();
	            }

			// check to see if we are updating the user
			   if($this->ion_auth->update($user->id, $data)){
					redirect('users/admin_user', 'refresh');
			    }else{
			    	// redirect them back to the admin page if admin, or to the base url if non admin
				    $this->session->set_flashdata('message', $this->ion_auth->errors() );
					redirect('users/admin_user', 'refresh');
			    }
			}
			else{
				$msg = validation_errors();
	            $url = 'users/edit_user/'.$user->id;
	            redirectWithMsgFailed($msg,$url);
	            die();
			}
		}

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['user'] = $user;
		$this->data['roles'] = $roles;
		//$this->data['currentroles'] = $currentroles;
		$this->load->view('users/edit_user', $this->data);
	}
	
	/**
	 * delete_user($id)
	 * Deactivate Admin User
	 * @Params $id int
	 */
	
	public function delete_user($id = NULL){
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the home page because they must be an administrator to view this
			redirect('users/logout', 'refresh');
		}
		
		$id = (int) $id;
		// do we really want to deactivate?
		//if ($this->input->post('confirm') == 'yes')
		//{
			// do we have the right userlevel?
			if ($this->ion_auth->logged_in())
			{
				$this->ion_auth->delete($id);
				$this->user_model->removeUserFromContactFriends($id);
			}
		//}

		// redirect them back to the auth page
		redirect('users/admin_user', 'refresh');	
	}
	
	/**
	 * create_role()
	 * Create New Role For Admin Section
	 */
	
	public function create_role(){
		$this->data['title'] = $this->lang->line('create_role_title');

		if (!$this->ion_auth->logged_in())
		{
			redirect('users', 'refresh');
		}

		// validate form input
		$this->form_validation->set_rules('role_name', $this->lang->line('create_role_validation_name_label'), 'required|alpha_dash');

		if ($this->form_validation->run() == TRUE)
		{
			$new_role_id = $this->ion_auth->create_role($this->input->post('role_name'), $this->input->post('description'));
			if($new_role_id)
			{
				// check to see if we are creating the role
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("users", 'refresh');
			}
		}
		else
		{
			// display the create role form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['role_name'] = array(
				'name'  => 'role_name',
				'id'    => 'role_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('role_name'),
			);
			$this->data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);

			$this->_render_page('auth/create_role', $this->data);
		}
	}
        /**
         * 
         */
	// edit a role
	public function edit_role($id){
		// bail if no role id given
		if(!$id || empty($id))
		{
			redirect('users', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_role_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('users', 'refresh');
		}

		$role = $this->ion_auth->role($id)->row();

		// validate form input
		$this->form_validation->set_rules('role_name', $this->lang->line('edit_role_validation_name_label'), 'required|alpha_dash');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$role_update = $this->ion_auth->update_role($id, $_POST['role_name'], $_POST['role_description']);

				if($role_update)
				{
					$this->session->set_flashdata('message', $this->lang->line('edit_role_saved'));
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
				redirect("users", 'refresh');
			}
		}

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['role'] = $role;

		$readonly = $this->config->item('admin_role', 'ion_auth') === $role->name ? 'readonly' : '';

		$this->data['role_name'] = array(
			'name'    => 'role_name',
			'id'      => 'role_name',
			'type'    => 'text',
			'value'   => $this->form_validation->set_value('role_name', $role->name),
			$readonly => $readonly,
		);
		$this->data['role_description'] = array(
			'name'  => 'role_description',
			'id'    => 'role_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('role_description', $role->description),
		);

		$this->_render_page('users/edit_role', $this->data);
	}

        /**
         * 
         * @return type
         */
	public function _get_csrf_nonce(){
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}
        /**
         * 
         * @return boolean
         */
	public function _valid_csrf_nonce(){
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
        /**
         * 
         * @param type $view
         * @param type $data
         * @param type $returnhtml
         * @return type
         */
	public function _render_page($view, $data=null, $returnhtml=false){//I think this makes more sense 
		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}

	public function edit_site_user(){
		$this->data['title'] = 'Update Site Users';
		$user_data = $this->session->userdata();
		$role_id   = $user_data['role_id'];

		$user_id = abs(intval($this->uri->segment(3)));

		if($user_id){
			if(checkPermission($role_id,'edit_status')){

				$user = $this->common_model->getRowDetails('users',array('id'=>$user_id));

                if($user->role_id!=5){
					$msg = 'Cannot edit admin users from this section!';
		            $url = 'users/site_user';
		            redirectWithMsgFailed($msg,$url);
                }

				if (isset($_POST['submit_action'])){
					try{
						$this->form_validation->set_rules($this->validation_rules['siteUserUpdate']);

						if(!empty($this->input->post('password')) && !empty($this->input->post('password_confirm'))){	
							$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
							$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');
						}

						if(!$this->form_validation->run()){
			            	throw new Exception(validation_errors());
			            }

			            $post['first_name'] = $this->input->post('first_name');
			            $post['last_name']  = $this->input->post('last_name');
			            //$post['phone']      = $this->input->post('phone');
			            $post['email']      = $this->input->post('email');
						$post['updated_by'] = $user_data['user_id'];
						$post['updated_at'] = date('Y-m-d H:i:s');

						if(!empty($this->input->post('password')) && !empty($this->input->post('password_confirm'))){
							$password = $this->input->post('password');
							$post['password'] = $password;
						}

						#chk email duplication
                        $chk = $this->common_model->getRowDetails('users', array('id !='=>$user_id,'email'=>$post['email']));
                        if(!empty($chk)){
			            	throw new Exception('Email is already used for another user. Try diffrent email id!');
			            }					

						//$update = $this->common_model->updateData('users', $post, array('id'=>$user_id));

						// check to see if we are updating the user
					   if($this->ion_auth->update($user->id, $post)){
							redirect('users/site_user', 'refresh');
						}else{
							// redirect them back to the admin page if admin, or to the base url if non admin
							$this->session->set_flashdata('message', $this->ion_auth->errors() );
							redirect('users/site_user', 'refresh');
						}

						// if(!$update){
			   //          	throw new Exception('Failed to update!');
			   //          }

						// $msg = 'Site User Updated Successfully!!';
						// $url = 'users/site_user';
						// redirectWithMsgSuccess($msg,$url);
			        }
			        catch(Exception $e){
			            $msg = $e->getMessage();
			            $url = 'users/edit_site_user/'.$user_id;
			            redirectWithMsgFailed($msg,$url);
			        }		
				}

				$this->data['user'] = $user;

				if(!empty($this->data['user'])){
					$this->load->view('users/update_site_user',$this->data);
				}
				else{
					redirect(base_url().'users/site_user');
				}
			}
			else{
				redirect(base_url().'errors');
			}
		}
		else{
			$msg = 'Select user for edit!';
            $url = 'users/site_user';
            redirectWithMsgFailed($msg,$url);
		}
	}

}
