<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	
	class Art_model extends CI_Model {

		public $table_name;
		
		public function __construct(){
			parent::__construct();
			$this->load->database();
			$this->table_name = 'art_wall';
		}

		/**
		 * deleteArtComment()
		 * Delete Art Comment
		 */

		public function deleteArtComment($data){
			$id = $data['reportedcontent_id'];
			$results =  $this->db->query('SELECT * FROM reported_content WHERE id='.$id)->row();
			$commentid=$results->report_id;
			$records  = $this->db->query('SELECT * FROM wall_arts_comment WHERE id='.$commentid.' OR comment_id='.$commentid)->result();
			if(count($records) > 0){
				foreach($records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('wall_arts_comment');
				}
			}
			//delete reported content
			$this->db->where('id', $id);
			return $this->db->delete('reported_content');	
		}

		/**
		 * deleteArtWallArts()
		 * Delete Art Wall Art
		 */

		public function deleteArtWallArts($data = null){
			$id = $data['reportedcontent_id'];
			$results = $this->db->query('SELECT * FROM reported_content WHERE id='.$id)->row();
			$artid   = $results->report_id;
			$user_id = $this->session->userdata('user_id');
			//delete arts comment
			$c_records  = $this->db->query('SELECT * FROM wall_arts_comment WHERE element_id='.$artid)->result();
			if(count($c_records) > 0){
				foreach($c_records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('wall_arts_comment');
				}
			}
			//delete arts view
			$v_records  = $this->db->query('SELECT * FROM wall_arts_views WHERE art_id='.$artid)->result();
			if(count($v_records) > 0){
				foreach($v_records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('wall_arts_views');
				}
			}
			//delete art like
			$l_records  = $this->db->query('SELECT * FROM wall_art_like WHERE art_id='.$artid)->result();
			if(count($l_records) > 0){
				foreach($l_records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('wall_art_like');
				}
			}
			//delete art wall share
			$w_records  = $this->db->query('SELECT * FROM wall_shared_post WHERE post_id='.$artid.' AND content_type=4')->result();
			if(count($w_records) > 0){
				foreach($w_records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('wall_shared_post');
				}
			}

			//delete art
			$get_record  = $this->db->query('SELECT * FROM wall_arts WHERE id='.$artid)->row();
			if (file_exists($get_record->image_location)){
			    unlink($get_record->image_location); 
			}
			$this->db->where('id', $get_record->id);
            return $this->db->delete('wall_arts');
		}

	}

?>
