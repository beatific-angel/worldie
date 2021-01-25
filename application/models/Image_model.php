<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	
	class Image_model extends CI_Model {

		public $table_name;
		public $asset_location;
		public $album_image_location;
		public function __construct(){
			parent::__construct();
			$this->load->database();
			$this->table_name = 'image';
			$this->asset_location = $this->config->item('base_url_front'); 
			$this->album_image_location = 'assets/member_album_items/';
		}
		
		
		public function imageExists($imageid = null, $moduletype = null) {
			$type = string_strtolower($this->type);
			$tmpFilename = $this->IMAGE_DIR."/".$this->prefix."photo_".$this->id.".".$type;
			return (file_exists($tmpFilename) && is_readable($tmpFilename));
		}

		function getURL($imageid = null, $moduletype = null)
		{
			$this->db->from($this->table_name);
			$this->db->where('id', $imageid);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				$result = $query->result();
				if($moduletype == 'post'){
					$type = mb_strtolower($result[0]->type);
					$tmpFilename = $this->asset_location.'assets/member_images/post_image/'.$result[0]->prefix."image_".$result[0]->id.".".$type;
					return $tmpFilename;
				}
				
			}
			//$data[$value] = $count;
			//$type = string_strtolower($this->type);
			
		}

		function getAdvertisementImageURL($image_url= null)
		{
	
			$tmpFilename = $this->asset_location.$image_url;
			return $tmpFilename;
						
		}
		
		function getEventImageURL($image = null)
		{	
			if($image != ''){
				$tmpFilename = $this->asset_location.$image;
				return $tmpFilename;
			}
		}
		
		function getAlbumItems($albumid = null)
		{
			$user_album_query = $this->db->query('SELECT * FROM user_album_items WHERE album_id='.$albumid);
			$album_items = $user_album_query->result();
			//$album_items->album_item_count = count($album_items);
			if(count($album_items) > 0){
				foreach($album_items as $album_item){
					$album_item->image_url = $this->asset_location.$this->album_image_location.$album_item->user_id.'/'.$album_item->image_name;
				}
			}
			return $album_items;
		}


		public function deleteEventComment($data){
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
