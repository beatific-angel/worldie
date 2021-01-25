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

		public function deleteEvent($id = null){
			$results =  $this->db->query('SELECT * FROM reported_content WHERE id='.$id)->row();
			$event_id=$results->report_id;
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

				//delete reported content
			$this->db->where('id', $id);
			return $this->db->delete('reported_content');
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
		

	}

?>
