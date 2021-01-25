<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Groups extends CI_Controller {
    /**
	 * __construct()
	 * User __construct
	*/
    public function __construct(){
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->helper(array('url','language'));
		$this->lang->load('auth');
		$this->load->model('Crud_model','crud');
	}
	/**
	 * postDeleted()
	 * User Delete his Post
	*/
	public function index(){
            if (!$this->ion_auth->logged_in()){
                    redirect('/login', 'refresh');
            }

            $this->data['title']   = 'Groups';
            $this->load->view('all-content/group_list', $this->data);		
	}

        public function group_list(){
            if(!$_SERVER['HTTP_REFERER']) redirect(base_url());

            $res = $this->crud->getFromSQL('
                        SELECT g.id, g.name, g.title, g.status, g.created_at, CONCAT(u.first_name, " ", u.last_name) as uname 
                        FROM groups as g 
                        LEFT JOIN users as u ON u.id = g.user_id 
                        ORDER BY g.id desc');
            $data = array();
            $no = 0;

            foreach ($res as $r){
                $no++;
                $row = array();
                $row[] = $no.'.';
                $row[] = $r->uname;
                $row[] = $r->name;
                $row[] = $r->title;
                $row[] = date('Y-m-d', strtotime($r->created_at));
                $row[] = ($r->status == 1) ? '<span class="btn btn-success btn-xs item_status" current_status="'.$r->status.'" item_id="'.$r->id.'" item_type="Group" type="button">Active</span>' : '<span class="btn btn-warning btn-xs item_status" current_status="'.$r->status.'" item_id="'.$r->id.'" item_type="Group" type="button">In-Active</span>';

                $row[] = '<span class="btn btn-warning btn-xs view_page" type="button" item_id="'.$r->id.'" item_type="Group">View</span>
                            <span class="btn btn-danger btn-xs delete_group" title="Delete Post" id="delete_post_'.$r->id.'" data-id="'.$r->id.'">Delete</span>
                        ';
//                <span class="btn btn-info btn-xs" title="Comment View" id="view_wallart_comments_'.$r->id.'" onClick="wallartCommentsViewById('.$r->id.')"> Comments</span>';
                $data[] = $row;
            }

            $output = array(
                "data" => $data,
                "recordsTotal" => count($data),
                "recordsFiltered" => count($data),
            );
            echo json_encode($output);
        }

        public function item_view(){
            $item_id   = $this->input->get('item_id', TRUE); 

            $this->data['item_id']   = $item_id;
            $this->data['results'] = $res = $this->crud->getFromSQL('
                            SELECT g.id, g.name, g.title, g.status, g.created_at, g.summary, g.banner_image, g.display_image, CONCAT(u.first_name, " ", u.last_name) as created_by 
                            FROM groups as g 
                            LEFT JOIN users as u ON u.id = g.created_by
                            WHERE g.id = "'.$item_id.'"
                            ORDER BY g.id desc', 'row');

            $this->load->view('reportedcontent/item_view_group', $this->data);
	}

        public function deleteGroup(){
            if (!$this->ion_auth->logged_in()){
                    redirect('/login', 'refresh');
            }
            $postData = $this->input->post();
            $gId = $postData['id'];
            if(isset($postData['reported_post']) && $postData['reported_post'] == 1){
                $grpInfo = $this->crud->getRowById(array('id' => $gId), 'reported_content');
                if(isset($grpInfo->report_id) && $grpInfo->report_id){
                    $gId = $grpInfo->report_id;
                }
            }
            
            $item = $this->crud->getRowById(array('id' => $gId), 'groups');
//            if(isset($postData['reported_post']) && $postData['reported_post'] == 1){
                $this->crud->delete(array('report_id' => $gId , 'content_type' => 16),'reported_content');
//            }
            if(isset($item->banner_image) && $item->banner_image){
                $path = $this->config->item('parent_folder_name').$item->banner_image;
                $this->unlinkFile($path);
            }
            if(isset($item->display_image) && $item->display_image){
                $path = $this->config->item('parent_folder_name').$item->display_image;
                $this->unlinkFile($path);
            }
            if(isset($item->id) && $item->id){
                $groupAlbums = $this->crud->getAllRecords('group_album_items', array('group_id' => $item->id), 'image_name');
                if($groupAlbums){
                    foreach($groupAlbums as $gitem){
                        $dirname = $this->config->item('parent_folder_name').$gitem->image_name;
                        $this->unlinkFile($dirname);
                    }
                }

                $groupPosts = $this->crud->getAllRecords('group_posts', array('user_id_written_on' => $item->id), 'id, image_id, video_id, album_id');
                if($groupPosts){
                    foreach($groupPosts as $gpitem){
                        if($gpitem->image_id != 0){
                            $imageInfo = $this->crud->getRowById(array('id' => $gpitem->image_id),'image');
                            if($imageInfo){
                                $type = mb_strtolower($imageInfo->type);
                                $imageName = $this->config->item('parent_folder_name').'assets/group/post_image/'.$imageInfo->prefix.'image_'.$imageInfo->id.'.'.$type;
                                $this->unlinkFile($imageName);
                            }
                            $this->crud->delete(array('id' => $gpitem->image_id),'image');
                            $this->deleteImageData($gpitem->image_id);
                        }

                        if($gpitem->video_id != 0){
                            $videoInfo = $this->crud->getRowById(array('id' => $gpitem->video_id),'video');
                            if($videoInfo){
                                $type = mb_strtolower($videoInfo->type);
                                $videoName = 'assets/group/post_video/'.$videoInfo->prefix.'video_'.$videoInfo->id.'.'.$type;
                                $path = $this->config->item('parent_folder_name').$videoName;
                                $this->unlinkFile($path);
                            }
                            $this->crud->delete(array('id' => $gpitem->video_id),'video');
                        }
                        $this->deletegroupPostData($gpitem->id);
                        $this->crud->delete(array('post_id' => $gpitem->id, 'content_type' => 14), 'wall_shared_post');
                    }
                }
                $this->crud->delete(array('user_id_written_on' => $item->id),'group_posts');
                $this->deleteGroupData($item->id);
                $this->crud->delete(array('id' => $item->id), 'groups');
                $msg['type']  = 'success';
                $msg['text']  = "Content Deleted Succsfully....!";
                echo json_encode($msg);
            } else {
                $msg['type']  = 'error';
                $msg['text']  = "Content Could Not Be Deleted. Pls. Try Again ....!";
                echo json_encode($msg);
            }
        }

        public function unlinkFile($path){
            if (file_exists($path)){
                unlink($path);
            }
        }

        public function deletepagePostData($postId){
            if($postId){
                $tableNames = array('page_post_comment', 'page_post_like', 'page_post_views');
                foreach($tableNames as $key => $value){
                    $this->crud->delete(array('post_id' => $postId), $value);
                }
            }
        }

        public function deletePagesData($pageId){
            if($pageId){
                $tableNames = array('pages_favourite', 'pages_following', 'pages_invites', 'pages_like', 'pages_users', 'pages_user_play_list', 'page_albums', 'page_album_items', 'page_views');
                foreach($tableNames as $key => $value){
                    $this->crud->delete(array('page_id' => $pageId), $value);
                }
            }
        }

        public function deleteImageData($imageId){
            if($imageId){
                $tableNames = array('image_comment', 'image_like', 'image_views');
                foreach($tableNames as $key => $value){
                    $this->crud->delete(array('image_id' => $imageId), $value);
                }
            }
        }

        public function deletegroupPostData($postId){
            if($postId){
                $tableNames = array('group_post_comment', 'group_post_views', 'group_post_like');
                foreach($tableNames as $key => $value){
                    $this->crud->delete(array('post_id' => $postId), $value);
                }
            }
        }

        public function deleteGroupData($groupId){
            if($groupId){
                $tableNames = array('groups_favourite', 'group_albums', 'group_album_items', 'group_comment', 'group_invites', 'group_like', 'group_meeting', 'group_rules', 'group_users');
                foreach($tableNames as $key => $value){
                    $this->crud->delete(array('group_id' => $groupId), $value);
                }
            }
        }
}