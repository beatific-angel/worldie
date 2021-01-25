<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Imagecomment_model extends CI_Model {
		
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
			/*$this->table_name  = 'events';
			$this->table_image = 'image';
			$this->event_image_location = 'assets/events_images/';
			$this->image_max_size       = 2000; //Maximum image size (height and width)*/
            $this->quality              = 90; //jpeg quality

		}

		
		/* 
		  Function: deleteEventComment()
		  Description:  delete  event's comment and comments replies 
		
		*/	

		public function deleteComment($data){
			//echo"<pre>";
			//print_r($data);die;
			$id = $data['reportedcontent_id'];

			$results =  $this->db->query('SELECT * FROM reported_content WHERE id='.$id)->row();
			$commentid=$results->report_id;

			//$eventid   = $data['event_id'];
			$records   = $this->db->query('SELECT * FROM image_comment WHERE id='.$commentid.' OR comment_id='.$commentid)->result();
			//print_r($records);die;
			if(count($records) > 0){
				foreach($records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('image_comment');
				}
				
				//delete reported content
			$this->db->where('id', $id);
			return $this->db->delete('reported_content');
			}
			
		}


		public function deleteImageComment($data){
			//echo"<pre>";
			//print_r($data);die;
			$id = $data['reportedcontent_id'];

			$results =  $this->db->query('SELECT * FROM reported_content WHERE id='.$id)->row();
			$commentid=$results->report_id;

			//$eventid   = $data['event_id'];
			$records   = $this->db->query('SELECT * FROM image_comment WHERE id='.$commentid.' OR comment_id='.$commentid)->result();
			//print_r($records);die;
			if(count($records) > 0){
				foreach($records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('image_comment');
				}
				
				//delete reported content
			$this->db->where('id', $id);
			return $this->db->delete('reported_content');
			}
			
		}
		

	}

?>
