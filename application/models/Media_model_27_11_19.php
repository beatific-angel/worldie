<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	
	class Media_model extends CI_Model {

		public $table_name;
		
		public function __construct(){
			parent::__construct();
			$this->load->database();
			$this->table_name = 'media_channel';
		}

		/**
		 * deleteVideo()
		 * Delete Media Channel Video
		 */

		public function deleteVideo($id = null){
			$results =  $this->db->query('SELECT * FROM reported_content WHERE id='.$id)->row();
			$videoid=$results->report_id;
			
			//delete video comment
			$c_records  = $this->db->query('SELECT * FROM media_video_comment WHERE element_id='.$videoid)->result();
			if(count($c_records) > 0){
				foreach($c_records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('media_video_comment');
				}
			}

			//delete video view
			$v_records  = $this->db->query('SELECT * FROM media_video_views WHERE video_id='.$videoid)->result();
			if(count($v_records) > 0){
				foreach($v_records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('media_video_views');
				}
			}

			//delete video like
			$l_records  = $this->db->query('SELECT * FROM media_video_like WHERE video_id='.$videoid)->result();
			if(count($l_records) > 0){
				foreach($l_records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('media_video_like');
				}
			}

			//delete video wall share
			$w_records  = $this->db->query('SELECT * FROM wall_shared_post WHERE post_id='.$videoid.' AND content_type=3')->result();
			if(count($w_records) > 0){
				foreach($w_records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('wall_shared_post');
				}
			}

			//delete video from playlist share
			$p_records  = $this->db->query('SELECT * FROM media_user_play_list_item WHERE video_id='.$videoid)->result();
			if(count($p_records) > 0){
				foreach($p_records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('media_user_play_list_item');
				}
			}
			//delete video
			$get_record  = $this->db->query('SELECT * FROM media_video WHERE id='.$videoid)->row();
			$this->db->where('id', $get_record->id);
            return $this->db->delete('media_video');
		}

		
		/**
		 * deleteVideoComment()
		 * Delete Video Comment
		 */

		public function deleteVideoComment($id = null){
			$results   = $this->db->query('SELECT * FROM reported_content WHERE id='.$id)->row();
			$commentid = $results->report_id;
			$this->db->where('id', $commentid);
            return $this->db->delete('media_video_comment');
		}

	}

?>
