<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class SponseredContent_model extends CI_Model
    {
        public $table_name;
		
        public function __construct()
        {
            parent::__construct();
			$this->load->database();
			$this->table_name = 'sponsered_items';
			$this->config->load('user_permission', TRUE);
        }

        public function getSponseredContent(){
			
			$content_types = $this->config->item('sponsered_content_type', 'user_permission');
			$data = [];
			foreach ($content_types as $key => $value)
			{ 
			    $this->db->from($this->table_name);
				$this->db->where('content_type', $key);
				$query = $this->db->get();
				$count = $query->num_rows();
				$data[$value] = $count;
			}
			return $data;
			
        }
		
		public function getSponseredItems($item = null){
			
			$content_types = $this->config->item('content_type', 'user_permission');
			$key = array_search($item, $content_types);
			
			$this->db->from($this->table_name);
			$this->db->where('content_type', $key);
			$q = $this->db->get();
			if($q->num_rows() > 0){
				foreach ($q->result() as $row)
				{
					$data[] = $row;
				}
			    return $data;
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
		
    }
?>