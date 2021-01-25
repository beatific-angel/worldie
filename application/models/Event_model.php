<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Event_model extends CI_Model {
		
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
			$this->table_name  = 'events';
			$this->table_image = 'image';
			$this->event_image_location = 'assets/events_images/';
			$this->image_max_size       = 2000; //Maximum image size (height and width)
            $this->quality              = 90; //jpeg quality

		}

		/* 
		  Function: deleteEvent()
		  Description:  delete event 
		
		*/		

		public function deleteEvent($id = null,$reported_event=true){
			if($reported_event){	
			 $results =  $this->db->query('SELECT * FROM reported_content WHERE id='.$id)->row();
			 $event_id=$results->report_id;
			}
			else{
             $event_id = $id;
			}
			
			//delete event comment
			$c_records  = $this->db->query('SELECT * FROM event_comment WHERE element_id='.$event_id)->result();
			if(count($c_records) > 0){
				foreach($c_records as $record){
				$this->db->where('id', $record->id);
				       $this->db->delete('event_comment');
				}
			}
			//delete event view
			$v_records  = $this->db->query('SELECT * FROM event_views WHERE event_id='.$event_id)->result();
			if(count($v_records) > 0){
				foreach($v_records as $record){
				$this->db->where('id', $record->id);
				       $this->db->delete('event_views');
				}
			}
			//delete event like
			$l_records  = $this->db->query('SELECT * FROM event_like WHERE event_id='.$event_id)->result();
			if(count($l_records) > 0){
				foreach($l_records as $record){
				$this->db->where('id', $record->id);
				       $this->db->delete('event_like');
				}
			}
			//delete event wall share
			$w_records  = $this->db->query('SELECT * FROM wall_shared_post WHERE post_id='.$event_id.' AND content_type=2')->result();
			if(count($w_records) > 0){
				foreach($w_records as $record){
				$this->db->where('id', $record->id);
				       $this->db->delete('wall_shared_post');
				}
			}
			//delete event invited user
			$i_records  = $this->db->query('SELECT * FROM event_invited_users WHERE event_id='.$event_id)->result();
			if(count($i_records) > 0){
				foreach($i_records as $record){
				$this->db->where('id', $record->id);
				       $this->db->delete('event_invited_users');
				}
			}
			// delete joied event from list
			$j_records  = $this->db->query('SELECT * FROM user_events_join WHERE event_id='.$event_id)->result();
			if(count($j_records) > 0){
				foreach($j_records as $record){
				$this->db->where('id', $record->id);
				       $this->db->delete('user_events_join');
				}
			}
			// delete user contacted user
			$e_records  = $this->db->query('SELECT * FROM events_contact WHERE event_id='.$event_id)->result();
			if(count($e_records) > 0){
				foreach($e_records as $record){
				$this->db->where('id', $record->id);
				       $this->db->delete('events_contact');
				}
			}
			//delete event
			$this->db->where('id', $event_id);
			$this ->db->delete('events');

	        if($reported_event){
				//delete reported content
				$this->db->where('id', $id);
				return $this->db->delete('reported_content');
			}
			else{
				return 1;
			}
		}


		/* 
		  Function: deleteEventComment()
		  Description:  delete  event's comment and comments replies 
		
		*/	

		public function deleteEventComment($data){
			//echo"<pre>";
			//print_r($data);die;
			$id = $data['reportedcontent_id'];

			$results =  $this->db->query('SELECT * FROM reported_content WHERE id='.$id)->row();
			$commentid=$results->report_id;

			//$eventid   = $data['event_id'];
			$records   = $this->db->query('SELECT * FROM event_comment WHERE id='.$commentid.' OR comment_id='.$commentid)->result();
			//print_r($records);die;
			if(count($records) > 0){
				foreach($records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('event_comment');
				}
				
				//delete reported content
			$this->db->where('id', $id);
			return $this->db->delete('reported_content');
			}
		}

		#----------------------Media List Pagination------------------------------#
    public function count_event_list() {
        $query = $this->_event_list(NULL);
        return $result = $this->db->query($query)->num_rows();
    }
    /*  Show all User  */
    public function eventList($show_list = 'show_list') {
        $query = $this->_event_list($show_list);
        return $result = $this->db->query($query)->result();
    }
    
    var $column_order = array('e.id');
    var $column_search = array('b.first_name', 'b.last_name', 'e.long_description','e.event_planning');

    private function _event_list($param = NULL) {
    	$sql = $sql_custom = array();
    	$f_sql = $f_sql_custom = $custom_where = '';
    	foreach ($this->column_search as $cmn) {
    		if (isset($_POST["search"]["value"]) && $_POST["search"]["value"] != '') {
    			$sql[] = '(' . $cmn . ' LIKE ' . "'%" . $_POST["search"]["value"] . "%'" . ')';
    		}
    	}
    	$order_by = 'e.created_at DESC';
    	if (isset($_POST['order'])) {
    		$order_by = $this->column_order[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
    	}
    	if (sizeof($sql) > 0) {
    		$f_sql = '(' . implode(' OR ', $sql) . ')';
    	}
    	if (sizeof($sql_custom) > 0) {
    		$f_sql_custom = implode(' AND ', $sql_custom);
    	}
    	if (!empty($f_sql) && !empty($f_sql_custom)) {
    		$custom_where = $f_sql . ' AND ' . $f_sql_custom;
    	} else if (!empty($f_sql)) {
    		$custom_where = $f_sql;
    	} else if (!empty($f_sql_custom)) {
    		$custom_where = $f_sql_custom;
    	}
    	if ($param == 'show_list' && isset($_POST["length"]) && $_POST["length"] != - 1) {
    		$limit  = $_POST['length'];
    		$offset = $_POST['start'];

    		$this->db->select("e.id AS data_item_id, e.user_id AS data_userid, e.long_description AS data_content, e.event_privacy AS data_privacy, e.share_count AS data_sharecount, e.invites AS data_invites,  e.sponsered AS data_sponsered, ec.name AS data_category_name, e.created_at AS data_created_at,  e.updated_at AS data_updated_at, e.image_path AS data_image, e.image_id AS data_video, e.start_date AS data_startdate, e.end_date AS data_enddate, e.event_tags AS data_tags, e.timezone_id AS data_item_type, e.event_planning AS data_planing, e.event_country AS data_country, e.event_street AS data_street, e.event_city AS data_city, e.event_category AS data_category, e.price AS data_price, b.first_name AS data_f_name, b.last_name AS data_l_name, b.gender AS data_gender, c.image AS data_userimage, c.thumb_image AS data_thumb_image,e.status as status");

    		$this->db->from('events e');
    		$this->db->join('event_category ec', 'ec.id = e.event_category', 'LEFT');
    		$this->db->join('users b', 'b.id = e.user_id', 'LEFT');
    		$this->db->join('users_profile c', 'b.id = c.user_id', 'LEFT');
    		if (!empty($custom_where)) {
    			$this->db->where($custom_where);
    		}
    		$this->db->order_by($order_by);
    		$this->db->limit($limit, $offset);
    		$this->db->get();
    		return $query1 = $this->db->last_query();
    	} else {
    		$this->db->select("e.id AS data_item_id, e.user_id AS data_userid, e.long_description AS data_content, e.event_privacy AS data_privacy, e.share_count AS data_sharecount, e.invites AS data_invites,  e.sponsered AS data_sponsered, ec.name AS data_category_name, e.created_at AS data_created_at,  e.updated_at AS data_updated_at, e.image_path AS data_image, e.image_id AS data_video, e.start_date AS data_startdate, e.end_date AS data_enddate, e.event_tags AS data_tags, e.timezone_id AS data_item_type, e.event_planning AS data_planing, e.event_country AS data_country, e.event_street AS data_street, e.event_city AS data_city, e.event_category AS data_category, e.price AS data_price, b.first_name AS data_f_name, b.last_name AS data_l_name, b.gender AS data_gender, c.image AS data_userimage, c.thumb_image AS data_thumb_image,e.status as status");
    		$this->db->from('events e');
    		$this->db->join('event_category ec', 'ec.id = e.event_category', 'LEFT');
    		$this->db->join('users b', 'b.id = e.user_id', 'LEFT');
    		$this->db->join('users_profile c', 'b.id = c.user_id', 'LEFT');
    		if (!empty($custom_where)) {
    			$this->db->where($custom_where);
    		}
    		$this->db->order_by($order_by);
    		$this->db->get();
    		return $query1 = $this->db->last_query();
    	}
    }
    public function get_single_event_details($id=null){

    	$query = $this->db->query("select e.*,tz.short_name,tz.timezone_Offset,ec.id AS ec_id,ec.name,ec.status,b.first_name AS pu_f_name, b.last_name AS pu_l_name, b.gender AS pu_gender,c.image AS pu_image,c.thumb_image AS pu_thumb_image FROM events as e LEFT JOIN event_category as ec ON e.event_category = ec.id LEFT JOIN timezone_list tz ON tz.id = e.timezone_id   LEFT JOIN users AS b ON b.id = e.user_id LEFT JOIN users_profile c ON b.id = c.user_id where e.id='$id'");

    	$result = $query->row();
    	$data = array();
    	$data[] = $result;
    	$data['comment'] = $this->get_comments('events', $result->id);

    	return $data;
    }

    public function get_comments($module = null, $id = null){
        $table = $this->getCommentTable($module);
        $this->db->select('b.comment AS comment_text, b.raw_comment AS edit_comment, b.id AS comment_id,b.comment_id AS parent_id,b.user_id AS c_user_id,c.first_name AS cu_f_name,c.last_name AS cu_l_name,c.id AS cu_userid,c.gender AS cu_gender,d.image AS cu_image,d.thumb_image AS cu_thumb_image, c.ip_address');
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
        $this->db->select('b.comment AS comment_text, b.raw_comment AS edit_comment, b.id AS comment_id,b.comment_id AS parent_id,b.user_id AS c_user_id,c.first_name AS cu_f_name,c.last_name AS cu_l_name,c.id AS cu_userid,c.gender AS cu_gender,d.image AS cu_image,d.thumb_image AS cu_thumb_image, c.ip_address');
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
        $c_type  = $this->config->item('ss_content_type', 'user_permission');
        if(count($c_type) > 0){
            foreach($c_type as $key => $value){
                if($type == $value){
                    return $key;
                }
            }
        }
    }
}
?>
