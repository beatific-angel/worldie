<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Category_model extends CI_Model
    {
       
        public function __construct()
        {
            parent::__construct();
			$this->load->database();
        }

		 public function getCategoryData($type = null){
			
			$tablname = $this->getTableName($type);
			//print_R($tablname);
			$this->db->from($tablname);
			$this->db->order_by("id", "asc");
            $q = $this->db->get();
			if($q->num_rows() > 0){
				foreach ($q->result() as $row)
				{
					$data[] = $row;
				}
			    return $data;
			}
        }
		
		public function getCategoryDataByOrder($type = null){
			
			$tablname = $this->getTableName($type);
			$this->db->from($tablname);
			$this->db->where("status", 1); 
			$this->db->order_by("display_order", "asc");
            $q = $this->db->get();
			if($q->num_rows() > 0){
				foreach ($q->result() as $row)
				{
					$data[] = $row;
				}
			    return $data;
			}
        }
		
        /*public function getShopCategory(){
			$this->db->from('shop_product_category');
			$this->db->order_by("id", "asc");
            $q = $this->db->get();
			if($q->num_rows() > 0){
				foreach ($q->result() as $row)
				{
					$data[] = $row;
				}
			    return $data;
			}
        }
		
		public function getArtCategory(){
			$this->db->from('art_gallery_category');
			$this->db->order_by("id", "asc");
            $q = $this->db->get();
			if($q->num_rows() > 0){
				foreach ($q->result() as $row)
				{
					$data[] = $row;
				}
			    return $data;
			}
        }
		
		public function getEventCategory(){
			$this->db->from('event_category');
			$this->db->order_by("id", "asc");
            $q = $this->db->get();
			if($q->num_rows() > 0){
				foreach ($q->result() as $row)
				{
					$data[] = $row;
				}
			    return $data;
			}
        }
		
		public function getMediaCategory(){
			$this->db->from('media_gallery_category');
			$this->db->order_by("id", "asc");
            $q = $this->db->get();
			if($q->num_rows() > 0){
				foreach ($q->result() as $row)
				{
					$data[] = $row;
				}
			    return $data;
			}
        }
		
		public function getMeetDateCategory(){
			$this->db->from('meet_date_category');
			$this->db->order_by("id", "asc");
            $q = $this->db->get();
			if($q->num_rows() > 0){
				foreach ($q->result() as $row)
				{
					$data[] = $row;
				}
			    return $data;
			}
        }*/
		
		/***************************************Create slug from name**********************************/
		//function sanitize_title_with_dashes 
		public function sanitize($title) {
			 $title = strip_tags($title);
		    // Preserve escaped octets.
		    $title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
		    // Remove percent signs that are not part of an octet.
		    $title = str_replace('%', '', $title);
		    // Restore octets.
		    $title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);
	
		    if ($this->seems_utf8($title)) {
		        if (function_exists('mb_strtolower')) {
		            $title = mb_strtolower($title, 'UTF-8');
		        }
		        $title = $this->utf8_uri_encode($title);
		    }

		    $title = strtolower($title);
		    $title = preg_replace('/&.+?;/', '', $title); // kill entities
		    $title = str_replace('.', '-', $title);
		    $title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
		    $title = preg_replace('/\s+/', '-', $title);
		    $title = preg_replace('|-+|', '-', $title);
		    $title = trim($title, '-');

		    return $title;
		}
		
				Public function seems_utf8($str) {
		    $length = strlen($str);
		    for ($i=0; $i < $length; $i++) {
		        $c = ord($str[$i]);
		        if ($c < 0x80) $n = 0; # 0bbbbbbb
		        elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
		        elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
		        elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
		        elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
		        elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
		        else return false; # Does not match any model
		        for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
		            if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
		                return false;
		        }
		    }
		    return true;
		}

		
		Public function utf8_uri_encode( $utf8_string, $length = 0 ) {
		    $unicode = '';
		    $values = array();
		    $num_octets = 1;
		    $unicode_length = 0;

		    $string_length = strlen( $utf8_string );
		    for ($i = 0; $i < $string_length; $i++ ) {

		        $value = ord( $utf8_string[ $i ] );

		        if ( $value < 128 ) {
		            if ( $length && ( $unicode_length >= $length ) )
		                break;
		            $unicode .= chr($value);
		            $unicode_length++;
		        } else {
		            if ( count( $values ) == 0 ) $num_octets = ( $value < 224 ) ? 2 : 3;

		            $values[] = $value;

		            if ( $length && ( $unicode_length + ($num_octets * 3) ) > $length )
		                break;
		            if ( count( $values ) == $num_octets ) {
		                if ($num_octets == 3) {
		                    $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
		                    $unicode_length += 9;
		                } else {
		                    $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
		                    $unicode_length += 6;
		                }

		                $values = array();
		                $num_octets = 1;
		            }
		        }
		    }

		    return $unicode;
		}

		
		/***************************************Create slug from name End**********************************/
		
		public function getCategoryById($id = null, $type = null){
			$tablname = $this->getTableName($type);
			$this->db->select('*');
			$this->db->from($tablname);
			$this->db->where('id', $id);  // Also mention table name here
			$query = $this->db->get();    
			if($query->num_rows() > 0)
				return $query->result();
        }
		
		public function CreateCategory($data = null){
			
			$tablname = $this->getTableName($data['type']);
			$user_id = $this->session->userdata('user_id');
			$savedata = array(
				'name'       => $data['name'],
				'slug' 		 => $this->sanitize($data['name']),
				'description' =>$data['description'],
				'created_by' => $user_id,
				'updated_by' => $user_id,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
				'status'     => $data['status']
			);
			
			$result = $this->db->insert($tablname, $savedata);
			if($result){
		        return true;
			}else{
				return false;
			}
            
        }
		
		public function UpdateCategory($data = null){
			$tablname = $this->getTableName($data['type']);
			$user_id = $this->session->userdata('user_id');
			$savedata = array(
				'name'       => $data['name'],
				'slug' 		 => $this->sanitize($data['name']),
				'description' =>$data['description'],
				'updated_by' => $user_id,
				'updated_at' => date('Y-m-d H:i:s'),
				'status'     => $data['status']
			);
		
			$this->db->where('id', $data['id']);
            $result = $this->db->update($tablname, $savedata);
			if($result){
		        return true;
			}else{
				return false;
			}
            
        }
		
		public function saveCategoryOrderData($data = null){
			$tablname = $this->getTableName($data['type']);
			$user_id = $this->session->userdata('user_id');
			$data=json_decode($_POST['data']);
			$counter=1;
			foreach($data as $key=>$val)
			{
				$savedata = array(
					'display_order' =>$counter,
					'updated_by' => $user_id,
					'updated_at' => date('Y-m-d H:i:s')
				);
				$this->db->where('id', $val);
				$result = $this->db->update($tablname, $savedata);
				$counter++;
			}
            
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
		
		public function DeleteCategory($type = null, $id = null){
			$tablname = $this->getTableName($type);
			$this->db->where('id', $id);
            $result = $this->db->delete($tablname);
			if($result){
		        return true;
			}else{
				return false;
			}
        }
		
		public function getTableName($type = null){
			switch ($type) {
				case "shop":
					return "store_product_category";
					break;
				case "store":
					return "store_category";
					break;	
				case "media":
					return "media_video_category";
					break;

				case "media_channel":
					return "media_channel_category";
					break;
				case "art":
					return "wall_arts_category";
					break;
				case "art_wall":
					return "art_wall_category";
					break;	
				case "event":
					return "event_category";
					break;
				case "meet_date":
					return "meet_date_category";
					break;	
				default:
					break;
			}
		}
		
		public function getShortName($cid = null){
			$this->db->from('taxonomies');
			$this->db->select('shortName');
			$this->db->where('cID', $cid);
			$this->db->order_by('shortName', 'asc');
            $this->db->group_by('shortName');
            $query = $this->db->get();
			if($query->num_rows() > 0){
			   foreach ($query->result() as $row)
				{
					$data[] = $row;
				}
			    return $data;
			}
            
        }
    
    }
?>