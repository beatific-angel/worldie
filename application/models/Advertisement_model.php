<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Advertisement_model extends CI_Model
    {
       
        public function __construct()
        {
            parent::__construct();
			$this->load->database();
			$this->table_name = 'advertisement';
			$this->load->model('image_model');
        }

		 public function getAdvertisementData(){
			
			$this->db->from($this->table_name);
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
		
		public function getAdvertisementById($id = null){
			$this->db->select('*');
			$this->db->from("$this->table_name a");
			$this->db->join('users u', 'a.user_id = u.id');
			$this->db->where('a.id', $id);  // Also mention table name here
			$query = $this->db->get();    
			if($query->num_rows() > 0){		
				$result=$query->result();
				if($result[0]->image_url != ""){
					$image = $this->image_model->getAdvertisementImageURL($result[0]->image_url);
					$result[0]->image_url = $image;
				}
				return $result;
			}
        }
			
		
		public function UpdateStatus($data = null){
			
			$tablname = $this->table_name;
			$user_id = $this->session->userdata('user_id');
			
			$savedata = array(
				'updated_by' => $user_id,
				'updated_at' => date('Y-m-d H:i:s'),
				'status'     => $data['new_status']
			);
			
			$this->db->where('id', $data['id']);
            $result = $this->db->update($tablname, $savedata);
            //echo $this->db->last_query();
			if($result){
		        return true;
			}else{
				return false;
			}
            
        }
		
		public function DeleteAdvertisement($id = null){
			$this->db->where('id', $id);
            $result = $this->db->delete($this->table_name);
			if($result){
		        return true;
			}else{
				return false;
			}
        }
		
		
		/*public function getShortName($cid = null){
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
            
        }*/
    
    }
?>