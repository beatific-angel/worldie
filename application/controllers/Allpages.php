<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Allpages extends CI_Controller {
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

            $this->data['title']   = 'Pages';
            $this->load->view('all-content/page_list', $this->data);		
	}

        public function page_list(){
            if(!$_SERVER['HTTP_REFERER']) redirect(base_url());

            $res = $this->crud->getFromSQL('
                        SELECT p.id, p.name, p.title, p.status, p.created_at, CONCAT(u.first_name, " ", u.last_name) as uname 
                        FROM pages as p 
                        LEFT JOIN users as u ON u.id = p.user_id 
                        ORDER BY p.id desc');
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
                $row[] = ($r->status == 1) ? '<span class="btn btn-success btn-xs item_status" current_status="'.$r->status.'" item_id="'.$r->id.'" item_type="Page" type="button">Active</span>' : '<span class="btn btn-warning btn-xs item_status" current_status="'.$r->status.'" item_id="'.$r->id.'" item_type="Page" type="button">In-Active</span>';

                $row[] = '<span class="btn btn-warning btn-xs view_page" type="button" item_id="'.$r->id.'" item_type="Page">View</span>
                            <span class="btn btn-danger btn-xs delete_page" title="Delete Post" id="delete_post_'.$r->id.'" data-id="'.$r->id.'">Delete</span>
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
                            SELECT p.id, p.name, p.title, p.status, p.created_at, p.summary, p.banner_image, p.display_image, CONCAT(u.first_name, " ", u.last_name) as created_by 
                            FROM pages as p 
                            LEFT JOIN users as u ON u.id = p.created_by
                            WHERE p.id = "'.$item_id.'"
                            ORDER BY p.id desc', 'row');

            $this->load->view('reportedcontent/item_view_page', $this->data);
	}

        public function deletePage(){
            if (!$this->ion_auth->logged_in()){
                    redirect('/login', 'refresh');
            }
            $postData = $this->input->post();
            $pId = $postData['id'];
            if(isset($postData['reported_post']) && $postData['reported_post'] == 1){
                $pageInfo = $this->crud->getRowById(array('id' => $pId), 'reported_content');
                if(isset($pageInfo->report_id) && $pageInfo->report_id){
                    $pId = $pageInfo->report_id;
                }
            }
            $item = $this->crud->getRowById(array('id' => $pId), 'pages');
            $this->crud->delete(array('report_id' => $pId , 'content_type' => 17),'reported_content');
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

                $pageAlbums = $this->crud->getAllRecords('page_album_items', array('page_id' => $item->id), 'image_name');
                if($pageAlbums){
                    foreach($pageAlbums as $gitem){
                        $dirname = $this->config->item('parent_folder_name').$gitem->image_name;
                        $this->unlinkFile($dirname);
                    }
                }

                $pagePosts = $this->crud->getAllRecords('page_posts', array('user_id_written_on' => $item->id), 'id, image_id, video_id, album_id');
                if($pagePosts){
                    foreach($pagePosts as $gpitem){
                        $this->crud->delete(array('post_id' => $gpitem->id, 'content_type' => 15), 'wall_shared_post');
                        if($gpitem->image_id != 0){
                            $imageInfo = $this->crud->getRowById(array('id' => $gpitem->image_id),'image');
                            if($imageInfo){
                                $type = mb_strtolower($imageInfo->type);
                                $imageName = $this->config->item('parent_folder_name').'assets/page/post_image/'.$imageInfo->prefix.'image_'.$imageInfo->id.'.'.$type;
                                $this->unlinkFile($imageName);
                            }
                            $this->crud->delete(array('id' => $gpitem->image_id),'image');
                            $this->deleteImageData($gpitem->image_id);
                        }

                        if($gpitem->video_id != 0){
                            $videoInfo = $this->crud->getRowById(array('id' => $gpitem->video_id),'video');
                            if($videoInfo){
                                $type = mb_strtolower($videoInfo->type);
                                $videoName = 'assets/page/post_video/'.$videoInfo->prefix.'video_'.$videoInfo->id.'.'.$type;
                                $path = $this->config->item('parent_folder_name').$videoName;
                                $this->unlinkFile($path);
                            }
                            $this->crud->delete(array('id' => $gpitem->video_id),'video');
                        }
                        $this->deletepagePostData($gpitem->id);
                    }
                }
                $this->crud->delete(array('id' => $item->id), 'pages');
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
}