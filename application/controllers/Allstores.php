<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Allstores extends CI_Controller {
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

            $this->data['title']   = 'Stores';
            $this->load->view('all-content/store_list', $this->data);		
	}

        public function store_list(){
            if(!$_SERVER['HTTP_REFERER']) redirect(base_url());

            $res = $this->crud->getFromSQL('
                        SELECT s.id, s.name, s.title, s.status, s.created_at, CONCAT(u.first_name, " ", u.last_name) as uname , sc.name as category
                        FROM store as s 
                        LEFT JOIN users as u ON u.id = s.user_id 
                        LEFT JOIN store_category as sc ON sc.id = s.category_id
                        WHERE s.user_id IN(SELECT id FROM users)
                        ORDER BY s.id desc');
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
                $row[] = ($r->status == 1) ? '<span class="btn btn-success btn-xs item_status" current_status="'.$r->status.'" item_id="'.$r->id.'" item_type="Store" type="button">Active</span>' : '<span class="btn btn-warning btn-xs item_status" current_status="'.$r->status.'" item_id="'.$r->id.'" item_type="Store" type="button">In-Active</span>';

                $row[] = '<span class="btn btn-warning btn-xs view_page" type="button" item_id="'.$r->id.'" item_type="Store">View</span>
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
                            SELECT s.id, s.name, s.title, s.status, s.created_at, s.description, s.slider_image1, s.slider_image2, s.slider_image3, CONCAT(u.first_name, " ", u.last_name) as created_by 
                            FROM store as s 
                            LEFT JOIN users as u ON u.id = s.created_by
                            WHERE s.id = "'.$item_id.'"
                            ORDER BY s.id desc', 'row');

            $this->load->view('reportedcontent/item_view_store', $this->data);
	}

        public function deleteStore(){
            if (!$this->ion_auth->logged_in()){
                    redirect('/login', 'refresh');
            }
            $postData = $this->input->post();
            $pId = $postData['id'];
            $item = $this->crud->getRowById(array('id' => $pId), 'store');
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
                    $this->crud->delete(array('id' => $item->id), 'store');
                }
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

        public function deleteStoreData($Id) {
            if($Id){
                $tableNames = array('store_favourite', 'store_invited', 'store_join', 'store_liked', 'store_products', 'store_views');
                foreach($tableNames as $key => $value){
                    $this->crud->delete(array('store_id' => $Id), $value);
                }
            }
        }
}