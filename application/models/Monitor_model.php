<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitor_model extends CI_Model
{
    protected $tables;

    public function __construct()
	{
		parent::__construct();
		$this->load->database();
		// initialize db tables data
		$this->tables  = 'blocked_text';
    }
    
    public function getBlockedText(){

		$this->db->select('*');

		$this->db->from('blocked_text');

		$query = $this->db->get();

		return $query->result();

	}
}