<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Walls extends CI_Controller {
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

            $this->data['title']   = 'Walls';
            $this->load->view('all-content/wall_list', $this->data);		
	}

        public function wall_list(){
            if(!$_SERVER['HTTP_REFERER']) redirect(base_url());

            $res = $this->crud->getFromSQL('
                        SELECT a.id, a.name, a.title, a.status, a.created_at, CONCAT(u.first_name, " ", u.last_name) as uname , ac.name as category
                        FROM art_wall as a 
                        LEFT JOIN users as u ON u.id = a.user_id 
                        LEFT JOIN art_wall_category as ac ON ac.id = a.category_id
                        WHERE a.user_id IN(SELECT id FROM users)
                        ORDER BY a.id desc');
            $data = array();
            $no = 0;

            foreach ($res as $r){
                $no++;
                $row = array();
                $row[] = $no.'.';
                $row[] = $r->uname;
                $row[] = $r->category;
                $row[] = $r->name;
                $row[] = $r->title;
                $row[] = date('Y-m-d', strtotime($r->created_at));
                $row[] = ($r->status == 1) ? '<span class="btn btn-success btn-xs item_status" current_status="'.$r->status.'" item_id="'.$r->id.'" item_type="Art_Wall" type="button">Active</span>' : '<span class="btn btn-warning btn-xs item_status" current_status="'.$r->status.'" item_id="'.$r->id.'" item_type="Art_Wall" type="button">In-Active</span>';

                $row[] = '<span class="btn btn-warning btn-xs view_page" type="button" item_id="'.$r->id.'" item_type="Art_Wall">View</span>
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
                            SELECT a.id, a.name, a.title, a.status, a.created_at, a.description, a.banner_image, a.display_image, CONCAT(u.first_name, " ", u.last_name) as created_by 
                            FROM art_wall as a 
                            LEFT JOIN users as u ON u.id = a.created_by
                            WHERE a.id = "'.$item_id.'"
                            ORDER BY a.id desc', 'row');

            $this->load->view('reportedcontent/item_view_wall', $this->data);
	}

        public function deleteWall(){
            if (!$this->ion_auth->logged_in()){
                    redirect('/login', 'refresh');
            }
            $postData = $this->input->post();
            $pId = $postData['id'];
            $item = $this->crud->getRowById(array('id' => $pId), 'art_wall');
            if(isset($item->banner_image) && $item->banner_image){
                $path = $this->config->item('parent_folder_name').$item->banner_image;
                $this->unlinkFile($path);
            }
            if(isset($item->display_image) && $item->display_image){
                $path = $this->config->item('parent_folder_name').$item->display_image;
                $this->unlinkFile($path);
            }
            if(isset($item->id) && $item->id){
                $this->crud->delete(array('id' => $item->id), 'art_wall');
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