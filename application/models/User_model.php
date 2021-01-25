<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Name:  User Model
*
*
* Created:  18.09.2017
*
*
* Requirements: PHP5 or above
*
*/

class User_model extends CI_Model
{
	/**
	 * Holds name of tables used
	 *
	 * @var string
	 **/
	protected $tables;

	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		// initialize db tables data
		$this->tables  = 'users';
	}

	/**
	 * Get All admin Users stored in the database.
	 *
	 * @return object
	 * 
	 **/
	 
	public function get_AdminUsers()
	{
		$this->db->from($this->tables);
		$this->db->select("*");
		$this->db->where('role_id != 5');
		$query = $this->db->get();        
		return $query->result();
	}

	/**
	 * Get All site Users stored in the database.
	 * 
	 *
	 * @return object
	 *
	 **/
	 
	/*public function get_SiteUsers()
	{
		$this->db->from($this->tables);
		$this->db->select("*");
		$this->db->where('role_id = 5');
		$query = $this->db->get();        
		return $query->result();
	}*/
	
	/**
	* removeUserFromContact()
	* Remove User From Contact
	*/

	public function removeUserFromContactFriends($remove_id){
		$userid = $this->session->userdata('user_id');
		$result = $this->db->query('SELECT * FROM user_contacts WHERE user_id='.$remove_id.' OR contact_id='.$remove_id)->row();	
		if($result){
			$this->db->where('id', $result->id);
			$result = $this->db->delete('user_contacts');
		}
		$result = $this->db->query('SELECT * FROM user_contact_request WHERE user_id='.$remove_id.' OR contact_id='.$remove_id)->row();	
		if($result){
			$this->db->where('id', $result->id);
			$result = $this->db->delete('user_contact_request');
		}
		$result = $this->db->query('SELECT * FROM user_friends WHERE user_id='.$remove_id.' OR friend_id='.$remove_id)->row();	
		if($result){
			$this->db->where('id', $result->id);
			$result = $this->db->delete('user_friends');
		}
	}

    /** Khushali : 2020_01_06 3:49 PM **/
    public function get_SiteUsers()
	{
		$this->db->from('users as u');
		$this->db->select("u.*, b.id as is_block");
                $this->db->join('blocked_ip b', 'b.ip_address = u.ip_address', 'LEFT');
		$this->db->where('u.role_id = 5');
//                $this->db->order_by('u.id' , 'DESC');
		$query = $this->db->get();        
		return $query->result();
	}
    public function get_DuplicateSiteUsers()
	{
        $query = $this->db->query("
                    SELECT u.id, u.ip_address, u.first_name, u.last_name, u.created_at , r.name as role, b.id as is_block
                    FROM users as u 
                    LEFT JOIN tbl_role as r ON r.id = u.role_id
                    LEFT JOIN blocked_ip as b ON b.ip_address = u.ip_address
                    WHERE u.ip_address IN (SELECT ip_address FROM `users` GROUP BY ip_address HAVING COUNT(ip_address)>1) 
                    ORDER BY u.ip_address
                ");        
		return $query->result();
	}

    public function insert_block_ip($data)
    {
        $this->db->insert('blocked_ip', $data);
        return $this->db->insert_id();
    }

    public function get_users_post($userId)
	{
                $this->db->select("id, content, created_at, status");
                $this->db->from("post");
                $this->db->where('user_id', $userId);
		$this->db->order_by('id', 'DESC');
                $query = $this->db->get();
                if($query->num_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }
	}

        public function get_users_gpost($userId)
	{
                $this->db->select("gp.id, gp.content, gp.created_at, gp.status, gp.post_type, g.name as group");
                $this->db->from("group_posts as gp");
                $this->db->join('groups g', 'g.id = gp.user_id_written_on', 'LEFT');
                $this->db->where('gp.user_id', $userId);
		$this->db->order_by('gp.id', 'DESC');
                $query = $this->db->get();
                if($query->num_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }
	}

        public function get_users_ppost($userId)
	{
                $this->db->select("pp.id, pp.content, pp.created_at, pp.status, pp.post_type, p.name as page");
                $this->db->from("page_posts as pp");
                $this->db->join('pages p', 'p.id = pp.user_id_written_on', 'LEFT');
                $this->db->where('pp.user_id', $userId);
		$this->db->order_by('pp.id', 'DESC');
                $query = $this->db->get();
                if($query->num_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }
	}

        public function get_users_epost($userId)
	{
                $this->db->select("e.id, e.event_planning AS data_planing, e.created_at, e.status");
                $this->db->from("events as e");
//                $this->db->join('pages p', 'p.id = pp.user_id_written_on', 'LEFT');
                $this->db->where('e.user_id', $userId);
		$this->db->order_by('e.id', 'DESC');
                $query = $this->db->get();
                if($query->num_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }
	}

        public function get_users_mpost($userId)
	{
                $this->db->select("v.id, v.title, v.created_at, v.status");
                $this->db->from("media_video as v");
//                $this->db->join('pages p', 'p.id = pp.user_id_written_on', 'LEFT');
                $this->db->where('v.user_id', $userId);
		$this->db->order_by('v.id', 'DESC');
                $query = $this->db->get();
                if($query->num_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }
	}

        public function get_users_wpost($userId)
	{
                $this->db->select("w.id, w.title, w.created_at, w.status, a.name");
                $this->db->from("wall_arts as w");
                $this->db->join('art_wall a', 'a.id = w.wall_id', 'LEFT');
                $this->db->where('w.user_id', $userId);
		$this->db->order_by('w.id', 'DESC');
                $query = $this->db->get();
                if($query->num_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }
	}

        public function delete($where, $table){
            $this->db->where($where);
            $result = $this->db->delete($table);
            return $result;
        }

        public function getAllRecords($table, $where = '', $column = '*', $orderColumn = '', $order = ''){
            $this->db->select($column);
            if($where){
                $this->db->where($where);
            }
            $this->db->from($table);
            if($order && $orderColumn){
                $this->db->order_by($orderColumn, $order);
            }
            $query = $this->db->get();
            if($query->num_rows() > 0) {
               return $query->result();
            } else {
                return false;
            }
        }

        public function getRowById($where, $table){
            $this->db->select('*');
            $this->db->where($where);
            $this->db->from($table);
            $query = $this->db->get();
            if($query->num_rows() > 0) {
               return $query->row();
            } else {
                return false;
            }
        }

        public function getFromSQL($sql, $returnType = 'result'){
            $query = $this->db->query($sql);
            if($query->num_rows() > 0) {
                if($returnType == 'row') {
                    return $query->row();
                } else {
                    return $query->result();
                }
            } else {
                return false;
            }
        }

        public function checkUserPostOwner($post_id){

                $user_id = $this->session->userdata('user_id');

                $get_record  = $this->db->query('SELECT * FROM post WHERE id='.$post_id.' AND (user_id='.$user_id.' OR user_id_written_on='.$user_id.')')->row();

                //echo"<pre>"; print_r(count($get_record)); print_r($get_record); die;



                if(count($get_record) > 0){

                        return true;

                }else{

                        return false;

                }	

        }

    /** Khushali : 2020_01_06 3:49 PM **/


}
