<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Reported_model extends CI_Model{
        public $table_name;
		
        public function __construct(){
            parent::__construct();
			$this->load->database();
			$this->table_name = 'reported_content';
			$this->config->load('user_permission', TRUE);
			$this->load->model('image_model');
        }

        public function getReportedContent(){
			$content_types = $this->config->item('content_type', 'user_permission');
			$data = [];
			foreach ($content_types as $key => $value){ 
			    $this->db->from($this->table_name);
				$this->db->where('content_type', $key);
				$query = $this->db->get();
				$count = $query->num_rows();
				$data[$value] = $count;
			}
			
			return $data;	
        }
		
		public function getReportedItems($item = null){
			$content_types = $this->config->item('content_type', 'user_permission');
			$key = array_search($item, $content_types);
		    $tablename = $this->table_name;

			$datatable = $this->getTableName($item);
			
			if($datatable == 'post'){
				$this->db->select('a.*,b.first_name,b.last_name,c.id AS item_id,c.content'); 
				$this->db->from("$tablename a");
				$this->db->join('users b', 'a.user_id = b.id');
				$this->db->join("$datatable c", 'a.report_id = c.id');
				$this->db->where('a.content_type', $key);
				$this->db->order_by('a.id', 'desc');
				$q = $this->db->get();
				if($q->num_rows() > 0){
					foreach ($q->result() as $row)
					{
						$data[] = $row;
					}
					/* echo"<pre>";
					print_r($data);die;*/
					return $data;
				}
			}elseif($datatable == 'events'){
				$this->db->select('a.*,b.first_name,b.last_name,c.id AS item_id,c.short_description'); 
				$this->db->from("$tablename a");
				$this->db->join('users b', 'a.user_id = b.id');
				$this->db->join("$datatable c", 'a.report_id = c.id');
				$this->db->where('a.content_type', $key);
				$this->db->order_by('a.id', 'desc');
				$q = $this->db->get();
				if($q->num_rows() > 0){
					foreach ($q->result() as $row)
					{
						$data[] = $row;
					}
					return $data;
				}			
			}elseif($datatable == 'post_comment'){
				//echo $key; die('dd');
				$this->db->select('a.*,b.first_name,b.last_name,c.id AS item_id,c.comment,c.element_id'); 
				$this->db->from("$tablename a");
				$this->db->join('users b', 'a.user_id = b.id');
				$this->db->join("$datatable c", 'a.report_id = c.id');
				$this->db->where('a.content_type', $key);
				$this->db->order_by('a.id', 'desc');
				$q = $this->db->get();
				//$this->db->last_query(); die('dd');
				if($q->num_rows() > 0){
					foreach ($q->result() as $row)
					{
						$data[] = $row;
					}
					return $data;
				}			
			}elseif($datatable == 'event_comment'){
				$this->db->select('a.*,b.first_name,b.last_name,c.id AS item_id,c.comment,c.element_id'); 
				$this->db->from("$tablename a");
				$this->db->join('users b', 'a.user_id = b.id');
				$this->db->join("$datatable c", 'a.report_id = c.id');
				$this->db->where('a.content_type', $key);
				$this->db->order_by('a.id', 'desc');
				$q = $this->db->get();
				if($q->num_rows() > 0){
					foreach ($q->result() as $row)
					{
						$data[] = $row;
					}
					return $data;
				}	
			}elseif($datatable == 'users_profile'){
				$this->db->select('a.*,a.report_id AS item_id,b.first_name,b.last_name'); 
				$this->db->from("$tablename a");
				$this->db->join('users b', 'a.user_id = b.id');
				$this->db->where('a.content_type', $key);
				$this->db->order_by('a.id', 'desc');
				$q = $this->db->get();
				if($q->num_rows() > 0){
					foreach ($q->result() as $row)
					{
						$data[] = $row;
					}
					return $data;
				}	
			}
			elseif($datatable == 'image_comment'){
				$this->db->select('a.*,a.report_id AS item_id,b.first_name,b.last_name'); 
				$this->db->from("$tablename a");
				$this->db->join('users b', 'a.user_id = b.id');
				$this->db->where('a.content_type', $key);
				$this->db->order_by('a.id', 'desc');
				$q = $this->db->get();
				if($q->num_rows() > 0){
					foreach ($q->result() as $row)
					{
						$data[] = $row;
					}
					return $data;
				}	
			} elseif($datatable == 'media_video_comment'){
				$this->db->select('a.*,a.report_id AS item_id,b.first_name,b.last_name,c.comment,c.element_id'); 
				$this->db->from("$tablename a");
				$this->db->join('users b', 'a.user_id = b.id');
				$this->db->join("$datatable c", 'a.report_id = c.id');
				$this->db->where('a.content_type', $key);
				$this->db->order_by('a.id', 'desc');
				$q = $this->db->get();
				if($q->num_rows() > 0){
					foreach ($q->result() as $row)
					{
						$data[] = $row;
					}
					return $data;
				}	
			}else if($datatable == 'media_video'){
				$this->db->select('a.*,a.report_id AS item_id,b.first_name,b.last_name,c.title'); 
				$this->db->from("$tablename a");
				$this->db->join('users b', 'a.user_id = b.id');
				$this->db->join("$datatable c", 'a.report_id = c.id');
				$this->db->where('a.content_type', $key);
				$this->db->order_by('a.id', 'desc');
				$q = $this->db->get();
				if($q->num_rows() > 0){
					foreach ($q->result() as $row)
					{
						$data[] = $row;
					}
					return $data;
				}	
			}else if($datatable == 'media_channel'){
				$this->db->select('a.*,a.report_id AS item_id,b.first_name,b.last_name,c.title'); 
				$this->db->from("$tablename a");
				$this->db->join('users b', 'a.user_id = b.id');
				$this->db->join("$datatable c", 'a.report_id = c.id');
				$this->db->where('a.content_type', $key);
				$this->db->order_by('a.id', 'desc');
				$q = $this->db->get();
				if($q->num_rows() > 0){
					foreach ($q->result() as $row)
					{
						$data[] = $row;
					}
					return $data;
				}	
			}else if($datatable == 'art_wall'){
				$this->db->select('a.*,a.report_id AS item_id,b.first_name,b.last_name,c.title'); 
				$this->db->from("$tablename a");
				$this->db->join('users b', 'a.user_id = b.id');
				$this->db->join("$datatable c", 'a.report_id = c.id');
				$this->db->where('a.content_type', $key);
				$this->db->order_by('a.id', 'desc');
				$q = $this->db->get();
				if($q->num_rows() > 0){
					foreach ($q->result() as $row)
					{
						$data[] = $row;
					}
					return $data;
				}	
			}else if($datatable == 'wall_arts'){
				$this->db->select('a.*,a.report_id AS item_id,b.first_name,b.last_name,c.title'); 
				$this->db->from("$tablename a");
				$this->db->join('users b', 'a.user_id = b.id');
				$this->db->join("$datatable c", 'a.report_id = c.id');
				$this->db->where('a.content_type', $key);
				$this->db->order_by('a.id', 'desc');
				$q = $this->db->get();
				if($q->num_rows() > 0){
					foreach ($q->result() as $row)
					{
						$data[] = $row;
					}
					return $data;
				}	
			}else if($datatable == 'wall_arts_comment'){
				$this->db->select('a.*,a.report_id AS item_id,b.first_name,b.last_name,c.comment,c.element_id'); 
				$this->db->from("$tablename a");
				$this->db->join('users b', 'a.user_id = b.id');
				$this->db->join("$datatable c", 'a.report_id = c.id');
				$this->db->where('a.content_type', $key);
				$this->db->order_by('a.id', 'desc');
				$q = $this->db->get();
				if($q->num_rows() > 0){
					foreach ($q->result() as $row)
					{
						$data[] = $row;
					}
					return $data;
				}	
			}else if($datatable == 'advertisement'){
				$this->db->select('a.*,a.report_id AS item_id,b.first_name,b.last_name,c.description,c.id'); 
				$this->db->from("$tablename a");
				$this->db->join('users b', 'a.user_id = b.id');
				$this->db->join("$datatable c", 'a.report_id = c.id');
				$this->db->where('a.content_type', $key);
				$this->db->order_by('a.id', 'desc');
				$q = $this->db->get();
				if($q->num_rows() > 0){
					foreach ($q->result() as $row)
					{
						$data[] = $row;
					}
					return $data;
				}	
			}else if($datatable == 'store_products'){
				$this->db->select('a.*,a.report_id AS item_id,b.first_name,b.last_name,c.name'); 
				$this->db->from("$tablename a");
				$this->db->join('users b', 'a.user_id = b.id');
				$this->db->join("$datatable c", 'a.report_id = c.id');
				$this->db->where('a.content_type', $key);
				$this->db->order_by('a.id', 'desc');
				$q = $this->db->get();
				//echo $this->db->last_query();
				if($q->num_rows() > 0){
					foreach ($q->result() as $row)
					{
						$data[] = $row;
					}
					return $data;
				}	
			}else if($datatable == 'store'){
				$this->db->select('a.*,a.report_id AS item_id,b.first_name,b.last_name,c.name'); 
				$this->db->from("$tablename a");
				$this->db->join('users b', 'a.user_id = b.id');
				$this->db->join("$datatable c", 'a.report_id = c.id');
				$this->db->where('a.content_type', $key);
				$this->db->order_by('a.id', 'desc');
				$q = $this->db->get();
				//echo $this->db->last_query();
				if($q->num_rows() > 0){
					foreach ($q->result() as $row)
					{
						$data[] = $row;
					}
					return $data;
				}	
			}else if($datatable == 'groups'){
				$this->db->select('a.*,a.report_id AS item_id,b.first_name,b.last_name,c.name'); 
				$this->db->from("$tablename a");
				$this->db->join('users b', 'a.user_id = b.id');
				$this->db->join("$datatable c", 'a.report_id = c.id');
				$this->db->where('a.content_type', $key);
				$this->db->order_by('a.id', 'desc');
				$q = $this->db->get();
				//echo $this->db->last_query();
				if($q->num_rows() > 0){
					foreach ($q->result() as $row)
					{
						$data[] = $row;
					}
					return $data;
				}	
			}
        }
		
		public function getReportedItemDetails($item_type = null, $item_id = null){
			$tablname = $this->getTableName($item_type);
			$result = '';
			if($tablname == 'post'){
				$this->db->select('a.*,b.first_name,b.last_name');
				$this->db->from("$tablname a");
				$this->db->join('users b', 'a.created_by = b.id');
				$this->db->where('a.id', $item_id);  // Also mention table name here
				$this->db->order_by('a.id', 'desc');
				$query = $this->db->get();    
				if($query->num_rows() > 0){
					$result = $query->row();
					if($result->post_type == 1){
						if($result->image_id != 0){
							$image = $this->image_model->getURL($result->image_id, $tablname);
							$result->image_url = $image;
						}
					}elseif($result->post_type == 4){
						if($result->album_id != 0){
							$album_item = $this->image_model->getAlbumItems($result->album_id);
							$result->album_items = $album_item;
						}
					}
				}	
			}elseif($tablname == 'events'){
				$this->db->select('*');
				$this->db->where('id', $item_id);
				$this->db->order_by('id', 'desc');  
				$query = $this->db->get($tablname);
				if($query->num_rows() > 0){
					$result = $query->row();
					if($result->image_path != 0 || $result->image_path != ''){
						$result->image_url = $this->image_model->getEventImageURL($result->image_path);
					}
				}	
			}elseif($tablname == 'post_comment'){
				$this->db->select('a.*,b.first_name,b.last_name');
				$this->db->from("$tablname a");
				$this->db->join('users b', 'a.created_by = b.id');
				$this->db->where('a.id', $item_id);
				$this->db->order_by('a.id', 'desc');
				$query = $this->db->get();
				if($query->num_rows() > 0){
					$result = $query->row();
					/*if($result->image_path != 0 || $result->image_path != ''){
						$result->image_url = $this->image_model->getEventImageURL($result->image_path);
					}*/
				}	
			}elseif($tablname == 'event_comment'){
				$this->db->select('a.*,b.first_name,b.last_name');
				$this->db->from("$tablname a");
				$this->db->join('users b', 'a.created_by = b.id');
				$this->db->where('a.id', $item_id);
				$this->db->order_by('a.id', 'desc');
				$query = $this->db->get();
				if($query->num_rows() > 0){
					$result = $query->row();
					/*if($result->image_path != 0 || $result->image_path != ''){
						$result->image_url = $this->image_model->getEventImageURL($result->image_path);
					}*/
				}	
			}elseif($tablname == 'image_comment'){
				$this->db->select('a.*,b.first_name,b.last_name');
				$this->db->from("$tablname a");
				$this->db->join('users b', 'a.created_by = b.id','LEFT');
				$this->db->where('a.id', $item_id);
				$this->db->order_by('a.id', 'desc');
				$query = $this->db->get();
				if($query->num_rows() > 0){
					$result = $query->row();
					/*if($result->image_path != 0 || $result->image_path != ''){
						$result->image_url = $this->image_model->getEventImageURL($result->image_path);
					}*/
				}	
			}elseif($tablname == 'media_video'){

				$this->db->select('a.*,b.first_name,b.last_name');
				$this->db->from("$tablname a");
				$this->db->join('users b', 'a.created_by = b.id');
				$this->db->where('a.id', $item_id);
				$this->db->order_by('a.id', 'desc');
				$query = $this->db->get();
				if($query->num_rows() > 0){
					$result = $query->row();
					$array  = explode('/', $result->video_location);
					$array1 = explode('.', $array[2]);
					//$array2 = explode('_', $array1[0]);
					$result->video_ext = strtolower($array1[1]);
					/*if($result->image_path != 0 || $result->image_path != ''){
						$result->video_url = $this->image_model->getMediaImageURL($result->image_path);
					}*/
				}	
			}
			elseif($tablname == 'wall_arts'){
				$this->db->select('a.*,b.first_name,b.last_name');
				$this->db->from("$tablname a");
				$this->db->join('users b', 'a.created_by = b.id','LEFT');
				$this->db->where('a.id', $item_id);
				$this->db->order_by('a.id', 'desc');
				$query = $this->db->get();
				if($query->num_rows() > 0){
					$result = $query->row();
				}	
			}
			else if($tablname == 'group_posts'){
				$this->db->select('a.*,b.first_name,b.last_name');
				$this->db->from("$tablname a");
				$this->db->join('users b', 'a.created_by = b.id');
				$this->db->where('a.id', $item_id);  // Also mention table name here
				$this->db->order_by('a.id', 'desc');
				$query = $this->db->get();    
				if($query->num_rows() > 0){
					$result = $query->row();
				}	
			}

			return $result;
        }
		
		public function UpdateStatus($data = null){
			$tablname = $this->getTableName($data['type']);
			$user_id = $this->session->userdata('user_id');
			
			$savedata = array(
				'updated_by' => $user_id,
				'updated_at' => date('Y-m-d H:i:s'),
				'status'     => $data['new_status']
			);
			
			$this->db->where('id', $data['id']);
            $result = $this->db->update($tablname, $savedata);
			if($result){
		        return true;
			}else{
				return false;
			}       
        }
		
		public function updateReportedItemStatus($data = null){
			$tablname = $this->getTableName($data['type']);
			$user_id = $this->session->userdata('user_id');
			$content_types = $this->config->item('content_type', 'user_permission');
			$key = array_search($data['type'], $content_types);
			
			$reported = $this->db->query('SELECT * FROM reported_content WHERE id='.$data['id'])->row();
			
			$savedata = array(
				'updated_by' => $user_id,
				'updated_at' => date('Y-m-d H:i:s'),
				'status'     => $data['new_status']
			);
			
			$this->db->where('id', $reported->report_id);
			if($key == 6){
				$tablname = 'users';
			}else{
				$tablname = $this->getTableName($data['type']);
			}
            $result = $this->db->update($tablname, $savedata);
			//update reported content table
			$this->db->where('id', $data['id']);
            $result = $this->db->update($this->table_name, $savedata);
			if($result){
		        return true;
			}else{
				return false;
			}
		}
		
		public function getTableName($type = null){
			switch ($type) {
				case "Post":
					return "post";
					break;
				case "Post_Comment":
					return "post_comment";
					break;
			  	case "Image_comment":
					return "image_comment";
					break;
				case "Event_Comment":
					return "event_comment";
					break;
				case "media":
					return "media_gallery_category";
					break;
				case "art":
					return "art_gallery_category";
					break;
				case "Event":
					return "events";
					break;
				case "User_Profile":
					return "users_profile";
					break;
				case "meet_date":
					return "meet_date_category";
					break;	
				case "Media_video":
				    return "media_video";
				    break;
				case "Media_video_comment":
				    return "media_video_comment";
				    break;
				case "Media_Channel":
				    return "media_channel";
				    break;
				case "Art_Wall":
				    return "art_wall";
				    break;
				case "Wall_Art":
				    return "wall_arts";
				    break;
				case "Wall_Art_Comment":
				    return "wall_arts_comment";
				    break;  
				case "Advertisement":
				    return "advertisement";
				    break;
				case "Product":
				    return "store_products";
				    break;	
				case "Store":
				    return "store";
				    break;		
				case "Group":
				    return "groups";
				    break;
			    case "Group_Post":
				    return "group_posts";
				    break;		
				default:
					break;
			}
		}

		public function sendReviewNotification($data = null){
			//print_r($data); die();
			$table         = 'user_notification';
			$userid        = $this->session->userdata('user_id');	
			$reportid      = $data['user_id'];	
			$id            = $data['id'];	
			$item_id       = $data['item_id'];
			$content_types = $this->config->item('content_type', 'user_permission');
			$item_type     = array_search($data['item_type'], $content_types);

			if($data['item_type']=="Post" || $data['item_type']=="Post_Comment"){
				$item_type = array_search('Post', $content_types);
				if($data['item_type']=="Post_Comment"){
					$this->db->where('id',$item_id);
					$result=$this->db->get('post_comment')->row();
					$item_id=$result->post_id;
					$title="Your reported content for Post Comment has been reviewed";
				}else{
					$item_id=$item_id;
					$title="Your reported content for Post has been reviewed";
				}
			}else if($data['item_type']=="Event" || $data['item_type']=="Event_Comment"){
				$item_type = array_search('Event', $content_types);
				if($data['item_type']=="Event_Comment"){
					$this->db->where('id',$item_id);
					$result=$this->db->get('event_comment')->row();
					$item_id=$result->event_id;
					$title="Your reported content for Event Comment has been reviewed";
				}else{
					$item_id=$item_id;
					$title="Your reported content for Event has been reviewed";
				}
			}elseif($data['item_type']=="User_Profile"){
				$item_id=$item_id;
				$item_type = 4;
				$title="Your reported user profile has been reviewed";
			}else if($data['item_type']=="Image_comment"){
				$item_type = 3;
				if($data['item_type']=="Image_comment"){
					$this->db->where('id',$item_id);
					$result=$this->db->get('image_comment')->row();
					$item_id=$result->id;
					$title="Your reported content for Image Comment has been reviewed";
				}else{
					$item_id=$item_id;
					$title="Your reported content for Image has been reviewed";
				}
		 	}else if($data['item_type']=="Media_video"){
		 		$item_type = 5;
				$title="Your reported Media Video has been reviewed";
		 	}else if($data['item_type']=="Media_video_comment"){
		 		$this->db->where('id',$item_id);
				$result=$this->db->get('media_video_comment')->row();
				$item_id=$result->video_id;
				$item_type = 5;
				$title="Your reported content for Video Comment has been reviewed";
		 	}else if($data['item_type']=="Media_Channel"){
		 		$item_type = 6;
				$title="Your reported Media Channel has been reviewed";
		 	}else if($data['item_type']=="Art_Wall"){
		 		$item_type = 7;
				$title="Your reported Art Wall has been reviewed";
		 	}else if($data['item_type']=="Wall_Art"){
		 		$item_type = 8;
				$title="Your reported Wall Art has been reviewed";
		 	}else if($data['item_type']=="Wall_Art_Comment"){
		 		$this->db->where('id',$item_id);
				$result=$this->db->get('wall_arts_comment')->row();
				$item_id=$result->art_id;
				$item_type = 8;
				$title="Your reported content for Art Comment has been reviewed";
		 	}else if($data['item_type']=="Wall_Art_Comment"){
		 		$this->db->where('id',$item_id);
				$result=$this->db->get('wall_arts_comment')->row();
				$item_id=$result->art_id;
				$item_type = 8;
				$title="Your reported content for Art Comment has been reviewed";
		 	}else if($data['item_type']=="Product"){
		 		$this->db->where('id',$item_id);
				$result=$this->db->get('store_products')->row();
				$item_id=$result->id;
				$item_type = 14;
				$title="Your reported content for Product has been reviewed";
		 	}else if($data['item_type']=="Group"){
		 		$this->db->where('id',$item_id);
				$result=$this->db->get('groups')->row();
				$item_id=$result->id;
				$item_type = 16;
				$title="Your reported content for Product has been reviewed";
		 	}
			

			$savedata = array(
				'type' => $item_type ,
				'item_id' => $item_id,
				'notification_by' => $userid ,
				'notification_to' => $reportid,
				'title' => $title,
				'status' => 1,
				'updated_by' => $userid,
				'updated_at' => date('Y-m-d H:i:s'),
				'created_by' => $userid,
				'created_at' => date('Y-m-d H:i:s'),
			);
			$result=$this->db->insert($table, $savedata); 
			//$result=1; 
			if($result){
					$updatedata = array(
					'isReviewed' => 1 ,
					'updated_by' => $userid,
					'updated_at' => date('Y-m-d H:i:s')
					);
				$this->db->where('id', $id);
	            $result = $this->db->update($this->table_name, $updatedata);
				//echo $this->db->last_query();
				//die();
				if($result){
					return true;
				}
			}else{
				return false;
			}       
        }

        public function sendWarningToUser($data = null){
        	$table = 'user_notification';
			$userid = $this->session->userdata('user_id');	
			$item_id = $data['item_id'];
			
			//$reported = $this->db->query('SELECT * FROM reported_content WHERE report_id='.$data['item_id'])->row();
			$title="Your profile have some content which have been reported by other user kindly remove it.Other wise your profile will be blocked";
			$savedata = array(
				'type' => 4,
				'item_id' => $item_id,
				'notification_by' => $userid ,
				'notification_to' => $data['item_id'],
				'title' => $title,
				'status' => 1,
				'updated_by' => $userid,
				'updated_at' => date('Y-m-d H:i:s'),
				'created_by' => $userid,
				'created_at' => date('Y-m-d H:i:s'),
			);
			$result=$this->db->insert($table, $savedata);
			if($result){
				return true;
			}else{
				return false;
			}     
        }
		
    }
?>