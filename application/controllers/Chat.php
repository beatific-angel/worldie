<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Chat extends CI_Controller {
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
                $user_data = $this->session->userdata();
                $role_id   = $user_data['role_id'];

                $this->data['view_status']   = checkPermission($role_id,'view_status');
//                if($this->data['view_status']){
                    $this->data['add_status']    = checkPermission($role_id,'add_status');
                    $this->data['edit_status']   = checkPermission($role_id,'edit_status');
                    $this->data['delete_status'] = checkPermission($role_id,'delete_status');

                    $this->data['title']   = 'Messages';
                
                    $chatInfo = $this->crud->getFromSQL('
                            SELECT uc.chat_id as id, uc.time, CONCAT(uf.first_name, " ", uf.last_name) as ufrom, CONCAT(ut.first_name, " ", ut.last_name) as uto
                            FROM user_chat as uc 
                            LEFT JOIN users as uf ON uc.user_one = uf.id 
                            LEFT JOIN users as ut ON uc.user_two = ut.id
                            WHERE uc.chat_id IN (SELECT chat_id FROM users_chat_msgs) ORDER BY uc.chat_id desc');
                    if($chatInfo){
                        foreach($chatInfo as $key => $value){
                            $chatId = $value->id;
                            $msgInfo = $this->crud->getFromSQL('
                                SELECT msg, time FROM users_chat_msgs WHERE chat_id="'.$chatId.'" ORDER BY id desc LIMIT 1', 'row');
                            if($msgInfo){
                                $chatInfo[$key]->message = $msgInfo->msg;
                                $chatInfo[$key]->message_time = $msgInfo->time;
                            }
                        }
                    }
                    $this->data['chatInfo'] = $chatInfo;
                    $this->load->view('chat/list', $this->data);
//                }else{	
//			redirect(base_url().'errors');
//		}	
	}

        public function messages($chatId = ''){
            if($chatId){
                $messages = $this->crud->getFromSQL('
                            SELECT m.*, CONCAT(u.first_name, " ", u.last_name) as uname, DATE_FORMAT(m.time,"%d-%m-%Y %H:%i") AS mtime
                            FROM users_chat_msgs as m 
                            LEFT JOIN users as u ON m.user_id = u.id 
                            WHERE m.chat_id = "'.$chatId.'" ORDER BY m.id ASC');
                $this->data['messages'] = $messages;
                $this->data['title']   = 'User Chat';
                $this->data['chatId']   = $chatId;
                $this->load->view('chat/messages', $this->data);
            }
        }
        public function deleteChat(){
            $postData = $this->input->post();
            $chatId = $postData['id'];
            $this->crud->delete(array('chat_id' => $chatId), 'users_chat_msgs');
            $this->crud->delete(array('chat_id' => $chatId), 'user_chat');
            $this->session->set_flashData('success', 'Chat Deleted Successfully!');
        }

        public function deleteMessage(){
            $postData = $this->input->post();
            $chatId = $postData['chat_id'];
            $id = $postData['id'];
            $this->crud->delete(array('id' => $id), 'users_chat_msgs');
            $chatMessages = $this->crud->getAllRecords('users_chat_msgs', array('chat_id' => $chatId));
//            print_r($chatMessages);exit;
            if(empty($chatMessages)){
                $this->crud->delete(array('chat_id' => $chatId), 'user_chat');
            }
            $this->session->set_flashData('success', 'Message Deleted Successfully!');
        }

        public function searchChat(){
            $postData = $this->input->post();
            $chatId = $postData['chatId'];
            $search = $postData['search'];
            $search = strtolower(str_replace(' ', '', $search));
            $messages = $this->crud->getFromSQL('
                            SELECT m.*, CONCAT(u.first_name, " ", u.last_name) as uname, DATE_FORMAT(m.time,"%d-%m-%Y %H:%i") AS mtime
                            FROM users_chat_msgs as m 
                            LEFT JOIN users as u ON m.user_id = u.id 
                            WHERE m.chat_id = "'.$chatId.'" AND LOWER(REPLACE(m.msg, " ", "")) LIKE "%'.$search.'%" ORDER BY m.id ASC');
            $this->data['messages'] = $messages;
            $html = $this->load->view('chat/search_html', $this->data, TRUE);
            $return = array();
            $return['html'] = $html;
            echo json_encode($return);
            exit;
        }
}