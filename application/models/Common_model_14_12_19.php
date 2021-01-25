<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Common_model extends CI_Model {

		public $table_name;
		
		public function __construct(){
			parent::__construct();
			$this->load->database();
		}

		public function updateStatus($data = null){
			$user_id          = $this->session->userdata('user_id');
			$this->table_name = $this->getTableName($data['type']);

			$savedata = array(
				'updated_by' => $user_id,
				'updated_at' => date('Y-m-d H:i:s'),
				'status'     => $data['new_status']
			);
			
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

		public function count_post_list(){
			return $this->db->count_all_results('post');
		}

		public function count_media_list(){
			return $this->db->count_all_results('media_video');
		}
		
		public function count_art_list(){
			return $this->db->count_all_results('wall_arts');  // Produces an integer, like 25
		}
		
		public function count_wall_list(){
			return $this->db->count_all_results('art_wall');  // Produces an integer, like 25
		}

		public function count_events_list(){
			return $this->db->count_all_results('events');  //
		}
		
		public function count_product_list(){
			return $this->db->count_all_results('store_products');
		}
		public function count_store_list(){
			return $this->db->count_all_results('store');
		}
		public function count_groups_list(){
			return $this->db->count_all_results('groups');
		}
		public function count_pages_list(){
			return $this->db->count_all_results('pages');
		}

		public function getTotalReportContentCount(){
			$this->db->select('count(`id`) as total_report, content_type');
			$this->db->from('reported_content');
			$this->db->group_by('content_type');
			$query = $this->db->get(); 
			return $query->result();
		}

		public function getTotalReadReportContent($content_type){
			$this->db->select('count(`id`) as total_read');
			$this->db->from('reported_content');
			$this->db->where('content_type', $content_type);
			$this->db->where('isReviewed', 1);
			$this->db->group_by('content_type');
			$query = $this->db->get(); 
			return $query->row();
		}

		#delete image & other subcomponent(likes, comment, views)
		public function delete_image_with_component($image_id,$image_folder,$image_folder_full=false){
             $get_image = $this->db->query('SELECT * FROM image WHERE id='.$image_id)->row();
             if(!empty($get_image)){

             	if(!$image_folder_full){
                 $img_name = $image_folder.$get_image->prefix.'image_'.$get_image->id.'.'.strtolower($get_image->type);
             	}
             	else{
             	 $img_name = $image_folder;
             	}

                if (file_exists($this->config->item('parent_folder_name').$img_name)){
                    unlink($this->config->item('parent_folder_name').$img_name);
                }

                $this->db->where('id', $get_image->id);
                $this->db->delete('image');

                #delete image like,view,comment
                $get_image_comment = $this->db->query('SELECT * FROM image_comment WHERE image_id='.$get_image->id)->result();
                if(count($get_image_comment)>0){
                    foreach ($get_image_comment as $record) {
                        $this->db->where('id', $record->id);
                        $this->db->delete('image_comment');
                   }
                }

                $get_image_like = $this->db->query('SELECT * FROM image_like WHERE image_id='.$get_image->id)->result();
                if(count($get_image_like)>0){
                    foreach ($get_image_like as $record) {
                       $this->db->where('id', $record->id);
                       $this->db->delete('image_like');
                   }
                }

                $get_image_views = $this->db->query('SELECT * FROM image_views WHERE image_id='.$get_image->id)->result();
                if(count($get_image_views)>0){
                    foreach ($get_image_views as $record) {
                       $this->db->where('id', $record->id);
                       $this->db->delete('image_views');
                    }
                }
             }
		}

		public function get_comments($module = null, $id = null){
			$table = $this->getCommentTable($module);
			$this->db->select('b.comment AS comment_text, b.raw_comment AS edit_comment, b.id AS comment_id,b.comment_id AS parent_id,b.user_id AS c_user_id,c.first_name AS cu_f_name,c.last_name AS cu_l_name,c.id AS cu_userid,c.gender AS cu_gender,d.image AS cu_image,d.thumb_image AS cu_thumb_image');
			$this->db->from("$table b");
			$this->db->join('users c', 'b.user_id = c.id', 'left');
			$this->db->join('users_profile d','b.user_id = d.user_id','left');
			$cmnt_cond = array('b.element_id =' => $id, 'comment_id =' => 0);
			$this->db->where($cmnt_cond);
			$this->db->order_by('b.created_at','DESC');
			$cmnt_query = $this->db->get();
			$comment = $cmnt_query->result();
			if(count($comment) > 0){
				$i=0;
				foreach($comment as $cmnt){
					$comment[$i]->reply_count = $this->getModuleCommentReplyCount($table, $cmnt->comment_id);
					$comment[$i]->reply = $this->comment_replies($table, $cmnt->comment_id);
					$i++;
				}
				return $comment;
			}else{
				return (object) array();
			}
		}

		/* Function Name: getModuleCommentReplyCount() */
		public function getModuleCommentReplyCount($table, $comment_id){
			$query   = $this->db->query('SELECT * FROM '.$table.' WHERE comment_id= '.$comment_id);
			$results = $query->result();
			if(count($results) > 0){
				$reply_count = $query->num_rows();
				foreach($results as $result){
					$reply_count += $this->getModuleCommentReplyCount($table, $result->id);
				}
				return $reply_count;
			}else{
				return 0;
			}
		}


		public function comment_replies($table = null, $id = null){
			$this->db->select('b.comment AS comment_text, b.raw_comment AS edit_comment, b.id AS comment_id,b.comment_id AS parent_id,b.user_id AS c_user_id,c.first_name AS cu_f_name,c.last_name AS cu_l_name,c.id AS cu_userid,c.gender AS cu_gender,d.image AS cu_image,d.thumb_image AS cu_thumb_image');
			$this->db->from("$table b");
			$this->db->join('users c', 'b.user_id = c.id', 'left');
			$this->db->join('users_profile d', 'c.id = d.user_id', 'left');
			$this->db->where('b.comment_id =', $id);
			$this->db->order_by("b.created_at", "DESC");
			$reply_query = $this->db->get(); 
			$this->db->last_query();
			$comment_reply = $reply_query->result();
			if(count($comment_reply) > 0){
				$i=0;
				foreach($comment_reply as $reply){
					$comment_reply[$i]->reply = $this->comment_replies($table, $reply->comment_id);
					$i++;
				}
			}
			return $comment_reply;       
		}

		/* ss custom methods */
		public function ssDeleteComment($data){
			$result = $this->deleteCommentCommon($data);
			return $result;
		}
		public function deleteCommentCommon($data){

			$user_id = $this->session->userdata('user_id');
			$table = $this->getCommentTable($data['type']);
        // echo "<pre>"; print_r($table);die;

			$commentid = $data['id'];
			$records   = $this->db->query('SELECT * FROM '.$table.' WHERE id='.$commentid.' OR comment_id='.$commentid)->result();
			if(count($records) > 0){
				foreach($records as $record){
					$this->db->where('id', $record->id);
					$this->db->delete($table);
				}
				return 'deleted';
			}else{
				return 'norecord';
			}
		}
		
		public function getCommentTable($type)
		{
			if (filter_var($type, FILTER_VALIDATE_INT)) {
				$module = $this->getModuleOfComment($type);
			}else{
				$module = $type;
			}
			$table = '';
			switch ($module) {
				case 'events':
				$table = 'event_comment';
				break;
				case 'posts':
				$table = 'post_comment';
				break;
				case 'wall_arts':
				$table = 'wall_arts_comment';
				break;
				case 'store_products':
				$table = 'store_product_comment';
				break;
				case 'media_video':
				$table = 'media_video_comment';
				break;    
			}
			return $table;
		}

		public function getModuleOfComment($type){
			$this->config->load('user_permission', TRUE);
			$c_type  = $this->config->item('content_type', 'user_permission');
			if(count($c_type) > 0){
				foreach($c_type as $key => $value){
					if($type == $value){
						return $key;
					}
				}
			}
		}

	/*	Get table data by ID  */
	public function getTableValue($table_name,$column_name,$value)
	{
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->where($column_name, $value);
		$query = $this->db->get();
		return $query->row() ;
	}		
}
?>
