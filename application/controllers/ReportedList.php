<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ReportedList extends Base_Controller {
	 
	function __construct(){
		parent::__construct();
		$this->load->model('reported_model');
		$this->load->library('ion_auth');

		$user_data = $this->session->userdata();
		$this->role_id = $user_data['role_id'];
		$this->user_id = $user_data['user_id'];

	} 
	
	public function index(){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}

	$this->data['view_status'] = checkPermission($this->role_id,'view_status');
    if($this->data['view_status']){
    	$this->data['add_status']    = checkPermission($this->role_id,'add_status');
		$this->data['edit_status']   = checkPermission($this->role_id,'edit_status');
		$this->data['delete_status'] = checkPermission($this->role_id,'delete_status');

		$this->data['title']   = 'Reported Content List';
		$this->load->view('reportedcontent/reported_item_list', $this->data);
	}
	else{	
			redirect(base_url().'errors');
		}
	}

	public function reported_list(){
        if(!$_SERVER['HTTP_REFERER']) redirect(base_url());

        $this->reported_model->column_order  = array('a.id');
        $this->reported_model->column_search = array('b.first_name', 'b.last_name');

        $res = $this->reported_model->reportedList();

        $data = array();
        $no = $_POST['start'];

        $edit_status   = checkPermission($this->role_id,'edit_status');
		$delete_status = checkPermission($this->role_id,'delete_status');
            
        foreach ($res as $r){
        	$view_button = $delete_button = $review_button = '';

            if($r->content_type==1){//post
             $content_data = $this->common_model->getData('post' , array('id'=>$r->report_id) , $fetch_type = 'single');
             $item_type    = 'Post';
             $item_id      = $content_data->id;
             $data_content = $content_data->content;

            $view_button = '<span class="btn btn-warning btn-xs view_reported" type="button" item_id="'.$item_id.'" item_type="'.$item_type.'">View</span>';

            $delete_button = '<span class="btn btn-danger delete_post" title="Delete Reported Post" id="delete_post_'.$r->id.'" data-id="'.$r->id.'"><i class="fa fa-trash"></i></span>';
            }
            else if($r->content_type==2){//event
             $content_data = $this->common_model->getData('events' , array('id'=>$r->report_id) , $fetch_type = 'single');
             $item_type    = 'Event';
             $item_id      = $content_data->id;
             $data_content = $content_data->short_description;

             $view_button = '<span class="btn btn-warning btn-xs view_reported_event" type="button" item_id="'.$item_id.'" item_type="'.$item_type.'">View</span>';

             $delete_button = '<span class="btn btn-danger delete_event" title="Delete Reported Event" id="delete_event_'.$r->id.'" data-id="'.$r->id.'"><i class="fa fa-trash"></i></span>';
            }
            else if($r->content_type==3){//Post_Comment
             $content_data = $this->common_model->getData('post_comment' , array('id'=>$r->report_id) , $fetch_type = 'single');
             $item_type    = 'Post_Comment';
             $item_id      = $content_data->id;
             $data_content = $content_data->comment;

             $delete_button = '<span class="btn btn-danger delete_postcomment" title="Delete Reported Comment" id="delete_postcomment_'.$r->id.'" data-id="'.$r->id.'"><i class="fa fa-trash"></i></span>';
            }
            else if($r->content_type==4){//Image Comment
             $item_type    = 'Image_Comment';
             $item_id      = $r->report_id;
             $data_content = 'Image';

             $delete_button = '<span class="btn btn-danger delete_imagecomment" title="Delete Image Comment" id="delete_imagecomment_'.$r->id.'" data-id="'.$r->id.'"><i class="fa fa-trash"></i></span>';
            }
            else if($r->content_type==5){//Event_Comment
             $content_data = $this->common_model->getData('event_comment' , array('id'=>$r->report_id) , $fetch_type = 'single');
             $item_type    = 'Event_Comment';
             $item_id      = $content_data->id;
             $data_content = $content_data->comment;

             $delete_button = '<span class="btn btn-danger delete_eventcomment" title="Delete Event Comment" id="delete_eventcomment_'.$r->id.'" data-id="'.$r->id.'"><i class="fa fa-trash"></i></span>';
            }
            else if($r->content_type==6){//User_Profile
             $item_type    = 'User_Profile';
             $item_id      = $r->report_id;
             $data_content = 'Reported User Profile';

             $view_button = '<span class="btn btn-warning btn-xs view_user_profile" type="button" item_id="'.$item_id.'" item_type="'.$item_type.'">View</span>';

             $delete_button = '<span class="btn btn-danger btn-xs send_warning" type="button" item_id="'.$item_id.'" item_type="'.$item_type.'">Send Warning</span>';
            }
            else if($r->content_type==7){//Media_video
            $content_data = $this->common_model->getData('media_video' , array('id'=>$r->report_id) , $fetch_type = 'single');

             $item_type    = 'Media_video';
             $item_id      = $r->report_id;
             $data_content = $content_data->title;

             $view_button = '<span class="btn btn-warning btn-xs view_media_video" type="button" item_id="'.$item_id.'" item_type="'.$item_type.'">View</span>';

             $delete_button = '<span class="btn btn-danger btn-xs delete_media_video" title="Delete Media Video" type="button" id="delete_mediavideo_'.$r->id.'" data-id="'.$r->id.'"><i class="fa fa-trash"></i></span>';
            }
            else if($r->content_type==8){//Media_video_comment
            $content_data = $this->common_model->getData('media_video_comment' , array('id'=>$r->report_id) , $fetch_type = 'single');

             $item_type    = 'Media_video_comment';
             $item_id      = $r->report_id;
             $data_content = $content_data->comment;

             $delete_button = '<span class="btn btn-danger btn-xs delete_mediavideo_comment" title="Delete Media Video Commment" type="button" id="delete_mediavideocomment_'.$r->id.'" data-id="'.$r->id.'"><i class="fa fa-trash"></i></span>';
            }
            else if($r->content_type==9){//Media_Channel
            $content_data = $this->common_model->getData('media_channel' , array('id'=>$r->report_id) , $fetch_type = 'single');

             $item_type    = 'Media_Channel';
             $item_id      = $r->report_id;
             $data_content = $content_data->title;
             
             $view_button = '<span class="btn btn-warning btn-xs view_media_channel" type="button" item_id="'.$item_id.'" item_type="'.$item_type.'">View</span>';
            }
            else if($r->content_type==10){//Art_Wall
            $content_data = $this->common_model->getData('art_wall' , array('id'=>$r->report_id) , $fetch_type = 'single');

             $item_type    = 'Art_Wall';
             $item_id      = $r->report_id;
             $data_content = $content_data->title;
             
             $view_button = '<span class="btn btn-warning btn-xs view_art_wall" type="button" item_id="'.$item_id.'" item_type="'.$item_type.'">View</span>';
            }
            else if($r->content_type==11){//Wall_Art
            $content_data = $this->common_model->getData('wall_arts' , array('id'=>$r->report_id) , $fetch_type = 'single');

             $item_type    = 'Wall_Art';
             $item_id      = $r->report_id;
             $data_content = $content_data->title;
             
             $view_button = '<span class="btn btn-warning btn-xs view_wall_art" type="button" item_id="'.$item_id.'" item_type="'.$item_type.'">View</span>';

             $delete_button = '<span class="btn btn-danger btn-xs delete_wall_art" title="Delete Wall Art" id="delete_comment_'.$r->id.'" data-id="'.$r->id.'"><i class="fa fa-trash"></i></span>';
            }
            else if($r->content_type==12){//Wall_Art_Comment
            $content_data = $this->common_model->getData('wall_arts_comment' , array('id'=>$r->report_id) , $fetch_type = 'single');

             $item_type    = 'Wall_Art_Comment';
             $item_id      = $r->report_id;
             $data_content = $content_data->comment;
            
             $delete_button = '<span class="btn btn-danger btn-xs delete_wallart_comment" title="Delete Wall Art Commment" id="delete_comment_'.$r->id.'" data-id="'.$r->id.'"><i class="fa fa-trash"></i></span>';
            }
            else if($r->content_type==13){//Advertisement
            $content_data = $this->common_model->getData('advertisement' , array('id'=>$r->report_id) , $fetch_type = 'single');

             $item_type    = 'Advertisement';
             $item_id      = $r->report_id;
             $data_content = $content_data->description;
            
             $delete_button = '<span class="btn btn-danger btn-xs delete_advertisement" title="Delete Advertisement" id="delete_advt_'.$r->id.'" data-id="'.$r->id.'"><i class="fa fa-trash"></i></span>';
            }
            else if($r->content_type==14){//Product
            $content_data = $this->common_model->getData('store_products' , array('id'=>$r->report_id) , $fetch_type = 'single');

             $item_type    = 'Product';
             $item_id      = $r->report_id;
             $data_content = $content_data->name;
            
             $delete_button = '<span class="btn btn-danger btn-xs delete_product" title="Delete Product" id="delete_store_product_'.$r->id.'" data-id="'.$r->id.'"><i class="fa fa-trash"></i></span>';
            }
            else if($r->content_type==15){//Store
            $content_data = $this->common_model->getData('store' , array('id'=>$r->report_id) , $fetch_type = 'single');

             $item_type    = 'Store';
             $item_id      = $r->report_id;
             $data_content = $content_data->name;
            
             $delete_button = '<span class="btn btn-danger btn-xs delete_store" title="Delete Store" id="delete_store_'.$r->id.'" data-id="'.$r->id.'"><i class="fa fa-trash"></i></span>';
            }
            else if($r->content_type==16){//Group
            $content_data = $this->common_model->getData('groups' , array('id'=>$r->report_id) , $fetch_type = 'single');

             $item_type    = 'Group';
             $item_id      = $r->report_id;
             $data_content = $content_data->name;

             $delete_button = '<span class="btn btn-danger btn-xs delete_group" title="Delete Group" id="delete_group_'.$r->id.'" data-id="'.$r->id.'"><i class="fa fa-trash"></i></span>';
            }
            else if($r->content_type==17){//Group
            $content_data = $this->common_model->getData('pages' , array('id'=>$r->report_id) , $fetch_type = 'single');

             $item_type    = 'Page';
             $item_id      = $r->report_id;
             $data_content = $content_data->name;

             $delete_button = '<span class="btn btn-danger btn-xs delete_page" title="Delete Page" id="delete_page_'.$r->id.'" data-id="'.$r->id.'"><i class="fa fa-trash"></i></span>';
            }

            $review_button = '<span class="btn btn-warning btn-xs review_reported" type="button" item_id="'.$item_id.'" item_type="'.$item_type.'" report_id="'.$r->report_id.'" user_id="'.$r->user_id.'" id="'.$r->id.'">Review</span>';

            $no++;
            $row = array();
            if($r->content_type == 5){
                if($data_content){
                    $row[] = $no.'.';
                    $row[] = $item_type;
                    $row[] = $data_content;
                    $row[] = ($r->first_name.' '.$r->last_name);
                    $row[] = ($r->report_reason=='other') ? $r->report_description : $r->report_reason;
                    $row[] = date('Y-m-d', strtotime($r->created_at));

                    $row[] = ($edit_status) ? (($r->status == 1) ? '<span class="btn btn-success btn-xs reported_item_status" current_status="'.$r->status.'" reported_item_id="'.$r->id.'" item_type="'.$item_type.'" type="button">Active</span>' : '<span class="btn btn-warning btn-xs reported_item_status" current_status="'.$r->status.'" reported_item_id="'.$r->id.'" item_type="'.$item_type.'" type="button">In-Active</span>') : '';

                    $row[]  = ($edit_status && $delete_status) ? $view_button . $delete_button . $review_button : '';
                    $data[] = $row;
                }
            }else {
                $row[] = $no.'.';
                $row[] = $item_type;
                $row[] = $data_content;
                $row[] = ($r->first_name.' '.$r->last_name);
                $row[] = ($r->report_reason=='other') ? $r->report_description : $r->report_reason;
                $row[] = date('Y-m-d', strtotime($r->created_at));

                $row[] = ($edit_status) ? (($r->status == 1) ? '<span class="btn btn-success btn-xs reported_item_status" current_status="'.$r->status.'" reported_item_id="'.$r->id.'" item_type="'.$item_type.'" type="button">Active</span>' : '<span class="btn btn-warning btn-xs reported_item_status" current_status="'.$r->status.'" reported_item_id="'.$r->id.'" item_type="'.$item_type.'" type="button">In-Active</span>') : '';

                $row[]  = ($edit_status && $delete_status) ? $view_button . $delete_button . $review_button : '';
                $data[] = $row;
            }
            //}
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($res),
            "recordsFiltered" => $this->reported_model->count_reported_list(),
            "data" => $data,
        );
        echo json_encode($output);
    }
	
	public function reported_item(){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('reported_item-reportedcontent')){
			$this->session->set_flashdata('error', "You Don't Have Permission to Perform the Operation");
			redirect('users/dashboard', 'refresh');
		}
		
	    $item = $this->input->get('item', TRUE);
		$result = $this->reported_model->getReportedItems($item);
       
		$this->data['title']   = 'Reported Content';
		$this->data['item']    = $item;
		$this->data['results'] = $result;

		$this->load->view('reportedcontent/reported_item', $this->data);
	}
	
	public function item_view(){
		$item_type = $this->input->get('item_type', TRUE); 
		$item_id   = $this->input->get('item_id', TRUE); 

		$result = $this->reported_model->getReportedItemDetails($item_type, $item_id);

		$this->data['item_type'] = $item_type;
		$this->data['item_id']   = $item_id;
		$this->data['results']   = $result;

		if($item_type == 'Post'){
			$this->load->view('reportedcontent/item_view', $this->data);
		}elseif($item_type == 'Event'){
			$this->load->view('reportedcontent/item_view_event', $this->data);
		}elseif($item_type == 'Post_Comment'){
			$this->load->view('reportedcontent/item_view_postcomment', $this->data);
		}elseif($item_type == 'Event_Comment'){
			$this->load->view('reportedcontent/item_view_eventcomment', $this->data);
		}elseif($item_type == 'Image_comment'){
			$this->load->view('reportedcontent/item_view_imagecomment', $this->data);
		} elseif($item_type == 'Media_video'){
            $this->load->view('reportedcontent/item_view_mediavideo', $this->data);
		}
		elseif($item_type == 'Wall_Art'){
            $this->load->view('reportedcontent/item_view_wallart', $this->data);
		}
		elseif($item_type == 'Group_Post'){
            $this->load->view('reportedcontent/item_view_group_post', $this->data);
		}
		elseif($item_type == 'Page_Post'){
            $this->load->view('reportedcontent/item_view_page_post', $this->data);
		}
	}
	
	public function delete_content(){
		$type = $this->input->get('type', TRUE); 
		$serviceid = $this->uri->segment(3);
		$data['servicedata'] = $this->county_model->getservicedata($serviceid);
		
		$this->load->view('category/show', $data);
		
	}
	
	public function deactivate(){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('change_status-category')){
			
			$msg['type'] = 'error';
			$msg['text'] = "You Don't Have Permission to Perform the Operation";
			$msg = json_encode($msg);
			echo $msg; exit;
		}
		if ($this->input->post())
        {
			$result = $this->category_model->UpdateStatus($this->input->post());
            if($result){
				$msg['type'] = 'success';
				$msg['text'] = "Category Status Updated Successfully.................!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}else{
				$msg['type'] = 'error';
				$msg['text'] = "Category Could not be Updated Pls. Try Again..!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}
        }
	}
	
	public function activate(){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('change_status-category')){
			
			$msg['type'] = 'error';
			$msg['text'] = "You Don't Have Permission to Perform the Operation";
			$msg = json_encode($msg);
			echo $msg; exit;
		}
		if ($this->input->post())
        {
			$result = $this->category_model->UpdateStatus($this->input->post());
            if($result){
				$msg['type'] = 'success';
				$msg['text'] = "Category Status Updated Successfully.................!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}else{
				$msg['type'] = 'error';
				$msg['text'] = "Category Could not be Updated Pls. Try Again..!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}
        }
	}
	
	public function deactivateReportedContent(){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('change_status-reportedcontent')){
			
			$msg['type'] = 'error';
			$msg['text'] = "You Don't Have Permission to Perform the Operation";
			$msg = json_encode($msg);
			echo $msg; exit;
		}
		if ($this->input->post())
        {
			//echo"<pre>";
			//print_r($this->input->post());die;
			$result = $this->reported_model->updateReportedItemStatus($this->input->post());
            if($result){
				$msg['type'] = 'success';
				$msg['text'] = "Report Content Status Updated Successfully.................!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}else{
				$msg['type'] = 'error';
				$msg['text'] = "Report Content Status Could not be Updated Pls. Try Again..!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}
        }
	}
	
	public function activateReportedContent(){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('change_status-reportedcontent')){
			
			$msg['type'] = 'error';
			$msg['text'] = "You Don't Have Permission to Perform the Operation";
			$msg = json_encode($msg);
			echo $msg; exit;
		}
		if ($this->input->post())
        {
			$result = $this->reported_model->updateReportedItemStatus($this->input->post());
            if($result){
				$msg['type'] = 'success';
				$msg['text'] = "Report Content Status Updated Successfully.................!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}else{
				$msg['type'] = 'error';
				$msg['text'] = "Report Content Status Could not be Updated Pls. Try Again..!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}
        }
	}

	public function sendReviewNotification(){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('review-reportedcontent')){
			
			$msg['type'] = 'error';
			$msg['text'] = "You Don't Have Permission to Perform the Operation";
			$msg = json_encode($msg);
			echo $msg; exit;
		}
		if ($this->input->post())
        {
			$result = $this->reported_model->sendReviewNotification($this->input->post());
            if($result){
				$msg['type'] = 'success';
				$msg['text'] = "Reviewed Notification Sent Successfully.................!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}else{
				$msg['type'] = 'error';
				$msg['text'] = "Reviewed Notification Could not be Sent Pls. Try Again..!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}
        }
	}
	
	public function sendWarningToUser(){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}
		if(!$this->ion_auth->has_permission('review-reportedcontent')){
			
			$msg['type'] = 'error';
			$msg['text'] = "You Don't Have Permission to Perform the Operation";
			$msg = json_encode($msg);
			echo $msg; exit;
		}
		if ($this->input->post())
        {
			$result = $this->reported_model->sendWarningToUser($this->input->post());
            if($result){
				$msg['type'] = 'success';
				$msg['text'] = "Warning Message Sent Successfully.................!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}else{
				$msg['type'] = 'error';
				$msg['text'] = "Warning Message Could not be Sent Pls. Try Again..!";
				$msg = json_encode($msg);
				echo $msg; exit;
			}
        }
	}

}
