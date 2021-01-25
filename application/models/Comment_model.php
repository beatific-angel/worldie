<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Comment_model extends CI_Model {
		
		public $table_name;
		public $table_image;
		public $event_image_location;
		public $profile_image_location;
		public $image_max_size;
		public $quality;
		public $profile_table_name;
		
		public function __construct(){
			parent::__construct();
			$this->load->database();
		}

        public function getAllComments($comment = 'ALL'){
            $return = array();
            if($comment == 'Event') {
                $event_comments = $this->getModuleComments('event_comment', ',a.element_id as module_id, a.raw_comment as ocomment, c.event_planning as module', 'events', 'a.element_id', ', d.raw_comment as rcomment');
                if($event_comments){
                    foreach($event_comments as $data){
                        $data->type = 'Event';
                        $data->comment_type = 'Event_Comment';
                    }
                    array_push($return, $event_comments);
                }
            } else if($comment == 'Group Post') {
                $group_post_comments = $this->getModuleComments('group_post_comment', ',a.post_id as module_id, a.comment as ocomment, c.content as module', 'group_posts', 'a.post_id', ', d.comment as rcomment');
                if($group_post_comments){
                    foreach($group_post_comments as $data){
                        $data->type = 'Group_Post';
                        $data->comment_type = 'Group_Post_Comment';
                    }
                    array_push($return, $group_post_comments);
                }
            } else if($comment == 'Media Video') {
                $media_video_comments = $this->getModuleComments('media_video_comment', ',a.element_id as module_id, a.raw_comment as ocomment, c.title as module', 'media_video', 'a.element_id', ', d.raw_comment as rcomment');
                if($media_video_comments){
                    foreach($media_video_comments as $data){
                        $data->type = 'Media_video';
                        $data->comment_type = 'Media_video_comment';
                    }
                    array_push($return, $media_video_comments);
                }
            } else if($comment == 'Page Post') {
                $page_post_comments = $this->getModuleComments('page_post_comment', ',a.post_id as module_id, a.comment as ocomment, c.content as module', 'page_posts', 'a.post_id', ', d.comment as rcomment');
                if($page_post_comments){
                    foreach($page_post_comments as $data){
                        $data->type = 'Page_Post';
                        $data->comment_type = 'Page_Post_Comment';
                    }
                    array_push($return, $page_post_comments);
                }
            } else if($comment == 'Post') {
                $post_comments = $this->getModuleComments('post_comment', ',a.element_id as module_id, a.raw_comment as ocomment, c.content as module', 'post', 'a.element_id', ', d.raw_comment as rcomment');
                if($post_comments){
                    foreach($post_comments as $data){
                        $data->type = 'Post';
                        $data->comment_type = 'Post_Comment';
                    }
                    array_push($return, $post_comments);
                }
            } else if($comment == 'Store Product') {
                $sp_comments = $this->getModuleComments('store_product_comment', ',a.element_id as module_id, a.raw_comment as ocomment, c.name as module', 'store_products', 'a.element_id', ', d.raw_comment as rcomment');
                if($sp_comments){
                    foreach($sp_comments as $data){
                        $data->type = 'Store_Product';
                        $data->comment_type = 'Store_Product_Comment';
                    }
                    array_push($return, $sp_comments);
                }
            } else if($comment == 'Wall Art') {
                $wa_comments = $this->getModuleComments('wall_arts_comment', ',a.element_id as module_id, a.raw_comment as ocomment, c.title as module', 'wall_arts', 'a.element_id', ', d.raw_comment as rcomment');
                if($wa_comments){
                    foreach($wa_comments as $data){
                        $data->type = 'Wall_Art';
                        $data->comment_type = 'Wall_Art_Comment';
                    }
                    array_push($return, $wa_comments);
                }
            } else if($comment == 'Group') {
                $g_comments = $this->getModuleComments('group_comment', ',a.group_id as module_id, a.comment as ocomment, c.name as module', 'groups', 'a.group_id', ', d.comment as rcomment');
                if($g_comments){
                    foreach($g_comments as $data){
                        $data->type = 'Group';
                        $data->comment_type = 'Group_Comment';
                    }
                    array_push($return, $g_comments);
                }
            } else if($comment == 'ALL'){
                $page_post_comments = $this->getModuleComments('page_post_comment', ',a.post_id as module_id, a.comment as ocomment, c.content as module', 'page_posts', 'a.post_id', ', d.comment as rcomment');
                if($page_post_comments){
                    foreach($page_post_comments as $data){
                        $data->type = 'Page_Post';
                        $data->comment_type = 'Page_Post_Comment';
                    }
                    array_push($return, $page_post_comments);
                }
                $post_comments = $this->getModuleComments('post_comment', ',a.element_id as module_id, a.raw_comment as ocomment, c.content as module', 'post', 'a.element_id', ', d.raw_comment as rcomment');
                if($post_comments){
                    foreach($post_comments as $data){
                        $data->type = 'Post';
                        $data->comment_type = 'Post_Comment';
                    }
                    array_push($return, $post_comments);
                }
                $group_post_comments = $this->getModuleComments('group_post_comment', ',a.post_id as module_id, a.comment as ocomment, c.content as module', 'group_posts', 'a.post_id', ', d.comment as rcomment');
                if($group_post_comments){
                    foreach($group_post_comments as $data){
                        $data->type = 'Group_Post';
                        $data->comment_type = 'Group_Post_Comment';
                    }
                    array_push($return, $group_post_comments);
                }
                $event_comments = $this->getModuleComments('event_comment', ',a.element_id as module_id, a.raw_comment as ocomment, c.event_planning as module', 'events', 'a.element_id', ', d.raw_comment as rcomment');
                if($event_comments){
                    foreach($event_comments as $data){
                        $data->type = 'Event';
                        $data->comment_type = 'Event_Comment';
                    }
                    array_push($return, $event_comments);
                }
                $media_video_comments = $this->getModuleComments('media_video_comment', ',a.element_id as module_id, a.raw_comment as ocomment, c.title as module', 'media_video', 'a.element_id', ', d.raw_comment as rcomment');
                if($media_video_comments){
                    foreach($media_video_comments as $data){
                        $data->type = 'Media_video';
                        $data->comment_type = 'Media_video_comment';
                    }
                    array_push($return, $media_video_comments);
                }
                $sp_comments = $this->getModuleComments('store_product_comment', ',a.element_id as module_id, a.raw_comment as ocomment, c.name as module', 'store_products', 'a.element_id', ', d.raw_comment as rcomment');
                if($sp_comments){

                    foreach($sp_comments as $data){
                        $data->type = 'Store_Product';
                        $data->comment_type = 'Store_Product_Comment';
                    }
                    array_push($return, $sp_comments);
                }
                $wa_comments = $this->getModuleComments('wall_arts_comment', ',a.element_id as module_id, a.raw_comment as ocomment, c.title as module', 'wall_arts', 'a.element_id', ', d.raw_comment as rcomment');
                if($wa_comments){
                    foreach($wa_comments as $data){
                        $data->type = 'Wall_Art';
                        $data->comment_type = 'Wall_Art_Comment';
                    }
                    array_push($return, $wa_comments);
                }
                $g_comments = $this->getModuleComments('group_comment', ',a.group_id as module_id, a.comment as ocomment, c.name as module', 'groups', 'a.group_id', ', d.comment as rcomment');
                if($g_comments){
                    foreach($g_comments as $data){
                        $data->type = 'Group';
                        $data->comment_type = 'Group_Comment';
                    }
                    array_push($return, $g_comments);
                }
            }
            // print_r($return);exit;
            return $return;
        }

        public function getModuleComments($tableFrom, $selectColumns, $joinTable, $joinTableId, $replyConent){
            $this->db->select("a.id AS data_item_id, a.user_id AS data_userid, a.created_at AS data_created_at, a.updated_at AS data_updated_at, b.first_name AS data_f_name, b.last_name AS data_l_name, b.gender AS data_gender,a.status as status, a.comment_id, e.first_name AS rf_name, e.last_name AS rl_name, b.ip_address, f.id as is_block, b.status as user_status, g.first_name AS original_fname, g.last_name AS original_lname".$selectColumns.$replyConent);
            $this->db->from($tableFrom. " a");
            $this->db->join('users b', 'b.id = a.user_id');
            $this->db->join($joinTable." c", "c.id = ".$joinTableId);
            $this->db->join($tableFrom." d", "d.id = a.comment_id", "LEFT");
            $this->db->join("users e", "d.user_id = e.id", "LEFT");
            $this->db->join("blocked_ip as f", "f.ip_address = b.ip_address", "LEFT");
            $this->db->join("users g", "g.id = c.user_id");
            $this->db->where('a.status' , 1);
            $this->db->order_by('a.id', 'desc');
            $query = $this->db->get();
            $data = $query->result();
            return $data;
        }

        public function deleteComment($id, $type){
            $table = '';
            switch ($type) {
                case 'Event_Comment':
                    $table = 'event_comment';
                    break;
                case 'Group_Post_Comment':
                    $table = 'group_post_comment';
                    break;
                case 'Media_video_comment':
                    $table = 'media_video_comment';
                    break;
                case 'Page_Post_Comment':
                    $table = 'page_post_comment';
                    break;
                case 'Post_Comment':
                    $table = 'post_comment';
                    break;
                case 'Store_Product_Comment':
                    $table = 'store_product_comment';
                    break;
                case 'Wall_Art_Comment':
                    $table = 'wall_arts_comment';
                    break;
                case 'Group_Comment' :
                    $table = "group_comment";
                    break;
            }
            $result = FALSE;
            if($table){
                $this->db->where('id', $id);
		        $result = $this->db->delete($table);

                $this->db->where('comment_id', $id);
		        $result = $this->db->delete($table);
                // $this->db->where('id', $id);
                // $result = $this->db->update($table, array('status' => 0));
            }
            return $result;
        }


        public function deleteCommentPerticular($element_id, $type, $id){
            $table = $this->getCommentTable($type);
            $result = FALSE;
            if($table){
                $this->db->where('id', $id);
		        $result = $this->db->delete($table);

                $this->db->where('comment_id', $id);
		        $result = $this->db->delete($table);
            }
            return $result;
        }

        public function deleteAllComment($carray){
            $result = FALSE;
            foreach($carray as $value){
                $idArr = explode('-', $value);
                $type = $idArr[0];
                $id = $idArr[1];

                $table = $this->getCommentTable($type);

                if($table){
                    $this->db->where('id', $id);
    		        $result = $this->db->delete($table);
    
                    $this->db->where('comment_id', $id);
    		        $result = $this->db->delete($table);
                }
            }
            return $result;
        }
        public function getGroupById($postid){
            $tablename = 'groups';
            $user_id = $this->session->userdata('user_id');
            $this->db->select('a.*, b.first_name AS pu_f_name, b.last_name AS pu_l_name, b.gender AS pu_gender,c.image AS pu_image,c.thumb_image AS pu_thumb_image');
            $this->db->from("groups a");
            $this->db->join('users b', 'b.id = a.user_id');
            $this->db->join('users_profile c', 'b.id = c.user_id');
            $this->db->where('a.status', 1);
            $this->db->where('a.id', $postid);
            $query = $this->db->get();        
            $result = $query->row();
            $data = array();
    	    $data[] = $result;
            $data['comment'] = $this->get_comments('Group', $result->id);
            
            return $data;
        }

        public function getGroupPostById($postid){
            $tablename = 'group_posts';
            $user_id = $this->session->userdata('user_id');
            $this->db->select('a.*, b.first_name AS pu_f_name, b.last_name AS pu_l_name, b.gender AS pu_gender,c.image AS pu_image,c.thumb_image AS pu_thumb_image');
            $this->db->from("group_posts a");
            $this->db->join('users b', 'b.id = a.user_id');
            $this->db->join('users_profile c', 'b.id = c.user_id');
            $this->db->where('a.status', 1);
            $this->db->where('a.id', $postid);
            $query = $this->db->get();        
            $result = $query->row();
            $data = array();
    	    $data[] = $result;
            $data['comment'] = $this->get_comments('Group_Post', $result->id);
            
            return $data;
        }

        public function getPagePostById($postid){
            $tablename = 'page_posts';
            $user_id = $this->session->userdata('user_id');
            $this->db->select('a.*, b.first_name AS pu_f_name, b.last_name AS pu_l_name, b.gender AS pu_gender,c.image AS pu_image,c.thumb_image AS pu_thumb_image');
            $this->db->from("page_posts a");
            $this->db->join('users b', 'b.id = a.user_id');
            $this->db->join('users_profile c', 'b.id = c.user_id');
            $this->db->where('a.status', 1);
            $this->db->where('a.id', $postid);
            $query = $this->db->get();        
            $result = $query->row();
            $data = array();
    	    $data[] = $result;
            $data['comment'] = $this->get_comments('Page_Post', $result->id);
            
            return $data;
        }

        public function getPostById($postid){
            $tablename = 'post';
            $user_id = $this->session->userdata('user_id');
            $this->db->select('a.*, b.first_name AS pu_f_name, b.last_name AS pu_l_name, b.gender AS pu_gender,c.image AS pu_image,c.thumb_image AS pu_thumb_image');
            $this->db->from("post a");
            $this->db->join('users b', 'b.id = a.user_id');
            $this->db->join('users_profile c', 'b.id = c.user_id');
            $this->db->where('a.status', 1);
            $this->db->where('a.id', $postid);
            $query = $this->db->get();        
            $result = $query->row();
            $data = array();
    	    $data[] = $result;
            $data['comment'] = $this->get_comments('Post', $result->id);
            
            return $data;
        }

        public function getStoreProductById($postid){
            $tablename = 'store_products';
            $user_id = $this->session->userdata('user_id');
            $this->db->select('a.*, b.first_name AS pu_f_name, b.last_name AS pu_l_name, b.gender AS pu_gender,c.image AS pu_image,c.thumb_image AS pu_thumb_image');
            $this->db->from("store_products a");
            $this->db->join('users b', 'b.id = a.user_id');
            $this->db->join('users_profile c', 'b.id = c.user_id');
            $this->db->where('a.status', 1);
            $this->db->where('a.id', $postid);
            $query = $this->db->get();        
            $result = $query->row();
            $data = array();
    	    $data[] = $result;
            $data['comment'] = $this->get_comments('Store_Product', $result->id);
            
            return $data;
        }

        public function getWallArtById($postid){
            $tablename = 'wall_arts';
            $user_id = $this->session->userdata('user_id');
            $this->db->select('a.*, b.first_name AS pu_f_name, b.last_name AS pu_l_name, b.gender AS pu_gender,c.image AS pu_image,c.thumb_image AS pu_thumb_image');
            $this->db->from("wall_arts a");
            $this->db->join('users b', 'b.id = a.user_id');
            $this->db->join('users_profile c', 'b.id = c.user_id');
            $this->db->where('a.status', 1);
            $this->db->where('a.id', $postid);
            $query = $this->db->get();        
            $result = $query->row();
            $data = array();
    	    $data[] = $result;
            $data['comment'] = $this->get_comments('Wall_Art', $result->id);
            
            return $data;
        }

        public function get_comments($module = null, $id = null){
            $table = $this->getCommentTable($module);
            if($table == 'group_comment') {
                $column = 'group_id';
                $ccolumn = 'comment';
            } else if($table == 'group_post_comment' || $table == 'page_post_comment') {
                $column = 'post_id';
                $ccolumn = 'comment';
            } else {
                $column = 'element_id';
                $ccolumn = 'raw_comment';
            }

            // echo 'table '.$table;die;
            $this->db->select('b.comment AS comment_text, b.'.$ccolumn.' AS edit_comment, b.id AS comment_id,b.comment_id AS parent_id,b.user_id AS c_user_id,c.first_name AS cu_f_name,c.last_name AS cu_l_name,c.id AS cu_userid,c.gender AS cu_gender,d.image AS cu_image,d.thumb_image AS cu_thumb_image, c.ip_address');
            $this->db->from("$table b");
            $this->db->join('users c', 'b.user_id = c.id', 'left');
            $this->db->join('users_profile d','b.user_id = d.user_id','left');
            $cmnt_cond = array('b.'.$column.' =' => $id, 'comment_id =' => 0);
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
            if($table == 'group_comment') {
                $column = 'group_id';
                $ccolumn = 'comment';
            } else if($table == 'group_post_comment' || $table == 'page_post_comment') {
                $column = 'post_id';
                $ccolumn = 'comment';
            } else {
                $column = 'element_id';
                $ccolumn = 'raw_comment';
            }
            $this->db->select('b.comment AS comment_text, b.'.$ccolumn.' AS edit_comment, b.id AS comment_id,b.comment_id AS parent_id,b.user_id AS c_user_id,c.first_name AS cu_f_name,c.last_name AS cu_l_name,c.id AS cu_userid,c.gender AS cu_gender,d.image AS cu_image,d.thumb_image AS cu_thumb_image, c.ip_address');
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

        public function getCommentTable($type){
            // if (filter_var($type, FILTER_VALIDATE_INT)) {
            //     $module = $this->getModuleOfComment($type);
            // }else{
            //     $module = $type;
            // }
            
            $table = '';

            switch ($type) {
                case 'Event':
                    $table = 'event_comment';
                    break;
                case 'Group':
                    $table = 'group_comment';
                    break;
                case 'Group_Post':
                    $table = 'group_post_comment';
                    break;
                case 'Media_video':
                    $table = 'media_video_comment';
                    break;
                case 'Page_Post':
                    $table = 'page_post_comment';
                    break;
                case 'Post':
                    $table = 'post_comment';
                    break;
                case 'Store_Product':
                    $table = 'store_product_comment';
                    break;
                case 'Wall_Art':
                    $table = 'wall_arts_comment';
                    break;
            }
    
            return $table;
        }

        public function blockAllIP($carray){
            $result = FALSE;
            foreach($carray as $value){
                $ip = $value;
                $this->db->select('ip_address');
                $this->db->from("blocked_ip");
                $this->db->where('ip_address', $value);
                $query = $this->db->get();        
                $ipInfo = $query->row();
                // print_r($this->db->last_query());exit;
                $ipAddress = isset($ipInfo->ip_address) ? $ipInfo->ip_address : NULL;
                if(empty($ipAddress)){
                    $user_data = $this->session->userdata();
                    $user_id   = $user_data['user_id'];
                    $this->db->insert('blocked_ip', array('ip_address' => $ip, 'created_by' => $user_id));
                }
            }
            return $result;
        }

        public function unblockAllIP($carray){
            $result = FALSE;
            foreach($carray as $value){
                $ip = $value;
                $this->db->where('ip_address', $ip);
		        $result = $this->db->delete('blocked_ip');
            }
            return $result;
        }
}
?>
